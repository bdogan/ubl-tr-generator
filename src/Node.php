<?php

namespace UblTr;

/**
 * Class Node
 * @package UblTr
 *
 * @property string $id
 * @property Ns $ns
 * @property string $tag
 * @property string|Node|Node[]|NodeCollection $body
 * @property boolean $require
 * @property array $attributes
 */
class Node
{

    /**
     * Node Content
     *
     * @var array
     */
    protected $content = array();

    /**
     * Default Node content
     *
     * @var array|null
     */
    private $default_content = array(
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
        $this->boot();
        $this->content = array_replace_recursive($this->default_content, $this->content, (array) $content);
    }

    /**
     * Boot
     */
    protected function boot() { }

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
        if (! $this->body instanceof NodeCollection) $this->body = NodeCollection::create(array());
        $nodes = $this->body;
        foreach ($chain as $hook) {
            $id = isset($hook[2]) ? $hook[2] : $hook[0];
            if (!$nodes->exists($id)) {
                $nodes->add(Node::create($hook[0])->withNs(NsLoader::load($hook[1]))->withId($id)->withBody(NodeCollection::create()));
            }
            $nodes = $nodes->get($id);
        }
        return $nodes;
    }

    protected function createNode($parent, $tag, $ns, $body, $id = null, $attributes = null)
    {
        $node = Node::create($tag)->withNs(NsLoader::load($ns))->withId($id)->withBody($body);
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
        $nodes = $this->body;
        if (! $nodes instanceof NodeCollection) return $nodes;
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

    /**
     * @param $name
     * @return mixed|string|Node|Node[]|NodeCollection|null
     */
    public function get($name) {
        switch (true) {
            case (array_key_exists($name, $this->content)): return $this->content[$name];
            case $this->body instanceof NodeCollection && $this->body->exists($name): return $this->body->get($name)->body;
            default: return null;
        }
    }

    /**
     * @param $node
     */
    public function add($node)
    {
        if (!$this->body instanceof NodeCollection) $this->content['body'] = NodeCollection::create();
        $this->content['body']->add($node);
    }

    /**
     * @param $name
     * @return bool
     */
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
     * Getter
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (method_exists($this, $method)) return $this->{$method}();
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
        $method = 'set' . $name;
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }
        switch (true) {
            case (array_key_exists($name, $this->content)): return $this->content[$name] = $value;
            case $this->body instanceof NodeCollection && $this->body->exists($name): return $this->body->{$name} = $value;
        }
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