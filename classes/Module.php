<?php
/* Bestandsnaam: Module.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * De klasse Module
 *
 */

class Module {
	private $intID;
	private $strModuleNaam;
	private $strActieBestand;
	private $strFunctiesBestand;
	private $strMenuNaam;
	private $intMinUserLevel;
	private $dtToevoeging;
	private $strActief;
	
	// Constructor, om een instantie van het object aan te maken
	public function __construct() {
		$this->intID = "";
		$this->strModuleNaam = "";
		$this->strActieBestand = "";
		$this->strFunctiesBestand = "";
		$this->strMenuNaam = "";
		$this->intMinUserLevel = -1;
		$this->dtToevoeging = "";
		$this->strActief = "";	
	}
	// De setValues-functie, om de waarden van array in het object te stoppen
	public function setValues( $arrModule, $booCheck = false ) {
		if(isset($arrModule['id']))
			$this->setID( $arrModule['id'], $booCheck );
		if(isset($arrModule['modulenaam']))
			$this->setModuleNaam(  $arrModule['modulenaam'], $booCheck );
		if(isset($arrModule['functiesbestand']))
			$this->setFunctiesBestand( $arrModule['functiesbestand'], $booCheck );
		elseif(isset($arrModule['functiebestand']))
			$this->setFunctiesBestand( $arrModule['functiebestand'], $booCheck );
		if(isset($arrModule['actiebestand']))
			$this->setActieBestand( $arrModule['actiebestand'], $booCheck );
		if(isset($arrModule['menunaam']))
			$this->setMenuNaam( $arrModule['menunaam'], $booCheck );
		if(isset($arrModule['userlevel']))
			$this->setMinUserLevel( $arrModule['userlevel'], $booCheck );
		if(isset($arrModule['toevoegdatum']))
			$this->setToevoegDatum( $arrModule['toevoegdatum'], $booCheck );
		if(isset($arrModule['actief']))
			$this->setActief( $arrModule['actief'], $booCheck );
	}
	// Functies om de waarden van de variabelen op te vragen
	public function getID() {
		return $this->intID;
	}
	public function getModuleID() {
		return $this->getID();	
	}
	public function getModuleNaam( $booFixed = false) {
		if($booFixed == false) return $this->strModuleNaam;
		else return fixData($this->strModuleNaam);	
	}
	public function getActieBestand( $booFixed = false) {
		if($booFixed == false) return $this->strActieBestand;
		else return fixData($this->strActieBestand);	
	}
	public function getFunctiesBestand( $booFixed = false) {
		if($booFixed == false) return $this->strFunctiesBestand;
		else return fixData($this->strFunctiesBestand);	
	}
	public function getMenuNaam( $booFixed = false) {
		if($booFixed == false) return $this->strMenuNaam;
		else return fixData($this->strMenuNaam);	
	}
	// Om fouten met spellingsfouten te voorkomen, ook eentje zonder 's'
	public function getFunctieBestand($booFixed = false) {
		return $this->getFunctiesBestand( $booFixed );	
	}
	public function getToevoegDatum() {
		if($booFixed == false) return $this->dtToevoeging;
		else return fixData($this->dtToevoeging);	
	}
	public function getToevoegDatumNet($booSeconden = false, $booMinuten = true, $booUren = true, $booDagen = true, $booMaanden = true, $booJaren = true) {
		$arrToevoegDatum = explode(" ",$this->dtToevoeging);
		$arrTijd = explode(":",$arrToevoegDatum[1]);
		$arrDatum = explode("-",$arrToevoegDatum[0]);
		$strHTML =  $arrDatum[2]."-".$arrDatum[1]."-".$arrDatum[0]." om ".$arrTijd[0].":".$arrTijd[1].":".$arrTijd[2];	
		return $strHTML;
	}
	public function getMinUserLevel() {
		return $this->intMinUserLevel;
	}
	public function getUserLevel() {
		return $this->getMinUserLevel();	
	}
	public function getActief($booFixed = false) {
		if($booFixed == false) return $this->strActief;
		else return ucfirst($this->strActief);	
	}
	// Functies om de variabelen een waarde te geven
	public function setID( $intID, $booCheck = false ) {
		if($booCheck == true) $this->intID = checkData( $intID, 'integer');
		else $this->intID = $intID;
	}
	public function setModuleID( $intID, $booCheck = false  ) {
		$this->setID( $intID, $booCheck );
	}
	public function setModuleNaam( $strNewModuleNaam, $booCheck = false  ) {
		if($booCheck == true) $this->strModuleNaam = checkData( $strNewModuleNaam );
		else $this->strModuleNaam = $strNewModuleNaam;
	}
	public function setActieBestand( $strNewActieBestand, $booCheck = false  ) {
		if($booCheck == true) $this->strActieBestand = checkData( $strNewActieBestand );
		else $this->strActieBestand = $strNewActieBestand;
	}
	public function setFunctiesBestand( $strNewFunctiesBestand, $booCheck = false  ) {
		if($booCheck == true) $this->strFunctiesBestand = checkData( $strNewFunctiesBestand );
		else $this->strFunctiesBestand = $strNewFunctiesBestand;
	}
	public function setToevoegDatum( $dtNewToevoeging, $booCheck = false  ) {
		if($booCheck == true) $this->dtToevoeging = checkData( $dtNewToevoeging  );
		else $this->dtToevoeging = $dtNewToevoeging;
	}
	public function setMenuNaam( $strNewMenuNaam, $booCheck = false  ) {
		if($booCheck == true) $this->strMenuNaam = checkData( $strNewMenuNaam  );
		else $this->strMenuNaam = $strNewMenuNaam;
	}
	public function setMinUserLevel( $strNewUserLevel, $booCheck = false  ) {
		if($booCheck == true) $this->intMinUserLevel = checkData( $strNewUserLevel, 'integer' );
		else $this->intMinUserLevel = $strNewUserLevel;
	}
	public function setUserLevel( $strNewUserLevel, $booCheck = false  ) {
		$this->setMinUserLevel($strNewUserLevel, $booCheck);
	}
	public function setActief( $strNewActief, $booCheck = false  ) {
		if($booCheck == true) $this->strActief = checkData( $strNewActief );
		else $this->strActief = $strNewActief;
	}	
	
	
}
?>