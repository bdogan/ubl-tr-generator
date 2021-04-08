<?php

namespace UblTr;

use SimpleXMLElement;
use UblTr\Ns\CommonBasicComponents2;
use UblTr\Ns\Invoice2;
use UblTr\Ns\XMLSchemaInstance;

class Generator
{

    /**
     * Generator schema
     *
     * @var Schema
     */
    protected $schema;

    /**
     * Generator options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Generator constructor.
     * @param Schema|string $schema
     * @param array $options
     * @throws GeneratorException
     */
    public function __construct($schema, $options = array())
    {
        // Check class
        if (is_string($schema) && !class_exists($schema)) {
            throw new GeneratorException("$schema not found");
        }

        // Merge options
        $this->options = array_replace_recursive($this->options, (array) $options);

        // Create schema
        $this->schema = is_string($schema) ? new $schema($options['schema']) : $schema;
        if (!($this->schema instanceof Schema)) {
            throw new GeneratorException(get_class($schema) . " is not instance of UblTR\\Schema");
        }

        // Unset schema options
        unset($this->options['schema']);

    }

    /**
     * @return SimpleXMLElement
     */
    public function generate()
    {
        // Get root node
        $rootTag = $this->schema->getTag();

        // Prepare required ns
        $requiredNs = $this->schema->getRequireNs();
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
        $nodes = $this->schema->getNodes();
        foreach ($nodes as $tag => $node) {
            $this->renderNode($node, $xml);
        }

        return $xml;
    }

    /**
     * @param Node $node
     * @param SimpleXMLElement $xml
     */
    private function renderNode(&$node, &$xml)
    {
        $body = $node->body;
        $childXml = null;
        switch (true) {

            // Node Collection
            case $body instanceof NodeCollection:
                $childXml = $xml->addChild($node->tag, null, $node->ns->getUri());
                foreach ($body as $tag => $innerNode) {
                    $this->renderNode($innerNode, $childXml);
                }
                break;

            // Callable
            case is_callable($body):
                $body = call_user_func($body, $node);
                $childXml = $xml->addChild($node->tag, $body, $node->ns->getUri());
                break;

            // Plain text array
            case is_array($body):
                $childXml = array();
                foreach ($body as $_body) {
                    $childXml[] = $xml->addChild($node->tag, $_body, $node->ns->getUri());
                }
                break;

            // Boolean
            case is_bool($body):
                $childXml = $xml->addChild($node->tag, $body === true ? 'true' : 'false', $node->ns->getUri());
                break;

            // ?string
            case is_string($body) || is_numeric($body) || is_null($body):
                $childXml = $xml->addChild($node->tag, $body, $node->ns->getUri());
        }

        // Add node attributes
        if (!is_null($childXml)) {
            $childXml = !is_array($childXml) ? array($childXml) : $childXml;
            foreach ($node->attributes as $key => $value) {
                $childXml->attributes($key, $value);
            }
        }
    }

}