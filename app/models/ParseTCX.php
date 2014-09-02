<?PHP

class ParseTCX
{
	protected $tcxFile;
	
	public function __construct()
	{
	}

	public function saveActivityXML($tcxFile)
	{
		$reader = new XMLReader();
		try
		{
			if (! $reader->open($tcxFile))
				return FALSE;
		}
		catch (Exception $e)
		{
			Log::debug('Exception thrown while reading tcxFile: ' . $e);
			return FALSE;
		}
		
		$uploadParsing = UploadParsing::create(array(
			'completeActivitiesCount' => 0,
			'allActivitiesInDb' => 'n',
		));
		Auth::user()->uploadParsing()->save($uploadParsing);
		$uploadParsing_id = $uploadParsing->id;
		
		$activityCount = 0;
		while ($reader->read())
		{
			if ($reader->nodeType == XMLReader::ELEMENT and $reader->name == 'Activity')
			{
				$activityCount ++;
				$activityXML = $reader->readOuterXML();
				if (empty($activityXML))
					return FALSE;

				$unsavedActivity = UnsavedActivity::create(array(
							'activityXML' => $activityXML,
							'uploadParsing_id' => $uploadParsing_id,
							));

				Auth::user()->unsavedActivitys()->save($unsavedActivity);

				$data = array('user_id' => Auth::id(), 'unsavedActivity_id' => $unsavedActivity->id);
				Queue::push('ParseTCX', $data);
			}
		}

		$uploadParsing->allActivitiesInDb = 'y';
		$uploadParsing->totalActivitiesCount = $activityCount;
		$uploadParsing->save();

		return TRUE;
	}

	public function fire($job, $data)
	{
		$unsavedActivity = UnsavedActivity::find($data['unsavedActivity_id']);
		if (! $unsavedActivity)
		{
			// notifications? for now just delete job and ignore
			Log::info('Bad activityID in parse job:' . $data['unsavedActivity_id']);
			$job->delete();
			return ;
		}
		$unsavedActivity->delete();

		$user = User::find($data['user_id']);
		if (! $user)
		{
			Log::info('Bad user in parse job: userID:' . $data['user_id'] . '; activityID:'. $data['unsavedActivity_id']);
			$job->delete();
			return;
		}
		
		if (TRUE !== $this->parseActivityXMLForUser($unsavedActivity, $user))
		{
			Log::info('Parsing of activity failed: user_id: ' . $data['user_id'] . '; ' . $data['unsavedActivity_id']);
			$job->delete();
			return;
		}
		$job->delete();
		$unsavedActivity->forceDelete();
		return;
	}
	
	private function parseActivityXMLForUser($unsavedActivityEntry, $userEntry)
	{

		$activityXML = new SimpleXMLElement($unsavedActivityEntry->activityXML);
		
		$activityEntry = new Activity;
		$activityEntry->user_id = $userEntry->id;
		$activityEntry->activityID = $activityXML->Id;
		$activityEntry->sport = $activityXML['Sport'];
		
		$activityEntry->save();
		$activityID = $activityEntry->id;

		foreach ($activityXML->Lap as $lap)
		{
			$lapEntry = new Lap;
			$lapEntry->activity_id = $activityID;
			$lapEntry->startTime = $lap['StartTime'];
			$lapEntry->totalTimeSeconds = $lap->TotalTimeSeconds;
			$lapEntry->distanceMeters = $lap->DistanceMeters;
			$lapEntry->maximumSpeed = $lap->MaximumSpeed;
			$lapEntry->calories = $lap->Calories;
			$lapEntry->averageHeartRateBpm = $lap->AverageHeartRateBpm->Value;
			$lapEntry->maximumHeartRateBpm = $lap->MaximumHeartRateBpm->Value;
			$lapEntry->intensity = $lap->Intensity;
			$lapEntry->TriggerMethod = $lap->TriggerMethod;
			$lapEntry->save();
			$lapID = $lapEntry->id;

			foreach ($lap->Track as $track)
			{
				foreach ($track->Trackpoint as $trackpoint)
				{
					$trackpointEntry = new Trackpoint;
					$trackpointEntry->time  = $trackpoint->Time ;
					$trackpointEntry->latitudeDegrees  = $trackpoint->Position->LatitudeDegrees ;
					$trackpointEntry->longitudeDegrees  = $trackpoint->Position->LongitudeDegrees ;
					$trackpointEntry->altitudeMeters  = $trackpoint->AltitudeMeters ;
					$trackpointEntry->distanceMeters  = $trackpoint->DistanceMeters ;
					$trackpointEntry->heartRateBpm  = $trackpoint->HeartRateBpm ;
					$trackpointEntry->lap_id = $lapID;
					$trackpointEntry->save();
				}
			}
		}
		
		$this->saveParsedExtras($activityID);

		$uploadParsingEntry = UploadParsing::find($unsavedActivityEntry->uploadParsing_id);
		$uploadParsingEntry->completedActivitiesCount++;
		
		if ($uploadParsingEntry->completedActivitiesCount == $uploadParsingEntry->totalActivitiesCount)
			$uploadParsingEntry->delete();
		else
			$uploadParsingEntry->save();

		return TRUE;
	}

	protected function saveParsedExtras($activity_id)
	{
		// likely to be MEAN on memory
		ini_set('memory_limit', '2G');
		DB::connection()->disableQueryLog();

		$activity = Activity::find($activity_id);

		$leafletJSLatLongArray = '';
		$jsHRArray = '';
		$jsAltArray = '';
		$jsCadenceArray = '';
		$leafletCount = 0;
		$tmpFileHandle = tmpfile();
		ob_start();
		foreach ($activity->laps as $lap)
		{
			foreach ($lap->trackpoints as $trackpoint)
			{
				if ($trackpoint->latitudeDegrees and $trackpoint->longitudeDegrees)
				{
					fwrite($tmpFileHandle, 'var l'.$leafletCount.' = L.latLng(');
					fwrite($tmpFileHandle, $trackpoint->latitudeDegrees . ',');
					fwrite($tmpFileHandle, $trackpoint->longitudeDegrees . ');');
					$leafletCount++;
				}
			}
		}
		unset($activity);
		unset($lap);
		unset($trackpoint);

		fwrite($tmpFileHandle, 'var track = [ ');
		$comma = '';

		for($i = 0; $i < $leafletCount; $i++)
		{
			fwrite($tmpFileHandle, $comma . 'l'.$i);
			$comma = ',';
		}

		fwrite($tmpFileHandle, "];\n");
		rewind($tmpFileHandle);
		$fileStats =fstat($tmpFileHandle);
		$leafletJSLatLongArray = fread($tmpFileHandle, $fileStats['size']+10);
		Log::debug($leafletJSLatLongArray);
		$activityParsedExtras = new ActivitysParsedExtras();
		$activityParsedExtras->leafletJSLatLongArray = $leafletJSLatLongArray;
		$activityParsedExtras->activity_id = $activity_id;

		$activityParsedExtras->save();

	}
}
