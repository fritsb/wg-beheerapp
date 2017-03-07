<?php
/* Bestandsnaam: Status.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 31-10-2005
 * Omschrijving: 
 * De klasse Status
 *
 */

class Status {
	private $intID;
	private $strSoort;
	private $dtBeginDatum;
	private $dtEindDatum;
	private $dtToevoeging;
	private $dtDatum;
	private $strInfo;
	private $strStatus;
	private $strIPadres;
	private $strUniek;
	private $intPersoonID;

	// Constructor
	public function __construct() {
		$this->intID = "";
		$this->strSoort = "";
		$this->dtBeginDatum = "";
		$this->dtEindDatum = "";
		$this->dtToevoeging = "";
		$this->dtDatum = "";
		$this->strInfo = "";
		$this->strStatus = "";
		$this->strIPadres = "";
		$this->strUniek = "";
		$this->intPersoonID = "";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
	public function setValues( $arrStatus, $booCheck = false ) {
		if(isset($arrStatus['id'])) 
			$this->setID( $arrStatus['id'], $booCheck);
		if(isset($arrStatus['begindatum'])) 
			$this->setBeginDatum( $arrStatus['begindatum'] );
		if(isset($arrStatus['einddatum'])) 
			$this->setEindDatum( $arrStatus['einddatum'], $booCheck); 
		if(isset($arrStatus['toevoegdatum']))
			$this->setToevoegDatum( $arrStatus['toevoegdatum'], $booCheck); 
		if(isset($arrStatus['soort']))
			$this->setSoort( $arrStatus['soort'], $booCheck);
		if(isset($arrStatus['extrainfo']))
			$this->setInfo( $arrStatus['extrainfo'], $booCheck);
		if(isset($arrStatus['status']))
		    $this->setStatus( $arrStatus['status'], $booCheck);
		if(isset($arrStatus['ipadres']))
			$this->setIPadres( $arrStatus['ipadres'], $booCheck);
		if(isset($arrStatus['uniekestring']))
			$this->setUniekeString( $arrStatus['uniekestring'], $booCheck);
		if(isset($arrStatus['persoonid'])) 
			$this->setPersoonID( $arrStatus['persoonid'], $booCheck);
	}
	
	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;
	}
	public function getBeginDatum($booFixed = false) {
		if($booFixed == false) return $this->dtBeginDatum;
		else return fixData($this->dtBeginDatum);
	}
	public function getEindDatum($booFixed = false) {
		if($booFixed == false) return $this->dtEindDatum;
		else return fixData($this->dtEindDatum);
	}
	public function getEindDatumNet() {
		return getHTMLDatumNet($this->dtEindDatum);
	}
	public function getToevoegDatum($strFixes = false) {
		if($booFixed == false) return $this->dtToevoeging;
		else return fixData($this->dtToevoeging);
	}
	public function getDatum($strFixes = false) {
		if($booFixed == false) return $this->dtDatum;
		else return fixData($this->dtDatum);
	}
	public function getSoort($booFixed = false) {
		if($booFixed == false) return $this->strSoort;
		else return fixData($this->strSoort);
	}
	public function getInfo($booFixed = false) {
	    if($booFixed == false) return $this->strInfo;
	    else return fixData($this->strInfo);
	}
	public function getStatus($booFixed = false) {
	    if($booFixed == false) return $this->strStatus;
	    else return fixData($this->strStatus);
	}
	public function getUniekeString($booFixed = false) {
	    if($booFixed == false) return $this->strUniek;
	    else return fixData($this->strUniek);
	}
	public function getIPadres($booFixed = false) {
		if($booFixed == false) return $this->strIPadres;	
		else return fixData($this->strIPadres);
	}
	public function getPersoonID() {
		return $this->intPersoonID;
	}
	public function getGebruikersID() {
		return $this->getPersoonID();	
	}
	// Functies om de variabelen een waarde te geven
	public function setID($intNewID, $booCheck = false) {
		if($booCheck == true) $this->intID = checkData($intNewID, 'integer');
		else $this->intID = $intNewID;
	}
	public function setBeginDatum($dtNewBeginDatum, $booCheck = false) {
		if($booCheck == true) $this->dtBeginDatum = checkData($dtNewBeginDatum);
		else $this->dtBeginDatum = $dtNewBeginDatum;
	}
	public function setEindDatum($dtNewEindDatum, $booCheck = false) {
		if($booCheck == true) $this->dtEindDatum = checkData($dtNewEindDatum);
		else $this->dtEindDatum = $dtNewEindDatum;
	}
	public function setToevoegDatum($dtNewToevoeging, $booCheck = false) {
	    if($booCheck == true) $this->dtToevoeging = checkData($dtNewToevoeging);
	    else $this->dtToevoeging = $dtNewToevoeging;
	}
	public function setDatum($dtNewDatum, $booCheck = false) {
	    if($booCheck == true) $this->dtDatum = checkData($dtNewDatum);
	    else $this->dtDatum = $dtNewDatum;
	}
	public function setSoort($strNewSoort, $booCheck = false) {
		if($booCheck == true) $this->strSoort = checkData($strNewSoort);
		else $this->strSoort = $strNewSoort;
	}
	public function setInfo($strNewInfo, $booCheck = false) {
	    if($booCheck == true) $this->strInfo = checkData($strNewInfo);
	    else $this->strInfo = $strNewInfo;
	}
	public function setStatus($strNewStatus, $booCheck = false) {
	    if($booCheck == true) $this->strStatus = checkData($strNewStatus);
	    else $this->strStatus = $strNewStatus;
	}
	public function setIPadres($strNewIPadres, $booCheck = false) {
		if($booCheck == true) $this->strIPadres = checkData($strNewIPadres);
		else $this->strIPadres = $strNewIPadres;
	}
	public function setUniekeString($strNewUniek, $booCheck = false) {
	    if($booCheck == true) $this->strUniek = checkData($strNewUniek);
	    else $this->strUniek = $strNewUniek;
	}
	public function setPersoonID($intNewPersoonID, $booCheck = false) {
		if($booCheck == true) $this->intPersoonID = checkData($intNewPersoonID, 'integer');
		else $this->intPersoonID = $intNewPersoonID;
	}
	public function setGebruikersID($intNewPersoonID, $booCheck = false) {
		$this->setPersoonID($intNewPersoonID, $booCheck);
	}
}

?>
