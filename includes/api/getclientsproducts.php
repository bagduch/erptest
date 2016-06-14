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


if (!function_exists("getCustomFields")) {
	require ROOTDIR . "/includes/customfieldfunctions.php";
}


if (!function_exists("getCartConfigOptions")) {
	require ROOTDIR . "/includes/configoptionsfunctions.php";
}

$where = array();

if ($clientid) {
	$where["tblcustomerservices.userid"] = $clientid;
}


if ($serviceid) {
	$where["tblcustomerservices.id"] = $serviceid;
}


if ($pid) {
	$where["tblcustomerservices.packageid"] = $pid;
}


if ($domain) {
	$where["tblcustomerservices.domain"] = $domain;
}


if ($username2) {
	$where["tblcustomerservices.username"] = $username2;
}

$result = select_query("tblcustomerservices", "COUNT(*)", $where, "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblservicegroups ON tblservicegroups.id=tblservices.gid");
$data = mysql_fetch_array($result);
$totalresults = $data[0];
$limitstart = (int)$limitstart;
$limitnum = (int)$limitnum;

if (!$limitnum) {
	$limitnum = 999999;
}

$result = select_query("tblcustomerservices", "tblcustomerservices.*,tblservices.name AS productname,tblservicegroups.name AS groupname,(SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblcustomerservices.server) AS serverdetails,(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblcustomerservices.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname", $where, "tblhosting`.`id", "ASC", "" . $limitstart . "," . $limitnum, "tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblservicegroups ON tblservicegroups.id=tblservices.gid");
$apiresults = array("result" => "success", "clientid" => $clientid, "serviceid" => $serviceid, "pid" => $pid, "domain" => $domain, "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysql_num_rows($result));

if (!$totalresults) {
	$apiresults['products'] = "";
}


while ($data = mysql_fetch_array($result)) {
	$id = $data['id'];
	$userid = $data['userid'];
	$orderid = $data['orderid'];
	$pid = $data['packageid'];
	$name = $data['productname'];
	$groupname = $data['groupname'];
	$server = $data['server'];
	$regdate = $data['regdate'];
	$domain = $data['domain'];
	$paymentmethod = $data['paymentmethod'];
	$paymentmethodname = $data['paymentmethodname'];
	$firstpaymentamount = $data['firstpaymentamount'];
	$recurringamount = $data['amount'];
	$billingcycle = $data['billingcycle'];
	$nextduedate = $data['nextduedate'];
	$servicestatus = $data['servicestatus'];
	$username = $data['username'];
	$password = decrypt($data['password']);
	$notes = $data['notes'];
	$subscriptionid = $data['subscriptionid'];
	$promoid = $data['promoid'];
	$ipaddress = $data['ipaddress'];
	$overideautosuspend = $data['overideautosuspend'];
	$overidesuspenduntil = $data['overidesuspenduntil'];
	$ns1 = $data['ns1'];
	$ns2 = $data['ns2'];
	$dedicatedip = $data['dedicatedip'];
	$assignedips = $data['assignedips'];
	$diskusage = $data['diskusage'];
	$disklimit = $data['disklimit'];
	$bwusage = $data['bwusage'];
	$bwlimit = $data['bwlimit'];
	$lastupdate = $data['lastupdate'];
	$serverdetails = $data['serverdetails'];
	$serverdetails = explode("|", $serverdetails);
	$customfieldsdata = array();
	$customfields = getCustomFields("product", $pid, $id, "on", "");
	foreach ($customfields as $customfield) {
		$customfieldsdata[] = array("id" => $customfield['id'], "name" => $customfield['name'], "value" => $customfield['value']);
	}

	$configoptionsdata = array();
	$configoptions = getCartConfigOptions($pid, "", $billingcycle, $id);
	foreach ($configoptions as $configoption) {
		switch ($configoption['optiontype']) {
		case 1: {
				$type = "dropdown";
				break;
			}

		case 2: {
				$type = "radio";
				break;
			}

		case 3: {
				$type = "yesno";
				break;
			}

		case 4: {
				$type = "quantity";
			}
		}


		if ($configoption['optiontype'] == "3" || $configoption['optiontype'] == "4") {
			$configoptionsdata[] = array("id" => $configoption['id'], "option" => $configoption['optionname'], "type" => $type, "value" => $configoption['selectedqty']);
			continue;
		}

		$configoptionsdata[] = array("id" => $configoption['id'], "option" => $configoption['optionname'], "type" => $type, "value" => $configoption['selectedoption']);
	}

	$apiresults['products']['product'][] = array("id" => $id, "clientid" => $userid, "orderid" => $orderid, "pid" => $pid, "regdate" => $regdate, "name" => $name, "groupname" => $groupname, "domain" => $domain, "dedicatedip" => $dedicatedip, "serverid" => $server, "servername" => $serverdetails[0], "serverip" => $serverdetails[1], "serverhostname" => $serverdetails[2], "firstpaymentamount" => $firstpaymentamount, "recurringamount" => $recurringamount, "paymentmethod" => $paymentmethod, "paymentmethodname" => $paymentmethodname, "billingcycle" => $billingcycle, "nextduedate" => $nextduedate, "status" => $servicestatus, "username" => $username, "password" => $password, "subscriptionid" => $subscriptionid, "promoid" => $promoid, "overideautosuspend" => $overideautosuspend, "overidesuspenduntil" => $overidesuspenduntil, "ns1" => $ns1, "ns2" => $ns2, "dedicatedip" => $dedicatedip, "assignedips" => $assignedips, "notes" => $notes, "diskusage" => $diskusage, "disklimit" => $disklimit, "bwusage" => $bwusage, "bwlimit" => $bwlimit, "lastupdate" => $lastupdate, "customfields" => array("customfield" => $customfieldsdata), "configoptions" => array("configoption" => $configoptionsdata));
}

$responsetype = "xml";
?>