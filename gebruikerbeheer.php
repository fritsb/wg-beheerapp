<?php
/* Bestandsnaam: gebruikersbeheer.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt gebruikers regelt
 *
 */

 if(isset($_GET['action']) && isset($_GET['id'])) {
 	$objTmpGebruiker = new Gebruiker();
 	$objTmpGebruiker->setID($_GET['id'], true);
 	
 	if($objTmpGebruiker->getID() >= 1) {
	 	$objGebruiker = new Gebruiker();
	 	$objGebruiker = getGebruiker($objTmpGebruiker);
	 	
	 	if($_GET['action'] == "edit") {
	 		showGebruikerPagina(3,'',$objGebruiker);
	 	}
	 	elseif($_GET['action'] == "del") {
	 		 showGebruikerPagina(4,'',$objGebruiker);
	 	}
	 	elseif($_GET['action'] == "view") {
	 		showGebruikerPagina(2,'',$objGebruiker);
	 	}
	 	elseif($_GET['action'] == "pass") {
	 		showGebruikerPagina(5,'',$objGebruiker);
	 	}
	 	else showErrorPagina(1);
 	}
 	else showErrorPagina(1);
 	
 }
 elseif(isset($_GET['action']) && $_GET['action'] == "add") {
 	showGebruikerPagina(1);
 }
 elseif(isset($_POST['id'])) {
 	$objTmpGebruiker = new Gebruiker();
 	$objTmpGebruiker->setID($_POST['id'], true);
	if(isset($_POST['addGebruikerKnop'])) {
		$booToon = false;
		if(isset($_POST['toon']) && $_userLevel >= 3) $booToon = true;
	
	 	$objGebruiker = new Gebruiker();
	 	$objGebruiker->setValues($_POST, true);
	 	$objGebruiker->setToevoegDatum(getDatumTijd());
	 	$objGebruiker->setAanmeldDatum(getDatumTijd());
	 	$arrErrorsG = checkGebruiker($objGebruiker, true);
	 	$arrErrorsP = checkPersoon($objGebruiker, true);
	  	$arrDBErrorsG = checkGebruikerDB($objGebruiker, true);
	 	$arrDBErrorsP = checkPersoonDB($objGebruiker, true);
	 	

	 	if($arrErrorsG != false && $arrErrorsP != false) $arrErrors = $arrErrorsG + $arrErrorsP;
	 	elseif($arrErrorsG == false && $arrErrorsP != false) $arrErrors = $arrErrorsP;
	 	elseif($arrErrorsG != false && $arrErrorsP == false) $arrErrors = $arrErrorsG;
	 	
	 	if($arrDBErrorsG != false && $arrDBErrorsP != false) $arrDBErrors = $arrDBErrorsG + $arrDBErrorsP;
	 	elseif($arrDBErrorsG == false && $arrDBErrorsP != false) $arrDBErrors = $arrDBErrorsP;
	 	elseif($arrDBErrorsG != false && $arrDBErrorsP == false) $arrDBErrors = $arrDBErrorsG;
	 	
	 	if($arrErrors == false && $arrDBErrors == false) {
		    $objGebruiker->setID(getNewID('persoon'));
		    $objGebruiker->setPersoonID( $objGebruiker->getID() );
			$strWachtwoord = generateRandomString(8);
			$objStatus = new Status();
			if($objGebruiker->getStatus() == "0") {
				$objStatus->setStatus(-1);
				$objStatus->setSoort('new_user');
				$objStatus->setEindDatum( getToekomstDatumTijd(7) );
			}
			elseif($objGebruiker->getStatus() == "1") {
				$objStatus->setSoort('changed_pass');
				$objStatus->setStatus(0);
			}
			$objStatus->setBeginDatum( getDatumTijd() );
			$objStatus->setPersoonID( $objGebruiker->getPersoonID() );
			$objStatus->setIPadres($_SERVER['REMOTE_ADDR']);
			$objStatus->setUniekeString( generateRandomString(10) );
			$objStatus->setToevoegDatum( getDatumTijd() );
			$objGebruiker->setWachtwoordClear( $strWachtwoord );
	 	}
	 	
	 	if($arrErrors != false) {
	 		$strMelding = "De gebruiker kon niet worden toegevoegd.";
	 		showGebruikerPagina(3,$strMelding,$objGebruiker, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De gebruiker kon niet worden toegevoegd.";
	 		showGebruikerPagina(3,$strMelding,$objGebruiker, $arrDBErrors);
	 	}
	 	elseif(addPersoon($objGebruiker) == false || addGebruiker($objGebruiker) == false) {
	 		$strMelding = "De gebruiker kon niet worden toegevoegd.";
	 		showGebruikerPagina(3,$strMelding,$objGebruiker);
	 	}
		elseif(addStatus($objStatus) == false) {
			$strMelding = "De gebruiker is wel toegevoegd, maar het wachtwoord kon niet worden ingesteld.".
						  " Probeer dit nogmaals via gebruikersbeheer.";
			showGebruikerPagina(2, $strMelding, $objGebruiker);
		}
		elseif($objGebruiker->getStatus() == "0" && verstuurBevestiging($objGebruiker, $objStatus) == false ) {
			$strMelding = "De gebruiker is wel toegevoegd, maar de bevestigingse-mail kon niet worden ".
						  "verzonden naar de gebruiker. ";
			showGebruikerPagina(2, $strMelding, $objGebruiker);
		}

		elseif($objGebruiker->getStatus() == "1" && verstuurWachtwoord($objGebruiker, 'new_user') == false ) {
			$strMelding = "De gebruiker is wel toegevoegd, maar de e-mail met het wachtwoord kon niet worden ".
						  "verzonden naar de gebruiker. ";
			showGebruikerPagina(2, $strMelding, $objGebruiker);
		}
	 	else {
			$strMelding = "De gebruiker is succesvol toegevoegd! ";
			if($objGebruiker->getStatus() == "0") {
				$strMelding .= "Er is een bevestiging verstuurd naar het e-mailadres van de gebruiker. De gebruiker moet binnen 7 dagen op ".
			              " de hyperlink in de e-mail klikken om de registratie te bevestigen. ";
			}
			elseif($objGebruiker->getStatus() == "1") {
				$strMelding .= "Er is een e-mail verstuurd naar het e-mailadres van de gebruiker met daarin de logingegevens. ";
			}
			if($booToon == true) $strMelding .= " <br />Het wachtwoord is ".$strWachtwoord;
			
			showGebruikerPagina(2,$strMelding,$objGebruiker);
	 	}
	}
 	elseif(isset($_POST['editGebruikerKnop'])) {
 		$objGebruikerOrg = getGebruiker($objTmpGebruiker);
 		$objGebruiker = getGebruiker($objTmpGebruiker);
 		$objGebruiker->setValues($_POST, true);
	 	$arrErrorsG = checkGebruiker($objGebruiker);
	 	$arrErrorsP = checkPersoon($objGebruiker);
	 	$booMailChanged = false;

	 	$arrDBErrorsG = checkGebruikerDB($objGebruiker);
	 	$arrDBErrorsP = checkPersoonDB($objGebruiker);

	 	if($arrErrorsG != false && $arrErrorsP != false) $arrErrors = $arrErrorsG + $arrErrorsP;
	 	elseif($arrErrorsG == false && $arrErrorsP != false) $arrErrors = $arrErrorsP;
	 	elseif($arrErrorsG != false && $arrErrorsP == false) $arrErrors = $arrErrorsG;
	 	
	 	if($arrDBErrorsG != false && $arrDBErrorsP != false) $arrDBErrors = $arrDBErrorsG + $arrDBErrorsP;
	 	elseif($arrDBErrorsG == false && $arrDBErrorsP != false) $arrDBErrors = $arrDBErrorsP;
	 	elseif($arrDBErrorsG != false && $arrDBErrorsP == false) $arrDBErrors = $arrDBErrorsG;
	 	
	 	if($objGebruikerOrg != false && $objGebruiker != false && $objGebruikerOrg->getEmail() != $objGebruiker->getEmail()) $booMailChanged = true;
		
	 	if($booMailChanged == true) {
	 		$objStatus = new Status();	
			$objStatus->setSoort('changed_mail');
			$objStatus->setEindDatum( getToekomstDatumTijd(7) );
			$objStatus->setBeginDatum( getDatumTijd() );
			$objStatus->setToevoegDatum( getDatumTijd() );
			$objStatus->setPersoonID( $objGebruikerOrg->getPersoonID() );
			$objStatus->setIPadres($_SERVER['REMOTE_ADDR']);
			$objStatus->setUniekeString( generateRandomString(10) );
			$objStatus->setInfo($objGebruiker->getEmail() );
			$objGebruiker->setWachtwoordClear( $strWachtwoord );
			$objGebruiker->setEmail( $objGebruikerOrg->getEmail() );
	 	}
	 	
		if($objGebruikerOrg == false) {
			$strMelding = "De gegevens van de gebruiker kunnen niet worden aangepast, er is iets misgegaan.";
			showGebruikerPagina(0,$strMelding);
		}
	 	elseif($arrErrors != false) {
	 		$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' kunnen niet worden aangepast.";
	 		showGebruikerPagina(3,$strMelding, $objGebruiker, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' kunnen niet worden aangepast.";
	 		showGebruikerPagina(3,$strMelding, $objGebruiker, $arrDBErrors);
	 	}
	 	elseif(editGebruiker($objGebruiker) == false || editPersoon($objGebruiker) == false) {
 			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' kunnen niet worden aangepast, er is iets mis met de database.";
 			showGebruikerPagina(3,$strMelding, $objGebruiker);
	 	}
	 	elseif($booMailChanged == true && addStatus($objStatus) == false) {
 			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' zijn wel aangepast, maar het e-mailadres kon niet worden aangepast. ";
 			showGebruikerPagina(3,$strMelding, $objGebruiker);
	 	}
	 	elseif($booMailChanged == true && verstuurMailBevestiging($objGebruiker, $objStatus) == false) {
 			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' zijn wel aangepast, maar de ".
 						 " e-mail om de verandering van het e-mailadres te bevestigen kon niet worden verstuurd.";
 			showGebruikerPagina(3,$strMelding, $objGebruiker);
	 	}
	 	else {
 			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' zijn succesvol aangepast";
 			if($booMailChanged == true) $strMelding .= "<br/><br/>  Er is een e-mail onderweg naar ".$objStatus->getInfo()." om de verandering van het e-mailadres te bevestigen.";
	 		showGebruikerPagina(2,$strMelding, $objGebruiker);
	 	}

 	}
 	elseif(isset($_POST['delGebruikerKnop'])) {
 		$objGebruikerOrg = getGebruiker($objTmpGebruiker);
		$objStatus = new Status();
		$objStatus->setPersoonID( $objGebruikerOrg->getPersoonID() ); 		 		
		if($objGebruikerOrg == false) {
			$strMelding = "De gegevens van de gebruiker kunnen niet worden verwijderd, er is iets misgegaan.";
			showGebruikerPagina(0,$strMelding);
		}
		elseif($_objUser->getPersoonID() == $objGebruikerOrg->getPersoonID()) {
			$strMelding = "Het is niet mogelijk om uw eigen gebruikersaccount te verwijderen.";
			showGebruikerPagina(2,$strMelding,$objGebruikerOrg);
		}
		elseif($_objUser->getUserLevel() < $objGebruikerOrg->getUserLevel()) {
			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' kunnen niet worden verwijderd, omdat de gebruiker een hoger gebruikerslevel heeft als u.";
			showGebruikerPagina(2,$strMelding,$objGebruikerOrg);
		}
	 	elseif(delGebruiker($objGebruikerOrg) == false || delPersoon($objGebruikerOrg) == false || delStatus($objStatus, false, true)) {
			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' kunnen niet worden verwijderd.";
			showGebruikerPagina(4,$strMelding,$objGebruikerOrg);
	 	}
	 	else {
			$strMelding = "De gegevens van de gebruiker '".$objGebruikerOrg->getGebruikersNaam()."' zijn succesvol verwijderd.";
			showGebruikerPagina(0,$strMelding );
	 	}
 	}
 	elseif(isset($_POST['resetPassKnop'])) {
 		$objGebruikerOrg = getGebruiker($objTmpGebruiker);
		if($objGebruikerOrg == false) {
			$strMelding = "Het wachtwoord van de gebruiker kan niet opnieuw worden ingesteld, er is iets misgegaan.";
			showGebruikerPagina(0,$strMelding);
		}
		else {
			$booVerstuur = false;
			$booToon = false;
			if(isset($_POST['verstuur'])) $booVerstuur = true;
			if(isset($_POST['toon']) && $_userLevel >= 3) $booToon = true;
			
			$strWachtwoord = generateRandomString(8);
			$objStatus = new Status();
			$objStatus->setSoort('changed_pass');
			$objStatus->setBeginDatum( getDatumTijd() );
			//$objStatus->setEindDatum(  );
			$objStatus->setPersoonID( $objGebruikerOrg->getPersoonID() );
			$objStatus->setStatus(0);
			$objStatus->setIPadres($_SERVER['REMOTE_ADDR']);
			$objStatus->setUniekeString( generateRandomString(10) );
			$objStatus->setToevoegDatum( getDatumTijd() );
			$objGebruikerOrg->setWachtwoordClear( $strWachtwoord );

			if(addStatus($objStatus) == false) {
				$strMelding = "Het wachtwoord kon niet opnieuw worden ingesteld, er is iets misgegaan.";
				showGebruikerPagina(2, $strMelding, $objGebruikerOrg);
			}
			elseif(editGebruiker($objGebruikerOrg) == false) {
				$strMelding = "Het wachtwoord kon niet opnieuw worden ingesteld, er is iets misgegaan.";
				showGebruikerPagina(2, $strMelding, $objGebruikerOrg);
			}
			elseif($booVerstuur == true && verstuurWachtwoord($objGebruikerOrg) == false ) {
				$strMelding = "Het wachtwoord is succesvol opnieuw ingesteld, maar kon niet naar de gebruiker worden verstuurd. ";
				if($booToon == true) $strMelding .= " <br />Het nieuwe wachtwoord is  ".$strWachtwoord;
				showGebruikerPagina(2, $strMelding, $objGebruikerOrg);
			}
			else {
				$strMelding = "Het wachtwoord is succesvol opnieuw ingesteld ";
				if($booVerstuur == true) $strMelding .= " en succesvol naar de gebruiker toegestuurd. ";
				else $strMelding .= " en niet naar de gebruiker toe gestuurd.";
				if($booToon == true) $strMelding .= " <br />Het nieuwe wachtwoord is  ".$strWachtwoord;
				showGebruikerPagina(2, $strMelding, $objGebruikerOrg);
			}
		}
 		
 	}
 	else showErrorPagina(1);
 }
 else {
 	$intVan = 0;
 	if(isset($_GET['van'])) $intVan = checkData($intVan, 'integer');
 	showGebruikerPagina(0,'',$intVan);
 }



?>