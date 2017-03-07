<?php
/* Bestandsnaam: Ban.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 16-09-2005
 * Omschrijving: 
 * De klasse Ban
 *
 */

class Ban {
	private $intID;
	private $dtBeginDatum;
	private $dtEindDatum;
	private $dtToevoeging;
	private $strReden;
	private $strStatus;
	private $strIPadres;
	private $intGebruikersID;
	private $dtDatum;

	// Constructor
	public function __construct() {
		$this->intID = "";
		$this->dtBeginDatum = "";
		$this->dtEindDatum = "";
		$this->dtToevoeging = "";
		$this->strReden = "";
		$this->strStatus = "";
		$this->strIPadres = "";
		$this->intGebruikersID = "";
		$this->dtDatum = "";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
	public function setValues( $arrBan, $booCheck = false ) {
		if(isset($arrBan['id'])) 
			$this->setID( $arrBan['id'], $booCheck);
		if(isset($arrBan['begindatum'])) 
			$this->setBeginDatum( $arrBan['begindatum'] );
		if(isset($arrBan['einddatum'])) 
			$this->setEindDatum( $arrBan['einddatum'], $booCheck); 
		if(isset($arrBan['toevoegdatum']))
			$this->setToevoegDatum( $arrBan['toevoegdatum'], $booCheck); 
		if(isset($arrBan['reden']))
			$this->setReden( $arrBan['reden'], $booCheck);
		if(isset($arrBan['status']))
		    $this->setStatus( $arrBan['status'], $booCheck);
		if(isset($arrBan['ipadres']))
			$this->setIPadres( $arrBan['ipadres'], $booCheck);
		if(isset($arrBan['gebruikersid']))
			$this->setGebruikersID( $arrBan['gebruikersid'], $booCheck);
			
		if(isset($arrBan['begindatumjaar']) && isset($arrBan['begindatummaand']) && 
				isset($arrBan['begindatumdag']) && isset($arrBan['begindatumuur'])
				&& isset($arrBan['begindatumminuten'])) {
				$this->setBeginDatum($arrBan['begindatumjaar']."-".$arrBan['begindatummaand'].
					"-".$arrBan['begindatumdag']." ".$arrBan['begindatumuur'].
					":".$arrBan['begindatumminuten'].":00"	);
		}
		if(isset($arrBan['einddatumjaar']) && isset($arrBan['einddatummaand']) && 
				isset($arrBan['einddatumdag']) && isset($arrBan['einddatumuur'])
				&& isset($arrBan['einddatumminuten'])) {
				$this->setEindDatum($arrBan['einddatumjaar']."-".$arrBan['einddatummaand'].
					"-".$arrBan['einddatumdag']." ".$arrBan['einddatumuur'].
					":".$arrBan['einddatumminuten'].":00"	);
		}
	}
	
	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;
	}
	public function getBeginDatum($booFixed = false) {
		if($booFixed == false) return $this->dtBeginDatum;
		else return fixData($this->dtBeginDatum);
	}
	public function getBeginDatumNet() {
		return getHTMLDatumNet($this->dtBeginDatum);
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
	public function getToevoegDatumNet() {
		return getHTMLDatumNet($this->dtToevoeging);
	}
	public function getDatum($booFixed = false) {
		if($booFixed == false) return $this->dtDatum;
		else return fixData($this->dtDatum);
	}
	public function getReden($booFixed = false) {
		if($booFixed == false) return $this->strReden;
		else return fixData($this->strReden);
	}
	public function getRedenNet( ) {
		if($this->getReden() == "false_login") return "Persoon heeft 5 keer verkeerd ingelogd";
		elseif($this->getReden() == "perm_ban") return "Persoon is voor altijd verbannen";
		elseif($this->getReden() == "short_ban") return "Persoon is tijdelijk verbannen";	
	}
	public function getStatus($booFixed = false) {
	    if($booFixed == false) return $this->strStatus;
	    else return fixData($this->strStatus);
	}
	public function getStatusNet( ) {
		if($this->getStatus() == "-1") return "Ban is niet actief";
		elseif($this->getStatus() == "0") return "Ban is tijdelijk niet actief";
		elseif($this->getStatus() == "1") return "Ban is actief";		
	}
	public function getIPadres($booFixed = false) {
		if($booFixed == false) return $this->strIPadres;	
		else return fixData($this->strIPadres);
	}
	public function getGebruikersID() {
		return $this->intGebruikersID;
	}
	public function getGebruikersIDNet() {
		if($this->intGebruikersID == ""  || $this->intGebruikersID == "0") return "<i>Geen</i>";
		else return $this->intGebruikersID;
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
	public function setReden($strNewReden, $booCheck = false) {
		if($booCheck == true) $this->strReden = checkData($strNewReden);
		else $this->strReden = $strNewReden;
	}
	public function setStatus($strNewStatus, $booCheck = false) {
	    if($booCheck == true) $this->strStatus= checkData($strNewStatus);
	    else $this->strStatus= $strNewStatus;
	}
	public function setIPadres($strNewIPadres, $booCheck = false) {
		if($booCheck == true) $this->strIPadres = checkData($strNewIPadres);
		else $this->strIPadres = $strNewIPadres;
	}
	public function setGebruikersID($intNewGebruikersID, $booCheck = false) {
		if($booCheck == true) $this->intGebruikersID = checkData($intNewGebruikersID, 'integer');
		else $this->intGebruikersID = $intNewGebruikersID;
	}
}

?>
