<?php
/* Bestandsnaam: layoutfuncties.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 19-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies voor de lay-out staan of andere functies die met xhtml te maken hebben
 *
 */
 // Functie om de header te tonen
 function  showHeader() {
 	global $_arrConfig;
 	global $_arrStatussen;
 	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\" >\n".
		  "<html>\n".
		  "  <head>\n".
		  "    <title>Wireless Grootebroek - Beheerapplicatie</title>\n".
		  "    <style type=\"text/css\">\n".
		  "      <!--\n".
		  "      @import url(\"".$_arrConfig['css_dir'].$_arrConfig['css_file']."\");\n".
		  "      -->\n".
 		  "    </style>\n".
  		  "  </head>\n".
		  "  <body>\n".
		  "   <div id=\"website\">\n".
		  "    <div id=\"header\">\n".
		  "    ".getImage($_arrConfig['header_img'],127, 78, 0, $_arrConfig['website_title'],'', true).
		  "        <p>".$_arrConfig['website_title']." - Applicatie</p>\n".
		  "    </div>\n".
		  "    <div id=\"main\">\n".
		  "";
		  showMenu();
	$_arrStatussen['header'] = true;
 }
 // Functie om de header te tonen
 function  showFooter() {
 	global $_arrConfig;
 	echo "    </div>".
	     "    <div id=\"footer\">\n".
	     "        <p>&copy; ".date("Y")." ".$_arrConfig['website_title']."</p>".
	     "    </div>\n".
	     "   </div>\n".
 	     "  </body>\n".
 	     "</html>\n";
 }
 // Functie om het menu te tonen
 function showMenu() {
 	global $_objUser, $_userLevel;
 	if($_objUser != false) {
	 	$arrMenuModules = getMenuModules($_userLevel);
	 	$intArraySize = count($arrMenuModules);
	 	echo "    <div id=\"menu\"><ul>\n";
	 	if($arrMenuModules != false && $intArraySize != 0) {
			for($i = 0; $i < $intArraySize; $i++) {	
			 	$objModule = $arrMenuModules[$i];
				if($objModule != false) {
				 	echo "      <li class=\"menuitem\">".
				 		 getLink('index.php', $objModule->getMenuNaam(), $objModule->getModuleNaam()).
				 		 "</li>\n";
			 	}
			}
	 	}
	 	echo "      <li class=\"menuitem\">".getLink('index.php?action=uitloggen', 'Uitloggen','login')."</li>\n".
	 	     "    </ul></div>\n";
 	}
 	else {
	 	echo "    <div id=\"menu\"><ul>\n".
	 	     "      <li class=\"menuitem\">".getLink('index.php', 'Inloggen', 'login')."</li>\n".
	 	     "      <li class=\"menuitem\">".getLink('index.php?action=aanvraag', 'Wachtwoord  aanvragen', 'login')."</li>\n".
	 	     "    </ul></div>\n";
 	} 	

 }


 // Functie om een error pagina af te beelden
 function showErrorPagina( $intErrorID ) {
 	global $_arrStatussen;
	
	switch ($intErrorID) {
	case 1: // Algemene error, geen actie opgegeven
		$strTitel = "Er is iets misgegaan..";
		$strMelding = "Helaas is er iets misgegaan, vandaar dat deze pagina tevoorschijn komt. ".
					  "<br/><br/>Maak uw keuze in het menu links om verder te gaan";
		break;
	case 2: // Status-pagina, onbekende actie
		$strTitel = "Er is iets misgegaan..";
		$strMelding = "Helaas is er iets misgegaan, vandaar dat deze pagina tevoorschijn komt. ".
					  "De status van de bevestiging, verificatie of wachtwoord aanvraag kon ".
					  " niet worden veranderd. Probeer het nog een keer. ";
		break;
	case 3: // Ban
		$strTitel = "Geen toegang";
		$strMelding = "Uw IP-adres is verbannen van deze website, dat betekent dat u geen toegang meer ".
					  "heeft tot deze website. Probeer het later nog een keer. ";
		break;
	default: // Algemene error
		$strTitel = "Er is iets misgegaan..";
		$strMelding = "Helaas is er iets misgegaan, vandaar dat deze pagina tevoorschijn komt. ".
					  "<br/><br/>Maak uw keuze in het menu links om verder te gaan";
	}

	if($_arrStatussen['header'] == false) {
		showHeader();
		showContentVak($strTitel, $strMelding);
		showFooter();
	}
	else showContentVak($strTitel, $strMelding);
 }
 // Functie om de zogenaamde content-tabel op te bouwen en bepaalde tekst te laten zien
 function showContentVak( $strTitel = '', $strTekst = '' ) {
		echo openContentVak( $strTitel ).
			 $strTekst.
		     sluitContentVak();
 }
 
  // Functie om een div te open
 function openDiv( $strID = '', $strClass = '', $strStyle = '') {
	$strHTML = getOpenTag('div',$strID, $strClass, $strStyle);
 	return $strHTML;
 }
 // Functie om een div te sluiten
 function sluitDiv() {
 	return "</div>\n";	
 }
  // Functie om een span te open
 function openSpan( $strID = '', $strClass = '', $strStyle = '') {
	$strHTML = getOpenTag('span',$strID, $strClass, $strStyle);
 	return $strHTML;
 }
 // Functie om een span te sluiten
 function sluitSpan() {
 	return "</span>\n";	
 }
  // Functie om een span te open
 function openP( $strID = '', $strClass = '', $strStyle = '') {
	$strHTML = getOpenTag('p',$strID, $strClass, $strStyle);
 	return $strHTML;
 }
 // Functie om een span te sluiten
 function sluitP() {
 	return "</p>\n";	
 }
 // Functie om contentvak te openen
 function openContentVak($strContentTitel = '', $strModuleNaam = '', $strModuleNaamNet = '', $intMenuID = '', $strActive = 'view' ) {
 	$strHTML .= openDiv('mainVak').
		     	openDiv('mainHeader').sluitDiv().
		     	openDiv('mainTitle')."<h1>".$strContentTitel."</h1>".
		    	sluitDiv().
				getMainMenu($strModuleNaam, $strModuleNaamNet, $intMenuID, $strActive).
				openDiv('mainContent').
		     	openDiv('content');
	return $strHTML;
 }
 function sluitContentVak() {
 	$strHTML .= sluitDiv().sluitDiv().
		     	openDiv('mainFooter').
		     	sluitDiv().
		     	sluitDiv();	
	return $strHTML;
 }
 
 
 // Functie om een zogenaamde tabel te maken, met tabelhoofd
 function openTabel( $strSoortTabel = '', $booVeldTitels = true, $strAlternatiefTitel = false ) {	
	$strHTML = openDiv('', $strSoortTabel."Tabel", '');
	if($booVeldTitels == true) {
		$strHTML .= openRij($strSoortTabel);
		$strHTML .= openDiv('', $strSoortTabel."TabelVeldTitel", '');
		$strHTML .= "Veldnaam:".sluitDiv();
		$strHTML .= openDiv('', $strSoortTabel."TabelVeldTitel", '');
		$strHTML .= "Waarde:".sluitDiv();
		$strHTML .= sluitRij();
	}
	elseif($strAlternatiefTitel != false) {
		$strHTML .= openRij($strSoortTabel).openDiv('', $strSoortTabel."TabelVeldTitel").
					$strAlternatiefTitel.sluitDiv().sluitRij();
	}
	
	return $strHTML;
 }
 // Functie om een tabel af te sluiten
 function sluitTabel() {
 	return sluitDiv();	
 }
 // Functie om een rij te openen
 function openRij( $strSoortTabel = '') {
 	return openDiv('', $strSoortTabel."TabelRij", '');
 	
 }
 // Functie om een rij te sluiten
 function sluitRij() {	
 	return sluitDiv();	
 }
 // Functie om een cel te openen
 function openCel( $strSoortTabel = '', $intVeldNummer = 1) {
 	return openDiv('', $strSoortTabel."TabelVeld".$intVeldNummer, '');
 }
 // Functie om een cel te sluiten
 function sluitCel() {
 	return sluitDiv();
 }
 
 // Functie om ID, Class en Style-attributen te verwerken
 function getOpenTag($strTagNaam, $strID = '', $strClass = '', $strStyle = '') {
	$strHTML = "<".$strTagNaam;
 	if($strID != "")
 		$strHTML .= " id=\"".$strID."\"";
 	if($strClass != "")
 		$strHTML .= " class=\"".$strClass."\"";
 	if($strStyle != "")
 		$strHTML .= " style=\"".$strStyle."\"";
 	$strHTML .= ">\n";
 	return $strHTML;
 }
 // Functie om menu te laten zien bij een 
 function getMainMenu($strModuleNaam = '', $strModuleNaamNet = '', $intID = '', $strActive = 'view') {
 		$strLink = "index.php?module=".$strModuleNaam."&amp;id=".$intID."&amp;action=";
 		$strHTML = "<div id=\"mainMenu\">\n";
 		if($strModuleNaam != "") {
	 		$strHTML .= "<ul>\n".
	 				   "<li><a href=\"".$strLink."view\"";
	 		if($strActive == "view") $strHTML .= " class=\"active\"";
	 		$strHTML .= ">Bekijk ".$strModuleNaamNet."</a></li>\n".
	 				   "<li><a href=\"".$strLink."edit\"";
	 		if($strActive == "edit") $strHTML .= " class=\"active\""; 				   
	 		$strHTML .= ">Bewerk ".$strModuleNaamNet."</a></li>\n".
	 				   "<li><a href=\"".$strLink."del\"";
	 		if($strActive == "del") $strHTML .= " class=\"active\"";
	 		$strHTML .= ">Verwijder ".$strModuleNaamNet."</a></li>\n".
	 				   "</ul>\n";
 		}
 		$strHTML .= "</div>\n";
 				   
 		return $strHTML;
 	
 }
 // Functie om een plaatje op te vragen	
 function getImage( $strURL, $intWidth = '', $intHeight = '', $intBorder = 0, $strAlt = 'Afbeelding', $strTitle = '', $booImgDir = false ) {
 	if($booImgDir == true) { 
 		global $_arrConfig;
 		$strURL = $_arrConfig['img_dir'].$strURL;
 	}
 	if(!eregi("http", $strURL) && is_file($strURL) == false) return false;
	
 	if($intWidth == "" || $intHeight == "") {
		$arrMaten = getimagesize($strURL);
 		if($arrMaten != false && $intWidth == "") $intWidth = $arrMaten[0];
 		if($arrMaten != false && $intHeight == "") $intHeight = $arrMaten[1];
 	}

 	
 	$strHTML .= "<img src=\"".$strURL."\" width=\"".$intWidth."\" height=\"".$intHeight."\" border=\"".$intBorder."\"".
 				" alt=\"".$strAlt."\"";
 	if($strTitle != "") $strHTML .= " title=\"".$strTitle."\"";
 	$strHTML .= "/>\n";
 	return $strHTML;
 }
 // Functie om een link op te vragen
 function getLink( $strURL, $strLinkedTekst, $strModule = '',$strTitle = '', $strTarget = '') {
 	if($strModule != "") { 
 		if(eregi("\?", $strURL)) $strURL = $strURL."&amp;module=".$strModule;
 		else $strURL = $strURL."?module=".$strModule;
 	}
 	$strHTML = "<a href=\"".$strURL."\"";
 	if($strTarget != "") $strHTML .= " target=\"".$strTarget."\"";
 	if($strTitle != "") $strHTML .= " title=\"".$strTitle."\"";
 	$strHTML .= ">".$strLinkedTekst."</a>";
 	return $strHTML;
 }
 // Functie om een actielink te krijgen met of zonder icoon
 function getActieLink( $intID, $strModule, $strActie = 'view', $booIcoon = false,  $strLinkedTekst = '', $strTitle = '', $strTarget = '') {
 	$strHTML = "<a href=\"index.php?module=".$strModule."&id=".$intID."&action=".$strActie."\"";
 	
 	if($strTarget != "") $strHTML .= " target=\"".$strTarget."\"";
 	if($strTitle != "") $strHTML .= " title=\"".$strTitle."\"";
 	$strHTML .= ">";
 	if($strLinkedTekst != "") $strHTML .= $strLinkedTekst;
 	if($booIcoon == true) $strHTML .= getActieIcoon($strActie);
 	
 	$strHTML .= "</a>\n";
 	return $strHTML;
 	
 }
 // Functie om een icoon op te vragen
 function getActieIcoon( $strActie = 'view') {
 	$strURL = "icon_".$strActie.".png";
 	return getImage($strURL, '','', 0, '', '', true); 	
 }

 
 // Functie om het zogenaamde actiemenu op te vragen
 function getActieMenu( $intID, $strModule, $booView = false, $booEdit = false, 
 						 $booDel = false, $booPass = false, $strStijl = 'overzicht', $intVeldID = 1  ) {
	$strHTML = openDiv('',$strStijl."TabelMenuVeld".$intVeldID);
	if($booView == true) $strHTML .= getActieLink($intID, $strModule, 'view', true, '', 'Bekijk item');
	if($booEdit == true) $strHTML .= getActieLink($intID, $strModule, 'edit', true, '', 'Bewerk item');
	if($booDel == true) $strHTML .= getActieLink($intID, $strModule, 'del', true, '', 'Verwijder item');
	if($booPass == true) $strHTML .= getActieLink($intID, $strModule, 'pass', true, '', 'Wachtwoord opties');
	
	$strHTML .= sluitDiv();
		
	return $strHTML;
				 	
 }
 // Functie om een link te krijgen van het overzicht
 function getOverzichtLink($strModuleNaam, $strModuleNaamNet) {
 	$strHTML = "<a href=\"index.php?module=".$strModuleNaam."\">".
 			   "Terug naar het overzicht van ".$strModuleNaamNet."</a>";
 	return $strHTML;
 	
 }

 
 ?>
