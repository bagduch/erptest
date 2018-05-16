<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_user_contacts", "id", array("id" => $contactid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Contact ID Not Found");
	return null;
}

delete_query("ra_user_contacts", array("id" => $contactid));
$apiresults = array("result" => "success", "contactid" => $contactid);
?>