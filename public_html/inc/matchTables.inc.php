<?php

function generateMatches($strTeam1, $strFlag1, $strTeam2, $strFlag2)
{
	/* match */
	$strTeams = "<table width='100%' align='center' cellspacing=0 cellpadding=0 border=0 style='margin:0px;'>";
	$strTeams .= "<tr>";
	$strTeams .= "<td width='52%' style='text-align:center; padding-left:2px; font-weight:bold;'><img src='".$strFlag1."' border=0><br/>&nbsp;".$strTeam1."</td>";
	$strTeams .= "<td width='1%' style='text-align:center;'>-</td>";
	$strTeams .= "<td width='56%' style='text-align:center; padding-left:2px;font-weight:bold;'><img src='".$strFlag2."' border=0><br/>&nbsp;".$strTeam2."</td>";
	$strTeams .= "</tr>";
	/*
	$strTeams .= "<tr>";
	$strTeams .= "<td width='54%' style='text-align:center'><img src='".$aryCurrentMatch['flagpic1']."' border=0></td>";
	$strTeams .= "<td width='1%' style='text-align:center'>&nbsp;</td>";
	$strTeams .= "<td width='54%' style='text-align:center'><img src='".$aryCurrentMatch['flagpic2']."' border=0></td>";
	$strTeams .= "</tr>";*/
	$strTeams .= "</table>";
	
	return $strTeams;
}

function generateResultMatches($strTeam1, $strFlag1, $strTeam2, $strFlag2, $intTeam1, $intTeam2, $intSD)
{
	/* match */
	$strTeams = "<table width='100%' align='center' cellspacing=0 cellpadding=0 border=0 style='margin:0px;'>";
	$strTeams .= "<tr>";
	$strTeams .= "<td width='52%' style='text-align:center; padding-left:2px; font-weight:bold;'><img src='".$strFlag1."' border=0><br/>&nbsp;".$strTeam1."</td>";
	$strTeams .= "<td width='1%' style='text-align:center;'>-</td>";
	$strTeams .= "<td width='56%' style='text-align:center; padding-left:2px;font-weight:bold;'><img src='".$strFlag2."' border=0><br/>&nbsp;".$strTeam2."</td>";
	$strTeams .= "</tr>";
	$strTeams .= "<tr>";
	$strTeams .= "<td  style='text-align:center;'><b>".$intTeam1."</b></td>";
	$strTeams .= "<td>:</td>";
	$strTeams .= "<td  style='text-align:center;'><b>".$intTeam2."</b></td>";
	$strTeams .= "</tr>";
	if($intSD == 1)
	{
		$strTeams .= "<tr>";
		$strTeams .= "<td colspan=3 style='text-align:center;'><i>(Nach Verl&auml;ngerung)</i></td>";		
		$strTeams .= "</tr>";
	}
	if($intSD == 2)
	{
		$strTeams .= "<tr>";
		$strTeams .= "<td colspan=3 style='text-align:center;'><i>(Nach Elfmeterschie&szlig;en)</i></td>";		
		$strTeams .= "</tr>";
	}
	/*$strTeams .= "<tr>";
	$strTeams .= "<td width='54%' style='text-align:center'><img src='".$aryCurrentMatch['flagpic1']."' border=0></td>";
	$strTeams .= "<td width='1%' style='text-align:center'>&nbsp;</td>";
	$strTeams .= "<td width='54%' style='text-align:center'><img src='".$aryCurrentMatch['flagpic2']."' border=0></td>";
	$strTeams .= "</tr>";*/
	$strTeams .= "</table>";
	
	return $strTeams;
}


