<?php
/* Bestandsnaam: Gebruiker.php
 * Ontwikkelaar: Jeffrey Lensen 
 * Project: Wireless Grootebroek
 * 
 * Datum: 16-09-2005
 * Omschrijving: 
 * De klasse Gebruiker
 *
 */

class Gebruiker extends Persoon {
	private $intPersoonID;
	private $strGebruikersnaam;
	private $strWachtwoord;
	private $strWachtwoordClear;
	private $intUserLevel;	
	private $strStatus;
	private $strIPadres;
	private $dtLastLogin;
	private $dtAanmeld;
	private $strNotificaties;

	// Constructor
	public function __construct() {
		parent::__construct();
		$this->intPersoonID = "";
		$this->strGebruikersnaam = "";
		$this->strWachtwoord = "";
		$this->strWachtwoordClear = "";
		$this->intUserLevel = "";
		$this->strStatus = "nee";
		$this->strIPadres = "";
		$this->dtLastLogin = "";
		$this->dtAanmeld = "";
		$this->strNotificaties = "nee";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
    public function setValues( $arrGebruiker, $booCheck = false ) {
    	parent::setValues($arrGebruiker, $booCheck);
		//if(isset($arrGebruiker['id'])) 
			//$this->setPersoonID($arrGebruiker['id'], $booCheck);
		if(isset($arrGebruiker['persoonid'])) {
			parent::setID($arrGebruiker['persoonid'], $booCheck);
			$this->setPersoonID($arrGebruiker['persoonid'], $booCheck);
		}
		if(isset($arrGebruiker['gebruikersnaam'])) 
			$this->setGebruikersNaam( $arrGebruiker['gebruikersnaam'], $booCheck);
		if(isset($arrGebruiker['wachtwoord'])) 
			$this->setWachtwoord( $arrGebruiker['wachtwoord'], $booCheck);
		if(isset($arrGebruiker['wachtwoordclear'])) 
			$this->setWachtwoordClear( $arrGebruiker['wachtwoordclear'], $booCheck);
		if(isset($arrGebruiker['userlevel'])) 
			$this->setUserLevel( $arrGebruiker['userlevel'], $booCheck);
		if(isset($arrGebruiker['status']))
			$this->setStatus( $arrGebruiker['status'], $booCheck);
		if(isset($arrGebruiker['ipadres']))
			$this->setIPadres( $arrGebruiker['ipadres'], $booCheck);
		if(isset($arrGebruiker['lastlogin']))
			$this->setLastLogin( $arrGebruiker['lastlogin'], $booCheck );
		if(isset($arrGebruiker['aanmelddatum'])) 
			$this->setAanmeldDatum( $arrGebruiker['aanmelddatum'], $booCheck);
		if(isset($arrGebruiker['notificaties'])) 
			$this->setNotificaties( $arrGebruiker['notificaties'], $booCheck );
    }
	// Functies om de waarden van de variabelen op te vragen
	public function getPersoonID() {
		return $this->intPersoonID;	
	}
	public function getGebruikersNaam($strFixed = false) {
		if($strFixed == false) return $this->strGebruikersnaam;
		else return fixData($this->strGebruikersnaam);
	}
	public function getWachtwoord($strFixed = false) {
		if($strFixed == false) return $this->strWachtwoord;
		else return fixData($this->strWachtwoord);
	}	
	public function getWachtwoordClear($strFixed = false) {
		if($strFixed == false) return $this->strWachtwoordClear;
		else return fixData($this->strWachtwoordClear);
	}	
	public function getUserLevel($strFixed = false) {
		if($strFixed == false) return $this->intUserLevel;
		else return fixData($this->intUserLevel);
	}
	public function getStatus($strFixed = false) {
		if($strFixed == false) return $this->strStatus;
		else return fixData($this->strStatus);
	}
	public function getStatusNet() {	
		if($this->getStatus() == "-1") return "Gebruiker is niet actief";
		elseif($this->getStatus() == "0") return "Gebruiker heeft de registratie nog niet bevestigd";
		elseif($this->getStatus() == "1") return "Gebruiker is actief";
	}
	public function getIPadres($strFixed = false) {
		if($strFixed == false) return $this->strIPadres;
		else return fixData($this->strIPadres);
	}
	public function getLastLogin($strFixed = false) {
		if($strFixed == false) return $this->dtLastLogin;
		else return fixData($this->dtLastLogin);
	}
	public function getLastLoginDatumNet() {
		return getHTMLDatumNet($this->dtLastLogin);
	}
	public function getAanmeldDatum($strFixed = false) {
		if($strFixed == false) return $this->dtAanmeld;
		else return fixData($this->dtAanmeld);
	}
	public function getAanmeldDatumNet() {
		return getHTMLDatumNet($this->dtAanmeld);
	}
	public function getNotificaties($strFixed = false) {
		if($strFixed == false) return $this->strNotificaties;
		else return fixData($this->strNotificaties);
	}
	// Functies om de variabelen een waarde te geven
	public function setPersoonID($intNewID, $booCheck = false) {
		if($booCheck == true) $this->intPersoonID = checkData( $intNewID, 'integer');
		else $this->intPersoonID = $intNewID;
	}
    public function setGebruikersNaam($strNewGebruikersnaam, $booCheck = false) {
		if($booCheck == true) $this->strGebruikersnaam = checkData($strNewGebruikersnaam);
		else $this->strGebruikersnaam = $strNewGebruikersnaam;
	}
	public function setWachtwoord($strNewWachtwoord, $booCheck = false) {
		if($booCheck == true) $this->strWachtwoord = checkData($strNewWachtwoord);
		else $this->strWachtwoord = $strNewWachtwoord;
	}	
	public function setWachtwoordClear($strNewWachtwoord, $booCheck = false) {
		if($booCheck == true) $this->strWachtwoordClear = checkData($strNewWachtwoord);
		else $this->strWachtwoordClear = $strNewWachtwoord;
	}
	public function setUserLevel($intUserLevel, $booCheck = false) {
		if($booCheck == true) $this->intUserLevel = checkData($intUserLevel, 'integer');
		else $this->intUserLevel = $intUserLevel;
	}
	public function setStatus($strNewStatus, $booCheck = false) {
		if($booCheck == true) $this->strStatus = checkData($strNewStatus);
		else $this->strStatus = $strNewStatus;
	}
	public function setIPadres($strNewIP, $booCheck = false) {
		if($booCheck == true) $this->strIPadres = checkData($strNewIP);
		else $this->strIPadres = $strNewIP;
	}
	public function setLastLogin($dtNewLastLogin, $booCheck = false) {
		if($booCheck == true) $this->dtLastLogin = checkData($dtNewLastLogin);
		else $this->dtLastLogin = $dtNewLastLogin;
	}
	public function setAanmeldDatum($dtNewAanmeldDatum, $booCheck = false) {
		if($booCheck == true) $this->dtAanmeldDatum = checkData($dtNewAanmeldDatum);	
		else $this->dtAanmeldDatum = $dtNewAanmeldDatum;	
	}
	public function setNotificaties($strNewNotificaties, $booCheck = false) {
		if($booCheck == true) $this->strNotificaties = checkData($strNewNotificaties);
		else $this->strNotificaties = $strNewNotificaties;
	}
}

?>
