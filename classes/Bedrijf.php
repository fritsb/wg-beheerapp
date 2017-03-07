<?php
/* Bestandsnaam: Bedrijf.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 16-09-2005
 * Omschrijving: 
 * De klasse Bedrijf
 *
 */

class Bedrijf {
	private $intID;
	private $strBedrijfsnaam;
	private $strKVK;
	private $strStraat;
	private $strHuisnr;
	private $strPostcode;
	private $strWoonplaats;
	private $strTelefoon;
	private $strFax;
	private $strEmailadres;
	private $strWebsite;
	private $dtToevoeging;
	
	// Constructor
	public function __construct() {
		$this->intID = "";
		$this->strBedrijfsnaam = "";
		$this->strKVK;
		$this->strStraat = "";
		$this->strHuisnr = "";
		$this->strPostcode = "";
		$this->strWoonplaats = "";
		$this->strTelefoon = "";
		$this->strFax = "";
		$this->strEmailadres = "";
		$this->strWebsite = "";
		$this->dtToevoeging = "";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
	public function setValues( $arrBedrijf, $booCheck = false ) {
		if(isset($arrBedrijf['id'])) 
			$this->setID( $arrBedrijf['id'], $booCheck);
		if(isset($arrBedrijf['bedrijfsnaam'])) 
			$this->setBedrijfsnaam( $arrBedrijf['bedrijfsnaam'], $booCheck);
		if(isset($arrBedrijf['kvk']))
		    $this->setKVK($arrBedrijf['kvk'], $booCheck); 
		if(isset($arrBedrijf['straat']))
			$this->setStraat( $arrBedrijf['straat'], $booCheck);
		if(isset($arrBedrijf['huisnr']))
			$this->setHuisnr( $arrBedrijf['huisnr'], $booCheck);  
		if(isset($arrBedrijf['postcode']))
			$this->setPostcode( $arrBedrijf['postcode'], $booCheck);
		if(isset($arrBedrijf['woonplaats'])) 
			$this->setWoonplaats( $arrBedrijf['woonplaats'], $booCheck);
		if(isset($arrBedrijf['telefoon'])) 
			$this->setTelefoon( $arrBedrijf['telefoon'], $booCheck);
		if(isset($arrBedrijf['fax']))
			$this->setFax( $arrBedrijf['fax'], $booCheck);
		if(isset($arrBedrijf['emailadres']))
			$this->setEmailadres( $arrBedrijf['emailadres'], $booCheck);
		if(isset($arrBedrijf['website']))
			$this->setWebsite( $arrBedrijf['website'], $booCheck);
		if(isset($arrBedrijf['toevoegdatum']))
			$this->setToevoegDatum( $arrBedrijf['toevoegdatum'], $booCheck);
	}
	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;
	}
	public function getBedrijfsnaam($strFixed = false) {
		if($strFixed == false) return $this->strBedrijfsnaam;
		else return fixData( $this->strBedrijfsnaam );
	}
	public function getKVK($strFixed = false) {
		if($strFixed == false) return $this->strKVK;
		else return fixData( $this->strKVK );
	}
	public function getStraat($strFixed = false) {
		if($strFixed == false) return $this->strStraat;	
		else return fixData( $this->strStraat );
	}
	public function getStraatNaam( $strFixed = false) {
		return $this->getStraat( $strFixed );
	}
	public function getHuisnr($strFixed = false) {
		if($strFixed == false) return $this->strHuisnr;
		else return fixData( $this->strHuisnr );
	}
	public function getHuisNummer($strFixed = false) {
		return $this->getHuisnr($strFixed);
	}
	public function getAdres($strFixed = false) {
		if($strFixed == false) return $this->strStraat." ".$this->strHuisnr;
		else return fixData( $this->strStraat." ".$this->strHuisnr  );
	}
	public function getPostcode($strFixed = false) {
		if($strFixed == false) return $this->strPostcode;
		else return fixData( $this->strPostcode );
	}
	public function getWoonplaats($strFixed = false) {
		if($strFixed == false) return $this->strWoonplaats;
		else return fixData( $this->strWoonplaats );
	}
	public function getTelefoon($strFixed = false) {
		if($strFixed == false) return $this->strTelefoon;
		else return fixData( $this->strTelefoon );
	}
	public function getFax($strFixed = false) {
		if($strFixed == false) return $this->strFax;
		else return fixData( $this->strFax );
	}
	public function getEmailadres($strFixed = false) {
		if($strFixed == false) return $this->strEmailadres;	
		else return fixData( $this->strEmailadres );
	}
	public function getEmail($strFixed = false) {
		return $this->getEmailadres($strFixed);
	}
	public function getWebsite($strFixed = false) {
		if($strFixed == false) return $this->strWebsite;	
		else return fixData( $this->strWebsite );
	}
	public function getToevoegDatum($strFixed = false) {
	        if($strFixed == false) return $this->dtToevoeging;
	        else return fixData( $this->dtToevoeging );
	}

