<?php
$data['cron_location'] =  dirname(__FILE__) . '/cron.php';

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

$sql = "SELECT date_index FROM mod_hdtolls_log_index WHERE date_index<'" . date('Y-m-d', $stop) . "' AND date_index>='" . date('Y-m-d', $from) . "'";

$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result, MYSQL_ASSOC)) {
	if(isset($data['check_dates'][$row['date_index']])){
		unset($data['check_dates'][$row['date_index']]);
	}
}
