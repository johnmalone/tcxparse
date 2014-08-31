<?php

class UploadController extends BaseController 
{
	public function showUpload()
	{
		return View::make('upload');
	}

	public function doUpload()
	{
		$parseTCX = new ParseTCX();
		$result = $parseTCX->saveActivityXML(Input::file('file'));

		if (! $result)
			return View::make('upload')->withResult($result);
		else 
			return Redirect::to('')->with('result', $result);
	
	}

	public function getUploadProgress()
	{
		$uploadParsingEntries = UploadParsing::where('user_id', '=', Auth::id())->get();
		if (! $uploadParsingEntries)
			return 0;

		$total = 0;
		$done = 0;
		foreach ($uploadParsingEntries as $oneEntry)
		{
			$total += $oneEntry->totalActivitiesCount;
			$done += $oneEntry->completedActivitiesCount;
		}
		if ($total == 0) return 0;
		else return floor(($done/$total) * 100);
	}
}
