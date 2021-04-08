<?php

namespace UblTr\Ns;

use UblTr\Ns;

/**
 * Class XMLSchemaInstance
 * @package UblTr\Ns
 */
class XMLSchemaInstance extends Ns
{

    /**
     * @inheritdoc
     */
    protected $prefix = 'xsi';

    /**
     * @inheritdoc
     */
    protected $uri = 'http://www.w3.org/2001/XMLSchema-instance';

    /**
     * @inheritdoc
     */
    protected $schemaLocation = 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 UBL-Invoice-2.1.xsd';

}