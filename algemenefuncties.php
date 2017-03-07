<?php
/* Bestandsnaam: algemenefuncties.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * Het bestand waarin de algemene functies staan
 *
 */

 // Functie om klassen automatisch in te laden als ze nodig zijn.
 function __autoload($strKlasseNaam) {
   include_once("classes/".$strKlasseNaam.".php");
 }

 // Functie om data te checken.
 function checkData($strData, $strType = 'regeltekst') {
 	$strData = trim($strData);
 	if($strType == "regeltekst") {
 		$strData = addslashes($strData); 	
  		$strData = htmlentities($strData);
 	}
 	elseif($strType == "tekst") {
 		$strData = addslashes($strData);
 		$strData = htmlentities($strData);
 		$strData = nl2br($strData);
 	}
 	elseif($strType == "html") {
 	    $strData = addslashes($strData);
 	}
	elseif($strType == "wysiwyg" ) {
		$strData = addslashes( $strData );
		$strData = htmlentities( $strData );
	}
 	elseif($strType == "integer") {
 		settype($strData, "integer");
 	}
 	elseif($strType == "checkbox") {
 		if($strData == "")
 			$strData = "nee"; 
 	}
 	return $strData;
 }
 // Functie om data te fixen
 function fixData($strData, $strType = 'normaal') {
 	if($strType == "normaal") {
 		$strData = stripslashes($strData);
 	}
 	elseif($strType == "tekstvak") {
 		$strData = stripslashes($strData);
	    $strData = preg_replace( '!<br.*>!iU', "", $strData );
 		
 	}
 	return $strData;
 }
 // Functie om huidige datum+tijd op te vragen, volgorde: yyyy-mm-dd uu:mm:ss
 function getDatumTijd() {
    return date("Y-m-d H:i:s");
 }
 // Functie om toekomstige dagen op te vragen 
 function getToekomstDatumTijd( $intDagen = 0, $intMaanden = 0, $intJaren = 0, $intUren = 1, $booMinSec = false) {
 	if($booMinSec == false) {
 		return date("Y-m-d H:i:s", mktime(date("H") + $intUren, 0,
 					 0, date("m") + $intMaanden, date("d") + $intDagen, date("Y") + $intJaren) );
 	}
	else {
		return date("Y-m-d H:i:s", mktime(date("H") + $intUren, date("i"),
			 	date("s"), date("m") + $intMaanden, date("d") + $intDagen, date("Y") + $intJaren) );
 	}
 	
 }
 // Functie om array met alle userlevels op te vragen
 function getUserLevelsArray($intMaxUserLevel = 4) {
 	for($i = 0; $i < ($intMaxUserLevel); $i++) {
 		$arrUserlevel[$i] = ($i+1);	
 	}	
 	return $arrUserlevel;
 }
 // Functie om array met alle userlevels op te vragen (korte versie)
 function getUserLevels($intMaxUserLevel = 4) {
 	return getUserLevelsArray($intMaxUserLevel);
 }
 // Functie om array met Ja en Nee op te vragen
 function getJaNeeArray() {
 	$arrJaNee[0]  = "ja";
 	$arrJaNee[1]  = "nee";
 	return $arrJaNee;
 }
 // Functie om maanden array op te vragen
 function getMaandArray() {
 	$arrMaand[1] = "Januari";
 	$arrMaand[2] = "Februari";
 	$arrMaand[3] = "Maart";
 	$arrMaand[4] = "April";
 	$arrMaand[5] = "Mei";
 	$arrMaand[6] = "Juni";
 	$arrMaand[7] = "Juli";
 	$arrMaand[8] = "Augustus";
 	$arrMaand[9] = "September";
 	$arrMaand[10] = "Oktober";
 	$arrMaand[11] = "November";
 	$arrMaand[12] = "December";
 	
 	return $arrMaand;
 }
 function getGetallenArray($intAantal = 0, $intBegin = 0) {
 	if($intAantal == 0 && $intBegin == 0) return false;
 	else {
 		for($i = 0; $i < $intAantal; $i++) {
 			$arrGetallen[$i] = $intBegin + $i;	
 		}
 		return $arrGetallen;
 	}
 } 
 // Functie om een MySQL-result array om te zetten naar een array vol met objecten
 function SQLArrToObjArr( $arrMySQLResult, $strKlasseNaam = '' ) {
	if($arrMySQLResult != false && $strKlasseNaam != "") {
 		$intArraySize = count($arrMySQLResult);
		for($i = 0; $i < $intArraySize; $i++) {
			$obj = new $strKlasseNaam;
			$obj->setValues($arrMySQLResult[$i]);
			$arrObjecten[$i] = $obj;
		}
		return $arrObjecten;
	}
	else return false;
 }
 // Functie om een MySQL-result array om te zetten naar 1 object, als er geen ID wordt opgegeven
 // dan wordt automatisch de 1e gepakt (0 dus)
 function SQLArrToObj( $arrMySQLResult, $strKlasseNaam = '', $intArrayID = 0) {
	if($arrMySQLResult != false && $strKlasseNaam != "") {
			$obj = new $strKlasseNaam;
			$obj->setValues($arrMySQLResult[$intArrayID]);
			return $obj;
	}
	else return false;
 }
 // Functie om een datum om te zetten tot een 'nette' datum
 function getHTMLDatumNet($strDatumTijd = '', $booMelding = true) {
 	if($strDatumTijd != "" && $strDatumTijd != "0000-00-00 00:00:00") {
		$arrDatumTijd = explode(" ",$strDatumTijd);
		$arrTijd = explode(":",$arrDatumTijd[1]);
		$arrDatum = explode("-",$arrDatumTijd[0]);
		$strHTML =  $arrDatum[2]."-".$arrDatum[1]."-".$arrDatum[0]." om ".$arrTijd[0].":".$arrTijd[1].":".$arrTijd[2];	
		return $strHTML;
 	}
 	elseif($booMelding == true) return "<i>Niet van toepassing</i>"; 
 	else return "";
 }
 // Functie om nieuwe ID-nummer op te vragen
 function getNewID( $strTable) {
 	global $objDBConnectie;
 	$sql = "SELECT id FROM ".$strTable." ORDER BY id DESC";
	$arrMysqlResult = $objDBConnectie->getData($sql);
	if($arrMysqlResult == false) return "1";
	else {
		return ($arrMysqlResult[0]['id'] + 1 );
	}
 }
