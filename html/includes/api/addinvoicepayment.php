<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("addInvoicePayment")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}

$query = "SELECT * FROM ra_bills WHERE id='" . (int)$_POST['invoiceid'] . "'";
$result = full_query_i($query);
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Invoice ID Not Found");
	return null;
}

$date = ($_POST['date'] ? fromMySQLDate($_POST['date']) : "");

if ($date2) {
	$date = fromMySQLDate($date2);
}

addInvoicePayment($_POST['invoiceid'], $_POST['transid'], $_POST['amount'], $_POST['fees'], $_POST['gateway'], $_POST['noemail'], $date);
$apiresults = array("result" => "success");
?>