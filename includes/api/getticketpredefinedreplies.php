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

$result = select_query("tblticketpredefinedreplies", "COUNT(id)", $where);
$data = mysql_fetch_array($result);
$totalresults = $data[0];
$apiresults = array("result" => "success", "totalresults" => $totalresults);
$result = select_query("tblticketpredefinedreplies", "name,reply", array("catid" => $catid), "name", "ASC");

while ($data = mysql_fetch_assoc($result)) {
	$apiresults['predefinedreplies']['predefinedreply'][] = array("name" => $data['name'], "reply" => $data['reply']);
}

$responsetype = "xml";
?>