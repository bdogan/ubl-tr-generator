<?php

namespace UblTr\Ns;

use UblTr\Ns;

/**
 * Class CommonBasicComponents2
 * @package UblTr\Ns
 */
class CommonBasicComponents2 extends Ns
{

    /**
     * @var string
     */
    protected $parent = Invoice2::class;

    /**
     * @inheritdoc
     */
    protected $uri = 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2';

}