<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("ModuleBuildParams")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}


if (!function_exists("changeOrderStatus")) {
	require ROOTDIR . "/includes/orderfunctions.php";
}

$result = select_query_i("ra_orders", "", array("id" => $orderid));
$data = mysqli_fetch_array($result);
$orderid = $data['id'];

if (!$orderid) {
	$apiresults = array("result" => "error", "message" => "Order ID Not Found");
	return null;
}

changeOrderStatus($orderid, "Fraud");
$apiresults = array("result" => "success");
?>
