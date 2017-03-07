<?php
/* Bestandsnaam: DatabaseConnectie.php
 * Ontwikkelaar: Frits Bosschert
 * Project: Wireless Grootebroek
 * 
 * Datum: 08-09-2005
 * Omschrijving: 
 * De klasse DatabaseConnectie, een subklasse van mysqli
 *
 */

class DatabaseConnectie extends mysqli {
  private $booConnection = false;
  private $result;
  private $strHost;
  private $strUser;
  private $strPass;
  private $strDatabase;
  private $strErrorPage;
  private $arrResult;
  
  // Constructor 
  public function __construct($strHost = '', $strUser = '', $strPass = '', $strDatabase = '', $strErrorPage = true ) {
	 $this->strHost = $strHost;
	 $this->strUser = $strUser;
	 $this->strPass = $strPass; 
	 $this->strDatabase = $strDatabase;
	 //$this->strErrorPage = $strErrorPage; //Error pagina uit gezet
	 $this->strErrorPage = false;
	
	 if($this->strHost != "" && $this->strUser != "") {
		$this->openConnectie($this->strHost, $this->strUser, $this->strPass, $this->strDatabase );
    }
  }
  
  // Destructor; sluit de connectie  
  public function __destruct() {
  	 if($this->booConnection == true) parent::close();
  }
  // Functie om een databaseconnectie te openen
  public function openConnectie($strHost = '', $strUser = '', $strPass = '', $strDatabase = '') {
	 if($strHost != "") $this->strHost = $strHost;
	 if($strUser != "") $this->strUser = $strUser;
	 if($strPass != "") $this->strPass = $strPass; 
	 if($strDatabase != "") $this->strDatabase = $strDatabase;
  	 try {
    	parent::__construct( $this->strHost, $this->strUser, $this->strPass, $this->strDatabase );	    
	    	if(mysqli_connect_errno()) {
	   	  		throw new FoutMelding(mysqli_connect_error(), 1, mysqli_connect_errno($this));
	    	}
	    	else {
		      	$this->booConnection = true;
		      	return $this->booConnection;
	   	}
 	 }
 	 catch(FoutMelding $objFoutMelding) {
    	$this->booConnection = false;
    	if($this->strErrorPage)
	  		$objFoutMelding->getErrorPage();
	  	else
	  		return $this->booConnection;
 	 }
  }
  // Functie om een databaseconnectie te sluiten
  public function sluitConnectie( ) {
  	if($this->booConnection == true) parent::close(); 
  	$this->booConnection = false;
  }
  // Methode om andere DB te selecteren
  public function setDB($newDatabase) {
  	$this->strDatabase = $newDatabase;
	try {
	  	if(!parent::select_db($this->strDatabase)) {
	  		throw new FoutMelding(mysqli_error($this), 4, mysqli_errno($this));
	  	}
	  	else {
	  		return true;
	  	}
	}
	catch(FoutMelding $objFoutMelding) {
		$this->booConnection = false;
     	if($this->strErrorPage)
  	  		$objFoutMelding->getErrorPage();
  	  	else
  	  		return false;
	}
  }
  
  // Methode om DB op te vragen
  public function getDB() {
  	return $this->strDatabase;
  }
  // Methode om data op te vragen, returnt een array of false/null als het niet goed gaat
  public function getData($sql) {
  	 if($this->booConnection != true) 
  	 	return false;
  	 
  	 try {	
  	   $this->result = parent::query( $sql );
  	   if(mysqli_error($this)) {
  		  throw new FoutMelding(mysqli_error($this), 2, mysqli_errno($this), $sql);
  	   }
  	   elseif($this->result == false || $this->result == null) {
  	 	  $this->result->close();
  	      return false;
  	   }
  	   elseif(mysqli_num_rows($this->result) == 0) {
  		  $this->result->close();
  	 	  return null;
       }
   	   elseif(mysqli_num_rows($this->result) >= 1) {
  	      for( $i = 0; $i < mysqli_num_rows($this->result); $i++ ) {
  		      $arrResult[$i] = $this->result->fetch_array();
  	      }
  	      $this->result->close();
  	      return $arrResult;    	
  	   }
  	   else {
  	 	  $this->result->close();
  	   	  return false;
  	   }  	
  	 }
  	 catch(Foutmelding $objFoutMelding) {
     	if($this->strErrorPage)
  	  		$objFoutMelding->getErrorPage();
  	  	else
  	  		return false;
     }
  }
  // Methode om data te setten, dus voor INSERT, UPDATE, DELETE
  public function setData($sql) {
     if($this->booConnection != true) return false;
  	 
  	 parent::query($sql);
  	 try {	
  	   if(mysqli_error($this)) {
  		  throw new FoutMelding(mysqli_error($this), 3, mysqli_errno($this), $sql);
  	   }
  	   else {
  	 	  return true;
  	   }
  	 }
  	 catch(Foutmelding $objFoutMelding) {
     	if($this->strErrorPage)
  	  		$objFoutMelding->getErrorPage();
  	  	else
  	  		return false;
     }  	 	 
  }
  // Methode om de ID van de laatste INSERT-query op te vragen
  public function getLastInsertedID() {
     if($this->booConnection != true) return false;
     else return $this->insert_id;
  }  
}

?>