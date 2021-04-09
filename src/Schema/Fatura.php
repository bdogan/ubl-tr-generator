<?php

namespace UblTr\Schema;

use UblTr\Node;
use UblTr\NodeCollection;
use UblTr\Schema;

/**
 * Class Fatura
 * @package UblTr\Schema
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
 * @property string $AliciWebsite
 * @property string $AliciVergiNo
 * @property string $AliciKimlikNo
 * @property string $AliciMersisNo
 * @property string $AliciHizmetNo
 * @property string $AliciTicariSicilNo
 * @property string $AliciUnvan
 * @property string $AliciAdres
 * @property string $AliciBinaNo
 * @property string $AliciPostaKodu
 * @property string $AliciIl
 * @property string $AliciIlce
 * @property string $AliciUlke
 * @property string $AliciUlkeKodu
 * @property string $AliciVergiDairesi
 */
class Fatura extends Schema
{

    /**
     * @var string tag
     */
    protected $tag = 'Invoice';


    /**
     * @return array
     */
    protected function generateCommonNodes()
    {
        $cbc = $this->getNs('cbc');

        return array(
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
            Node::create('LineCountNumeric')->withNs($cbc)->withBody([ $this, 'lineCountNumeric' ])
        );
    }

    /**
     * @param $note
     * @param $id
     * @return $this
     */
    public function addNote($note, $id = null)
    {
        $this->nodes->add(Node::create('Note')->withNs($this->getNs('cbc'))->withBody($note)->withId($id));
        return $this;
    }

    /**
     * Line counter
     *
     * @return int
     */
    public function lineCountNumeric()
    {
        return 0;
    }

    // --- Getters & Setters

    // -- Sipariş No
    protected function getSiparisNo()
    {
        $node = $this->nodes->get('OrderReference');
        return $node ? $node->get('No') : null;
    }

    protected function setSiparisNo($value)
    {
        $node = Node::create('ID')->withNs($this->getNs('cbc'))->withId('No')->withBody($value);
        $this->prepare('OrderReference', 'cac')->add($node);
    }

    // -- Sipariş Tarihi
    protected function getSiparisTarihi()
    {
        $node = $this->nodes->get('OrderReference');
        return $node ? $node->get('Tarih') : null;
    }

    protected function setSiparisTarihi($value)
    {
        $node = Node::create('IssueDate')->withNs($this->getNs('cbc'))->withId('Tarih')->withBody($value);
        $this->prepare('OrderReference', 'cac')->add($node);
    }

    // -- İrsaliye No
    protected function getIrsaliyeNo()
    {
        $node = $this->nodes->get('DespatchDocumentReference');
        return $node ? $node->get('No') : null;
    }

    protected function setIrsaliyeNo($value)
    {
        $node = Node::create('ID')->withNs($this->getNs('cbc'))->withId('No')->withBody($value);
        $this->prepare('DespatchDocumentReference', 'cac')->add($node);
    }

    // -- İrsaliye Tarihi
    protected function getIrsaliyeTarihi()
    {
        $node = $this->nodes->get('DespatchDocumentReference');
        return $node ? $node->get('Tarih') : null;
    }

    protected function setIrsaliyeTarihi($value)
    {
        $node = Node::create('IssueDate')->withNs($this->getNs('cbc'))->withId('Tarih')->withBody($value);
        $this->prepare('DespatchDocumentReference', 'cac')->add($node);
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