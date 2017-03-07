<?php
/* Bestandsnaam: banbeheer.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 31-10-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt bans regelt
 *
 */

 
 if(isset($_GET['action']) && isset($_GET['id'])) {
 	$objTmpBan = new Ban();
 	$objTmpBan->setID($_GET['id'], true);
 	
 	if($objTmpBan->getID() >= 1) {
	 	$objBan = new Ban();
	 	$objBan = getBan($objTmpBan);
	 	
	 	if($_GET['action'] == "edit") {
	 		showBanPagina(3,'',$objBan);
	 	}
	 	elseif($_GET['action'] == "del") {
	 		 showBanPagina(4,'',$objBan);
	 	}
	 	elseif($_GET['action'] == "view") {
	 		showBanPagina(2,'',$objBan);
	 	}
	 	else showErrorPagina(1);
 	}
 	else showErrorPagina(1);
 	
 }
 elseif(isset($_GET['action']) && $_GET['action'] == "add") {
 	showBanPagina(1);
 }
 elseif(isset($_POST['id'])) {
 	$objTmpBan = new Ban();
 	$objTmpBan->setID($_POST['id'], true);

	if(isset($_POST['addBanKnop'])) {
	 	$objBan = new Ban();
	 	$objBan->setValues($_POST, true);
	 	$objBan->setToevoegDatum(getDatumTijd());
	 	$arrErrors = checkBan($objBan, true);
	 	//$arrDBErrors = checkBanDB($objBan, true);

	 	if($arrErrors != false) {
	 		$strMelding = "De ban kon niet worden toegevoegd.";
	 		showBanPagina(3,$strMelding,$objBan, $arrErrors);
	 	}
//	 	elseif($arrDBErrors != false) {
	 		//$strMelding = "De ban kon niet worden toegevoegd.";
//	 		showBanPagina(3,$strMelding,$objBan, $arrDBErrors);
	 	//} 	
	 	elseif(addBan($objBan) == false) {
	 		$strMelding = "De ban kon niet worden toegevoegd.";
	 		showBanPagina(3,$strMelding,$objBan);
	 	}
	 	else {
			$objBan->setID($objDBConnectie->getLastInsertedID());
			$strMelding = "De ban is succesvol toegevoegd!";
			showBanPagina(2,$strMelding,$objBan);
	 	}
	}
 	elseif(isset($_POST['editBanKnop'])) {
 		$objBanOrg = getBan($objTmpBan);
 		$objBan = $objBanOrg;
 		$objBan->setValues($_POST, true);
	 	$arrErrors = checkBan($objBan);
		//$arrDBErrors = checkBanDB($objBan);
		if($objBanOrg == false) {
			$strMelding = "De gegevens van de ban kunnen niet worden aangepast, er is iets misgegaan.";
			showBanPagina(0,$strMelding);
		}
	 	elseif($arrErrors != false) {
	 		$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' kunnen niet worden aangepast.";
	 		showBanPagina(3,$strMelding, $objBan, $arrErrors);
	 	}
//	 	elseif($arrDBErrors != false) {
	 		//$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' kunnen niet worden aangepast.";
//	 		showBanPagina(3,$strMelding, $objBan, $arrErrors);
	 	//}
	 	elseif(editBan($objBan) == false) {
 			$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' kunnen worden aangepast, er is iets mis met de database.";
 			showBanPagina(3,$strMelding, $objBan);
	 	}
	 	else {
 			$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' zijn succesvol aangepast";
	 		showBanPagina(2,$strMelding, $objBan);
	 	}

 	}
 	elseif(isset($_POST['delBanKnop'])) {
 		$objBanOrg = getBan($objTmpBan);
 		 		
		if($objBanOrg == false) {
			$strMelding = "De gegevens van de ban kunnen niet worden verwijderd, er is iets misgegaan.";
			showBanPagina(0,$strMelding);
		}
	 	elseif(delBan($objBanOrg) == false) {
			$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' kunnen niet worden verwijderd.";
			showBanPagina(4,$strMelding,$objBanOrg);
	 	}
	 	else {
			$strMelding = "De gegevens van de ban met ID '".$objBanOrg->getID()."' zijn succesvol verwijderd.";
			showBanPagina(0,$strMelding );
	 	}
 	} 	
 	else showErrorPagina(1);
 }
 else {
 	$intVan = 0;
 	if(isset($_GET['van'])) $intVan = checkData($intVan, 'integer');
 	showBanPagina(0,'',$intVan);
 }



?>
