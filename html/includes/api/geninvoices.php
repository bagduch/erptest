<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("createInvoices")) {
	require ROOTDIR . "/includes/processinvoices.php";
}


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("updateInvoiceTotal")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}


if (!function_exists("getGatewaysArray")) {
	require ROOTDIR . "/includes/gatewayfunctions.php";
}



if (!function_exists("ModuleBuildParams")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}


if ($clientid) {
	$clientid = get_query_val("ra_user", "id", array("id" => $clientid));

	if (!$clientid) {
		$apiresults = array("result" => "error", "message" => "Client ID Not Found");
		return null;
	}
}

$cronreport = "";

if ((is_array($serviceids) || is_array($addonids)) || is_array($domainids)) {
	$specificitems = array("products" => $serviceids, "addons" => $addonids, "domains" => $domainids);
	$invoiceid = createInvoices($clientid, $noemails, "", $specificitems);
}
else {
	$invoiceid = createInvoices($clientid, $noemails);
}

$cronreport = explode(" ", $cronreport, 2);
$numcreated = $cronreport[0];
$apiresults = array("result" => "success", "numcreated" => $numcreated);

if ($clientid) {
	$apiresults['latestinvoiceid'] = $invoiceid;
}

?>
