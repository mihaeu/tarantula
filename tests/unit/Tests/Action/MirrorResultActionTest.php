<?php

namespace Mihaeu\Tarantula\Tests\Action;

use Mihaeu\Tarantula\Action\MirrorResultAction;
use Mihaeu\Tarantula\Result;
use Mihaeu\Tarantula\Tests\BaseUnitTest;
use Symfony\Component\Filesystem\Filesystem;

class MirrorResultActionTest extends BaseUnitTest
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
            $fs = new Filesystem();
            $fs->mkdir($testFolder);

            $action = new MirrorResultAction($testFolder);
            $action->execute($result);
            $this->assertEquals('<wayne>', file_get_contents($testFolder.DIRECTORY_SEPARATOR.'google.com/test/site.html'));
            $fs->remove($testFolder);
        }
    }

    public function testDetectsPrettyUrls()
    {
        $action = new MirrorResultAction(sys_get_temp_dir());

        $this->assertTrue($action->isPrettyUrl('google.com'));
        $this->assertTrue($action->isPrettyUrl('google.com/posts'));
        $this->assertTrue($action->isPrettyUrl('google.com/posts/too-crazy-and.com/deep'));

        $this->assertFalse($action->isPrettyUrl('http://google.com'));
        $this->assertFalse($action->isPrettyUrl('google.com/my/deep/url.php'));
    }
}
