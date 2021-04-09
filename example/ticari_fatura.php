<?php
/**
 * This files generate ticari fatura based on UBL-TR v1.2
 */

include '../vendor/autoload.php';
include_once 'functions.php';

$schema = new \UblTr\Schema\TicariFatura();

$schema->Senaryo = "EARSIVFATURA";
$schema->FaturaNo = "BT02021000000260";
$schema->FaturaTipi = "SATIS";
$schema->Ettn = "1112-2233-4455-6677-8899";

// Fatura bilgileri
$schema->FaturaTarihi = "2021-01-01";
$schema->FaturaSaati = "11:12";

$schema->SiparisNo = "Sipariş no";
$schema->SiparisTarihi = "Sipariş tarihi";

$schema->IrsaliyeNo = "İrsaliye no";
$schema->IrsaliyeTarihi = "İrsaliye tarihi";

// Gönderen bilgileri
$schema->Website = "ww.google.com";

$schema->VergiNo = "vergi numarası";
$schema->MersisNo = "Mersis numarası";
$schema->HizmetNo = "Hizmet numarası";
$schema->TicariSicilNo = "Ticari sicil no";

$schema->Unvan = "ABC Bilisim";
$schema->Adres = "asdasdasd";
$schema->BinaNo = "ddd";
$schema->PostaKodu = "59860";
$schema->Ilce = "Beykoz";
$schema->Il = "İstanbul";

$schema->UlkeKodu = "TR";
$schema->Ulke = "Türkiye";

$schema->VergiDairesi = "vergi dairesi";

// Alıcı bilgileri
$schema->AliciWebsite = "AAA ww.google.com";

$schema->AliciVergiNo = "AAA vergi numarası";
$schema->AliciKimlikNo = "AAA Kimlik numarası";
$schema->AliciMersisNo = "AAA Mersis numarası";
$schema->AliciHizmetNo = "AAA Hizmet numarası";
$schema->AliciTicariSicilNo = "AAA Ticari sicil no";

$schema->AliciUnvan = "AAA ABC Bilisim";
$schema->AliciAdres = "AAA asdasdasd";
$schema->AliciBinaNo = "AAA ddd";
$schema->AliciPostaKodu = "AAA 59860";
$schema->AliciIlce = "AAA Beykoz";
$schema->AliciIl = "AAA İstanbul";

$schema->AliciUlkeKodu = "AAA TR";
$schema->AliciUlke = "AAA Türkiye";

$schema->AliciVergiDairesi = "AAA vergi dairesi";



$generator = new \UblTr\Generator($schema);

header('Content-Type: text/xml');
echo $generator->generate()->asXML();