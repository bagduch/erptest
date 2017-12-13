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
$clientid = $data[0];

if (!$clientid) {
	$apiresults = array("status" => "error", "message" => "Client ID Not Found");
	return null;
}


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$where = array();
$where['userid'] = $clientid;

if ($date) {
	$where['date'] = array("sqltype" => "LIKE", "value" => $date);
}


if ($subject) {
	$where['subject'] = array("sqltype" => "LIKE", "value" => $subject);
}

$result = select_query_i("tblemails", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("tblemails", "", $where, "id", "DESC", "" . $limitstart . "," . $limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['emails']['email'][] = $data;
}

$responsetype = "xml";
?>