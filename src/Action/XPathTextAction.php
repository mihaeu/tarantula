<?php

namespace Mihaeu\Tarantula\Action;

use Mihaeu\Tarantula\Result;

use Symfony\Component\DomCrawler\Crawler as DOMCrawler;

/**
 * XPath Text Action
 *
 * @author Michael haeuslmann <haeuslmann@gmail.com>
 */
class XPathTextAction implements ActionInterface
{
    /**
     * @var String
     */
    protected $xpath;

    /**
     * Constructor.
     *
     * @param String $xpath
     */
    public function __construct($xpath)
    {
        $this->xpath = $xpath;
    }

    /**
     * Return text that matches a XPath expression.
     *
     * @param  Result $result
     *
     * @return Result
     */
    public function execute(Result $result)
    {
        $domCrawler = new DOMCrawler();
        $domCrawler->addContent($result->getData());

        $domCrawler->filterXPath($this->xpath)->each(
            function (DOMCrawler $node) {
                $text = trim($node->text());
                if (!empty($text)) {
                    echo $text.PHP_EOL;
                }
            }
        );

        return $result;
    }
}
