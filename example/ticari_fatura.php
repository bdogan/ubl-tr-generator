<?php
/**
 * This files generate ticari fatura based on UBL-TR v1.2
 */

include '../vendor/autoload.php';
include_once 'functions.php';

$schema = new \UblTr\Schema\TicariFatura();

$schema->addNote('test ediyorum');
$schema->addNote('test ediyorum');



$generator = new \UblTr\Generator($schema);

header('Content-Type: text/xml');
echo $generator->generate()->asXML();