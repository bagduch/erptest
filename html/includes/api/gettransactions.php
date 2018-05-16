<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$where = array();

if ($clientid) {
	$where['userid'] = $clientid;
}


if ($invoiceid) {
	$where['invoiceid'] = $invoiceid;
}


if ($transid) {
	$where['transid'] = $transid;
}

$result = select_query_i("tblaccounts", "", $where);
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result), "startnumber" => 0, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['transactions']['transaction'][] = $data;
}

$responsetype = "xml";
?>