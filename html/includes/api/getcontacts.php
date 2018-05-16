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
	$where['userid'] = $userid;
}


if ($firstname) {
	$where['firstname'] = $firstname;
}


if ($lastname) {
	$where['lastname'] = $lastname;
}


if ($lastname) {
	$where['companyname'] = $companyname;
}


if ($email) {
	$where['email'] = $email;
}


if ($address1) {
	$where['address1'] = $address1;
}


if ($address2) {
	$where['address2'] = $address2;
}


if ($city) {
	$where['city'] = $city;
}


if ($state) {
	$where['state'] = $state;
}


if ($postcode) {
	$where['postcode'] = $postcode;
}


if ($country) {
	$where['country'] = $country;
}


if ($phonenumber) {
	$where['phonenumber'] = $phonenumber;
}


if ($subaccount) {
	$where['subaccount'] = "1";
}

$result = select_query_i("ra_user_contacts", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$result = select_query_i("ra_user_contacts", "", $where, "id", "ASC", "" . $limitstart . "," . $limitnum);
$apiresults = array("result" => "success", "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysqli_num_rows($result));

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['contacts']['contact'][] = $data;
}

$responsetype = "xml";
?>