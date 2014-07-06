<?php

namespace Mihaeu\Tarantula\Tests\Command;

use Mihaeu\Tarantula\Console\CrawlCommand;
use Mihaeu\Tarantula\Tests\BaseUnitTest;
use Mihaeu\Tarantula\Tests\DemoServer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class HttpClientTest extends BaseUnitTest
{
    public function testCrawls()
    {
        $application = new Application();
        $application->add(new CrawlCommand());

        $command = $application->find('crawl');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'url'     => DemoServer::URL,
                '--depth' => 0
            )
        );

        $this->assertRegExp('/Links found 1/', $commandTester->getDisplay());
    }
}
