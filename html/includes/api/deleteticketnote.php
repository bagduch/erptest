<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("tblticketnotes", "id", array("id" => $noteid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Note ID Not Found");
	return null;
}

delete_query("tblticketnotes", array("id" => $noteid));
$apiresults = array("result" => "success", "noteid" => $noteid);
?>