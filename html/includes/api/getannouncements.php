<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$result = select_query_i("tblannouncements", "COUNT(*)", "");
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("tblannouncements", "", "", "date", "DESC", "" . $limitstart . "," . $limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['announcements']['announcement'][] = $data;
}

$responsetype = "xml";
?>