<?php
/* Bestandsnaam: banfuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 30-10-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt bans staan
 *
 */

// Functie om de Ban-gegevens toe te voegen
function addBan($objBan) {

	global $objDBConnectie;
	$sql = "INSERT INTO ban (gebruikersid, ipadres, reden, status, begindatum, einddatum, toevoegdatum) VALUES ".
		   "  ('".$objBan->getGebruikersID()."', '".$objBan->getIPAdres()."', '".
		   $objBan->getReden()."', '".$objBan->getStatus()."', '".
		   $objBan->getBeginDatum()."', '".$objBan->getEindDatum()."', '".
		   $objBan->getToevoegDatum()."' ) ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;

}
// Functie om de Ban-gegevens te bewerken
function editBan($objBan) {

	global $objDBConnectie;
	$sql = "UPDATE ban SET gebruikersid = '".$objBan->getGebruikersID(true)."', ".
	 	   " ipadres = '".$objBan->getIPadres(true)."', ".
	 	   " reden = '".$objBan->getReden()."', ".
	 	   " status = '".$objBan->getStatus()."', ".
	 	   " begindatum = '".$objBan->getBeginDatum()."', ".
	 	   " einddatum = '".$objBan->getEindDatum()."' WHERE ".
	 	   " id = '".$objBan->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
	 
}
// Functie om de Ban-gegevens te verwijderen
function delBan($objBan) {
	global $objDBConnectie;
	$objTmpBan = getBan($objBan);
	if($objTmpBan != false) {
		$sql = "DELETE FROM ban WHERE id = '".$objTmpBan->getID()."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}									
// Functie om de Ban-gegevens op te vragen
function getBan($objBan, $booID = true, $booGebruikersID = false, $booIP = false, $booDatum = false) {
	global $objDBConnectie;
	if($objBan == false) return false;
	else {
		$sql = "SELECT * FROM ban WHERE id = id";
		if($booID == true && $objBan->getID() != "") 
			$sql .= " AND id = '".$objBan->getID()."'";
		if($booGebruikersID == true && $objBan->getGebruikersID() != "")
			$sql .= " AND gebruikersid = '".$objBan->getGebruikersID()."'";
		if($booIP == true && $objBan->getIPAdres() != "")
			$sql .= " AND ipadres = '".$objBan->getIPAdres()."'";
		if($objBan->getReden() != "")
			$sql .= " AND reden = '".$objBan->getReden()."'";
		if($objBan->getStatus() != "")
			$sql .= " AND status = '".$objBan->getStatus()."'";
		if($booDatum == true && $objBan->getDatum() != "")
			$sql .= " AND begindatum < '".$objBan->getDatum()."'";
		if($booDatum == true && $objBan->getDatum() != "")
			$sql .= " AND einddatum > '".$objBan->getDatum()."'";

		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Ban');
	}

}

// Functie om de ban-gegevens op te vragen
function getBans($intVan = 0, $intLimiet = 30, $objBan = false) {
	global $objDBConnectie;
	$sql = "SELECT * FROM ban WHERE id = id";
	if($objBan != false && $objBan->getID() != "")
		$sql .= " AND id = '".$objBan->getID()."'";
	if($objBan != false && $objBan->getGebruikersID() != "")
		$sql .= " AND gebruikersid = '".$objBan->getGebruikersID()."'";
	if($objBan != false && $objBan->getIPAdres() != "")
		$sql .= " AND ipadres <= '".$objBan->getIPAdres()."'";
	if($objBan != false && $objBan->getReden() != "")
		$sql .= " AND reden = '".$objBan->getReden()."'";
	if($objBan != false && $objBan->getStatus() != "")
		$sql .= " AND status = '".$objBan->getStatus()."'";
	if($objBan != false && $objBan->getBeginDatum() != "")
		$sql .= " AND begindatum = '".$objBan->getBeginDatum()."'";
	if($objBan != false && $objBan->getEindDatum() != "")
		$sql .= " AND einddatum = '".$objBan->getEindDatum()."'";
	$sql .= " ORDER BY id ASC";
	if($intLimiet != 0)
		$sql .= " LIMIT ".$intVan.", ".$intLimiet." ";
	
	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Ban');
}

function checkBan( $objBan, $booNew = false) {
	if($objBan == false) $arrError[-1] = true;
	else {
		//ID-nummer
		if($objBan->getID() == "" && $booNew == false) $arrError[0] = true;
		elseif(strlen($objBan->getID()) > 255 && $booNew == false) $arrError[1] = true; // Mag niet groter zijn dan 255 tekens
		elseif(eregi("[^0-9]{1,}", $objBan->getID())  && $booNew == false) $arrError[2] = true; // Alleen 0-9 zijn toegestaan
		// Gebruikers-ID
		if($objBan->getGebruikersID() != "0" && $objBan->getGebruikersID() == "") $arrError[10] = true;
		elseif(strlen($objBan->getGebruikersID()) > 255 ) $arrError[11] = true; // Mag niet groter zijn dan 255 tekens
		elseif(eregi("[^0-9]{1,}", $objBan->getGebruikersID())) $arrError[12] = true; // Alleen chars 0-9 zijn toegestaan
		// IPAdres
		if($objBan->getIPadres() == "") $arrError[20] = true;
		//elseif(strlen($objBan->getIPadres()) > 16) $arrError[21] = true; // Mag niet groter zijn dan 16 tekens
		elseif(checkIP($objBan->getIPadres()) == false) $arrError[22] = true;
		// Status
		if($objBan->getStatus() == "") $arrError[30] = true;
		elseif(strlen($objBan->getStatus()) > 3) $arrError[31] = true; // Mag niet groter zijn dan 100 tekens
		elseif(eregi("[^a-zA-Z0-9]{1,}", $objBan->getStatus())) $arrError[32] = true; // Alleen de chars a-z, A-Z en 0-9 zijn toegestaan
		// Reden
		if($objBan->getReden() == "") $arrError[40] = true;
		elseif(strlen($objBan->getReden()) > 255) $arrError[41] = true; // Mag niet groter zijn dan 18 tekens
		elseif(eregi("[^a-zA-Z0-9_-]{1,}", $objBan->getReden())) $arrError[42] = true; // Alleen de chars a-z, A-Z en 0-9 zijn toegestaan
		// BeginDatum
		if($objBan->getBeginDatum() == "") $arrError[50] = true;
		elseif(strlen($objBan->getBeginDatum()) > 19) $arrError[51] = true; // Mag niet groter zijn dan 18 tekens
		elseif(eregi("[^0-9:-[:space:]]{1,}", $objBan->getBeginDatum())) $arrError[52] = true; // Alleen data zijn toegestaan
		// EindDatum
		if($objBan->getEindDatum() == "") $arrError[60] = true;
		elseif(strlen($objBan->getEindDatum()) > 19) $arrError[61] = true; // Mag niet groter zijn dan 18 tekens
		elseif(eregi("[^0-9:-[:space:]]{1,}", $objBan->getEindDatum())) $arrError[62] = true; // Alleen data zijn toegestaan
		}
	if(isset($arrError)) return $arrError;
	else return false;
}

// Functie om ban te checken, of de gegevens al bestaan in de db. False als alles OK is
function checkBanDB($objBan, $booNew = false) {
	global $objDBConnectie;
	if($objBan == false) $arrError[-1] = true;
	else {
		$sql = "SELECT * FROM ban WHERE id = id ".
			" AND gebruikersid = '".$objBan->getGebruikersID()."'";
		if($booNew != true)
			$sql .= " AND id != '".$objBan->getID()."'   ";
		
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else {
			$intArraySize = count($arrMysqlResult);
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpBan = new Ban();
				$objTmpBan->setValues($arrMysqlResult[$i]);
				if($objTmpBan->getGebruikersID() == $objBan->getGebruikersID())
					$arrError[40] = true; // Er is al een ban met dezelfde gebruikersid
			}
		}
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om banpagina te laten zien
function showBanPagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
        showHeader();
	
	switch ($intID) {
	case 1: // Nieuwe ban toevoegen
		showBanForm(false, $strMelding, $extraObj2);
		break;
	case 2: // Ban bekijken
		showBan($extraObj, $strMelding, $extraObj2);
		break;
	case 3: // Ban bewerken, of als er iets mis is gegaan bij toevoegen
		showBanForm($extraObj, $strMelding, $extraObj2);
		break;
	case 4: // Ban verwijderen
		showDelBanForm($extraObj, $strMelding, $extraObj2);
		break;
	default: // Standaard, loginformulier
		showBansOverzicht(0, 30, $extraObj, $strMelding);
	}
	showFooter();
}

// Functie om de informatie van een ban op het scherm te tonen
function showBan( $objBan, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}
	$strTabelStijl = "info";
	if($objBan != false) {
		if($objBan->getGebruikersID() != "0") {
			$objTmpGebruiker = new Gebruiker();
			$objTmpGebruiker->setID( $objBan->getGebruikersID() );
			$objGebruiker = getGebruiker($objTmpGebruiker);
			if($objGebruiker != false)
				$strGebrNaam = $objGebruiker->getVolledigeNaam()." (".$objGebruiker->getPersoonID().")";
			else $strGebrNaam = "<i>Ex-gebruiker</i>";
		}
		else $strGebrNaam = "<i>Geen</i>";
		
		echo openContentVak( "Ban informatie van '".$objBan->getIPAdres()."'", 'ban', 'Ban', $objBan->getID() );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruiker: ".sluitCel().
		     openCel($strTabelStijl, 2).$strGebrNaam.sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."IP Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getIPAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getStatusNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Reden: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getRedenNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Begindatum: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getBeginDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Einddatum: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getEindDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getToevoegDatumNet().sluitCel().sluitRij().
		     sluitTabel().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('ban', "bans").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
  
	}
	else showErrorPagina(4);
}
// Functie om het formulier op te vragen om een ban te bewerken
function showBanForm( $objBan = false, $strMelding = '', $arrErrors = false  ) {
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De ban is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de ban is onjuist</li>\n";
				if(isset($arrErrors[10]) || isset($arrErrors[11]) || isset($arrErrors[12])) $strMeldingHTML .= "<li>Het gebruikers-ID is onjuist of niet ingevuld.</li>\n";
				if(isset($arrErrors[20]) || isset($arrErrors[21]) || isset($arrErrors[22])) $strMeldingHTML .= "<li>Het IP adres is onjuist of niet ingevuld</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>De status is niet ingevuld</li>\n";
				if(isset($arrErrors[40]) || isset($arrErrors[41]) || isset($arrErrors[42])) $strMeldingHTML .= "<li>De reden is niet ingevuld</li>\n";
				if(isset($arrErrors[50]) || isset($arrErrors[51]) || isset($arrErrors[52])) $strMeldingHTML .= "<li>Er is geen begindatum ingevuld</li>\n";
				if(isset($arrErrors[50]) || isset($arrErrors[51]) || isset($arrErrors[52])) $strMeldingHTML .= "<li>Er is geen einddatum ingevuld</li>\n";
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}
	if($objBan != false && $objBan->getID() != "") {
		$objBanOrg = getBan($objBan);
		$strFormKnopNaam = "editBanKnop";
		$strFormKnopWaarde = "Bewerk ban";
		echo openContentVak( "Ban op IP '".$objBan->getIPAdres()."' bewerken", 'ban', 'Ban', $objBan->getID(), 'edit' );
	}
	else {
		if($objBan == false)
			$objBan = new Ban();
		$strFormKnopNaam = "addBanKnop";
		$strFormKnopWaarde = "Voeg ban toe";
		echo openContentVak( "Nieuwe ban toevoegen");
	}
	;
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo openForm('editBan', 'ban').
	     showInputTekst('id',$objBan->getID(), '', '', 'hidden').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikers-ID: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     //showInputTekst('gebruikersid', $objBan->getGebruikersID(true)).sluitCel().sluitRij().
	     showGebruikerLijst('gebruikersid', $objBan->getGebruikersID()).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."IP adres: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputTekst('ipadres', $objBan->getIPAdres(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     //showInputTekst('status', $objBan->getStatus(true)).sluitCel().sluitRij().
	     showBanStatusLijst('status', $objBan->getStatus() ).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Reden:".sluitCel().
	     openCel($strTabelStijl, 2).
	     //showInputTekst('reden', $objBan->getReden(true)).sluitCel().sluitRij().
	     showBanRedenLijst('reden', $objBan->getReden(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Begindatum:".sluitCel().
	     openCel($strTabelStijl, 2).
	     //showInputTekst('begindatum', $objBan->getBeginDatum(true)).sluitCel().sluitRij().
	     showDatumLijst('begindatum', $objBan->getBeginDatum(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Einddatum:".sluitCel().
	     openCel($strTabelStijl, 2).
	     //showInputTekst('einddatum', $objBan->getEindDatum(true)).sluitCel().sluitRij().
	     showDatumLijst('einddatum', $objBan->getEindDatum(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop($strFormKnopNaam, $strFormKnopWaarde,'buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().
	     openTabel($strTabelStijl, false).
	     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('ban', "bans").sluitCel().
	     sluitRij().sluitTabel().
	     sluitContentVak();
}

// Functie om het formulier op te vragen om een ban te verwijderen
function showDelBanForm( $objBan, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;			
		$strMeldingHTML .= "</div>\n";
	}
	
	$strTabelStijl = "info";
	if($objBan != false) {
		if($objBan->getGebruikersID() != "0") {
			$objTmpGebruiker = new Gebruiker();
			$objTmpGebruiker->setID( $objBan->getGebruikersID() );
			$objGebruiker = getGebruiker($objTmpGebruiker);
			if($objGebruiker != false)
				$strGebrNaam = $objGebruiker->getVolledigeNaam()." (".$objGebruiker->getPersoonID().")";
			else $strGebrNaam = "<i>Ex-gebruiker</i>";
		}
		else $strGebrNaam = "<i>Geen</i>";
		
		echo openContentVak( "Ban op IP '".$objBan->getIPAdres()."' verwijderen", 'ban', 'Ban', $objBan->getID(), 'del' );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openForm('delBan', 'ban').
		     showInputTekst('id',$objBan->getID(), '', '', 'hidden').
		     openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruiker: ".sluitCel().
		     openCel($strTabelStijl, 2).$strGebrNaam.sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."IP Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getIPAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getStatusNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Reden: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getRedenNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Begindatum: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getBeginDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Einddatum: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getEindDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objBan->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputKnop('delBanKnop', 'Verwijder ban','buttonStijl1').
		     sluitCel().sluitRij().sluitTabel().
		     sluitForm().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('ban', "bans").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
	}
	else showErrorPagina(4);
}

// Functie om het overzicht van bans te laten zien
function showBansOverzicht( $intVan = 0, $intLimiet = 0, $objModule = false, $strMelding = '' ) {
	if($intVan < 0) $intVan = 0;
	$arrBans = getBans($intVan, $intLimiet, $objBan);
	$intArraySize = count($arrBans);

	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}	
	$strTabelStijl = "overzicht";
	echo openContentVak( "Bansoverzicht").
		 "Hieronder staat het overzicht van de bans in het systeem. ";
		 if($intArraySize != 0 && $arrBans != false)
		 	echo "Bans ".($intVan+1)." tot en met ".($intVan + $intArraySize)." worden getoond.\n";
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;		 
	
	if($arrBans != false && $intArraySize != 0) {
		echo openTabel($strTabelStijl, false).
			 openRij($strTabelStijl).openDiv('',$strTabelStijl."TabelVeldTitel1")."IP adres:".sluitDiv().
			 openDiv('',$strTabelStijl."TabelVeldTitel2")."Acties:".sluitDiv().sluitRij();
		$intVeldID = 1;
		for($i = 0; $i < $intArraySize; $i++) {
			$objBan = $arrBans[$i];
			if($objBan != false) {
				echo openRij($strTabelStijl).openCel($strTabelStijl, $intVeldID).
					 getActieLink( $objBan->getID(), 'ban', 'view', false, $objBan->getIPAdres(), 'Bekijk ban').
					 " (tot ".$objBan->getEindDatumNet().")".
					 sluitCel().
					 getActieMenu($objBan->getID(),  'ban', true, true, true, false, 'overzicht',$intVeldID).
					 sluitRij();		
				if($intVeldID == 1) $intVeldID = 2;
				elseif($intVeldID == 2) $intVeldID = 1;
			}
		}
		echo sluitTabel();
	}
	else {
		echo "<br/><br/>Er zijn nog geen bans in de database.";
	}
	
	echo openTabel('info', false).
	     openRij('info').openCel('info',3).
	     getLink('index.php?&action=add', "Voeg een ban toe", 'ban').
	     sluitCel().sluitRij().sluitTabel().
		 sluitContentVak();
}



?>
