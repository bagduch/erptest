<?php

require('models/tolls.php');

class tollsincome extends tolls {
	
	function show_income() {
		$result = $this->db->query("
			SELECT SUM(`bill_amount`) AS total_day_income

			FROM `mod_tolls_all_calls`
			
			WHERE `start` LIKE CONCAT(CURDATE(), '%')
		");
		
		$income = $result->fetch_row();
		$income = $income[0];
		
		mail("ben@hd.net.nz", "HD Tolls Daily Income", "Total income for " . date("l, j F Y") . ": \$" . $income . "\n");
	}
	
}

$tollsincome = new tollsincome();
echo $tollsincome->show_income();