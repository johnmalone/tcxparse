@extends('layout')

@section('content')

<div class="container-fluid">

<div class="row">

<div class="col-sm-3 col-md-2 sidebar">
	<div id="uploadProgressbar">
		Processing uploaded file 
		<div class="progress">
			<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
    				<span class="sr-only"></span>
    				<span id="uploadProgressbarContents"></span>
			</div>
			<script>doUploadProgress = true;</script>
		</div>
	</div>
<script>var idsToLoad = new Array();</script>
	</div>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header"> TCX Parser </h1>
		<div class="row placeholders">

		<?PHP
			foreach ($activities as $activity)
			{
				echo '<div class="panel panel-success">';

				echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">';
				echo $activity->activityId;
				echo '</h3>';
				echo '</div>';

				echo '<div class="panel-body">';
				echo '<div class="row">';
				
				echo '<div class="col-xs-2">';
				echo '<div id="map_'.$activity->id.'" style="height:256px;width:256px;">';
				echo '</div>';
				
				echo '<div class="col-xs-10">';
				foreach ($activity->laps as $lap)
				{
					echo 'Distance: ' . $lap->distanceMeters . '<br/>';
					echo 'Avg Hr: ' . $lap->averageHeartRateBpm . '<br/>';
				}
				echo '</div>';

				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

				echo '<script>';
				echo 'idsToLoad.push('.$activity->id.');';
				echo '</script>';
			}

		?>
		
		</div>
		<?PHP echo $activities->links() ?>
	</div>
</div>
</div>

<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="<?PHP echo asset('js/maps.js');?>"></script>
<script>
var arrayLength = idsToLoad.length;
for(var i=0; i< arrayLength; i++)
{
	getAndLoadLatLongData(idsToLoad[i]);
}
/*
$points = array();
$oneLap = '';
$allActivities->each(function($activity){
	$laps = $activity->laps;
	$laps->each(function($lap){
		global $oneLap;
		$count = 0;
		$trackpoints = $lap->trackpoints;
		$allTrackPoints = $trackpoints->all();
		foreach ($allTrackPoints as $trackpoint)
		{
			if ($trackpoint->latitudeDegrees and $trackpoint->longitudeDegrees)
			{
				$oneLap .= 'var l'.$count.'  = L.latLng('.$trackpoint->latitudeDegrees. ',' . $trackpoint->longitudeDegrees . ");\n";
				$count++;
			}
		}
		$oneLap .= 'var track = [ ';
		$comma = '';
		for ($i=0;$i<$count; $i++)
		{
			$oneLap .= $comma. 'l'.$i ;
			$comma = ',';
		}
		$oneLap .= '];';
		Log::debug($oneLap);
	});
});
*/


</script>
@stop


