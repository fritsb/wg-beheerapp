<?php
/* Bestandsnaam: Persoon.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 11-09-2005
 * Omschrijving: 
 * De klasse Persoon
 *
 */

class Persoon {
	private $intID;
	private $strVoornaam;
	private $strTussenvoegsel;
	private $strAchternaam;	
	private $strStraat;
	private $strHuisnr;
	private $strPostcode;
	private $strWoonplaats;
	private $strTelThuis;
	private $strTelWerk;
	private $strTelMobiel;
	private $strEmail;
	private $dtToevoeging;
	private $intBedrijfsID;

	// Constructor
	public function __construct() {
		$this->intID = "";
		$this->strVoornaam = "";
		$this->strTussenvoegsel = "";
		$this->strAchternaam = "";
		$this->strStraat = "";
		$this->strHuisnr = "";
		$this->strPostcode = "";
		$this->strWoonplaats = "";
		$this->strTelThuis = "";
		$this->strTelWerk = "";
		$this->strTelMobiel = "";
		$this->strEmail = "";
		$this->dtToevoeging = "";
		$this->intBedrijfsID = "";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
    public function setValues( $arrPersoon, $booCheck = false ) {
		if(isset($arrPersoon['id'])) 
			$this->setID( $arrPersoon['id'], $booCheck);
		if(isset($arrPersoon['voornaam'])) 
			$this->setVoornaam( $arrPersoon['voornaam'], $booCheck);
		if(isset($arrPersoon['tussenvoegsel'])) 
			$this->setTussenvoegsel( $arrPersoon['tussenvoegsel'], $booCheck);
		if(isset($arrPersoon['achternaam'])) 
			$this->setAchternaam( $arrPersoon['achternaam'], $booCheck);
		if(isset($arrPersoon['straat']))
			$this->setStraat( $arrPersoon['straat'], $booCheck);
		elseif(isset($arrPersoon['straatnaam']))
			$this->setStraat( $arrPersoon['straatnaam'], $booCheck);
		if(isset($arrPersoon['huisnr']))
			$this->setHuisnr( $arrPersoon['huisnr'], $booCheck);
		if(isset($arrPersoon['postcode']))
			$this->setPostcode( $arrPersoon['postcode'], $booCheck);
		if(isset($arrPersoon['woonplaats'])) 
			$this->setWoonplaats( $arrPersoon['woonplaats'], $booCheck);
		if(isset($arrPersoon['telthuis'])) 
			$this->setTelThuis( $arrPersoon['telthuis'], $booCheck);
		if(isset($arrPersoon['telwerk']))
			$this->setTelWerk( $arrPersoon['telwerk'], $booCheck);
		if(isset($arrPersoon['telmobiel']))
			$this->setTelMobiel( $arrPersoon['telmobiel'], $booCheck);
		if(isset($arrPersoon['email']))
			$this->setEmail( $arrPersoon['email'], $booCheck);
		if(isset($arrPersoon['toevoegdatum']))
			$this->setToevoegDatum($arrPersoon['toevoegdatum'], $booCheck);
		if(isset($arrPersoon['bedrijfsid']))
			$this->setBedrijfsID( $arrPersoon['bedrijfsid'], $booCheck);
		else $this->setBedrijfsID(0);
    }
    	
	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;	
	}
	public function getPersoonID() {
		return $this->intID;	
	}
	public function getVoornaam($strFixed = false) {
		if($strFixed == false) return $this->strVoornaam;
		else return fixData($this->strVoornaam);
	}
	public function getTussenvoegsel($strFixed = false) {
		if($strFixed == false) return $this->strTussenvoegsel;
		else return fixData($this->strTussenvoegsel);
	}	
	public function getAchternaam($strFixed = false) {
		if($strFixed == false) return $this->strAchternaam;
		else return fixData($this->strAchternaam);
	}
	public function getVolledigeNaam($strFixed = false) {
		$strVolledigeNaam = $this->strVoornaam;
		if($this->strTussenvoegsel != "") $strVolledigeNaam .= " ".$this->strTussenvoegsel;
		$strVolledigeNaam .= " ".$this->strAchternaam;
		
		if($strFixed == false) return $strVolledigeNaam;
		else return fixData($strVolledigeNaam);
	}
	public function getStraat($strFixed = false) {
		if($strFixed == false) return $this->strStraat;	
		else return fixData($this->strStraat);
	}
	public function getStraatnaam($strFixed = false) {
		return $this->getStraat($strFixed);
	}
	public function getHuisnr($strFixed = false) {
		if($strFixed == false) return $this->strHuisnr;
		else return fixData($this->strHuisnr);
	}
	public function getHuisNummer($strFixed = false) {
		return $this->getHuisNr($strFixed);
	}
	public function getAdres($strFixed = false) {
		if($strFixed == false) return $this->strStraat." ".$this->strHuisnr;
		else return fixData($this->strStraat." ".$this->strHuisnr);
	}
	public function getPostcode($strFixed = false) {
		if($strFixed == false) return $this->strPostcode;
		else return fixData($this->strPostcode);
	}
	public function getWoonplaats($strFixed = false) {
		if($strFixed == false) return $this->strWoonplaats;
		else return fixData($this->strWoonplaats);
	}
	public function getTelThuis($strFixed = false) {
		if($strFixed == false) return $this->strTelThuis;	
		else return fixData($this->strTelThuis);
	}
	public function getTelWerk($strFixed = false) {
		if($strFixed == false) return $this->strTelWerk;	
		else return fixData($this->strTelWerk);
	}
	public function getTelMobiel($strFixed = false) {
		if($strFixed == false) return $this->strTelMobiel;	
		else return fixData($this->strTelMobiel);
	}
	public function getEmail($strFixed = false) {
		if($strFixed == false) return $this->strEmail;	
		else return fixData($this->strEmail);
	}
	public function getToevoegDatum($strFixed = false) {
	        if($strFixed == false) return $this->dtToevoeging;
	        else return fixData($this->dtToevoeging);
	}
	public function getToevoegDatumNet() {
		return getHTMLDatumNet($this->dtToevoeging);
	}
	public function getBedrijfsID($strFixed = false) {
		if($strFixed == false) return $this->intBedrijfsID;
		else return fixData($this->intBedrijfsID);
	}
	public function getBedrijfID( $strFixed = false) {
		return $this->getBedrijfsID($strFixed);	
	}
	// Functies om de variabelen een waarde te geven
	public function setID($intNewID, $booCheck = false) {
		if($booCheck == true) $this->intID = checkData($intNewID, 'integer');
		else $this->intID = $intNewID;
	}
	public function setVoornaam($strNewVoornaam, $booCheck = false) {
		if($booCheck == true) $this->strVoornaam = checkData($strNewVoornaam);
		else $this->strVoornaam = $strNewVoornaam;
	}
	public function setTussenvoegsel($strNewTussenvoegsel, $booCheck = false) {
		if($booCheck == true) $this->strTussenvoegsel = checkData($strNewTussenvoegsel);
		else $this->strTussenvoegsel = $strNewTussenvoegsel;
	}	
	public function setAchternaam($strNewAchternaam, $booCheck = false) {
		if($booCheck == true) $this->strAchternaam = checkData($strNewAchternaam);
		else $this->strAchternaam = $strNewAchternaam;
	}
	public function setVolledigeNaam($strNewNaam, $booCheck = false) {
		$arrNaam = explode(" ",$strNewNaam);
		$intArraySize = count($arrNaam);
		if($intArraySize == 2) {
			$this->setVoornaam($arrNaam[0], $booCheck);
			$this->setAchternaam($arrNaam[1], $booCheck);
		}
		elseif($intArraySize == 3) {
			$this->setVoornaam($arrNaam[0], $booCheck);
			$this->setTussenvoegsel($arrNaam[1], $booCheck );
			$this->setAchternaam($arrNaam[2], $booCheck);
		}
	}
	public function setStraat($strNewStraat, $booCheck = false) {
		if($booCheck == true) $this->strStraat = checkData($strNewStraat);
		else $this->strStraat = $strNewStraat;
	}
	public function setHuisnr($strNewHuisnr, $booCheck = false) {
		if($booCheck == true) $this->strHuisnr = checkData($strNewHuisnr);
		else $this->strHuisnr = $strNewHuisnr;
	}
	public function setAdres($strNewAdres, $booCheck = false) {
		$arrAdres = explode(" ",$strNewAdres);
		$intArraySize = count($arrAdres);
		if($intArraySize == 2) {
			$this->setStraat($arrAdres[0], $booCheck);
			$this->setHuisnr($arrAdres[1], $booCheck);
		}
		else {
			$this->strStraat = $arrAdres[0];
		}
	}
	public function setPostcode($strNewPostcode, $booCheck = false) {
		if($booCheck == true) $this->strPostcode = checkData($strNewPostcode);
		else $this->strPostcode = $strNewPostcode;
	}
	public function setWoonplaats($strNewWoonplaats, $booCheck = false) {
		if($booCheck == true) $this->strWoonplaats = checkData($strNewWoonplaats);
		else $this->strWoonplaats = $strNewWoonplaats;
	}
	public function setTelThuis($strNewTelThuis, $booCheck = false) {
		if($booCheck == true) $this->strTelThuis = checkData($strNewTelThuis);
		else $this->strTelThuis = $strNewTelThuis;
	}
	public function setTelWerk($strNewTelWerk, $booCheck = false) {
		if($booCheck == true) $this->strTelWerk = checkData($strNewTelWerk);
		else $this->strTelWerk = $strNewTelWerk;
	}
	public function setTelMobiel($strNewTelMobiel, $booCheck = false) {
		if($booCheck == true) $this->strTelMobiel = checkData($strNewTelMobiel);
		else $this->strTelMobiel = $strNewTelMobiel;	
	}
	public function setEmail($strNewEmail, $booCheck = false) {
		if($booCheck == true) $this->strEmail = checkData($strNewEmail);
		else $this->strEmail = $strNewEmail;	
	}
	public function setToevoegDatum($dtNewToevoeging, $booCheck = false) {
		if($booCheck == true) $this->dtToevoeging = checkData($dtNewToevoeging);
		else $this->dtToevoeging = $dtNewToevoeging;
	}
	public function setBedrijfsID($intNewBedrijfsID, $booCheck = false) {
	    if($booCheck == true) $this->intBedrijfsID = checkData( $intNewBedrijfsID, 'integer');
	    else $this->intBedrijfsID = $intNewBedrijfsID;
	}
}

?>
