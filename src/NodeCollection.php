<?php

namespace UblTr;

class NodeCollection implements \Iterator
{

    public $id;

    /**
     * Node Collection
     *
     * @var Node[]
     */
    protected $nodes = array();

    /**
     * NodeCollection constructor.
     * @param array $nodes
     */
    public function __construct($nodes = array())
    {
        if (!is_array($nodes)) return;
        foreach ($nodes as $node) {
            $this->add($node);
        }
    }

    /**
     * @param array $nodes
     * @return NodeCollection
     */
    public static function create($nodes = array())
    {
        return new NodeCollection($nodes);
    }

    /**
     * @param Node $node
     */
    public function add($node)
    {
        if (!!$node->id) {
            $this->nodes[$node->id] = $node;
        } else {
            $this->nodes[] = $node;
        }
    }

    /**
     * @param $id
     * @return Node
     */
    public function get($id)
    {
        return $this->exists($id) ? $this->nodes[$id] : null;
    }

    public function exists($id)
    {
        return array_key_exists($id, $this->nodes);
    }

    public function current()
    {
        return current($this->nodes);
    }

    public function next()
    {
        return next($this->nodes);
    }

    public function key()
    {
        return key($this->nodes);
    }

    public function valid()
    {
        $key = key($this->nodes);
        return ($key !== NULL && $key !== FALSE);
    }

    public function rewind()
    {
        reset($this->nodes);
    }

    public function __isset($name)
    {
        return $this->exists($name);
    }

    public function __get($name)
    {
        if ($node = $this->get($name)) {
            return $node->body;
        }
        return null;
    }

    public function __set($name, $value)
    {
        if ($node = $this->get($name)) {
            $node->body = $value;
            return;
        }
    }

    private function bodyToArray($body, $node)
    {
        switch (true) {
            case is_callable($body):
                return $this->bodyToArray(call_user_func($body, $node), $node);
            case $body instanceof NodeCollection: return $body->toArray();
            default: return $body;
        }
    }

    public function toArray()
    {
        $results = array();
        foreach ($this->nodes as $node) {
            if (!!$node->id) {
                $results[$node->id] = $this->bodyToArray($node->body, $node);
            } else if(!!$node->tag && !isset($results[$node->tag])) {
                $results[$node->tag] = $this->bodyToArray($node->body, $node);
            }
        }
        return $results;
    }
}