<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!$days) {
	$days = 0;
}


if (!$expires) {
	$expires = date("YmdHis", mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $days, date("Y")));
}

$banid = insert_query("ra_bannedips", array("ip" => $ip, "reason" => $reason, "expires" => $expires));
$apiresults = array("result" => "success", "banid" => $banid);
?>