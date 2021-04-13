<?php

namespace UblTr\Node;

use UblTr\Node;
use UblTr\NodeCollection;
use UblTr\NsLoader;

/**
 * Class InvoiceLine
 * @package UblTr\Schema
 *
 * @property string SiraNo
 * @property string Miktar
 */
class InvoiceLine extends Node
{

    /**
     * Class boot method
     */
    protected function boot()
    {
        $cac = NsLoader::load('cac');
        $cbc = NsLoader::load('cbc');

        $this->content = array(
            'tag' => 'InvoiceLine',
            'ns' => NsLoader::load('cac'),
            'body' => NodeCollection::create(array(
                Node::create('ID')->withNs($cbc)->withId('SiraNo'),
                Node::create('InvoicedQuantity')->withNs($cbc)->withAttr('unitCode', 'C62')->withId('Miktar')
            ))
        );
    }

}
