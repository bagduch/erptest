<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("tblcurrencies", "", "", "id", "ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$code = $data['code'];
	$prefix = $data['prefix'];
	$suffix = $data['suffix'];
	$format = $data['format'];
	$rate = $data['rate'];
	$apiresults['currencies']['currency'][] = array("id" => $id, "code" => $code, "prefix" => $prefix, "suffix" => $suffix, "format" => $format, "rate" => $rate);
}

$responsetype = "xml";
?>