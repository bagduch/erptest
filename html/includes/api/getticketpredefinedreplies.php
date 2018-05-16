<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_macro_categories_templates", "COUNT(id)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults);
$result = select_query_i("ra_macro_categories_templates", "name,reply", array("catid" => $catid), "name", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['predefinedreplies']['predefinedreply'][] = array("name" => $data['name'], "reply" => $data['reply']);
}

$responsetype = "xml";
?>