function generateGroupMatches( & $aryPreMatchGroups, & $strHTMLGroupMatches, $intUserID )
{	
	/*****************/
	/* Group Matches */
	/*****************/
	require_once("tippDB.inc.php");
	$objMatchDB = new matchDB();	
	
	$strHTMLGroupMatches = "\n<table align='center' border=0 width='100%' cellspacing=2 cellpadding=2>\n";
	foreach( $aryPreMatchGroups AS $strCurrentGroup)
	{
		$strHTMLCurGroupMatches = "";
		
		/****************
		 * GroupMatches  
		 ***************/
		$strHTMLGroupMatches .= "<tr><td class='filled_header'><b>Gruppe ".$strCurrentGroup."</b></td></tr>\n";		
		GenerateMatchTable( "VR", $strHTMLCurGroupMatches , $strCurrentGroup, $intUserID );		
		$strHTMLGroupMatches .= "<tr><td>\n\n".$strHTMLCurGroupMatches."\n\n</td></tr>\n";;
			  
		
		/***************************
		 * preliminary round table 
		 **************************/
		 // FUCK MYSQL 4.0		
		
		$aryGroupData = $objMatchDB->getGroupTable($strCurrentGroup);		
		//$aryGroupRanking = $objMatchDB->getGroupRanking($strCurrentGroup);
		$strHTMLGroupMatches .= "<tr><td>".generateGroupTable($aryGroupData)."</td></tr>\n";
		
		
		/* free line */
		$strHTMLGroupMatches .= "<tr><td>&nbsp;</td></tr>\n";
	}
	$strHTMLGroupMatches .= "</table>\n";	
	//unset($objMatchDB);
}


function generateTipResultTable( $intMatchID, &$strHTML )
{
	require_once("calculate.inc.php");
	require_once("resultDB.inc.php");
	require_once("tippDB.inc.php");
	
	/* Vars */
	$objResultDB = new resultDB();
	$objMatchDB = new matchDB();	
	$objSession = new userSession();
	$intUserID = $objSession->getUserID();
	$aryWinners = array();
	$aryWinners = $objResultDB->getTipWinners($intMatchID);
	
	/* preapre DB-query */	
	$aryTipResults = $objResultDB->getTipResultStats($intMatchID);
				
	  
		
	/* fill table */			
	if($objResultDB->resultAvailable($intMatchID))
	{
		$aryWinners = $objResultDB->getTipWinners($intMatchID);	
		/* preapre DB-query */	
		$aryTipResults = $objResultDB->getTipResultStats($intMatchID);
	
		/* headers */
		$aryHeaders['rank'] = "Rang";	
		$aryHeaders['username'] = "Spieler";
		$aryHeaders['s_team1'] = "Tipp";
		$aryHeaders['score'] = "Punkte";
		
		/* create table */
		$objTable = new Table($aryHeaders, 'score', 40, 0);
		$objTable->strAlign = "center";
		$objTable->intCellpadding = 1;
		$objTable->mixWidth = "60%";
		$objTable->enableSorting();
		$objTable->enableToggledRowStyle();
		  	
		foreach($aryTipResults AS $aryCurTipResult)
		{
			
			//$intScore = calculateScore(array($aryCurTipResult['s_team1'], $aryCurTipResult['s_team2']), array($aryCurTipResult['rs_team1'], $aryCurTipResult['rs_team2']), $aryCurTipResult['sd'], $aryCurTipResult['usersd']);
			$intScore = $aryCurTipResult['score'];
			
			if(@in_array($aryCurTipResult['userID'], $aryWinners))
			{
				$intRank = "<b>".$aryCurTipResult['rank']."</b>";			
				$strUsername = "<b>".$aryCurTipResult['username']."</b>";
				$strTipResult = "<b>".$aryCurTipResult['s_team1']." : ".$aryCurTipResult['s_team2']."</b>";
				$strScore = "<b>".$intScore."</b>";		 
			}
			else
			{
				$intRank = $aryCurTipResult['rank'];
				$strUsername = $aryCurTipResult['username'];	
				$strTipResult = $aryCurTipResult['s_team1']." : ".$aryCurTipResult['s_team2'];
				$strScore = $intScore;
			}
					
			$intPrevScore = $intScore;
		
			/* fill table */
			$aryTableContent[0] = $intRank;
			$aryTableContent[1] = $strUsername;
			$aryTableContent[2] = $strTipResult;
			$aryTableContent[3] = $strScore;						
			
			/* add row */
			usercustomRow($intUserID, $aryCurTipResult['userID'], &$aryTableContent);
			$objTable->insertRow($aryTableContent);
		}
		
		/* output table */
		$strHTML .= $objTable->generateTable();
	}
	elseif(!$objMatchDB->matchTippable($intMatchID))
	{
		
		$aryTips = $objMatchDB->getAllTips($intMatchID);
		
		/* headers */
		$aryHeaders['rank'] = "Rang";	
		$aryHeaders['username'] = "Spieler";
		$aryHeaders['s_team1'] = "Tipp";
		$aryHeaders['goaldiff'] = "Tordiff.";
		//$aryHeaders['score'] = "Punkte";
		
		/* create table */
		$objTable = new Table($aryHeaders, 'score', 40, 0);
		$objTable->strAlign = "center";
		$objTable->intCellpadding = 1;
		$objTable->mixWidth = "60%";
		$objTable->enableSorting();
		$objTable->enableToggledRowStyle();
		
		foreach($aryTips AS $aryCurTip)
		{
			$intRank = $aryCurTip['rank'];
			$strUsername = $aryCurTip['username'];
			$strTip = $aryCurTip['s_team1']." : ".$aryCurTip['s_team2'];
			$intGoaldiff = $aryCurTip['goaldiff'];
			
			$aryRow = array("rank"=>$intRank, "username"=> $strUsername, "s_team1" => $strTip, "goaldif" => $intGoaldiff);
			usercustomRow($intUserID, $aryCurTip['userID'], &$aryRow);
			$objTable->insertRow($aryRow);
		}
		/* output table */
		$strHTML .= $objTable->generateTable();
		  	
	}
	else
	{
		$strHTML .= "<br/><center class='alert'>Die Tippergebnisse f&uuml;r dieses Spiel d&uuml;rfen noch nich eingesehen werden</center>";
	}
	
	
	
}

