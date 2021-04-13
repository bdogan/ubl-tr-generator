<?php

namespace UblTr\Ns;

use UblTr\Ns;

/**
 * Class Xades
 * @package UblTr\Ns
 */
class Xades extends Ns
{

    /**
     * @var string
     */
    protected $parent = Invoice2::class;

    /**
     * @inheritdoc
     */
    protected $uri = 'http://uri.etsi.org/01903/v1.3.2#';

}