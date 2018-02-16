<?php
/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 **/

if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if ($custommessage) {
	delete_query("tblemailtemplates", array("name" => "Mass Mail Template"));
	insert_query("tblemailtemplates", array("type" => "admin", "name" => "Custom Admin Temp", "subject" => html_entity_decode($customsubject), "message" => html_entity_decode($custommessage)));
	$messagename = "Custom Admin Temp";
}

$result = select_query_i("tblemailtemplates", "COUNT(*)", array("name" => $messagename, "type" => "admin"));
$data = mysqli_fetch_array($result);

if (!$data[0]) {
	$apiresults = array("result" => "error", "message" => "Email Template not found");
	return null;
}


if (!in_array($type, array("system", "account", "support"))) {
	$type = "system";
}

sendAdminMessage($messagename, $mergefields, $type, $deptid);

if ($custommessage) {
	delete_query("tblemailtemplates", array("name" => "Custom Admin Temp"));
}

$apiresults = array("result" => "success");
?>