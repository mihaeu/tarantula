<?php

use Mihaeu\Tarantula\HttpClient;

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function testDownloadsDefaultGooglePage()
    {
        if (!@file_get_contents('http://google.com', 'r')) {
            $this->markTestSkipped('No internet connection ...');
        }
        
        $client = new HttpClient('doesnt matter for this test');
        $html = $client->downloadContent('http://google.com');
        $this->assertContains('https://accounts.google.com', $html);
    }

    public function testDoesntCrashOnBadUrl()
    {
        $client = new HttpClient('doesnt matter for this test');
        $html = $client->downloadContent('1337@http://google.com');
        $this->assertEmpty($html);
    }

    public function testHashesAUrl()
    {
        $client = new HttpClient('http://google.com/');
        $this->assertEquals(
            '4539330648b80f94ef3bf911f6d77ac9',
            $client->createHashFromUrl('http://google.com/test')
        );
        $this->assertEquals(
            $client->createHashFromUrl('http://google.com/test'),
            $client->createHashFromUrl('/test')
        );
    }

    public function testConvertsToFullyQuallifiedUrl()
    {
        $client = new HttpClient('http://google.com/');
        $this->assertEquals(
            $client->convertToAbsoluteUrl('http://google.com/test'),
            $client->convertToAbsoluteUrl('/test')
        );
    }
}
