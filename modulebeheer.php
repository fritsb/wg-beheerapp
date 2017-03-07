<?php
/* Bestandsnaam: modulebeheer.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt modules regelt
 *
 */

 
 if(isset($_GET['action']) && isset($_GET['id'])) {
 	$objTmpModule = new Module();
 	$objTmpModule->setID($_GET['id'], true);
 	
 	if($objTmpModule->getID() >= 1) {
	 	$objModule = new Module();
	 	$objModule = getModule($objTmpModule);
	 	
	 	if($_GET['action'] == "edit") {
	 		showModulePagina(3,'',$objModule);
	 	}
	 	elseif($_GET['action'] == "del") {
	 		 showModulePagina(4,'',$objModule);
	 	}
	 	elseif($_GET['action'] == "view") {
	 		showModulePagina(2,'',$objModule);
	 	}
	 	else showErrorPagina(1);
 	}
 	else showErrorPagina(1);	
 }
 elseif(isset($_GET['action']) && $_GET['action'] == "add") {
 	showModulePagina(1);
 }
 elseif(isset($_POST['id'])) {
 	$objTmpModule = new Module();
 	$objTmpModule->setID($_POST['id'], true);

	if(isset($_POST['addModuleKnop'])) {
	 	$objModule = new Module();
	 	$objModule->setValues($_POST, true);
	 	$objModule->setToevoegDatum(getDatumTijd());
	 	$arrErrors = checkModule($objModule, true);
	 	$arrDBErrors = checkModuleDB($objModule, true);

	 	if($arrErrors != false) {
	 		$strMelding = "De module kon niet worden toegevoegd.";
	 		showModulePagina(3,$strMelding,$objModule, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De module kon niet worden toegevoegd.";
	 		showModulePagina(3,$strMelding,$objModule, $arrDBErrors);
	 	} 	
	 	elseif(addModule($objModule) == false) {
	 		$strMelding = "De module kon niet worden toegevoegd.";
	 		showModulePagina(3,$strMelding,$objModule);
	 	}
	 	else {
			$objModule->setID($objDBConnectie->getLastInsertedID());
			$strMelding = "De module is succesvol toegevoegd!";
			showModulePagina(2,$strMelding,$objModule);
	 	}
	}
 	elseif(isset($_POST['editModuleKnop'])) {
 		$objModuleOrg = getModule($objTmpModule);
 		$objModule = $objModuleOrg;
 		$objModule->setValues($_POST, true);
	 	$arrErrors = checkModule($objModule);
		$arrDBErrors = checkModuleDB($objModule);
	 	
		if($objModuleOrg == false) {
			$strMelding = "De gegevens van de module kunnen niet worden aangepast, er is iets misgegaan.";
			showModulePagina(0,$strMelding);
		}
	 	elseif($arrErrors != false) {
	 		$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' kunnen niet worden aangepast.";
	 		showModulePagina(3,$strMelding, $objModule, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' kunnen niet worden aangepast.";
	 		showModulePagina(3,$strMelding, $objModule, $arrDBErrors);
	 	} 	
	 	elseif(editModule($objModule) == false) {
 			$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' kunnen worden aangepast, er is iets mis met de database.";
 			showModulePagina(3,$strMelding, $objModule);
	 	}
	 	else {
 			$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' zijn succesvol aangepast";
	 		showModulePagina(2,$strMelding, $objModule);
	 	}

 	}
 	elseif(isset($_POST['delModuleKnop'])) {
 		$objModuleOrg = getModule($objTmpModule);
 		 		
		if($objModuleOrg == false) {
			$strMelding = "De gegevens van de module kunnen niet worden verwijderd, er is iets misgegaan.";
			showModulePagina(0,$strMelding);
		}
	 	elseif(delModule($objModuleOrg) == false) {
			$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' kunnen niet worden verwijderd.";
			showModulePagina(4,$strMelding,$objModuleOrg);
	 	}
	 	else {
			$strMelding = "De gegevens van de module '".$objModuleOrg->getModuleNaam()."' zijn succesvol verwijderd.";
			showModulePagina(0,$strMelding );
	 	}
 	} 	
 	else {
 		showErrorPagina(1);	 
 	}
 }
 else {
 	$intVan = 0;
 	if(isset($_GET['van'])) $intVan = checkData($intVan, 'integer');
 	showModulePagina(0,'',$intVan);
 }



?>