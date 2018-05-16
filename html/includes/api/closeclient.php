<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("closeClient")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$result = select_query_i("ra_user", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return 1;
}

closeClient($_REQUEST['clientid']);
$apiresults = array("result" => "success", "clientid" => $_REQUEST['clientid']);
?>