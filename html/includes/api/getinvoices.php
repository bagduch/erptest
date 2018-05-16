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

$where = array();

if ($userid) {
	$where[] = "ra_bills.userid='" . (int)$userid . "'";
}


if ($status) {
	if ($status == "Overdue") {
		$where[] = "ra_bills.status='Unpaid' AND ra_bills.duedate<'" . date("Ymd") . "'";
	}
	else {
		$where[] = "ra_bills.status='" . db_escape_string($status) . "'";
	}
}

$where = implode(" AND ", $where);
$result = select_query_i("ra_bills", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("ra_bills", "ra_bills.id,ra_bills.userid,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_bills.*", $where, "ra_bills`.`duedate", "DESC", "" . $limitstart . "," . $limitnum, "ra_user ON ra_user.id=ra_bills.userid");
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result), "invoices" => array());

while ($data = mysqli_fetch_assoc($result)) {
	$currency = getCurrency($userid);
	$data['currencycode'] = $currency['code'];
	$data['currencyprefix'] = $currency['prefix'];
	$data['currencysuffix'] = $currency['suffix'];
	$apiresults['invoices']['invoice'][] = $data;
}

$responsetype = "xml";
?>