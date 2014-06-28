<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

use zz\Html\HTMLMinify;

/**
 * Minify HTML Action
 *
 * For this action to be effective it has to be registered
 * before the result is being persisted.
 *
 * @author Michael haeuslmann <haeuslmann@gmail.com>
 */
class MinifyHtmlAction implements ActionInterface
{
    /**
     * Minify HTML.
     * 
     * @param  Result $result
     * 
     * @return Result
     */
    public function execute(Result $result)
    {
        $result->setData(HTMLMinify::minify($result->getData()));
        return $result;
    }
}
