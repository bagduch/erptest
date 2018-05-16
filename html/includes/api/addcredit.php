<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_user", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return 1;
}

insert_query("ra_transactions_credit", array("clientid" => $clientid, "date" => "now()", "description" => $description, "amount" => $amount));
update_query("ra_user", array("credit" => "+=" . $amount), array("id" => (int)$clientid));
$currency = getCurrency($clientid);
logActivity("Added Credit - User ID: " . $clientid . " - Amount: " . formatCurrency($amount), $clientid);
$result = select_query_i("ra_user", "", array("id" => $clientid));
$data = mysqli_fetch_array($result);
$creditbalance = $data['credit'];
$apiresults = array("result" => "success", "newbalance" => $creditbalance);
?>