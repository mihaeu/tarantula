<?php

namespace Mihaeu\Tarantula\Tests;

use Mihaeu\Tarantula\Action\CssTextAction;
use Mihaeu\Tarantula\Crawler;
use Mihaeu\Tarantula\Filter\ContainsFilter;
use Mihaeu\Tarantula\HttpClient;

class CrawlerTest extends BaseUnitTest
{
    public function testFindsAllLinks()
    {
        $crawler = new Crawler(new HttpClient('http://google.com'));
        $html = <<<EOT
<!doctype html>
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
</html>
EOT;
        $this->assertCount(3, $crawler->findAllLinks($html));
    }

    public function testCrawlsDemoServer()
    {
        $this->skipTestIfTestingWithPHP53();

        $crawler = new Crawler(new HttpClient(DemoServer::URL));

        // only root
        $links = $crawler->go(0);
        $this->assertCount(1, $links);

        // one lvl down
        $links = $crawler->go(1);
        $this->assertCount(23, $links);
    }

    public function testFiltersUrls()
    {
        $this->skipTestIfTestingWithPHP53();

        $crawler = new Crawler(new HttpClient(DemoServer::URL));

        // match only links with "part"
        $crawler->addFilter(new ContainsFilter('part'));

        $links = $crawler->go(1);
        $this->assertCount(4, $links);
    }

    public function testRunsActionsOnResults()
    {
        $this->skipTestIfTestingWithPHP53();

        $crawler = new Crawler(new HttpClient(DemoServer::URL));
        $crawler->addAction(new CssTextAction('header .title'));

        ob_start();
        $crawler->go(0);
        $output = trim(ob_get_clean());
        $this->assertEquals('mike-on-a-bike', $output);
    }
}
