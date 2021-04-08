<?php

namespace UblTr;

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
     * @var string
     */
    protected $schemaLocation = null;

    /**
     * Parent namespace
     * @var Ns
     */
    protected $parent = null;

    /**
     * Ns constructor.
     * @param null $parent
     */
    public function __construct($parent = null)
    {
        $this->parent = $parent;
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

}