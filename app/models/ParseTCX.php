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

		while ($reader->read())
		{
			if ($reader->nodeType == XMLReader::ELEMENT and $reader->name == 'Activity')
			{
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
		$uploadParsing->save();

		return TRUE;
	}

	public function fire($job, $data)
	{
		Log::debug($data);
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
		Log::debug('Parsing unsaved activity: ' . $unsavedActivityEntry->id);

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
			$lapEntry->averageHeartRateBpm = $lap->AverageHeartRateBpm;
			$lapEntry->maximumHeartRateBpm = $lap->MaximumHeartRateBpm;
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
					$trackpointEntry->latitudeDegrees  = $trackpoint->Positiion->LatitudeDegrees ;
					$trackpointEntry->longitudeDegrees  = $trackpoint->Position->LongitudeDegrees ;
					$trackpointEntry->altitudeMeters  = $trackpoint->AltitudeMeters ;
					$trackpointEntry->distanceMeters  = $trackpoint->DistanceMeters ;
					$trackpointEntry->heartRateBpm  = $trackpoint->HeartRateBpm ;
					$trackpointEntry->lap_id = $lapID;
					$trackpointEntry->save();
				}
			}
		}
		
		$uploadParsingEntry = UploadParsing::find($unsavedActivityEntry->uploadParsing_id);
		$uploadParsingEntry->completedActivitiesCount++;
		$uploadParsingEntry->save();

		return TRUE;
	}
}
