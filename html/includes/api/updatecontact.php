<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("generateClientPW")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}

$result = select_query_i("ra_user_contacts", "id,subaccount", array("id" => $contactid));
$data = mysqli_fetch_array($result);
$subaccount = $data['subaccount'];

if (!$data[0]) {
	$apiresults = array("result" => "error", "message" => "Contact ID Not Found");
	return null;
}


if (($subaccount || $_REQUEST['subaccount']) && $_REQUEST['email']) {
	$result = select_query_i("ra_user", "id", array("email" => $_REQUEST['email']));
	$data = mysqli_fetch_array($result);
	$result = select_query_i("ra_user_contacts", "id", array("email" => $_REQUEST['email'], "subaccount" => "1", "id" => array("sqltype" => "NEQ", "value" => $contactid)));
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

$updateqry = array();

if (isset($_REQUEST['firstname'])) {
	$updateqry['firstname'] = $firstname;
}


if (isset($_REQUEST['lastname'])) {
	$updateqry['lastname'] = $lastname;
}


if (isset($_REQUEST['companyname'])) {
	$updateqry['companyname'] = $companyname;
}


if (isset($_REQUEST['email'])) {
	$updateqry['email'] = $email;
}


if (isset($_REQUEST['address1'])) {
	$updateqry['address1'] = $address1;
}


if (isset($_REQUEST['address2'])) {
	$updateqry['address2'] = $address2;
}


if (isset($_REQUEST['city'])) {
	$updateqry['city'] = $city;
}


if (isset($_REQUEST['state'])) {
	$updateqry['state'] = $state;
}


if (isset($_REQUEST['postcode'])) {
	$updateqry['postcode'] = $postcode;
}


if (isset($_REQUEST['country'])) {
	$updateqry['country'] = $country;
}


if (isset($_REQUEST['phonenumber'])) {
	$updateqry['phonenumber'] = $phonenumber;
}


if (isset($_REQUEST['subaccount'])) {
	$updateqry['subaccount'] = $subaccount;
}


if (isset($_REQUEST['password2'])) {
	$updateqry['password'] = generateClientPW($password2);
}


if (isset($_REQUEST['permissions'])) {
	$updateqry['permissions'] = $permissions;
}


if (isset($_REQUEST['generalemails'])) {
	$updateqry['generalemails'] = $generalemails;
}


if (isset($_REQUEST['productemails'])) {
	$updateqry['productemails'] = $productemails;
}


if (isset($_REQUEST['domainemails'])) {
	$updateqry['domainemails'] = $domainemails;
}


if (isset($_REQUEST['invoiceemails'])) {
	$updateqry['invoiceemails'] = $invoiceemails;
}


if (isset($_REQUEST['supportemails'])) {
	$updateqry['supportemails'] = $supportemails;
}

update_query("ra_user_contacts", $updateqry, array("id" => $contactid));
$apiresults = array("result" => "success", "contactid" => $contactid);
?>