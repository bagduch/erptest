<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$currusername = get_query_val("ra_admin", "username", array("id" => $_SESSION['adminid']));
$result = full_query_i("SELECT DISTINCT adminusername FROM ra_adminlog WHERE lastvisit>='" . date("Y-m-d H:i:s", mktime(date("H"), date("i") - 15, date("s"), date("m"), date("d"), date("Y"))) . "' AND adminusername!='" . db_escape_string($currusername) . "' AND logouttime='0000-00-00' ORDER BY lastvisit ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result) + 1);
$apiresults['staffonline']['staff'][] = array("adminusername" => $currusername, "logintime" => date("Y-m-d H:i:s"), "ipaddress" => $remote_ip, "lastvisit" => date("Y-m-d H:i:s"));

while ($data = mysqli_fetch_assoc($result)) {
	$username = $data['adminusername'];
	$result2 = select_query_i("ra_adminlog", "adminusername,logintime,ipaddress,lastvisit", "lastvisit>='" . date("Y-m-d H:i:s", mktime(date("H"), date("i") - 15, date("s"), date("m"), date("d"), date("Y"))) . "' AND adminusername='" . db_escape_string($username) . "'", "lastvisit", "ASC", "0,1");
	$apiresults['staffonline']['staff'][] = mysqli_fetch_assoc($result2);
}

$responsetype = "xml";
?>