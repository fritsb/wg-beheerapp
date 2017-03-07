<?php
/* Bestandsnaam: persoonfuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 22-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt personen staan
 *
 */

// Functie om de persoon-gegevens toe te voegen
function addPersoon($objPersoon) {
	global $objDBConnectie;
	$sql = "INSERT INTO persoon (id, voornaam, tussenvoegsel, achternaam, straat, huisnr, postcode, ".
	 	   " woonplaats, telthuis, telwerk, telmobiel, email, toevoegdatum, bedrijfsid) VALUES ".
		   "  ('".$objPersoon->getID()."','".$objPersoon->getVoorNaam(true)."', '".
		   $objPersoon->getTussenvoegsel()."', '".$objPersoon->getAchternaam(true)."', '".
		   $objPersoon->getStraat(true)."', '".$objPersoon->getHuisnummer(true)."', '".
		   $objPersoon->getPostcode(true)."', '".$objPersoon->getWoonplaats(true)."', '".
		   $objPersoon->getTelThuis()."', '".$objPersoon->getTelWerk()."', '".
		   $objPersoon->getTelMobiel()."', '".$objPersoon->getEmail()."', '".
		   $objPersoon->getToevoegDatum()."', '".$objPersoon->getBedrijfsID()."') ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de persoon-gegevens te bewerken
function editPersoon($objPersoon) {
	global $objDBConnectie;
	$sql = "UPDATE persoon SET voornaam = '".$objPersoon->getVoornaam(true)."', ".
	 	   " tussenvoegsel = '".$objPersoon->getTussenvoegsel()."', ".
	 	   " achternaam = '".$objPersoon->getAchternaam(true)."', ".
		   " straat = '".$objPersoon->getStraat(true)."', ".
		   " huisnr = '".$objPersoon->getHuisnummer(true)."', ".
		   " postcode = '".$objPersoon->getPostcode(true)."', ".
		   " woonplaats = '".$objPersoon->getWoonplaats(true)."', ".
		   " telthuis = '".$objPersoon->getTelThuis(true)."', ".
		   " telwerk = '".$objPersoon->getTelWerk(true)."', ".
		   " telmobiel = '".$objPersoon->getTelMobiel(true)."', ".
	 	   " email = '".$objPersoon->getEmail(true)."' WHERE ".
	 	   " id = '".$objPersoon->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de persoon-gegevens te verwijderen
function delPersoon($objPersoon) {
	global $objDBConnectie;
	$objTmpPersoon = getPersoon($objPersoon);
	if($objTmpPersoon != false) {
		$sql = "DELETE FROM persoon WHERE id = '".$objPersoon->getID()."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}

// Functie om de persoon-gegevens op te vragen
function getPersoon($objPersoon, $booID = true, $booVNaam = false, $booANaam = false, $booEmail = false,
		$booStraat = false, $booPostCode = false, $booPlaats = false, $booTelThuis = false, 
		$booTelMobiel = false, $booTelWerk = false ) {
	if($objPersoon == false) return false;
	else {
		global $objDBConnectie;
		$sql = "SELECT * FROM persoon WHERE id = id";
		if($booID == true && $objPersoon->getID() != "") 
			$sql .= " AND id = '".$objPersoon->getID()."'";
		if($booVNaam == true && $objPersoon->getVoornaam() != "")
			$sql .= " AND voornaam = '".$objPersoon->getVoornaam()."'";
		if($booANaam == true && $objPersoon->getAchternaam() != "")
			$sql .= " AND achternaam = '".$objPersoon->getAchternaam()."'";
		if($booStraat == true && $objPersoon->getStraat() != "")
			$sql .= " AND straat = '".$objPersoon->getStraat()."'";
		if($booPostCode == true && $objPersoon->getPostcode() != "")
			$sql .= " AND postcode = '".$objPersoon->getPostcode()."'";
		if($booPlaats == true && $objPersoon->getWoonplaats() != "")
		    $sql .= " AND woonplaats = '".$objPersoon->getWoonplaats()."'";
		if($booTelThuis == true && $objPersoon->getTelThuis() != "")
			$sql .= " AND telthuis = '".$objPersoon->getTelThuis()."'";
		if($booTelWerk == true && $objPersoon->getTelWerk() != "")
			$sql .= " AND telwerk = '".$objPersoon->getTelWerk()."'";
		if($booTelMobiel == true && $objPersoon->getTelMobiel() != "")
		    $sql .= " AND telmobiel = '".$objPersoon->getTelMobiel()."'";
		if($booEmail == true && $objPersoon->getEmail() != "")
			$sql .= " AND email = '".$objPersoon->getEmail()."'";
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Persoon');
	}
}


// Functie om persoon te checken, false  als alles OK is. Een array met errorcodes als alles niet ok is
function checkPersoon( $objPersoon, $booNew = false, $booWachtWoord = false) {
	if($objPersoon == false) $arrError[-1] = true;
	else {
		global $_arrConfig;
		// ID-nummer
		if($objPersoon->getID() == "" && $booNew == false) $arrError[100] = true;
		elseif(strlen($objPersoon->getID()) > 255 && $booNew == false) $arrError[101] = true; // Mag niet groter zijn dan 255 tekens		
		elseif(eregi("[^0-9]{1,}", $objPersoon->getID())  && $booNew == false) $arrError[102] = true; // Alleen 0-9 zijn toegestaan		
		// E-mail
		if($objPersoon->getEmail() == "") $arrError[110] = true;
		elseif(strlen($objPersoon->getEmail()) < 5) $arrError[111] = true; // Kan niet kleiner zijn dan 5 tekens
		elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{1,})*\.([a-z]{2,}){1}$", $objPersoon->getEmail())) $arrError[112] = true; 
		// Achternaam
		if($objPersoon->getAchternaam() == "") $arrError[120] = true;
		elseif(eregi("[^a-zA-Z0-9-]{1,}", $objPersoon->getAchternaam())) $arrError[121] = true; // Alleen chars a-z, A-Z, 0-9 zijn toegestaan
		// Woonplaats
		if($objPersoon->getWoonplaats() == "") $arrError[130] = true;
		elseif(eregi("[^a-zA-Z0-9-]{1,}", $objPersoon->getWoonplaats())) $arrError[131] = true; // Alleen chars a-z, A-Z, 0-9 zijn toegestaan
		
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om persoon te checken, of de gegevens al bestaan in de db oid. False als alles OK is
function checkPersoonDB($objPersoon, $booNew = false) {
	global $objDBConnectie;
	if($objPersoon == false) $arrError[-1] = true;
	else {
		$sql = "SELECT * FROM persoon WHERE id = id ".
			   " AND email = '".$objPersoon->getEmail()."'";
		if($booNew != true) 
			$sql .= " AND id != '".$objPersoon->getID()."'";
			
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else {
			$intArraySize = count($arrMysqlResult);
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpPersoon = SQLArrToObj($arrMysqlResult, 'Persoon', $i);
				if($objTmpPersoon != false && $objTmpPersoon->getEmail() == $objPersoon->getEmail())
					$arrError[140] = true; // Er is al een persoon met dezelfde e-mailadres
			}
		}
	}
	if(isset($arrError)) return $arrError;
	else return false;
}
?>
