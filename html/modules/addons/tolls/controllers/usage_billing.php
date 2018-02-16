<?php

if(!defined("RA"))
	die("This file cannot be accessed directly");

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

if(!$_GET['period']) {
	header("location: " . html_entity_decode($_SERVER['REQUEST_URI']) . '&period=' . date('Y-m', strtotime('last month')));
	exit;
}

require(dirname(dirname(__FILE__)) . '/models/hdtolls.php');

$hdtolls = new hdtolls();

$result_billing = $hdtolls->db->query("
	SELECT *
	
	FROM `mod_hdtolls_billing2`
	
	WHERE `period` = '" . $hdtolls->db->real_escape_string($_GET['period']) . "'
");

while($row = $result_billing->fetch_assoc()) {
	$data['invoices'][] = $row;
	@$data['entire_invoice_total'] += $row['amount'];
}

$result_clients = $hdtolls->db->query("
	SELECT `tblclients`.`id` AS clientid,
		`tblclients`.`firstname`,
		`tblclients`.`lastname`,
		`tblclients`.`companyname`,
		`tblclients`.`email`,
		`tblhosting`.`id` AS hostingid,
		`tblhosting`.`domain`,
		`tblhosting`.`username`
	
	FROM `tblclients`
	
		INNER JOIN `tblhosting` ON `tblhosting`.`userid` = `tblclients`.`id`
	
	WHERE `tblhosting`.`packageid` IN (520, 560, 712, 738)
	
	ORDER BY `tblhosting`.`domain`
");

while($row = $result_clients->fetch_assoc()) {
	$data['clients'][$row['clientid']] = $row;
}

$data['back'] = date('Y-m', strtotime($_GET['period'] . ' - 1 month'));
$data['next'] = date('Y-m', strtotime($_GET['period'] . ' + 1 month'));

?>
