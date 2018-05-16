<?php

require('models/tolls.php');

class hdcron extends tolls {
	
	function array_diff_custom($periods_per_domain, $billed_periods) {
		
		$unbilled_periods = array();
		
		foreach($periods_per_domain as $domain => $periods)
			foreach($periods as $period)
				if(!@in_array($period, $billed_periods[$domain]))
					$unbilled_periods[$domain][] = $period;
   		
   		return $unbilled_periods;
	}
	
	function get_unbilled_months() {
		$periods_per_domain = array();
		$billed_periods = array();
		
		$result_clients = $this->db->query("
			SELECT `ra_user`.`id` AS clientid,
				`ra_user`.`firstname`,
				`ra_user`.`lastname`,
				`ra_user`.`companyname`,
				`ra_user`.`email`,
				`tblhosting`.`id` AS hostingid,
				`tblhosting`.`domain`,
				`tblhosting`.`username`
			
			FROM `ra_user`
			
				INNER JOIN `tblhosting` ON `tblhosting`.`userid` = `ra_user`.`id`
			
			WHERE `tblhosting`.`packageid` IN (520, 560, 712)
			
				AND `domainstatus` = 'Active'
			
			ORDER BY `tblhosting`.`domain`
		");
		
		while($row = $result_clients->fetch_assoc()) {
			$data['clients'][] = $row;
		}
		
		foreach($data['clients'] as $clientid => $client) {
			$result = $this->db->query("
				SELECT DISTINCT SUBSTRING(`start`, 1, 7) AS period
				
				FROM `mod_tolls_all_calls`
				
				WHERE `accountcode` = '" . $client['hostingid'] . "'
					AND `disposition` = 'ANSWERED'
					AND `start` NOT LIKE '" . date("Y-m") . "-%'
			");
			while($row = $result->fetch_assoc()) {
				$periods_per_domain[$client['hostingid']][] = $row['period'];
			}
		}
		
		foreach($periods_per_domain as $hostingid => $periods) {
			$result_billing = $this->db->query("
				SELECT `hostingid`, `period`
				
				FROM `mod_tolls_billing2`
				
				WHERE `hostingid` = '" . $hostingid . "'
			");
			while($row = $result_billing->fetch_assoc()) {
				$billed_periods[$row['hostingid']][] = $row['period'];
			}
		}
		
		return $this->array_diff_custom($periods_per_domain, $billed_periods);
	}
	
	function bill_months($hostingids) {
		foreach($hostingids as $hostingid => $periods) {
			foreach($periods as $period) {
				$this->create_invoice($hostingid, $period);
			}
		}
	}
	
}

$hdcron = new hdcron();
$hdcron->bill_months($hdcron->get_unbilled_months());

?>
