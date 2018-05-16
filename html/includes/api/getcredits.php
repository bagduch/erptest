<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_user", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);
$clientid = $data['id'];

if (!$clientid) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return null;
}

$credits = array();
$result = select_query_i("ra_transactions_credit", "id,date,description,amount,relid", array("clientid" => $clientid), "date", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$credits[] = $data;
}

$apiresults = array("result" => "success", "totalresults" => count($credits), "clientid" => $clientid, "credits" => array("credit" => $credits));
$responsetype = "xml";
?>