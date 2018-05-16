<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if ($_POST['userid']) {
	$result = select_query_i("ra_user", "", array("id" => $_POST['userid']));
}
else {
	$result = select_query_i("ra_user", "", array("email" => $_POST['email']));
}

$data = mysqli_fetch_array($result);

if ($data['id']) {
	$password = $data['password'];

	$apiresults = array("result" => "success", "password" => $password);
	return 1;
}

$apiresults = array("result" => "error", "message" => "Client ID Not Found");
?>
