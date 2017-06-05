<?php

require_once("inc/ContentObjects.inc.php");

/*
$objCO = new std_CO();
//$objCO->mixWidth = "98%";
$objCO->setHeader("Willkommen");
//$strHTMLContent = "<center class='alert'>!!! Wegen Wartungsarbeiten wird die Seite heute ab 22Uhr kurzzeitig nicht erreichbar sein !!!</center>";
$strHTMLContent = "<br/><br/>Herzlich willkommen auf <b>DER</b> Tippspielseite zur Fu&szlig;ball Weltmeisterschaft 2006<br/>";
$strHTMLContent .= "<br/>Die Teilnahme an dem Tippspiel ist umsonst und ohne jeglichen kommerziellen Hintergrund. ";
$strHTMLContent .= "Nach der <a href='?menu=register'>Anmeldung</a> kanns direkt los gehn. ";
//$strHTMLContent .= "&Auml;nderungen und Bekanntgaben sind im <a target='_blank' href='forum/index.php'>Forum</a> nachzulesen. ";
//$strHTMLContent .= "Au&szlig;erdem ist jeder dazu aufgefordert das genaue Spielkonzept und Verbesserungen der Seite dort mit zu gestalten!";
$strHTMLContent .= "<br/><br/>Viel Spa&szlig; beim tippen!! :)";
$objCO->addContent($strHTMLContent);
echo $objCO->generateCO();
*/

$objCO = new std_CO();
//$objCO->mixWidth = "98%";
$objCO->setHeader("Ende der Weltmeisterschaft");
//$strHTMLContent = "<center class='alert'>!!! Wegen Wartungsarbeiten wird die Seite heute ab 22Uhr kurzzeitig nicht erreichbar sein !!!</center>";
$strHTMLContent = "<br/><br/>Am Sonntag Abend des <i>9.Juli 2006</i> endet die Weltmeisterschaft - leider ohne ein Finale mit der deutschen Nationalmannschaft.<br/>";
$strHTMLContent .= "Wie schon so oft wird eine Mannschaft Weltmeister, die w&auml;rend der WM nur wenig glanzvolle Siege eingefahren hat und mit einem anst&auml;ndigen Schiedsrichter eigentlich schon im Viertelfinale gegen Australien h&auml;tte aus dem Turnier geschmissen werden m&uuml;ssen.<br/>";
//$strHTMLContent .= "Dennoch bleibt uns aber wohl nichts anderes &uuml;brig wie den Italienern zu Ihrem 4 Weltmeister-Titel zu gratulieren.";
$strHTMLContent .= "<br/><br/> Mit dem Ende der WM ist leider auch die Zeit des \"Tippens\" vorbei. Ich hoffe es hat allen viel Spa&szlig; gemacht denn die n&auml;chste EM/WM kommt ganz sicher :).";
$strHTMLContent .= "<br/><br/>Die komplette Auswertung des Tippspiels, mit Einbezug der Sondertipps, werde ich in den kommenden Tagen durchf&uuml;hren.";
$strHTMLContent .= "<br/><br/><b>Viel Dank allen Mittipper und bis zum n&auml;chsten mal!</b>";
//$strHTMLContent .= "</br></br/>BesteMartin alias snuggl3s";
$objCO->addContent($strHTMLContent);
echo $objCO->generateCO();


?>
