<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("deleteClient")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$result = select_query_i("tblclients", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);

if (!$data['id']) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return 1;
}

deleteClient($_POST['clientid']);
$apiresults = array("result" => "success", "clientid" => $_POST['clientid']);
?>