function generateGroupTable(&$aryGroupData)
{		
	
	/* Table Headers */
	$aryHeaders['rank'] = "Rang";	
	$aryHeaders['team'] = "Mannschaft";	
	$aryHeaders['score'] = "Punkte";
	$aryHeaders['goals'] = "Tore";
				  
	
	$objTable = new Table($aryHeaders, NULL, 40, 0);
	$objTable->strAlign = "center";
	$objTable->intCellpadding = 3;
	$objTable->mixWidth = "50%";	
	$objTable->defineColAlignments(array("rank"=>"center", "team"=>"left", "score"=>"center", "goals"=>"center"));
	$objTable->enableToggledRowStyle();	   
	 
	foreach( $aryGroupData AS $strTeam => $aryCurGroupData )
	{
		$aryRowContent = array();		
		$aryRowContent['rank'] = $aryCurGroupData['rank'];
		$aryRowContent['team'] = "<img src='".$aryCurGroupData['flagpic']."' border=0>&nbsp;<b>".$aryCurGroupData['teamname']."</b>";
		$aryRowContent['score'] = $aryCurGroupData['score'];
		$aryRowContent['goals'] = $aryCurGroupData['goals']." : ".$aryCurGroupData['vsgoals'];		
		
		/*insert Row*/
		$objTable->insertRow($aryRowContent);     
	}
	
	$strHTMLMatchTable = "\n<table align='center' border=0 width='100%' cellspacing=2 cellpadding=2>\n";
	$strHTMLMatchTable .= "<tr><td>\n\n".$objTable->generateTable()."\n\n</td></tr>\n";
	$strHTMLMatchTable .= "<tr><td>&nbsp;</td></tr>\n";
	$strHTMLMatchTable .= "</table>\n";	
	
	return $strHTMLMatchTable;
}


