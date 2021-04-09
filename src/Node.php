<?php

namespace UblTr;

/**
 * Class Node
 * @package UblTr
 *
 * @property string $id
 * @property Ns $ns
 * @property string $tag
 * @property mixed|Node[] $body
 * @property boolean $require
 * @property array $attributes
 */
class Node
{

    /**
     * Node content
     *
     * @var array|null
     */
    protected $content = array(
        'id' => null,
        'ns' => null,
        'tag' => null,
        'body' => null,
        'require' => false,
        'attributes' => [],
    );

    /**
     * Node constructor.
     * @param Ns $ns
     * @param string $tag
     * @param mixed $content
     */
    public function __construct($content = array())
    {
        $this->content = array_replace_recursive($this->content, (array) $content);
    }

    /**
     * Getter
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Setter
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        switch (true) {
            case (array_key_exists($name, $this->content)): return $this->content[$name] = $value;
            case $this->body instanceof NodeCollection && $this->body->exists($name): return $this->body->{$name} = $value;
        }
    }

    public function get($name) {
        switch (true) {
            case (array_key_exists($name, $this->content)): return $this->content[$name];
            case $this->body instanceof NodeCollection && $this->body->exists($name): return $this->body->get($name)->body;
            default: return null;
        }
    }

    public function add($node)
    {
        if (!$this->body instanceof NodeCollection) $this->content['body'] = NodeCollection::create();
        $this->content['body']->add($node);
    }

    public function exists($name)
    {
        switch (true) {
            case (array_key_exists($name, $this->content)):
            case $this->body instanceof NodeCollection && $this->body->exists($name): return true;
            default: return false;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->exists($name);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->content[$name]);
    }

    /**
     * Static creator
     *
     * @param $tag
     * @param mixed|Node[] $body
     * @return Node
     */
    public static function create($tag, $body = null)
    {
        return new Node(array(
            'tag' => $tag,
            'body' => $body
        ));
    }

    /**
     * Set require state
     *
     * @param $state
     * @return $this
     */
    public function withRequired($state)
    {
        $this->content['require'] = $state;
        return $this;
    }

    /**
     * Set namespace
     *
     * @param $ns
     * @return $this
     */
    public function withNs($ns)
    {
        $this->content['ns'] = $ns;
        return $this;
    }

    /**
     * Set body
     *
     * @param $body
     * @return $this
     */
    public function withBody($body)
    {
        $this->content['body'] = $body;
        return $this;
    }

    /**
     * Append body
     *
     * @param $body
     * @return $this
     */
    public function appendBody($body)
    {
        if (!is_array($this->body)) {
            $this->content['body'] = $this->body ? array($this->body) : array();
        }
        $this->content['body'][] = $body;
        return $this;
    }

    /**
     * Set attribute value
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function withAttr($name, $value)
    {
        $this->content['attributes'][$name] = $value;
        return $this;
    }

    /**
     * Set node id for external access
     *
     * @param $id
     * @return $this
     */
    public function withId($id)
    {
        $this->content['id'] = $id;
        return $this;
    }

}