<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Mirror Result Action
 *
 * @author Michael haeuslmann <haeuslmann@gmail.com>
 */
class MirrorResultAction implements ActionInterface
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
        // strip protocol and www.
        $url = preg_replace('/^((https?):\/\/)?(www\d{0,3}\.)?/', '', $result->getLink());

        // URLs like blog.com/posts and blog.com/posts/a would conflict, because
        // the first URL would be created as a file and the same name cannot be used for
        // a directory with the same name. Workaround is to simply attach an underscore
        // to the *index* file.
        if ($this->isPrettyUrl($url)) {
            $url = $url.'_';
        }
        $saveTo = $this->dir.DIRECTORY_SEPARATOR.$url;
        $fs = new Filesystem();
        $fs->dumpFile($saveTo, $result->getData());

        return $result;
    }

    /**
     * Checks is a URL is pretty.
     *
     * Pretty URLs contain no file endings like `.php`. The check is not
     * 100%, but checking for the usual file ending patterns should do.
     * Plain domain names are always pretty.
     *
     * @param  String $url
     *
     * @return bool
     */
    public function isPrettyUrl($url)
    {
//        $regex = '/
//            .*\/            # get as many characters up to the last /
//            [^\.]+          # move forward till the last .
//            \.\w{2,4}$      # check if there is a file ending at the end
//            /x';
        $regex = '/.*\/[^\.]+\.\w{2,4}$/';
        return 1 !== preg_match($regex, $url);
    }
}
