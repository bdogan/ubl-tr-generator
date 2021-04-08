<?php

namespace UblTr;

use UblTr\Ns\CommonAggregateComponents2;
use UblTr\Ns\CommonBasicComponents2;
use UblTr\Ns\CommonExtensionComponents2;
use UblTr\Ns\Invoice2;
use UblTr\Ns\Xades;
use UblTr\Ns\XMLSchemaInstance;
use UblTr\Ns\XMLSignatures;

abstract class Schema
{

    /**
     * Schema options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Schema nodes
     *
     * @var NodeCollection
     */
    protected $nodes;

    /**
     * Schema root tag
     *
     * @var string
     */
    protected $tag;

    /**
     * Using namespaces
     *
     * @var Ns[]
     */
    protected $ns = array();

    /**
     * Required ns
     *
     * @var Ns[]
     */
    protected $requireNs = array();

    /**
     * Schema constructor.
     * @param array $options
     */
    public function __construct($options = array())
    {

        // Set options
        $this->options = array_replace_recursive($this->options, (array) $options);

        // Load namespaces
        $this->loadNamespaces();

        // Generate nodes
        $this->nodes = NodeCollection::create($this->generateNodes());

    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        if (isset($this->nodes->{$name})) return $this->nodes->{$name};
        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (isset($this->nodes->{$name})) $this->nodes->{$name} = $value;
    }

    public function toArray()
    {
        return $this->nodes->toArray();
    }


    /**
     * Returns required ns
     *
     * @return Ns[]
     */
    public function getRequireNs()
    {
        return $this->requireNs;
    }

    /**
     * Generate
     *
     * @return Ns[]
     */
    private function loadNamespaces()
    {
        $invoice2Ns = new Invoice2();
        $this->ns = array(
            new CommonBasicComponents2($invoice2Ns),
            new CommonAggregateComponents2($invoice2Ns),
            new Xades($invoice2Ns),
            new XMLSchemaInstance($invoice2Ns),
            new CommonExtensionComponents2($invoice2Ns),
            new XMLSignatures($invoice2Ns)
        );
    }

    /**
     * Generate Schema nodes
     *
     * @return Node[]
     */
    protected abstract function generateNodes();

    /**
     * Update Schema
     * @return mixed
     */
    protected function update() { }

    /**
     * @param $prefix
     * @return mixed|Ns|null
     */
    protected function getNs($prefix = null)
    {
        // Required
        if (isset($this->requireNs[$prefix])) return $this->requireNs[$prefix];

        // Return specific ns
        foreach ($this->ns as $ns) {
            if ($ns->getPrefix() === $prefix) return $this->requireNs[$prefix] = $ns;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return NodeCollection
     */
    public function getNodes()
    {
        // Update Schema
        $this->update();

        // Return all nodes
        return $this->nodes;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

}