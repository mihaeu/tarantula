<?php

namespace Mihaeu\Tarantula\Tests;

use Symfony\Component\Process\Process;

/**
 * Base Unit Test
 *
 * @author Michael Haeuslmann (haeuslmann@gmail.com)
 */
class DemoServer
{
    /**
     * @var Process
     */
    protected $demoServerProcess;

    const HOST = 'localhost:8088';
    const URL  = 'http://localhost:8088';

    /**
     * Shutdown the process when the file gets destroyed (after all tests are done).
     */
    public function __destruct()
    {
        $this->stopDemoServer();
    }

    /**
     * Starts the internal PHP server using excerpts from my (awesome)
     * travel blog as the docroot.
     */
    public function startDemoServer()
    {
        $docRoot = realpath(__DIR__ . '/../../demo/mike-on-a-bike.com');
        $this->demoServerProcess = new Process('php -S '.self::HOST.' -t '.$docRoot);
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
}