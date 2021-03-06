<?php
/** RA - Version 0.1 **/


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


if (!isset($_REQUEST['taskid'])) {
	$apiresults = array("result" => "error", "message" => "Task ID is Required");
	return null;
}


if (isset($_REQUEST['taskid'])) {
	$result = select_query_i("mod_projecttasks", "", array("id" => (int)$taskid));
	$data = mysqli_fetch_assoc($result);
	$taskid = $data['id'];

	if (!$taskid) {
		$apiresults = array("result" => "error", "message" => "Task ID Not Found");
		return null;
	}
}

$taskid = (int)$_REQUEST['taskid'];

if (isset($_REQUEST['adminid'])) {
	$result_adminid = select_query_i("ra_admin", "id", array("id" => $_REQUEST['adminid']));
	$data_adminid = mysqli_fetch_array($result_adminid);

	if (!$data_adminid['id']) {
		$apiresults = array("result" => "error", "message" => "Admin ID Not Found");
		return null;
	}
}

$projectid = $_REQUEST['projectid'];
$adminid = (isset($_REQUEST['adminid']) ? $data_adminid['id'] : 0);
$task = $_REQUEST['task'];
$notes = $_REQUEST['notes'];
$duedate = $_REQUEST['duedate'];
$completed = (isset($_REQUEST['completed']) ? 1 : 0);
$updateqry = array();

if ($projectid) {
	$updateqry['projectid'] = $projectid;
}


if ($task) {
	$updateqry['task'] = $task;
}


if ($notes) {
	$updateqry['notes'] = $notes;
}


if ($duedate) {
	$updateqry['duedate'] = $duedate;
}


if ($adminid) {
	$updateqry['adminid'] = $adminid;
}


if ($completed) {
	$updateqry['completed'] = $completed;
}

update_query("mod_projecttasks", $updateqry, array("id" => $taskid));
$apiresults = array("result" => "success", "message" => "Task has been updated");
?>