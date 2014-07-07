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
    public function skipTestIfTestingWithPHP53()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped('Test requires Demo Server, which is based on the PHP 5.4 internal server.');
        }
    }
}