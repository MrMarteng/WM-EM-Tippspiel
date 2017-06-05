<?php
  	
require_once("tippDB.inc.php");
  	
class resultDB extends tippDB
{

	var $blnFC_getAllResults = TRUE;	
		
	function resultDB()
	{
		$this->connect();
		
		$this->getQueryOptions();
	}	
		
	function getResult($intMatchID)	
	{
	    $strSQL = "SELECT s_team1 AS rs_team1, s_team2 AS rs_team2, sd ";
	    $strSQL .= "FROM results ";
		$strSQL .= "WHERE matchID=".$intMatchID." ";
		$strSQL .= ";";
    	//echo $strSQL;
    
    	if($objResult = mysql_query($strSQL))
    	{
      		return  mysql_fetch_assoc($objResult);
    	}
    	else return 0;
  	}
		
  	function getAllResults($intMax)
  	{  		
  		//if($this->intCount == 0)$this->intCount = $intMax;
  		//echo $this->intCount;
  		
  		$this->getQueryOptions();
  		$this->intCount = $intMax;
  		
  		$aryAllResults = array();
  		
  		/*  		
  		$strSQL = "SELECT r.matchID, r.s_team1, r.s_team2, m.team1ID, m.team2ID ";
		$strSQL .= "FROM results AS r ";
		$strSQL .= "INNER JOIN matches AS m ";
		$strSQL .= "ON m.matchID = r.matchID ";		
		$strSQL .= "ORDER BY m.date ASC ";
		*/
		
		$strSQL = "SELECT r.matchID, r.sd, m.team1ID, m.team2ID, r.s_team1, r.s_team2 ";
		$strSQL .= "FROM results AS r ";
		$strSQL .= "INNER JOIN matches AS m ";
		$strSQL .= "ON m.matchID = r.matchID ";		
		$strSQL .= "ORDER BY m.matchID DESC ";
		$strSQL .= "LIMIT ".$this->intOffset.",".$this->intCount." ";		
		$strSQL .= ";";
  		//echo $strSQL;	
  		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurResult = mysql_fetch_assoc($objResult))
				{
					array_push($aryAllResults, $aryCurResult);
				}
				return $aryAllResults;
			}
			else return 0;
		}
  	}
  	
  	function getResultCount()
  	{
  	    $strSQL = "SELECT COUNT(*) AS resultcount FROM results;";
    	//echo $strSQL;
    
    	if($objResult = mysql_query($strSQL))
    	{
      		$aryResult = mysql_fetch_assoc($objResult);
      		return $aryResult['resultcount'];      		
    	}
    	else return 0;  	
  	}
  	
  	function getTipResultStats($intMatchID)
	{
		if(array_key_exists("orderby",$_GET))
		{
			$this->getQueryOptions();
		}
		else
		{
			$this->strOrderBy = "score DESC, username ASC, s_team1";
			$this->strOrder = "DESC";
		}
		
		
		$aryTipResults = array();
		$strSQL = "SELECT r.matchID, u.username, u.rank, tr.score, u.userID, t.s_team1, t.s_team2, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, r.sd AS sd, t.sd AS usersd, ";
		$strSQL .= "( ABS(ABS(t.s_team1-t.s_team2)-ABS(r.s_team1-r.s_team2)) + ABS(ABS(t.s_team1/t.s_team2)-ABS(r.s_team1/r.s_team2)) ) AS tiprank ";
		$strSQL .= "FROM tips AS t ";
		$strSQL .= "INNER JOIN users AS u ";
		$strSQL .= "ON t.userID = u.userID ";
		$strSQL .= "INNER JOIN results AS r ";
		$strSQL .= "ON r.matchID = t.matchID ";
		$strSQL .= "LEFT JOIN tipresults AS tr ";
		$strSQL .= "ON tr.userID = t.userID AND tr.matchID = t.matchID ";
		$strSQL .= "WHERE r.matchID=".$intMatchID." ";
		$strSQL .= "ORDER BY r.matchID ASC, ".$this->strOrderBy." ".$this->strOrder.";";
		//echo $strSQL;
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTip = mysql_fetch_assoc($objResult))
				{
					array_push($aryTipResults, $aryCurTip);
				}
				return $aryTipResults;
			}
			else return 0;
		}
	}
  	  	
  	function getAllTipResults($aryMatchIDs)
	{
		$aryAllTipResults = array();
		
		$strSQL = "SELECT u.username, u.userID, u.rank, u.score AS alloverscore, tr.score, t.s_team1, t.s_team2, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, r.sd, t.sd AS usersd, r.matchID ";
		$strSQL .= "FROM results AS r ";
		$strSQL .= "INNER JOIN users AS u ON TRUE ";
		$strSQL .= "INNER JOIN matches AS m on r.matchID = m.matchID ";
		$strSQL .= "LEFT OUTER JOIN tips AS t ON t.matchID = r.matchID AND t.userID = u.userID ";
		$strSQL .= "LEFT OUTER JOIN tipresults AS tr ON tr.userID = t.userID AND tr.matchID = t.matchID ";
						
		/*	 
		so tuts in mysql 5.1: 
			SELECT u.username, u.userID, u.rank, u.score AS alloverscore, tr.score, t.s_team1, t.s_team2, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, r.sd, t.sd AS usersd, t.matchID		
			FROM results AS r
			INNER JOIN users AS u ON TRUE
			INNER JOIN matches AS m on r.matchID = m.matchID
			LEFT OUTER JOIN tips AS t ON t.matchID = r.matchID AND t.userID = u.userID
			LEFT OUTER JOIN tipresults AS tr ON tr.userID = t.userID AND tr.matchID = t.matchID
		*/
		
		/* doesn;t work with mysql 5.1		
		$strSQL = "SELECT u.username, u.userID, u.rank, u.score AS alloverscore, tr.score, t.s_team1, t.s_team2, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, r.sd, t.sd AS usersd, t.matchID ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID ";
		$strSQL .= "INNER JOIN tips AS t ";
		$strSQL .= "ON r.matchID = t.matchID ";
		$strSQL .= "RIGHT OUTER JOIN users AS u ";
		$strSQL .= "ON u.userID = t.userID ";	
		$strSQL .= "LEFT JOIN tipresults AS tr ";
		$strSQL .= "ON tr.userID = t.userID AND tr.matchID = t.matchID ";
		*/	
		
		$strSQL .= "WHERE r.matchID IN ( ";
		$intMatchIDCount = count($aryMatchIDs);
		$intCount = 1;
		foreach($aryMatchIDs AS $intMatchID)
		{
			$strSQL .= $intMatchID;
			if($intCount<$intMatchIDCount)$strSQL .= ",";	
			$intCount++;
		}
		$strSQL .= ") ";
		if($this->strOrderBy != "" AND $this->strOrder != "") $strSQL .= "ORDER BY m.Date DESC, ".$this->strOrderBy." ".$this->strOrder." ";
		else $strSQL .= "ORDER BY m.matchID DESC, u.rank, u.username ASC ";
		//$strSQL .= "ORDER BY u.rank, u.username, m.date ASC ";
		//if($this->intCount)$strSQL .= "LIMIT ".$this->intOffset.",".$this->intCount;
		$strSQL .= ";";
		//echo $strSQL;		
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTipResult = mysql_fetch_assoc($objResult))
				{
					if(!array_key_exists($aryCurTipResult['username'], $aryAllTipResults))
					{
						$aryAllTipResults[$aryCurTipResult['username']] = array();
					}
					array_push($aryAllTipResults[$aryCurTipResult['username']],$aryCurTipResult);
				}
				return $aryAllTipResults;
			}
			else return 0;
		}
	}
		
		function resultAvailable($intMatchID)
		{
			$strSQL = "SELECT matchID FROM results WHERE matchID=".$intMatchID.";";
			
			if($objResult = mysql_query($strSQL, $this->objDB))
			{
				return mysql_num_rows($objResult);
			}
			else return 0;
			
		}	
		
  	
  	function TipResultAvailable($intUserID, $intMatchID)
	{
		$strSQL = "SELECT userID FROM tipresults ";		
		$strSQL .= "WHERE userID=".$intUserID." AND matchID=".$intMatchID;		
		if($this->objResult = mysql_query($strSQL, $this->objDB))
		{
			return mysql_num_rows($this->objResult);
		} return 0;
	}
	
	function updateTipScore($intMatchID, $intUserID, $intScore)
	{
		if($this->TipResultAvailable($intUserID, $intMatchID))
		{
			$strSQL = "UPDATE tipresults SET ";
			$strSQL .= "score=".$intScore." ";
			$strSQL .= "WHERE userID=".$intUserID." AND matchID=".$intMatchID.";";	
		}
		else
		{
			$strSQL = "INSERT INTO tipresults ";
			$strSQL .= "(score, userID, matchID) VALUES (".$intScore.", ".$intUserID.", ".$intMatchID.") ;";			
		}
				
		echo $strSQL."<br/>";
		if(mysql_query($strSQL, $this->objDB)) return 0;
		else return "Das Tipresultst konnte nicht geaendert wreden.";	
	}
	
	
	function getTipWinners($intMatchID)
	{
		$aryWinners = array();		
    	$strSQL = "SELECT userID FROM tipresults WHERE matchID=".$intMatchID." AND score=(SELECT MAX(score) FROM tipresults WHERE matchID=".$intMatchID.");";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{
			if(mysql_num_rows($objResult))
			{
				while($aryCurWinner = mysql_fetch_assoc($objResult))
				{
					array_push($aryWinners, $aryCurWinner['userID']);
				}
				return $aryWinners;
			}
			else return 0;			
		}
		else return 0;
	}	
  	
}  	

?>  