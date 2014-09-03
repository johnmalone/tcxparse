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
					if (! $activitiesParsedExtras)
						return '{}';
					$coordArray = $activitiesParsedExtras->jsonCoordArray;

					$geoJson = '{"type": "FeatureCollection",'.
						'"features": [ '.
							' { "type" : "Feature",'.
							'"properties" : { "activity_id" : "'.$id.'" },'.
							'"geometry":{ "type" : "LineString",'.
								'"coordinates": '. $coordArray.
							'}}]}';
					return $geoJson;
				}
				break;
			case 'googChartAlt':
				if (ActivitysParsedExtras::validate(array('id'=> $id)))
				{
					$activitiesParsedExtras = ActivitysParsedExtras::where('activity_id', '=', $id)->get()->first();
					if (! $activitiesParsedExtras)
						return '{}';
					$jsAltArray = $activitiesParsedExtras->jsAltArray;

					return $jsAltArray;
				}


				break;
	
			default:
				return '{}';
		}
		return '{}';
	}
}
