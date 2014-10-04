<?php

namespace Mihaeu\Tarantula;

use Mihaeu\Tarantula\Action\ActionInterface;
use Mihaeu\Tarantula\Filter\FilterInterface;

use Symfony\Component\DomCrawler\Crawler as DOMCrawler;

/**
 * Guzzle based Crawler.
 *
 * @author Michael Haeuslmann <haeuslmann@gmail.com>
 */
class Crawler
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var Array
     */
    private $allLinks = array();

    /**
     * @var Array
     */
    private $actions = array();

    /**
     * @var Array
     */
    private $filters = array();

    /**
     * Constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * This is the main action that will get called recursively depending on `$depth`.
     * 
     * @param  integer $depth
     * @param  String  $url
     * 
     * @return Array
     */
    public function go($depth = -1, $url = '')
    {
        if ($url === '') {
            $url = $this->client->getStartUrl();
        }

        // apply filters
        if ($url !== $this->client->getStartUrl() && !$this->urlPassesFilters($url)) {
            return array();
        }

        // download from the url
        $data = $this->client->downloadContent($url);
        if (empty($data)) {
            return array();
        }
        $this->processActions($url, $data);

        // add current url to links
        $currentHash = $this->client->createHashFromUrl($url);
        if (!isset($this->allLinks[$currentHash])) {
            $this->allLinks[$currentHash] = $this->client->convertToAbsoluteUrl($url);
        }

        // when we reach max. depth we don't need to go deeper and download more
        if ($depth-- !== 0) {
            // parse sub links
            $links = $this->filterUrls($this->findAllLinks($data));
            $this->allLinks = array_merge($this->allLinks, $links);

            // recursive calls provide depth
            foreach ($links as $hash => $link) {
                $this->allLinks = array_merge($this->allLinks, $this->go($depth, $link));
            }
        }

        return $this->allLinks;
    }

    public function filterUrls(Array $urls)
    {
        $filteredUrls = array();
        foreach ($urls as $hash => $url) {
            if ($this->urlPassesFilters($url)) {
                $filteredUrls[$hash] = $url;
            }
        }
        return $filteredUrls;
    }

    /**
     * Runs all filters against the url.
     *
     * @param String $url
     *
     * @return bool
     */
    public function urlPassesFilters($url)
    {
        foreach ($this->filters as $filter) {
            if (!$filter->filter($url)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Process the result by execution all actions.
     *
     * @param String $url
     * @param String $data
     */
    public function processActions($url, $data)
    {
        $hash = $this->client->createHashFromUrl($url);
        $result = new Result($hash, $url, $data);
        foreach ($this->actions as $action) {
            $result = $action->execute($result);
        }
        unset($result);
    }

    /**
     * Add actions that will be executed on the results.
     * 
     * @param ActionInterface $action
     */
    public function addAction(ActionInterface $action)
    {
        $this->actions[] = $action;
    }

    /**
     * Add another filter.
     *
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Finds all links from a HTML document.
     *
     * @param String $html
     * @param bool   $foreignLinks
     * 
     * @return Array
     */
    function findAllLinks($html, $foreignLinks = false)
    {
        $domCrawler = new DOMCrawler();
        $domCrawler->addContent($html);

        $xpathLinksWithUrl = '//a[@href]';
        $client = $this->client;
        $links = $domCrawler->filterXPath($xpathLinksWithUrl)->each(
            function (DOMCrawler $node, $i) use ($foreignLinks, $client) {
                $url = $node->attr('href');

                // this url has already been parsed
                if ($url === '#') {
                    return array();
                }

                $url = $client->convertToAbsoluteUrl($url);
                
                // no foreign links
                if ($foreignLinks === false && strpos($url, $client->getStartUrl()) !== 0) {
                    return array();
                }

                return array(
                    'hash'   => $client->createHashFromUrl($url),
                    'target' => $url
                );
            }
        );

        $cleanLinks = array();
        foreach ($links as $link) {
            // only gather links that have not been processed yet
            if (!empty($link) && !isset($this->allLinks[$link['hash']])) {
                $cleanLinks[$link['hash']] = $link['target'];
            }
        }

        return $cleanLinks;
    }
}
