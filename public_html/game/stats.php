<?php

/* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{


/* inludes */
require_once("inc/ContentObjects.inc.php");
require_once("inc/tippDB.inc.php");

/* Vars */
$objCO = new std_CO();
$strHTML = "";
$aryUserData = array();


/* create CO */
$objCO->setHeader("Statistik");

/* Get User Scores */
$objUserDB = new userDB("rank", "ASC");
//$objUserDB->strOrderBy = "rank";
//$objUserDB->strOrder = "ASC";
$aryUserData = $objUserDB->getAllUserData();
$objUserDB->disconnect();

//$strHTMLTable = "<table border=0 cellspacing=2 cellpadding=2>";
foreach( $aryUserData AS $aryCurUserData)
{
  $objUserCO = new boxed_CO();
  $objUserCO->intTextWidth = 100 ;
  $objUserCO->setHeader($aryCurUserData['rank'].". ".$aryCurUserData['username']);
  $objUserCO->addContent("<img width=500 src='pics/stats/".$aryCurUserData['userID'].".png'>");
  
  $objCO->addContent( $objUserCO->generateCO()."<br/>" );
  unset($objUserCO);
  /*
  $aryTableContent = array($aryCurUserData['rank'], $aryCurUserData['username'], $aryCurUserData['score']);
  $strHTMLTable .= "<tr><td>".$aryCurUserData['username']."</td></tr>";
  $strHTMLTable .= "<tr><td><img width=500 src='pics/stats/".$aryCurUserData['userID'].".png'></td></tr>";
  $strHTMLTable .= "<tr><td>&nbsp;</td></tr>";
  */
}
//$strHTMLTable .= "</table>";


/* Add Table to CO */
//$objCO->addContent( $strHTMLTable );



/* output */
$strHTML .= $objCO->generateCO();
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";

?>
