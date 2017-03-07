<?php
/* Bestandsnaam: bedrijffuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 22-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt bedrijven staan
 *
 */

// Functie om de bedrijf-gegevens toe te voegen
function addBedrijf($objBedrijf) {
	global $objDBConnectie;
	$sql = "INSERT INTO bedrijf (bedrijfsnaam, kvk, straat, huisnr, postcode, woonplaats, telefoon, fax, emailadres, website) VALUES ".
		   "  ('".$objBedrijf->getBedrijfsnaam(true)."', '".$objBedrijf->getKVK()."', '".
		   $objBedrijf->getStraatnaam(true)."', '".$objBedrijf->getHuisnummer(true)."', '".
		   $objBedrijf->getPostcode(true)."', '".$objBedrijf->getWoonplaats(true)."', '".$objBedrijf->getTelefoon()."', '".
		   $objBedrijf->getFax()."', '".$objBedrijf->getEmailadres()."', '".$objPersoon->getWebsite().") ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de bedrijf-gegevens te bewerken
function editBedrijf($objBedrijf) {
	global $objDBConnectie;
	$sql = "UPDATE bedrijf SET bedrijfsnaam = '".$objBedrijf->getBedrijfsNaam(true)."', ".
	 	   " kvk = '".$objBedrijf->getKVK()."', ".
		   " straatnaam = '".$objBedrijf->getStraatnaam(true)."', ".
		   " huisnr = '".$objBedrijf->getHuisnummer(true)."', ".
		   " postcode = '".$objBedrijf->getPostcode(true)."', ".
		   " woonplaats = '".$objBedrijf->getWoonplaats(true)."', ".
		   " telefoon = '".$objBedrijf->getTelefoon()."', ".
		   " fax = '".$objBedrijf->getFax()."', ".
		   " emailadres = '".$objBedrijf->getEmailadres()."', ".
	 	   " website = '".$objBedrijf->getWebsite()."' WHERE ".
	 	   " id = '".$objBedrijf->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de bedrijf-gegevens te verwijderen
function delBedrijf($intID) {
	global $objDBConnectie;
	if(getBedrijf($intID)) {
		$sql = "DELETE FROM bedrijf WHERE id = '".$intID."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}

// Functie om de bedrijf-gegevens op te vragen
function getBedrijf($objBedrijf) {
	global $objDBConnectie;
	$sql = "SELECT * FROM bedrijf WHERE id = id";
	if($objBedrijf->getID() != "") 
		$sql .= " AND id = '".$objBedrijf->getID()."'";
	if($objBedrijf->getBedrijfsaam() != "")
		$sql .= " AND bedrijfsnaam = '".$objBedrijf->getBedrijfsnaam()."'";
	if($objBedrijf->getAdres() != "")
		$sql .= " AND adres >= '".$objBedrijf->getAdres()."'";
	if($objBedrijf->getPostcode() != "")
		$sql .= " AND postcode = '".$objBedrijf->getPostcode()."'";
	if($objBedrijf->getWoonplaats() != "")
	        $sql .= " AND woonplaats = '".$objBedrijf->getWoonplaats()."'";
	if($objBedrijf->getTelefoon() != "")
		$sql .= " AND telefoon = '".$objBedrijf->getTelefoon()."'";
	if($objBedrijf->getFax() != "")
		$sql .= " AND fax = '".$objBedrijf->getFax()."'";
	if($objBedrijf->getEmail() != "")
	        $sql .= " AND emailadres = '".$objBedrijf->getEmail()."'";
	if($objBedrijf->getWebsite() != "")
		$sql .= " AND website = '".$objBedrijf->getWebsite()."'";
	

	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else {
		$objBedrijf = new Bedrijf();
		$objBedrijf->setValues($arrMysqlResult[0]);
		return $objBedrijf;		
	}
}

function showBedrijf($intID ) {
	$objBedrijf = getBedrijf($intID);
	
	echo openDiv('bedrijfInfo', 'infoVak').
	     openDiv('', 'naamVeldB')."  Bedrijfsnaam: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getBedrijfsnaam(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Adres: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getAdres(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Postcode: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getPostcode(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Woonplaats: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getWoonplaats(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Telefoonnummer: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getTelefoon(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Faxnummer: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getFax(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Emailadres: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getEmail(true).sluitDiv().
	     openDiv('', 'naamVeldB')."  Website: ".sluitDiv().
	     openDiv('', 'naamVeld').$objBedrijf->getWebsite(true).sluitDiv().
	     sluitDiv();
}

?>
