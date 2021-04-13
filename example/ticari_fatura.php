<?php
/**
 * This files generate ticari fatura based on UBL-TR v1.2
 */

include '../vendor/autoload.php';
include_once 'functions.php';

$node = new \UblTr\Node\Invoice();

$node->Senaryo = "EARSIVFATURA";
$node->FaturaNo = "BT02021000000260";
$node->FaturaTipi = "SATIS";
$node->Ettn = "1112-2233-4455-6677-8899";

// Fatura bilgileri
$node->FaturaTarihi = "2021-01-01";
$node->FaturaSaati = "11:12";

$node->SiparisNo = "Sipariş no";
$node->SiparisTarihi = "Sipariş tarihi";

$node->IrsaliyeNo = "İrsaliye no";
$node->IrsaliyeTarihi = "İrsaliye tarihi";

// Gönderen bilgileri
$node->Website = "www.google.com";

$node->VergiNo = "vergi numarası";
$node->MersisNo = "Mersis numarası";
$node->HizmetNo = "Hizmet numarası";
$node->TicariSicilNo = "Ticari sicil no";

$node->Unvan = "ABC Bilisim";
$node->Adres = "asdasdasd";
$node->BinaNo = "ddd";
$node->PostaKodu = "59860";
$node->Ilce = "Beykoz";
$node->Il = "İstanbul";

$node->UlkeKodu = "TR";
$node->Ulke = "Türkiye";

$node->VergiDairesi = "vergi dairesi";

$node->Telefon = "fffddd";
$node->Mail = "ffasdfasdfsdf";

// Alıcı bilgileri
$node->AliciWebsite = "AAA ww.google.com";

$node->AliciVergiNo = "AAA vergi numarası";
$node->AliciKimlikNo = "AAA Kimlik numarası";
$node->AliciMersisNo = "AAA Mersis numarası";
$node->AliciHizmetNo = "AAA Hizmet numarası";
$node->AliciTicariSicilNo = "AAA Ticari sicil no";

$node->AliciUnvan = "AAA ABC Bilisim";
$node->AliciAdres = "AAA asdasdasd";
$node->AliciBinaNo = "AAA ddd";
$node->AliciPostaKodu = "AAA 59860";
$node->AliciIlce = "AAA Beykoz";
$node->AliciIl = "AAA İstanbul";

$node->AliciUlkeKodu = "AAA TR";
$node->AliciUlke = "AAA Türkiye";

$node->AliciVergiDairesi = "AAA vergi dairesi";

$node->AliciTelefon = "test";
$node->AliciMail = "ssssddd";

$node->AliciAd = "Burak";
$node->AliciSoyad = "Doğan";

$node->OdemeTarihi = "Ödeme Tarihi";
$node->OdemeNotu = "Ödeme Notu";

$invoiceLine = new \UblTr\Node\InvoiceLine();
$invoiceLine->SiraNo = 1;
$invoiceLine->Miktar = 20;

$invoiceLine2 = new \UblTr\Node\InvoiceLine();
$invoiceLine2->SiraNo = 2;
$invoiceLine2->Miktar = 30;

$node->Urunler = array($invoiceLine, $invoiceLine2);

pr($node->Urunler);

$generator = new \UblTr\Generator($node);

header('Content-Type: text/xml');
echo $generator->generate()->asXML();