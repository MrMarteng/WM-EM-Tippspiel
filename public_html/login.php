<?php
/* includes */
require_once("inc/ContentObjects.inc.php");
require_once("inc/tippDB.inc.php");

/* functions */
function failedLogins()
{
	/* first time login failed */
	if(!session_is_registered('loginfails'))
	{
		session_register('loginfails');
		$_SESSION['loginfails'] = 0;
	}
	
	return (++$_SESSION['loginfails']);
	
}

/* Vars */
$objCO = new boxed_CO();
$objForm = new formular("login", "?");
$strHTML = "";
$strForm = "";
$aryMessages = array();
$intFailedCount = 0;
$intMaxFails = 3;

$objCO->mixWidth = "50%";

/* analyze userdata */
if( array_key_exists('login', $_POST) )
{
	if( $_POST['password'] == "")array_push($aryMessages, "Sie muessen ein Passwort eingeben!");
	if( $_POST['username'] == "")array_push($aryMessages, "Sie muessen ein Benutzernamen eingeben!");
	
	if( count($aryMessages) == 0 )
	{
		/* formular seems to be correct -> add user */
		$objUserDB = new userDB();
		$intUserID = $objUserDB->getUserIDbyName($_POST['username']);
		if( $intUserID == 0 )
		{
			array_push($aryMessages, "Dieser Benutzer existiert nicht!");
		}
		elseif( $objUserDB->userLocked($intUserID) )
		{
			array_push($aryMessages, "Dieses Benutzerkonto ist leider gesperrt!");
		}
		else
		{
			if( $objUserDB->authUser($intUserID, md5($_POST['password'])) == 1 )
			{
				$objSession->authUser($intUserID);
				//echo "<script type='text/javascript'>window.location.href=\"index.php?menu=tipoverview&view=next5\"</script>";
				echo "<script type='text/javascript'>window.location.href=\"index.php?menu=home\"</script>";
			}
			else 
			{
				$strPasswdMessage = "falsches Passwort! <br>";
				if( ($intFailedCount = failedLogins()) >= $intMaxFails)
				{
					/* lock useraccount*/ 
					$strPasswdMessage .= "Sie haben das Passwort zum ".$intMaxFails.". mal falsch eingegeben. "; 
					$strPasswdMessage .= "Dieses Benutzerkonto wurde hiermit deaktiviert!";
					$strPasswdMessage .= "<br>Bitte setzen Sie sich mit dem Administrator in Verbindung";
					$objUserDB->lockUser($intUserID);
				}
				else
				{	
					$strPasswdMessage .= "Bleibende Fehlversuche: <i>".($intMaxFails-$intFailedCount)."</i>";
				}
				array_push($aryMessages, $strPasswdMessage);
			}
		}
		$objUserDB->disconnect();	
	}
}

/* Error? */



/* create Login-form */
$objForm->setAction("?menu=login");
if(array_key_exists("username", $_POST)) $objForm->insertinput("Benutzername", "name='username' value='".$_POST['username']."' type='text' size=20");
else $objForm->insertinput("Benutzername", "name='username' type='text' size=20");
$objForm->insertinput("Passwort", "name='password' type='password' size=20");
$objForm->insertSubmit("einloggen", "login");


/* create CO */
$objCO->setHeader("Einloggen");
$objCO->intTextWidth = 80;
$objCO->addContent($objForm->generateForm());

/* add messages */
if( array_count_values($aryMessages) != 0)
{
	$objCO->addContent( generateMessages(&$aryMessages) );
}

/* output */
$strHTML .= $objCO->generateCO();
echo $strHTML;





?>