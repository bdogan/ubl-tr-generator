<?php

namespace UblTr;

use UblTr\Exception\NsException;

class Ns
{

    /**
     * Namespace prefix
     *
     * @var string
     */
    protected $prefix = null;

    /**
     * Namespce uri
     * @var string
     */
    protected $uri = null;

    /**
     * Schema location
     * @var string|null
     */
    protected $schemaLocation = null;

    /**
     * Parent namespace
     * @var Ns|null
     */
    protected $parent = null;

    /**
     * Ns constructor.
     *
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getSchemaLocation()
    {
        return $this->schemaLocation;
    }

    /**
     * @return Ns
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     * @throws NsException
     */
    public function setParent($parent)
    {
        if (!($parent instanceof Ns)) throw new NsException("$parent is not an instance of UblTR\NS");
        $this->parent = $parent;
    }

}