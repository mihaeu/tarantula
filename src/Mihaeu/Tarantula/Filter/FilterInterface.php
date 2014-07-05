<?php

namespace Mihaeu\Tarantula\Filter;

interface FilterInterface
{
    /**
     * Check if a url passes the filter.
     *
     * @param String $url
     *
     * @return bool
     */
    public function filter($url);
} 