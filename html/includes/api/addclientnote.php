<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$userid = get_query_val("tblclients", "id", array("id" => $userid));

if (!$userid) {
	$apiresults = array("result" => "error", "message" => "Client ID not found");
	return null;
}


if (!$notes) {
	$apiresults = array("result" => "error", "message" => "Notes can not be empty");
	return null;
}

$sticky = $sticky ? 1 : 0;
$noteid = insert_query("tblnotes", array("userid" => $userid, "adminid" => $_SESSION['adminid'], "created" => "now()", "modified" => "now()", "note" => nl2br($notes), "sticky" => $sticky));
$apiresults = array("result" => "success", "noteid" => $noteid);
?>