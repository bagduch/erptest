<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$where = array();

if ($clientid) {
	$where['id'] = $clientid;
}
else {
	if ($email) {
		$where['email'] = $email;
	}
}

$result = select_query_i("tblclients", "id", $where);
$data = mysqli_fetch_array($result);
$clientid = $data['id'];

if (!$clientid) {
	$apiresults = array("result" => "error", "message" => "Client Not Found");
	return null;
}

$clientsdetails = getClientsDetails($clientid);
$currency_result = full_query_i("SELECT code FROM tblcurrencies WHERE id=" . (int)$clientsdetails['currency']);
$currency = mysqli_fetch_assoc($currency_result);
$clientsdetails['currency_code'] = $currency['code'];

if ($responsetype == "xml") {
	$apiresults = array("result" => "success", "client" => $clientsdetails);
}
else {
	$apiresults = array_merge(array("result" => "success"), $clientsdetails);
}


if ($stats || $responsetype == "xml") {
	$apiresults = array("result" => "success", "client" => $clientsdetails, "stats" => getClientsStats($clientid));
}

?>