	// Functies om de variabelen een waarde te geven
	public function setID($intNewID, $booCheck = false) {
		if($booCheck == true ) $this->intID = checkData($intNewID, 'integer');
		else $this->intID = $intNewID;
	}
	public function setBedrijfsnaam($strNewBedrijfsnaam, $booCheck = false) {
		if($booCheck == true ) $this->strBedrijfsnaam = checkData($strNewBedrijfsnaam);
		else $this->strBedrijfsnaam = $strNewBedrijfsnaam;
	}
	public function setKVK($strNewKVK, $booCheck = false) {
		if($booCheck == true ) $this->strKVK = checkData($strNewKVK);
		else $this->strKVK = $strNewKVK;
	}	
	public function setStraat($strNewStraat, $booCheck = false) {
		if($booCheck == true ) $this->strStraat = checkData($strNewStraat);
		else $this->strStraat = $strNewStraat;
	}
	public function setHuisnr($strNewHuisnr, $booCheck = false) {
		if($booCheck == true ) $this->strHuisnr = checkData($strNewHuisnr);
		else $this->strHuisnr = $strNewHuisnr;
	}
	public function setAdres($strNewAdres, $booCheck = false) {
		$arrAdres = explode(" ",$strNewAdres);
		$intArraySize = count($arrAdres);
		if($intArraySize >= 2) {
			$this->setStraat($arrAdres[0], $booCheck);
			$this->setHuisnr($arrAdres[$intArraySize - 1], $booCheck);
		}
		elseif($intArraySize == 1) {
			$this->setStraat( $arrAdres[0], $booCheck );
		}
	}
	public function setPostcode($strNewPostcode, $booCheck = false) {
		if($booCheck == true ) $this->strPostcode = checkData($strNewPostcode );
		else $this->strPostcode = $strNewPostcode;
	}
	public function setWoonplaats($strNewWoonplaats, $booCheck = false) {
		if($booCheck == true ) $this->strWoonplaats = checkData($strNewWoonplaats );
		else $this->strWoonplaats = $strNewWoonplaats;
	}
	public function setTelefoon($strNewTelefoon, $booCheck = false) {
		if($booCheck == true ) $this->strTelefoon = checkData($strNewTelefoon );
		else $this->strTelefoon = $strNewTelefoon;
	}
	public function setFax($strNewFax, $booCheck = false) {
		if($booCheck == true ) $this->strFax = checkData($strNewFax );
		else $this->strFax = $strNewFax;
	}
	public function setEmailadres($strNewEmailadres, $booCheck = false) {
		if($booCheck == true ) $this->strEmailadres = checkData($strNewEmailadres );
		else $this->strEmailadres = $strNewEmailadres;
	}
	public function setEmail($strNewEmailadres, $booCheck = false) {
		$this->setEmailadres($strNewEmailadres, $booCheck);
	}
	public function setWebsite($strNewWebsite, $booCheck = false) {
		if($booCheck == true ) $this->strWebsite = checkData($strNewWebsite );
		else $this->strWebsite = $strNewWebsite;
	}
	public function setToevoegDatum($dtNewToevoeging, $booCheck = false) {
		if($booCheck == true ) $this->dtToevoeging = checkData($dtNewToevoeging );
		else $this->dtToevoeging = $dtNewToevoeging;
	}
}

?>
