<?php

class tippDB
{
	/*
	var $strUsername = "tippspiel";
	var $strPassword = "schweini";
	var $strServername = "localhost";
	var $strDatabase = "tippspiel";
	*/
	var $strUsername = "";
	var $strPassword = "";
	var $strServername = "";
	var $strDatabase = "";
	var $objDB;
	var $strOrder = "";
	var $strOrderBy = "";
	var $objResult;  
	var $intCount = 0;
	var	$intOffset = 0;
		
	function connect()
	{
		include("DB.inc.php");
		$this->strDatabase = $strDatabase;
		$this->strUsername = $strUsername;
		$this->strPassword = $strPassword;
		$this->strServername = $strHost;
		
		if( ($this->objDB = mysql_connect($this->strServername, $this->strUsername, $this->strPassword)) )
		{
			if( $this->selectDB($this->strDatabase) )	return 1;
		} 
		else echo mysql_error();
	}
	
	function selectDB($strDB)
	{
		if( mysql_select_db($strDB, $this->objDB) )return 1;
		else echo mysql_error();				
	}
	
	function disconnect()
	{
		return mysql_close($this->objDB);
	}
	
	function getQueryOptions()
	{
		if(func_num_args() == 2)
		{
			$this->strOrderBy = func_get_arg(0);
			$this->strOrder = func_get_arg(1);
		}
		
		/* detec orderby clause */
		if(array_key_exists("orderby", $_GET)) $this->strOrderBy = $_GET['orderby'];		    			
		
		/* detect order */
		if(array_key_exists("order", $_GET))$this->strOrder = $_GET['order'];
		//elseif($this->strOrder == "") $this->strOrder = "ASC";
		
		/* detect offset and limitation */
		if(array_key_exists("count", $_GET))$this->intCount = $_GET['count'];
		
		if(array_key_exists("offset", $_GET))$this->intOffset = $_GET['offset'];
		  	
		
		/* include session */
		require_once("sessionManagement.inc.php");
		$this->objSession = new userSession();    
	}
}


class userDB extends tippDB
{
  
	var $blnFC_getAllUserData = TRUE;	 
	var $objSession;
  
	function userDB()
	{
		/* optional Parameteres */
		if(func_num_args() == 2)
		{
			$this->strOrderBy = func_get_arg(0);
			$this->strOrder = func_get_arg(1);
		}	
		
		$this->connect();	
		
		$this->getQueryOptions();   
			
	}
	
	function addUser($strUsername, $strPassword, $strSurName, $strName, $strEMail)
	{
		$blnError = FALSE;	
		
		if( $this->getUserIDbyName($strUsername) == 0 )
		{
			$strSQL = "INSERT INTO users ";
			$strSQL .= "(username, passwd, surname, name, email) ";
			$strSQL .= "VALUES ('".$strUsername."','".$strPassword."','".$strSurName."','".$strName."','".$strEMail."' );";			
			//echo $strSQL;	 
			
			mysql_query($strSQL, $this->objDB);
			
			$this->updateUserRanking();
					
			if($blnError) return "Fehler beim erstellen des Benutzers";
			else return 0;
			
			
		}
		else return "Dieser Benutzername ist bereits vergeben";
	}
	
	function authUser($intUserID, $strPassword)
	{
		$strSQL = "SELECT passwd FROM users WHERE userID=".$intUserID.";";
		//echo $strSQL;
		$objResult = mysql_query($strSQL, $this->objDB);
		$aryUserData = mysql_fetch_assoc($objResult);
		if( $aryUserData['passwd'] == $strPassword )return 1;
		else return 0;
	}
	
	function getUserIDbyName($strUsername)
	{
		$strSQL = "SELECT userID FROM users WHERE username='".$strUsername."';";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{
			$aryUserData = mysql_fetch_assoc($objResult);
			return $aryUserData['userID'];
		}
		else return 0;
	}
	
	function userLocked($intUserID)
	{
		$strSQL = "SELECT locked FROM users WHERE userID=".$intUserID;
		//echo $strSQL;
		$objResult =  mysql_query($strSQL, $this->objDB);
		$aryUserData = mysql_fetch_array($objResult);		
		return  $aryUserData['locked'];
		
	}
	
