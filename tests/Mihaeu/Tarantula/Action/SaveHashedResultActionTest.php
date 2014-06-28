<?php

use Mihaeu\Tarantula\Action\SaveHashedResultAction;
use Mihaeu\Tarantula\Result;

class SaveHashedResultActionTest extends PHPUnit_Framework_TestCase
{
    public function testSavesResultDataUnderHashedPathOneLevelDeep()
    {
        $result = new Result('0123456789', 'http://example.com', '<html>');
        $testFolder = sys_get_temp_dir().DIRECTORY_SEPARATOR.'phpunit-'.date('Y-m-d-H-i-s');
        mkdir($testFolder);
        
        $action = new SaveHashedResultAction($testFolder);
        $processedResult = $action->execute($result);

        $savedFile = $testFolder.DIRECTORY_SEPARATOR.'0'.DIRECTORY_SEPARATOR.'0123456789';
        $this->assertTrue(file_exists($savedFile));
        $this->assertEquals('<html>', file_get_contents($savedFile));
        
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
