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

$where = array();

if ($userid) {
	$where['userid'] = (int)$userid;
}


if ($title) {
	$where['title'] = array("sqltype" => "LIKE", "value" => $title);
}


if ($ticketids) {
	$where['ticketids'] = array("sqltype" => "LIKE", "value" => $ticketids);
}


if ($invoiceids) {
	$where['invoiceids'] = array("sqltype" => "LIKE", "value" => $invoiceids);
}


if ($notes) {
	$where['notes'] = array("sqltype" => "LIKE", "value" => $notes);
}


if (isset($_REQUEST['adminid'])) {
	$where['adminid'] = (int)$_REQUEST['adminid'];
}


if ($status) {
	$where['status'] = array("sqltype" => "LIKE", "value" => $status);
}


if ($created) {
	$where['created'] = array("sqltype" => "LIKE", "value" => $created);
}


if ($duedate) {
	$where['duedate'] = array("sqltype" => "LIKE", "value" => $duedate);
}


if ($completed) {
	$where['completed'] = array("sqltype" => "LIKE", "value" => $completed);
}


if ($lastmodified) {
	$where['lastmodified'] = array("sqltype" => "LIKE", "value" => $lastmodified);
}

$result = select_query_i("mod_project", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("mod_project", "", $where, "id", "ASC", (int)$limitstart . "," . (int)$limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result), "projects" => array());

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['projects']['project'] = $data;
}

$responsetype = "xml";
?>