	function getUsername($intUserID)
	{
    		$strSQL = "SELECT username FROM users WHERE userID='".$intUserID."';";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{
			$aryUserData = mysql_fetch_assoc($objResult);
			return $aryUserData['username'];
		}
		else return 0;
	}
	
	function lockUser($intUserID)
	{
		$strSQL = "UPDATE users SET ";
		$strSQL .= "locked=1 ";
		$strSQL .= "WHERE userID=".$intUserID;		
		//echo $strSQL;
		mysql_query($strSQL, $this->objDB);		
	}
	
	function getUserData($intUserID)
	{
		$strSQL = "SELECT * FROM users WHERE userID='".$intUserID."';";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{
			$aryUserData = mysql_fetch_assoc($objResult);
			$aryUserData['passwd'] = "";
			return $aryUserData;
		}
		else return 0;
	}
	
	function updatePasswd($strPasswd, $intUserID)
	{
		$strSQL = "UPDATE users SET ";
		$strSQL .= "passwd='".$strPasswd."' ";
		$strSQL .= "WHERE userID=".$intUserID;		
		//echo $strSQL;
		if(mysql_query($strSQL, $this->objDB)) return 0;
		else return "Das Passwort konnte nicht geandert werden.";
	}
	
	function updateScore($intScore, $intUserID)
	{
		$strSQL = "UPDATE users SET ";
		$strSQL .= "score='".$intScore."' ";
		$strSQL .= "WHERE userID=".$intUserID;		
		echo $strSQL."<br/>";
		if(mysql_query($strSQL, $this->objDB)) return 0;
		else return "Das Passwort konnte nicht geandert werden.";
	}
	
	function updateUserRanking()
	{		
		$strSQL = "";		
		$blnError = FALSE;
		$intRank = 0;
		$intPrevScore = 0;
		
		$this->strOrderBy = "score";
		$this->strOrder = "DESC";
		$aryUsers = $this->getAllUserData();							
		foreach($aryUsers AS $aryCurUser)
		{		
			//print_r($aryCurUser);
			
			if($aryCurUser['score'] != $intPrevScore)
			{			
				$intRank++;					
			}				
			$intPrevScore = $aryCurUser['score'];
			
			$strSQL = "UPDATE users SET rank=".$intRank." WHERE userID=".$aryCurUser['userID'].";";
											
			//echo $strSQL."<br/>";			
			
			if(!mysql_query($strSQL))$blnError = TRUE ;
									
		}
		
		if($blnError) return 1;
		else return 0;
			 	
	}
	
	function updateEMail($strEMail, $intUserID)
	{
		$strSQL = "UPDATE users SET ";
		$strSQL .= "email='".$strEMail."' ";
		$strSQL .= "WHERE userID=".$intUserID;		
		//echo $strSQL;
		if(mysql_query($strSQL, $this->objDB)) return 0;
		else return "Die EMail-Adresse konnte nicht geandert werden.";
	}

	function getAllUserData()
	{
		$aryAllUserData = array();
		//$this->getQueryOptions();
		
		if($this->strOrderBy == "")
		{
			$this->strOrderBy = "rank ASC, username";
		} 
		if($this->strOrder == "")
		{
			$this->strOrder == "DESC";
		}		
		
    	$strSQL = "SELECT * FROM users ";
		$strSQL .= "ORDER BY ".$this->strOrderBy." ".$this->strOrder." ";
		if($this->intCount)$strSQL .= "LIMIT ".$this->intOffset.",".$this->intCount;
		$strSQL .= ";";
		
    	//echo $strSQL;
    	$objResult = mysql_query($strSQL); 				
		
		if($objResult)
		{
			/* store rowcount */      
			//if( $this->strOrderBy == "" AND !$this->intCount)
			//{      
				$this->objSession->setVar("rowcount", mysql_num_rows($objResult));
			//}
		
			while( $aryUserData = mysql_fetch_assoc($objResult))
			{			
				$aryUserData['passwd'] = "";
				array_push($aryAllUserData, $aryUserData);				
			}
			return $aryAllUserData;			
		}
		else return 0;
	}  	
	
