<?php

require_once("inc/ContentObjects.inc.php");

$objCO = new std_CO();
//$objCO->mixWidth = "98%";
$objCO->setHeader("Forum");
$strHTMLContent = "<br/><br/><a href='forum/index.php' target='_blank'><b>Forum</b></a> in neuem Fenster &ouml;ffnen";
$objCO->addContent($strHTMLContent);
echo $objCO->generateCO();

?>