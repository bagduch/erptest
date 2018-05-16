<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$activestatuses = $awaitingreplystatuses = array();
$query = "SELECT title,showactive,showawaiting FROM ra_tickettatuses";
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
	$result = select_query_i("ra_admin", "supportdepts", array("id" => $_SESSION['adminid']));
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
		$deptfilter = "WHERE ra_ticket_teams.id IN (" . db_build_in_array($deptids) . ") ";
	}
}

$result = full_query_i("SELECT id,name,(SELECT COUNT(id) FROM ra_ticket WHERE did=ra_ticket_teams.id AND status IN (" . db_build_in_array($awaitingreplystatuses) . ")) AS awaitingreply,(SELECT COUNT(id) FROM ra_ticket WHERE did=ra_ticket_teams.id AND status IN (" . db_build_in_array($activestatuses) . ")) AS opentickets FROM ra_ticket_teams " . $deptfilter . "ORDER BY name ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$apiresults['departments']['department'][] = array("id" => $data['id'], "name" => $data['name'], "awaitingreply" => $data['awaitingreply'], "opentickets" => $data['opentickets']);
}

$responsetype = "xml";
?>