<?php

class ParsedActivityDataController extends BaseController 
{
	public function getAjaxData($id, $type)
	{
		if (! Auth::check())
			return View::make('home');
			
		switch ($type)
		{
			case 'leafletJSLatLong':
				if (ActivitysParsedExtras::validate(array('id'=> $id)))
				{
					$activitiesParsedExtras = ActivitysParsedExtras::where('activity_id', '=', $id)->get()->first();
					if ($activitiesParsedExtras)
						return $activitiesParsedExtras->leafletJSLatLongArray;

				}
				break;
			default:
				return 'var track_' . $id . ' = []';
		}
		return 'var track_' . $id . ' = []';
	}
}
