<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$result = select_query_i("tblactivitylog", "COUNT(id)", "");
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart);
$result = select_query_i("tblactivitylog", "id, date, description, user", "", "id", "DESC", "" . $limitstart . "," . $limitnum);
$apiresults['numreturned'] = mysqli_num_rows($result);

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$date = $data['date'];
	$description = $data['description'];
	$user = $data['user'];
	$description = RAHtmlspecialchars($description);
	$apiresults['activity']['entry'][] = array("id" => $id, "date" => $date, "description" => $description, "user" => $user);
}

$responsetype = "xml";
?>