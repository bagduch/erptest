<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Top 25 Clients by Income";
$reportdata["description"] = "This report shows the 25 clients with the highest net income according to the transactions entered in ra.";

$reportdata["tableheadings"] = array("Client ID","Client Name","Total Amount In","Total Fees","Total Amount Out","Balance");

$query = "SELECT ra_user.id,ra_user.firstname, ra_user.lastname, SUM(ra_transactions.amountin/ra_transactions.rate), SUM(ra_transactions.fees/ra_transactions.rate), SUM(ra_transactions.amountout/ra_transactions.rate), SUM((ra_transactions.amountin/ra_transactions.rate)-(ra_transactions.fees/ra_transactions.rate)-(ra_transactions.amountout/ra_transactions.rate)) AS balance, ra_transactions.rate FROM ra_transactions INNER JOIN ra_user ON ra_user.id = ra_transactions.userid GROUP BY userid ORDER BY balance DESC LIMIT 0,25";
$result=full_query_i($query);
while($data = mysqli_fetch_array($result)) {
    $userid = $data[0];

    $currency = getCurrency();
    $rate = ($data['rate']=="1.00000") ? '' : '*';

    $clientlink = '<a href="clientssummary.php?userid='.$data[0].'">';

    $reportdata["tablevalues"][] = array($clientlink.$data[0].'</a>',$clientlink.$data[1].' '.$data[2].'</a>',formatCurrency($data[3])." $rate" ,formatCurrency($data[4])." $rate" ,formatCurrency($data[5])." $rate", formatCurrency($data[6])." $rate");

    $chartdata['rows'][] = array('c'=>array(array('v'=>$data[1].' '.$data[2]),array('v'=>round($data[6],2),'f'=>formatCurrency($data[6]))));

}

$reportdata["footertext"] = "<p>* denotes converted to default currency</p>";

$chartdata['cols'][] = array('label'=>'Client','type'=>'string');
$chartdata['cols'][] = array('label'=>'Balance','type'=>'number');

$args = array();
$args['legendpos'] = 'right';

$reportdata["headertext"] = $chart->drawChart('Pie',$chartdata,$args,'300px');

?>