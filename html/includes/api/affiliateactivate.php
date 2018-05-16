<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getAdminName")) {
	require ROOTDIR . "/includes/adminfunctions.php";
}


if (!function_exists("affiliateActivate")) {
	require ROOTDIR . "/includes/affiliatefunctions.php";
}

$result = select_query_i("ra_user", "id", array("id" => $userid));
$data = mysqli_fetch_array($result);
$userid = $data['id'];

if (!$userid) {
	$apiresults = array("result" => "error", "message" => "Client ID not found");
	return null;
}

affiliateActivate($userid);
$apiresults = array("result" => "success");
?>