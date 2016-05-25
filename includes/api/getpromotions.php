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

$where = "";

if ($code) {
	$where['code'] = $code;
}

$result = select_query("tblpromotions", "", $where, "code", "ASC");
$apiresults = array("result" => "success", "totalresults" => mysql_num_rows($result));

while ($data = mysql_fetch_assoc($result)) {
	$apiresults['promotions']['promotion'][] = $data;
}

$responsetype = "xml";
?>