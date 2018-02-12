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


if (!function_exists("addContact")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$result = select_query_i("tblclients", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);

if (!$data[0]) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return null;
}

$permissions = $permissions ? explode(",", $permissions) : array();

if (count($permissions)) {
	$result = select_query_i("tblclients", "id", array("email" => $email));
	$data = mysqli_fetch_array($result);
	$result = select_query_i("tblcontacts", "id", array("email" => $email, "subaccount" => "1"));
	$data2 = mysqli_fetch_array($result);

	if ($data['id'] || $data2['id']) {
		$apiresults = array("result" => "error", "message" => "Duplicate Email Address");
		return null;
	}
}


if ($generalemails) {
	$generalemails = "1";
}


if ($productemails) {
	$productemails = "1";
}


if ($domainemails) {
	$domainemails = "1";
}


if ($invoiceemails) {
	$invoiceemails = "1";
}


if ($supportemails) {
	$supportemails = "1";
}

$contactid = addContact($clientid, $firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password2, $permissions, $generalemails, $productemails, $domainemails, $invoiceemails, $supportemails);
$apiresults = array("result" => "success", "contactid" => $contactid);
?>