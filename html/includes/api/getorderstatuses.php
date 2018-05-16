<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$statuses = array("Pending" => 0, "Active" => 0, "Fraud" => 0, "Cancelled" => 0);
$result = full_query_i("SELECT status, COUNT(*) AS count FROM ra_orders GROUP BY status");
$apiresults = array("result" => "success", "totalresults" => 4);

while ($data = mysqli_fetch_array($result)) {
	$statuses[$data['status']] = $data['count'];
}

foreach ($statuses as $status => $ordercount) {
	$apiresults['statuses']['status'][] = array("title" => $status, "count" => $ordercount);
}

$responsetype = "xml";
?>