<?php
/* Bestandsnaam: Node.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 16-09-2005
 * Omschrijving: 
 * De klasse Node
 *
 */

class Node {
	private $intID;
	private $intNWID;
	private $strNaam;
	private $strIPadres;
	private $strMACadres;	
	private $intBedrijfsID;
	private $intPersoonsID;

	// Constructor
	public function __construct() {
		$this->intID = "";
		$this->intNWID = "";
		$this->strNaam = "";
		$this->strIPadres = "";
		$this->strMACadres = "";
		$this->intBedrijfsID = "";
		$this->intPersoonsID = "";
		$this->dtToevoeging = "";
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
	public function setValues( $arrNode, $booCheck = false ) {
		if(isset($arrNode['id']))
			$this->setID( $arrNode['id'], $booCheck);
		if(isset($arrNode['nwid']))
			$this->setNWID( $arrNode['nwid'], $booCheck);
		if(isset($arrNode['naam']))
			$this->setNaam( $arrNode['naam'], $booCheck);
		if(isset($arrNode['ipadres']))
			$this->setIPadres( $arrNode['ipadres'], $booCheck);
		if(isset($arrNode['macadres']))
			$this->setMACadres( $arrNode['macadres'], $booCheck);
		if(isset($arrNode['bedrijfsid']))
			$this->setBedrijfsID( $arrNode['bedrijfsid'], $booCheck);
		if(isset($arrNode['persoonsid']))
			$this->setPersoonsID( $arrNode['persoonsid'], $booCheck); 
	}

	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;	
	}
	public function getNWID() {
		return $this->intNWID;	
	}
	public function getNaam($strFixed = false) {
		if($strFixed == false) return $this->strNaam;
		else return fixData($this->strNaam);
	}
	public function getIPadres($strFixed = false) {
		if($strFixed == false) return $this->strIPadres;
		else return fixData($this->strIPadres);
	}	
	public function getMACadres($strFixed = false) {
		if($strFixed == false) return $this->strMACadres;
		else return fixData($this->strMACadres);
	}
	public function getBedrijfsID() {
		return $this->intBedrijfsID;
	}
	public function getPersoonsID() {
		return $this->intPersoonsID;	
	}
	public function getToevoegDatum($strFixed = false) {
		if($strFixed == false) return $this->dtToevoeging;	
		else return fixData( $this->dtToevoeging );
	}
	public function getToevoegDatumNet() {
		return getHTMLDatumNet($this->dtToevoeging);
	}
	// Functies om de variabelen een waarde te geven
	public function setID($intNewID, $booCheck = false) {
		if($this->booCheck == true) $this->intID = checkData( $intNewID, 'integer' );
		else $this->intID = $intNewID;
	}
	public function setNWID($intNewNWID, $booCheck = false) {
		if($this->booCheck == true) $this->intNWID = checkData( $intNewNWID, 'integer' );
		else $this->intNWID = $intNewID;
	}
	public function setNaam($strNewNaam, $booCheck = false) {
		if($this->booCheck == true) $this->strNaam = checkData( $strNewNaam);
		else $this->strNaam = $strNewNaam;
	}
	public function setIPadres($strNewIPadres, $booCheck = false) {
		if($this->booCheck == true) $this->strIPadres = checkData( $strNewIPadres );
		else $this->strIPadres = $strNewIPadres;
	}	
	public function setMACadres($strNewMACadres, $booCheck = false) {
		if($this->booCheck == true) $this->strMACadres = checkData( $strNewMACadres );
		else $this->strMACadres = $strNewMACadres;
	}
	public function setBedrijfsID($intNewBedrijfsID, $booCheck = false) {
		if($this->booCheck == true) $this->intBedrijfsID = checkData( $intNewBedrijfsID, 'integer' );
		else $this->intBedrijfsID = $intNewBedrijfsID;
	}
	public function setPersoonsID($intNewPersoonsID, $booCheck = false) {
		if($this->booCheck == true) $this->intPersoonsID = checkData(  $intNewPersoonsID, 'integer' );
		else $this->intPersoonsID = $intNewPersoonsID;
	}
	public function setToevoegDatum( $dtNewToevoeging, $booCheck = false) {
		if($this->booCheck == true) $this->dtToevoeging = checkData( $dtNewToevoeging );
		else $this->dtToevoeging = $dtNewToevoeging;
	}
}

?>