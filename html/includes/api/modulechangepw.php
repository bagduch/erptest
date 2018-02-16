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


if (!function_exists("ServerChangePassword")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}

$serviceid = (isset($_POST['serviceid']) ? $_POST['serviceid'] : $_POST['accountid']);
$result = select_query_i("tblcustomerservices", "packageid", array("id" => $serviceid));
$data = mysqli_fetch_array($result);
$packageid = $data['packageid'];

if (!$packageid) {
	$apiresults = array("result" => "error", "message" => "Service ID Not Found");
	return false;
}


if ($servicepassword) {
	update_query("tblcustomerservices", array("password" => encrypt($servicepassword)), array("id" => $serviceid));
}

$result = ServerChangePassword($serviceid);

if ($result == "success") {
	$apiresults = array("result" => "success");
	return 1;
}

$apiresults = array("result" => "error", "message" => $result);
?>