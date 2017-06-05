<?php
ini_set('include_path', ini_get('include_path') .

':' . realpath('./inc'));

/* includes */
/*Session management*/
require_once ("sessionManagement.inc.php");
require_once ("ContentObjects.inc.php");
require_once ("matchTables.inc.php");
require_once ("tippDB.inc.php");

/* Vars */
$strHTMLMenu = "";


/* NEWS */
//$strNews =  "+++ Heute um 21 Uhr endet die Abgabe die Sondertipps +++";


/* Menu-vars */
$aryMenu['home']['name'] = "Startseite";
$aryMenu['home']['url'] = "content/home.php";
$aryMenu['home']['hidden'] = 0;
$aryMenu['home']['rightbox'] = 1;

$aryMenu['tipgame_h']['name'] = "Zum Tippspiel";
$aryMenu['tipgame_h']['menuheader'] = 1;

$aryMenu['rules']['name'] = "Regeln";
$aryMenu['rules']['url'] = "content/rules.php";
$aryMenu['rules']['hidden'] = 0;
$aryMenu['rules']['rightbox'] = 1;

$aryMenu['forum']['name'] = "Forum";
$aryMenu['forum']['url'] = "game/forum.php";
$aryMenu['forum']['hidden'] = 0;
$aryMenu['forum']['rightbox'] = 1;

$aryMenu['register']['name'] = "Anmelden";
$aryMenu['register']['url'] = "game/register.php";
$aryMenu['register']['hidden'] = 0;
$aryMenu['register']['rightbox'] = 0;

$aryMenu['impress']['name'] = "Impressum";
$aryMenu['impress']['url'] = "content/impressum.php";
$aryMenu['impress']['hidden'] = 0;
$aryMenu['impress']['rightbox'] = 1;

$aryMenu['tips_h']['name'] = "Tipps";
$aryMenu['tips_h']['menuheader'] = 1;
$aryMenu['tips_h']['hidden'] = 1;

$aryMenu['matches']['name'] = "Spielplan";
$aryMenu['matches']['url'] = "game/matches.php";
$aryMenu['matches']['hidden'] = 1;
$aryMenu['matches']['rightbox'] = 0;

$aryMenu['tipoverview']['name'] = "Tipp&uuml;bersicht";
$aryMenu['tipoverview']['url'] = "game/tip_overview.php";
$aryMenu['tipoverview']['hidden'] = 1;
$aryMenu['tipoverview']['rightbox'] = 0;

$aryMenu['tipresults']['name'] = "Tippergebnisse";
$aryMenu['tipresults']['url'] = "game/tip_results.php";
$aryMenu['tipresults']['hidden'] = 1;
$aryMenu['tipresults']['rightbox'] = 0;

$aryMenu['tip']['name'] = "Tippabgabe";
$aryMenu['tip']['url'] = "game/tip.php";
$aryMenu['tip']['hidden'] = 1;
$aryMenu['tip']['rightbox'] = 0;

$aryMenu['tipresult']['name'] = "Tippabgabe";
$aryMenu['tipresult']['url'] = "game/tip_result.php";
$aryMenu['tipresult']['hidden'] = 1;
$aryMenu['tipresult']['rightbox'] = 0;

$aryMenu['resultoverview']['name'] = "Spiel&uuml;bersicht";
$aryMenu['resultoverview']['url'] = "game/result_overview.php";
$aryMenu['resultoverview']['hidden'] = 1;
$aryMenu['resultoverview']['rightbox'] = 0;

$aryMenu['special']['name'] = "Sondertipps";
$aryMenu['special']['url'] = "game/special.php";
$aryMenu['special']['hidden'] = 1;
$aryMenu['special']['rightbox'] = 0;

$aryMenu['score']['name'] = "Rangliste";
$aryMenu['score']['url'] = "game/score.php";
$aryMenu['score']['hidden'] = 1;
$aryMenu['score']['rightbox'] = 1;

