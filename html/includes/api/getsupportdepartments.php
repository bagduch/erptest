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

$activestatuses = $awaitingreplystatuses = array();
$query = "SELECT title,showactive,showawaiting FROM tblticketstatuses";
$result = full_query_i($query);

while ($data = mysqli_fetch_array($result)) {
	if ($data['showactive']) {
		$activestatuses[] = $data[0];
	}


	if ($data['showawaiting']) {
		$awaitingreplystatuses[] = $data[0];
	}
}

$deptfilter = "";

if (!$ignore_dept_assignments) {
	$result = select_query_i("tbladmins", "supportdepts", array("id" => $_SESSION['adminid']));
	$data = mysqli_fetch_array($result);
	$supportdepts = $data[0];
	$supportdepts = explode(",", $supportdepts);
	$deptids = array();
	foreach ($supportdepts as $id) {

		if (trim($id)) {
			$deptids[] = trim($id);
			continue;
		}
	}


	if (count($deptids)) {
		$deptfilter = "WHERE tblticketdepartments.id IN (" . db_build_in_array($deptids) . ") ";
	}
}

$result = full_query_i("SELECT id,name,(SELECT COUNT(id) FROM tbltickets WHERE did=tblticketdepartments.id AND status IN (" . db_build_in_array($awaitingreplystatuses) . ")) AS awaitingreply,(SELECT COUNT(id) FROM tbltickets WHERE did=tblticketdepartments.id AND status IN (" . db_build_in_array($activestatuses) . ")) AS opentickets FROM tblticketdepartments " . $deptfilter . "ORDER BY name ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$apiresults['departments']['department'][] = array("id" => $data['id'], "name" => $data['name'], "awaitingreply" => $data['awaitingreply'], "opentickets" => $data['opentickets']);
}

$responsetype = "xml";
?>