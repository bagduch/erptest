<?php
if(!defined("RA")){die("This file cannot be accessed directly");}
//ini_set('display_errors', 1);
//error_reporting(E_ALL);;


// ------------------------------------------------------------
// Period
if(isset($_REQUEST['period']) && strtolower($_REQUEST['period'])=='current'){
	$_REQUEST['period'] = date('Y-m');
}

if(isset($_REQUEST['period']) && substr_count($_REQUEST['period'], '-')==1){
	$data['period'] = (preg_replace('/[^0-9-]+/is', '', $_REQUEST['period']));
	list($Y,$M) = explode('-', $data['period']);
	if($M>12 || $M<1 || $Y<2012){
		$data['period'] = date('Y-m');
	}
} else {
	#$data['period'] = date('Y-m');
	$data['period'] = date('Y-m', mktime(0, 0, 0, date('m'), 0, date('Y')));
}

$data['period_unix'] = strtotime($data['period']);
$data['period_last'] = mktime(0, 0, 0, date('m', $data['period_unix'])-1, 1, date('Y', $data['period_unix']));
$data['period_next'] = mktime(0, 0, 0, date('m', $data['period_unix'])+1, 1, date('Y', $data['period_unix']));
$data['period_epoc'] = mktime(0,0,0,1,1,2012);



if(date('Ym')>str_replace('-', '', $data['period'])){
	$data['canbill'] = true;
}


# ------------------------------------------------------

$sql = "SELECT h.domain, h.username, t.hosting_id, t.client_id,
		c.firstname, ' ', c.lastname,  c.companyname
		, b.date_billing_period, b.date_from, b.date_stop, b.invoice_id, b.invoice_item_amount
	FROM mod_hdtolls_daily t
		LEFT JOIN mod_hdtolls_billing b ON t.hosting_id=b.hosting_id AND b.date_billing_period='" . $data['period'] . "'
		LEFT JOIN tblhosting h ON t.hosting_id=h.id
		LEFT JOIN ra_user c ON t.client_id=c.id
	WHERE domainstatus='Active'
	GROUP BY h.id
	ORDER BY c.firstname ASC, h.domain ASC";

$result = mysql_query($sql) or die("MySQL Error: " . mysql_errno() . ": " . mysql_error() . '<hr ><pre>' . $sql . '</pre>');
$data['rows'] = array();
while ($row = mysql_fetch_assoc($result, MYSQL_ASSOC)) {
    $data['rows'][] = $row;
}
