<?php
/* Bestandsnaam: profiel.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Dit bestand regelt de acties mbt het profiel
 *
 */

 if($_userLevel >= 1) {
 	$objTmpGebruiker = $_objUser;
 	$objGebruikerOrg = $_objUser;
 	
 	if(isset($_POST['editProfielKnop'])) {
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
			showErrorPagina(0);
		}
	 	elseif($arrErrors != false) {
	 		$strMelding = "De gegevens kunnen niet worden aangepast.";
	 		showProfielPagina(2,$strMelding, $objGebruiker, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De gegevens kunnen niet worden aangepast.";
	 		showProfielPagina(2,$strMelding, $objGebruiker, $arrDBErrors);
	 	}
	 	elseif(editGebruiker($objGebruiker) == false || editPersoon($objGebruiker) == false) {
 			$strMelding = "De gegevens  kunnen niet worden aangepast, er is iets mis met de database.";
 			showProfielPagina(2,$strMelding, $objGebruiker);
	 	}
	 	elseif($booMailChanged == true && addStatus($objStatus) == false) {
 			$strMelding = "De gegevens zijn wel aangepast, maar het e-mailadres kon niet worden aangepast. ";
 			showProfielPagina(2,$strMelding, $objGebruiker);
	 	}
	 	elseif($booMailChanged == true && verstuurMailBevestiging($objGebruiker, $objStatus) == false) {
 			$strMelding = "De gegevens zijn wel aangepast, maar de ".
 						 " e-mail om de verandering van het e-mailadres te bevestigen kon niet worden verstuurd.";
 			showProfielPagina(2,$strMelding, $objGebruiker);
	 	}
	 	else {
 			$strMelding = "De gegevens zijn succesvol aangepast";
 			if($booMailChanged == true) $strMelding .= "<br/><br/>  Er is een e-mail onderweg naar ".$objStatus->getInfo()." om de verandering van het e-mailadres te bevestigen.";
	 		showProfielPagina(1, $strMelding, $objGebruiker);
	 	}
 	}
 	elseif(isset($_POST['changePassKnop'])) {
 		$objTmpGebruiker->setWachtwoordClear( $_POST['old_pass'], true );
 		$objGebruiker2 = getGebruiker($objTmpGebruiker, true, false, true);

 		if(isset($_POST['soort']) && $_POST['soort'] != "") $booSoort = true;
 		
 		if($booSoort == true) {
	 		$objStatus = new Status();
	 		$objStatus->setSoort($_POST['soort'], true);
	 		$objStatus->setStatus(1);
	 		$objStatus->setPersoonID($objGebruikerOrg->getPersoonID());
	 		if($objStatus->getSoort() == "new_user") $intIndexID = 5;
	 		elseif($objStatus->getSoort() == "changed_pass") $intIndexID = 4;
 		}
 		else $intIndex = 3;

 		
		if($objGebruikerOrg == false) {
			showErrorPagina(0);
		}
		elseif($objGebruiker2 == false) {
			$strMelding = "Het oude wachtwoord is niet juist ingevuld. Probeer het nogmaals.";
			showProfielPagina($intIndexID,$strMelding, $objGebruikerOrg);
		}
		elseif($_POST['new_pass1'] != $_POST['new_pass2']) {
			$strMelding = "De nieuwe wachtwoorden zijn niet hetzelfde. Probeer het nogmaals.";			
			showProfielPagina($intIndexID,$strMelding, $objGebruikerOrg);
		}
		elseif($_POST['new_pass1'] == $_POST['old_pass']) {
			$strMelding = "De oude en nieuwe wachtwoorden zijn hetzelfde. Dit is niet toegestaan, probeer het nogmaals.";			
			showProfielPagina($intIndexID,$strMelding, $objGebruikerOrg);
		}
		else {
			$objGebruikerOrg->setWachtwoordClear( $_POST['new_pass1'] );
			$arrErrors = checkGebruiker($objGebruikerOrg, false, true);
			if($arrErrors != false) {
		 		$strMelding = "Het wachtwoord kan niet worden aangepast.";
		 		showProfielPagina($intIndexID,$strMelding, $objGebruikerOrg, $arrErrors);
			}
			elseif(editGebruiker($objGebruikerOrg) == false) {
				$strMelding = "Het wachtwoord kan niet worden aangepast.";
		 		showProfielPagina($intIndexID,$strMelding, $objGebruikerOrg);
			}
			elseif($booSoort == true && editStatus($objStatus, false, true, true) == false) {
				$strMelding = "Uw wachtwoord is succesvol aangepast.";
		 		showProfielPagina(1 ,$strMelding, $objGebruikerOrg);
			}
			else {
				$strMelding = "Uw wachtwoord is succesvol aangepast!";
		 		showProfielPagina(1,$strMelding, $objGebruikerOrg);								
			}
			
		}
 	}
 	elseif(isset($_GET['action'])) {
 		if($_GET['action'] == "edit") {
 			showProfielPagina(2, '', $objGebruikerOrg);
 			
 		}
 		elseif($_GET['action'] == "pass") {
 			showProfielPagina(3, '', $objGebruikerOrg);
 		} 
 	}
 	else {
 		$objGebruikerOrg = getGebruiker($objTmpGebruiker);
 		showProfielPagina(1, '', $objGebruikerOrg);	
 	}
 		
 	
 	
 }
 else showErrorPagina(0); 










?>
