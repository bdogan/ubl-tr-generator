<?php

namespace UblTr\Ns;

use UblTr\Ns;

/**
 * Class XMLSignatures
 * @package UblTr\Ns
 */
class XMLSignatures extends Ns
{

    /**
     * @var string
     */
    protected $parent = Invoice2::class;

    /**
     * @inheritdoc
     */
    protected $uri = 'http://www.w3.org/2000/09/xmldsig#';

}