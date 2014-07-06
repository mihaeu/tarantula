<?php

namespace Mihaeu\Tarantula\Tests;

use Symfony\Component\Process\Process;

/**
 * Base Unit Test
 *
 * @author Michael Haeuslmann (haeuslmann@gmail.com)
 */
abstract class BaseUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    protected $demoServerProcess;

    public function startDemoServer()
    {
        $docRoot = realpath(__DIR__ . '/../../../../demo');
        $this->demoServerProcess = new Process('php -S localhost:9134 -t ' . $docRoot);
        $this->demoServerProcess->setTimeout(3600);
        $this->demoServerProcess->run();
    }

    public function stopDemoServer()
    {
        $this->demoServerProcess->stop();
    }
}