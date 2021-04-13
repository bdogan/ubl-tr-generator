<?php

namespace UblTr;

use SimpleXMLElement;
use UblTr\Exception\GeneratorException;

class Generator
{

    /**
     * Generator node
     *
     * @var Node
     */
    protected $node;

    /**
     * Generator constructor.
     * @param Node|string $node
     * @throws GeneratorException
     */
    public function __construct($node)
    {
        // Check class
        if (is_string($node) && !class_exists($node)) {
            throw new GeneratorException("$node not found");
        }

        // Create node
        $this->node = is_string($node) ? new $node() : $node;
        if (!($this->node instanceof Node)) {
            throw new GeneratorException(get_class($node) . " is not instance of UblTR\\Node");
        }

    }

    /**
     * @return SimpleXMLElement
     */
    public function generate()
    {
        // Get root node
        $rootTag = $this->node->tag;

        // Root ns resolving
        $requiredNs = NsLoader::loaded();
        $nsAttributes = array();
        foreach ($requiredNs as $ns) {
            if (!!$ns->getParent() && !isset($nsAttributes[$ns->getParent()->getPrefix()])) {
                $nsAttributes[$ns->getParent()->getPrefix()] = $ns->getParent()->getUri();
            }
            $nsAttributes[(!!$ns->getParent() ? $ns->getParent()->getPrefix() . ':' : '') . $ns->getPrefix()] = $ns->getUri();
        }
        $rootAttributes = [];
        foreach ($nsAttributes as $key => $value) {
            $rootAttributes[] = sprintf('%s="%s"', $key, $value);
        }

        // Generate root xml element
        $xml = new SimpleXMLElement(sprintf("<%s %s></%s>", $rootTag, implode(' ', $rootAttributes), $rootTag));

        // Add nodes to xml
        $nodes = $this->node->body;

        // Node -> NodeCollection
        if ($nodes instanceof Node) $nodes = NodeCollection::create(array($node));

        // Render nodes
        foreach ($nodes as $node) {
            $this->renderNode($node, $xml);
        }

        // Return xml
        return $xml;
    }

    /**
     * @param Node $node
     * @param SimpleXMLElement $xml
     */
    private function renderNode(&$node, &$xml)
    {
        $childXml = null;
        $body = $node->body;
        $nsUrl = $node->ns ? $node->ns->getUri() : null;
        switch (true) {

            // Node Collection
            case $body instanceof NodeCollection:
                $childXml = $xml->addChild($node->tag, null, $nsUrl);
                foreach ($body as $tag => $innerNode) {
                    $this->renderNode($innerNode, $childXml);
                }
                break;

            // Callable
            case (is_array($body) && count($body) === 2 && $body[0] instanceof Node && is_string($body[1])):
                $node->body = call_user_func($body, $node);
                $this->renderNode($node, $xml);
                break;

            // Boolean
            case is_bool($body):
                $childXml = $xml->addChild($node->tag, ($body === true ? 'true' : 'false'), $nsUrl);
                break;

            // Node
            case ($body instanceof Node):
                $this->renderNode($body, $xml);
                break;

            // Boolean
            case is_array($body):
                $childXml = array();
                foreach ($body as $_node) {
                    if (!($_node instanceof Node)) throw new GeneratorException('Only nodes allow to be render');
                    $childXml[] = $xml->addChild($_node->tag, ($body === true ? 'true' : 'false'), $nsUrl);
                }
                break;

            // ?string
            case is_string($body) || is_numeric($body) || is_null($body):
                $childXml = $xml->addChild($node->tag, $body, $nsUrl);
        }

        // Add node attributes
        if (!is_null($childXml)) {
            $childXml = !is_array($childXml) ? array($childXml) : $childXml;
            foreach ($node->attributes as $key => $value) {
                foreach ($childXml as $_childXml) {
                    $_childXml->addAttribute($key, $value);
                }
            }
        }
    }

}