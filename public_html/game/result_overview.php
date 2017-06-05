<?php
/* result_overview.php
 * Created on 	Apr 19, 2006
 * Author:		Martin Kiepfer
 */
 
 
/* 
 * User logged in? 
 */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

	/*
	 * includes
	 */	
	require_once("resultDB.inc.php");
	require_once("ContentObjects.inc.php");
	require_once("calculate.inc.php");
	
	/*
	 * Vars
	 */
	$objResultDB = new resultDB();
	$objTippDB = new tippDB();	
	$objUserDB = new userDB();
	$aryMatchIDs = array();
	$objCO = new std_CO();
	$intMax = 6;
	
	$objCO->setHeader("Gesamt&uuml;bersicht");	
	$objCO->mixWidth = "98%";
		
	$aryResults = $objResultDB->getAllResults($intMax);
	$intResultCount = $objResultDB->getResultCount();
	//echo  $intResultCount;	
		
	if($intResultCount >= 1 )
	{		
		
		/* add Page-Split-bar if necessary*/
		$strHTMLPageSplitBar = "";	
		
		if($intResultCount > $intMax)
		{
			if(array_key_exists('offset', $_GET))$intOffset = $_GET['offset'];
			else $intOffset = 0;
			
			$intMaxPages = ceil($intResultCount / $intMax);
    		$intCurPage = round(($intOffset / $intMax) +1 );
    		
    		$strHTMLPageSplitBar .= "<table border=0 align='center' cellspacing=2 cellpadding=2>";
    		$strHTMLPageSplitBar .= "<tr>";
    		$strHTMLPageSplitBar .= "<td>";
    		
    		/* prev button*/
    		if($intOffset)
    		{
		    	/* create url */      
			    $aryGetVars = $_GET;
			    $intPrevOffset = ($intOffset - $intMax); 
			    if($intPrevOffset < 0) $intPrevOffset = 0;
			    $aryGetVars['offset'] = $intPrevOffset;
			    //$aryGetVars['count'] = ($intPrevOffset + $intMax);  
			    $aryGetVars['count'] = $intMax;
			    /* add link */      
			    $strHTMLPageSplitBar .= "<a style='text-decoration:none;' href='?".http_build_query($aryGetVars)."'><img src='pics/prev_arrow_act.png' border=0></a>";
		    }
		    else $strHTMLPageSplitBar .= "<span><img src='pics/prev_arrow_inact.png' border=0></span>";
		    
		    $strHTMLPageSplitBar .= "&nbsp;<span style='color:#46b750; font-weight:bold;'>".$intCurPage."/".$intMaxPages."</span>&nbsp;";
		    
		    /* next button*/		    
		    if( $intResultCount > ($intOffset + $intMax) )
		    {
			    /* create url */      
			    $aryGetVars = $_GET;
			    $intPrevOffset = ($intOffset + $intMax); 
			    $aryGetVars['offset'] = $intPrevOffset;
			    //$aryGetVars['count'] = ($intPrevOffset + (2*$intMax));  
			    $aryGetVars['count'] = $intMax;
			    /* add link */      
			    $strHTMLPageSplitBar .= "<a style='text-decoration:none;' href='?".http_build_query($aryGetVars)."'><img src='pics/next_arrow_act.png' border=0></a>";
		    }
		    else $strHTMLPageSplitBar .= "<span><img src='pics/next_arrow_inact.png' border=0></span>";
    		
    		$strHTMLPageSplitBar .= "</td>";
    		$strHTMLPageSplitBar .= "</tr>";
    		$strHTMLPageSplitBar .= "</table>";	
		}
		
		
		/*
		 * create Table
	 	*/
	 	
	 	/* header */			
		$aryHeaders['rank'] = "Rang";
		$aryHeaders['username'] = "Name";
		
		$intCount = $intOffset + 1;
		foreach($aryResults AS $aryCurResult)
		{				
			array_push($aryMatchIDs, $aryCurResult['matchID']);
			$aryHeaders['spiel'.$aryCurResult['matchID']] = "<span style='font-size:xx-small;'>Spiel ".$aryCurResult['matchID']."<br/></span>";
			$aryHeaders['spiel'.$aryCurResult['matchID']] .= "<a style='color:#000000;' href='?menu=tipresult&matchID=".$aryCurResult['matchID']."'>".$aryCurResult['team1ID']." - ".$aryCurResult['team2ID']."</a>";			
			$aryHeaders['spiel'.$aryCurResult['matchID']] .= "<br/><i>".$aryCurResult['s_team1']." : ".$aryCurResult['s_team2']."</i>";
			if( $aryCurResult['sd']==1 )$aryHeaders['spiel'.$aryCurResult['matchID']] .= "&nbsp;<i>(V)</i>";
			if( $aryCurResult['sd']==2 )$aryHeaders['spiel'.$aryCurResult['matchID']] .= "&nbsp;<i>(E)</i>";
			$intCount++;			
			$arySDs[$aryCurResult['matchID']] = $aryCurResult['sd'];				
		}
		$aryHeaders['score'] = "Punkte";
		
		/* Table */		
		$objTable = new table($aryHeaders, "rank", 50, FALSE);
		$objTable->mixWidth = "100%";
		$objTable->strColStyle .= "font-size:xx-small; height:30px;";
		$objTable->enableToggledRowStyle();		
		$objTable->intCellpadding = 1;
		$objTable->enableSorting();
		
		
		$aryAllTipResults = $objResultDB->getAllTipResults($aryMatchIDs);		
		//print_r($aryAllTipResults);
		
		foreach($aryAllTipResults AS $aryUserTipResult)
		{
			$intScore = 0;
			$aryRow = array();
			
			foreach($aryUserTipResult AS $aryCurUserTipResult)
			{
				$aryRow['rank'] = $aryCurUserTipResult['rank'];
				$aryRow['username'] = $aryCurUserTipResult['username'];								
				$intCurScore =  $aryCurUserTipResult['score'];
				//calculateScore(array($aryCurUserTipResult['s_team1'], $aryCurUserTipResult['s_team2']), array($aryCurUserTipResult['rs_team1'], $aryCurUserTipResult['rs_team2']), $arySDs[$aryCurUserTipResult['matchID']], $aryCurUserTipResult['usersd']);						
				
				/* Tips */
				
				/* game tipped? */  
				if( ($aryCurUserTipResult['s_team1'] == NULL) OR ($aryCurUserTipResult['s_team2'] == NULL) OR ($aryCurUserTipResult['s_team1'] == "") OR ($aryCurUserTipResult['s_team2'] == "") )
				{
					$strTip = "-";
				}
				else
				{	
					$strTip = $aryCurUserTipResult['s_team1']." : ".$aryCurUserTipResult['s_team2'];
					//if( $aryCurUserTipResult['usersd'] )$strTip .= "&nbsp;<i></i>"; /* not needed anymore (no SD) */				
					$strTip .= "<br/><i>(".$intCurScore.")</i>";						
				}			
				
				
				/* add the row */
				if( ($aryCurUserTipResult['matchID'] == NULL) OR ($aryCurUserTipResult['matchID'] == "") )
				{
					array_push($aryRow, "-");
				}
				else
				{
					$aryRow[$aryCurUserTipResult['matchID']] = $strTip;
				}
				
				/* update Score */				
				$intScore += $intCurScore;			
				
			}
			//$aryRow['score'] = $intScore;
			$aryRow['score'] = $intScore." / ".$aryCurUserTipResult['alloverscore'];
						
			usercustomRow($intUserID, $aryCurUserTipResult['userID'], $aryRow);
			$objTable->insertRow($aryRow);
			
		}
				
		/* add table to CO */		 
		$objCO->addContent( $strHTMLPageSplitBar.$objTable->generateTable().$strHTMLPageSplitBar );
		
	}
	else
	{
		$objCO->addContent("Es sind noch keine Ergebnisse verf&uuml;gbar");
	}
	
	$strHTML .= $objCO->generateCO();
	echo $strHTML;	
}
else echo "<center class='alert'>Session invalid!</center>";

?>
