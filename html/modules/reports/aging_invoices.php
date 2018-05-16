<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Aging Invoices";
$reportdata["description"] = "A summary of outstanding invoices broken down into the period of which they are overdue";

$reportdata["tableheadings"][] = "Period";


foreach ($currencies AS $currencyid=>$currencyname) {
    $reportdata["tableheadings"][] = "$currencyname Amount";
}

$totals = array();

for ( $day = 0; $day < 120; $day += 30) {
    $startdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$day,date("Y")));
    $enddate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-($day+30),date("Y")));
    $rowdata = array();
    $rowdata[] = "$day - ".($day+30);
    $currencytotals = array();
    $query = "SELECT ra_user.currency,SUM(ra_bills.total),(SELECT SUM(amountin-amountout) FROM ra_transactions INNER JOIN ra_bills ON ra_bills.id=ra_transactions.invoiceid INNER JOIN ra_user t2 ON t2.id=ra_bills.userid WHERE ra_bills.duedate<='".db_make_safe_date($startdate)."' AND ra_bills.duedate>='".db_make_safe_date($enddate)."' AND ra_bills.status='Unpaid' AND t2.currency=ra_user.currency) FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.duedate<='".db_make_safe_date($startdate)."' AND ra_bills.duedate>='".db_make_safe_date($enddate)."' AND ra_bills.status='Unpaid' GROUP BY ra_user.currency";
    $result = full_query_i($query);
    while ($data = mysqli_fetch_array($result)) {
        $currencytotals[$data[0]] = $data[1]-$data[2];
    }
    foreach ($currencies AS $currencyid=>$currencyname) {
        $currencyamount = $currencytotals[$currencyid];
        if (!$currencyamount) $currencyamount=0;
        $totals[$currencyid] += $currencyamount;
        $currency = getCurrency('',$currencyid);
        $rowdata[] = formatCurrency($currencyamount);
        if ($currencyid==$defaultcurrencyid) $chartdata['rows'][] = array('c'=>array(array('v'=>"$day - ".($day+30)),array('v'=>$currencyamount,'f'=>formatCurrency($currencyamount))));
    }
    $reportdata["tablevalues"][] = $rowdata;
}

$startdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-120,date("Y")));
$rowdata = array();
$rowdata[] = "120 +";
$currencytotals = array();
$query = "SELECT ra_user.currency,SUM(ra_bills.total) FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.duedate<='".db_make_safe_date($startdate)."' AND ra_bills.status='Unpaid' GROUP BY ra_user.currency";
$result = full_query_i($query);
while ($data = mysqli_fetch_array($result)) {
        $currencytotals[$data[0]] = $data[1];
}
foreach ($currencies AS $currencyid=>$currencyname) {
        $currencyamount = $currencytotals[$currencyid];
        if (!$currencyamount) $currencyamount=0;
        $totals[$currencyid] += $currencyamount;
        $currency = getCurrency('',$currencyid);
        $rowdata[] = formatCurrency($currencyamount);
}
$reportdata["tablevalues"][] = $rowdata;

$rowdata = array();
$rowdata[] = "<b>Total</b>";
foreach ($currencies AS $currencyid=>$currencyname) {
        $currencytotal = $totals[$currencyid];
        if (!$currencytotal) $currencytotal=0;
        $currency = getCurrency('',$currencyid);
        $rowdata[] = "<b>".formatCurrency($currencytotal)."</b>";
}

$reportdata["tablevalues"][] = $rowdata;

$chartdata['cols'][] = array('label'=>'Days Range','type'=>'string');
$chartdata['cols'][] = array('label'=>'Value','type'=>'number');

$args = array();
$args['legendpos'] = 'right';

$reportdata["footertext"] = $chart->drawChart('Pie',$chartdata,$args,'300px');

?>