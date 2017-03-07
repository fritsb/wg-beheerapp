<?php
/* Bestandsnaam: statusbeheer.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 02-11-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt statussen regelt
 *
 */

 if(isset($_GET['id'])) {
	$objTmpStatus = new Status();
	$objTmpStatus->setPersoonID($_GET['id'], true);
	
	if(isset($_GET['str'])) {
		$objTmpStatus->setUniekeString($_GET['str'], true);
		$objStatus = getStatus($objTmpStatus);
		if($objStatus == false) {
			$strTitel = "Bevestiging of verificatie mislukt";
			$strMelding = "Helaas zijn de opgegeven gegevens niet juist. ";	
			showStatusPagina(2, $strMelding, $strTitel );
		}
		elseif($objStatus->getStatus() == "1") {
			$strTitel = "Bevestiging of verificatie mislukt";
			$strMelding = "De bevestiging of verificatie is al voltooid, dus het kan niet nogmaals gedaan worden. ";	
			showStatusPagina(2, $strMelding, $strTitel );
		}
		elseif($objStatus->getSoort(true) == "new_user" && $objStatus->getStatus() == "-1") {
			$objStatus->setStatus(0);
			$objTmpGebruiker = new Gebruiker();
			$objTmpGebruiker->setID( $objStatus->getPersoonID() );
			$objGebruiker = getGebruiker( $objTmpGebruiker );
			$objGebruiker->setStatus( 1 );
			if($objGebruiker->getUserLevel() == "0" || $objGebruiker->getUserLevel() == null)
				$objGebruiker->setUserLevel(1);
			if($objStatus->getEindDatum() < getDatumTijd() || $objStatus->getBeginDatum() > getDatumTijd() ) {
				$strTitel = "Bevestiging mislukt";
				$strMelding = "Het was niet mogelijk om de bevestiging te verwerken. ".
						      "De registratie moet binnen 7 dagen worden worden bevestigd.";
				showStatusPagina(2, $strMelding, $strTitel);		
			}
			elseif(editGebruiker($objGebruiker) == false) {
				$strMelding = "Het was niet mogelijk om de bevestiging te verwerken. Probeer het nogmaals.";
				$strTitel = "Bevestiging mislukt";
				showStatusPagina(2, $strMelding, $strTitel);
			}
			elseif(editStatus($objStatus, false, true, true) == false) {
				$strMelding = "Het was niet mogelijk om de bevestiging te verwerken. Probeer het nogmaals. ";
				$strTitel = "Bevestiging mislukt";
				showStatusPagina(2, $strMelding, $strTitel);
			}
			else {
				$strMelding = "De registratie is bij deze bevestigd. Hieronder kunt u inloggen.";
				showStatusPagina(1, $strMelding);
			}
		}

		elseif($objStatus->getSoort(true) == "changed_mail") {
			$objStatus->setStatus(1);
			$objTmpGebruiker = new Gebruiker();
			$objTmpGebruiker->setPersoonID( $objStatus->getPersoonID() );
			$objGebruiker = getGebruiker( $objTmpGebruiker );
			$objGebruiker->setEmail( $objStatus->getInfo() );
			
			if($objStatus->getEindDatum() < getDatumTijd() || $objStatus->getBeginDatum() > getDatumTijd() ) {
				$strTitel = "Verificatie mislukt";
				$strMelding = "Het was niet mogelijk om de verificatie van het e-mailadres te verwerken. ".
						      "De verandering moest binnen 7 dagen worden worden bevestigd.";
				showStatusPagina(4, $strMelding, $strTitel);		
			}
			elseif(editPersoon($objGebruiker) == false) {
				$strMelding = "Het was niet mogelijk om  de verificatie van het e-mailadres te verwerken. ".
							  " Probeer het nogmaals.";
				$strTitel = "Verificatie mislukt";
				showStatusPagina(4, $strMelding, $strTitel);
			}
			elseif(editStatus($objStatus, false, true, true) == false) {
				$strMelding = "Het was niet mogelijk de verificatie van het e-mailadres te verwerken. ".
							  "Probeer het nogmaals. ";
				$strTitel = "Verificatie mislukt";
				showStatusPagina(4, $strMelding, $strTitel);
			}
			else {
				$strTitel = "Verificatie succesvol gelukt";
				$strMelding = "De verificatie van het e-mailadres is bij deze bevestigd. ".
							   "Het e-mailadres is nu opgeslagen.  ";
				showStatusPagina(3, $strMelding, $strTitel);
			}
		}
		elseif($objStatus->getSoort(true) == "requested_pass") {
			$objStatus->setStatus(0);
			$objTmpGebruiker = new Gebruiker();
			$objTmpGebruiker->setPersoonID( $objStatus->getPersoonID() );
			$objGebruiker = getGebruiker( $objTmpGebruiker );
			$objGebruiker->setWachtwoordClear( generateRandomString(8) );
			
			if($objStatus->getEindDatum() < getDatumTijd() || $objStatus->getBeginDatum() > getDatumTijd() ) {
				$strTitel = "Aanvraag mislukt";
				$strMelding = "Het was niet mogelijk om de aanvraag van het wachtwoord te verwerken. ".
						      "De aanvraag moet binnen 2 dagen worden worden bevestigd.";
				showStatusPagina(4, $strMelding, $strTitel);		
			}
			elseif(editGebruiker($objGebruiker) == false) {
				$strMelding = "Het was niet mogelijk om de aanvraag van het wachtwoord te verwerken. ".
							  " Probeer het nogmaals.";
				$strTitel = "Aanvraag mislukt";
				showStatusPagina(4, $strMelding, $strTitel);
			}
			elseif(editStatus($objStatus, false, true, true) == false) {
				$strMelding = "Het was niet mogelijk om de aanvraag van het wachtwoord te verwerken. ".
							  "Probeer het nogmaals. ";
				$strTitel = "Aanvraag mislukt";
				showStatusPagina(4, $strMelding, $strTitel);
			}
			else {
				$strTitel = "Aanvraag van het wachtwoord is gelukt";
				$strMelding = "De aanvraag van het wachtwoord is goed gelukt. Uw gegevens staan hieronder vermeld.".
							  "\n<br/></br>\n".
							  "Uw gebruikersnaam is: ".$objGebruiker->getGebruikersNaam().
							  "<br/>\nUw wachtwoord is nu veranderd in: ".$objGebruiker->getWachtwoordClear().
							  
							  "\n<br/><br/>Log nu hieronder in met deze gegevens.\n";
				showStatusPagina(1, $strMelding );
			}
		}
	}
 	
 }
 else showErrorPagina(2);



?>