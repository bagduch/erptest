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


if (!$limitstart) {
	$limitstart = 0;
}


if (!$limitnum) {
	$limitnum = 25;
}

$search = mysqli_real_escape_string($search);
$result = full_query_i("SELECT SQL_CALC_FOUND_ROWS id, firstname, lastname, companyname, email, groupid, datecreated, status FROM tblclients WHERE email LIKE '" . $search . "%' OR firstname LIKE '" . $search . "%' OR lastname LIKE '" . $search . "%' OR companyname LIKE '" . $search . "%' ORDER BY firstname, lastname, companyname LIMIT " . (int)$limitstart . ", " . (int)$limitnum);
$result_count = full_query_i("SELECT FOUND_ROWS()");
$data = mysqli_fetch_array($result_count);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	$companyname = $data['companyname'];
	$email = $data['email'];
	$groupid = $data['groupid'];
	$datecreated = $data['datecreated'];
	$status = $data['status'];
	$apiresults['clients']['client'][] = array("id" => $id, "firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "datecreated" => $datecreated, "groupid" => $groupid, "status" => $status);
}

$responsetype = "xml";
?>