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

$where = array();

if ($type) {
	$where['type'] = $type;
}


if ($language) {
	$where['language'] = $language;
}

$result = select_query_i("tblemailtemplates", "", $where, "name", "ASC");
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$apiresults['emailtemplates']['emailtemplate'][] = array("id" => $data['id'], "name" => $data['name'], "subject" => $data['subject'], "custom" => $data['custom']);
}

$responsetype = "xml";
?>