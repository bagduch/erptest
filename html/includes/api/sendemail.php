<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("pdfInvoice")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}


if ($_POST['custommessage']) {
	delete_query("ra_templates_mail", array("name" => "Mass Mail Template"));
	insert_query("ra_templates_mail", array("type" => $_POST['customtype'], "name" => "Mass Mail Template", "subject" => html_entity_decode($_POST['customsubject']), "message" => html_entity_decode($_POST['custommessage'])));
	$messagename = "Mass Mail Template";
}
else {
	$messagename = $_POST['messagename'];
}

$result = select_query_i("ra_templates_mail", "COUNT(*)", array("name" => $messagename));
$data = mysqli_fetch_array($result);

if (!$data[0]) {
	$apiresults = array("result" => "error", "message" => "Email Template not found");
	return null;
}


if (isset($customvars)) {
	if (!is_array($customvars)) {
		$customvars = unserialize(base64_decode($customvars));
	}
}
else {
	$customvars = array();
}

sendMessage($messagename, $_POST['id'], $customvars);

if ($_POST['customtext']) {
	delete_query("ra_templates_mail", array("name" => "Mass Mail Template"));
}

$apiresults = array("result" => "success");
?>