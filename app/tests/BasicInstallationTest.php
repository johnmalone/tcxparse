<?php

class BasicInstallationTest extends TestCase 
{
	public function testSlashIsWorking()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}

	public function testUploadRedirectsToLoginIfNotLoggedIn()
	{
		$crawler = $this->client->request('GET', '/upload');
		$this->assertRedirectedTo('login');
	}

}
