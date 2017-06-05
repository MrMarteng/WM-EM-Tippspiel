<?php
/* includes */
require_once("inc/tippDB.inc.php");
require_once("inc/ContentObjects.inc.php");


/* Vars */
$objStdCO = new std_CO();
$objRegisterForm = new formular("register", "?");
$strHTML = "";
$aryMessages = array();
$strForm;
$blnUserAdded = FALSE;



/* analyze the formular? */
if( array_key_exists('register', $_POST) )
{
	/* analyze */
	if( $_POST['password1'] == "")array_push($aryMessages, "Sie muessen ein Passwort eingeben!");
	elseif( $_POST['password1'] != $_POST['password2'])array_push($aryMessages, "Die Passw&ouml;rter untescheiden sich!");
	if( $_POST['username'] == "")array_push($aryMessages, "Sie muessen ein Benutzernamen eingeben!");
	
	
	if( count($aryMessages) == 0 )
	{
		/* formular seems to be correct -> add user */
		$objUserDB = new userDB();
		$mixDBError = $objUserDB->addUser($_POST['username'], md5($_POST['password1']), $_POST['surname'], $_POST['name'], $_POST['email']);
		if( ! $mixDBError )
		{
			$blnUserAdded = TRUE;
			array_push($aryMessages, "der Benutzer <b>".$_POST['username']."</b> wurde erfolgreich angelegt");
		}
		else
		{
			array_push($aryMessages, $mixDBError);
		}
		$objUserDB->disconnect();	
	}
}


/* create CO */
$objStdCO->setHeader("Anmeldung zum Tippspiel");
$objStdCO->intTextWidth = 180;


if(!$blnUserAdded)
{
	/* generate formular */
	if( array_key_exists('register', $_POST) )
	{
		$objRegisterForm->strAction = "?menu=register";
		$objRegisterForm->insertKat("Benutzerdaten");
		$objRegisterForm->insertInput("Benutzername", "name='username' size=20 type='text' value='".$_POST['username']."'");
		$objRegisterForm->insertInput("Passwort", "name='password1' size=20 type='password' value='".$_POST['password1']."'");
		$objRegisterForm->insertInput("Wiederholung", "name='password2' size=20 type='password' value='".$_POST['password2']."'");
		$objRegisterForm->insertKat("Pers&ouml;nliche Daten");
		$objRegisterForm->insertDoubleInput("Name", "name='name' type='text' size=15 value='".$_POST['name']."'", "name='surname' type='text' size=15 value='".$_POST['surname']."'");
		$objRegisterForm->insertInput("EMail", "name='email' size=25 type=text value='".$_POST['email']."'");
		$objRegisterForm->insertBlank();
		$objRegisterForm->insertSubmit("anmelden", "register");
	}
	else
	{
		$objRegisterForm->strAction = "?menu=register";
		$objRegisterForm->insertKat("Benutzerdaten");
		$objRegisterForm->insertInput("Benutzername", "name='username' size=20 type='text'");
		$objRegisterForm->insertInput("Passwort", "name='password1' size=20 type='password'");
		$objRegisterForm->insertInput("Wiederholung", "name='password2' size=20 type='password'");
		$objRegisterForm->insertKat("Pers&ouml;nliche Daten");
		$objRegisterForm->insertDoubleInput("Name", "name='name' type='text' size=15", "name='surname' type='text' size=15");
		$objRegisterForm->insertInput("EMail-Adresse", "name='email' size=25 type=text");
		$objRegisterForm->insertBlank();
		$objRegisterForm->insertSubmit("anmelden", "register");
	}
	
	/* add the formular to the CO */
	$objStdCO->addContent( $objRegisterForm->generateForm() );
}


/* display messages */
if( count($aryMessages) != 0)
{
	$objStdCO->addContent( generateMessages(&$aryMessages) );
}

/* output */
$strHTML .= $objStdCO->generateCO();
echo $strHTML;

?>
