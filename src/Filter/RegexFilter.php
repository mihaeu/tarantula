<?php

namespace Mihaeu\Tarantula\Filter;

class RegexFilter implements FilterInterface
{
    /**
     * @var String
     */
    private $regex;

    /**
     * Sets the string that the urls are supposed to contain.
     *
     * @param String $regex
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Filters a url by checking it against a regular expression.
     *
     * @param String $url
     *
     * @return bool
     */
    public function filter($url)
    {
        return 1 === preg_match($this->regex, $url);
    }
}
