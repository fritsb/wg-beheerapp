<?php
/* Bestandsnaam: loginfuncties.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt inloggen staan
 *
 */

// Functie om de module-gegevens toe te voegen
function checkLGebruiker($objGebruiker, $strSoort = 'inlog') {
	global $objDBConnectie;
	if($objGebruiker == false) $arrError[-1] = true;
	else {
		if($strSoort == "inlog") {
			// Gebruikersnaam
			if($objGebruiker->getGebruikersNaam() == "") $arrError[0] = true;
			elseif(strlen($objGebruiker->getGebruikersNaam()) < 6) $arrError[1] = true; // Mag niet kleiner zijn dan 255 tekens
			elseif(strlen($objGebruiker->getGebruikersNaam()) > 12) $arrError[2] = true; // Mag niet groter zijn dan 255 tekens
			elseif(eregi("[^a-zA-Z0-9]{1,}", $objGebruiker->getGebruikersNaam())) $arrError[3] = true; // Alleen a-z, A-Z en 0-9 zijn toegestaan
			// Wachtwoord
			if($objGebruiker->getWachtwoordClear() == "") $arrError[10] = true;
			elseif(strlen($objGebruiker->getWachtwoordClear()) < 6) $arrError[10] = true; // Mag niet kleiner zijn dan 6 tekens
			elseif(strlen($objGebruiker->getWachtwoordClear()) > 12) $arrError[20] = true; // Mag niet groter zijn dan 12 tekens
			elseif(eregi("[^a-zA-Z0-9]{1,}", $objGebruiker->getWachtwoordClear())) $arrError[30] = true; // Alleen a-z, A-Z en 0-9 zijn toegestaan
		}
		elseif($strSoort == "aanvraag") {
			if($objGebruiker->getGebruikersNaam() == "" && $objGebruiker->getEmail() == "") $arrError[0] = true;
			elseif($objGebruiker->getGebruikersNaam() != "" && strlen($objGebruiker->getGebruikersNaam()) < 6) $arrError[1] = true; // Mag niet kleiner zijn dan 6 tekens
			elseif($objGebruiker->getGebruikersNaam() != "" && strlen($objGebruiker->getGebruikersNaam()) > 12) $arrError[2] = true; // Mag niet groter zijn dan 12 tekens
			elseif($objGebruiker->getGebruikersNaam() != "" && eregi("[^a-zA-Z0-9]{1,}", $objGebruiker->getGebruikersNaam())) $arrError[3] = true; // Alleen a-z, A-Z en 0-9 zijn toegestaan
			elseif($objGebruiker->getEmail() != "" && strlen($objGebruiker->getEmail()) < 5) $arrError[10] = true; // Kan niet kleiner zijn dan 5 tekens
			elseif($objGebruiker->getEmail() != "" && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{1,})*\.([a-z]{2,}){1}$", $objGebruiker->getEmail())) $arrError[11] = true; 

		}
		
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om de module-gegevens te bewerken
function verstuurAanvraag($objGebruiker, $objStatus) {
	global $_arrConfig;
	if($objGebruiker == false) echo "HEUJ FALSIE";
	if($objStatus == false) echo "HEUJ FALSI2E";
	
	if($objGebruiker != false && $objStatus != false) {
		$strTekst = "Beste ".$objGebruiker->getVolledigeNaam().",\n\n".
		  			"Zojuist is er een aanvraag gedaan om het wachtwoord van uw gebruikersaccount ".
		  			"te veranderen. Om te controleren of u hier zelf opdracht voor heb gegeven, ". 
		  			"moet deze aanvraag bevestigd worden door op de hyperlink hieronder te klikken. " .
		  			"Dit moet wel voor ".$objStatus->getEindDatumNet()." gedaan worden, anders vervalt ".
		  			"de aanvraag. \n\n".
		  			"Klik op de hyperlink om de aanvraag te bevestigen: \n".
		  			$_arrConfig['website_url'].$_arrConfig['work_dir']."index.php?module=status&id=".
		  			$objGebruiker->getPersoonID()."&str=".$objStatus->getUniekeString()."\n\n".
		  			"Als u deze aanvraag niet heeft gedaan, hoeft u niets te doen. De aanvraag ".
		  			" zal dan binnen een paar dagen vervallen. \n\n".
		  			"Met vriendelijke groeten, \n\n".
		  			$_arrConfig['website_title'].
		  			"\n---------------------------------------------------------------".
		  			"\nNB: Dit is een automatisch gegenereerd bericht.";
				  
		$strOnderwerp = "[".$_arrConfig['website_title_afk']."] Bevestiging om wachtwoord te veranderen";
		
		
		if(verstuurMail($objGebruiker->getEmail(), $strTekst, $strOnderwerp )) return true;
		else return false;
	}
	else return false;	 
}
// Functie die regelt wat er op de pagina komt
function showLoginPagina($intID = 0, $strMelding = '', $extraObj = false ) {
	showHeader();
	
	switch ($intID) {
	case 1: // Inloggen is gelukt
	  global $_objUser;
	  showContentVak("Welkom ".$_objUser->getVolledigeNaam()."!", $strMelding);
	  break;  
	case 2: // Aanvraagformulier
	  showAanvraagForm($strMelding, $extraObj);
	  break;
	case 3: // Aanvraag gelukt
	  showContentVak('Wachtwoord succesvol aangevraagd', $strMelding);
	  break;
	case 4: // Gebruiker is al ingelogd
	  global $_objUser;
	  showContentVak("Welkom ".$_objUser->getVolledigeNaam()."!", $strMelding);
	  break;  
	default: // Standaard, loginformulier
	  showLoginForm($strMelding, $extraObj);
	}
 	showFooter();

}
// Functie om het loginscherm tevoorschijn te toveren
function showLoginForm($strMelding = '', $arrErrors = false) {
	global $_arrConfig;
	$strTabelStijl = "info";
	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		if($arrErrors != false)	 { // array doorlopen	
			$strMeldingHTML .= "<ul>\n";
			if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De gebruiker is onjuist</li>\n";
			if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2]) || isset($arrErrors[3])) $strMeldingHTML .= "<li>Er is geen of een onjuist gebruikersnaam ingevuld</li>\n";
			if(isset($arrErrors[10]) || isset($arrErrors[11]) || isset($arrErrors[12]) || isset($arrErrors[13])) $strMeldingHTML .= "<li>Er is geen of een onjuist wachtwoord ingevuld</li>\n";
			$strMeldingHTML .= "</ul>\n";
		}	
		$strMeldingHTML .= "</div>\n";
	}
	echo openContentVak( "Inloggen" );
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	$objTmpBan = new Ban();
	$objTmpBan->setIPadres( $_SERVER['REMOTE_ADDR']);
	$objBan = getBan($objTmpBan, false, false, true);
		
	if($objBan != false && $objBan->getEindDatum() > getDatumTijd() && $objBan->getBeginDatum() < getDatumTijd()) {
		echo "<div class=\"error\">U heeft (voorlopig) geen toegang meer tot deze applicatie.</div>\n";			 		
	}
	elseif(isset($_SESSION['ban']) && $_SESSION['ban'] == true) {
		echo "<div class=\"error\">U heeft (voorlopig) geen toegang meer tot deze applicatie.</div>\n";		
	}
	elseif(isset($_SESSION['false_login']) && $_SESSION['false_login'] >= $_arrConfig['login_pogingen']) {
		echo "<div class=\"error\">U heeft ".$_arrConfig['login_pogingen']." keer geprobeerd om in te loggen met foute gegevens. ".
			  "Voorlopig kunt u niet meer inloggen op deze website.</div>\n";
		echo "Over een uur vervalt u ban en kunt u weer proberen om in te loggen. Als u uw wachtwoord bent vergeten, kunt u deze ".
			  getLink('index.php?action=aanvraag', 'hier aanvragen', 'login').".";
		$objBan = new Ban();
		$objBan->setIPadres($_SERVER['REMOTE_ADDR']);
		$objBan->setBeginDatum(getDatumTijd());
		$objBan->setToevoegDatum(getDatumTijd()); 
		$objBan->setEindDatum(getToekomstDatumTijd(0, 0, 0, 1, true));
		$objBan->setReden('false_login');
		$objBan->setStatus(1);
		addBan($objBan);
		$_SESSION['ban'] = true;
	}
	else {
		if(isset($_SESSION['false_login']) && $_SESSION['false_login'] != 0) {
			$intAantal = $_SESSION['false_login'];
			echo "<div class=\"error\">U heeft ".$intAantal." keer geprobeerd om in te loggen met foute gegevens. U heeft nog ".
				 ($_arrConfig['login_pogingen'] - $intAantal)." pogingen, voordat u tijdelijk wordt verbannen van de website.</div>\n";		
		}
		
		echo "Voer uw gegevens hieronder in om in te loggen voor de applicatie van Wireless Grootebroek.".
		     openForm('login', 'login').
		     openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputVeld('gebruikersnaam', '', '', '' ,'text', '12', '12').sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Wachtwoord: ".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputVeld('wachtwoord', '', '', '', 'password', '12', '12').sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputKnop('loginKnop', 'Inloggen','buttonStijl1').
		     sluitCel().sluitRij().
		     sluitTabel().sluitForm().
		     
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).
		     "<a href=\"".$_SERVER['PHP_SELF']."?module=login&action=aanvraag\">Als u uw gegevens bent vergeten, klik dan hier.</a>".
		     sluitCel().
		     sluitRij().sluitTabel();
	}
	echo sluitContentVak();	
}
// Functie om het aanvraag form tevoorschijn te toveren
function showAanvraagForm($strMelding = '', $arrErrors) {
	$strTabelStijl = "info";
	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		if($arrErrors != false)	 { // array doorlopen	
			$strMeldingHTML .= "<ul>\n";
			if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De gebruiker is onjuist</li>\n";
			if(isset($arrErrors[0])) $strMeldingHTML .= "<li>Er is geen gebruikersnaam of e-mailadres ingevuld</li>\n";
			if(isset($arrErrors[1]) || isset($arrErrors[2]) || isset($arrErrors[3])) $strMeldingHTML .= "<li>De opgegeven gebruikersnaam is onjuist</li>\n";
			if(isset($arrErrors[10]) || isset($arrErrors[11])) $strMeldingHTML .= "<li>Het opgegeven e-mailadres is onjuist</li>\n";
			$strMeldingHTML .= "</ul>\n";
		}	
		$strMeldingHTML .= "</div>\n";
	}
	
	echo openContentVak( "Uw gegevens opvragen" );
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
		
	echo "Het onderstaande formulier kan gebruikt worden om uw gebruikersgegevens, zoals je wachtwoord of gebruikersnaam, op te vragen.\n".
		 "Vul een van de twee velden in.".
	     openForm('aanvraagform', 'login').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikersnaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('gebruikersnaam').sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."E-mailadres: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('email').sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop('aanvraagKnop', 'Gegevens opvragen','buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().
	     
	     openTabel($strTabelStijl, false).
	     openRij($strTabelStijl).openCel($strTabelStijl,3).
	     "<a href=\"".$_SERVER['PHP_SELF']."?module=login\">Om in te loggen, klik dan hier.</a>".
	     sluitCel().
	     sluitRij().sluitTabel().
	     sluitContentVak();	
}
// Functie om de laatste inlogdatum bij te werken van de gebruiker
function updateLastLogin($objGebruiker) {
	global $objDBConnectie;
	$sql = "UPDATE gebruiker SET lastlogin = '".$objGebruiker->getLastLogin()."', ipadres = '".$objGebruiker->getIPadres()."'  ".
	       " WHERE persoonid = '".$objGebruiker->getPersoonID()."'";
	return $objDBConnectie->setData($sql);
}



?>
