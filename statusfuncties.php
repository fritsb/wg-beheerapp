<?php
/* Bestandsnaam: Statusfuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum:22 -09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt statusveranderingen staan
 *
 */

// Functie om de status-gegevens toe te voegen
function addStatus($objStatus) {
	global $objDBConnectie;
	$sql = "INSERT INTO status (persoonid, soort, begindatum, einddatum, extrainfo, status,".
		   " ipadres, uniekestring, toevoegdatum) VALUES ".
		   "  ('".$objStatus->getPersoonID()."','".$objStatus->getSoort()."', '".
		   $objStatus->getBeginDatum()."', '".$objStatus->getEindDatum()."', '".
		   $objStatus->getInfo()."', '".$objStatus->getStatus()."', '".
		   $objStatus->getIPAdres()."', '".$objStatus->getUniekeString()."', '".
		   $objStatus->getToevoegDatum()."' ) ";
		   
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}
// Functie om de status-gegevens te bewerken
function editStatus($objStatus, $booID = true, $booPersoonID = true, $booSoort = false) {
	global $objDBConnectie;
	$sql = "UPDATE status SET status = '".$objStatus->getStatus()."' ".
	       " WHERE id = id";
	if($booSoort == true)
		$sql .= " AND soort = '".$objStatus->getSoort()."' ";
	if($booID == true)
		$sql .= " AND id = '".$objStatus->getID()."' ";
	if($booPersoonID == true)
		$sql .= " AND persoonid = '".$objStatus->getPersoonID()."'";

	if($objDBConnectie->setData($sql)) return true;
	else return false;
	 
}
// Functie om de status-gegevens te verwijderen
function delStatus($objStatus, $booID = true, $booPersoonID = false) {
	global $objDBConnectie;
	$objTmpStatus = getStatus($objStatus);
	if($objTmpStatus != false) {
		$sql = "DELETE FROM status WHERE id = id ";
		if($booID == true && $objStatus->getID() != "")
			$sql .= "id = '".$objStatus->getID()."'";
		if($booPersoonID == true && $objStatus->getPersoonID() != "")
			$sql .= "persoonid = '".$objStatus->getPersoonID()."'";			
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}
// Functie om de status-gegevens op te vragen
function getStatus($objStatus, $booID = false, $booPersoonID = true, $booUniek = true, $booSoort = false,
					$booStatus = false, $booEindDatum = false, $booBeginDatum = false ) {
	if($objStatus == false) return false;
	else {
		global $objDBConnectie;
		$sql = "SELECT * FROM status WHERE id = id";
		if($objStatus->getID() != "" && $booID == true) 
			$sql .= " AND id = '".$objStatus->getID()."'";
		if($objStatus->getPersoonID() != "" && $booPersoonID == true) 
			$sql .= " AND persoonid = '".$objStatus->getPersoonID()."'";
		if($objStatus->getUniekeString() != "" && $booUniek == true)
			$sql .= " AND uniekestring = '".$objStatus->getUniekeString()."'";
		if($objStatus->getSoort() != "" && $booSoort == true)
			$sql .= " AND soort = '".$objStatus->getSoort()."'";
		if($objStatus->getStatus() != "" && $booStatus == true)
			$sql .= " AND status = '".$objStatus->getStatus()."'";
		if($objStatus->getEindDatum() != "" && $booEindDatum == true)
			$sql .= " AND einddatum > '".$objStatus->getDatum()."'";
		if($objStatus->getBeginDatum() != "" && $booBeginDatum == true)
			$sql .= " AND begindatum < '".$objStatus->getDatum()."'";
		$sql .= " ORDER BY id DESC LIMIT 1";

		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Status');
	}
}
// Functie om meerdere statussen op te vragen
function getStatussen($intVan = 0, $intLimiet = 30, $objStatus = false) {
	global $objDBConnectie;
	$sql = "SELECT * FROM status WHERE id = id";
	if($objStatus != false && $objStatus->getID() != "") 
		$sql .= " AND id = '".$objStatus->getID()."'";
	if($objStatus != false && $objStatus->getPersoonID() != "") 
		$sql .= " AND persoonid = '".$objStatus->getPersoonID()."'";
	if($objStatus != false && $objStatus->getUniekeString() != "")
		$sql .= " AND uniekestring = '".$objStatus->getUniekeString()."'";	
	if($objStatus != false && $objStatus->getBeginDatum() != "") 
		$sql .= " AND begindatum = '".$objStatus->getID()."'";
	if($objStatus != false && $objStatus->getEindDatum() != "")
		$sql .= " AND einddatum = '".$objStatus->getStatussNaam()."'";
	$sql .= " ORDER BY id ASC LIMIT ".$intVan.", ".$intLimiet." ";

	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Status');
}
// Functie om statuspagina te laten zien
function showStatusPagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
	showHeader();
	
	switch ($intID) {
	case 1: // Nieuwe gebruiker, bevestiging oke
	  showLoginForm($strMelding);
	  break;  
	case 2: // Nieuwe gebruiker, bevestiging niet oke
	  showStatusMelding($extraObj, $strMelding, $extraObj2);
	  break;
	case 3: // E-mailadres verificatie, oke
	  showStatusMelding($extraObj, $strMelding, $extraObj2);
	  break;
	case 4: // E-mailadres verificatie, niet oke
	  showStatusMelding($extraObj, $strMelding, $extraObj2);
	  break;
	default: // Standaard, error
	  showLoginForm($strMelding);
	}
 	showFooter();
}
// Functie om een melding te laten zien
function showStatusMelding($strTitel = '', $strMelding = '', $obj ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}
	echo openContentVak($strTitel);
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo sluitContentVak();
}
?>
