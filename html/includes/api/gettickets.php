<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$filters = array();

if ($deptid) {
	$filters[] = "did IN (" . mysqli_real_escape_string($deptid) . ")";
}


if ($clientid) {
	$filters[] = "userid='" . mysqli_real_escape_string($clientid) . "'";
}


if ($email) {
	$filters[] = "(email='" . mysqli_real_escape_string($email) . "' OR userid=(SELECT id FROM ra_user WHERE email='" . mysqli_real_escape_string($email) . "'))";
}


if ($status == "Awaiting Reply") {
	$statusfilter = "";
	$result = select_query_i("ra_tickettatuses", "title", array("showawaiting" => "1"));

	while ($data = mysqli_fetch_array($result)) {
		$statusfilter .= "'" . $data[0] . "',";
	}

	$statusfilter = substr($statusfilter, 0, 0 - 1);
	$filters[] = "ra_ticket.status IN (" . $statusfilter . ")";
}
else {
	if ($status == "All Active Tickets") {
		$statusfilter = "";
		$result = select_query_i("ra_tickettatuses", "title", array("showactive" => "1"));

		while ($data = mysqli_fetch_array($result)) {
			$statusfilter .= "'" . $data[0] . "',";
		}

		$statusfilter = substr($statusfilter, 0, 0 - 1);
		$filters[] = "ra_ticket.status IN (" . $statusfilter . ")";
	}
	else {
		if ($status == "My Flagged Tickets") {
			$statusfilter = "";
			$result = select_query_i("ra_tickettatuses", "title", array("showactive" => "1"));

			while ($data = mysqli_fetch_array($result)) {
				$statusfilter .= "'" . $data[0] . "',";
			}

			$statusfilter = substr($statusfilter, 0, 0 - 1);
			$filters[] = "ra_ticket.status IN (" . $statusfilter . ") AND flag='" . $_SESSION['adminid'] . "'";
		}
		else {
			if ($status) {
				$filters[] = "status='" . mysqli_real_escape_string($status) . "'";
			}
		}
	}
}


if ($subject) {
	$filters[] = "title LIKE '%" . mysqli_real_escape_string($subject) . "%'";
}


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
		$filters[] = "did IN (" . db_build_in_array(db_escape_numarray($deptids)) . ")";
	}
}

$where = implode(" AND ", $filters);
$result = select_query_i("ra_ticket", "COUNT(id)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart);
$result = select_query_i("ra_ticket", "", $where, "lastreply", "DESC", "" . $limitstart . "," . $limitnum);
$apiresults['numreturned'] = mysqli_num_rows($result);

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$tid = $data['tid'];
	$deptid = $data['did'];
	$userid = $data['userid'];
	$name = $data['name'];
	$email = $data['email'];
	$cc = $data['cc'];
	$c = $data['c'];
	$date = $data['date'];
	$subject = $data['title'];
	$message = $data['message'];
	$status = $data['status'];
	$priority = $data['urgency'];
	$admin = $data['admin'];
	$attachment = $data['attachment'];
	$lastreply = $data['lastreply'];
	$flag = $data['flag'];
	$service = $data['service'];

	if ($userid) {
		$result2 = select_query_i("ra_user", "", array("id" => $userid));
		$data = mysqli_fetch_array($result2);
		$name = $data['firstname'] . " " . $data['lastname'];

		if ($data['companyname']) {
			$name .= " (" . $data['companyname'] . ")";
		}

		$email = $data['email'];
	}

	$apiresults['tickets']['ticket'][] = array("id" => $id, "tid" => $tid, "deptid" => $deptid, "userid" => $userid, "name" => $name, "email" => $email, "cc" => $cc, "c" => $c, "date" => $date, "subject" => $subject, "status" => $status, "priority" => $priority, "admin" => $admin, "attachment" => $attachment, "lastreply" => $lastreply, "flag" => $flag, "service" => $service);
}

$responsetype = "xml";
?>