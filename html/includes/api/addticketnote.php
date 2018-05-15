<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getAdminName")) {
	require ROOTDIR . "/includes/adminfunctions.php";
}


if (!function_exists("AddNote")) {
	require ROOTDIR . "/includes/ticketfunctions.php";
}


if ($ticketnum) {
	$result = select_query_i("tbltickets", "id", array("tid" => $ticketnum));
}
else {
	$result = select_query_i("tbltickets", "id", array("id" => $ticketid));
}

$data = mysqli_fetch_array($result);
$ticketid = $data['id'];

if (!$ticketid) {
	$apiresults = array("result" => "error", "message" => "Ticket ID not found");
	return null;
}

AddNote($ticketid, $message);
$apiresults = array("result" => "success");
?>