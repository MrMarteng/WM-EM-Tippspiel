<?php
require_once("ContentObjects.inc.php");

$objCO = new std_CO();

$objCO->setHeader("Impressum");


$strHTMLImressum = "<table align='center'>";
$strHTMLImressum .= "<tr><td colspan=2><b>Webmaster:</b></td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>Martin Kiepfer</td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>Streichet 3</td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>73669 Lichtenwald</td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>".addEmail("snuggl3s", "sparflamme.net")."</td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
$strHTMLImressum .= "<tr><td colspan=2><b>URL</b></td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp</td><td>http://tippspiel.sparflamme.net</td></tr>";
$strHTMLImressum .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
$strHTMLImressum .= "<tr><td colspan=2></td></tr>";
$strHTMLImressum .= "</table>";

$objCO->addContent($strHTMLImressum);

echo $objCO->generateCO();




?>