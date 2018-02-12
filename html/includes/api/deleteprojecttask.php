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

$projectid = (int)$_REQUEST['projectid'];
$taskid = (int)$_REQUEST['taskid'];

if (!$projectid) {
	$apiresults = array("result" => "error", "message" => "Project ID is Required");
	return null;
}


if (!$taskid) {
	$apiresults = array("result" => "error", "message" => "Task ID is Required");
	return null;
}

$result = select_query_i("mod_project", "", array("id" => (int)$projectid));
$data = mysqli_fetch_assoc($result);
$projectid = $data['id'];

if (!$projectid) {
	$apiresults = array("result" => "error", "message" => "Project ID Not Found");
	return null;
}

$result_taskid = select_query_i("mod_projecttasks", "id", array("id" => $_REQUEST['taskid']));
$data_taskid = mysqli_fetch_array($result_taskid);

if (!$data_taskid['id']) {
	$apiresults = array("result" => "error", "message" => "Task ID Not Found");
	return null;
}

delete_query("mod_projecttasks", array("id" => $taskid, "projectid" => $projectid));
$apiresults = array("result" => "success", "message" => "Task has been deleted");
?>