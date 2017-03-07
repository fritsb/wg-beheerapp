<?php
/* Project: iCMS
 * Auteur: Frits Bosschert 
 * In opdracht van Impression New Media
 * 
 * Bestandsnaam: errorPagina.php
 * Beschrijving: De errorpagina van beheerders en gebruikers
 */
 session_start();
 include("header.php");
 
 
 if(isset($_SESSION['foutObj'])) {
 	$objError = unserialize($_SESSION['foutObj']);
 } 
 echo "<h1>Pagina kon niet worden opgevraagd</h1>\n";
 if(isset($_GET['dberror'])) {
	echo "Er is iets misgegaan bij het aanroepen van deze pagina. Het is namelijk niet mogelijk om een verbinding te maken met de database.<br><br>";
	echo " Probeer nog een keer deze pagina aan te roepen en als u deze pagina weer krijgt, probeer het dan over een paar uur weer. Neem dan contact op met <a href=\"mailto:$strInfoMailAdres\">$strBedrijfsNaam</a>.<br><br>\n"; 
	
 }
 else {
 	echo "Er is iets misgegaan bij het aanroepen van deze pagina. <a href=\"javascript:history.go(-1)\">Klik hier</a> om terug te gaan naar de vorige pagina of probeer het later nog een keer.<br><br>\n";
 	echo "Al krijgt u elke keer deze pagina te zien, neem dan contact op met <a href=\"mailto:$strInfoMailAdres\">$strBedrijfsNaam</a>.<br><br>\n";
 }

if(isset($_SESSION['login']) && isset($_SESSION['adm'])) {
 echo "<h1>Gegevens van de foutmelding</h1>\n";
 echo "Hieronder staan de gegevens van de foutmelding. Deze gegevens zijn alleen voor de beheerders zichtbaar.<br><br>\n"; 
 echo "<b>Foutcode:</b> ".$objError->getCode()."<br>\n";
 echo "<b>Fouttype:</b> ".$objError->getCodeBetekenis()."<br>\n";
 echo "<b>Bericht:</b> ".$objError->getMessage()."<br>\n";
 echo "<b>Bestandsnaam:</b> ".$objError->getFile()."<br>\n";
 echo "<b>Regelnummer:</b> ".$objError->getLine()."<br>\n";
 if($objError->getQuery() != "")
 	echo "<b>SQL-query:</b> ".$objError->getQuery()."<br>\n";
 if($objError->getMySQLErrCode() != "")
 	echo "<b>MySQL Errorcode:</b> ".$objError->getMySQLErrCode()."<br>\n";
 echo "<b>Stack trace:</b><br>".nl2br($objError->getTraceAsString())."<br>\n";
 
 }
 include("footer.php");
 ?>