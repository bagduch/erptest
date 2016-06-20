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

$result = select_query_i("tblclients", "id", array("id" => $clientid));
$data = mysqli_fetch_array($result);
$clientid = $data['id'];

if (!$clientid) {
	$apiresults = array("result" => "error", "message" => "Client ID Not Found");
	return null;
}

$credits = array();
$result = select_query_i("tblcredit", "id,date,description,amount,relid", array("clientid" => $clientid), "date", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$credits[] = $data;
}

$apiresults = array("result" => "success", "totalresults" => count($credits), "clientid" => $clientid, "credits" => array("credit" => $credits));
$responsetype = "xml";
?>