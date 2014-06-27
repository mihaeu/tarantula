<?php

use Mihaeu\Tarantula\HttpClient;

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function testDownloadsDefaultGooglePage()
    {
        $client = new HttpClient();
        $html = $client->downloadContent('http://google.com');
        $this->assertContains('https://accounts.google.com', $html);
    }
}
