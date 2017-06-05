<?php
/* special.php
 * Created on 	Jun 6, 2006
 * Author:		Martin Kiepfer
 */
 
 /* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

/* inludes */
require_once("inc/ContentObjects.inc.php");
require_once("inc/tippDB.inc.php");

/* vars */
$strHTML = "";
$objMatchDB = new matchDB();


/************************************/
/* Display Form only if not started */
/************************************/
//if( !$objMatchDB->getPastMatches(1)  )
if( FALSE  )
{

	/*****************************/
	/* Some form data available? */
	/*****************************/
	if(array_key_exists("wm1", $_POST))
	{
	  $aryErrors = array();  
	  $intUserID = $objSession->getUserID();
	  $strWM1TeamID = $_POST['wm1'];
	  $strWM2TeamID = $_POST['wm2'];
	  $strWM3TeamID = $_POST['wm3'];
	  $objMatchDB = new matchDB();
	    
	  /* check formvalues */
	  if( $strWM1TeamID == $strWM2TeamID OR $strWM1TeamID == $strWM3TeamID OR $strWM2TeamID == $strWM3TeamID)
	  {
		array_push($aryErrors, "So geht das leider nicht! :) ");	  
	  }
	
		  
	  
	  if(!count($aryErrors))
	  {
	  	  $blnDBError = FALSE;
	  	  
	  	  /* WM1 */
		  if($strWM1TeamID != "")
		  {
		  	if( !$objMatchDB->setSpecialTip(1, $intUserID, $strWM1TeamID) ) $blnDBError = TRUE;
		  }
		  else
		  {
		  	array_push($aryErrors, "Sie haben noch keinen Weltmeister-Tipp abgegeben! ");	
		  }		  	
		  
		  /* WM1 */
		  if($strWM2TeamID != "")
		  {
		  	if( !$objMatchDB->setSpecialTip(2, $intUserID, $strWM2TeamID) ) $blnDBError = TRUE;
		  }
		  else
		  {
		  	array_push($aryErrors, "Sie haben noch keinen Vize-Weltmeister-Tipp abgegeben! ");	
		  }
		  
		  /* WM3 */
		  if($strWM3TeamID != "")
		  {
		  	if( !$objMatchDB->setSpecialTip(3, $intUserID, $strWM3TeamID) ) $blnDBError = TRUE;
		  }
		  else
		  {
		  	array_push($aryErrors, "Sie haben noch keinen Tipp f&uuml;r den Dritten abgegeben! ");	
		  }
		  
		  /* any errors? */
		  if(!$blnDBErrors)
		  {
		  	array_push($aryErrors, "Tipp(s) wurde(n) erfolgreich &uuml;bernommen!");
		  }
	  }	  
	   
	  	    
	}
	
	
	/* Vars */
	$intMatchID = $_GET['matchID'];
	$intUserID = $objSession->getUserID();
	$objCO = new boxed_CO();
	$strHTMLTippform = "";
	$aryRowTargets = array();
	 
	$objCO->setHeader("Spezialtipps");
	$objCO->mixWidth = "50%";
	  
	$aryMatch = $objMatchDB->getMatchByMatchID($intMatchID);
	$aryTeams = $objMatchDB->getAllTeams();
	  
	/* if tip allreay set */ 
	if( $objMatchDB->matchTipped($intMatchID, $intUserID) )
	{
	  $aryTip = $objMatchDB->getTip($intMatchID, $intUserID);    
	}
	
	/* generate Form */
	$strHTMLTippform .= "<form method='POST' action='?menu=special'>";
	$strHTMLTippform .= "<table align='center' style='padding-top:5px;' cellspacing=2 cellpadding=2 border=0>";
	
	
	$arySpecialTipps = array(	 
								 1	=> "Weltmeister"
								,2	=> "Vize-Weltmeister"
								,3	=> "(undankbarer) Dritter"
							);
	foreach($arySpecialTipps AS $intWM => $strSpecialTipName)
	{
		/* world campionchip winner */   
		$strHTMLTippform .= "<tr>";
		$strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'>$strSpecialTipName</td>";
		$strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'>&nbsp;:&nbsp;</td>";
		$strHTMLTippform .= "<td style='text-align:center;'>";	
		$strHTMLTippform .= "<select name='wm".$intWM."'>";
		if( ! $arySpecialTip = $objMatchDB->getSpecialTip($intWM, $intUserID) )
		{
			$strHTMLTippform .= "<option value=''>Bitte w&auml;hlen</option>";
		}
		foreach($aryTeams AS $aryCurTeam)
		{
					
			$strHTMLTippform .= "<option value='".$aryCurTeam['teamID']."' ";
			if($arySpecialTip['teamID'] == $aryCurTeam['teamID'])$strHTMLTippform .= "selected";
			$strHTMLTippform .= ">".$aryCurTeam['name']."</option>";
		}
		$strHTMLTippform .= "</select>";
		$strHTMLTippform .= "</td>";
		$strHTMLTippform .= "</tr>";
	}   

	
	$strHTMLTippform .= "<tr><td colspan=3>&nbsp;</td></tr>";
	$strHTMLTippform .= "<tr><td style='text-align:center;' colspan=3><input type='submit' value='Tipp abgeben' name='tip'></td></tr>";
	$strHTMLTippform .= "</table>";
	$strHTMLTippform .= "</form>";
  
	$objMatchDB->disconnect();
	
	$objCO->addContent($strHTMLTippform);
	$strHTML .= $objCO->generateCO();
	  
	/* Display Messages*/
	if(count($aryErrors)) $strHTML .= generateMessages($aryErrors);  
	  
	/* back button */
	$strHTML .= "<center><a href='".$objSession->getVar('refurl')."'>zur&uuml;ck</a></center>";
}
else
{
	/*************************
	 * Display SpecialResults
	 *************************/
	 
	/* Vars */	 
	$objUserDB = new userDB();
	$arySpecialTipResults = $objMatchDB->getAllSpecialResults();
	//print_r($arySpecialTipResults);

	$objSpecial = new std_CO();
	$objSpecial->setHeader("Specialtipps");
	//$objSpecial->mixWidth = "80%";	
	/* Create Table */
	$aryHeader['username'] = "Benutzer";
	$aryHeader['wm1'] = "Weltmeister";
	$aryHeader['wm2'] = "Vize-Weltmeister";
	$aryHeader['wm3'] = "Dritter";
	$aryHeader['score'] = "Punkte";
	
	$objTable = new table($aryHeader, 'username', 99, FALSE);
	$objTable->enableToggledRowStyle();
	$objTable->enableSorting();
	$objTable->mixWidth="";
	
	foreach($arySpecialTipResults AS $aryCurSpecialTipResult)
	{		
		$aryCurRow = array();
		
		$aryCurRow['username'] = $aryCurSpecialTipResult['username'];
		$aryCurRow['wm1'] = $aryCurSpecialTipResult['wm1'];
		$aryCurRow['wm2'] = $aryCurSpecialTipResult['wm2'];
		$aryCurRow['wm3'] = $aryCurSpecialTipResult['wm3'];
		$aryCurRow['score'] = $aryCurSpecialTipResult['score'];
		
		$objTable->insertRow($aryCurRow);
	}
	
	$objSpecial->addContent( $objTable->generateTable() );
	$strHTML .= $objSpecial->generateCO();
	
	
}


//$strHTML .= "<script type='text/javascript'>window.location.href=\"".$objSession->getVar('refurl')."\"</script>";

/* output */
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";
 
 
?>
