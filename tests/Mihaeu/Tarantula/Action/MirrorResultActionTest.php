<?php

use Mihaeu\Tarantula\Action\MirrorResultAction;
use Mihaeu\Tarantula\Result;

class MirrorResultActionTest extends PHPUnit_Framework_TestCase
{
    public function testMirrorsUrlStructure()
    {
        $result = new Result('', 'https://google.com/test/site.html', '<wayne>');
        $testFolder = sys_get_temp_dir().DIRECTORY_SEPARATOR.'phpunit-'.date('Y-m-d-H-i-s').rand();
        mkdir($testFolder);

        $action = new MirrorResultAction($testFolder);
        $processedResult = $action->execute($result);
        $this->assertEquals('<wayne>', file_get_contents($testFolder.DIRECTORY_SEPARATOR.'google.com/test/site.html'));

        $this->rrmdir($testFolder);
        $this->assertFalse(file_exists($testFolder));
    }

    /**
     * Recursively removes a folder along with all its files and directories.
     *
     * Taken from: http://ben.lobaugh.net/blog/910/php-recursively-remove-a-directory-and-all-files-and-folder-contained-within
     * 
     * @param String $path 
     *
     * @author Ben Lobaugh
     */
    private function rrmdir($path) {
        // Open the source directory to read in files
        $i = new DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->rrmdir($f->getRealPath());
            }
        }
        rmdir($path);
    }
}
