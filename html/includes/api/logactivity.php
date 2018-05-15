<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$userid = (int)$userid;
logActivity($description, $userid);
$apiresults = array("result" => "success");
?>