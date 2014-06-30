<?php

use Mihaeu\Tarantula\Action\MirrorResultAction;
use Mihaeu\Tarantula\Result;

class MirrorResultActionTest extends PHPUnit_Framework_TestCase
{
    public function testMirrorsUrlStructure()
    {
        $urls = array(
            'http://www.google.com/test/site.html',
            'https://google.com/test/site.html',
            'www.google.com/test/site.html'
        );
        foreach ($urls as $url) {
            $result = new Result('', $url, '<wayne>');
            $testFolder = sys_get_temp_dir().DIRECTORY_SEPARATOR.'phpunit-'.date('Y-m-d-H-i-s').rand();
            $fs = new Symfony\Component\Filesystem\Filesystem($testFolder);
            $fs->mkdir($testFolder);

            $action = new MirrorResultAction($testFolder);
            $action->execute($result);
            $this->assertEquals('<wayne>', file_get_contents($testFolder.DIRECTORY_SEPARATOR.'google.com/test/site.html'));
            $fs->remove($testFolder);
        }
    }
}