// Functie om een IP te checken of die voldoet aan IPv4 
function checkIP($strIP) {
   if (eregi("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $strIP)) {
       for ($i = 1; $i <= 3; $i++) {
           if (!(substr($strIP, 0, strpos($strIP, ".")) >= "0" && substr($strIP, 0, strpos($strIP, ".")) <= "255")) return false;
           $strIP = substr($strIP, strpos($strIP, ".") + 1);
       }
       if (!($strIP >= "0" && $strIP <= "255")) return false;
   }
   else return false;
   
return true;
}
// Functie om een e-mail te versturen
function verstuurMail($strAan, $strBericht, $strOnderwerp = false, $strAfzender = false ) {
	global $_arrConfig;
		
	if($strOnderwerp == false)
		$strOnderwerp = "Bericht van ".$_arrConfig['website_title'];
	if($strAfzender == false)
		$strAfzender = $_arrConfig['website_mail'];
		
	$strHeaders = "From:  \"".$_arrConfig['website_title']."\" <".$strAfzender.">\n".
	              "Reply-To: ".$strAfzender."\n".
	              "MIME-Version: 1.0\n".
	              "Content-Type: text/plain; charset=\"utf-8\"\n".
	              "X-Sender: <".$strAfzender.">\n".
	              //"X-Mailer: ".$_arrConfig['website_title']."\n".
	              "X-Priority: 3\n".
	              "Return-Path: <".$strAfzender.">\n";

	 if($strBericht != "" && $strAan != "") {
	 	if(mail($strAan, $strOnderwerp, $strBericht, $strHeaders, "-i -f ".$strAfzender)) return true; 
	 	else return false; 
	 }
	 else return false;
}

 // Functie om random wachtwoord te genereren
 function generateRandomString( $intLengte = 8) {
	$strAlphaNumValues = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	return random_string($strAlphaNumValues, $intLengte);
 }
// Functie om random char te genereren (gekopieerd van phpfreakz)
// Aangepast door mijzelf
function random_char($strTekst) {
  $intLengte = strlen($strTekst);
  $intPositie = mt_rand(0, $intLengte - 1);
  return($strTekst[$intPositie]);
}
// Functie om random string te genereren (gekopieerd van phpfreakz)
// Aangepast door mijzelf
function random_string($strCharSet, $intLengte) {
  $strReturnString = "";
  for ($i = 0; $i < $intLengte; $i++) 
    $strReturnString .= random_char($strCharSet);

  return $strReturnString;
}
 // Functie om datum/tijd te converteren tot leesbare datum/tijd
 function convertDatumTijd( $dtDatum = '') {
    if($dtDatum == null) {
    	$dtDatum = getDatumTijd();
    }
 	$arrDatum['jaar'] = substr($dtDatum, 0, 4);  	
 	$arrDatum['maand'] = substr($dtDatum, 5, 2);
 	$arrDatum['dag'] = substr($dtDatum, 8, 2);
 	$arrDatum['uur'] =  substr($dtDatum, 11, 2);
 	$arrDatum['minuten'] = substr($dtDatum, 14, 2);
 	$arrDatum['seconden'] = substr($dtDatum, 17, 2);
 	
 	return $arrDatum;
 }



?>