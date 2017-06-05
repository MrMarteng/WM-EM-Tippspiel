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
$strHTML8Matches = "";
$strHTMLGroupMatches = "";

/*
 * select view
 */
if(array_key_exists("view",$_POST)) $strSelectedView = $_POST['view'];
else 
{
	/* default view */	
	
	$tsToday = strtotime("now");	
	$strSelectedView = "VR";
	foreach($aryMatchtypeDates AS $strCurMatchType => $strCurDate)
	{
		$aryDate = explode("-", $strCurDate);		
		if($tsToday >= mktime(0, 0, 0, $aryDate[1], $aryDate[2], $aryDate[0]))$strSelectedView = $strCurMatchType;
	}		
}



/**************/
/* selectForm */
/**************/
$arySelectValues = $aryAllMatchTypes;
$arySelectValues["ALL"] = "Alle";
$objSelectForm =  new formular("selectview", "?menu=matches");
$objSelectForm->insertSingleSelection("view", $arySelectValues, $strSelectedView, "TRUE");
$objSelectForm->insertSubmit("&uuml;bernehmen", "submit");
$strSelectForm = $objSelectForm->generateForm();
unset($objSelectForm);

$objStdCO = new boxed_CO();
$objStdCO->setHeader("Auswahl");
$objStdCO->mixWidth = "40%";
$objStdCO->intTextWidth = 80;
$objStdCO->addContent($strSelectForm);
$strHTML .=  $objStdCO->generateCO();
unset($objStdCO);



if($strSelectedView == "ALL")
{
	/****************/
	/* Groupmatches */
	/****************/
	$objCO = new std_CO();
	$objCO->setHeader("Gruppenspiele");
	$objCO->mixWidth = "98%";
	GenerateGroupMatches( $aryPreMatchGroups, $strHTMLGroupMatches, $intUserID );
	/* Add GroupMatches to CO */
	$objCO->addContent( $strHTMLGroupMatches );
	$strHTML .= $objCO->generateCO();
	unset($objCO);
	
	
	/*******************/
	/* 8 - FIN matches */
	/*******************/
	foreach($arySecMatchTypes AS $strCurSecMatchTypeKey => $strCurSecMatchType)
	{
		$objCO = new std_CO();
		$objCO->setHeader($strCurSecMatchType);
		$objCO->mixWidth = "98%";
		
		/* get MatchTable*/
		GenerateMatchTable( $strCurSecMatchTypeKey, $strHTMLGroupMatches , NULL, $intUserID );
		
		/* Add 8Matches to CO */
		$objCO->addContent( $strHTMLGroupMatches );
		$strHTML .= $objCO->generateCO();
		unset($objCO);
	}
}
else
{
	if($strSelectedView == "VR")
	{
		/****************/
		/* Groupmatches */
		/****************/
		$objCO = new std_CO();
		$objCO->setHeader($aryAllMatchTypes[$strSelectedView]);
		$objCO->mixWidth = "98%";
		GenerateGroupMatches( $aryPreMatchGroups, $strHTMLGroupMatches, $intUserID );
		/* Add GroupMatches to CO */
		$objCO->addContent( $strHTMLGroupMatches );
		$strHTML .= $objCO->generateCO();
		unset($objCO);
		
	}
	else
	{
		$objCO = new std_CO();
		$objCO->setHeader($aryAllMatchTypes[$strSelectedView]);
		$objCO->mixWidth = "98%";
		
		/* get MatchTable*/
		GenerateMatchTable( $strSelectedView, $strHTMLGroupMatches , NULL, $intUserID );
		
		/* Add 8Matches to CO */
		$objCO->addContent( $strHTMLGroupMatches );		
		$strHTML .= $objCO->generateCO();
		unset($objCO);
	}
}

/* output */
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";

?>
