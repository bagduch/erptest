<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$apiresults = array("result" => "success");
update_query("ra_admin", array("notes" => $notes), array("id" => $_SESSION['adminid']));
?>