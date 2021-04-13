<?php

namespace UblTr\Node;

use UblTr\Node;
use UblTr\NodeCollection;
use UblTr\NsLoader;

/**
 * Class Invoice
 * @package UblTr\Node
 *
 * @property string $UBLVersionID
 * @property string $CustomizationID
 * @property string $Senaryo
 * @property string $FaturaNo
 * @property string $CopyIndicator
 * @property string $Ettn
 * @property string $FaturaTarihi
 * @property string $FaturaSaati
 * @property string $FaturaTipi
 * @property string $ParaBirimi
 * @property string $SiparisNo
 * @property string $SiparisTarihi
 * @property string $IrsaliyeNo
 * @property string $IrsaliyeTarihi
 * @property string $Website
 * @property string $VergiNo
 * @property string $MersisNo
 * @property string $HizmetNo
 * @property string $TicariSicilNo
 * @property string $Unvan
 * @property string $Adres
 * @property string $BinaNo
 * @property string $PostaKodu
 * @property string $Il
 * @property string $Ilce
 * @property string $Ulke
 * @property string $UlkeKodu
 * @property string $VergiDairesi
 * @property string $Telefon
 * @property string $Mail
 * @property string $AliciWebsite
 * @property string $AliciVergiNo
 * @property string $AliciKimlikNo
 * @property string $AliciMersisNo
 * @property string $AliciHizmetNo
 * @property string $AliciTicariSicilNo
 * @property string $AliciUnvan
 * @property string $AliciAd
 * @property string $AliciSoyad
 * @property string $AliciAdres
 * @property string $AliciBinaNo
 * @property string $AliciPostaKodu
 * @property string $AliciIl
 * @property string $AliciIlce
 * @property string $AliciUlke
 * @property string $AliciUlkeKodu
 * @property string $AliciVergiDairesi
 * @property string $AliciTelefon
 * @property string $AliciMail
 * @property string $OdemeTarihi
 * @property string $OdemeNotu
 * @property InvoiceLine[] $Urunler
 */
class Invoice extends Node
{

    protected function boot()
    {
        $cbc = NsLoader::load('cbc');

        $this->content = array(
            'tag' => 'Invoice',
            'body' => NodeCollection::create(array(
                Node::create('UBLVersionID')->withNs($cbc)->withBody('2.1')->withId('UBLVersionID'),
                Node::create('CustomizationID')->withNs($cbc)->withBody('TR1.2')->withId('CustomizationID'),
                Node::create('ProfileID')->withNs($cbc)->withRequired(true)->withId('Senaryo'),
                Node::create('ID')->withNs($cbc)->withId('FaturaNo'),
                Node::create('CopyIndicator')->withNs($cbc)->withBody(false)->withId('CopyIndicator'),
                Node::create('UUID')->withNs($cbc)->withId('Ettn'),
                Node::create('IssueDate')->withNs($cbc)->withId('FaturaTarihi'),
                Node::create('IssueTime')->withNs($cbc)->withId('FaturaSaati'),
                Node::create('InvoiceTypeCode')->withNs($cbc)->withRequired(true)->withBody('SATIS')->withId('FaturaTipi'),
                Node::create('DocumentCurrencyCode')->withNs($cbc)->withBody('TRY')->withId('ParaBirimi'),
                Node::create('LineCountNumeric')->withNs($cbc)->withBody([ $this, 'lineCountNumeric' ]),
                Node::create('InvoiceLine')->withNs($cbc)->withBody([ $this, 'lineCountNumeric' ])
            ))
        );
    }

    public function lineCountNumeric()
    {
        return 0;
    }

    // --- Getters & Setters

    // -- Sipariş No
    protected function getSiparisNo()
    {
        return $this->getValue(array('OrderReference', 'No'));
    }

    protected function setSiparisNo($value)
    {
        $this->createNode($this->prepare(array(
            array('OrderReference', 'cac')
        )), 'ID', 'cbc', $value, 'No');
    }

    // -- Sipariş Tarihi
    protected function getSiparisTarihi()
    {
        return $this->getValue(array('OrderReference', 'Tarih'));
    }

    protected function setSiparisTarihi($value)
    {
        $this->createNode($this->prepare(array(
            array('OrderReference', 'cac')
        )), 'IssueDate', 'cbc', $value, 'Tarih');
    }

    // -- İrsaliye No
    protected function getIrsaliyeNo()
    {
        return $this->getValue(array('DespatchDocumentReference', 'No'));
    }

    protected function setIrsaliyeNo($value)
    {
        $this->createNode($this->prepare(array(
            array('DespatchDocumentReference', 'cac')
        )), 'ID', 'cbc', $value, 'No');
    }

    // -- İrsaliye Tarihi
    protected function getIrsaliyeTarihi()
    {
        return $this->getValue(array('DespatchDocumentReference', 'Tarih'));
    }

    protected function setIrsaliyeTarihi($value)
    {
        $this->createNode($this->prepare(array(
            array('DespatchDocumentReference', 'cac')
        )), 'IssueDate', 'cbc', $value, 'Tarih');
    }

