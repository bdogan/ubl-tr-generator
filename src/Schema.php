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
     * @param array $body
     */
    public function __construct($body = array())
    {
        // Load namespaces
        $this->loadNamespaces();

        // Generate nodes
        $this->nodes = NodeCollection::create($this->generateCommonNodes());

        // Generate nodes from array
        $this->generateNodesFromArray($body);

    }

    /**
     * Prepare given xml chain
     *
     * @param $name
     * @param null $ns
     * @return Node|NodeCollection|null
     */
    protected function prepare($name, $ns = null)
    {
        $chain = is_array($name) ? $name : array(array($name, $ns));
        $nodes = $this->nodes;
        foreach ($chain as $hook) {
            $id = isset($hook[2]) ? $hook[2] : $hook[0];
            if (!$nodes->exists($id)) {
                $nodes->add(Node::create($hook[0])->withNs($this->getNs($hook[1]))->withId($id)->withBody(NodeCollection::create()));
            }
            $nodes = $nodes->get($id);
        }
        return $nodes;
    }

    protected function createNode($parent, $tag, $ns, $body, $id = null, $attributes = null)
    {
        $node = Node::create($tag)->withNs($this->getNs($ns))->withId($id)->withBody($body);
        if (!!$id) $node->withId($id);
        if (!!$attributes) {
            foreach ($attributes as $key => $value) {
                $node->withAttr($key, $value);
            }
        }
        $parent->add($node);
    }

    protected function getValue($name)
    {
        $chain = is_array($name) ? $name : array($name);
        $nodes = $this->nodes;
        foreach ($chain as $hook) {
            if (!$nodes->exists($hook)) {
                return null;
            }
            $nodes = $nodes->get($hook);
        }
        if ($nodes instanceof Node) return $nodes->body;
        if ($nodes instanceof NodeCollection) return $nodes;
        return $nodes;
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
     * Generate common nodes
     *
     * @return mixed
     */
    protected abstract function generateCommonNodes();

    /**
     * Generate nodes from given array
     *
     * @param array $body
     * @return Node[]
     */
    private function generateNodesFromArray($body)
    {
        foreach ($body as $key => $value) {
            $this->{$key} = $value;
        }
    }

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

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->nodes->{$name});
    }


    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (method_exists($this, $method)) return $this->{$method}();
        if (isset($this->nodes->{$name})) return $this->nodes->{$name};
        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        switch (true) {
            case method_exists($this, $method):
                $this->{$method}($value);
                break;
            case isset($this->nodes->{$name}):
                $this->nodes->{$name} = $value;
                break;
        }
    }
}