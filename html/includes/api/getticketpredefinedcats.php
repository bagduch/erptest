<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$result = select_query_i("tblticketpredefinedcats", "COUNT(id)");
$data = mysqli_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults);
$result = full_query_i("SELECT c.*, COUNT(r.id) AS replycount FROM tblticketpredefinedcats c LEFT JOIN tblticketpredefinedreplies r ON c.id=r.catid GROUP BY c.id ORDER BY c.name ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$apiresults['predefinedreplies']['predefinedreply'][] = array("id" => $data['id'], "parentid" => $data['parentid'], "name" => $data['name'], "replycount" => $data['replycount']);
	$apiresults['categories']['category'][] = $data;
}

$responsetype = "xml";
?>