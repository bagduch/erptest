<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("closeTicket")) {
	require ROOTDIR . "/includes/ticketfunctions.php";
}

$result = select_query_i("ra_ticket", "id", array("id" => $ticketid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Ticket ID Not Found");
	return null;
}

$updateqry = array();

if ($deptid) {
	$updateqry['did'] = $deptid;
}


if ($userid) {
	$updateqry['userid'] = $userid;
}


if ($name) {
	$updateqry['name'] = $name;
}


if ($email) {
	$updateqry['email'] = $email;
}


if ($cc) {
	$updateqry['cc'] = $cc;
}


if ($subject) {
	$updateqry['title'] = $subject;
}


if ($priority) {
	$updateqry['urgency'] = $priority;
}


if ($status && $status != "Closed") {
	$updateqry['status'] = $status;
}


if ($status == "Closed") {
	closeTicket($ticketid);
}


if ($flag) {
	$updateqry['flag'] = $flag;
}

update_query("ra_ticket", $updateqry, array("id" => $ticketid));
$apiresults = array("result" => "success", "ticketid" => $ticketid);
?>