<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("addTransaction")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}

$updateqry = array();

if (isset($_REQUEST['userid'])) {
	$updateqry['userid'] = $_REQUEST['userid'];
}


if (isset($_REQUEST['currency'])) {
	$updateqry['currency'] = $_REQUEST['currency'];
}


if (isset($_REQUEST['gateway'])) {
	$updateqry['gateway'] = $_REQUEST['gateway'];
}


if (isset($_REQUEST['date'])) {
	$updateqry['date'] = $_REQUEST['date'];
}


if (isset($_REQUEST['description'])) {
	$updateqry['description'] = $_REQUEST['description'];
}


if (isset($_REQUEST['amountin'])) {
	$updateqry['amountin'] = $_REQUEST['amountin'];
}


if (isset($_REQUEST['fees'])) {
	$updateqry['fees'] = $_REQUEST['fees'];
}


if (isset($_REQUEST['amountout'])) {
	$updateqry['amountout'] = $_REQUEST['amountout'];
}


if (isset($_REQUEST['rate'])) {
	$updateqry['rate'] = $_REQUEST['rate'];
}


if (isset($_REQUEST['transid'])) {
	$updateqry['transid'] = $_REQUEST['transid'];
}


if (isset($_REQUEST['invoiceid'])) {
	$updateqry['invoiceid'] = $_REQUEST['invoiceid'];
}


if (isset($_REQUEST['refundid'])) {
	$updateqry['refundid'] = $_REQUEST['refundid'];
}

update_query("ra_transactions", $updateqry, array("id" => $transactionid));
$apiresults = array("result" => "success", "transactionid" => $transactionid);
?>