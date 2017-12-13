<?php
/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 **/

if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("tblclients", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return 1;
}

insert_query("tblcredit", array("clientid" => $clientid, "date" => "now()", "description" => $description, "amount" => $amount));
update_query("tblclients", array("credit" => "+=" . $amount), array("id" => (int)$clientid));
$currency = getCurrency($clientid);
logActivity("Added Credit - User ID: " . $clientid . " - Amount: " . formatCurrency($amount), $clientid);
$result = select_query_i("tblclients", "", array("id" => $clientid));
$data = mysqli_fetch_array($result);
$creditbalance = $data['credit'];
$apiresults = array("result" => "success", "newbalance" => $creditbalance);
?>