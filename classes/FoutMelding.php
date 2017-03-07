<?php
/* Bestandsnaam: FoutMelding.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * De klasse die foutmeldingen opvangt en doorgeeft aan de errorpage.
 *
 * Errorcodes:
 * 1 - MySQL-error bij het verbinden
 * 2 - MySQL-error bij een SELECT-query
 * 3 - MySQL-error bij een INSERT, UPDATE of DELETE-query
 * 4 - MySQL-error bij het selecteren van een andere database
 */


class FoutMelding extends Exception {
	// String voor als er een fout opgetreden is bij het uitvoeren van een SQL-query 
	private $strQuery;
	// Int voor als er een fout bij MySQL-db is opgetreden, dus krijg je de MySQL-foutcode mee
	private $intMySQLErrCode;
    
    // Constructor
    function __construct($strException, $strErrCode, $intMySQLErrCode, $strQuery = '') {
        parent::__construct($strException, $strErrCode);
        $this->strQuery = $strQuery;
        $this->intMySQLErrCode = $intMySQLErrCode;
    }
    
    // Functie 
    public function getErrorPage() {
    	$_SESSION['foutObj'] = serialize($this);
    	if(parent::getCode() == "1")
	    	header("Location: errorPagina.php?dberror=true");
	   else
	    	header("Location: errorPagina.php");	   
    }
	
	 // Set-methodes
    public function setQuery( $newQuery ) {
        $this->strQuery = $newQuery;
    }
	 public function setMySQLErrCode( $newMySQLErrCode ) {
	 	  $this->intMySQLErrCode = $newMySQLErrCode;
	 }
    // Get-methodes
    public function getQuery() {
        return $this->strQuery;
    }
    public function getMySQLErrCode() {
    	  return $this->intMySQLErrCode;
    }
    public function getCodeBetekenis() {
    	$arrCodes[1] = "MySQL-error bij het verbinden";
    	$arrCodes[2] = "MySQL-error bij een SELECT-query";
    	$arrCodes[3] = "MySQL-error bij een INSERT, UPDATE of DELETE-query";
    	$arrCodes[4] = "MySQL-error bij het selecteren van een andere database";
		
		while (list($strKey, $strVal) = each($arrCodes)) {
   		if(parent::getCode() == $strKey)
   			return $strVal;
		}  	
    }
}

?>