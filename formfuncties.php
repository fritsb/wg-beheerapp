<?php
/* Bestandsnaam: formfuncties.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 19-09-2005
 * Omschrijving: 
 * Het bestand waarin de functies voor de formulieren staan
 *
 */
// Functie om een form te openen
function openForm($strFormNaam, $strModuleNaam = '', $strMethode = 'post', $booEnctype = false, $strExtraHTML = '' ) {
	$strHTML = "<form action=\"".$_SERVER['PHP_SELF']."\" id=\"".$strFormNaam."\" method=\"".$strMethode."\"";
	if($booEnctype == true)
		$strHTML .= " enctype=\"multipart/form-data\"";
	$strHTML .= ">\n";
	if($strModuleNaam != "") 
		$strHTML .= "<input type=\"hidden\" name=\"module\" value=\"".$strModuleNaam."\"/>\n";
  	return $strHTML;
 }
  // Functie om een tekst-inputvak te zien
 function showInputVeld( $strNaam, $strValue  = '', $strClass = '',$strStyle = '', $strSoort = 'text', $intSize = '', $intMaxLength = '' ) {
 	$strHTML = "<input type=\"".$strSoort."\" name=\"".$strNaam."\"".
 	           " value=\"".$strValue."\"";
 	if($strClass != "")
 		$strHTML .= " class=\"".$strClass."\"";
 	if($strStyle != "")
 		$strHTML .= " style=\"".$strStyle."\"";
 	if($intSize != "")
 		$strHTML .= " size=\"".$intSize."\"";
 	if($intMaxLength != "")
 		$strHTML .= " maxlength=\"".$intMaxLength."\"";
 	$strHTML .= "/>\n";
 	
 	return $strHTML;
 }
 // Functie om door te schuiven
 function showInputTekst( $strNaam, $strValue  = '', $strClass = '',$strStyle = '', $strSoort = 'text', $intSize = '', $intMaxLength = '' ) {
 	return showInputVeld($strNaam, $strValue, $strClass,$strStyle, $strSoort, $intSize, $intMaxLength);	
 }
 // Functie om een tekst-inputvak te zien
 function showTextArea( $strNaam, $strValue  = '', $strClass = '',$strStyle = '', $intCols = '', $intRows = '' ) {
 	$strHTML = "<textarea name=\"".$strNaam."\"";
 	if($strClass != "")
 		$strHTML .= " class=\"".$strClass."\"";
 	if($strStyle != "")
 		$strHTML .= " style=\"".$strStyle."\"";
 	if($intSize != "")
 		$strHTML .= " rows=\"".$intRows."\"";
 	if($intMaxLength != "")
 		$strHTML .= " cols=\"".$intCols."\"";
 	$strHTML .= ">".$strValue."</textarea>\n";
 	
 	return $strHTML;
 }
 // Functie om een een button te laten zien
 function showInputKnop($strNaam, $strValue = '', $strClass = '', $strStyle = '', $strSoort = 'submit') {
	$strHTML = "<input type=\"".$strSoort."\" name=\"".$strNaam."\"".
		   " value=\"".$strValue."\"";
	if($strClass != "")
		$strHTML .= " class=\"".$strClass."\"";
	if($strStyle != "")
		$strHTML .= " style=\"".$strStyle."\"";
	$strHTML .= "/>\n";
	return $strHTML;	
 }
 // Functie om een selectlijst te laten zien
 function showSelectLijst( $strNaam, $strValue, $arrKeuzeValues, $arrKeuzeNamen) {
 	$intArraySize = count($arrKeuzeValues);
	$strHTML = "<select name=\"".$strNaam."\">\n";
 	for($i = 0; $i < $intArraySize; $i++) {
 		$strHTML .= "<option value=\"".$arrKeuzeValues[$i]."\"";
 		if($arrKeuzeValues[$i] == $strValue) $strHTML .= " selected=\"selected\"";
 		$strHTML .= ">".ucfirst($arrKeuzeNamen[$i])."</option>\n";
 		
 	}
 	$strHTML .= "</select>\n";
 	return $strHTML;
 	
 }
 // Functie om een selectlijst te laten zien die bestaat uit 'ja' en 'nee'
 function showJaNeeLijst( $strNaam, $strKeuze ) {
 	$arrKeuzes = array( 'ja', 'nee' );
 	return showSelectLijst($strNaam, $strKeuze, $arrKeuzes, $arrKeuzes); 	
 }
 // Functie om een selectlijst te laten zien van dagen
 function showSelectDagenLijst($strNaam, $strValue = '') {
 	$arrGetallen = getGetallenArray(31, 1);
	return showSelectLijst($strNaam, $strValue, $arrGetallen, $arrGetallen);
 }
 // Functie om een selectlijst te laten zien van maanden
 function showSelectMaandenLijst($strNaam, $strValue = '') {
 	$arrMaanden = getMaandArray();
 	$arrGetallen = getGetallenArray(12, 1);
	return showSelectLijst($strNaam, $strValue, $arrGetallen, $arrMaanden );
 }
 // Functie om een selectlijst te laten zien voor bij het toevoegen van een gebruiker
 function showActivatieLijst( $strNaam, $strValue = '' ) {
 	$arrKeuzes = getGetallenArray(3, -1);
 	$arrNamen[0] = "(-1) Sla gegevens op, verstuur geen e-mail";
 	$arrNamen[1] = "( 0) Sla gegevens op, vraag om bevestiging";
 	$arrNamen[2] = "( 1) Activeer gebruiker, verstuur wachtwoord";
 	return showSelectLijst($strNaam, $strValue, $arrKeuzes, $arrNamen);
 }
  // Functie om een selectlijst te laten zien voor bij het toevoegen van een gebruiker
 function showBanStatusLijst( $strNaam, $strValue = '' ) {
 	$arrKeuzes = getGetallenArray(2, 0);
 	$arrNamen[0] = "(0) Ban is niet actief";
 	$arrNamen[1] = "(1) Ban is actief";
 	return showSelectLijst($strNaam, $strValue, $arrKeuzes, $arrNamen);
 }
  // Functie om een selectlijst te laten zien voor bij het toevoegen van een gebruiker
 function showBanRedenLijst( $strNaam, $strValue = '' ) {
 	$arrKeuzes[0] = "false_login";
 	$arrKeuzes[1] = "perm_ban";
 	$arrKeuzes[2] = "short_ban";
 	$arrNamen[0] = "Persoon heeft 5 keer verkeerd ingelogd";
 	$arrNamen[1] = "Persoon is voor altijd verbannen";
 	$arrNamen[2] = "Persoon is tijdelijk verbannen";
 	return showSelectLijst($strNaam, $strValue, $arrKeuzes, $arrNamen);
 }
 // Functie om een selectlijst te laten zien voor bij het toevoegen van een gebruiker
 function showGebruikerLijst( $strNaam, $strValue = '' ) {
 	$arrGebruikers = getGebruikers(0, 0);
 	$intArraySize = count($arrGebruikers);
 	$arrKeuzes[0] = 0;
 	$arrNamen[0] = "Geen";
 	for($i = 0; $i < $intArraySize; $i++) {
 		$objGebruiker = $arrGebruikers[$i];
 		if($objGebruiker != false) {
	 		$arrKeuzes[$i+1] = $objGebruiker->getPersoonID();
	 		$arrNamen[$i+1] = $objGebruiker->getVolledigeNaam()." (".$objGebruiker->getPersoonID().")";
 		}
 	}
 	 	
 	return showSelectLijst($strNaam, $strValue, $arrKeuzes, $arrNamen);
 }
 // Functie om een datum lijst te laten zien
 function showDatumLijst( $strNaam, $strValue = '') {
 	if($strValue == "") {
	 	$arrValues['jaar'] = date("Y");  	
	 	$arrValues['maand'] = date("m");
	 	$arrValues['dag'] = date("d");
	 	$arrValues['uur'] =  "";
	 	$arrValues['minuten'] = "";
 	}
 	$arrValues = convertDatumTijd($strValue);
 	return showInputVeld($strNaam."dag", $arrValues['dag'], '', '', 'text', '2', '2')."-".
 		   showInputVeld($strNaam."maand", $arrValues['maand'], '', '', 'text', '2', '2')."-".
 		   showInputVeld($strNaam."jaar", $arrValues['jaar'], '', '', 'text', '4', '4')." ".
 		   showInputVeld($strNaam."uur", $arrValues['uur'], '', '', 'text', '2', '2').":".
 		   showInputVeld($strNaam."minuten", $arrValues['minuten'], '', '', 'text', '2', '2')." ";

 }
 // Functie om een form te sluiten
 function sluitForm( ) {
 	$strHTML = "</form>\n";
	return $strHTML;
 }
 
 ?>