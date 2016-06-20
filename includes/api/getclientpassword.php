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


if ($_POST['userid']) {
	$result = select_query_i("tblclients", "", array("id" => $_POST['userid']));
}
else {
	$result = select_query_i("tblclients", "", array("email" => $_POST['email']));
}

$data = mysqli_fetch_array($result);

if ($data['id']) {
	$password = $data['password'];

	if ($CONFIG['NOMD5']) {
		$password = decrypt($password);
	}

	$apiresults = array("result" => "success", "password" => $password);
	return 1;
}

$apiresults = array("result" => "error", "message" => "Client ID Not Found");
?>