<?php

/* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

/* inludes */
require_once("ContentObjects.inc.php");
require_once("tippDB.inc.php");
require_once("vars.inc.php");
require_once("matchTables.inc.php");
require_once("resultDB.inc.php");

/* Vars */
$strHTML = "";




/**************/
/* selectForm */
/**************/

$objSelectForm =  new formular("selectview", "?");
$objSelectForm->strMethod = "GET";
$strSelectedKey = "next";
$arySlectItems = array("next" => "anstehende Spiele", "lastmatches" => "beendete Spiele" );
$objSelectForm->insertHidden("menu", "tipoverview");
$objSelectForm->insertSingleSelection("view", $arySlectItems, $strSelectedKey, "TRUE");
$objSelectForm->insertSubmit("&uuml;bernehmen", "submit");
$strSelectForm = $objSelectForm->generateForm();

$objStdCO = new boxed_CO();
$objStdCO->setHeader("Auswahl");
$objStdCO->mixWidth = "40%";
$objStdCO->intTextWidth = 80;
$objStdCO->addContent($strSelectForm);
$strHTML .=  $objStdCO->generateCO();


/*************************************/
/* show next 5 matches or last games */
/*************************************/
$strMatchTable = "";
//print_r($_POST);
if( @$_GET["view"] == "lastmatches" )
{	
	$strHTMLMatches = "";
	generateMatchTable( "past", $strHTMLMatches , "", $intUserID );	
}
else
{
	$strHTMLMatches = "";
	generateMatchTable( "next", $strHTMLMatches , "", $intUserID );	
}


/* generate CO */
$objStdCO = new std_CO();
$objStdCO->setHeader("Tipp&uuml;bersicht");
$objStdCO->intTextWidth = 180;
$objStdCO->addContent( $strHTMLMatches );
$strHTML .= $objStdCO->generateCO();
echo $strHTML;




} // session valid?
else echo "<center class='alert'>Session invalid!</center>";



?>