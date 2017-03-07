<?php
/* Bestandsnaam: header.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Dit bestand regelt de opening van de website, de standaard variabelen enz.
 *
 */
session_start();

// Externe bestanden die altijd nodig zijn
include_once("algemenefuncties.php");
include_once("layoutfuncties.php");
include_once("formfuncties.php");
include_once("modulefuncties.php");
include_once("persoonfuncties.php");

$objDBConnectie = new DatabaseConnectie();
if($objDBConnectie->openConnectie($_arrConfig['db_host'], $_arrConfig['db_user'], 
								$_arrConfig['db_pass'], $_arrConfig['db_table'] ) == false) {
	showErrorPagina(1); 
	exit;
}
// Overige functiebestanden
includeFunctiesBestanden();
// Wat settings
$_arrStatussen['header'] = false;

$objTmpModule = new Module();

$objTmpBan = new Ban();
$objTmpBan->setIPadres( $_SERVER['REMOTE_ADDR']);
$objTmpBan->setDatum( getDatumTijd() );
$objBan = getBan($objTmpBan, false, false, true, true);

if($objBan != false ) {
	$_SESSION['ban'] = true;
	showErrorPagina(3);
	exit;
}
elseif(isset($_SESSION['ban']) && $_SESSION['ban'] == true) {
	showErrorPagina(3);
	exit;		
}


if(isset($_SESSION['user'])) {
	$_objUser = unserialize($_SESSION['user']);
	$_userLevel = $_objUser->getUserLevel();	
	$_userID = $_objUser->getID();
	$_userNaam = $_objUser->getGebruikersNaam();
	
	if((strtotime(getDatumTijd()) - strtotime($_objUser->getLastLogin())) > ($_arrConfig['max_inlog_uur'] * 60 * 60)) {
		$_objUser = false;
		$_userLevel = 0;
		$_userID = 0;
		$objTmpModule->setModuleNaam("login");
		unset($_SESSION['user']);
		$_SESSION['max_login'] = true;
	}
}
else {
	$_objUser = false;
	$_userLevel = 0;
	$_userID = 0;
	$objTmpModule->setModuleNaam("login");
}
// Ook check voor als user verbannen is

if(isset($_SESSION['melding'])) {
	$strMelding = $_SESSION['melding'];	
	
}



 // Als er een module is meegegeven met de URL
 if(isset($_GET['module'])) $objTmpModule->setModuleNaam($_GET['module'], true); 
 // Als er een module is meegegeven met een formulier
 elseif(isset($_POST['module'])) $objTmpModule->setModuleNaam($_POST['module'], true); 
 // Als een module nog in een sessie staat
 elseif(isset($_SESSION['module']))  $objTmpModule->setModuleNaam($_SESSION['module'], true);	
 else $objTmpModule->setModuleNaam("login");

 // Als de modulenaam al bekend is:
 if($objTmpModule->getModuleNaam() != "") {
 	$objTmpModule->setMinUserLevel($_userLevel);
 	$objTmpModule->setActief('ja');
 	$objModule = getModule($objTmpModule, false, true, true, true);
 	if($objModule != false) { 
 		if(is_file($_arrConfig['www_dir'].$_arrConfig['work_dir'].$objModule->getFunctiesBestand()) && 
 				is_file($_arrConfig['www_dir'].$_arrConfig['work_dir'].$objModule->getActieBestand())) {
 			include_once($objModule->getActieBestand());
 		}
 		else showErrorPagina(0);
	}
 	else showErrorPagina(0);
 }
 else showErrorPagina(0);















?>
