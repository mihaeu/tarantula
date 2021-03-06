<?php

namespace Mihaeu\Tarantula\Tests;

use Mihaeu\Tarantula\HttpClient;

class HttpClientTest extends BaseUnitTest
{
    public function testDownloadsDefaultGooglePage()
    {
        $this->skipTestIfTestingWithPHP53();

        $client = new HttpClient('doesnt matter for this test');
        $html = $client->downloadContent(DemoServer::URL);
        $this->assertContains('mike-on-a-bike', $html);
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

    public function testConvertsToFullyQualifiedUrl()
    {
        $client = new HttpClient('http://google.com/');
        $this->assertEquals(
            $client->convertToAbsoluteUrl('http://google.com/test'),
            $client->convertToAbsoluteUrl('/test')
        );
    }

    /**
     * I'm not sure if this is practical, because www1 might produce a different
     * result than www15 (although it probably shouldn't)
     */
    /*
    public function testSameUrlWithDifferentPrefixProducesSameHash()
    {
        $client = new HttpClient('http://google.com/');
        $equalUrls = [
            'http://www.google.com/test',
            'http://google.com/test',
            'www.google.com/test',
            'google.com/test'
        ];
        foreach ($equalUrls as $url) {
            $this->assertEquals(
                '4539330648b80f94ef3bf911f6d77ac9',
                $client->createHashFromUrl($url)
            );
        }
    }
    */
}