$aryMenu['stats']['name'] = "Statistik";
$aryMenu['stats']['url'] = "game/stats.php";
$aryMenu['stats']['hidden'] = 1;
$aryMenu['stats']['rightbox'] = 1;

$aryMenu['settings_h']['name'] = "Einstellungen";
$aryMenu['settings_h']['menuheader'] = 1;
$aryMenu['settings_h']['hidden'] = 1;

$aryMenu['settings']['name'] = "Pers. Daten";
$aryMenu['settings']['url'] = "user/settings.php";
$aryMenu['settings']['hidden'] = 1;
$aryMenu['settings']['rightbox'] = 1;

/*
$aryMenu['otherusers']['name'] = "Teilnehmer";
$aryMenu['otherusers']['url'] = "user/userlist.php";
$aryMenu['otherusers']['hidden'] = 1;
*/

$aryMenu['login']['name'] = "einloggen";
$aryMenu['login']['url'] = "login.php";
$aryMenu['login']['hidden'] = 1;
$aryMenu['login']['rightbox'] = 0;

$aryMenu['logout']['name'] = "logout";
$aryMenu['logout']['url'] = "logout.php";
$aryMenu['logout']['hidden'] = 1;
$aryMenu['logout']['rightbox'] = 0;

$aryDisplayRightBoxes[0] = "home";

/* analyze parameters */
if (array_key_exists('menu', $_GET)) {
	if (array_key_exists($_GET['menu'], $aryMenu)) {
		$strCurSite = $_GET['menu'];
	} else
		echo "Seite nicht gefunden!";
} 
else $strCurSite = 'home';

