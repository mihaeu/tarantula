<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

use Symfony\Component\DomCrawler\Crawler as DOMCrawler;
use Symfony\Component\CssSelector\CssSelector;

/**
 * Css Text Action
 *
 * @author Michael haeuslmann <haeuslmann@gmail.com>
 */
class CssTextAction extends XPathTextAction
{
    /**
     * Constructor.
     *
     * @param String $css
     */
    public function __construct($css)
    {
        $this->xpath = CssSelector::toXPath($css);
    }
}
