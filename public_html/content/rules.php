<?php

require_once("ContentObjects.inc.php");

$objCO = new std_CO();
$objCO->setHeader("Regeln");
$objCo->strContentAlign = "left";

$strHTMLRules = "<br/><h1 style='text-align:left;'>F&uuml;r das WM Tippspiel 2006 gelten folgende Regeln:</h1>";
$strHTMLRules .= "<ol style='list-style-type:upper-roman; text-align:left; font-weight:bold;' type='I' >";
$strHTMLRules .= "<li style='padding-bottom:10px;'><i>Bugs sind unverz&uuml;glich dem Webmaster zu melden</i></lo>";
$strHTMLRules .= "<li style='padding-bottom:10px;'><i>Tipps werden nur bis 30 Minuten vor Beginn des jeweiligen Spiels akzeptiert. Bis zu diesem Zeitpunkt kann der Tipp beliebig oft ge&auml;ndert werden. Nachtr&auml;gliche &Auml;nderungen sind nicht mehr m&ouml;glich.</i></lo>";
$strHTMLRules .= "<li style='padding-bottom:10px;'><i>Die Abgabe der Sondertipps ist nur m&ouml;glich, bis jede Mannschaft ein Spiel absolviert hat (14.06.2006 18:00)</i></lo>";
$strHTMLRules .= "<li style='padding-bottom:10px;'><i>Zur Berechung der Punkte werden (ab dem Achtelfinale) die Ergebnisse nach Spielende gewertet. Wer Unentschieden tippt ist selbst schuld!</i></lo>";
$strHTMLRules .= "</ol>";

$strHTMLRules .= "<h1 style='text-align:left;'>Punktevergabe:</h1>";
$strHTMLRules .= "<table border=0 cellspacing=0 cellpadding=5>";
$strHTMLRules .= "<tr></td><td><b><i><nobr>1 Punkt</nobr></i></b></td><td>:</td><td>Tipp auf die Siegermannschaft eines Spiels</td></tr>";
$strHTMLRules .= "<tr><td><b><i><nobr>2 Punkte</nobr></i></b></td><td>:</td><td>Korrekte Tordifferenz</td></tr>";
$strHTMLRules .= "<tr><td><b><i><nobr>3 Punkte</nobr></i></b></td><td>:</td><td>Bei einem komplett richtig getippten Ergebnis</td></tr>";
$strHTMLRules .= "</table>";

/*
$strHTMLRules .= "<h1 style='text-align:left;'>Punktevergabe:</h1>";
$strHTMLRules .= "<table border=0 cellspacing=0 cellpadding=5>";
$strHTMLRules .= "<tr><td rowspan=3><img src='pics/120m.png' border=0 style='margin:0px;'></td><td><b><i><nobr>1 Punkt</nobr></i></b></td><td>:</td><td>Tipp auf die Siegermannschaft eines Spiels</td></tr>";
$strHTMLRules .= "<tr><td><b><i><nobr>2 Punkt</nobr></i></b></td><td>:</td><td>Korrekte Tordifferenz</td></tr>";
$strHTMLRules .= "<tr><td style='border-bottom-style:dashed; border-bottom-width:1px; border-bottom-color:black;'><b><i><nobr>3 Punkt</nobr></i></b></td><td style='border-bottom-style:dashed; border-bottom-width:1px; border-bottom-color:black;'>:</td><td style='border-bottom-style:dashed; border-bottom-width:1px; border-bottom-color:black;'>Bei einem komplett richtig getippten Ergebnis</td></tr>";
$strHTMLRules .= "<tr><td><img src='pics/elfm.png' border=0 style='margin:0px;'></td><td style='vertical-align:top;'><b><i><nobr>4 Punkt</nobr></i></b></td><td style='vertical-align:top;'>:</td><td style='vertical-align:top;'>Wird auf ein Elfmeterschie&szlig;en <i>und</i> die Siegermannschaft getippt. Die Tordifferenz spielt dabei keine Rolle.<br/><i>(Diese Tippvariante ist logischerweise erst nach der Vorrunde m&ouml;glich)</i></td><td></tr>";
$strHTMLRules .= "</table>";
*/

$strHTMLRules .= "<h1 style='text-align:left;'>Zusatzpunkte:</h1>";
$strHTMLRules .= "<table border=0 cellspacing=2 cellpadding=4>";
$strHTMLRules .= "<tr><td><b><i>8 Punkte</i></b></td><td>:</td><td>Weltmeistertipp</td></tr>";
$strHTMLRules .= "<tr><td><b><i>7 Punkte</i></b></td><td>:</td><td>Vize-Weltmeister</td></tr>";
$strHTMLRules .= "<tr><td><b><i>6 Punkte</i></b></td><td>:</td><td>WM Dritter</td></tr>";
$strHTMLRules .= "</table>";


$objCO->addContent($strHTMLRules);

echo $objCO->generateCO();

?>