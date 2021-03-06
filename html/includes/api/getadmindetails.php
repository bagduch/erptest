<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getAdminPermsArray")) {
	require ROOTDIR . "/includes/adminfunctions.php";
}

$result = select_query_i("ra_admin", "id,firstname,lastname,notes,signature,roleid,supportdepts", array("id" => $_SESSION['adminid']));
$data = mysqli_fetch_array($result);
$adminid = $data['id'];
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$notes = $data['notes'];
$signature = $data['signature'];
$adminroleid = $data['roleid'];
$supportdepts = $data['supportdepts'];
$apiresults = array("result" => "success", "adminid" => $adminid, "name" => "" . $firstname . " " . $lastname, "notes" => $notes, "signature" => $signature);
$adminpermsarray = getAdminPermsArray();
$result = select_query_i("ra_adminpriv", "", array("roleid" => $adminroleid));

while ($data = mysqli_fetch_array($result)) {
	$permid = $data['permid'];
	$apiresults->allowedpermissions .= $adminpermsarray[$permid] . ",";
}

$apiresults->departments .= $supportdepts;
$apiresults['allowedpermissions'] = substr($apiresults['allowedpermissions'], 0, 0 - 1);

if ($iphone) {
	$apiresults['iphone'] = true;
}

if ($windows8app) {
	$apiresults['windows8app'] = true;
}

if ($android) {
	$apiresults['android'] = true;
	$statuses = array();
	$result = select_query_i("ra_tickettatuses", "", "", "sortorder", "ASC");

	while ($data = mysqli_fetch_array($result)) {
		$statuses[$data['title']] = 0;
	}

	$where = "";

	if ($deptid) {
		$where = "WHERE did='" . mysqli_real_escape_string($deptid) . "'";
	}

	$result = full_query_i("SELECT status, COUNT(*) AS count FROM ra_ticket " . $where . " GROUP BY status");

	while ($data = mysqli_fetch_array($result)) {
		$statuses[$data['status']] = $data['count'];
	}

	foreach ($statuses as $status => $ticketcount) {
		$apiresults['supportstatuses']['status'][] = array("title" => $status, "count" => $ticketcount);
	}

	$deptartments = array();
	$result = full_query_i("SELECT id, name FROM ra_ticket_teams");

	while ($data = mysqli_fetch_assoc($result)) {
		$deptartments[$data['id']] = $data['name'];
	}

	foreach ($deptartments as $deptid => $deptname) {
		$apiresults['supportdepartments']['department'][] = array("id" => $deptid, "name" => $deptname, "count" => get_query_val("ra_ticket", "COUNT(id)", array("did" => $deptid)));
	}

	$gateways = array();
	$result = select_query_i("ra_modules_gateways", "gateway,value", array("setting" => "name"));

	while ($data = mysqli_fetch_assoc($result)) {
		$gateways[$data['gateway']] = $data['value'];
	}


	if (!function_exists("getGatewaysArray")) {
		require ROOTDIR . "/includes/gatewayfunctions.php";
	}

	$paymentmethods = getGatewaysArray();
	foreach ($paymentmethods as $module => $name) {
		$apiresults['paymentmethods']['paymentmethod'][] = array("module" => $module, "displayname" => $name);
	}
}

$apiresults['requesttime'] = date("Y-m-d H:i:s");
?>
