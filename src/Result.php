<?php

namespace Mihaeu\Tarantula;

/**
 * Result entity.
 *
 * @author Michael Haeuslmann <haeuslmann@gmail.com>
 */
Class Result
{
    /**
     * @var String
     */
    private $hash;

    /**
     * @var String
     */
    private $link;
    
    /**
     * @var String
     */
    private $data;

    /**
     * Constructor.
     * 
     * @param String $hash
     * @param String $link
     * @param String $data
     */
    public function __construct($hash, $link, $data)
    {
        $this->setHash($hash);
        $this->setLink($link);
        $this->setData($data);
    }

    /**
     * Gets hash.
     * 
     * @return String
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Sets hash.
     * 
     * @param String $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }
 
    /**
     * Gets link.
     * 
     * @return String
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets link.
     * 
     * @param String $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
 
    /**
     * Gets data.
     * 
     * @return String
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets data.
     * 
     * @param String $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
