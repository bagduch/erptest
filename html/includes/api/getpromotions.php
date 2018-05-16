<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$where = "";

if ($code) {
	$where['code'] = $code;
}

$result = select_query_i("ra_promos", "", $where, "code", "ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['promotions']['promotion'][] = $data;
}

$responsetype = "xml";
?>