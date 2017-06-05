<?php

/* User logged in? */
if( $objSession->sessionValid() AND ($intUserID = $objSession->getUserID()) )
{

/* inludes */
require_once("inc/ContentObjects.inc.php");
require_once("inc/tippDB.inc.php");

/* vars */
$strHTML = "";


/*****************************/
/* Some form data available? */
/*****************************/
if(array_key_exists("tip", $_POST))
{
  $aryErrors = array();  
  $intUserID = $objSession->getUserID();
  $intMatchID = $_POST['matchID'];
  $objMatchDB = new matchDB();
    
  /* check formvalues */
  if($_POST['team1'] == "" OR $_POST['team2'] == "" )array_push($aryErrors, "Sie haben kein vollst&auml;ndiges Ergebnis eingegeben");
  
  /* sudden death */
  /*
  $intSD = 0;
  if(array_key_exists('sd', $_POST))
  {
  	if($_POST['sd'] == 1)$intSD = 1;
  }
  */
  $intSD = 0;
  
  
  /* tipping possible? */
  if( $objMatchDB->matchTippable($intMatchID) AND !count($aryErrors) )
  {
  	
    if( $objMatchDB->matchTipped($intMatchID, $intUserID))
    {
      /* update tip */
      if($strInsertError = $objMatchDB->updateTip($intMatchID, $intUserID, $_POST['team1'], $_POST['team2'], $intSD))
      {
         array_push($aryErrors, "DB-Error: ".$strInsertError);      
      }
      else
      {
         array_push($aryErrors, "Der Tipp wurde erfolgreich ge&auml;ndert!<br> <a href='".$objSession->getVar('refurl')."'>OK</a>");
      }
    }
    else
    { 
      /* insret new tip*/
      if($strInsertError = $objMatchDB->setTip($intMatchID, $intUserID, $_POST['team1'], $_POST['team2'], $intSD))
      {
         array_push($aryErrors, "DB-Error: ".$strInsertError);      
      }
      else
      {
         array_push($aryErrors, "Der Tipp wurde erfolgreich gespeichert!<br> <a href='".$objSession->getVar('refurl')."'>OK</a>");
      }
    }
  } 
  elseif(!count($aryErrors)) array_push($aryErrors, "F&uuml;r dieses Spiel werden keine Tippabgaben mehr akzeptiert.");  
    
  $objMatchDB->disconnect();  
}


/***************************************************/
/* Only Display the Form, when a matchID is passed */
/***************************************************/
if(array_key_exists(matchID, $_GET))
{
  /* Vars */
  $intMatchID = $_GET['matchID'];
  $objCO = new boxed_CO();
  $strHTMLTippform = "";
  $aryUserData = array();
  $objMatchDB = new matchDB();
  $aryRowTargets = array();
  
  $objCO->setHeader("Tippabgabe");
  $objCO->mixWidth = "50%";
  
  $aryMatch = $objMatchDB->getMatchByMatchID($intMatchID);
  
  /* if tip allreay set */ 
  if( $objMatchDB->matchTipped($intMatchID, $intUserID) )
  {
    $aryTip = $objMatchDB->getTip($intMatchID, $intUserID);    
  }
  
  /* generate Form */
  $strHTMLTippform .= "<form method='POST' action='?menu=tip&matchID=".$intMatchID."'>";
  $strHTMLTippform .= "<input type='hidden' name='matchID' value=".$intMatchID.">";
  $strHTMLTippform .= "<table align='center' style='padding-top:5px;' cellspacing=2 cellpadding=2 border=0>";  
  $strHTMLTippform .= "<tr><td colspan=3 style='text-align:center;'>".strftime("%a - %d.%m.%Y  %H:%M",$aryMatch['date'])." <br/><i>(".$aryMatch['location'].")</i></td></tr>";
  $strHTMLTippform .= "<tr><td colspan=3>&nbsp;</td></tr>";
  $strHTMLTippform .= "<tr>";
  $strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'><img src='".$aryMatch['flagpic1']."' border=0>&nbsp;".$aryMatch['team1']."</td>";
  $strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'>&nbsp;-&nbsp;</td>";
  $strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'><img src='".$aryMatch['flagpic2']."' border=0>&nbsp;".$aryMatch['team2']."</td>";  
  $strHTMLTippform .= "</tr>";
  $strHTMLTippform .= "<tr>";
  $strHTMLTippform .= "<td style='text-align:center;'><input type='text' style='text-align:center' name='team1' size=1 maxlength=2 ";
  if(isset($aryTip['s_team1']))$strHTMLTippform .= "value='".$aryTip['s_team1']."'";
  $strHTMLTippform .= "></td>";
  $strHTMLTippform .= "<td style='font-weight:bold; text-align:center;'>&nbsp;:&nbsp;</td>";
  $strHTMLTippform .= "<td style='text-align:center;'><input type='text' style='text-align:center' name='team2' size=2 maxlength=2 ";
  if(isset($aryTip['s_team2']))$strHTMLTippform .= "value='".$aryTip['s_team2']."'";
  $strHTMLTippform .= "></td>";
  $strHTMLTippform .= "</td></tr>";
  /*
  if($aryMatch['matchtype']!="VR")
  {
  	$strHTMLTippform .= "<tr><td colspan=3 style='text-align:center;'><input type='checkbox' name='sd' value=1";
  	if($aryTip['sd'])$strHTMLTippform .= " checked ";
	$strHTMLTippform .= ">&nbsp;Elfmeterschie&szlig;en</td></tr>";
  } 
  */ 
  $strHTMLTippform .= "<tr><td colspan=3>&nbsp;</td></tr>";
  $strHTMLTippform .= "<tr><td style='text-align:center;' colspan=3><input type='submit' value='Tipp abgeben' name='tip'></td></tr>";
  $strHTMLTippform .= "</table>";
  $strHTMLTippform .= "</form>";
  
  $objMatchDB->disconnect();

  $objCO->addContent($strHTMLTippform);
  $strHTML .= $objCO->generateCO();
  
  /* back button */
  if(!count($aryErrors))$strHTML .= "<br/><center><a href='".$objSession->getVar('refurl')."'>zur&uuml;ck</a></center>";
}
else
{
  $strHTML .= "Kein Spiel ausgew&auml;hlt!";
}

/* Display Messages*/
if(count($aryErrors)) $strHTML .= generateMessages($aryErrors);    
//$strHTML .= "<script type='text/javascript'>window.location.href=\"".$objSession->getVar('refurl')."\"</script>";

/* output */
echo $strHTML;

} // session valid?
else echo "<center class='alert'>Session invalid!</center>";

?>
