<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("addTransaction")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}


if ($userid) {
	$result = select_query_i("ra_user", "id", array("id" => $userid));
	$data = mysqli_fetch_array($result);

	if (!$data['id']) {
		$apiresults = array("result" => "error", "message" => "Client ID Not Found");
		return null;
	}
}


if ($invoiceid = (int)$_POST['invoiceid']) {
	$query = "SELECT * FROM ra_bills WHERE id='" . $invoiceid . "'";
	$result = full_query_i($query);
	$data = mysqli_fetch_array($result);

	if (!$data['id']) {
		$apiresults = array("result" => "error", "message" => "Invoice ID Not Found");
		return null;
	}
}


if (!$paymentmethod) {
	$apiresults = array("result" => "error", "message" => "Payment Method is required");
	return null;
}

addTransaction($userid, $currencyid, $description, $amountin, $fees, $amountout, $paymentmethod, $transid, $invoiceid, $date, "", $rate);

if ($userid && $credit) {
	if ($transid) {
		$description .= " (Trans ID: " . $transid . ")";
	}

	insert_query("ra_transactions_credit", array("clientid" => $userid, "date" => toMySQLDate($date), "description" => $description, "amount" => $amountin));
	update_query("ra_user", array("credit" => "+=" . $amountin), array("id" => (int)$userid));
}

$apiresults = array("result" => "success");
?>