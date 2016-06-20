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


if (!function_exists("getClientsDetails")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("runFraudCheck")) {
	require ROOTDIR . "/includes/fraudfunctions.php";
}

$result = select_query_i("tblorders", "id,userid,ipaddress,invoiceid", array("id" => $orderid));
$data = mysqli_fetch_array($result);
$orderid = $data[0];

if (!$orderid) {
	$apiresults = array("result" => "error", "message" => "Order ID Not Found");
	return false;
}

$userid = $data['userid'];
$ipaddress = $data['ipaddress'];
$invoiceid = $data['invoiceid'];

if (isset($_REQUEST['ipaddress'])) {
	$ipaddress = $_REQUEST['ipaddress'];
}

$fraudmodule = "maxmind";
$results = runFraudCheck($orderid, $fraudmodule, $userid, $ipaddress);
$fraudoutput = $results['fraudoutput'];
$fraudresults = getResultsArray($fraudoutput);
$error = $results['error'];

if ($results['userinput']) {
	$status = "User Input Required";
}
else {
	if ($results['error']) {
		$status = "Fail";
		update_query("tblorders", array("status" => "Fraud"), array("id" => $orderid));
		$result = select_query_i("tblcustomerservices", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tblcustomerservices", array("servicestatus" => "Fraud"), array("id" => $data['id'], "servicestatus" => "Pending"));
		}

		$result = select_query_i("tblserviceaddons", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tblserviceaddons", array("status" => "Fraud"), array("id" => $data['id'], "status" => "Pending"));
		}

		$result = select_query_i("tbldomains", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tbldomains", array("status" => "Fraud"), array("id" => $data['id'], "status" => "Pending"));
		}

		update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invoiceid, "status" => "Unpaid"));
	}
	else {
		$status = "Pass";
		update_query("tblorders", array("status" => "Pending"), array("id" => $orderid));
		$result = select_query_i("tblcustomerservices", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tblcustomerservices", array("servicestatus" => "Pending"), array("id" => $data['id'], "servicestatus" => "Fraud"));
		}

		$result = select_query_i("tblserviceaddons", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tblserviceaddons", array("status" => "Pending"), array("id" => $data['id'], "status" => "Fraud"));
		}

		$result = select_query_i("tbldomains", "id", array("orderid" => $orderid));

		while ($data = mysqli_fetch_array($result)) {
			update_query("tbldomains", array("status" => "Pending"), array("id" => $data['id'], "status" => "Fraud"));
		}

		update_query("tblinvoices", array("status" => "Unpaid"), array("id" => $invoiceid, "status" => "Cancelled"));
	}
}

$apiresults = array("result" => "success", "status" => $status, "results" => serialize($fraudresults));
$responsetype = "xml";
?>