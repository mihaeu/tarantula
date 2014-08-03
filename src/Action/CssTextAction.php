<?php

namespace Mihaeu\Tarantula\Action;

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
