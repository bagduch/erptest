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


if (!function_exists("ModuleBuildParams")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}


if (!function_exists("changeOrderStatus")) {
	require ROOTDIR . "/includes/orderfunctions.php";
}

$result = select_query_i("tblorders", "", array("id" => $orderid, "status" => "Pending"));
$data = mysqli_fetch_array($result);
$orderid = $data['id'];

if (!$orderid) {
	$apiresults = array("result" => "error", "message" => "Order ID not found or Status not Pending");
	return null;
}

changeOrderStatus($orderid, "Cancelled");
$apiresults = array("result" => "success");
?>
