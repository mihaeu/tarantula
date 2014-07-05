<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Save Hased Result Action
 *
 * @author Michael haeuslmann <haeuslmann@gmail.com>
 */
class SaveHashedResultAction implements ActionInterface
{
    /**
     * @var $dir
     */
    private $dir;

    /**
     * Constructor.
     *
     * @param String $dir
     */
    public function __construct($dir)
    {
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \InvalidArgumentException("$dir is not a directory or not writable", 1);
        }
        $this->dir = realpath($dir);
    }

    /**
     * Save the plain data into a file and hash the resulting filename.
     * 
     * Note: Overwrites old files and uses existing subdirectories.
     * 
     * @param  Result $result
     * 
     * @return Result
     */
    public function execute(Result $result)
    {
        $subDir = $this->dir.DIRECTORY_SEPARATOR.substr($result->getHash(), 0, 1);
        $saveTo = $subDir.DIRECTORY_SEPARATOR.$result->getHash();
        $fs = new Filesystem();
        $fs->dumpFile($saveTo, $result->getData());

        return $result;
    }
}