/* session available */
$objSession = new userSession();
if ($objSession->sessionValid()) {
	/* user allready logged in? */
	if ((($intUserID = $objSession->getUserID()) != 0) AND $strCurSite != "logout") 
	{		
		$aryMenu['tips_h']['hidden'] = 0;
		$aryMenu['tipoverview']['hidden'] = 0;
		//$aryMenu['tipresults']['hidden'] = 0;	
		$aryMenu['settings']['hidden'] = 0;
		$aryMenu['matches']['hidden'] = 0;
		$aryMenu['score']['hidden'] = 0;
		$aryMenu['resultoverview']['hidden'] = 0;
		$aryMenu['special']['hidden'] = 0;
		$aryMenu['stats']['hidden'] = 0;
		//$aryMenu['otherusers']['hidden'] = 0;
		//$aryMenu['forum']['hidden'] = 0;
		$aryMenu['tipps']['hidden'] = 1;
		$aryMenu['register']['hidden'] = 1;
		$aryMenu['settings_h']['hidden'] = 0;
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="SHORTCUT ICON" href="football.icon">
<!--<link rel="SHORTCUT ICON" href="favicon.ico">-->
<script type="text/javascript" language="javascript" src="inc/jscripts.js"></script>
<TITLE></TITLE>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<table width=800 border=0 cellspacing=0 cellpadding=0 align="center">
	<TR>
		<TD width=800 colspan=3><img src="pics/banner.png"></TD>		
	</TR>
	<tr>
		<TD colspan=3 height=33 style="background-image:url(pics/infoline.png); padding:0px;">
			<table width="100%" height="100%" cellpadding=3 cellspacing=0 border=1 style="border-width:1px; border-style:solid; border-color:#979fab;">
				<TR>
					<TD class="infoline" width="88%">
<?php
if($strNews != "" AND $objSession->sessionValid() AND $strCurSite != "logout")
{
	echo "<marquee class='alert' style='font-weight:normal;' scrollamount='5'  direction='left'>";
	echo "<i>".$strNews."</i>";
	echo "</marqee>";
}
else echo "&nbsp;";
?>					
					</TD>					
					<TD class="infoline" width="12%" style="text-align:center;">
<?php

if ($intUserID AND ($strCurSite != "logout")) {
	/*
	$objUserDB = new userDB();
	$strUsername = $objUserDB->getUsername($intUserID);
	echo "angemeldet als: ".$strUsername;
	$objUserDB->disconnect();
	*/
	echo "<a class='menu' href='?menu=logout'>logout</a>";
} else {
	echo "<a class='menu' href='?menu=login'>[einloggen]</a>";
}
?>
					</TD>
				</tr>
			</table>
		</TD>
	</tr>
	<tr>
		<TD width=120 style="vertical-align:top;">
		
<!--+++++++++++++++++++++++++ MENU +++++++++++++++++++++++++++-->				
<table cellspacing=0 cellpadding=3 border=0 valign="top">
<?


foreach ($aryMenu AS $strCurMenuKey => $aryCurMenuItem) {
	
	if ($aryCurMenuItem['hidden'] == 0) {
		if ($aryCurMenuItem['menuheader'] == 1) 
		{
			$strHTMLMenu .= "<tr>";
			$strHTMLMenu .= "<TD width=150 height=20 class='menu_header' >" . $aryCurMenuItem['name'] . "</TD>";
			$strHTMLMenu .= "</tr>";
		}
		else{
			$strHTMLMenu .= "<tr >";
			if ($strCurMenuKey != $strCurSite) {
				$strHTMLMenu .= "<TD onMouseOver=\"this.className='menu_item_highlight'\" onMouseOut=\"this.className='menu_item'\" width=150 height=33 class='menu_item' OnClick=\"location.href='?menu=" . $strCurMenuKey . "'\">" . $aryCurMenuItem['name'] . "</TD>";
			} else {
				$strHTMLMenu .= "<TD width=150 height=33 class='menu_item_selected' >" . $aryCurMenuItem['name'] . "</TD>";
			}
			$strHTMLMenu .= "</tr>";
		}
	}

}
echo $strHTMLMenu;
?>
</table>		
<!--+++++++++++++++++++++++++ MENU END +++++++++++++++++++++++++++-->				

		</TD>
<?php

if ($objSession->sessionValid() AND $aryMenu[$strCurSite]['rightbox']) {
	echo "<td width=560 style='padding:1px; padding:1px; vertical-align:top;'>";
} else {
	echo "<td width=680 style='padding:1px; padding:1px; vertical-align:top;'>";
}
?>
		
<!--+++++++++++++++++++++++++ CONTENT +++++++++++++++++++++++++++-->
<!--				
<table width="100%" cellspacing=2 cellpadding=2 >
	<TR>
		<TD style="text-align=center;">
		-->
<?php


include ($aryMenu[$strCurSite]['url']);
?>
<!--		
		</TD>
	</TR>
</table>
-->
<!--+++++++++++++++++++++++++ CONTENT END +++++++++++++++++++++++++++-->				
					
		</td>
<?php

if ($objSession->sessionValid() AND $aryMenu[$strCurSite]['rightbox']) {
	echo "<td width=120 style='padding-top:1px; vertical-align:top;'>";
}
?>		
<!--+++++++++++++++++++++++++ RIGHT BOX +++++++++++++++++++++++++++-->								
<?php

if ($objSession->sessionValid() AND $aryMenu[$strCurSite]['rightbox']) {
	/* next matches */
	$objRightBox = new rightBox();
	$objRightBox->mixWidth = "100%";
	$objRightBox->strContentAlign = "center";
	$objRightBox->setHeader("Anstehende Spiele");

	/* show next 5 mathces */
	$objMatchDB = new matchDB();
	$aryMatches = $objMatchDB->getNextMatches(5);	

	if(is_array($aryMatches))
	{
		$strHTMLNextMatches = "<table align='center' width='100%' cellspacing=0 cellpadding-2>";
		foreach ($aryMatches AS $aryCurMatch) {
			if($objMatchDB->matchTippable($aryCurMatch['matchID']))$strNextTarget = "?menu=tip&matchID=".$aryCurMatch['matchID'];
			else $strNextTarget = "?menu=tipresult&matchID=".$aryCurMatch['matchID'];
			$strHTMLNextMatches .= "<tr style='cursor:pointer;' onmouseover=\"this.style.backgroundColor='#FFFFFF'\" onmouseout=\"this.style.backgroundColor=''\" onclick=\"window.location.href='".$strNextTarget."'\">";
			$strHTMLNextMatches .= "<td style='text-align:center;'>" . $aryCurMatch['team1ID'] . "</td>";
			$strHTMLNextMatches .= "<td style='text-align:center;'>-</td>";
			$strHTMLNextMatches .= "<td style='text-align:center;'>" . $aryCurMatch['team2ID'] . "</td>";
			$strHTMLNextMatches .= "</tr>";
		}
		$strHTMLNextMatches .= "</table>";
	}
	unset($objMatchDB);

	$objRightBox->insert($strHTMLNextMatches);
	echo $objRightBox->generateBox();
	unset($objRightBox);

	/* last view matches */
	$objRightBox = new rightBox();
	$objRightBox->mixWidth = "100%";
	$objRightBox->strContentAlign = "center";
	$objRightBox->setHeader("Die letzen Spiele");

	/* show last 5 mathces */
	$objMatchDB = new matchDB();
	$aryMatches = $objMatchDB->getPastMatches(5);

	if ($aryMatches) {
		$strHTMLPastMatches = "<table align='center' width='100%' cellspacing=0 cellpadding-2>";
		foreach ($aryMatches AS $aryCurMatch) {
			$strHTMLPastMatches .= "<tr style='cursor:pointer;' onmouseover=\"this.style.backgroundColor='#FFFFFF'\" onmouseout=\"this.style.backgroundColor=''\" onclick=\"window.location.href='?menu=tipresult&matchID=" . $aryCurMatch['matchID'] . "'\">";
			$strHTMLPastMatches .= "<td style='text-align:center;'>" . $aryCurMatch['team1ID'] . "</td>";
			$strHTMLPastMatches .= "<td style='text-align:center;'>-</td>";
			$strHTMLPastMatches .= "<td style='text-align:center;'>" . $aryCurMatch['team2ID'] . "</td>";
			$strHTMLPastMatches .= "</tr>";
		}
		$strHTMLPastMatches .= "</table>";

		$objRightBox->insert($strHTMLPastMatches);
	}
	echo $objRightBox->generateBox();

	/* top5 */
	$objRightBox = new rightBox();
	$objRightBox->mixWidth = "100%";
	$objRightBox->strContentAlign = "center";
	$objRightBox->setHeader("Top5");

	$objUserDB = new userDB();
	$objUserDB->strOrderBy = "rank, username";
	$objUserDB->strOrder = "ASC";
	$objUserDB->intCount = 5;
	$strHTMLScores = "<table width='80%' align='center' cellspacing=2 cellpadding=2 border=0>";
	$aryUserData = $objUserDB->getAllUserData();
	foreach ($aryUserData AS $aryCurUserData) {

		$strHTMLScores .= "<tr>";
		$strHTMLScores .= "<td>" . $aryCurUserData['rank'] . ".</td>";
		$strHTMLScores .= "<td>" . wtf($aryCurUserData['username'], 10, "..") . "</td>";
		$strHTMLScores .= "<td>(" . $aryCurUserData['score'] . ")</td>";
		$strHTMLScores .= "</tr>";
		$intRank++;
	}
	$strHTMLScores .= "</table>";
	$objRightBox->insert($strHTMLScores);

	echo $objRightBox->generateBox();
}
?>		
<!--+++++++++++++++++++++++++ RIGHT BOX END +++++++++++++++++++++++++++-->							

<?php

if ($objSession->sessionValid() AND $aryMenu[$strCurSite]['rightbox']) {
	echo "</td>";
}
?>
	</tr>
	<tr>
		<TD colspan=3 style="border-bottom-color:#979fab; border-bottom-style:solid; border-bottom-width:1px;">&nbsp;</TD>
	</tr>
	<tr>
		<TD colspan=3 style="text-align:right;">Stand: 10.07.2006</TD>
	</tr>
</table>

</body>
</html>
