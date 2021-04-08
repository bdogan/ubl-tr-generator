<?php

namespace UblTr;

/**
 * Class Node
 * @package UblTr
 *
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
        if (array_key_exists($name, $this->content)) return $this->content[$name];
        return null;
    }

    /**
     * Setter
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->content)) {
            $this->content[$name] = $value;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->content);
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
        $this->require = $state;
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
        $this->ns = $ns;
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
        $this->body = $body;
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
        $this->attributes[$name] = $value;
        return $this;
    }

}