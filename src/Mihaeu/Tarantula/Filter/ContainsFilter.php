<?php

namespace Mihaeu\Tarantula\Filter;

class ContainsFilter implements FilterInterface
{
    /**
     * @var String
     */
    private $string;

    /**
     * Sets the string that the urls are supposed to contain.
     *
     * @param String $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * Filters a url by checking if it contains the string.
     *
     * @param String $url
     *
     * @return bool
     */
    public function filter($url)
    {
        return false !== strpos($url, $this->string);
    }
}
