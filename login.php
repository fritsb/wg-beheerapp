<?php
/* Bestandsnaam: login.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 15-09-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt het inloggen regelt
 * Tevens ook voor het wachtwoord vergeten-gedeelte
 *
 */

  if(isset($_SESSION['user']) && $_SESSION['user'] != false) {
	if($_GET['action'] == "uitloggen" && $_userLevel >= 0) {
		$_objUser = false;
		$_userID = 0;
		$_userLevel = 0;
		$_SESSION['user'] == false;
		session_destroy();
		$strMelding = "Succesvol uitgelogd.";
		showLoginPagina(0, $strMelding);
 	}
 	else {
 		$strMelding = "Welkom bij de beheerapplicatie, ".$_objUser->getVolledigeNaam()."!".
 					  "<br/><br/>Via het menu aan de linkerkant kunt u nu naar de verschillende onderdelen gaan.";
 		showLoginPagina(4, $strMelding);		
 	}
  	
  }
  elseif(isset($_GET['action'])) {	
	if($_GET['action'] == "aanvraag") {
		showLoginPagina(2);
 	} 	
 	else {
 		 showLoginPagina();
 	}
 }
 elseif(isset($_POST['aanvraagKnop'])) {
 	$objTmpGebruiker = new Gebruiker();
 	$objTmpGebruiker->setValues( $_POST, true );
 	$arrError = checkLGebruiker($objTmpGebruiker, 'aanvraag');
 	$objGebruiker = false;
 	$objPersoon = false;
 	if($_POST['gebruikersnaam'] != "") 
 		$objGebruiker = getGebruiker($objTmpGebruiker, false, true);
 	if($_POST['email'] != "")
 		$objPersoon = getPersoon($objTmpGebruiker, false, false, false, true);
 		 	
 	if($objPersoon != false || $objGebruiker != false) {
	 	$objStatus = new Status();
	 	if($objGebruiker != false) $objStatus->setPersoonID( $objGebruiker->getPersoonID() );
	 	elseif($objPersoon != false) $objStatus->setPersoonID( $objPersoon->getID() );
	 	$objStatus->setBeginDatum( getDatumTijd() );
	 	$objStatus->setToevoegDatum( getDatumTijd() );
	 	$objStatus->setEindDatum( getToekomstDatumTijd(2, 0, 0, 0, true) );
		$objStatus->setIPadres($_SERVER['REMOTE_ADDR']);
		$objStatus->setUniekeString( generateRandomString(10) );
		$objStatus->setSoort('requested_pass');
 	} 	
 	
 	if($arrError != false) {
		$strMelding = "De aanvraag is mislukt, omdat de gegevens niet juist zijn.";
		showLoginPagina(2, $strMelding, $arrError);
 	}
 	elseif($objGebruiker == false && $objPersoon == false) {
		$strMelding = "De aanvraag is mislukt, omdat de gegevens niet juist zijn.";
		showLoginPagina(2, $strMelding);
 	}
 	elseif($objGebruiker != false && verstuurAanvraag($objGebruiker, $objStatus) == false) {
 		$strMelding = "De aanvraag kon niet worden verstuurd.";
		showLoginPagina(2, $strMelding);
 	}
 	elseif($objGebruiker == false && $objPersoon != false && verstuurAanvraag($objPersoon, $objStatus) == false) {
 		$strMelding = "De aanvraag kon niet worden verstuurd.";
		showLoginPagina(2, $strMelding);
 	}
 	elseif(addStatus($objStatus) == false) {
 		$strMelding = "De aanvraag kon niet worden verstuurd.";
		showLoginPagina(2, $strMelding);
 	}
 	else {
		$strMelding = "De aanvraag is succesvol verstuurd. U heeft een e-mail binnen gekregen om ".
					  " de aanvraag te bevestigen. Dit moet wel voor ".$objStatus->getEindDatumNet().
					  " gebeuren.";
		showLoginPagina(3, $strMelding);
 	}
 }
 elseif(isset($_POST['loginKnop'])) { 
 	$objTmpGebruiker = new Gebruiker();
 	$objTmpGebruiker->setValues( $_POST, true );
 	$objTmpGebruiker->setWachtwoordClear( $_POST['wachtwoord'] );
 	$arrError = checkLGebruiker($objTmpGebruiker);
 	$objGebruiker = getGebruiker($objTmpGebruiker, false, true, true);
 	
 	if($arrError != false) {
 		if(!isset($_SESSION['false_login'])) $_SESSION['false_login'] = 1;
 		else $_SESSION['false_login'] = $_SESSION['false_login'] + 1;

 		$strMelding = "Het inloggen is niet gelukt. ";
 		showLoginPagina(0, $strMelding, $arrError);
 	}
 	elseif($objGebruiker == false) {
 		if(!isset($_SESSION['false_login'])) $_SESSION['false_login'] = 1;
 		else $_SESSION['false_login'] = $_SESSION['false_login'] + 1;
 		
 		$strMelding = "Het inloggen is niet gelukt. De combinatie van gebruikersnaam en wachtwoord is onjuist. ".
 					  "<br/>Let op: De gebruikersnaam en wachtwoord zijn beide hoofdlettergevoelig!";
 		showLoginPagina(0, $strMelding);
 	}
 	else {
		$objTmpPersoon = new Persoon();
		$objTmpPersoon->setID( $objGebruiker->getPersoonID() );
		$objPersoon = getPersoon($objTmpPersoon);
		if($objPersoon != false) {
			if(isset($_SESSION['false_login'])) $_SESSION['false_login'] = 0;	
			$objGebruiker->setIPAdres( $_SERVER['REMOTE_ADDR']);
			$objGebruiker->setLastLogin(getDatumTijd());
			updateLastLogin( $objGebruiker );
			$_objUser = $objGebruiker;
			$_userID = $objGebruiker->getID();
			$_userLevel = $objGebruiker->getUserLevel();
			$_SESSION['user'] = serialize($objGebruiker);
			
			$objTmpStatus = new Status();
			$objTmpStatus->setPersoonID($objGebruiker->getPersoonID() );
			$objTmpStatus->setStatus('0');
			$objTmpStatus->setSoort("changed_pass");
			$objTmpStatus->setDatum( getDatumTijd() );
			$objStatus = getStatus($objTmpStatus, false, true, false, true, true, true); 
			$objTmpStatus->setSoort("new_user");
			$objStatus2 = getStatus($objTmpStatus, false, true, false, true, true, true); 
			if($objStatus != false) {
					$strMelding = "Omdat uw wachtwoord onlangs opnieuw is ingesteld door een beheerder, ".
								  "is het noodzakelijk dat u uw wachtwoord opnieuw instelt. Het is mogelijk om ".
								  "dit ook later te doen. U krijgt dit scherm elke keer na het inloggen, totdat u".
								  " uw wachtwoord heeft veranderd. ";
					showProfielPagina(4, $strMelding, $objGebruiker );					
								
			}
			elseif($objStatus2 != false) {
					$strMelding = "Omdat u van ons via e-mail een wachtwoord heeft ontvangen, ".
								  "is het noodzakelijk dat u uw wachtwoord opnieuw instelt. Het is mogelijk om ".
								  "dit ook later te doen. U krijgt dit scherm elke keer na het inloggen, totdat u".
								  " uw wachtwoord heeft veranderd. ";
					showProfielPagina(5, $strMelding, $objGebruiker );
			}
			else {
				$strMelding = "Welkom bij de beheerapplicatie, ".$_objUser->getVolledigeNaam()."!".
							  "<br/><br/>U bent succesvol ingelogd. Via het menu aan de linkerkant kunt u nu naar de verschillende onderdelen gaan.";
				showLoginPagina(1, $strMelding);
			}
		}
		else {
			$strMelding = "Inloggen is niet mogelijk, omdat de bijbehorende persoonsgegevens niet op te vragen zijn.";
 			showLoginPagina(0, $strMelding);
		}
 	}
 }
 else {
 	 if(isset($_SESSION['max_login']))  {
 	 	showLoginPagina(0, "U bent langer als ".$_arrConfig['max_inlog_uur']." uur ingelogd, daarom".
 							" moet u opnieuw inloggen. ");
 	 }
 	 else showLoginPagina();
 	
 }




?>
