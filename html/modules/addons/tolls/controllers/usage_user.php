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
			AND `ra_user`.`id` = '" . $hdtolls->db->real_escape_string($_GET['c']) . "'
	
	ORDER BY `tblhosting`.`domain`
");

while($row = $result->fetch_assoc()) {
	$data['phonelines'][$row['username']][] = $row;
}

$data['totals'] = array();

foreach($data['phonelines'] as $username => $phonelines) {
	foreach($phonelines as $index => $line) {
		$result_tolls = $hdtolls->db->query("
			SELECT SUBSTRING(MIN(`start`), 1, 7) AS month, COUNT(*) AS total_calls,
			SUM(`billsec`) AS billsec, SUM(`used_included_secs`) AS total_used_included_secs
			
			FROM `mod_hdtolls_all_calls`
			
			WHERE `accountcode` = '" . $hdtolls->db->real_escape_string($line['hostingid']) . "'
				AND `disposition` = 'ANSWERED'
			
			GROUP BY SUBSTRING(`start`, 1, 7)
		");
		
		$data['phonelines'][$username][$index]['domain_formatted'] = $hdtolls->format_phone($line['domain']);
		
		// if there are any outbound calls on record
		if($result_tolls->num_rows) {
			while($row = $result_tolls->fetch_assoc()) {
				$result_tolls_all = $hdtolls->db->query("
					SELECT SUM(`bill_amount`) as total_month_bill
					
					FROM `mod_hdtolls_all_calls`
					
					WHERE `accountcode` = '" . $line['hostingid'] . "'
						AND `disposition` = 'ANSWERED'
						AND `start` LIKE '" . $row['month'] . "-%'
				");
				$result_invoiced = $hdtolls->db->query("
					SELECT `invoiceid`
					
					FROM `mod_hdtolls_billing2`
					
					WHERE `hostingid` = '" . $hdtolls->db->real_escape_string($line['hostingid']) . "'
						AND `period` = '" . $hdtolls->db->real_escape_string($row['month']) . "'
				");
				$invoiced = ($result_invoiced->num_rows) ? $result_invoiced->fetch_row() : array(false);
				list($invoiced) = $invoiced;
				
				// check if client's invoice has been purposely ignored
				// if so, set $invoiced to BOOLEAN TRUE, rather than the invoice ID
				if(is_null($invoiced)) {
					$invoiced = true;
				}
				
				// populate usage values to display to the end-user
				list($data['phonelines'][$username][$index]['usage'][$row['month']]['month_bill']) = $result_tolls_all->fetch_row();														
				$data['phonelines'][$username][$index]['usage'][$row['month']]['total_calls'] = $row['total_calls'];
				$data['phonelines'][$username][$index]['usage'][$row['month']]['total_used_included_secs_formatted'] = $hdtolls->format_sec2min($row['total_used_included_secs']);
				$data['phonelines'][$username][$index]['usage'][$row['month']]['billsec'] = $row['billsec'];
				$data['phonelines'][$username][$index]['usage'][$row['month']]['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
				$data['phonelines'][$username][$index]['usage'][$row['month']]['can_invoice'] = ($row['month'] != date("Y-m") && !$invoiced) ? true : false;	
				$data['phonelines'][$username][$index]['usage'][$row['month']]['invoiced'] = $invoiced;							
				@$data['totals'][$username][$index]['calls'] += $row['total_calls'];
				@$data['totals'][$username][$index]['billed_time'] += $row['billsec'];
				@$data['totals'][$username][$index]['free_time'] += $row['total_used_included_secs'];
				@$data['totals'][$username][$index]['bill'] += $data['phonelines'][$username][$index]['usage'][$row['month']]['month_bill'];
			}
		}
		else {
			// no calls on record, set relevant values to 0
			$data['phonelines'][$username][$index]['usage'] = array("No calls on record" => 0);
			@$data['totals'][$username][$index]['calls'] = 0;
			@$data['totals'][$username][$index]['billed_time'] = 0;
			@$data['totals'][$username][$index]['free_time'] = 0;
		}
		
		// set totals for 
		$data['totals'][$username][$index]['billed_time_formatted'] = $hdtolls->format_sec2min($data['totals'][$username][$index]['billed_time']);
		$data['totals'][$username][$index]['free_time_formatted'] = $hdtolls->format_sec2min($data['totals'][$username][$index]['free_time']);
	}
}



?>
