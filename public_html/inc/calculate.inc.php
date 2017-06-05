<?php
/*
 * Created on Apr 19, 2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 function calculateScore($aryTip, $aryResult, $blnSD, $blnUserSD)
 { 
 	$aryScore = array
	(
		"perfect" => 3,
		"correctScore"=> 2,
		"correctTeam" => 1
	); 	
 	
 	$intResultDiff = $aryResult[0] - $aryResult[1];		
	$intTipDiff = $aryTip[0] - $aryTip[1];			
	
	$intScore = 0;
	if(is_numeric($aryTip[0]) AND is_numeric($aryTip[1]))
	{
		/* Sudden Death? */
		//
		if(FALSE) /* SD can't be tipped anymore!! */
		{	
			/* user tipped a SD? */
			if( $blnUserSD == $blnSD )
			{
				/* correct team -> PERFECT */	
				if( ($intResultDiff * $intTipDiff) > 0 )
				{
					$intScore += $aryScore['perfect'] + 1;
				}
			}
		}
		else
		{		
			/* PERFECT */
			if( ($aryResult[0] == $aryTip[0]) AND ($aryResult[1] == $aryTip[1]) )
			{
				//echo "perfect";
				$intScore += $aryScore['perfect'];
			}
			/* Correct Score */
			elseif( $intResultDiff == $intTipDiff )
			{
				//echo "Correct Score";
				$intScore += $aryScore['correctScore'];
			}
			/* correct Team */
			elseif( ($intResultDiff * $intTipDiff) > 0 )
			{
				//echo "Correct Team";
				$intScore += $aryScore['correctTeam'];
			}			
		} 		
	}
	return $intScore;
}


function calculateTeamScore()
{
	
}


?>
