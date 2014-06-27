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
        try {
            $response = $client->get($url, $options);
        } catch (Exception $e) {
            return '';
        }

        $body = $response->getBody();
        return (string) $body;
    }
}
