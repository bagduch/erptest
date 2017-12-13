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

$where = "";

if ($code) {
	$where['code'] = $code;
}

$result = select_query_i("tblpromotions", "", $where, "code", "ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['promotions']['promotion'][] = $data;
}

$responsetype = "xml";
?>