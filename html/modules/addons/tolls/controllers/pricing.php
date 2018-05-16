<?php

if(!defined("RA"))
	die("This file cannot be accessed directly");

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require(dirname(dirname(__FILE__)) . '/models/hdtolls.php');

$hdtolls = new hdtolls();

$result = $hdtolls->db->query("
	SELECT	`ra_user`.`id` AS clientid,
		`ra_user`.`firstname`,
		`ra_user`.`lastname`,
		`ra_user`.`companyname`,
		`ra_user`.`email`,
		`tblhosting`.`id` AS hostingid,
		`tblhosting`.`domain`,
		`tblhosting`.`username`
	
	FROM `ra_user`
	
		INNER JOIN `tblhosting` ON `tblhosting`.`userid` = `ra_user`.`id`
	
	WHERE	`tblhosting`.`packageid` IN (520, 560, 712, 738)
	
	ORDER BY `tblhosting`.`domain`
");
$data['customers'] = array();
while($row = $result->fetch_assoc()) {
	$row['domain_formatted'] = $hdtolls->format_phone($row['domain']);
	$data['customers'][$row['clientid']][$row['username']][] = $row;
}

$result = $hdtolls->db->query("
	SELECT DISTINCT `category`
	
	FROM `mod_hdtolls_rates`
	
	ORDER BY `category` ASC;
");
$data['zones'] = array();
while($row = $result->fetch_row()) {
	$data['zones'][] = $row[0];
}

$result = $hdtolls->db->query("
	SELECT *
	
	FROM `mod_hdtolls_options`
");
while($row = $result->fetch_assoc()) {
	foreach($data['customers'] as $clientid => &$client) {
		foreach($client as $username => &$lines) {
			foreach($lines as &$line) {
				if($line['hostingid'] == $row['hostingid']) {
					$line['zones'][$row['zone']] = array(
						'rate' => $row['rate'],
						'freemins' => $row['freemins']
					);
				}
			}
		}
	}
}

?>
