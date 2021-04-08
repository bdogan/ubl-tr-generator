<?php

namespace UblTr\Schema;

use UblTr\Node;
use UblTr\NodeCollection;
use UblTr\Schema;

/**
 * Class Fatura
 * @package UblTr\Schema
 *
 * @property string $UBLVersionID
 * @property string $CustomizationID
 */
class Fatura extends Schema
{

    /**
     * @var string tag
     */
    protected $tag = 'Invoice';

    /**
     * @return array
     */
    protected function generateNodes()
    {
        $cbc = $this->getNs('cbc');
        $cac = $this->getNs('cac');

        return array(
            Node::create('UBLVersionID')->withNs($cbc)->withBody('2.1'),
            Node::create('CustomizationID')->withNs($cbc)->withBody('TR1.2'),
            Node::create('ProfileID')->withNs($cbc)->withRequired(true),
            Node::create('ID')->withNs($cbc),
            Node::create('CopyIndicator')->withNs($cbc)->withBody(false),
            Node::create('UUID')->withNs($cbc),
            Node::create('IssueDate')->withNs($cbc),
            Node::create('IssueTime')->withNs($cbc),
            Node::create('InvoiceTypeCode')->withNs($cbc)->withRequired(true)->withBody('SATIS'),
            Node::create('DocumentCurrencyCode')->withNs($cbc)->withBody('TRY'),
            Node::create('LineCountNumeric')->withNs($cbc)->withBody([ $this, 'lineCountNumeric' ]),
            Node::create('OrderReference')->withNs($cac)->withBody(NodeCollection::create(array(
                Node::create('ID')->withNs($cbc),
                Node::create('IssueDate')->withNs($cbc),
            ))),
        );
    }

    /**
     * @param $note
     * @return $this
     */
    public function addNote($note)
    {
        if (!$node = $this->nodes->get('Note')) {
            $node = Node::create('Note')->withNs($this->getNs('cbc'));
            $this->nodes->add($node);
        }
        $node->appendBody($note);
        return $this;
    }

    /**
     * Line counter
     *
     * @return int
     */
    public function lineCountNumeric()
    {
        return 0;
    }


}