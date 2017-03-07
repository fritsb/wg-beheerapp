<?php
/* Bestandsnaam: nodebeheer.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 24-10-2005
 * Omschrijving: 
 * Het bestand dat de acties mbt nodes regelt
 *
 */

 
 if(isset($_GET['action']) && isset($_GET['id'])) {
 	$objTmpNode = new Node();
 	$objTmpNode->setID($_GET['id'], true);
 	
 	if($objTmpNode->getID() >= 1) {
	 	$objNode = new Node();
	 	$objNode = getNode($objTmpNode);
	 	
	 	if($_GET['action'] == "edit") {
	 		showNodePagina(3,'',$objNode);
	 	}
	 	elseif($_GET['action'] == "del") {
	 		 showNodePagina(4,'',$objNode);
	 	}
	 	elseif($_GET['action'] == "view") {
	 		showNodePagina(2,'',$objNode);
	 	}
 	}
 	else showErrorPagina(1);
 	
 }
 elseif(isset($_GET['action']) && $_GET['action'] == "add") {
 	showNodePagina(1);
 }
 elseif(isset($_POST['id'])) {
 	$objTmpNode = new Node();
 	$objTmpNode->setID($_POST['id'], true);

	if(isset($_POST['addNodeKnop'])) {
	 	$objNode = new Node();
	 	$objNode->setValues($_POST, true);
	 	$objNode->setToevoegDatum(getDatumTijd());
	 	$arrErrors = checkNode($objNode, true);
	 	$arrDBErrors = checkNodeDB($objNode, true);

	 	if($arrErrors != false) {
	 		$strMelding = "De node kon niet worden toegevoegd.";
	 		showNodePagina(3,$strMelding,$objNode, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De node kon niet worden toegevoegd.";
	 		showNodePagina(3,$strMelding,$objNode, $arrDBErrors);
	 	} 	
	 	elseif(addNode($objNode) == false) {
	 		$strMelding = "De node kon niet worden toegevoegd.";
	 		showNodePagina(3,$strMelding,$objNode);
	 	}
		elseif(createDir($objNode->getIPAdres()) == false) {
			$strMelding = "De HTML folder kon niet worden aangemaakt.";
			showNodePagina(3,$strMelding,$objNode);
		}
		elseif(createConfig($objNode->getIPAdres()) == false) {
		        $strMelding = "Het configuratie bestand kon niet worden aangemaakt.";
			showNodePagina(3,$strMelding,$objNode);
			}
		elseif(addConfig($objNode->getIPAdres()) == false) {
			$strMelding = "Het configuratie bestand kon niet worden toegevoegd.";
			showNodePagina(3,$strMelding,$objNode);
		}
		elseif(createHTML($objNode->getIPAdres()) == false) {
			$strMelding = "Het HTML bestand kon niet worden aangemaakt.";
			showNodePagina(3,$strMelding,$objNode);
		}
		elseif(restartMRTG() == false) {
			$strMelding = "MRTG kon niet herstarten!";
			showNodePagina(3,$strMelding);
		}
	 	else {
			$objNode->setID($objDBConnectie->getLastInsertedID());
			$strMelding = "De node is succesvol toegevoegd!";
			showNodePagina(2,$strMelding,$objNode);
	 	}
	}
 	elseif(isset($_POST['editNodeKnop'])) {
 		$objNodeOrg = getNode($objTmpNode);
 		$objNode = $objNodeOrg;
 		$objNode->setValues($_POST, true);
	 	$arrErrors = checkNode($objNode);
		$arrDBErrors = checkNodeDB($objNode);
	 	
		if($objNodeOrg == false) {
			$strMelding = "De gegevens van de node kunnen niet worden aangepast, er is iets misgegaan.";
			showNodePagina(0,$strMelding);
		}
	 	elseif($arrErrors != false) {
	 		$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' kunnen niet worden aangepast.";
	 		showNodePagina(3,$strMelding, $objNode, $arrErrors);
	 	}
	 	elseif($arrDBErrors != false) {
	 		$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' kunnen niet worden aangepast.";
	 		showNodePagina(3,$strMelding, $objNode, $arrDBErrors);
	 	} 	
	 	elseif(editNode($objNode) == false) {
 			$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' kunnen worden aangepast, er is iets mis met de database.";
 			showNodePagina(3,$strMelding, $objNode);
	 	}
	 	else {
 			$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' zijn succesvol aangepast";
	 		showNodePagina(2,$strMelding, $objNode);
	 	}

 	}
 	elseif(isset($_POST['delNodeKnop'])) {
 		$objNodeOrg = getNode($objTmpNode);
 		 		
		if($objNodeOrg == false) {
			$strMelding = "De gegevens van de node kunnen niet worden verwijderd, er is iets misgegaan.";
			showNodePagina(0,$strMelding);
		}
	 	elseif(delNode($objNodeOrg) == false) {
			$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' kunnen niet worden verwijderd.";
			showNodePagina(4,$strMelding,$objNodeOrg);
	 	}
		elseif(removeConfig($objNodeOrg->getIPadres()) == false) {
			$strMelding = "Het configuratie bestand van de node '".$objNodeOrg->getNaam()."' kon niet worden verwijderd";
			showNodePagina(4,$strMelding,$objNode);
		}
		elseif(restartMRTG() == false) {
			$strMelding = "MRTG kon niet herstarten!";
			showNodePagina(4,$strMelding);
		}
	 	else {
			$strMelding = "De gegevens van de node '".$objNodeOrg->getNaam()."' zijn succesvol verwijderd.";
			showNodePagina(0,$strMelding );
	 	}
 	} 	
 	else showErrorPagina(1);
 }
 else {
 	$intVan = 0;
 	if(isset($_GET['van'])) $intVan = checkData($intVan, 'integer');
 	showNodePagina(0,'',$intVan);
 }


?>
