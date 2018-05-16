<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!$limitstart) {
	$limitstart = 0;
}

$result = select_query_i("`ra_cancellations", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$query = "SELECT * FROM ra_cancellations LIMIT " . (int)$limitstart . "," . (int)$limitnum;
$result2 = full_query_i($query);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result), "packages" => array());

while ($data = mysqli_fetch_assoc($result2)) {
	$apiresults['packages']['package'][] = $data;
}

$responsetype = "xml";
?>