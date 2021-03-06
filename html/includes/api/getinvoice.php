<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("ra_bills", "", array("id" => $invoiceid));
$data = mysqli_fetch_array($result);
$invoiceid = $data['id'];

if (!$invoiceid) {
	$apiresults = array("status" => "error", "message" => "Invoice ID Not Found");
	return null;
}

$userid = $data['userid'];
$invoicenum = $data['invoicenum'];
$date = $data['date'];
$duedate = $data['duedate'];
$datepaid = $data['datepaid'];
$subtotal = $data['subtotal'];
$credit = $data['credit'];
$tax = $data['tax'];
$tax2 = $data['tax2'];
$total = $data['total'];
$taxrate = $data['taxrate'];
$taxrate2 = $data['taxrate2'];
$status = $data['status'];
$paymentmethod = $data['paymentmethod'];
$notes = $data['notes'];
$result = select_query_i("ra_transactions", "SUM(amountin)-SUM(amountout)", array("invoiceid" => $invoiceid));
$data = mysqli_fetch_array($result);
$amountpaid = $data[0];
$balance = $total - $amountpaid;
$balance = format_as_currency($balance);
$gatewaytype = get_query_val("ra_modules_gateways", "value", array("gateway" => $paymentmethod, "setting" => "type"));
$ccgateway = (($gatewaytype == "CC" || $gatewaytype == "OfflineCC") ? true : false);
$apiresults = array("result" => "success", "invoiceid" => $invoiceid, "invoicenum" => $invoicenum, "userid" => $userid, "date" => $date, "duedate" => $duedate, "datepaid" => $datepaid, "subtotal" => $subtotal, "credit" => $credit, "tax" => $tax, "tax2" => $tax2, "total" => $total, "balance" => $balance, "taxrate" => $taxrate, "taxrate2" => $taxrate2, "status" => $status, "paymentmethod" => $paymentmethod, "notes" => $notes, "ccgateway" => $ccgateway);
$result = select_query_i("ra_bill_lineitems", "", array("invoiceid" => $invoiceid));

while ($data = mysqli_fetch_array($result)) {
	$apiresults['items']['item'][] = array("id" => $data['id'], "type" => $data['type'], "relid" => $data['relid'], "description" => $data['description'], "amount" => $data['amount'], "taxed" => $data['taxed']);
}

$apiresults['transactions'] = "";
$result = select_query_i("ra_transactions", "", array("invoiceid" => $invoiceid));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['transactions']['transaction'][] = $data;
}

$responsetype = "xml";
?>