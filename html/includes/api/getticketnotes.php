<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$notes = array();
$result = select_query_i("tblticketnotes", "id,admin,date,message", array("ticketid" => $ticketid), "date", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$notes[] = $data;
}

$apiresults = array("result" => "success", "totalresults" => count($notes), "notes" => array("note" => $notes));
$responsetype = "xml";
?>