<?php

namespace Mihaeu\Tarantula;

use GuzzleHttp\Client;

/**
 * HttpClient
 *
 * Wrapper for Guzzle.
 *
 * @author Michael Haeuslmann <haeuslmann@gmail.com>
 */
class HttpClient
{
    /**
     * This is where the crawler starts. Doesn't contain a trailing (or starting) slash.
     * 
     * @var String
     */
    private $startUrl;

    /**
     * Constructor.
     *
     * @param  String $startUrl 
     * 
     * @return void
     */
    public function __construct($startUrl = null)
    {
        $this->startUrl = trim($startUrl, '/');
    }

    /**
     * Get start url.
     * 
     * @return String
     */
    public function getStartUrl()
    {
        return $this->startUrl;
    }

    /**
     * Download (HTML) content from a URL using Guzzle.
     * 
     * @param  String $url
     * @param  Array  $options Options for Guzzle's request options see
     *                         [Guzzle Documentation](http://docs.guzzlephp.org/en/latest/quickstart.html#make-a-request)
     * 
     * @return String
     */
    function downloadContent($url, $options = [])
    {
        $client = new Client();
        $body = '';
        try {
            $response = $client->get($url, $options);
            $body = $response->getBody();
        } catch (\Exception $e) {
            // log
        }

        return (string) $body;
    }

    /**
     * Creates a hash based on the md5 of the absolute URL.
     *  
     * @param  String $url
     * 
     * @return String
     */
    public function createHashFromUrl($url)
    {
        return md5(str_replace($this->getStartUrl(), '', $this->convertToAbsoluteUrl($url)));
    }

    /**
     * Converts a url like /product to http://example.com/product.
     *
     * @param  String $url
     * 
     * @return String
     */
    public function convertToAbsoluteUrl($url)
    {
        if (strpos($url, '/') === 0) {
            $url = $this->startUrl.$url;
        }
        return $url;
    }
}
