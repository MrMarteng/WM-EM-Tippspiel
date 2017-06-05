<?php
require_once("inc/ContentObjects.inc.php");

$objSession->logout();

echo generateMessages(array("Der logout war erfolgreich<br> Bis zum n&auml;chsten mal."));

?>