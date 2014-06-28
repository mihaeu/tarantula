<?php

namespace Mihaeu\Tarantula;

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
     * Constructor.
     *
     * @param HttpClient $client
     * @param  Array   $options  Options for Guzzle's request options see
     *                           [Guzzle Documentation](http://docs.guzzlephp.org/en/latest/quickstart.html#make-a-request)
     *                           E.g. ['auth' =>  ['admin', 'admin']] for basic authentication
     */
    public function __construct(HttpClient $client, $options = array())
    {
        $client->setOptions($options);
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

        $html = $this->client->downloadContent($url);
        $links = $this->findAllLinks($html);
        $this->allLinks = array_merge($this->allLinks, $links);
        
        // recursive calls provide depth
        --$depth;
        if ($depth !== 0) {
            foreach ($links as $hash => $link) {
                $this->allLinks = array_merge($this->allLinks, $this->go($depth, $link));
            }
        }

        return $this->allLinks;
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
        $links = $domCrawler->filterXPath($xpathLinksWithUrl)->each(function ($node, $i) use ($foreignLinks) {
            $url = $node->attr('href');

            // this url has already been parsed
            if ($url === '#') {
                return array();
            }

            $url = $this->client->convertToAbsoluteUrl($url);
            
            // no foreign links
            if ($foreignLinks === false && strpos($url, $this->client->getStartUrl()) !== 0) {
                return array();
            }

            return array(
                'hash'   => $this->client->createHashFromUrl($url),
                'target' => $url
            );
        });

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