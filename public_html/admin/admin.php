<?php

ini_set('include_path',ini_get('include_path').':'.realpath('../inc'));

require_once("resultDB.inc.php");
require_once("ContentObjects.inc.php");
require_once("calculate.inc.php");
require_once("tippDB.inc.php");

if(array_key_exists('updatescore', $_GET))
{
	if(array_key_exists('matchID', $_GET))
	{
		updateScore($_GET['matchID']);	
	}
	
}
/*
elseif(array_key_exists('updatespecial', $_GET))
{

	$objTippDB = new userDB();
	$objMatchDB = new matchDB();
	$objResultDB = new resultDB();
	
	
	$aryUsers = $objTippDB->getAllUserData();
	
	foreach($aryUsers AS $aryCurUser)
	{
		$intScore = 0;		
		$ary3Result = $objResultDB->getResult(63);
		$aryFinResult = $objResultDB->getResult(64);
		
		// 1
		$arySpecialTips = $objMatchDB->getSpecialTip(1, $intUserID);
		if($aryFinResult['rs_team1'] > $aryFinResult['rs_team2'])
		{
			
		}
			
		// 2
		$arySpecialTips = $objMatchDB->getSpecialTip(2, $intUserID);
		
		// 3
		$arySpecialTips = $objMatchDB->getSpecialTip(3, $intUserID);
		}	
}
*/




function updateScore($intMatchID)
{	
	$objResultDB = new resultDB();
	$objUserDB = new userDB();
	$objMatchDB = new matchDB();
	$aryTeamScore = array();	
	
	
	$aryUsers = $objUserDB->getAllUserData();	
	$aryResult = $objResultDB->getResult($intMatchID);
	$arySpecial = $objMatchDB->getAllSpecialResults();	
	//print_r($arySpecial);
	foreach( $aryUsers AS $aryCurUser )
	{
		/****************************************
		 * update Result-Score of current match
		 ****************************************/
		$intScore = 0;	
		
		/* user Score */
		$aryTip = $objMatchDB->getTip($intMatchID, $aryCurUser['userID']);		
		$intScore += calculateScore(array($aryTip['s_team1'], $aryTip['s_team2']), array($aryResult['rs_team1'], $aryResult['rs_team2']), $aryResult['sd'], $aryTip['sd']);		
				
		/* update user score */		
		$objResultDB->updateTipScore($intMatchID, $aryCurUser['userID'], $intScore);

		
		/****************************************
		 * update Allover Score
		 ****************************************/
		/* get allover score */		
		$intAlloverScore = $objUserDB->getAlloverScore($aryCurUser['userID']);		
		echo $intAlloverScore."<br/>";
		
		/* special Score? */
		if($intMatchID >= 63)
		{
			 $intAlloverScore += $arySpecial[$aryCurUser['userID']]['score'];			 
			 echo $intAlloverScore."</br>";
		}
		
		/* update Score */
		$objUserDB->updateScore($intAlloverScore, $aryCurUser['userID']);
		echo "<br>";
	}			
	
		
	/****************************************
	 * update User-Ranking
	 ****************************************/
	$objUserDB->updateUserRanking();		
}
	



?>