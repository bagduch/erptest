<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("ServerChangePackage")) {
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

$result = ServerChangePackage($serviceid);

if ($result == "success") {
	$apiresults = array("result" => "success");
	return 1;
}

$apiresults = array("result" => "error", "message" => $result);
?>