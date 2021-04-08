<?php

namespace UblTr;

class NodeCollection implements \Iterator
{

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
        $this->nodes[$node->tag] = $node;
    }

    /**
     * @param $tag
     * @return Node
     */
    public function get($tag)
    {
        return $this->exists($tag) ? $this->nodes[$tag] : null;
    }

    public function exists($tag)
    {
        return array_key_exists($tag, $this->nodes);
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
        return array_map(function ($node) {
            return $this->bodyToArray($node->body, $node);
        }, $this->nodes);
    }
}