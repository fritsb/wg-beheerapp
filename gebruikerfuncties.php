<?php
/* Bestandsnaam: gebruikerfuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum:22 -09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt gebruikers staan
 *
 */

// Functie om de gebruiker-gegevens toe te voegen
function addGebruiker($objGebruiker) {
	global $objDBConnectie;
	$sql = "INSERT INTO gebruiker (persoonid, gebruikersnaam, wachtwoord, userlevel, status, ipadres,".
		   " aanmelddatum, notificaties) VALUES ".
		   "  ('".$objGebruiker->getID()."','".$objGebruiker->getGebruikersNaam()."', '".
		   md5($objGebruiker->getWachtwoordClear())."', '".$objGebruiker->getUserLevel()."', '".
		   //$objGebruiker->getWachtwoordClear()."', '".$objGebruiker->getUserLevel()."', '".
		   $objGebruiker->getStatus()."', '".$objGebruiker->getIPAdres()."', '".
		   $objGebruiker->getAanmeldDatum()."', '".$objGebruiker->getNotificaties()."' ) ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}
// Functie om de gebruiker-gegevens te bewerken
function editGebruiker($objGebruiker) {
	global $objDBConnectie;
	$sql = "UPDATE gebruiker SET gebruikersnaam = '".$objGebruiker->getGebruikersNaam()."' ";
	if($objGebruiker->getWachtwoordClear() != "")
	 	   $sql .= ", wachtwoord = '".md5($objGebruiker->getWachtwoordClear())."' ";
	if($objGebruiker->getUserLevel() != "")
		$sql .= ", userlevel = '".$objGebruiker->getUserLevel()."' ";
	if($objGebruiker->getStatus() != "")
	 	$sql .= ", status = '".$objGebruiker->getStatus()."' ";
	if($objGebruiker->getIPAdres() != "") 	   
		$sql .= ", ipadres = '".$objGebruiker->getIPAdres()."'  ";	 	   
	$sql .=  " WHERE persoonid = '".$objGebruiker->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
	 
}
// Functie om de gebruiker-gegevens te verwijderen
function delGebruiker($objGebruiker) {
	global $objDBConnectie;
	$objTmpGebruiker = getGebruiker($objGebruiker);
	if($objTmpGebruiker != false) {
		$sql = "DELETE FROM gebruiker WHERE persoonid = '".$objGebruiker->getID()."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}
// Functie om de gebruiker-gegevens op te vragen
function getGebruiker($objGebruiker, $booID = true, $booGebrNaam = false, $booWachtWoord = false, 
		$booUserLevel = false, $booIP = false) {
	if($objGebruiker == false) return false;
	else {
		global $objDBConnectie;
		$sql = "SELECT * FROM gebruiker AS g, persoon AS p WHERE g.persoonid = p.id";
		if($objGebruiker->getID() != "" && $booID == true) 
			$sql .= " AND g.persoonid = '".$objGebruiker->getID()."'";
		if($objGebruiker->getGebruikersNaam() != "" && $booGebrNaam == true)
			$sql .= " AND g.gebruikersnaam = '".$objGebruiker->getGebruikersNaam()."'";
		if($objGebruiker->getWachtwoordClear() != "" && $booWachtWoord == true)
			$sql .= " AND g.wachtwoord = '".md5($objGebruiker->getWachtwoordClear())."'"; 
		if($objGebruiker->getUserLevel() != "" && $booUserLevel == true)
			$sql .= " AND g.userlevel >= '".$objGebruiker->getUserLevel()."'";
		if($objGebruiker->getIPAdres() != "" && $booIP == true)
			$sql .= " AND g.ipadres = '".$objGebruiker->getIPAdres()."'";
		$sql .= " ORDER BY p.id ASC LIMIT 1";

		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Gebruiker');
	}
}
// Functie om meerdere gebruikers op te vragen
function getGebruikers($intVan = 0, $intLimiet = 30, $objGebruiker = false) {
	global $objDBConnectie;
	$sql = "SELECT * FROM gebruiker AS g, persoon AS p WHERE g.persoonid = p.id";
	if($objGebruiker != false && $objGebruiker->getID() != "") 
		$sql .= " AND g.persoonid = '".$objGebruiker->getID()."'";
	if($objGebruiker != false && $objGebruiker->getGebruikersNaam() != "")
		$sql .= " AND g.gebruikersnaam = '".$objGebruiker->getGebruikersNaam()."'";
	if($objGebruiker != false && $objGebruiker->getUserLevel() != "")
		$sql .= " AND g.userlevel >= '".$objGebruiker->getUserLevel()."'";
	if($objGebruiker != false && $objGebruiker->getIPAdres() != "")
		$sql .= " AND g.ipadres = '".$objGebruiker->getIPAdres()."'";
	$sql .= " ORDER BY p.id ASC ";
	if($intLimiet != "0")
		$sql .= " LIMIT ".$intVan.", ".$intLimiet." ";

	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Gebruiker');
}
// Functie om gebruiker te checken, false  als alles OK is. Een array met errorcodes als alles niet ok is
function checkGebruiker( $objGebruiker, $booNew = false, $booWachtWoord = false) {
	if($objGebruiker == false) $arrError[-1] = true;
	else {
		global $_arrConfig;
		// ID-nummer
		if($objGebruiker->getID() == "" && $booNew == false) $arrError[0] = true;
		elseif(strlen($objGebruiker->getID()) > 255 && $booNew == false) $arrError[1] = true; // Mag niet groter zijn dan 255 tekens		
		elseif(eregi("[^0-9]{1,}", $objGebruiker->getID())  && $booNew == false) $arrError[2] = true; // Alleen 0-9 zijn toegestaan		
		// Gebruikersnaam
		if($objGebruiker->getGebruikersNaam() == "") $arrError[10] = true;
		elseif(strlen($objGebruiker->getGebruikersNaam()) < 6) $arrError[11] = true; // Mag niet kleiner zijn dan 6 tekens
		elseif(strlen($objGebruiker->getGebruikersNaam()) > 12) $arrError[12] = true; // Mag niet groter zijn dan 12 tekens
		elseif(eregi("[^a-zA-Z0-9]{1,}", $objGebruiker->getGebruikersNaam())) $arrError[13] = true; // Alleen chars a-z, A-Z, 0-9 zijn toegestaan		
		// Wachtwoord
		if($booWachtWoord == true && $objGebruiker->getWachtwoordClear() == "") $arrError[20] = true;
		elseif($booWachtWoord == true && strlen($objGebruiker->getWachtwoordClear()) < 6) $arrError[21] = true; // Mag niet kleiner zijn dan 6 tekens
		elseif($booWachtWoord == true && strlen($objGebruiker->getWachtwoordClear()) > 12) $arrError[22] = true; // Mag niet groter zijn dan 12 tekens
		elseif($booWachtWoord == true && eregi("[^a-zA-Z0-9]{1,}", $objGebruiker->getWachtwoordClear())) $arrError[23] = true; // Alleen chars a-z, A-Z, 0-9 en ._- zijn toegestaan 	
		// Userlevel
		if($objGebruiker->getUserLevel() != "0" && $objGebruiker->getUserLevel() == "") $arrError[30] = true;
		elseif(strlen($objGebruiker->getUserLevel()) > 255) $arrError[31] = true; // Mag niet groter zijn dan 255 tekens		
		elseif(eregi("[^1-9]{1,}", $objGebruiker->getUserLevel())) $arrError[32] = true; // Alleen 1-9 zijn toegestaan
		
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om gebruiker te checken, of de gegevens al bestaan in de db oid. False als alles OK is
function checkGebruikerDB($objGebruiker, $booNew = false) {
	global $objDBConnectie;
	if($objGebruiker == false) $arrError[-1] = true;
	else {
		$sql = "SELECT * FROM gebruiker WHERE id = id ".
			   " AND gebruikersnaam = '".$objGebruiker->getGebruikersNaam()."'";
		if($booNew != true) 
			$sql .= " AND persoonid != '".$objGebruiker->getID()."'";
			
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else {
			$intArraySize = count($arrMysqlResult);
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpGebruiker = SQLArrToObj($arrMysqlResult, 'Gebruiker', $i);
				if($objTmpGebruiker != false && $objTmpGebruiker->getGebruikersNaam() == $objGebruiker->getGebruikersNaam())
					$arrError[40] = true; // Er is al een gebruiker met dezelfde naam
			}
		}
	}
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om gebruikerspagina te laten zien
function showGebruikerPagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
	showHeader();
	
	switch ($intID) {
	case 1: // Nieuwe gebruiker toevoegen
	  showGebruikerForm(false, $strMelding, $extraObj2);
	  break;  
	case 2: // Gebruiker bekijken
	  showGebruiker($extraObj, $strMelding, $extraObj2);
	  break;
	case 3: // Gebruiker bewerken, of als er iets mis is gegaan bij toevoegen
	  showGebruikerForm($extraObj, $strMelding, $extraObj2);
	  break;
	case 4: // Gebruiker verwijderen
	  showDelGebruikerForm($extraObj, $strMelding, $extraObj2);
	  break;
	case 5: // Wachtwoord opties
	  showGebruikerPassResetForm($extraObj, $strMelding, $extraObj2);
	  break;
	default: // Standaard, overzicht van gebruikers
	  if($extraObj == false) $extraObj = 0;
	  showGebruikersOverzicht($extraObj, 30, $extraObj2, $strMelding);
	}
 	showFooter();
}

// Functie om de informatie van een gebruiker op het scherm te tonen
function showGebruiker( $objGebruiker, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}
	$strTabelStijl = "info";
	if($objGebruiker != false) {
		echo openContentVak( "Gebruikersinformatie van '".$objGebruiker->getGebruikersNaam()."'", 'gebruiker', 'Gebruiker', $objGebruiker->getID() );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getGebruikersNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."E-mailadres: ".sluitCel().
		     openCel($strTabelStijl, 2).getLink("mailto:".$objGebruiker->getEmail(true), $objGebruiker->getEmail(true)).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Naam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getVolledigeNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Postcode: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getPostcode(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Woonplaats: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getWoonplaats(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon thuis: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelThuis(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon mobiel: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelMobiel(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon werk: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelWerk(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Laatst ingelogd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getLastLoginDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getStatusNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getUserLevel(true).sluitCel().sluitRij().
		     sluitTabel().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('gebruiker', "gebruikers").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
  
	}
	else showErrorPagina(4);
}
// Functie om het formulier op te vragen om een gebruiker te bewerken of toe te voegen
function showGebruikerForm( $objGebruiker = false, $strMelding = '', $arrErrors = false  ) {
	global $_objUser;
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				// Gebruikers
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De gebruiker is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				if(isset($arrErrors[10])) $strMeldingHTML .= "<li>De gebruikersnaam is niet ingevuld</li>\n";
				if(isset($arrErrors[11])) $strMeldingHTML .= "<li>De lengte van de gebruikersnaam is te klein</li>\n";
				if(isset($arrErrors[12])) $strMeldingHTML .= "<li>De lengte van de gebruikersnaam is te groot</li>\n";
				if(isset($arrErrors[13])) $strMeldingHTML .= "<li>De gebruikersnaam bevat tekens die niet toegestaan zijn</li>\n";
				if(isset($arrErrors[40])) $strMeldingHTML .= "<li>De gebruikersnaam is al bezet</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>Er is niet opgegeven wat de gebruikerslevel is van de gebruiker</li>\n";
				// Persoon
				if(isset($arrErrors[100]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				if(isset($arrErrors[110])) $strMeldingHTML .= "<li>Het emailadres is niet ingevuld</li>\n";
				if(isset($arrErrors[111]) || isset($arrErrors[112])) $strMeldingHTML .= "<li>Het emailadres is onjuist</li>\n";
				if(isset($arrErrors[140])) $strMeldingHTML .= "<li>Het emailadres is al in gebruikt door een andere gebruiker</li>\n";
				if(isset($arrErrors[120])) $strMeldingHTML .= "<li>De achternaam is niet ingevuld</li>\n";
				if(isset($arrErrors[121])) $strMeldingHTML .= "<li>De achternaam bevat tekens die niet toegestaan zijn</li>\n";
				if(isset($arrErrors[130])) $strMeldingHTML .= "<li>De woonplaats is niet ingevuld</li>\n";
				if(isset($arrErrors[131])) $strMeldingHTML .= "<li>De woonplaats bevat tekens die niet toegestaan zijn</li>\n";
				
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}
	if($objGebruiker != false && $objGebruiker->getID() != "") {
		$objGebruikerOrg = getGebruiker($objGebruiker);
		$strFormNaam = "editGebruiker";
		$strFormKnopNaam = "editGebruikerKnop";
		$strFormKnopWaarde = "Bewerk gebruiker";
		echo openContentVak( "Gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' bewerken", 'gebruiker', 'Gebruiker', $objGebruikerOrg->getID(), 'edit' );
	}
	else {
		if($objGebruiker == false)
			$objGebruiker = new Gebruiker();
		$strFormNaam = "addGebruiker";
		$strFormKnopNaam = "addGebruikerKnop";
		$strFormKnopWaarde = "Voeg gebruiker toe";
		echo openContentVak( "Nieuwe gebruiker toevoegen");
	}
	
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo openForm($strFormNaam, 'gebruiker').
	     showInputVeld('id',$objGebruiker->getID(), '', '', 'hidden').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('gebruikersnaam', $objGebruiker->getGebruikersNaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."E-mail: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('email', $objGebruiker->getEmail(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Voornaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('voornaam', $objGebruiker->getVoornaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Tussenvoegsel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('tussenvoegsel', $objGebruiker->getTussenvoegsel(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Achternaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('achternaam', $objGebruiker->getAchternaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Straatnaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('straatnaam', $objGebruiker->getStraatnaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Huisnummer: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('huisnr', $objGebruiker->getHuisNummer(true), '','','text',3, 5).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Postcode: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('postcode', $objGebruiker->getPostcode(true), '','','text',6, 7).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Woonplaats: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('woonplaats', $objGebruiker->getWoonplaats(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon thuis: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telthuis', $objGebruiker->getTelThuis(true), '','','text',11, 13).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon werk: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telwerk', $objGebruiker->getTelWerk(true), '','','text',11, 13).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon mobiel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telmobiel', $objGebruiker->getTelMobiel(true), '','','text',11, 13).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showSelectLijst('userlevel', $objGebruiker->getUserLevel(true), getUserLevels($_objUser->getUserLevel()), getUserLevels($_objUser->getUserLevel())).sluitCel().sluitRij();
	     //showSelectLijst('userlevel', $objGebruiker->getUserLevel(true), getUserLevels($_objUser->getUserLevel()), getUserLevels($_objUser->getUserLevel())).sluitCel().sluitRij().
	     //openRij($strTabelStijl).openCel($strTabelStijl)."Notificaties: ".sluitCel().
	     //openCel($strTabelStijl, 2).
	     //showJaNeeLijst('notificaties', $objGebruiker->getNotificaties(true)).sluitCel().sluitRij();
	     
	if($objGebruiker->getID() == "") {
	     echo openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
	     	  openCel($strTabelStijl, 2).
	     	  showActivatieLijst('status', $objGebruiker->getStatus(true)).sluitCel().sluitRij();
	}
	echo openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop($strFormKnopNaam, $strFormKnopWaarde,'buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().
	     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('gebruiker', "gebruikers").sluitCel().
	     sluitRij().sluitTabel().
	     sluitContentVak();
}
// Functie om het formulier op te vragen om een gebruiker te verwijderen
function showDelGebruikerForm( $objGebruiker, $strMelding = '', $arrErrors = false ) {
	global $_objUser;
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;				
		$strMeldingHTML .= "</div>\n";
	}
		
	$strTabelStijl = "info";
	if($objGebruiker != false) {
		echo openContentVak( "Gebruiker '".$objGebruiker->getGebruikersNaam()."' verwijderen", 'gebruiker', 'Gebruiker', $objGebruiker->getID(), 'del' );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		if($_objUser->getUserLevel() < $objGebruiker->getUserLevel()) {
			echo "<div class=\"error\">U heeft een lager gebruikerslevel dan deze gebruiker. Het is niet mogelijk om de gebruiker te verwijderen.</div>";
		}
		elseif($_objUser->getPersoonID() == $objGebruiker->getPersoonID()) {
			echo "<div class=\"error\">Het is niet mogelijk om uw eigen gebruikersaccount te verwijderen.</div>";
		}
			
		echo openForm('delGebruiker', 'gebruiker').
		     showInputVeld('id',$objGebruiker->getID(), '', '', 'hidden').
		     openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getGebruikersNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."E-mailadres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getEmail(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Naam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getVolledigeNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Postcode: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getPostcode(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Woonplaats: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getWoonplaats(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon thuis: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelThuis(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon mobiel: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelMobiel(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon werk: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelWerk(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getStatusNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getUserLevel(true).sluitCel().sluitRij();
		if($_objUser->getUserLevel() >= $objGebruiker->getUserLevel() && $_objUser->getPersoonID() != $objGebruiker->getPersoonID()) {
			echo openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
			     openCel($strTabelStijl, 2).
			     showInputKnop('delGebruikerKnop', 'Verwijder gebruiker','buttonStijl1').
			     sluitCel().sluitRij();
		}
		echo sluitTabel().
		     sluitForm().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('gebruiker', "gebruikers").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
	}
	else showErrorPagina(4);
}
// Functie om het formulier op te vragen om een gebruiker zijn wachtwoord te bewerken
function showGebruikerPassResetForm( $objGebruiker = false, $strMelding = '', $arrErrors = false  ) {
	global $_userLevel;
	global $_objUser;
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
	}
	if($objGebruiker != false) {
		$objGebruikerOrg = getGebruiker($objGebruiker);			
		echo openContentVak( "Wachtwoord van '".$objGebruikerOrg->getGebruikersNaam()."' resetten", 'gebruiker', 'Gebruiker', $objGebruikerOrg->getID(), false );
	
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openForm("resetPassForm", 'gebruiker').
		     showInputVeld('id',$objGebruiker->getID(), '', '', 'hidden').
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl, 4).
		     "Hieronder staan de optie(s) met betrekking tot het resetten van wachtwoorden. ".
		     "Het is mogelijk om het wachtwoord via e-mail te versturen naar de gebruiker, zodat ".
		     " de gebruiker weet dat zijn wachtwoord opnieuw is ingesteld. <br />\n".
		     " Voor superbeheerders is het mogelijk om ook het wachtwoord te zien op het scherm, ".
		     " zodat ze deze via andere manieren aan de gebruiker kunnen geven. <br />\n".
		     "  Bij de 1e keer inloggen met het nieuwe wachtwoord, moeten de gebruikers gelijk een ".
		     " nieuw wachtwoord opgeven. ".
		     sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl, 4).
		     showInputVeld('verstuur', 'true', '','', 'checkbox').
		     " Verstuur wachtwoord via e-mail naar de gebruiker".
		     sluitCel().sluitRij();
		if($_userLevel >= 3) {
		     echo openRij($strTabelStijl).openCel($strTabelStijl, 4).
		          showInputVeld('toon', 'true', '', '', 'checkbox').
		          " Toon het wachtwoord op het scherm ".
		          sluitCel().sluitRij();
		}
		echo openRij($strTabelStijl).openCel($strTabelStijl, 3).
		     showInputKnop("resetPassKnop","Reset wachtwoord",'buttonStijl1').
		     sluitCel().sluitRij().
		     sluitTabel().sluitForm().
		     openTabel($strTabelStijl, false).
			 openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('gebruiker', "gebruikers").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
	 }
	else showErrorPagina(4);

}
// Functie om het formulier op te vragen om een gebruiker te bewerken of toe te voegen
function showGebruikerPassForm( $objGebruiker = false, $strMelding = '', $arrErrors = false, $strSoort = ''  ) {
	global $_userLevel;
	global $_objUser;
	$strTabelStijl = "info";
	
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				// Gebruikers
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De gebruiker is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				if(isset($arrErrors[20])) $strMeldingHTML .= "<li>Het wachtwoord is niet ingevuld</li>\n";
				if(isset($arrErrors[21])) $strMeldingHTML .= "<li>De lengte van het wachtwoord is te klein</li>\n";
				if(isset($arrErrors[22])) $strMeldingHTML .= "<li>De lengte van het wachtwoord is te groot</li>\n";
				if(isset($arrErrors[23])) $strMeldingHTML .= "<li>Het wachtwoord bevat tekens die niet toegestaan zijn</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>Er is niet opgegeven wat de gebruikerslevel is van de gebruiker</li>\n";
				// Persoon
				if(isset($arrErrors[100]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}	
	if($objGebruiker != false) {
		$objGebruikerOrg = getGebruiker($objGebruiker);			
		echo openContentVak( "Verander wachtwoord van '".$objGebruikerOrg->getGebruikersNaam()."'" );
	
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openForm("changePassForm", 'profiel').
		     showInputVeld('soort',$strSoort, '', '', 'hidden').
		     openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Huidige wachtwoord: ".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputVeld('old_pass', '','','','password', '12', '12').sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Nieuw wachtwoord: ".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputVeld('new_pass1', '','','','password', '12', '12').sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Nogmaals nieuw wachtwoord: ".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputVeld('new_pass2', '','','','password', '12', '12').sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputKnop("changePassKnop", "Verander wachtwoord",'buttonStijl1').
		     sluitCel().sluitRij().
		     sluitTabel().sluitForm().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php', 'Bekijk profiel', 'profiel').
		     sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php?action=edit', 'Bewerk profiel', 'profiel').
		     sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php?action=pass', 'Verander wachtwoord', 'profiel').
		     sluitCel().sluitRij().
		     sluitTabel().
		     sluitContentVak();
	 }
	 else showErrorPagina(4);
}
// Functie om het overzicht van gebruikers te laten zien
function showGebruikersOverzicht( $intVan = 0, $intLimiet = 0, $objGebruiker = false, $strMelding = '' ) {
	if($intVan < 0) $intVan = 0;
	$arrGebruikers = getGebruikers($intVan, $intLimiet, $objGebruiker);
	$intArraySize = count($arrGebruikers);

	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}	
	$strTabelStijl = "overzicht";
	echo openContentVak( "Gebruikersoverzicht").
		 "Hieronder staat het overzicht van de gebruikers in het systeem. ";
		 if($intArraySize != 0 && $arrGebruikers != false)
		 	echo "Gebruiker ".($intVan + 1)." tot en met ".($intVan + $intArraySize)." worden getoond.\n";
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;		 
	
	if($arrGebruikers != false && $intArraySize != 0) {
		echo openTabel($strTabelStijl, false).
			 openRij($strTabelStijl).openDiv('',$strTabelStijl."TabelVeldTitel1")."Gebruiker: ".sluitDiv().
			 openDiv('',$strTabelStijl."TabelVeldTitel2")."Acties:".sluitDiv().sluitRij();
		$intVeldID = 1;
		for($i = 0; $i < $intArraySize; $i++) {
			$objGebruiker = $arrGebruikers[$i];
			if($objGebruiker != false) {
				echo openRij($strTabelStijl).openCel($strTabelStijl, $intVeldID).
					 getActieLink( $objGebruiker->getID(), 'gebruiker', 'view', false, $objGebruiker->getGebruikersNaam(), 'Bekijk gebruiker').
					 sluitCel().
					 getActieMenu($objGebruiker->getID(),  'gebruiker', true, true, true, true, 'overzicht',$intVeldID).
					 sluitRij();		
				if($intVeldID == 1) $intVeldID = 2;
				elseif($intVeldID == 2) $intVeldID = 1;
			}
		}
		echo sluitTabel();
	}
	else {
		echo "<br/><br/>Er zijn nog geen gebruikers in de database.";
	}
	
	
	echo openTabel('info', false).
	     openRij('info').openCel('info',3).
	     getLink('index.php?&action=add', "Voeg een gebruiker toe", 'gebruiker').
	     sluitCel().sluitRij().sluitTabel().
		 sluitContentVak();
}
// Functie om een e-mail te versturen mbt wachtwoord
function verstuurWachtwoord($objGebruiker, $strSoort = 'changed_pass') {
	global $_arrConfig;
	if($objGebruiker != false) {
		$strTekst = "Beste ".$objGebruiker->getVolledigeNaam().",\n\n";
		if($strSoort == "changed_pass") {
		  	$strTekst .= "Zojuist is door een beheerder van ".$_arrConfig['website_title']." het wachtwoord ".
		  				"van uw account opnieuw ingesteld. Hieronder staan de gebruikersnaam en het nieuwe ". 
		  				"wachtwoord vermeld. ";
			$strOnderwerp = "[".$_arrConfig['website_title_afk']."] Wachtwoord opnieuw ingesteld";
		}
		elseif($strSoort == "new_user") {
		  	$strTekst .= "Zojuist is door een beheerder van ".$_arrConfig['website_title']." een ".
		  				"gebruikersaccount aangemaakt met dit e-mailadres. Hieronder staan uw gebruikersnaam ". 
		  				"en uw wachtwoord vermeld. ";
			$strOnderwerp = "[".$_arrConfig['website_title_afk']."] Activatie gebruikersaccount";
		}
		$strTekst .= "Let wel op, er wordt onderscheid gemaakt tussen kleine en ".
		  			"hoofdletters. \n\n".
		  			"Gebruikersnaam: ".$objGebruiker->getGebruikersNaam(true).
		  			"\nWachtwoord: ".$objGebruiker->getWachtwoordClear(true).
		  			"\n\n".
		  			"U kunt vanaf nu met deze inloggegevens inloggen op: \n".
		  			$_arrConfig['website_url'].$_arrConfig['work_dir'].
		  			"\n\n".
		  			"Na het inloggen zal er gevraagd worden om uw wachtwoord te veranderen, dit vanwege ".
		  			" beveiligingsmaatregelen.\n\n".
		  			"Met vriendelijke groeten, \n\n".
		  			$_arrConfig['website_title'].
		  			"\n---------------------------------------------------------------".
		  			"\nNB: Dit is een automatisch gegenereerd bericht.";
				  
		if(verstuurMail($objGebruiker->getEmail(), $strTekst, $strOnderwerp )) return true;
		else return false;
	}
	else return false;
}
// Functie om een bevestigingse-mail te versturen
function verstuurBevestiging($objGebruiker, $objStatus) {
	global $_arrConfig;
	if($objGebruiker != false) {
		$strTekst = "Beste ".$objGebruiker->getVolledigeNaam().",\n\n".
		  			"Zojuist is er door een beheerder van ".$_arrConfig['website_title']." een gebruiker ".
		  			"toegevoegd met dit e-mailadres. Voordat nieuwe gebruikers toegang hebben tot de ". 
		  			"applicatie, moet de registratie eerst bevestigd worden. " .
		  			"Dit moet wel voor ".$objStatus->getEindDatumNet()." gedaan worden, anders ".
		  			"vervalt de toevoeging. ".
		  			"De registratie kan worden bevestigd door op de onderstaande ".
		  			"hyperlink te klikken: \n".
		  			$_arrConfig['website_url'].$_arrConfig['work_dir']."index.php?module=status&id=".
		  			$objGebruiker->getPersoonID()."&str=".$objStatus->getUniekeString()."\n\n".
		  			"Vervolgens kunt u inloggen met de onderstaande gegevens. Let wel op, er wordt ".
		  			"onderscheid gemaakt tussen kleine en hoofdletters.\n\n".
		  			"Gebruikersnaam: ".$objGebruiker->getGebruikersNaam(true).
		  			"\nWachtwoord: ".$objGebruiker->getWachtwoordClear(true).
		  			"\n\n".
		  			"Na de 1e keer inloggen zal er gevraagd worden om uw wachtwoord te veranderen, dit  ".
		  			" vanwege beveiligingsmaatregelen.\n\n".
		  			"Met vriendelijke groeten, \n\n".
		  			$_arrConfig['website_title'].
		  			"\n---------------------------------------------------------------".
		  			"\nNB: Dit is een automatisch gegenereerd bericht.";
				  
		$strOnderwerp = "[".$_arrConfig['website_title_afk']."] Bevestiging om gebruikersaccount te activeren";
		
		
		if(verstuurMail($objGebruiker->getEmail(), $strTekst, $strOnderwerp )) return true;
		else return false;
	}
	else return false;
	
}
// Functie om een bevestigingse-mail te versturen
function verstuurMailBevestiging($objGebruiker, $objStatus) {
	global $_arrConfig;
	if($objGebruiker != false && $objStatus != false) {
		$strTekst = "Beste ".$objGebruiker->getVolledigeNaam().",\n\n".
		  			"Zojuist is het e-mailadres van uw gebruikersaccount veranderd. ".
		  			"Om te controleren of het opgegeven e-mailadres wel bestaat en ook juist is, ". 
		  			"moet deze verandering bevestigd worden door op de hyperlink hieronder te klikken. " .
		  			"Dit moet wel voor ".$objStatus->getEindDatumNet()." gedaan worden, anders vervalt ".
		  			"de verandering. \n\n".
		  			"Klik op de hyperlink om de verandering te bevestigen: \n".
		  			$_arrConfig['website_url'].$_arrConfig['work_dir']."index.php?module=status&id=".
		  			$objGebruiker->getPersoonID()."&str=".$objStatus->getUniekeString()."\n\n".
		  			"Met vriendelijke groeten, \n\n".
		  			$_arrConfig['website_title'].
		  			"\n---------------------------------------------------------------".
		  			"\nNB: Dit is een automatisch gegenereerd bericht.";
				  
		$strOnderwerp = "[".$_arrConfig['website_title_afk']."] Bevestiging om e-mailadres te veranderen";
		
		
		if(verstuurMail($objGebruiker->getEmail(), $strTekst, $strOnderwerp )) return true;
		else return false;
	}
	else return false;
}


// Aparte functies voor het profiel
// Functie om de informatie van een gebruiker op het scherm te tonen
function showProfiel( $objGebruiker, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}
	$strTabelStijl = "info";
	if($objGebruiker != false) {
		echo openContentVak( "Profiel van '".$objGebruiker->getGebruikersNaam()."'");
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getGebruikersNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."E-mailadres: ".sluitCel().
		     openCel($strTabelStijl, 2).getLink("mailto:".$objGebruiker->getEmail(true), $objGebruiker->getEmail(true)).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Naam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getVolledigeNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Postcode: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getPostcode(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Woonplaats: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getWoonplaats(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon thuis: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelThuis(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon mobiel: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelMobiel(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon werk: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objGebruiker->getTelWerk(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Laatst ingelogd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getLastLoginDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Status: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getStatusNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitCel().
		     openCel($strTabelStijl, 2).$objGebruiker->getUserLevel(true).sluitCel().sluitRij().
		     sluitTabel().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php', 'Bekijk profiel', 'profiel').
		     sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php?action=edit', 'Bewerk profiel', 'profiel').
		     sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl,4).
		     getLink('index.php?action=pass', 'Verander wachtwoord', 'profiel').
		     sluitCel().sluitRij().
		     sluitTabel().
		     sluitContentVak();
  
	}
	else showErrorPagina(4);
}

// Functie om het formulier op te vragen om een eigen gegevens te bewerken
function showEditProfiel( $objGebruiker, $strMelding = '', $arrErrors = false  ) {
	global $_objUser;
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				// Gebruikers
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De gebruiker is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				if(isset($arrErrors[10])) $strMeldingHTML .= "<li>De gebruikersnaam is niet ingevuld</li>\n";
				if(isset($arrErrors[11])) $strMeldingHTML .= "<li>De lengte van de gebruikersnaam is te klein</li>\n";
				if(isset($arrErrors[12])) $strMeldingHTML .= "<li>De lengte van de gebruikersnaam is te groot</li>\n";
				if(isset($arrErrors[13])) $strMeldingHTML .= "<li>De gebruikersnaam bevat tekens die niet toegestaan zijn</li>\n";
				if(isset($arrErrors[40])) $strMeldingHTML .= "<li>De gebruikersnaam is al bezet</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>Er is niet opgegeven wat de gebruikerslevel is van de gebruiker</li>\n";
				// Persoon
				if(isset($arrErrors[100]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de gebruiker is onjuist</li>\n";
				if(isset($arrErrors[110])) $strMeldingHTML .= "<li>Het emailadres is niet ingevuld</li>\n";
				if(isset($arrErrors[111]) || isset($arrErrors[112])) $strMeldingHTML .= "<li>Het emailadres is onjuist</li>\n";
				if(isset($arrErrors[140])) $strMeldingHTML .= "<li>Het emailadres is al in gebruikt door een andere gebruiker</li>\n";
				if(isset($arrErrors[120])) $strMeldingHTML .= "<li>De achternaam is niet ingevuld</li>\n";
				if(isset($arrErrors[121])) $strMeldingHTML .= "<li>De achternaam bevat tekens die niet toegestaan zijn</li>\n";
				if(isset($arrErrors[130])) $strMeldingHTML .= "<li>De woonplaats is niet ingevuld</li>\n";
				if(isset($arrErrors[131])) $strMeldingHTML .= "<li>De woonplaats bevat tekens die niet toegestaan zijn</li>\n";
				
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}
	if($objGebruiker != false && $objGebruiker->getID() != "") {
	echo openContentVak( "Bewerk gegevens", false);	
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo openForm('editProfiel', 'profiel').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('gebruikersnaam', $objGebruiker->getGebruikersNaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."E-mail: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('email', $objGebruiker->getEmail(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Voornaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('voornaam', $objGebruiker->getVoornaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Tussenvoegsel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('tussenvoegsel', $objGebruiker->getTussenvoegsel(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Achternaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('achternaam', $objGebruiker->getAchternaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Straatnaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('straatnaam', $objGebruiker->getStraatnaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Huisnummer: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('huisnr', $objGebruiker->getHuisNummer(true), '','','text',3, 5).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Postcode: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('postcode', $objGebruiker->getPostcode(true), '','','text',6, 7).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Woonplaats: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('woonplaats', $objGebruiker->getWoonplaats(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon thuis: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telthuis', $objGebruiker->getTelThuis(true), '','','text',11, 13).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon werk: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telwerk', $objGebruiker->getTelWerk(true), '','','text',11, 13).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Telefoon mobiel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('telmobiel', $objGebruiker->getTelMobiel(true), '','','text',11, 13).sluitCel().sluitRij().
		 openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop("editProfielKnop", "Bewerk gegevens",'buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().    
	     openTabel($strTabelStijl, false).
	     openRij($strTabelStijl).openCel($strTabelStijl,4).
	     getLink('index.php', 'Bekijk profiel', 'profiel').
	     sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl,4).
	     getLink('index.php?action=edit', 'Bewerk profiel', 'profiel').
	     sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl,4).
	     getLink('index.php?action=pass', 'Verander wachtwoord', 'profiel').
	     sluitCel().sluitRij().
	     sluitTabel().
	     sluitContentVak();
	}
	else showErrorPagina(0);
}

// Functie om profielpagina te laten zien
function showProfielPagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
	showHeader();
	
	switch ($intID) {
	case 1: // Profiel bekijken
	  showProfiel($extraObj, $strMelding, $extraObj2);
	  break;  
	case 2: // Profiel bewerken
	  showEditProfiel($extraObj, $strMelding, $extraObj2);
	  break;
	case 3: // Wachtwoord veranderen
	  showGebruikerPassForm($extraObj, $strMelding, $extraObj2);
	  break;
	case 4: // Wachtwoord veranderen gebruiker, omdat ww automatisch is ingesteld
	  showGebruikerPassForm($extraObj, $strMelding, $extraObj2, 'changed_pass');
	  break;
	case 5: // Wachtwoord veranderen gebruiker, omdat het nieuwe user is
	  showGebruikerPassForm($extraObj, $strMelding, $extraObj2, 'new_user');
	  break;
	default: // Profiel bekijken
	  if($extraObj == false) $extraObj = 0;
	  showGebruikersOverzicht($extraObj, 30, $extraObj2, $strMelding);
	}
 	showFooter();
}
?>
