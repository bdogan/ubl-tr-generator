<?php

namespace UblTr\Ns;

use UblTr\Ns;

/**
 * Class CommonAggregateComponents2
 * @package UblTr\Ns
 */
class CommonAggregateComponents2 extends Ns
{

    /**
     * @var string
     */
    protected $parent = Invoice2::class;

    /**
     * @inheritdoc
     */
    protected $uri = 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2';

}