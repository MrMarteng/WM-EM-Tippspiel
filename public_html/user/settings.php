<?php

/* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

/* includes */
require_once("inc/tippDB.inc.php");
require_once("inc/ContentObjects.inc.php");


/* Vars */
$objCO = new std_CO();
$strHTML = "";
$aryMessages = array();
$strForm;
$blnChangedPasswd = FALSE;
$blnChangedEMail = FALSE;


/* update password? */
if( array_key_exists('updatePasswd', $_POST) )
{
	$blnChangedPasswd = TRUE;
	if( $_POST['password1'] == "")array_push($aryMessages, "Sie muessen ein Passwort eingeben!");
  elseif( $_POST['password1'] != $_POST['password2'])array_push($aryMessages, "Die Passw&ouml;rter untescheiden sich!");	
	
	if( count($aryMessages) == 0 )
	{
		/* formular seems to be correct -> update password */
		$objUserDB = new userDB();
		$mixDBError = $objUserDB->updatePasswd(md5($_POST['password1']), $intUserID);
		if( ! $mixDBError )
		{
			array_push($aryMessages, "Das Passwort wurde erfolgreich ge&auml;ndert.");
		}
		else
		{
			array_push($aryMessages, $mixDBError);
		}
		$objUserDB->disconnect();	
	}
}

/* update mailaddress */
if( array_key_exists('updateMail', $_POST) )
{
	$blnChangedEMail = TRUE;
		
	$objUserDB = new userDB();
	$mixDBError = $objUserDB->updateEMail($_POST['email'], $intUserID);
	if( ! $mixDBError )
	{
		array_push($aryMessages, "Die EMail-Adresse wurde erfolgreich ge&auml;ndert.");
		}
	else
	{
		array_push($aryMessages, $mixDBError);
	}
	$objUserDB->disconnect();		
}




/* get userdata */
$objUserDB = new userDB();
$aryUserData = $objUserDB->getUserData($intUserID);
$objUserDB->disconnect();	


/* create CO */
$objCO->setHeader("Benutzerkonto");


/* generate formular 1 */
$objRegisterForm = new formular("usersettings", "?menu=settings");
$objRegisterForm->insertKat("Benutzerdaten");
$objRegisterForm->insertProtected("Benutzername", $aryUserData['username']);
$objRegisterForm->insertInput("Passwort", "name='password1' size=20 type='password'");
$objRegisterForm->insertInput("Wiederholung", "name='password2' size=20 type='password'");
$objRegisterForm->insertSubmit("Passwort &uuml;bernehmen", "updatePasswd");
$objCO->addContent( $objRegisterForm->generateForm() );
/* display messages*/
if(count($aryMessages) AND $blnChangedPasswd)$objCO->addContent( generateMessages($aryMessages) );

/* generate formular 2 */
$objRegisterForm = new formular("usersettings", "?menu=settings");
$objRegisterForm->insertKat("Pers&ouml;nliche Daten");
$objRegisterForm->insertProtected("Name", $aryUserData['name']." ".$aryUserData['surname']);
$objRegisterForm->insertInput("EMail-Adresse", "name='email' size=25 type='text' value='".$aryUserData['email']."'");
$objRegisterForm->insertSubmit("Adresse &uuml;bernehmen", "updateMail");
$objCO->addContent( $objRegisterForm->generateForm() );
/* display messages*/
if(count($aryMessages) AND $blnChangedEMail)$objCO->addContent( generateMessages($aryMessages) );


/* output */
$strHTML .= $objCO->generateCO();
echo $strHTML;

} // session valid?
else echo "Session invalid!";

?>
