<?php
# Can't get directly off web
if(isset($argv[1]) && $argv[1]=='invoice=all'){
	$_GET['invoice'] = 'all';
	$base_dir = '/var/www/my/';
} else if(!defined("RA")){
	die("This file cannot be accessed directly");
} else {
	$base_dir = dirname(dirname(dirname(dirname(__FILE__)))) . '/';
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
require(dirname(__FILE__) . '/models/hdtolls.php');
require_once($base_dir . 'configuration.php');
require_once($base_dir . 'dbconnect.php');
$hdtolls = new hdtolls();


/* ============================================================
| SYNC
============================================================ */
$from = mktime(0, 0, 0, date('m'), -60, date('Y'));
$stop = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

$a = $from;
$i = 0;
$data['check_dates'] = array();
while($a<$stop && $i++<100){
	$stamp = date('Y-m-d', $a);
	$data['check_dates'][$stamp] = $stamp;
	$a += 86400;
}

$sql = "
	SELECT date_index FROM mod_hdtolls_log_index WHERE date_index<'" . date('Y-m-d', $stop) . "' AND date_index>='" . date('Y-m-d', $from) . "'";

$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result, MYSQL_ASSOC)) {
	if(isset($data['check_dates'][$row['date_index']])){
		unset($data['check_dates'][$row['date_index']]);
	}
}
if(isset($data['check_dates']) && count($data['check_dates'])>0){
	$m[] = '#============================================================#';
	$m[] = '# Sync Data';
	$m[] = '#------------------------------------------------------------#';
	foreach($data['check_dates'] as $date){
		$result = $hdtolls->sync(strtotime($date));
		#echo '<pre>' . print_r($result, true) . '</pre>';
		if(isset($result['errors']) && count($result['errors'])>0){
			$m[] = $date . ':' . implode("\n", $result['errors']);
		} else {
			$m[] = $date . ': Ok';
		}
	}
}

/* ============================================================
| DATA
============================================================ */
$error_count = 0;
$period_lock = mktime(0, 0, 0, 2, 29, 2012);
#for($i=1;$i>=0;$i--){
for($i=6;$i>0;$i--){
	$period_unix = mktime(0, 0, 0, date('m')-$i, 1, date('Y'));
	$period = date('Y-m', $period_unix);


	$sql = "SELECT h.domain, h.username
			, t.hosting_id, t.client_id, CONCAT(YEAR(t.`date`), '-', LPAD(MONTH(t.`date`), 2, 0)) AS month
			, MIN(t.`date`) AS `date`
			, GROUP_CONCAT(DISTINCT h.`domain`) AS domains
			, t.invoice_id
			, c.firstname, c.lastname,  c.companyname
		FROM mod_hdtolls_daily t
			LEFT JOIN tblhosting h ON t.hosting_id=h.id
			LEFT JOIN ra_user c ON t.client_id=c.id
		WHERE domainstatus='Active'
			AND `date` LIKE '" . $period . "-%'
		GROUP BY MONTH(t.`date`), h.id
		ORDER BY `month` ASC, c.firstname ASC, h.domain ASC";
	#echo '<pre>' . $sql . '</pre>';

	$result = mysql_query($sql) or die("MySQL Error: " . mysql_errno() . ": " . mysql_error() . '<hr ><pre>' . $sql . '</pre>');
	$res['rows'] = array();
	while ($row = mysql_fetch_assoc($result, MYSQL_ASSOC)) {
		$res['rows'][] = $row;
	}

	if(isset($res['rows']) && count($res['rows'])>0){
		$m[] = '#============================================================#';
		$m[] = '# Invoicing Period: ' . $period;
		$m[] = '#------------------------------------------------------------#';

		foreach($res['rows'] as $row){
			if(!isset($row['invoice_id']) || $row['invoice_id']==''){
				$row['canbill'] = 1;	# Spare flag for locking, not used.
			}
			if(isset($row['canbill']) && $row['canbill']==1){
				$text = "(" . $row['date'] . ") " . $row['firstname'] . " ". $row['lastname'] . ' (' . $row['domains'] . ')';
				if(strtotime($row['date'])<=$period_lock){
					$m[] = 'Locked: No invoicing before ' . date('Y-m-d', $period_lock) . $text;
				} else if($row['domain']=='099735083' || (isset($_GET['invoice']) && $_GET['invoice']=='all')){
					$result = $hdtolls->create_invoice($row['client_id'], strtotime($row['date']));
					if(isset($result['invoice_id'])){
						$m[] = 'Invoiced: #' . $result['invoice_id'] . $text;
					} else if(isset($result['result']) && $result['result']=='success' && isset($result['message'])){
						$m[] = 'Result: #' . $result['message'] . $text;
					} else if(isset($result['result']) && $result['result']=='error') {
						$e0[] = 'Error Invoicing: ' . $result['result'] . $text . ", reason: " . $result['message'];
						$error_count++;
					}
				} else {
					$m[] = "Invoice: ready up" . $text;
				}
				#echo '<pre>' . print_r($row, true) . '</pre>';
			} else {
		
			}
		}
	}
	if(isset($m0)){$m = array_merge($m, $m0); unset($m0);}
	$m[] = 'Period Completed';
	$m[] = '';
}

if(isset($error_count) && $error_count>0){
	$me[] = '#============================================================#';
	$me[] = '# Errors: ' . number_format($error_count, 0);
	$me[] = '#------------------------------------------------------------#';
	$m = array_merge(array(''), $m);
	if(isset($e2)){$m = array_merge($e2, $m);}
	if(isset($e1)){$m = array_merge($e1, $m);}
	if(isset($e0)){$m = array_merge($e0, $m);}
	if(isset($me)){$m = array_merge($me, $m);}
}

echo '<pre>' . implode("\n", $m) . '</pre>';

# Email Reports
if(isset($_GET['invoice']) && $_GET['invoice']=='all'){
	$emails = $hdtolls->setting('option_email_cron_to');
	if($emails){
		$emails = preg_split('/[\n\r\s,]+/is', trim($emails), 0, PREG_SPLIT_NO_EMPTY);
		if(count($emails)>0 && $emails[0]!=''){
			mail(
				implode(',', $emails),	# mails
				'RA Cron TOLLS Billing',
				implode("\r\n", $m)
			);
		}
	}
}

