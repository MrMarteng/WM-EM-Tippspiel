<?php

/* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

/* inludes */
require_once("ContentObjects.inc.php");
require_once("tippDB.inc.php");
require_once("matchTables.inc.php");
require_once("resultDB.inc.php");

/* vars */
$strHTML = "";


/***************************************************/
/* Only Display the Form, when a matchID is passed */
/***************************************************/
if(array_key_exists(matchID, $_GET))
{
	$intMatchID = $_GET['matchID'];
	
	/* MachtResult */
	$objResult = new boxed_CO();
	$objResult->setHeader("Ergebnis");
	$objResult->mixWidth = "50%";
	$objMatchDB = new matchDB();
	$objResultDB = new resultDB();
	
	
	$aryMatchData = $objMatchDB->getMatchByMatchID($intMatchID);
	$aryResult = $objResultDB->getResult($intMatchID);	
	$objResult->addContent( generateResultMatches($aryMatchData['team1'], $aryMatchData['flagpic1'], $aryMatchData['team2'], $aryMatchData['flagpic2'], $aryResult['rs_team1'], $aryResult['rs_team2'], $aryResult['sd']) );
	$strHTML .=  $objResult->generateCO();
	
	/* add result overview */
	generateTipResultTable($intMatchID, &$strHTML);
	
	$strHTML .= "<br/><center><a href='".$objSession->getVar('refurl')."'>OK</a></center>";
}
else
{
  $strHTML .= "Kein Spiel ausgew&auml;hlt!";
}

/* Display Messages*/
if(count($aryErrors)) $strHTML .= generateMessages($aryErrors);    
//$strHTML .= "<script type='text/javascript'>window.location.href=\"".$objSession->getVar('refurl')."\"</script>";

/* output */
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";

?>
