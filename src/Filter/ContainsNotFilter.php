<?php

namespace Mihaeu\Tarantula\Filter;

class ContainsNotFilter implements FilterInterface
{
    /**
     * @var String
     */
    private $string;

    /**
     * Sets the string that the urls are not supposed to contain.
     *
     * @param String $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * Filters a url by checking if it doesn't contain the string.
     *
     * @param String $url
     *
     * @return bool
     */
    public function filter($url)
    {
        return false === strpos($url, $this->string);
    }
}
