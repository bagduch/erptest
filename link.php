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

require "init.php";
$id = (int)$ra->get_req_var("id");
$url = get_query_val("tbllinks", "link", array("id" => $id));

if ($url) {
	update_query("tbllinks", array("clicks" => "+1"), array("id" => $id));
	RA_Cookie::set("LinkID", $id, "3m");
	run_hook("LinkTracker", array("linkid" => $id));
	header("Location: " . $url);
	exit();
	return 1;
}

redir("", "index.php");
?>