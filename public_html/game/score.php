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
$objCO->setHeader("Rangliste");


/* Create Table */
$aryHeader['rank'] = "Rang";
$aryHeader['username'] = "Benutzername";
$aryHeader['score'] = "Punkte";
$objTable = new table($aryHeader, 'rank', 99, TRUE);
$objTable->mixWidth="";
$objTable->enableToggledRowStyle();

/* Get User Scores */
$objUserDB = new userDB("rank", "ASC");
//$objUserDB->strOrderBy = "rank";
//$objUserDB->strOrder = "ASC";
$aryUserData = $objUserDB->getAllUserData();
foreach( $aryUserData AS $aryCurUserData)
{
  //print_r($aryUserData);
  $aryTableContent = array($aryCurUserData['rank'], $aryCurUserData['username'], $aryCurUserData['score']);
  $objTable->insertRow($aryTableContent);  
}
$objTable->addPageSplitBar();
$objUserDB->disconnect();

/* Add Table to CO */
$objCO->addContent( $objTable->generateTable() );



/* output */
$strHTML .= $objCO->generateCO();
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";

?>
