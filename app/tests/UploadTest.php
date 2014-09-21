<?php

class UploadTest extends TestCase 
{
	public function testUploadOfSimpleFile()
	{
		$tmp_name = tempnam('/tmp/', 'tcxPHPunitFileUpload.');
		$tcxFile = realpath(__DIR__). '/data/simpleTrip.tcx';
//print_r($tcxFile);
		$tcx = file_get_contents($tcxFile);
//print_r($tcx);
		file_put_contents($tmp_name, $tcx);
		$oneUploadedFile = array('file' => array('tmp_name' => $tmp_name, 'name' => 'simpleTrip.tcx', 'size' => strlen($tcx), 'error' => NULL, 'type' => 'application/xml'));

		Auth::login(User::find(1));

		$response = $this->call('POST', 'upload', array(), $oneUploadedFile );
		
		$this->assertRedirectedTo('/');

		$lap = Lap::find(1);
		
		$this->assertEquals('2029.38', $lap->totalTimeSeconds);
		$this->assertEquals('21.716528', $lap->distanceMeters);
		$this->assertEquals('1.220000', $lap->maximumSpeed);
		$this->assertEquals('0', $lap->calories);
		$this->assertEquals('Manual', $lap->triggerMethod);

		$extras = ActivitysParsedExtras::find(1);
		$this->assertEquals('[[-8.5278,54.76316],[-8.527797,54.763188],[-8.527748,54.763206]]', $extras->jsonCoordArray);
	}
	


}
