<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

global $currency;

$currency = getCurrency();
$where = array();

if ($serviceid) {
	if (is_numeric($serviceid)) {
		$where[] = "hostingid=" . (int)$serviceid;
	}
	else {
		$serviceids = explode(",", $serviceid);
		$serviceids = db_build_in_array(db_escape_numarray($serviceids));

		if ($serviceids) {
			$where[] = "hostingid IN (" . $serviceids . ")";
		}
	}
}


if ($clientid) {
	$result = select_query_i("tblcustomerservices", "", array("userid" => $clientid));
	$hostingids = array();

	while ($data = mysqli_fetch_array($result)) {
		$hostingids[] = (int)$data['id'];
	}

	$where[] = "hostingid IN (" . db_build_in_array($hostingids) . ")";
}


if ($addonid) {
	$where[] = "addonid=" . (int)$addonid;
}

$result = select_query_i("ra_catalog_user_sales_addons", "", implode(" AND ", $where));
$apiresults = array("result" => "success", "serviceid" => $serviceid, "clientid" => $clientid, "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$aid = $data['id'];
	$addonarray = array("id" => $data['id'], "userid" => get_query_val("tblcustomerservices", "userid", array("id" => $data['hostingid'])), "orderid" => $data['orderid'], "serviceid" => $data['hostingid'], "addonid" => $data['addonid'], "name" => $data['name'], "setupfee" => $data['setupfee'], "recurring" => $data['recurring'], "billingcycle" => $data['billingcycle'], "tax" => $data['tax'], "status" => $data['status'], "regdate" => $data['regdate'], "nextduedate" => $data['nextduedate'], "nextinvoicedate" => $data['nextinvoicedate'], "paymentmethod" => $data['paymentmethod'], "notes" => $data['notes']);
	$apiresults['addons']['addon'][] = $addonarray;
}

$responsetype = "xml";
?>