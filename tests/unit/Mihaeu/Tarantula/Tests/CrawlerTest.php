<?php

use Mihaeu\Tarantula\Crawler;
use Mihaeu\Tarantula\HttpClient;

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    public function testFindsAllLinks()
    {
        $crawler = new Crawler(new HttpClient('http://google.com'));
        $html = '<!doctype html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Document</title>
            </head>
            <body>
                <h1><a href="/test">link</a></h1>
                <header></header>
                <main>
                    <a href="back">back</a>
                    <a href="http://google.com/gmail">back</a>
                    <a href="http://test2">FOREIGN URL SHOULD BE IGNORED</a>
                </main>
            </body>
            </html>';
        $this->assertCount(3, $crawler->findAllLinks($html));
    }

    public function testCrawlsGoogle()
    {
        if (!@file_get_contents('http://google.com', 'r')) {
            $this->markTestSkipped('No internet connection ...');
        }
        
        $crawler = new Crawler(new HttpClient('http://google.com'));
        $links = $crawler->go(1);
        $this->assertNotEmpty($links);
    }
}