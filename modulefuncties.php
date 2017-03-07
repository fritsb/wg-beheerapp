<?php
/* Bestandsnaam: modulefuncties.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies mbt modules staan
 *
 */

// Functie om de module-gegevens toe te voegen
function addModule($objModule) {
	global $objDBConnectie;
	$sql = "INSERT INTO module (modulenaam, functiesbestand, actiebestand, menunaam, userlevel, ".
		   "  toevoegdatum, actief) VALUES ('".$objModule->getModuleNaam()."', '".
		   $objModule->getFunctiesBestand()."', '".$objModule->getActieBestand().
		   "', '".$objModule->getMenuNaam()."', '".$objModule->getMinUserLevel().
		   "', '".$objModule->getToevoegDatum()."', '".$objModule->getActief()."' ) ";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
}
// Functie om de module-gegevens te bewerken
function editModule($objModule) {
	global $objDBConnectie;
	$sql = "UPDATE module SET modulenaam = '".$objModule->getModuleNaam()."', ".
	 	   " functiesbestand = '".$objModule->getFunctiesBestand()."', ".
	 	   " actiebestand = '".$objModule->getActieBestand()."', ".
	 	   " menunaam = '".$objModule->getMenuNaam()."', ".
	 	   " userlevel = '".$objModule->getMinUserLevel()."', ".
	 	   " actief = '".$objModule->getActief()."' WHERE ".
	 	   " id = '".$objModule->getID()."'";
	if($objDBConnectie->setData($sql)) return true;
	else return false;
	 
}
// Functie om de module-gegevens te verwijderen
function delModule($objModule) {
	global $objDBConnectie;
	$objTmpModule = getModule($objModule);
	if($objTmpModule != false) {
		$sql = "DELETE FROM module WHERE id = '".$objTmpModule->getID()."'";
		if($objDBConnectie->setData($sql)) return true;
		else return false;
	}
	else return false;
}
// Functie om de module-gegevens op te vragen
function getModule($objModule, $booID = true, $booNaam = false, $booUserLevel = false, 
		$booActief = false) {
	if($objModule == false) return false;
	else {
		global $objDBConnectie;
		$sql = "SELECT * FROM module WHERE id = id";
		if($objModule->getID() != "" && $booID == true) 
			$sql .= " AND id = '".$objModule->getID()."'";
		if($objModule->getModuleNaam() != "" && $booNaam == true)
			$sql .= " AND modulenaam = '".$objModule->getModuleNaam()."'";
		if($objModule->getMinUserLevel() != '-1' && $booUserLevel == true) 
			$sql .= " AND userlevel <= '".$objModule->getMinUserLevel()."'";
		if($objModule->getActief() != "" && $booActief == true)
			$sql .= " AND actief = '".$objModule->getActief()."'";
		$sql .= " LIMIT 1";
		
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else return SQLArrToObj($arrMysqlResult, 'Module');
	}
}
// Functie om de module-gegevens op te vragen
function getModules($intVan = 0, $intLimiet = 30, $objModule = false) {
	global $objDBConnectie;
	$sql = "SELECT * FROM module WHERE id = id";
	if($objModule != false && $objModule->getID() != "") 
		$sql .= " AND id = '".$objModule->getID()."'";
	if($objModule != false && $objModule->getModuleNaam() != "")
		$sql .= " AND modulenaam = '".$objModule->getModuleNaam()."'";
  	if($objModule != false && $objModule->getUserLevel() != "")  
  		$sql .= " AND userlevel <= '".$objModule->getUserLevel()."'";
	if($objModule != false && $objModule->getActief() != "")
		$sql .= " AND actief = '".$objModule->getActief()."'";
	$sql .= " ORDER BY id ASC";
	if($intLimiet != 0)
		$sql .= " LIMIT ".$intVan.", ".$intLimiet." ";
	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Module');
}
function includeFunctiesBestanden($strActief = 'ja') {
	global $_arrConfig;	
		$objModule = new Module();
		$objModule->setActief($strActief);
		$objModule->setMinUserLevel(4);
		$arrModules = getModules(0,0, $objModule);
		$intArraySize = count($arrModules);
		if($arrModules != false && $intArraySize != 0)  {
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpModule = $arrModules[$i];
				if($objTmpModule != false && is_file($_arrConfig['www_dir'].$_arrConfig['work_dir'].$objTmpModule->getFunctiesBestand())) 
					include_once($objTmpModule->getFunctiesBestand(true));
				else echo ":(";
			}
		}
}
// Functie om de module-gegevens op te vragen
function getMenuModules($_userLevel = 0) {
	global $objDBConnectie;
	$sql = "SELECT * FROM module WHERE id = id".
		   " AND userlevel <= '".$_userLevel."'".
		   " AND menunaam != ''".
		   " AND actief = 'ja'".
		   " ORDER BY userlevel ASC ";

	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return false;
	else return SQLArrToObjArr($arrMysqlResult, 'Module');
}
// Functie om module te checken, false  als alles OK is. Een array met errorcodes als alles niet ok is
function checkModule( $objModule, $booNew = false) {
	if($objModule == false) $arrError[-1] = true;
	else {
		global $_arrConfig;
		// ID-nummer
		if($objModule->getID() == "" && $booNew == false) $arrError[0] = true;
		elseif(strlen($objModule->getID()) > 255 && $booNew == false) $arrError[1] = true; // Mag niet groter zijn dan 255 tekens		
		elseif(eregi("[^0-9]{1,}", $objModule->getID())  && $booNew == false) $arrError[2] = true; // Alleen 0-9 zijn toegestaan		
		// Modulenaam
		if($objModule->getModuleNaam() == "") $arrError[10] = true;
		elseif(strlen($objModule->getModuleNaam()) > 100) $arrError[11] = true; // Mag niet groter zijn dan 100 tekens
		elseif(eregi("[^a-zA-Z0-9]{1,}", $objModule->getModuleNaam())) $arrError[12] = true; // Alleen chars a-z, A-Z, 0-9 zijn toegestaan		
		// Functiebestand
		if($objModule->getFunctiesBestand() == "") $arrError[20] = true;
		elseif(strlen($objModule->getFunctiesBestand()) > 255) $arrError[21] = true; // Mag niet groter zijn dan 255 tekens
		elseif(eregi("[^a-zA-Z0-9_.]{1,}", $objModule->getFunctiesBestand())) $arrError[22] = true; // Alleen chars a-z, A-Z, 0-9 en ._- zijn toegestaan 	
		elseif(!is_file($_arrConfig['www_dir'].$_arrConfig['work_dir'].$objModule->getFunctiesBestand())) $arrError[23] = true; // Bestand checken
		// Actiebestand
		if($objModule->getActieBestand() == "") $arrError[30] = true;
		elseif(strlen($objModule->getActieBestand()) > 255) $arrError[31] = true; // Mag niet groter zijn dan 255 tekens
		elseif(eregi("[^a-zA-Z0-9_.]{1,}", $objModule->getActieBestand())) $arrError[32] = true; // Alleen chars a-z, A-Z, 0-9 en ._- zijn toegestaan
		elseif(!is_file($_arrConfig['www_dir'].$_arrConfig['work_dir'].$objModule->getActieBestand())) $arrError[33] = true; // Bestand checken
		// Menunaam
		if(strlen($objModule->getMenuNaam()) > 20) $arrError[40] = true; // Menunaam mag niet groter zijn dan 20 chars
		elseif(eregi("[^[:space:]a-zA-Z0-9_.-]{1,}", $objModule->getMenuNaam())) $arrError[41] = true; // Alleen chars a-z, A-Z, 0-9 en spatie zijn toegestaan
		// Userlevel
		if($objModule->getUserLevel() != "0" && $objModule->getUserLevel() == "") $arrError[50] = true;
		elseif(strlen($objModule->getUserLevel()) > 255) $arrError[51] = true; // Mag niet groter zijn dan 255 tekens		
		elseif(eregi("[^0-9]{1,}", $objModule->getUserLevel())) $arrError[52] = true; // Alleen 0-9 zijn toegestaan
		// Actief
		if($objModule->getActief() != "ja" && $objModule->getActief() != "nee") $arrError[60] = true;	
		
	}
	
	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om module te checken, of de gegevens al bestaan in de db oid. False als alles OK is
function checkModuleDB($objModule, $booNew = false) {
	global $objDBConnectie;
	if($objModule == false) $arrError[-1] = true;
	else {
		$sql = "SELECT * FROM module WHERE id = id ".
			   " AND modulenaam = '".$objModule->getModuleNaam()."'";
		if($booNew != true) 
			$sql .= " AND id != '".$objModule->getID()."'	";
		if($objModule->getMenuNaam() != "") 
			$sql .= " AND menunaam = '".$objModule->getMenuNaam()."'";
			
		$arrMysqlResult = $objDBConnectie->getData($sql);
		if($arrMysqlResult == false) return false;
		else {
			$intArraySize = count($arrMysqlResult);
			for($i = 0; $i < $intArraySize; $i++) {
				$objTmpModule = SQLArrToObj($arrMysqlResult, 'Module', $i);
				if($objTmpModule != false && $objTmpModule->getModuleNaam() == $objModule->getModuleNaam())
					$arrError[70] = true; // Er is al een module met dezelfde naam
				if($objTmpModule != false && $objTmpModule->getMenuNaam() == $objModule->getMenuNaam())
					$arrError[80] = true; // Er is al een module met dezelfde menunaam
			}
		}
	}

	if(isset($arrError)) return $arrError;
	else return false;
}
// Functie om modulepagina te laten zien
function showModulePagina( $intID = 0, $strMelding = '', $extraObj = false, $extraObj2 = false) {
	showHeader();
	
	switch ($intID) {
	case 1: // Nieuwe module toevoegen
	  showModuleForm(false, $strMelding, $extraObj2);
	  break;  
	case 2: // Module bekijken
	  showModule($extraObj, $strMelding, $extraObj2);
	  break;
	case 3: // Module bewerken, of als er iets mis is gegaan bij toevoegen
	  showModuleForm($extraObj, $strMelding, $extraObj2);
	  break;
	case 4: // Module verwijderen
	  showDelModuleForm($extraObj, $strMelding, $extraObj2);
	  break;
	default: // Standaard, overzicht van modules
	  if($extraObj == false) $extraObj = 0;
	  showModulesOverzicht($extraObj, 30, $extraObj2, $strMelding);
	}
 	showFooter();
}

// Functie om de informatie van een module op het scherm te tonen
function showModule( $objModule, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
/*			if($arrErrors != false)	 { // array doorlopen	
				$strMeldingHTML .= "<ul>\n";
				
				$strMeldingHTML .= "</ul>\n";
			}
*/
		$strMeldingHTML .= "</div>\n";
	}
	$strTabelStijl = "info";
	if($objModule != false) {
		echo openContentVak( "Module-informatie van '".$objModule->getModuleNaam()."'", 'module', 'Module', $objModule->getID() );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl).
		     openRij($strTabelStijl).openCel($strTabelStijl)."Module: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getModuleNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Actiesbestand: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getActieBestand(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Functiesbestand: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getFunctieBestand(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Menunaam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getMenuNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objModule->getMinUserLevel(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Actief: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getActief(true).sluitCel().sluitRij().
		     sluitTabel().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('module', "modules").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
  
	}
	else showErrorPagina(4);
}
// Functie om het formulier op te vragen om een module te bewerken
function showModuleForm( $objModule = false, $strMelding = '', $arrErrors = false  ) {
	$strTabelStijl = "info";
	if($strMelding != false) { // Als melding bestaat, zie hieronder
		$strMeldingHTML = "<div class=\"error\">".$strMelding."\n";
			if($arrErrors != false)	 {// array doorlopen
				$strMeldingHTML .= "<ul>\n";
				if(isset($arrErrors[-1])) $strMeldingHTML .= "<li>De module is onjuist</li>\n";
				if(isset($arrErrors[0]) || isset($arrErrors[1]) || isset($arrErrors[2])) $strMeldingHTML .= "<li>Het ID-nummer van de module is onjuist</li>\n";
				if(isset($arrErrors[10]) || isset($arrErrors[11]) || isset($arrErrors[12])) $strMeldingHTML .= "<li>De modulenaam is onjuist of niet ingevuld, de modulenaam mag geen aparte tekens bevatten.</li>\n";
				if(isset($arrErrors[20]) || isset($arrErrors[21]) || isset($arrErrors[22])) $strMeldingHTML .= "<li>Het functiebestand is onjuist of niet ingevuld</li>\n";
				if(isset($arrErrors[23])) $strMeldingHTML .= "<li>Het functiebestand kon niet worden gevonden in de map</li>\n";
				if(isset($arrErrors[30]) || isset($arrErrors[31]) || isset($arrErrors[32])) $strMeldingHTML .= "<li>Het actiebestand is onjuist of niet ingevuld</li>\n";
				if(isset($arrErrors[33])) $strMeldingHTML .= "<li>Het actiebestand kon niet worden gevonden in de map</li>\n";
				if(isset($arrErrors[40]) || isset($arrErrors[41]) || isset($arrErrors[42])) $strMeldingHTML .= "<li>De menunaam is onjuist ingevuld</li>\n";
				if(isset($arrErrors[50]) || isset($arrErrors[51]) || isset($arrErrors[52])) $strMeldingHTML .= "<li>Er is niet opgegeven wat het minimale gebruikerslevel is</li>\n";
				if(isset($arrErrors[60])) $strMeldingHTML .= "<li>Er is niet opgegeven of de module actief moet worden</li>\n";
				if(isset($arrErrors[70])) $strMeldingHTML .= "<li>Er is al een module met de modulenaam '".$objModule->getModuleNaam()."' aanwezig</li>\n";
				if(isset($arrErrors[80])) $strMeldingHTML .= "<li>Er is al een module met de menunaam '".$objModule->getMenuNaam()."' aanwezig</li>\n";
				$strMeldingHTML .= "</ul>\n";
			}			
		$strMeldingHTML .= "</div>\n";
	}
	if($objModule != false && $objModule->getID() != "") {
		$objModuleOrg = getModule($objModule);
		$strFormNaam = "editModule";
		$strFormKnopNaam = "editModuleKnop";
		$strFormKnopWaarde = "Bewerk module";
		echo openContentVak( "Module '".$objModuleOrg->getModuleNaam()."' bewerken", 'module', 'Module', $objModule->getID(), 'edit' );
	}
	else {
		if($objModule == false)
			$objModule = new Module();
		$strFormNaam = "addModule";
		$strFormKnopNaam = "addModuleKnop";
		$strFormKnopWaarde = "Voeg module toe";
		echo openContentVak( "Nieuwe module toevoegen");
	}
		
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;
	echo openForm($strFormNaam, 'module').
	     showInputVeld('id',$objModule->getID(), '', '', 'hidden').
	     openTabel($strTabelStijl).
	     openRij($strTabelStijl).openCel($strTabelStijl)."Module: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('modulenaam', $objModule->getModuleNaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Actiesbestand: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('actiebestand', $objModule->getActieBestand(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Functiesbestand: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('functiebestand', $objModule->getFunctieBestand(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Menunaam: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputVeld('menunaam', $objModule->getMenuNaam(true)).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showSelectLijst('userlevel', $objModule->getMinUserLevel(true), getUserLevels(), getUserLevels()).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."Actief: ".sluitCel().
	     openCel($strTabelStijl, 2).
	     showSelectLijst('actief', $objModule->getActief(), getJaNeeArray(), getJaNeeArray()).sluitCel().sluitRij().
	     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
	     openCel($strTabelStijl, 2).
	     showInputKnop($strFormKnopNaam, $strFormKnopWaarde,'buttonStijl1').
	     sluitCel().sluitRij().
	     sluitTabel().sluitForm().
	     openTabel($strTabelStijl, false).
	     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('module', "modules").sluitCel().
	     sluitRij().sluitTabel().
	     sluitContentVak();

}
// Functie om het formulier op te vragen om een module te verwijderen
function showDelModuleForm( $objModule, $strMelding = '', $arrErrors = false ) {
	if($strMelding != false) {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
			if($arrErrors != false)	 { // array doorlopen	
				$strMeldingHTML .= "<ul>\n";
				
				$strMeldingHTML .= "</ul>\n";				
			}
				
		$strMeldingHTML .= "</div>\n";
	}

	$strTabelStijl = "info";
		
	if($objModule->getID() == "1" || $objModule->getID() == "2") {
		$strMeldingHTML = "<div class=\"error\">Het is vanwege beveiligingsredenen niet mogelijk om deze module te verwijderen.\n</div>\n";
		echo openContentVak( "Module '".$objModule->getModuleNaam()."' verwijderen", 'module', 'Module', $objModule->getID(), 'del' );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('module', "modules").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();

	}	
	elseif($objModule != false) {
		echo openContentVak( "Module '".$objModule->getModuleNaam()."' verwijderen", 'module', 'Module', $objModule->getID(), 'del' );
		if(isset($strMeldingHTML))
			echo $strMeldingHTML;
		echo openForm('delModule', 'module').
		     showInputVeld('id',$objModule->getID(), '', '', 'hidden').
		     openTabel($strTabelStijl).
			 openRij($strTabelStijl).openCel($strTabelStijl)."Module: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getModuleNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Actiesbestand: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getActieBestand(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Functiesbestand: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getFunctieBestand(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Menunaam: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getMenuNaam(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Gebruikerslevel: ".sluitDiv().
		     openCel($strTabelStijl, 2).$objModule->getMinUserLevel(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Toegevoegd op: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getToevoegDatumNet().sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."Actief: ".sluitCel().
		     openCel($strTabelStijl, 2).$objModule->getActief(true).sluitCel().sluitRij().
		     openRij($strTabelStijl).openCel($strTabelStijl)."&nbsp;".sluitCel().
		     openCel($strTabelStijl, 2).
		     showInputKnop('delModuleKnop', 'Verwijder module','buttonStijl1').
		     sluitCel().sluitRij().sluitTabel().
		     sluitForm().
		     openTabel($strTabelStijl, false).
		     openRij($strTabelStijl).openCel($strTabelStijl,3).getOverzichtLink('module', "modules").sluitCel().
		     sluitRij().sluitTabel().
		     sluitContentVak();
	}
	else showErrorPagina(4);
}
// Functie om het overzicht van modules te laten zien
function showModulesOverzicht( $intVan = 0, $intLimiet = 0, $objModule = false, $strMelding = '' ) {
	if($intVan < 0) $intVan = 0;
	$arrModules = getModules($intVan, $intLimiet, $objModule);
	$intArraySize = count($arrModules);

	if($strMelding != "") {
		$strMeldingHTML = "<div class=\"error\">".$strMelding;
		$strMeldingHTML .= "</div>\n";
	}	
	$strTabelStijl = "overzicht";
	echo openContentVak( "Modulesoverzicht").
		 "Hieronder staat het overzicht van de modules in het systeem. ";
		 if($intArraySize != 0 && $arrModules != false)
		 	echo "Module ".($intVan+1)." tot en met ".($intVan + $intArraySize)." worden getoond.\n";
	if(isset($strMeldingHTML))
		echo $strMeldingHTML;		 
	
	if($arrModules != false && $intArraySize != 0) {
		echo openTabel($strTabelStijl, false).
			 openRij($strTabelStijl).openDiv('',$strTabelStijl."TabelVeldTitel1")."Modulenaam:".sluitDiv().
			 openDiv('',$strTabelStijl."TabelVeldTitel2")."Acties:".sluitDiv().sluitRij();
		$intVeldID = 1;
		for($i = 0; $i < $intArraySize; $i++) {
			$objModule = $arrModules[$i];
			if($objModule != false) {
				echo openRij($strTabelStijl).openCel($strTabelStijl, $intVeldID).
					 getActieLink( $objModule->getID(), 'module', 'view', false, $objModule->getModuleNaam(), 'Bekijk module').
					 sluitCel().
					 getActieMenu($objModule->getID(),  'module', true, true, true, false, 'overzicht',$intVeldID).
					 sluitRij();		
				if($intVeldID == 1) $intVeldID = 2;
				elseif($intVeldID == 2) $intVeldID = 1;
			}
		}
		echo sluitTabel();
	}
	else {
		echo "<br/><br/>Er zijn nog geen modules in de database.";
	}

	
	echo openTabel('info', false).
	     openRij('info').openCel('info',3).
	     getLink('index.php?&action=add', "Voeg een module toe", 'module').
	     sluitCel().sluitRij().sluitTabel().
		 sluitContentVak();
}


?>