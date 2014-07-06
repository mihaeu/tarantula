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

    /**
     * @var string
     */
    protected $host = 'localhost:8088';

    /**
     * Starts the internal PHP server using excerpts from my (awesome)
     * travel blog as the docroot.
     */
    public function startDemoServer()
    {
        $docRoot = realpath(__DIR__ . '/../../demo/mike-on-a-bike.com');
        $this->demoServerProcess = new Process("php -S $this->host -t $docRoot");
        $this->demoServerProcess->setTimeout(3600);
        $this->demoServerProcess->start();
    }

    /**
     * Stops the server.
     */
    public function stopDemoServer()
    {
        $this->demoServerProcess->stop();
    }

    /**
     * Returns the demo url that is currently used for the server.
     *
     * @return string
     */
    public function getDemoUrl()
    {
        return 'http://'.$this->host;
    }
}