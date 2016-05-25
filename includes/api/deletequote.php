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

$result = select_query("tblquotes", "", array("id" => $quoteid));
$data = mysql_fetch_array($result);
$quoteid = $data['id'];

if (!$quoteid) {
	$apiresults = array("result" => "error", "message" => "Quote ID Not Found");
	return null;
}

delete_query("tblquotes", array("id" => $quoteid));
delete_query("tblquoteitems", array("quoteid" => $quoteid));
$apiresults = array("result" => "success");
?>