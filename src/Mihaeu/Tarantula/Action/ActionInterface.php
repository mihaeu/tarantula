<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

interface ActionInterface
{
    /**
     * Execute an action on the result.
     *  
     * @param  Result $result
     * @return Result
     */
    public function execute(Result $result);
}