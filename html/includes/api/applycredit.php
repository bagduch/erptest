<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("applyCredit")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}

$data = get_query_vals("ra_bills", "id,userid,credit,total,status", array("id" => $invoiceid));
$invoiceid = $data['id'];

if (!$invoiceid) {
	$apiresults = array("result" => "error", "message" => "Invoice ID Not Found");
	return null;
}

$userid = $data['userid'];
$credit = $data['credit'];
$total = $data['total'];
$status = $data['status'];
$amountpaid = get_query_val("ra_transactions", "SUM(amountin)-SUM(amountout)", array("invoiceid" => $invoiceid));
$balance = round($total - $amountpaid, 2);
$amount = ($amount == "full" ? $balance : round($amount, 2));
$totalcredit = get_query_val("ra_user", "credit", array("id" => $userid));

if ($status != "Unpaid") {
	$apiresults = array("result" => "error", "message" => "Invoice Not in Unpaid Status");
	return null;
}


if ($totalcredit < $amount) {
	$apiresults = array("result" => "error", "message" => "Amount exceeds customer credit balance");
	return null;
}


if ($balance < $amount) {
	$apiresults = array("result" => "error", "message" => "Amount Exceeds Invoice Balance");
	return null;
}


if ($amount == "0.00") {
	$apiresults = array("result" => "error", "message" => "Credit Amount to apply must be greater than zero");
	return null;
}

$appliedamount = min($amount, $totalcredit);
applyCredit($invoiceid, $userid, $appliedamount, $noemail);
$apiresults = array("result" => "success", "invoiceid" => $invoiceid, "amount" => $appliedamount, "invoicepaid" => (get_query_val("ra_bills", "status", array("id" => $invoiceid)) == "Paid" ? "true" : "false"));
?>