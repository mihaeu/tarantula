<?php

namespace Mihaeu\Tarantula\Tests\Action;

use Mihaeu\Tarantula\Action\SaveHashedResultAction;
use Mihaeu\Tarantula\Result;
use Mihaeu\Tarantula\Tests\BaseUnitTest;
use Symfony\Component\Filesystem\Filesystem;

class SaveHashedResultActionTest extends BaseUnitTest
{
    public function testSavesResultDataUnderHashedPathOneLevelDeep()
    {
        $result = new Result('0123456789', 'http://example.com', '<html>');
        $testFolder = sys_get_temp_dir().DIRECTORY_SEPARATOR.'phpunit-'.date('Y-m-d-H-i-s').rand();
        $fs = new Filesystem();
        $fs->mkdir($testFolder);

        $action = new SaveHashedResultAction($testFolder);
        $action->execute($result);

        $savedFile = $testFolder.DIRECTORY_SEPARATOR.'0'.DIRECTORY_SEPARATOR.'0123456789';
        $this->assertTrue(file_exists($savedFile));
        $this->assertEquals('<html>', file_get_contents($savedFile));
        $fs->remove($testFolder);
    }
}
