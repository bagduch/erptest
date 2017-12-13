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


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$where = array();

if ($status) {
	$where['status'] = $status;
}

$result = select_query_i("tbltodolist", "COUNT(id)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("tbltodolist", "", $where, "duedate", "DESC", "" . $limitstart . "," . $limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$data['title'] = $data['title'];
	$data['description'] = strip_tags($data['description']);
	$apiresults['items']['item'][] = $data;
}

$responsetype = "xml";
?>