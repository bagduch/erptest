<?php

if(!defined("RA"))
	die("This file cannot be accessed directly");

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

if(!$_SESSION['uid']) {
	header("location: /");
	exit;
}


require(dirname(dirname(__FILE__)) . '/models/hdtolls.php');

$hdtolls = new hdtolls();

// if there is no individual DDI set in the request, we are showing the landing page
if(!@$_GET['ddi']) {
		
	// top-half of 'My SIP' landing page after this point
				
	$data['usage'] = array();
	$data['totals'] = array();
		
	$result = $hdtolls->db->query("
		SELECT SUBSTRING(`start`, 1, 7) AS start, `category`,
			SUM(`bill_amount`) AS total_bill, SUM(`billsec`) AS billsec,
			SUM(`used_included_secs`) AS freesec, COUNT(*) as total_calls
	
		FROM `mod_hdtolls_all_calls`
		
		WHERE `accountcode` IN (
				SELECT  `id`
				FROM  `tblhosting`
				WHERE  `userid` = '" . $hdtolls->db->real_escape_string($_SESSION['uid']) . "'
					AND  `packageid` IN (520, 560, 712, 738)
			)
			AND `accountcode` != ''
			AND `disposition` = 'ANSWERED'
			AND `start` LIKE '" . date("Y-m") . "-%'
		
		GROUP BY SUBSTRING(`start`, 1, 7), `category`
	");
	
	while($row = $result->fetch_assoc()) {
		$row['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
		$row['freesec_formatted'] = $hdtolls->format_sec2min($row['freesec']);
		$data['usage'][$row['start']][$row['category']] = $row;
	}
	
	foreach($data['usage'] as $month => $categories) {
		foreach($categories as $category => $item) {
			@$data['totals'][$month]['total_calls'] += $item['total_calls'];
			@$data['totals'][$month]['freesec'] += $item['freesec'];
			@$data['totals'][$month]['billsec'] += $item['billsec'];
			@$data['totals'][$month]['total_bill'] += $item['total_bill'];
		}
		
		@$data['totals']['sum']['total_calls'] += @$data['totals'][$month]['total_calls'];
		@$data['totals']['sum']['freesec'] += @$data['totals'][$month]['freesec'];
		@$data['totals']['sum']['billsec'] += @$data['totals'][$month]['billsec'];
		@$data['totals']['sum']['total_bill'] += @$data['totals'][$month]['total_bill'];
		
		$data['totals']['sum']['freesec_formatted'] = $hdtolls->format_sec2min($data['totals']['sum']['freesec']);
		$data['totals']['sum']['billsec_formatted'] = $hdtolls->format_sec2min($data['totals']['sum']['billsec']);
	}
	
	// top-half of 'My SIP' landing page before this point
	//
	// bottom-half of 'My SIP' landing page after this point
	
	$result = $hdtolls->db->query("
		SELECT	`tblclients`.`id` AS clientid,
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
			AND `tblclients`.`id` = '" . $hdtolls->db->real_escape_string($_SESSION['uid']) . "'
		
		ORDER BY `tblhosting`.`domain`
	");
	
	while($row = $result->fetch_assoc()) {
		$data['lines'][$row['username']][] = $row;
	}
	
	foreach($data['lines'] as $username => $lines) {
		foreach($lines as $index => $line) {
			$result_tolls = $hdtolls->db->query("
				SELECT SUBSTRING(MIN(`start`), 1, 7) AS month, COUNT(*) AS total_calls,
				SUM(`billsec`) AS billsec, SUM(`used_included_secs`) AS total_used_included_secs,
				MAX(`end`) AS last_call
				
				FROM `mod_hdtolls_all_calls`
				
				WHERE `accountcode` = '" . $hdtolls->db->real_escape_string($line['hostingid']) . "'
					AND `accountcode` != ''
					AND `disposition` = 'ANSWERED'
				
				GROUP BY SUBSTRING(`start`, 1, 7)
			");
			
			$data['lines'][$username][$index]['domain_formatted'] = $hdtolls->format_phone($line['domain']);
			
			if($result_tolls->num_rows) {
				while($row = $result_tolls->fetch_assoc()) {
					$result_tolls_all = $hdtolls->db->query("
						SELECT SUM(`bill_amount`) as total_month_bill
						
						FROM `mod_hdtolls_all_calls`
						
						WHERE `accountcode` = '" . $line['hostingid'] . "'
							AND `accountcode` != ''
							AND `disposition` = 'ANSWERED'
							AND `start` LIKE '" . $row['month'] . "-%'												
					");
					$result_invoiced = $hdtolls->db->query("
						SELECT `invoiceid`
						
						FROM `mod_hdtolls_billing2`
						
						WHERE `clientid` = '" . $hdtolls->db->real_escape_string($line['clientid']) . "'
							AND `hostingid` = '" . $hdtolls->db->real_escape_string($line['hostingid']) . "'
							AND `period` = '" . $hdtolls->db->real_escape_string($row['month']) . "'
					");
					$invoiced = ($result_invoiced->num_rows) ? $result_invoiced->fetch_row() : array(false);
					list($invoiced) = $invoiced;
					
					// check if client's invoice has been purposely ignored
					// if so, set $invoiced to BOOLEAN TRUE, rather than the invoice ID
					if(is_null($invoiced)) {
						$invoiced = true;
					}
					
					$data['lines'][$username][$index]['last_call_formatted'] = $hdtolls->time_ago($row['last_call']);
					list($data['lines'][$username][$index]['usage'][$row['month']]['month_bill']) = $result_tolls_all->fetch_row();														
					$data['lines'][$username][$index]['usage'][$row['month']]['total_calls'] = $row['total_calls'];
					$data['lines'][$username][$index]['usage'][$row['month']]['total_used_included_secs_formatted'] = $hdtolls->format_sec2min($row['total_used_included_secs']);
					$data['lines'][$username][$index]['usage'][$row['month']]['billsec'] = $row['billsec'];
					$data['lines'][$username][$index]['usage'][$row['month']]['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
					$data['lines'][$username][$index]['usage'][$row['month']]['can_invoice'] = ($row['month'] != date("Y-m") && !$invoiced) ? true : false;	
					$data['lines'][$username][$index]['usage'][$row['month']]['invoiced'] = $invoiced;							
					@$data['totals'][$username][$index]['calls'] += $row['total_calls'];
					@$data['totals'][$username][$index]['billed_time'] += $row['billsec'];
					@$data['totals'][$username][$index]['free_time'] += $row['total_used_included_secs'];
					@$data['totals'][$username][$index]['bill'] += $data['lines'][$username][$index]['usage'][$row['month']]['month_bill'];
				}
			}
			else {
				$data['lines'][$username][$index]['usage'] = array("No calls on record" => 0);
				@$data['totals'][$username][$index]['calls'] = 0;
				@$data['totals'][$username][$index]['billed_time'] = 0;
				@$data['totals'][$username][$index]['free_time'] = 0;
			}
			$data['totals'][$username][$index]['billed_time_formatted'] =
				$hdtolls->format_sec2min($data['totals'][$username][$index]['billed_time']);
				
			$data['totals'][$username][$index]['free_time_formatted'] =
				$hdtolls->format_sec2min($data['totals'][$username][$index]['free_time']);
		}
	}
        
        
    
	// bottom-half of 'My SIP' landing page before this point
}
else {
	// the DDI is set in the request, we are showing usage for this specific DDI
	$result = $hdtolls->db->query("
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
		
			AND	`tblclients`.`id` = '" . $hdtolls->db->real_escape_string($_SESSION['uid']) . "'
			AND `tblhosting`.`id` = '" . $hdtolls->db->real_escape_string($_GET['ddi']) . "'
		
		ORDER BY `tblhosting`.`domain`
	");
	
	$data['client'] = $result->fetch_assoc();
	$data['client']['domain_formatted'] = $hdtolls->format_phone($data['client']['domain']);
	
	if(@$_GET['viewby'] == "day") {
		// show usage by day
		
		$result_tolls = $hdtolls->db->query("
			SELECT *
			
			FROM `mod_hdtolls_all_calls`
			
			WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
				AND `accountcode` != ''
				AND `disposition` = 'ANSWERED'
				" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "
		");
		
		$data['usage'] = array();
		
		while($row = $result_tolls->fetch_assoc()) {
			$row['is_outbound'] = $row['dcontext'] == $hdtolls::OUTBOUND_CALLS ? true : false;
			$row['is_forwarded'] = $row['forward_from'] && $row['forward_to'] ? true : false;
			if($row['is_forwarded']) {
				$row['forward_to_formatted'] = $hdtolls->format_phone($row['forward_to']);
			}
			$noi = $row['is_outbound'] ? $row['dst'] : $row['src'];
			$row['noi_formatted'] = $hdtolls->format_phone($noi);
			$row['src_formatted'] = $hdtolls->format_phone($row['src']);
			$row['dst_formatted'] = $hdtolls->format_phone($row['dst']);
			$row['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
			$row['used_included_secs_formatted'] = $hdtolls->format_sec2min($row['used_included_secs']);			
			$data['usage'][date('Y', strtotime($row['start']))][date('F', strtotime($row['start']))][date("l d/m/y", strtotime($row['start']))][] = $row;
		}
		
		foreach($data['usage'] as $year => &$months) {
			foreach($months as $month => &$days) {
				foreach($days as $day => &$calls) {
					foreach($calls as $index => &$call) {
						@$data['totals'][$year][$month][$day]['billsec'] += $call['billsec'];
						@$data['totals'][$year][$month][$day]['total_bill'] += $call['bill_amount'];
						@$data['totals'][$year][$month][$day]['used_included_secs'] += $call['used_included_secs'];
					}
					$data['totals'][$year][$month][$day]['billsec_formatted'] = $hdtolls->format_sec2min($data['totals'][$year][$month][$day]['billsec']);
					$data['totals'][$year][$month][$day]['total_used_included_secs_formatted'] = $hdtolls->format_sec2min($data['totals'][$year][$month][$day]['used_included_secs']);
					$data['totals'][$year][$month][$day]['total_calls'] = count($calls);
				}
			}
		}
	}
	elseif(@$_GET['viewby'] == "cat") {
		// show usage by category (i.e. zone)
		
		$result_categories = $hdtolls->db->query("
			SELECT DISTINCT `category`
			
			FROM `mod_hdtolls_all_calls`
			
			ORDER BY `category` ASC;
		");
		
		$data['usage'] = array();
		
		while(list($category) = $result_categories->fetch_row()) {
			$result_tolls = $hdtolls->db->query("
				SELECT *
			
				FROM `mod_hdtolls_all_calls`
				
				WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
					AND `accountcode` != ''
					AND `disposition` = 'ANSWERED'
					" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "
					AND `category` = '" . $category . "'				
			");
			
			while($row = $result_tolls->fetch_assoc()) {
				$row['is_outbound'] = $row['dcontext'] == $hdtolls::OUTBOUND_CALLS ? true : false;
				$row['is_forwarded'] = $row['forward_from'] && $row['forward_to'] ? true : false;
				if($row['is_forwarded']) {
					$row['forward_to_formatted'] = $hdtolls->format_phone($row['forward_to']);
				}
				$noi = $row['is_outbound'] ? $row['dst'] : $row['src'];
				$row['noi_formatted'] = $hdtolls->format_phone($noi);
				$row['src_formatted'] = $hdtolls->format_phone($row['src']);
				$row['dst_formatted'] = $hdtolls->format_phone($row['dst']);
				$row['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
				$row['used_included_secs_formatted'] = $hdtolls->format_sec2min($row['used_included_secs']);
				$data['usage'][$row['category']][date('Y', strtotime($row['start']))][date('F', strtotime($row['start']))][date("l d/m/y", strtotime($row['start']))][] = $row;
			}
		}
		
		foreach($data['usage'] as $category => $arr) {
			foreach($arr as $year => &$months) {
				foreach($months as $month => &$days) {
					foreach($days as $day => &$calls) {
						foreach($calls as $index => &$call) {
							@$data['totals'][$category][$year][$month][$day]['billsec'] += $call['billsec'];
							@$data['totals'][$category][$year][$month][$day]['total_bill'] += $call['bill_amount'];
							@$data['totals'][$category][$year][$month][$day]['used_included_secs'] += $call['used_included_secs'];
						}
						@$data['totals'][$category][$year][$month][$day]['billsec_formatted'] = $hdtolls->format_sec2min($data['totals'][$category][$year][$month][$day]['billsec']);
						@$data['totals'][$category][$year][$month][$day]['total_used_included_secs_formatted'] = $hdtolls->format_sec2min($data['totals'][$category][$year][$month][$day]['used_included_secs']);
						@$data['totals'][$category][$year][$month][$day]['total_calls'] = count($calls);
					}
				}
			}
		}
		
		$result_tolls = $hdtolls->db->query("
			SELECT *
			
			FROM `mod_hdtolls_all_calls`
			
			WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
				AND `accountcode` != ''
				AND `disposition` = 'ANSWERED'
				" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "			
		");
	}
	elseif(@$_GET['viewby'] == "sum") {
		// show usage by short summary of all categories
		
		$result = $hdtolls->db->query("
			SELECT SUBSTRING(`start`, 1, 7) AS start, `category`,
				SUM(`bill_amount`) AS total_bill, SUM(`billsec`) AS billsec,
				SUM(`used_included_secs`) AS freesec, COUNT(*) as total_calls
		
			FROM `mod_hdtolls_all_calls`
			
			WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
				AND `accountcode` != ''
				AND `disposition` = 'ANSWERED'
				" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "
			
			GROUP BY SUBSTRING(`start`, 1, 7), `category`				
		");
		
		$data['usage'] = array();
		
		while($row = $result->fetch_assoc()) {
			$row['billsec_formatted'] = $hdtolls->format_sec2min($row['billsec']);
			$row['freesec_formatted'] = $hdtolls->format_sec2min($row['freesec']);
			$data['usage'][$row['start']][$row['category']] = $row;
		}
		
		foreach($data['usage'] as $month => $categories) {
			foreach($categories as $category => $item) {
				@$data['totals'][$month]['total_calls'] += $item['total_calls'];
				@$data['totals'][$month]['freesec'] += $item['freesec'];
				@$data['totals'][$month]['billsec'] += $item['billsec'];
				@$data['totals'][$month]['total_bill'] += $item['total_bill'];
			}
			$data['totals'][$month]['freesec_formatted'] = $hdtolls->format_sec2min($data['totals'][$month]['freesec']);
			$data['totals'][$month]['billsec_formatted'] = $hdtolls->format_sec2min($data['totals'][$month]['billsec']);
		}
	}
	else {
		echo "Cannot show data: unknown view parameter";
	}
}

?>
