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
}