    // -- Vergi no
    protected function setVergiNo($value)
    {
        $this->setPartyIdentification('AccountingSupplierParty', 'VKN', $value);
    }

    protected function getVergiNo()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'VKN', 'Id'));
    }

    // -- Mersis No
    protected function setMersisNo($value)
    {
        $this->setPartyIdentification('AccountingSupplierParty', 'MERSISNO', $value);
    }

    protected function getMersisNo()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'MERSISNO', 'Id'));
    }

    // -- Hizmet No
    protected function setHizmetNo($value)
    {
        $this->setPartyIdentification('AccountingSupplierParty', 'HIZMETNO', $value);
    }

    protected function getHizmetNo()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'HIZMETNO', 'Id'));
    }

    // -- Ticari Sicil No
    protected function setTicariSicilNo($value)
    {
        $this->setPartyIdentification('AccountingSupplierParty', 'TICARETSICILNO', $value);
    }

    protected function getTicariSicilNo()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'TICARETSICILNO', 'Id'));
    }

    // -- Unvan
    protected function setUnvan($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PartyName', 'cac', 'Unvan'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getUnvan()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'Unvan', 'Name'));
    }

    // -- Adres
    protected function setAdres($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'StreetName', 'cbc', $value, 'StreetName');
    }

    protected function getAdres()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'StreetName'));
    }

    // -- BinaNo
    protected function setBinaNo($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'BuildingNumber', 'cbc', $value, 'BuildingNumber');
    }

    protected function getBinaNo()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'BuildingNumber'));
    }

    // -- İlçe
    protected function setIlce($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'CitySubdivisionName', 'cbc', $value, 'CitySubdivisionName');
    }

    protected function getIlce()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'CitySubdivisionName'));
    }

    // -- İl
    protected function setIl($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'CityName', 'cbc', $value, 'CityName');
    }

    protected function getIl()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'CityName'));
    }

    // -- Ülke
    protected function setUlke($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
            array('Country', 'cac'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getUlke()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'Country', 'Name'));
    }

    // -- ÜlkeKodu
    protected function setUlkeKodu($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
            array('Country', 'cac'),
        )), 'IdentificationCode', 'cbc', $value, 'IdentificationCode');
    }

    protected function getUlkeKodu()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'Country', 'IdentificationCode'));
    }

    // -- Websitesi
    protected function setWebsite($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
        )), 'WebsiteURI', 'cbc', $value, 'WebsiteURI');
    }

    protected function getWebsite()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'WebsiteURI'));
    }

    // -- Postakodu
    protected function setPostaKodu($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'PostalZone', 'cbc', $value, 'PostalZone');
    }

    protected function getPostaKodu()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PostalAddress', 'PostalZone'));
    }

    // -- VergiDairesi
    protected function setVergiDairesi($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('PartyTaxScheme', 'cac'),
            array('TaxScheme', 'cac'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getVergiDairesi()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'PartyTaxScheme', 'TaxScheme', 'Name'));
    }

    // -- Telefon
    protected function setTelefon($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('Contact', 'cac'),
        )), 'Telephone', 'cbc', $value, 'Telephone');
    }

    protected function getTelefon()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'Contact', 'Telephone'));
    }

    // -- Mail
    protected function setMail($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingSupplierParty', 'cac'),
            array('Party', 'cac', 0),
            array('Contact', 'cac'),
        )), 'ElectronicMail', 'cbc', $value, 'ElectronicMail');
    }

    protected function getMail()
    {
        return $this->getValue(array('AccountingSupplierParty', 0, 'Contact', 'ElectronicMail'));
    }

    // -- Alıcı Vergi no
    protected function setAliciVergiNo($value)
    {
        $this->setPartyIdentification('AccountingCustomerParty', 'VKN', $value);
    }

    protected function getAliciVergiNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'VKN', 'Id'));
    }

    // -- Alıcı Kimlik no
    protected function setAliciKimlikNo($value)
    {
        $this->setPartyIdentification('AccountingCustomerParty', 'TCKN', $value);
    }

    protected function getAliciKimlikNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'TCKN', 'Id'));
    }

    // -- AlıcıMersis No
    protected function setAliciMersisNo($value)
    {
        $this->setPartyIdentification('AccountingCustomerParty', 'MERSISNO', $value);
    }

    protected function getAliciMersisNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'MERSISNO', 'Id'));
    }

    // -- AlıcıHizmet No
    protected function setAliciHizmetNo($value)
    {
        $this->setPartyIdentification('AccountingCustomerParty', 'HIZMETNO', $value);
    }

    protected function getAliciHizmetNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'HIZMETNO', 'Id'));
    }

    // -- AlıcıTicari Sicil No
    protected function setAliciTicariSicilNo($value)
    {
        $this->setPartyIdentification('AccountingCustomerParty', 'TICARETSICILNO', $value);
    }

    protected function getAliciTicariSicilNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'TICARETSICILNO', 'Id'));
    }

    // -- AlıcıUnvan
    protected function setAliciUnvan($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PartyName', 'cac', 'Unvan'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getAliciUnvan()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'Unvan', 'Name'));
    }

    // -- AlıcıAd
    protected function setAliciAd($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('Person', 'cac'),
        )), 'FirstName', 'cbc', $value, 'FirstName');
    }

    protected function getAliciAd()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'Person', 'FirstName'));
    }

    // -- AlıcıSoyad
    protected function setAliciSoyad($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('Person', 'cac'),
        )), 'FamilyName', 'cbc', $value, 'FamilyName');
    }

    protected function getAliciSoyad()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'Person', 'FamilyName'));
    }

    // -- AlıcıAdres
    protected function setAliciAdres($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'StreetName', 'cbc', $value, 'StreetName');
    }

    protected function getAliciAdres()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'StreetName'));
    }

    // -- AlıcıBinaNo
    protected function setAliciBinaNo($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'BuildingNumber', 'cbc', $value, 'BuildingNumber');
    }

    protected function getAliciBinaNo()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'BuildingNumber'));
    }

    // -- Alıcıİlçe
    protected function setAliciIlce($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'CitySubdivisionName', 'cbc', $value, 'CitySubdivisionName');
    }

    protected function getAliciIlce()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'CitySubdivisionName'));
    }

    // -- Alıcıİl
    protected function setAliciIl($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'CityName', 'cbc', $value, 'CityName');
    }

    protected function getAliciIl()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'CityName'));
    }

    // -- AlıcıÜlke
    protected function setAliciUlke($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
            array('Country', 'cac'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getAliciUlke()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'Country', 'Name'));
    }

    // -- AlıcıÜlkeKodu
    protected function setAliciUlkeKodu($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
            array('Country', 'cac'),
        )), 'IdentificationCode', 'cbc', $value, 'IdentificationCode');
    }

    protected function getAliciUlkeKodu()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'Country', 'IdentificationCode'));
    }

    // -- AlıcıWebsitesi
    protected function setAliciWebsite($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
        )), 'WebsiteURI', 'cbc', $value, 'WebsiteURI');
    }

    protected function getAliciWebsite()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'WebsiteURI'));
    }

    // -- AlıcıPostakodu
    protected function setAliciPostaKodu($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PostalAddress', 'cac'),
        )), 'PostalZone', 'cbc', $value, 'PostalZone');
    }

    protected function getAliciPostaKodu()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PostalAddress', 'PostalZone'));
    }

    // -- AlıcıVergiDairesi
    protected function setAliciVergiDairesi($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('PartyTaxScheme', 'cac'),
            array('TaxScheme', 'cac'),
        )), 'Name', 'cbc', $value, 'Name');
    }

    protected function getAliciVergiDairesi()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'PartyTaxScheme', 'TaxScheme', 'Name'));
    }

    // -- AlıcıTelefon
    protected function setAliciTelefon($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('Contact', 'cac'),
        )), 'Telephone', 'cbc', $value, 'Telephone');
    }

    protected function getAliciTelefon()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'Contact', 'Telephone'));
    }

    // -- AlıcıMail
    protected function setAliciMail($value)
    {
        $this->createNode($this->prepare(array(
            array('AccountingCustomerParty', 'cac'),
            array('Party', 'cac', 0),
            array('Contact', 'cac'),
        )), 'ElectronicMail', 'cbc', $value, 'ElectronicMail');
    }

    protected function getAliciMail()
    {
        return $this->getValue(array('AccountingCustomerParty', 0, 'Contact', 'ElectronicMail'));
    }

    // -- Ödeme Tarihi
    protected function setOdemeTarihi($value)
    {
        $this->createNode($this->prepare(array(
            array('PaymentTerms', 'cac'),
        )), 'PaymentDueDate', 'cbc', $value, 'OdemeTarihi');
    }

    protected function getOdemeTarihi()
    {
        return $this->getValue(array('PaymentTerms', 'OdemeTarihi'));
    }

    // -- Ödeme Notu
    protected function setOdemeNotu($value)
    {
        $this->createNode($this->prepare(array(
            array('PaymentTerms', 'cac'),
        )), 'Note', 'cbc', $value, 'Note');
    }

    protected function getOdemeNotu()
    {
        return $this->getValue(array('PaymentTerms', 'Note'));
    }

    // -- Ürünler
    protected function setUrunler($value)
    {
        foreach ($value as $line) {
            $this->add($line);
        }
    }

    protected function getUrunler()
    {
        return $this->getValue(array('Urunler'))->toArray();
    }


    // -- Helper functions --------
    private function setPartyIdentification($root, $schemaId, $value)
    {
        $this->createNode($this->prepare(array(
            array($root, 'cac'),
            array('Party', 'cac', 0),
            array('PartyIdentification', 'cac', $schemaId),
        )), 'ID', 'cbc', $value, 'Id', array( 'schemaID' => $schemaId ));
    }
}