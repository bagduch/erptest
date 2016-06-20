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


if (!function_exists("saveCustomFields")) {
	require ROOTDIR . "/includes/customfieldfunctions.php";
}


if (isset($_REQUEST['projectid'])) {
	$result = select_query_i("mod_project", "", array("id" => (int)$projectid));
	$data = mysqli_fetch_assoc($result);
	$projectid = $data['id'];

	if (!$projectid) {
		$apiresults = array("result" => "error", "message" => "Project ID Not Found");
		return null;
	}
}


if (!isset($_REQUEST['timerid'])) {
	$apiresults = array("result" => "error", "message" => "Timer ID Not Set");
	return null;
}


if (isset($_REQUEST['timerid'])) {
	$result = select_query_i("mod_projecttimes", "", array("id" => $_REQUEST['timerid']));
	$data_timerid = mysqli_fetch_assoc($result);
	$timerid = $data_timerid['id'];

	if (!$timerid) {
		$apiresults = array("result" => "error", "message" => "Timer ID Not Found");
		return null;
	}
}

$timerid = $data_timerid['id'];

if (isset($_REQUEST['adminid'])) {
	$result_adminid = select_query_i("tbladmins", "id", array("id" => $_REQUEST['adminid']));
	$data_adminid = mysqli_fetch_array($result_adminid);

	if (!$data_adminid['id']) {
		$apiresults = array("result" => "error", "message" => "Admin ID Not Found");
		return null;
	}
}

$projectid = $_REQUEST['projectid'];
$adminid = $_REQUEST['adminid'];
$endtime = (isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : time());
$updateqry = array();

if ($projectid) {
	$updateqry['projectid'] = $projectid;
}


if ($adminid) {
	$updateqry['adminid'] = $adminid;
}


if ($timerid) {
	$updateqry['end'] = $endtime;
}

update_query("mod_projecttimes", $updateqry, array("id" => $timerid));
$apiresults = array("result" => "success", "message" => "Timer Has Ended");
?>