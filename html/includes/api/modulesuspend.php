<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("ServerSuspendAccount")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}

$result = select_query_i("tblcustomerservices", "packageid", array("id" => $_POST['accountid']));
$data = mysqli_fetch_array($result);
$packageid = $data['packageid'];
$result = ServerSuspendAccount($_POST['accountid'], $_POST['suspendreason']);

if ($result == "success") {
	$apiresults = array("result" => "success");
	return 1;
}

$apiresults = array("result" => "error", "message" => $result);
?>