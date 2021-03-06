<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!isset($_REQUEST['projectid'])) {
	$apiresults = array("result" => "error", "message" => "Project ID Not Set");
	return null;
}

$result = select_query_i("mod_project", "", array("id" => (int)$projectid));
$data = mysqli_fetch_assoc($result);
$projectid = $data['id'];

if (!$projectid) {
	$apiresults = array("result" => "error", "message" => "Project ID Not Found");
	return null;
}

$apiresults['projectinfo'] = $data;
$result_task = select_query_i("mod_projecttasks", "", array("projectid" => (int)$projectid));

while ($data_tasks = mysqli_fetch_assoc($result_task)) {
	$data_tasks['timelogs'] = array();
	$result_time = select_query_i("mod_projecttimes", "", array("taskid" => (int)$data_tasks['id']));

	while ($DATA = mysqli_fetch_assoc($result_time)) {
		$DATA['starttime'] = date("Y-m-d H:i:s", $DATA['start']);
		$DATA['endtime'] = date("Y-m-d H:i:s", $DATA['end']);
		$data_tasks['timelogs']['timelog'][] = $DATA;
	}

	$apiresults['tasks']['task'][] = $data_tasks;
}

$apiresults['messages'] = array();
$result_message = select_query_i("mod_projectmessages", "", array("projectid" => (int)$projectid));

while ($DATA_message = mysqli_fetch_assoc($result_message)) {
	$apiresults['messages']['message'][] = $DATA_message;
}

$responsetype = "xml";
?>