<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_ticket", "", array("id" => $ticketid));
$data = mysqli_fetch_array($result);
$ticketid = $data['id'];

if (!$ticketid) {
	$apiresults = array("result" => "error", "message" => "Ticket ID not found");
	return null;
}


if (!function_exists("deleteTicket")) {
	require ROOTDIR . "/includes/ticketfunctions.php";
}

deleteTicket($ticketid);
$apiresults = array("result" => "success");
?>