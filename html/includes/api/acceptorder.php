<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("acceptOrder")) {
	require ROOTDIR . "/includes/orderfunctions.php";
}

if (!function_exists("ModuleBuildParams")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}

$result = select_query("ra_orders", "", array("id" => $orderid, "status" => "Pending"));
$data = mysql_fetch_array($result);
$orderid = $data['id'];

if (!$orderid) {
	$apiresults = array("result" => "error", "message" => "Order ID not found or Status not Pending");
	return null;
}

$ordervars = array();

if (isset($_REQUEST['serverid'])) {
	$ordervars['api']['serverid'] = $_REQUEST['serverid'];
}


if (isset($_REQUEST['serviceusername'])) {
	$ordervars['api']['username'] = $_REQUEST['serviceusername'];
}


if (isset($_REQUEST['servicepassword'])) {
	$ordervars['api']['password'] = $_REQUEST['servicepassword'];
}


if (isset($_REQUEST['registrar'])) {
	$ordervars['api']['registrar'] = $_REQUEST['registrar'];
}


if (isset($_REQUEST['sendregistrar'])) {
	$ordervars['api']['sendregistrar'] = $_REQUEST['sendregistrar'];
}


if (isset($_REQUEST['autosetup'])) {
	$ordervars['api']['autosetup'] = $_REQUEST['autosetup'];
}


if (isset($_REQUEST['sendemail'])) {
	$ordervars['api']['sendemail'] = $_REQUEST['sendemail'];
}

acceptOrder($orderid, $ordervars);
$apiresults = array("result" => "success");
?>
