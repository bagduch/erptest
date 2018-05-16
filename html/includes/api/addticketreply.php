<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("AddReply")) {
	require ROOTDIR . "/includes/ticketfunctions.php";
}

$from = "";
$result = select_query_i("ra_ticket", "", array("id" => $ticketid));
$data = mysqli_fetch_array($result);
$ticketid = $data['id'];

if (!$ticketid) {
	$apiresults = array("result" => "error", "message" => "Ticket ID Not Found");
	return null;
}


if ($clientid) {
	$result = select_query_i("ra_user", "id", array("id" => $clientid));
	$data = mysqli_fetch_array($result);

	if (!$data['id']) {
		$apiresults = array("result" => "error", "message" => "Client ID Not Found");
		return null;
	}


	if ($contactid) {
		$result = select_query_i("ra_user_contacts", "id", array("id" => $contactid, "userid" => $clientid));
		$data = mysqli_fetch_array($result);

		if (!$data['id']) {
			$apiresults = array("result" => "error", "message" => "Contact ID Not Found");
			return null;
		}
	}
}
else {
	if ((!$name || !$email) && !$adminusername) {
		$apiresults = array("result" => "error", "message" => "Name and email address are required if not a client");
		return null;
	}

	$from = array("name" => $name, "email" => $email);
}


if (!$message) {
	$apiresults = array("result" => "error", "message" => "Message is required");
	return null;
}

AddReply($ticketid, $clientid, $contactid, $message, $adminusername, "", $from, $status, $noemail, true);

if ($customfields) {
	$customfields = base64_decode($customfields);
	$customfields = unserialize($customfields);
	saveCustomFields($ticketid, $customfields);
}

$apiresults = array("result" => "success");
?>