function generateMatchTable( $strMatchType , & $strHTMLMatchTable, $strCurrentGroup, $intUserID )
{
	require_once("resultDB.inc.php");
	
	$aryRowTargets = array();
	$objMatchDB = new matchDB();
	$objResultDB = new resultDB();	
	$aryUserData = array();
	$aryMatches = array();		
	
	if($strMatchType == "VR")$aryMatches = $objMatchDB->getMatchesByGroup($strCurrentGroup, 'date', 'ASC');  
	elseif($strMatchType == "next")$aryMatches = $objMatchDB->getNextMatches(8);
	elseif($strMatchType == "past")$aryMatches = $objMatchDB->getPastMatches(8);
	else $aryMatches = $objMatchDB->getMatches("date", "ASC", $strMatchType);		
		
	if( $aryMatches )
	{
		/* create table witch matches for the current group */
		$aryHeaders['teams'] = "Mannschaften";
		if($strMatchType == "past" OR $strMatchType == "next")$aryHeaders['game'] = "Spiel";
		$aryHeaders['date'] = "Datum";
		//$aryHeaders['location'] = "Austragunsort";
		$aryHeaders['time'] = "Uhrzeit";
		if($strMatchType != "next")
		{
			$aryHeaders['result'] = "Ergebnis";
		}
		$aryHeaders['tip'] = "Mein Tip";
		/* no SD any more
		if( (!deep_in_array("VR", $aryMatches)) AND ($strMatchType != "VR") )
		{				
			$aryHeaders['verl'] = "Verl.";	
		}
		*/		  
		
		$objTable = new Table($aryHeaders, NULL, 40, 0);
		$objTable->strAlign = "center";
		$objTable->intCellpadding = 1;
		$objTable->mixWidth = "100%";
		$objTable->enableToggledRowStyle();    
		$objTable->setColWidth(1, "45%");   
		
		foreach( $aryMatches AS $aryCurrentMatch )
		{
			$intMatchID = $aryCurrentMatch['matchID'];
			
			$strTeams = generateMatches($aryCurrentMatch['team1'], $aryCurrentMatch['flagpic1'], $aryCurrentMatch['team2'], $aryCurrentMatch['flagpic2']);
			
			$strType = $aryCurrentMatch['matchtype'];
			
			$strDate = strftime("%a - %d.%m.%Y",$aryCurrentMatch['date']);    
			
			$strTime = strftime ("%H:%M",$aryCurrentMatch['date']);
			
			/*display result only in past table*/
			if($strMatchType != "next")
			{
				if($aryCurrentMatch['rs_team1'] != "" )
				{
					$strResult = $aryCurrentMatch['rs_team1']." : ".$aryCurrentMatch['rs_team2'];
					if($aryCurrentMatch['sd'] == 1) $strResult .= "&nbsp;<i>(V)</i>";	
					if($aryCurrentMatch['sd'] == 2) $strResult .= "&nbsp;<i>(E)</i>";
				}
				else $strResult = "- : -";
			}
			
			if($aryTip = $objMatchDB->getTip($intMatchID, $intUserID))$strTip = $aryTip['s_team1']." : ".$aryTip['s_team2'];
			else $strTip = "&nbsp;";						
			
			/* generate row */
			$aryRowContent = array();
			array_push($aryRowContent, $strTeams);
			if($strMatchType == "past" OR $strMatchType == "next")array_push($aryRowContent, $strType);
			array_push($aryRowContent, $strDate);
			array_push($aryRowContent, $strTime);
			if($strMatchType != "next")array_push($aryRowContent, $strResult);
			array_push($aryRowContent, $strTip);
			
			/* sudden death game? */
			/*no SD any more
			if( ($strMatchType != "VR") AND ($aryCurrentMatch['matchtype'] != "VR") )
			{				
				$aryHeaders['verl'] = "Verl.";
				if( $aryTip['sd'] == 0 ) $strSd = "&nbsp;";
				else $strSd = "X"; 
				array_push($aryRowContent, $strSd);
			}
			*/
						
			/* add target of current match */
			if( $strMatchType == "past" ) array_push($aryRowTargets, "?menu=tipresult&matchID=".$intMatchID);
			else
			{
				if( $objMatchDB->matchTippable($intMatchID) )array_push($aryRowTargets, "?menu=tip&matchID=".$intMatchID);
				//elseif( $objResultDB->resultAvailable($intMatchID) )array_push($aryRowTargets, "?menu=tipresult&matchID=".$intMatchID);
				//else array_push($aryRowTargets, "");				
				else array_push($aryRowTargets, "?menu=tipresult&matchID=".$intMatchID);			
			}
			
			
			/*insert Row*/
			$objTable->insertRow($aryRowContent);     
		}
		$objTable->setRowTargets($aryRowTargets);
		
		$strHTMLMatchTable = "\n<table align='center' border=0 width='100%' cellspacing=2 cellpadding=2>\n";
		//$strHTMLMatchTable .= "<tr><td class='filled_header'><b>Achtelfinale</b></td></tr>\n";
		$strHTMLMatchTable .= "<tr><td>\n\n".$objTable->generateTable()."\n\n</td></tr>\n";
		$strHTMLMatchTable .= "<tr><td>&nbsp;</td></tr>\n";
		$strHTMLMatchTable .= "</table>\n";	
	}

	//$objMatchDB->disconnect();

}



function deep_in_array($value, $array, $case_insensitive = false)
{
   foreach($array as $item){
       if(is_array($item)) $ret = deep_in_array($value, $item, $case_insensitive);
       else $ret = ($case_insensitive) ? strtolower($item)==$value : $item==$value;
       if($ret)return $ret;
   }
   return false;
}
?>