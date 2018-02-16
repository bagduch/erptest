<?php

if(!defined("RA"))
	die("This file cannot be accessed directly");

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require(dirname(dirname(__FILE__)) . '/models/hdtolls.php');

$hdtolls = new hdtolls();

if(isset($_GET['invoice'])) {
	$hdtolls->create_invoice($_GET['h'], $_GET['period']);
	header("location: " . html_entity_decode($_SERVER['HTTP_REFERER']));
	exit;
}

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
	
		AND	`tblclients`.`id` = '" . $hdtolls->db->real_escape_string($_GET['c']) . "'
		AND `tblhosting`.`id` = '" . $hdtolls->db->real_escape_string($_GET['h']) . "'
	
	ORDER BY `tblhosting`.`domain`
");

$data['client'] = $result->fetch_assoc();
$data['client']['domain_formatted'] = $hdtolls->format_phone($data['client']['domain']);

if(@$_GET['viewby'] == "day") {
	$result_tolls = $hdtolls->db->query("
		SELECT *
		
		FROM `mod_hdtolls_all_calls`
		
		WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
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
			AND `disposition` = 'ANSWERED'
			" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "			
	");
}
elseif(@$_GET['viewby'] == "sum") {
	$data['usage'] = array();
	
	$result = $hdtolls->db->query("
		SELECT SUBSTRING(`start`, 1, 7) AS start, `category`,
			SUM(`bill_amount`) AS total_bill, SUM(`billsec`) AS billsec,
			SUM(`used_included_secs`) AS freesec, COUNT(*) as total_calls
	
		FROM `mod_hdtolls_all_calls`
		
		WHERE `accountcode` = '" . $data['client']['hostingid'] . "'
			AND `disposition` = 'ANSWERED'
			" . (isset($_GET['period']) ? "AND `start` LIKE '" . $hdtolls->db->real_escape_string($_GET['period']) . "-%'" : '') . "
		
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
		$data['totals'][$month]['freesec_formatted'] = $hdtolls->format_sec2min($data['totals'][$month]['freesec']);
		$data['totals'][$month]['billsec_formatted'] = $hdtolls->format_sec2min($data['totals'][$month]['billsec']);
	}
}
else {
	echo "Cannot show data: unknown view parameter";
}

?>
