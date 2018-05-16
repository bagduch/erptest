<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("getCustomFields")) {
	require ROOTDIR . "/includes/customfieldfunctions.php";
}


if (!function_exists("getCartConfigOptions")) {
	require ROOTDIR . "/includes/configoptionsfunctions.php";
}

global $currency;

$currency = getCurrency();
$where = array();

if ($pid) {
	if (is_numeric($pid)) {
		$where[] = "id=" . (int)$pid;
	}
	else {
		$where[] = "id IN (" . db_escape_string($pid) . ")";
	}
}


if ($gid) {
	$where[] = "gid=" . (int)$gid;
}


if ($module) {
	$where[] = "servertype='" . db_escape_string($module) . "'";
}

$result = select_query_i("ra_catalog", "", implode(" AND ", $where));
$apiresults = array("result" => "success", "totalresults" => mysqli_num_rows($result));

while ($data = mysqli_fetch_array($result)) {
	$pid = $data['id'];
	$productarray = array("pid" => $data['id'], "gid" => $data['gid'], "type" => $data['type'], "name" => $data['name'], "description" => $data['description'], "module" => $data['servertype'], "paytype" => $data['paytype']);

	if ($data['stockcontrol']) {
		$productarray['stockcontrol'] = "true";
		$productarray['stocklevel'] = $data['qty'];
	}

	$result2 = select_query_i("ra_catalog_pricebook", "ra_currency.code,ra_currency.prefix,ra_currency.suffix,ra_catalog_pricebook.msetupfee,ra_catalog_pricebook.qsetupfee,ra_catalog_pricebook.ssetupfee,ra_catalog_pricebook.asetupfee,ra_catalog_pricebook.bsetupfee,ra_catalog_pricebook.tsetupfee,ra_catalog_pricebook.monthly,ra_catalog_pricebook.quarterly,ra_catalog_pricebook.semiannually,ra_catalog_pricebook.annually,ra_catalog_pricebook.biennially,ra_catalog_pricebook.triennially", array("type" => "product", "relid" => $pid), "code", "ASC", "", "ra_currency ON ra_currency.id=ra_catalog_pricebook.currency");

	while ($data = mysqli_fetch_assoc($result2)) {
		$code = $data['code'];
		unset($data['code']);
		$productarray['pricing'][$code] = $data;
	}

	$customfieldsdata = array();
	$customfields = getCustomFields("product", $pid, "", "", "on");
	foreach ($customfields as $field) {
		$customfieldsdata[] = array("id" => $field['id'], "name" => $field['name'], "description" => $field['description'], "required" => $field['required']);
	}

	$productarray['customfields']['customfield'] = $customfieldsdata;
	$configoptiondata = array();
	$configurableoptions = getCartConfigOptions($pid, "", "", "", true);
	foreach ($configurableoptions as $option) {
		$options = array();
		foreach ($option['options'] as $op) {
			$pricing = array();
			$result4 = select_query_i("ra_catalog_pricebook", "code,msetupfee,qsetupfee,ssetupfee,asetupfee,bsetupfee,tsetupfee,monthly,quarterly,semiannually,annually,biennially,triennially", array("type" => "configoptions", "relid" => $op['id']), "", "", "", "ra_currency ON ra_currency.id=ra_catalog_pricebook.currency");

			while ($oppricing = mysqli_fetch_assoc($result4)) {
				$currcode = $oppricing['code'];
				unset($oppricing['code']);
				$pricing[$currcode] = $oppricing;
			}

			$options['option'][] = array("id" => $op['id'], "name" => $op['name'], "recurring" => $op['recurring'], "pricing" => $pricing);
		}

		$configoptiondata[] = array("id" => $option['id'], "name" => $option['optionname'], "type" => $option['optiontype'], "options" => $options);
	}

	$productarray['configoptions']['configoption'] = $configoptiondata;
	$apiresults['products']['product'][] = $productarray;
}

$responsetype = "xml";
?>