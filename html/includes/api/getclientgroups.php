<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_user_group", "COUNT(id)", "");
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults);
$result = select_query_i("ra_user_group", "", "", "id", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['groups']['group'][] = $data;
}

$responsetype = "xml";
?>