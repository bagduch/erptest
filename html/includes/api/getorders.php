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

$query = " FROM ra_orders o
    LEFT JOIN ra_user c ON o.userid=c.id
    LEFT JOIN ra_modules_gateways p ON o.paymentmethod=p.gateway AND p.setting='name'
    LEFT JOIN ra_bills i ON o.invoiceid=i.id";
$where = array();

if ($id) {
	$where[] = "o.id=" . mysqli_real_escape_string($id);
}


if ($userid) {
	$where[] = "o.userid=" . mysqli_real_escape_string($userid);
}


if ($status) {
	$where[] = "o.status='" . mysqli_real_escape_string($status) . "'";
}


if (count($where)) {
	$query .= " WHERE " . implode(" AND ", $where);
}

$result_count = full_query_i("SELECT COUNT(o.id)" . $query);
$data = mysqli_fetch_array($result_count);
$totalresults = $data[0];
$result = full_query_i("SELECT o.*, p.value AS paymentmethodname, i.status AS paymentstatus, CONCAT(c.firstname,' ',c.lastname) AS name" . $query . " ORDER BY o.id DESC LIMIT " . (int)$limitstart . "," . (int)$limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($orderdata = mysqli_fetch_assoc($result)) {
	$orderid = $orderdata['id'];
	$userid = $orderdata['userid'];
	$fraudmodule = $orderdata['fraudmodule'];
	$fraudoutput = $orderdata['fraudoutput'];
	$currency = getCurrency($userid);
	$orderdata['currencyprefix'] = $currency['prefix'];
	$orderdata['currencysuffix'] = $currency['suffix'];
	$frauddata = "";

	if ($fraudmodule) {
		if (!isValidforPath($fraudmodule)) {
			exit("Invalid Fraud Module Name");
		}

		$fraudpath = ROOTDIR . ("/modules/fraud/" . $fraudmodule . "/" . $fraudmodule . ".php");

		if (file_exists($fraudpath)) {
			require_once $fraudpath;
			$fraudresults = getResultsArray($fraudoutput);

			if ($fraudresults) {
				foreach ($fraudresults as $key => $value) {
					$frauddata .= ("" . $key . " => " . $value . "\r\n");
				}
			}
		}
	}

	$orderdata['fraudoutput'] = $fraudoutput;
	$orderdata['frauddata'] = $frauddata;
	$lineitems = array();
	$result2 = select_query_i("tblcustomerservices", "", array("orderid" => $orderid));

	while ($data = mysqli_fetch_array($result2)) {
		$serviceid = $data['id'];
		$domain = $data['domain'];
		$billingcycle = $data['billingcycle'];
		$hostingstatus = $data['servicestatus'];
		$firstpaymentamount = formatCurrency($data['firstpaymentamount']);
		$packageid = $data['packageid'];
		$result3 = select_query_i("ra_catalog", "ra_catalog.name,ra_catalog.type,ra_catalog.welcomeemail,ra_catalog.autosetup,ra_catalog.servertype,ra_catalog_groups.name AS groupname", array("ra_catalog.id" => $packageid), "", "", "", "ra_catalog_groups ON ra_catalog.gid=ra_catalog_groups.id");
		$data = mysqli_fetch_array($result3);
		$groupname = $data['groupname'];
		$productname = $data['name'];
		$producttype = $data['type'];

		if ($producttype == "hostingaccount") {
			$producttype = "Hosting Account";
		}
		else {
			if ($producttype == "reselleraccount") {
				$producttype = "Reseller Account";
			}
			else {
				if ($producttype == "server") {
					$producttype = "Dedicated/VPS Server";
				}
				else {
					if ($producttype == "other") {
						$producttype = "Other Product/Service";
					}
				}
			}
		}

		$lineitems['lineitem'][] = array("type" => "product", "relid" => $serviceid, "producttype" => $producttype, "product" => $groupname . " - " . $productname, "domain" => $domain, "billingcycle" => $billingcycle, "amount" => $firstpaymentamount, "status" => $hostingstatus);
	}

	$predefinedaddons = array();
	$result2 = select_query_i("tbladdons", "", "");

	while ($data = mysqli_fetch_array($result2)) {
		$addon_id = $data['id'];
		$addon_name = $data['name'];
		$addon_welcomeemail = $data['welcomeemail'];
		$predefinedaddons[$addon_id] = array("name" => $addon_name, "welcomeemail" => $addon_welcomeemail);
	}

	$result2 = select_query_i("ra_catalog_user_sales_addons", "", array("orderid" => $orderid));

	while ($data = mysqli_fetch_array($result2)) {
		$aid = $data['id'];
		$hostingid = $data['hostingid'];
		$addonid = $data['addonid'];
		$name = $data['name'];
		$billingcycle = $data['billingcycle'];
		$addonamount = $data['recurring'] + $data['setupfee'];
		$addonstatus = $data['status'];
		$regdate = $data['regdate'];
		$nextduedate = $data['nextduedate'];
		$addonamount = formatCurrency($addonamount);

		if (!$name) {
			$name = $predefinedaddons[$addonid]['name'];
		}

		$lineitems['lineitem'][] = array("type" => "addon", "relid" => $aid, "producttype" => "Addon", "product" => $name, "domain" => "", "billingcycle" => $billingcycle, "amount" => $addonamount, "status" => $addonstatus);
	}

	$result2 = select_query_i("tbldomains", "", array("orderid" => $orderid));

	while ($data = mysqli_fetch_array($result2)) {
		$domainid = $data['id'];
		$type = $data['type'];
		$domain = $data['domain'];
		$registrationperiod = $data['registrationperiod'];
		$status = $data['status'];
		$regdate = $data['registrationdate'];
		$nextduedate = $data['nextduedate'];
		$domainamount = formatCurrency($data['firstpaymentamount']);
		$domainregistrar = $data['registrar'];
		$dnsmanagement = $data['dnsmanagement'];
		$emailforwarding = $data['emailforwarding'];
		$idprotection = $data['idprotection'];
		$lineitems['lineitem'][] = array("type" => "domain", "relid" => $domainid, "producttype" => "Domain", "product" => $type, "domain" => $domain, "billingcycle" => $registrationperiod, "amount" => $domainamount, "status" => $status, "dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection);
	}

	$renewals = $orderdata['renewals'];

	if ($renewals) {
		$renewals = explode(",", $renewals);
		foreach ($renewals as $renewal) {
			$renewal = explode("=", $renewal);
			$domainid = $renewal[0];
			$registrationperiod = $renewal[1];
			$result = select_query_i("tbldomains", "", array("id" => $domainid));
			$data = mysqli_fetch_array($result);
			$domainid = $data['id'];
			$type = $data['type'];
			$domain = $data['domain'];
			$registrar = $data['registrar'];
			$status = $data['status'];
			$regdate = $data['registrationdate'];
			$nextduedate = $data['nextduedate'];
			$domainamount = formatCurrency($data['recurringamount']);
			$domainregistrar = $data['registrar'];
			$dnsmanagement = $data['dnsmanagement'];
			$emailforwarding = $data['emailforwarding'];
			$idprotection = $data['idprotection'];
			$lineitems['lineitem'][] = array("type" => "renewal", "relid" => $domainid, "producttype" => "Domain", "product" => "Renewal", "domain" => $domain, "billingcycle" => $registrationperiod, "amount" => $domainamount, "status" => $status, "dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection);
		}
	}


	$apiresults['orders']['order'][] = array_merge($orderdata, array("lineitems" => $lineitems));
}

$responsetype = "xml";
?>