	function getAlloverScore($intUserID)
	{
		$aryResult = array();
		$strSQL = "SELECT SUM(score) AS alloverscore FROM tipresults WHERE userID=".$intUserID." GROUP BY userID;";
		echo $strSQL."<br/>";
		
		if($objResult = mysql_query($strSQL, $this->objDB))
		{
			$aryResult = mysql_fetch_assoc($objResult);
			return $aryResult['alloverscore'];
		}
		else return 0;
		
	}
 	
  	
}//userDB








class matchDB extends tippDB
{
 
 	var $aryMatchTypes = array();
 	
	function matchDB()
	{
    	$this->connect();    	  	
	}
	
	function getMatchesByGroup($strGroup, $strOrderBy, $strOrder)
	{
    	$aryMatches = array();
		$strSQL = "SELECT m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2 ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID ";    	
    	$strSQL .= "LEFT OUTER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID "; 	
		$strSQL .= "WHERE t1.premgroup='".$strGroup."' AND m.matchtype='VR'";
    	$strSQL .= "ORDER BY ".$strOrderBy." ".$strOrder.";";
		//echo $strSQL."<br/><br/>";
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{
			while($aryCurrentMatch = mysql_fetch_assoc($objResult))
			{
        		array_push($aryMatches, $aryCurrentMatch);
      		}
			return $aryMatches;
		}
		else return 0;
	}
	
	
	function getMatches( $strOrderBy, $strOrder, $strMatchType)
	{
		$aryMatches = array();
		$aryMatchTypes = array( "VR", "8", "4", "2", "3", "FI" );
		$strSQL = "SELECT m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, r.sd ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID "; 
		$strSQL .= "LEFT OUTER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID "; 						
    	if( in_array($strMatchType, $aryMatchTypes) )$strSQL .= "WHERE matchtype='".$strMatchType."' ";
    	$strSQL .= "ORDER BY ".$strOrderBy." ".$strOrder." ";    	
    	$strSQL .= ";";
		//echo $strSQL."<br/>";
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurrentMatch = mysql_fetch_assoc($objResult))
				{
					array_push($aryMatches, $aryCurrentMatch);
				}
				return $aryMatches;
			}
			else return 0;
		}
		else return 0;
	}	
	
	
	
	function getNextMatches( $intCount )
	{
		$aryMatches = array();
		$strSQL = "SELECT m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location, m.team1ID, m.team2ID, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, m.matchtype ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID ";    	    	
		$strSQL .= "LEFT OUTER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID ";  
		$strSQL .= "WHERE date > NOW() ";
		$strSQL .= "OR NOT EXISTS(SELECT matchID FROM results WHERE matchID=m.matchID) ";
    	$strSQL .= "ORDER BY date ASC ";    	
    	$strSQL .= "LIMIT ".$intCount." ";	
    	$strSQL .= ";";	
    	//echo $strSQL;
    	if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurrentMatch = mysql_fetch_assoc($objResult))
				{
					array_push($aryMatches, $aryCurrentMatch);
				}
				return $aryMatches;
			}
			else return 0;
		}
		else return 0;	
	}
	
	function getPastMatches( $intCount )
	{
		$aryMatches = array();
		$strSQL = "SELECT m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location, m.team1ID, m.team2ID, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2, m.matchtype, r.sd ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID ";    	    	
		//$strSQL .= "LEFT OUTER JOIN results AS r ";
		//$strSQL .= "ON r.matchID = m.matchID ";  
		//$strSQL .= "WHERE EXISTS (SELECT matchID FROM results AS r WHERE r.matchID=m.matchID) ";
		$strSQL .= "INNER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID ";
		//$strSQL .= "AND date < CURRENT_DATE ";
    	$strSQL .= "ORDER BY m.date DESC ";    	
    	$strSQL .= "LIMIT ".$intCount." ";	
    	$strSQL .= ";";	
    	//echo $strSQL;
    	if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurrentMatch = mysql_fetch_assoc($objResult))
				{
					array_push($aryMatches, $aryCurrentMatch);
				}
				return $aryMatches;
			}
			else return 0;
		}
		else return 0;	
	}
	
	
	function getMatchByMatchID($intMatchID)
	{
		$strSQL = "SELECT m.matchtype, m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID ";
		$strSQL .= "WHERE m.matchID='".$intMatchID."';";
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{		
			if($aryMatch = mysql_fetch_assoc($objResult))return $aryMatch;
			else return 0;
		}
		else return 0;
	}
	
		
	function get16Matches($strOrderBY, $str)
	{
		$strSQL = "SELECT m.matchID, t1.name AS team1, t2.name AS team2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2, UNIX_TIMESTAMP(m.date) AS date, l.name AS location ";
		$strSQL .= "FROM matches AS m ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON m.team1ID = t1.teamID ";
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON m.team2ID = t2.teamID ";
		$strSQL .= "INNER JOIN locations AS l ";
		$strSQL .= "ON m.locationID = l.locationID ";
    	$strSQL .= "WHERE  ";
    	$strSQL .= "ORDER BY ".$strOrderBy." ".$strOrder.";";
	}
		
	function setTip($intMatchID, $intUserID, $intScoreT1, $intScoreT2, $intSD)
	{
		$strSQL = "INSERT INTO tips (userID, s_team1, s_team2, matchID, sd) VALUES (".$intUserID.", ".$intScoreT1.", ".$intScoreT2.", ".$intMatchID.", ".$intSD.")";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{      
		if (mysql_affected_rows($this->objDB)) return 0; 
		else return mysql_error($this->objDB);
		}
		else return "Incorrect SQL-Statement";   
  	}
	
	function updateTip($intMatchID, $intUserID, $intScoreT1, $intScoreT2, $intSD)
	{
		$strSQL = "UPDATE tips SET s_team1=".$intScoreT1.", s_team2=".$intScoreT2.", sd=".$intSD." WHERE matchID=".$intMatchID." AND userID=".$intUserID.";";
		//echo $strSQL;
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{      
			if (mysql_affected_rows($this->objDB)) return 0; 
			else return mysql_error($this->objDB);
		}
		else return "Incorrect SQL-Statement";   
  	}

	function matchTippable($intMatchID)
	{
	    $strSQL = "SELECT m.date FROM matches AS m WHERE (m.matchID=".$intMatchID.") AND (m.date - INTERVAL 30 MINUTE >= NOW());";
    	//echo $strSQL;
    
    	if($objResult = mysql_query($strSQL))
    	{
      		return  mysql_num_rows($objResult);
    	}
    	else return 0;
  	}
  
  	function matchTipped($intMatchID, $intUserID)
  	{
	    $strSQL = "SELECT matchID FROM tips WHERE matchID=".$intMatchID." AND userID=".$intUserID.";";
	    //echo $strSQL;
    
	    if($objResult = mysql_query($strSQL))
	    {
      		return  mysql_num_rows($objResult);
    	}
    	else return 0;
  	}

	function getTip($intMatchID, $intUserID)
	{
    	$strSQL = "SELECT s_team1, s_team2, sd FROM tips WHERE matchID=".$intMatchID." AND userID=".$intUserID.";";
    	//echo $strSQL;
    	if( $objResult = mysql_query($strSQL, $this->objDB) )
		{		
			if($aryTip = mysql_fetch_assoc($objResult))return $aryTip;
			else return 0;
		}
		else return 0;
	}
	
	function getAllTips($intMatchID)
	{
		$this->getQueryOptions("username", "ASC");
		
		$aryTips = array();
		//$strSQL = "SELECT u.username, t.s_team1, t.s_team2, r.s_team1 AS rs_team1, r.s_team2 AS rs_team2 ";
		$strSQL = "SELECT u.rank, u.userID, u.username, t.s_team1, t.s_team2, (t.s_team1-t.s_team2) AS goaldiff ";
		$strSQL .= "FROM tips AS t ";
		$strSQL .= "INNER JOIN users AS u ";
		$strSQL .= "ON t.userID = u.userID ";
		//$strSQL .= "INNER JOIN results AS r ";
		//$strSQL .= "ON r.matchID = t.matchID ";
		$strSQL .= "WHERE t.matchID=".$intMatchID." ";
		$strSQL .= "ORDER BY ".$this->strOrderBy." ".$this->strOrder." ";
		$strSQL .= ";";
		//echo $strSQL;
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTip = mysql_fetch_assoc($objResult))
				{
					array_push($aryTips, $aryCurTip);
				}
				return $aryTips;
			}
			else return 0;
		}
		else return 0;	
	}

	function getAllTeams()
	{
		$aryTeams = array();
		$strSQL = "SELECT DISTINCT teamID, name ";
		$strSQL .= "FROM teams ";
		$strSQL .= "WHERE premgroup IN('A','B','C','D','E','F','G','H') ";
		$strSQL .= "ORDER BY name ASC ";
		$strSQL .= ";";
		//echo $strSQL;
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTeam = mysql_fetch_assoc($objResult))
				{
					array_push($aryTeams, $aryCurTeam);
				}
				return $aryTeams;
			}
			else return 0;
		}
		else return 0;		
	}


	function getSpecialTip($intSpecialID, $intUserID)
	{
    	$strSQL = "SELECT * FROM special WHERE specID=".$intSpecialID." AND userID=".$intUserID.";";
    	//echo $strSQL;
    	if( $objResult = mysql_query($strSQL, $this->objDB) )
		{		
			if($arySpecialTip = mysql_fetch_assoc($objResult))return $arySpecialTip;
			else return 0;
		}
		else return 0;
	}

	function getAllSpecialResults()
	{
		/* GET vars? */
		if(array_key_exists('orderby', $_GET))
		{
			if($_GET['orderby'] == "wm1")$this->strOrderBy = "specID ASC, teamname";
			elseif($_GET['orderby'] == "wm2")$this->strOrderBy = "antibug, teamname";
			elseif($_GET['orderby'] == "wm3")$this->strOrderBy = "specID DESC, teamname";
			else $this->strOrderBy = $_GET['orderby'];
			$this->strOrder = $_GET['order'];
			
		}
		else
		{
			$this->strOrderBy = "username, specID";
			$this->strOrder = "ASC";
		}
		$aryResults = array();
		$strSQL = "SELECT s.userID, u.username, s.teamID, s.specID, t.name AS teamname, MOD(s.specID,2) AS antibug, s.score ";
		$strSQL .= "FROM special AS s ";
		$strSQL .= "INNER JOIN teams AS t ";
		$strSQL .= "ON s.teamID = t.teamID ";
		$strSQL .= "INNER JOIN users AS u ";
		$strSQL .= "ON s.userID = u.userID ";			
		$strSQL .= "ORDER BY ".$this->strOrderBy." ".$this->strOrder." ";
		$strSQL .= ";";
		//echo $strSQL;
		
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTipResult = mysql_fetch_assoc($objResult))
				{					
					if(!array_key_exists($aryCurTipResult['userID'], $aryResults))
					{
						$aryResults[$aryCurTipResult['userID']] = array();
						$aryResults[$aryCurTipResult['userID']]['score'] = 0;
						$aryResults[$aryCurTipResult['userID']]['username'] = $aryCurTipResult['username'];
					}					
									
					$aryResults[$aryCurTipResult['userID']]['score'] += $aryCurTipResult['score'];
					
					if($aryCurTipResult['specID'] == 1)
					{
						$aryResults[$aryCurTipResult['userID']]['wm1'] = $aryCurTipResult['teamname'];	
					}					
					if($aryCurTipResult['specID'] == 2)
					{
						$aryResults[$aryCurTipResult['userID']]['wm2'] = $aryCurTipResult['teamname'];	
					}
					if($aryCurTipResult['specID'] == 3)
					{
						$aryResults[$aryCurTipResult['userID']]['wm3'] = $aryCurTipResult['teamname'];	
					}
				}				
				return $aryResults;
			}
			else return 0;
		}
		
		/*
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{			
				$intPrevUser = 0;				
				while($aryCurResult = mysql_fetch_assoc($objResult))
				{

					if(!array_key_exists($aryCurResult['userID'], $aryResults))
					{
						$aryResults[$aryCurResult['userID']] = array();	
					}					
					array_push($aryResults, $aryCurResult);
				}
				print_r($aryResults);
				return $aryResults;
			}
			else return 0;
		}
		else return 0;
		*/		
	}

	function setSpecialTip($intSpecialID, $intUserID, $strTeamID)
	{
		$arySpecialTip = $this->getSpecialTip($intSpecialID, $intUserID);
		
		/*update*/
		if($arySpecialTip)
		{
			$strSQL = "UPDATE special SET teamID='".$strTeamID."' WHERE specID=".$intSpecialID." AND userID=".$intUserID.";";
		}
		/*insert*//*update*/
		else
		{
			$strSQL = "INSERT INTO special (specID, userID, teamID) VALUES (".$intSpecialID.", ".$intUserID.", '".$strTeamID."')";	
		}
		
		//echo $strSQL;
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{      
		if (mysql_affected_rows($this->objDB)) return 0; 
		else return mysql_error($this->objDB);
		}
		else return "Incorrect SQL-Statement";   
  	}


	function getGroupRanking($strGroup)
	{
		//$aryTeams = array();
		$strSQL = "SELECT team1ID, team2ID, s_team1, s_team2, sd, t1.name AS teamname1, t2.name AS teamname2, SUM( s_team1 - s_team2 ) AS score, (SUM( s_team1 ) +1) / ( SUM( s_team2 ) +1 ) AS goaldiff FROM matches AS m ";
		$strSQL .= "LEFT OUTER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON t1.teamID = m.team1ID ";		
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON t2.teamID = m.team2ID ";		
		$strSQL .= "WHERE m.matchtype='VR' ";
		$strSQL .= "AND t1.premgroup='".$strGroup."' ";
		/*
		$strSQL .= "AND (";
		$strSQL .= "m.team1ID IN (SELECT teamID FROM teams WHERE premgroup='".$strGroup."') ";
		$strSQL .= "OR ";
		$strSQL .= "m.team2ID IN (SELECT teamID FROM teams WHERE premgroup='".$strGroup."') ";
		$strSQL .= ") ";
		*/
		$strSQL .= "GROUP BY team1ID ";
		$strSQL .= "ORDER BY score DESC , goaldiff DESC ";
		$strSQL .= ";";
		//echo $strSQL."<br/>";
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				$intRank = 1;
				while($aryCurTeamStats = mysql_fetch_assoc($objResult))
				{
					$aryTeams[$aryCurTeamStats['team1ID']] = $intRank;
					$intRank ++; 
				}
				//print_r($aryTeams);
				return $aryTeams;
			}
			else return 0;
		}
		else return 0;
	}

	function getGroupTable($strGroup)
	{	
		$aryGroupTable = array();
		$aryTeams = array();
		$strSQL = "SELECT team1ID, team2ID, s_team1, s_team2, sd, t1.name AS teamname1, t2.name AS teamname2, t1.flagpic AS flagpic1, t2.flagpic AS flagpic2 FROM matches AS m ";
		$strSQL .= "LEFT OUTER JOIN results AS r ";
		$strSQL .= "ON r.matchID = m.matchID ";
		$strSQL .= "INNER JOIN teams AS t1 ";
		$strSQL .= "ON t1.teamID = m.team1ID ";		
		$strSQL .= "INNER JOIN teams AS t2 ";
		$strSQL .= "ON t2.teamID = m.team2ID ";		
		$strSQL .= "WHERE m.matchtype='VR' ";
		$strSQL .= "AND t1.premgroup='".$strGroup."' ";
		/*
		$strSQL .= "AND (";
		$strSQL .= "m.team1ID IN (\"SELECT teamID FROM teams WHERE premgroup='".$strGroup."'\") ";
		$strSQL .= "OR ";
		$strSQL .= "m.team2ID IN (\"SELECT teamID FROM teams WHERE premgroup='".$strGroup."'\") ";
		$strSQL .= ") ";
		*/
		//$strSQL .= "ORDER BY SUM(r.s_team1 - r.s_team2) DESC ";
		$strSQL .= ";";
		//echo $strSQL."<br/>";
		
		if( $objResult = mysql_query($strSQL, $this->objDB) )
		{	
			if( mysql_num_rows($objResult) != 0 )
			{
				while($aryCurTeamStats = mysql_fetch_assoc($objResult))
				{
					if(!array_key_exists($aryCurTeamStats['team1ID'], $aryTeams))
					{
						//$aryTeams[$aryCurTeamStats['team1ID']] = array();
						$aryTeams[$aryCurTeamStats['team1ID']]['score']=0;
						$aryTeams[$aryCurTeamStats['team1ID']]['goals']=0;
						$aryTeams[$aryCurTeamStats['team1ID']]['vsgoals']=0;
						$aryTeams[$aryCurTeamStats['team1ID']]['teamname']=$aryCurTeamStats['teamname1'];
						$aryTeams[$aryCurTeamStats['team1ID']]['flagpic']=$aryCurTeamStats['flagpic1'];
					}					
					if(!array_key_exists($aryCurTeamStats['team2ID'], $aryTeams))
					{
						//$aryTeams[$aryCurTeamStats['team2ID']] = array();
						$aryTeams[$aryCurTeamStats['team2ID']]['score']=0;
						$aryTeams[$aryCurTeamStats['team2ID']]['goals']=0;
						$aryTeams[$aryCurTeamStats['team2ID']]['vsgoals']=0;
						$aryTeams[$aryCurTeamStats['team2ID']]['teamname']=$aryCurTeamStats['teamname2'];
						$aryTeams[$aryCurTeamStats['team2ID']]['flagpic']=$aryCurTeamStats['flagpic2'];
					}
					
					/*result available?*/
					if($aryCurTeamStats['s_team1']!= NULL AND $aryCurTeamStats['s_team1']!= "")
					{
						/* winner - 3 points*/
						if($aryCurTeamStats['s_team1']>$aryCurTeamStats['s_team2'])
						{
							$aryTeams[$aryCurTeamStats['team1ID']]['score'] += 3;
						}
						elseif($aryCurTeamStats['s_team2']>$aryCurTeamStats['s_team1'])
						{
							$aryTeams[$aryCurTeamStats['team2ID']]['score'] += 3;
						}						
						/* draw   - 1 point */
						else
						{							
							$aryTeams[$aryCurTeamStats['team1ID']]['score'] += 1;
							$aryTeams[$aryCurTeamStats['team2ID']]['score'] += 1;							
						}
						
						/* goals */
						$aryTeams[$aryCurTeamStats['team1ID']]['goals'] += $aryCurTeamStats['s_team1'];
						$aryTeams[$aryCurTeamStats['team1ID']]['vsgoals'] += $aryCurTeamStats['s_team2'];
						
						$aryTeams[$aryCurTeamStats['team2ID']]['goals'] += $aryCurTeamStats['s_team2'];
						$aryTeams[$aryCurTeamStats['team2ID']]['vsgoals'] += $aryCurTeamStats['s_team1'];
					}					
					
					//array_push($aryGroupTable, $aryCurTeamStats);
				}		
				
				/* add goal diff */
				foreach($aryTeams AS $strCurTeam => $aryCurData)
				{
					$aryTeams[$strCurTeam]['goaldiff'] = $aryCurData['goals'] - $aryCurData['vsgoals'];  
				}
					
				/**************
				 * sorting
				 **************/			
				foreach ($aryTeams AS $key => $row) 
				{
 					$scores[$key]  = $row['score'];
 					$goaldiff[$key] = $row['goaldiff'];
   					$goals[$key] = $row['goals'];
   					//$vsgoals[$key] = $row['vsgoals'];
				}	
				array_multisort($scores, SORT_DESC, $goaldiff, SORT_DESC, $goals, SORT_DESC, $aryTeams);
				
				/* ranking */
				$intRank = 0;		
				$aryLastData['score'] = -1;		
				$aryLastData['goaldiff'] = 0;
				$aryLastData['goals'] = 0;
				foreach($aryTeams AS $strCurTeam => $aryCurData)
				{
					
					if( $aryLastData['score'] == $aryTeams[$strCurTeam]['score'] AND $aryLastData['goaldiff'] == $aryTeams[$strCurTeam]['goaldiff'] AND $aryLastData['goals'] == $aryTeams[$strCurTeam]['goals'])
					{
						$aryTeams[$strCurTeam]['rank'] = $intRank;	
					}
					else
					{	
						$intRank++;	
						$aryTeams[$strCurTeam]['rank'] = $intRank;						
					} 
					$aryLastData['score'] = $aryTeams[$strCurTeam]['score'];
					$aryLastData['goaldiff'] = $aryTeams[$strCurTeam]['goaldiff'];
					$aryLastData['goals'] = $aryTeams[$strCurTeam]['goals'];					
				}				
				
				//print_r($aryTeams)."<br/><br/>";				
				return $aryTeams;
			}
			else return 0;
		}
		else return 0;	
	}
}








?>
