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

if ($userid) {
	$where[] = "tblinvoices.userid='" . (int)$userid . "'";
}


if ($status) {
	if ($status == "Overdue") {
		$where[] = "tblinvoices.status='Unpaid' AND tblinvoices.duedate<'" . date("Ymd") . "'";
	}
	else {
		$where[] = "tblinvoices.status='" . db_escape_string($status) . "'";
	}
}

$where = implode(" AND ", $where);
$result = select_query_i("tblinvoices", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("tblinvoices", "tblinvoices.id,tblinvoices.userid,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblinvoices.*", $where, "tblinvoices`.`duedate", "DESC", "" . $limitstart . "," . $limitnum, "tblclients ON tblclients.id=tblinvoices.userid");
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