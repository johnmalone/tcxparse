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
			'completedActivitiesCount' => 0,
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
				$user_id = Auth::user()->id;
				$data = array('user_id' => $user_id, 'unsavedActivity_id' => $unsavedActivity->id);
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
		DB::connection()->disableQueryLog();

		$activity = Activity::find($activity_id);
		
		$jsonCoords = $this->saveJsonCoordArray($activity);
		
		$jsonGoogAlt = $this->saveGoogJsAltArray($activity);
		
		$activityParsedExtras = new ActivitysParsedExtras();
		$activityParsedExtras->jsonCoordArray = $jsonCoords;
		$activityParsedExtras->jsAltArray = $jsonGoogAlt;
		$activityParsedExtras->activity_id = $activity_id;

		$activityParsedExtras->save();

	}

	private function saveJsonCoordArray($activity)
	{
		// points from different laps in the one activity are merged into one track
		$coordArray = array();
		foreach ($activity->laps as $lap)
			foreach ($lap->trackpoints as $trackpoint)
				if ($trackpoint->latitudeDegrees and $trackpoint->longitudeDegrees)
					$coordArray[] = array($trackpoint->longitudeDegrees, $trackpoint->latitudeDegrees );
		$jsonCoords = json_encode($coordArray, JSON_NUMERIC_CHECK);
		
		return $jsonCoords;

	}


	private function saveGoogJsAltArray($activity)
	{
		// manually building json string as require date obj
		// not sure how to build unquoted strings using json_encode()
		

		//$timeCol = array('id' => 'time','label'=>'Time', 'type'=> 'date');
		$distCol = array('id' => 'dist','label'=>'Distance', 'type'=> 'number');
		$altCol  = array('id' => 'alt', 'label'=>'Altitude', 'type'=> 'number');
		
		$cols = array($distCol, $altCol);
		
		$rows = array();
		foreach ($activity->laps as $lap)
		{
			foreach ($lap->trackpoints as $trackpoint)
			{
				if ($trackpoint->latitudeDegrees and $trackpoint->longitudeDegrees)
				{
					$dateBits = preg_split("@[- :]@", $trackpoint->time);
				//	$timeCell = array('v' => 'Date('.$dateBits[0].','.$dateBits[1].','.$dateBits[2].','.$dateBits[3].','.$dateBits[4].','.$dateBits[5].')',
				//			'f' => $dateBits[3].':'.$dateBits[4].':'.$dateBits[5]);
					$distCell = array('v' =>  round($trackpoint->distanceMeters/1000,2), 'f' => round($trackpoint->distanceMeters / 1000,2) . 'KM');
					$altCell = array('v' =>$trackpoint->altitudeMeters, 'f' => number_format($trackpoint->altitudeMeters).'M');
					$rows[] = array('c' => array($distCell, $altCell));
				}
			}
		}
		
		$jsonString = json_encode(array("cols" => $cols, "rows" => $rows));
		
		return $jsonString;
	}
}
