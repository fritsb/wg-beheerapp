<?php
/* Bestandsnaam: nodefuncties.php
 * Ontwikkelaar: Jeffrey Lensen
 * Project: Wireless Grootebroek
 * 
 * Datum: 22-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt nodes staan
 *
 */

// Functie om de node-gegevens toe te voegen
function addNode($objNode) {
	global $objDBConnectie;
	$sql = "INSERT INTO node (naam, ipadres, macadres, toevoegdatum) VALUES ".
		   "  ('".$objNode->getNaam()."', '".$objNode->getIPAdres()."', '".
		   $objNode->getMACAdres()."', '".$objNode->getToevoegDatum()."' ) ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de node-gegevens te bewerken
function editNode($objNode) {
	global $objDBConnectie;
	$sql = "UPDATE node SET naam = '".$objNode->getNaam()."', ".
	 	   " ipadres = '".$objNode->getIPAdres()."', ".
	 	   " macadres = '".$objNode->getMACAdres()."' WHERE ".
	 	   " id = '".$objNode->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}

// Functie om de node-gegevens te verwijderen
function delNode($objNode) {
	global $objDBConnectie;
	$objTmpNode = getNode($objNode);
	if($objTmpNode != false) {
		$sql = "DELETE FROM node WHERE id = '".$objTmpNode->getID()."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}
// Functie om de node-gegevens op te vragen

function getNode($objNode, $booID = true, $booNaam = false, $booIPAdres = false, $booMACAdres = false) {
	if($objNode == false) return false;
	else {
		global $objDBConnectie;
		$sql = "SELECT * FROM node WHERE id = id";
		if($objNode->getID() != "" && $booID == true) 
			$sql .= " AND id = '".$objNode->getID()."'";
		if($objNode->getNaam() != "" && $booNaam == true)
			$sql .= " AND naam = '".$objNode->getNaam()."'";
		if($objNode->getIPAdres() != "" && $booIPAdres == true)
			$sql .= " AND ipadres >= '".$objNode->getIPAdres()."'";
		if($objNode->getMACAdres() != "" && $booMACAdres == true)
			$sql .= " AND macadres = '".$objNode->getMACAdres()."'";
		$sql .= " LIMIT 1";
		
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Node');
	}
}

// Functie om de node-gegevens op te vragen
function getNodes($intVan = 0, $intLimiet = 30, $objNode = false) {
	global $objDBConnectie;
	$sql = "SELECT * FROM node WHERE id = id";
	if($objNode != false && $objNodule->getID() != "")
		$sql .= " AND id = '".$objNode->getID()."'";
	if($objNode != false && $objNode->getNaam() != "")
		$sql .= " AND naam = '".$objNode->getNaam()."'";
	if($objNode != false && $objNode->getIPAdres() != "")
		$sql .= " AND ipadres <= '".$objNode->getIPAdres()."'";
	if($objNode != false && $objNode->getMACAdres() != "")
		$sql .= " AND macadres = '".$objNode->getMACAdres()."'";
	$sql .= " ORDER BY id ASC";
	if($intLimiet != 0)
		$sql .= " LIMIT ".$intVan.", ".$intLimiet." ";
	
	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Node');
}

function checkNode( $objNode, $booNew = false) {
	if($objNode == false) $arrError[-1] = true;
	else {
		//ID-nummer
		if($objNode->getID() == "" && $booNew == false) $arrError[0] = true;
		elseif(strlen($objNode->getID()) > 255 && $booNew == false) $arrError[1] = true; // Mag niet groter zijn dan 255 tekens
		elseif(eregi("[^0-9]{1,}", $objNode->getID())  && $booNew == false) $arrError[2] = true; // Alleen 0-9 zijn toegestaan
		// Nodenaam
		if($objNode->getNaam() == "") $arrError[10] = true;
		elseif(strlen($objNode->getNaam()) > 100) $arrError[11] = true; // Mag niet groter zijn dan 100 tekens
		elseif(eregi("[^a-zA-Z0-9]{1,}", $objNode->getNaam())) $arrError[12] = true; // Alleen chars a-z, A-Z, 0-9 zijn toegestaan
		// IPAdres
		if($objNode->getIPAdres() == "") $arrError[20] = true;
		elseif(strlen($objNode->getIPAdres()) > 16) $arrError[21] = true; // Mag niet groter zijn dan 16 tekens
		elseif(checkIP($objNode->getIPAdres()) == false) $arrError[22] = true;
		// MACAdres
		if($objNode->getMACAdres() == "") $arrError[30] = true;
		elseif(strlen($objNode->getMACAdres()) > 18) $arrError[31] = true; // Mag niet groter zijn dan 18 tekens
		//elseif(eregi("^[a-zA-Z0-9]{1,2}+:[a-zA-Z0-9]{1,2}+:[a-zA-Z0-9]{1,2}+:[a-zA-Z0-9]{1,2}+:[a-zA-Z0-9]{1,2}+:[a-zA-Z0-9]{1,2}", $objNode->getMACAdres())) $arrError[32] = true; // Alleen een geldig MACAdres is toegestaan
		
	}
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om node te checken, of de gegevens al bestaan in de db. False als alles OK is
function checkNodeDB($objNode, $booNew = false) {
	global $objDBConnectie;
	if($objNode == false) $arrError[-1] = true;
	else {
		$sql = "SELECT * FROM node WHERE id = id ".
			" AND naam = '".$objNode->getNaam()."'";
		if($booNew != true)
			$sql .= " AND id != '".$objNode->getID()."'   ";
		
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else {
			$intArraySize = count($arrMysqlResult);
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpNode = new Node();
				$objTmpNode->setValues($arrMysqlResult[$i]);
				if($objTmpNode->getNaam() == $objNode->getNaam())
					$arrError[40] = true; // Er is al een node met dezelfde naam
			}
		}
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om nodepagina te laten zien
function showNodePagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
        showHeader();
	
	switch ($intID) {
	case 1: // Nieuwe node toevoegen
		showNodeForm(false, $strMelding, $extraObj2);
		break;
	case 2: // Node bekijken
		showNode($extraObj, $strMelding, $extraObj2);
		break;
	case 3: // Node bewerken, of als er iets mis is gegaan bij toevoegen
		showNodeForm($extraObj, $strMelding, $extraObj2);
		break;
	case 4: // Node verwijderen
		showDelNodeForm($extraObj, $strMelding, $extraObj2);
		break;
	default: // Standaard, loginformulier
		showNodesOverzicht(0, 30, $extraObj, $strMelding);
	}
	showFooter();
}

// Functie om de informatie van een node op het scherm te tonen
function showNode( $objNode, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
/*			if($arrErrors != false)	 { // array doorlopen	
				$strMeldingHTML .= "<ul>\n";
				
				$strMeldingHTML .= "</ul>\n";
			}
*/
		$strMeldingHTML .= "</div>\n";
	}
	global $_arrConfig;
	$strTabelStijl = "info";
	$strLinkIP = $objNode->getIPAdres();
	$strLink = getLink("/mrtg/$strLinkIP.index.html", "Klik hier", '', "Status informatie voor node $strLinkIP", "target (_blank?)" );
	if($objNode != false) {
		echo openContentVak( "Node-informatie van '".$objNode->getNaam()."'", 'node', 'Node', $objNode->getID() );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Node: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."IP Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getIPAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."MAC Adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getMACAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Statuspagina: ".sluitCel().
		     openCel($strTabelStijl, 2).$strLink .sluitCel().sluitRij().
		     sluitTabel().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('node', "nodes").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
  
	}
	else showErrorPagina(4);
}
// Functie om het formulier op te vragen om een node te bewerken
function showNodeForm( $objNode = false, $strMelding = '', $arrErrors = false  ) {
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De node is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de node is onjuist</li>\n";
				if(isset($arrErrors[10]) || isset($arrErrors[11]) || isset($arrErrors[12])) $strMeldingHTML .= "<li>De nodenaam is onjuist of niet ingevuld, de nodenaam mag geen aparte tekens bevatten.</li>\n";
				if(isset($arrErrors[20]) || isset($arrErrors[21]) || isset($arrErrors[22])) $strMeldingHTML .= "<li>Het IP adres is onjuist of niet ingevuld</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>Het MAC adres is onjuist of niet ingevuld</li>\n";
				if(isset($arrErrors[70])) $strMeldingHTML .= "<li>Er is al een node met de nodenaam '".$objNode->getNaam()."' aanwezig</li>\n";
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}
	if($objNode != false && $objNode->getID() != "") {
		$objNodeOrg = getNode($objNode);
		$strFormKnopNaam = "editNodeKnop";
		$strFormKnopWaarde = "Bewerk node";
		echo openContentVak( "Node '".$objNodeOrg->getNaam()."' bewerken", 'node', 'Node', $objNode->getID(), 'edit' );
	}
	else {
		if($objNode == false)
			$objNode = new Node();
		$strFormKnopNaam = "addNodeKnop";
		$strFormKnopWaarde = "Voeg node toe";
		echo openContentVak( "Nieuwe node toevoegen");
	}
	;
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo openForm('editNode', 'node').
	     showInputTekst('id',$objNode->getID(), '', '', 'hidden').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Node: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputTekst('naam', $objNode->getNaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."IP adres: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputTekst('ipadres', $objNode->getIPAdres(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."MAC adres: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputTekst('macadres', $objNode->getMACAdres(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop($strFormKnopNaam, $strFormKnopWaarde,'buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().
	     openTabel($strTabelStijl, false).
	     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('node', "nodes").sluitCel().
	     sluitRij().sluitTabel().
	     sluitContentVak();
}

// Functie om het formulier op te vragen om een node te verwijderen
function showDelNodeForm( $objNode, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
			if($arrErrors != false)	 { // array doorlopen	
				$strMeldingHTML .= "<ul>\n";
				
				$strMeldingHTML .= "</ul>\n";				
			}
				
		$strMeldingHTML .= "</div>\n";
	}
	
	$strTabelStijl = "info";
	if($objNode != false) {
		echo openContentVak( "Node '".$objNode->getNaam()."' verwijderen", 'node', 'Node', $objNode->getID(), 'del' );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openForm('delNode', 'node').
		     showInputTekst('id',$objNode->getID(), '', '', 'hidden').
		     openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Node: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."IP adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getIPAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."MAC adres: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getMACAdres(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objNode->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputKnop('delNodeKnop', 'Verwijder node','buttonStijl1').
		     sluitCel().sluitRij().sluitTabel().
		     sluitForm().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('node', "nodes").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
	}
	else showErrorPagina(4);
}

// Functie om het overzicht van nodes te laten zien
function showNodesOverzicht( $intVan = 0, $intLimiet = 0, $objModule = false, $strMelding = '' ) {
	if($intVan < 0) $intVan = 0;
	$arrNodes = getNodes($intVan, $intLimiet, $objNode);
	$intArraySize = count($arrNodes);

	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}	
	$strTabelStijl = "overzicht";
	echo openContentVak( "Nodesoverzicht").
		 "Hieronder staat het overzicht van de nodes in het systeem. ";
		 if($intArraySize != 0 && $arrNodes != false)
		 	echo "Nodes ".($intVan+1)." tot en met ".($intVan + $intArraySize)." worden getoond.\n";
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;		 
	
	if($arrNodes != false && $intArraySize != 0) {
		echo openTabel($strTabelStijl, false).
			 openRij($strTabelStijl).openDiv('',$strTabelStijl."TabelVeldTitel1")."Nodenaam:".sluitDiv().
			 openDiv('',$strTabelStijl."TabelVeldTitel2")."Acties:".sluitDiv().sluitRij();
		$intVeldID = 1;
		for($i = 0; $i < $intArraySize; $i++) {
			$objNode = $arrNodes[$i];
			if($objNode != false) {
				echo openRij($strTabelStijl).openCel($strTabelStijl, $intVeldID).
					 getActieLink( $objNode->getID(), 'node', 'view', false, $objNode->getNaam(), 'Bekijk node').
					 sluitCel().
					 getActieMenu($objNode->getID(),  'node', true, true, true, false, 'overzicht',$intVeldID).
					 sluitRij();		
				if($intVeldID == 1) $intVeldID = 2;
				elseif($intVeldID == 2) $intVeldID = 1;
			}
		}
		echo sluitTabel();
	}
	else {
		echo "<br/><br/>Er zijn nog geen nodes in de database.";
	}
	
	echo openTabel('info', false).
	     openRij('info').openCel('info',3).
	     getLink('index.php?&action=add', "Voeg een node toe", 'node').
	     sluitCel().sluitRij().sluitTabel().
		 sluitContentVak();
}

// Functies voor MRTG

// Functie om een folder aan te maken
function createDir($strNodeIP) {
	global $_arrConfig;
	$strDir = $_arrConfig['mrtg_html_dir']."/node_".$strNodeIP;
	$objOldmask = umask(o);
	if (!is_dir($strDir)) {
		mkdir($strDir, 0777);
		umask($objOldmask);
		return true;
	}
	else return false;
}

// Functie om een cfg file te maken
function createConfig($strNodeIP) {
	global $_arrConfig;
	$strNewFile = $_arrConfig['mrtg_cfg_dir']."/mrtg_".$strNodeIP.".cfg";
	
	$filehandler = file_get_contents( $_arrConfig['mrtg_cfg_template'] );
	$strReplace = 'IPadres';
	while(eregi($strReplace, $filehandler)) {
		$filehandler = eregi_replace($strReplace, $strNodeIP, $filehandler);
	}
	
	if (!is_file($strNewFile)) {
		$handle = fopen($strNewFile, "x+");
		fwrite($handle, $filehandler);
		fclose($handle);
		return true;
	}
	else return false;
}

// Functie om cfg aan de main cfg toe te voegen
function addConfig($strNodeIP) {
	global $_arrConfig;
	$strNewFile = $_arrConfig['mrtg_cfg_dir']."mrtg_".$strNodeIP.".cfg";
	
	if (is_file($_arrConfig['mrtg_cfg'])) {
		$cfghandle = fopen($_arrConfig['mrtg_cfg'], "a+");
		if(!eregi($strNewFile, file_get_contents($_arrConfig['mrtg_cfg']))) {
			fwrite($cfghandle, "Include: $strNewFile\n");
			fclose($cfghandle);
			return true;
		}
		else return false;
	}
	else return false;
}

// Functie om de cfg uit de main cfg te verwijderen
function removeConfig($strNodeIP) {
	global $_arrConfig;
	if (is_file($_arrConfig['mrtg_cfg'])) {
		$key = "Include: ".$_arrConfig['mrtg_cfg_dir']."mrtg_".$strNodeIP.".cfg\n";	
		$fc=file($_arrConfig['mrtg_cfg']);
		$f=fopen($_arrConfig['mrtg_cfg'],"w");
		foreach($fc as $line){
			if (!strstr($line,$key))
				fputs($f,$line);
		}
		fclose($f);
	}
	else return false;
}

// Functie om de HTML file aan te maken
function createHTML($strNodeIP) {
	global $_arrConfig;
	$strNewFile = $_arrConfig['mrtg_cfg_dir']."mrtg_".$strNodeIP.".cfg";
	$strIndexFile = $_arrConfig['mrtg_html_dir'].$strNodeIP.".index.html";
	
	if (!is_file($strIndexFile)) {
		shell_exec("indexmaker --column=1 --title='System statistics for $strNodeIP' --output=$strIndexFile /$strNewFile");
		return true;
	}
	else return false;
}

// Functie om MRTG te restarten
function restartMRTG() {
	shell_exec("sudo /usr/bin/killall mrtg");
	shell_exec("sudo /usr/bin/mrtg /etc/mrtg/mrtg.cfg");
	return true;
}


?>
