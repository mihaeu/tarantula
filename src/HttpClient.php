<?php

namespace Mihaeu\Tarantula;

use Guzzle\Http\Client;

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
     * @var Array
     */
    private $options;

    /**
     * @var String
     */
    private $user;

    /**
     * @var String
     */
    private $password;

    /**
     * Constructor.
     *
     * @param String $startUrl
     * @param Array  $options
     */
    public function __construct($startUrl, $options = array())
    {
        $this->setStartUrl($startUrl);
        $this->setOptions($options);
    }

    /**
     * Set basic authentication.
     * 
     * @param String $user
     * @param String $password
     */
    public function setAuth($user, $password = '')
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Set start url.
     * 
     * @param String $startUrl
     */
    public function setStartUrl($startUrl)
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
     *
     * @return String
     */
    public function downloadContent($url)
    {
        $client = new Client();

        $body = '';
        try {
            $request = $client->get($url, $this->options);
            if ($this->user) {
                $request->setAuth($this->user, $this->password);
            }
            $response = $request->send();
            $body = $response->getBody();
        } catch (\Exception $e) {
            // This is not fatal, because we're simply going
            // to return an empty result.
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
     * Converts a url like /product to http://example.com/product
     *
     * @TODO check if it's better to leave trailing slashes or not
     * 
     * @param  String $url
     * 
     * @return String
     */
    public function convertToAbsoluteUrl($url)
    {
        if (strpos($url, '/') === 0 || strpos($url, 'http') !== 0) {
            $url = $this->startUrl.$url;
        }
        return $url;
    }

    /**
     * Set options.
     * 
     * @param array $options
     */
    public function setOptions($options = array())
    {
        $this->options = $options;
    }
}
