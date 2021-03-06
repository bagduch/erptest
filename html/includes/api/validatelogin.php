<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("validateClientLogin")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$_SESSION['adminid'] = "";

if (validateClientLogin($email, $password2)) {
	$apiresults = array("result" => "success", "userid" => $_SESSION['uid']);

	if ($_SESSION['cid']) {
		$apiresults['contactid'] = $_SESSION['cid'];
	}

	$apiresults['passwordhash'] = $_SESSION['upw'];
	return 1;
}

$apiresults = array("result" => "error", "message" => "Email or Password Invalid");
?>