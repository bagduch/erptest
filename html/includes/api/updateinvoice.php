<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("updateInvoiceTotal")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}

$result = select_query_i("ra_bills", "id", array("id" => $invoiceid));
$data = mysqli_fetch_array($result);
$invoiceid = $data['id'];

if (!$invoiceid) {
	$apiresults = array("result" => "error", "message" => "Invoice ID Not Found");
	return null;
}


if ($itemdescription) {
	foreach ($itemdescription as $lineid => $description) {
		$amount = $itemamount[$lineid];
		$taxed = $itemtaxed[$lineid];
		update_query("ra_bill_lineitems", array("description" => $description, "amount" => $amount, "taxed" => $taxed), array("id" => $lineid));
	}
}


if ($newitemdescription) {
	foreach ($newitemdescription as $k => $v) {
		$description = $v;
		$amount = $newitemamount[$k];
		$taxed = $newitemtaxed[$k];
		insert_query("ra_bill_lineitems", array("invoiceid" => $invoiceid, "description" => $description, "amount" => $amount, "taxed" => $taxed));
	}
}


if ($deletelineids) {
	foreach ($deletelineids as $lineid) {
		delete_query("ra_bill_lineitems", array("id" => $lineid, "invoiceid" => $invoiceid));
	}
}

updateInvoiceTotal($invoiceid);
$updateqry = array();

if ($invoicenum) {
	$updateqry['invoicenum'] = $invoicenum;
}


if ($date) {
	$updateqry['date'] = $date;
}


if ($duedate) {
	$updateqry['duedate'] = $duedate;
}


if ($datepaid) {
	$updateqry['datepaid'] = $datepaid;
}


if ($subtotal) {
	$updateqry['subtotal'] = $subtotal;
}


if ($credit) {
	$updateqry['credit'] = $credit;
}


if ($tax) {
	$updateqry['tax'] = $tax;
}


if ($tax2) {
	$updateqry['tax2'] = $tax2;
}


if ($total) {
	$updateqry['total'] = $total;
}


if ($taxrate) {
	$updateqry['taxrate'] = $taxrate;
}


if ($taxrate2) {
	$updateqry['taxrate2'] = $taxrate2;
}


if ($status) {
	$updateqry['status'] = $status;
}


if ($paymentmethod) {
	$updateqry['paymentmethod'] = $paymentmethod;
}


if ($notes) {
	$updateqry['notes'] = $notes;
}

update_query("ra_bills", $updateqry, array("id" => $invoiceid));
$apiresults = array("result" => "success", "invoiceid" => $invoiceid);
?>