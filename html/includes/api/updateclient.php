<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("saveCustomFields")) {
	require ROOTDIR . "/includes/customfieldfunctions.php";
}


if ($clientemail) {
	$result = select_query_i("ra_user", "id", array("email" => $clientemail));
}
else {
	$result = select_query_i("ra_user", "id", array("id" => $clientid));
}

$data = mysqli_fetch_array($result);
$clientid = $data['id'];

if (!$clientid) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return null;
}


if ($_POST['email']) {
	$result = select_query_i("ra_user", "id", array("email" => $_POST['email'], "id" => array("sqltype" => "NEQ", "value" => $clientid)));
	$data = mysqli_fetch_array($result);
	$result = select_query_i("ra_user_contacts", "id", array("email" => $_POST['email'], "subaccount" => "1"));
	$data2 = mysqli_fetch_array($result);

	if ($data['id'] || $data2['id']) {
		$apiresults = array("result" => "error", "message" => "Duplicate Email Address");
		return null;
	}
}


if (isset($_POST['taxexempt'])) {
	$_POST['taxexempt'] = ($_POST['taxexempt'] ? "on" : "");
}


if (isset($_POST['latefeeoveride'])) {
	$_POST['latefeeoveride'] = ($_POST['latefeeoveride'] ? "on" : "");
}


if (isset($_POST['overideduenotices'])) {
	$_POST['overideduenotices'] = ($_POST['overideduenotices'] ? "on" : "");
}


if (isset($_POST['separateinvoices'])) {
	$_POST['separateinvoices'] = ($_POST['separateinvoices'] ? "on" : "");
}


if (isset($_POST['disableautocc'])) {
	$_POST['disableautocc'] = ($_POST['disableautocc'] ? "on" : "");
}

$updatequery = "";
$fieldsarray = array("firstname", "lastname", "companyname", "email", "address1", "address2", "city", "state", "postcode", "country", "phonenumber", "credit", "taxexempt", "notes", "cardtype", "status", "language", "currency", "groupid", "taxexempt", "latefeeoveride", "overideduenotices", "billingcid", "separateinvoices", "disableautocc", "datecreated", "bankname", "banktype", "lastlogin", "ip", "host", "gatewayid");
foreach ($fieldsarray as $fieldname) {

	if (isset($_POST[$fieldname])) {
		$updatequery .= "" . $fieldname . "='" . db_escape_string($_POST[$fieldname]) . "',";
		continue;
	}
}


if ($_POST['password2']) {
	$updatequery .= "password='" . generateClientPW($_POST['password2']) . "',";
}


if (isset($_POST['cardnum'])) {
	$updatequery .= "cardlastfour='" . db_escape_string(substr($_POST['cardnum'], 0 - 4)) . "',";
}

$cchash = md5($ra->get_hash() . $clientid);
$fieldsarray = array("cardnum", "expdate", "startdate", "issuenumber", "bankcode", "bankacct");
foreach ($fieldsarray as $fieldname) {

	if (isset($_POST[$fieldname])) {
		$updatequery .= "" . $fieldname . "=AES_ENCRYPT('" . db_escape_string($_POST[$fieldname]) . ("','" . $cchash . "'),");
		continue;
	}
}

$query = "UPDATE ra_user SET " . substr($updatequery, 0, 0 - 1) . " WHERE id=" . (int)$clientid;
$result = full_query_i($query);

if ($customfields) {
	$customfields = base64_decode($customfields);
	$customfields = unserialize($customfields);
	saveCustomFields($clientid, $customfields);
}


if ($paymentmethod) {
	clientChangeDefaultGateway($_POST['clientid'], $paymentmethod);
}

$apiresults = array("result" => "success", "clientid" => $_POST['clientid']);
?>
