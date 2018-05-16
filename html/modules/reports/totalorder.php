<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$reportdata["title"] = "Total Sale";
$reportdata["description"] = "This report allows you to review all the credits issued to clients between 2 dates you specify";

$reportdata["headertext"] = '<form method="post" action="' . $PHP_SELF . '?report=' . $report . '">
<p align="center">Start Date: <input type="text" name="startdate" value="' . $startdate . '" class="datepick" /> End Date: <input type="text" name="enddate" value="' . $enddate . '" class="datepick" /> <input type="submit" value="Generate Report"></p>
</form>';

$reportdata["tableheadings"] = array("Period", "New Signup", "Cancellation");
if (!$year)
    $year = date("Y");

for ($counter = 1; $counter <= 12; $counter += 1) {
    $month = $months[$counter - 1];
    $counter = str_pad($counter, 2, "0", STR_PAD_LEFT);
    $signup = get_query_vals("ra_orders", "COUNT(*)", "date LIKE '" . (int) $year . "-$counter-%' AND status='Active'");
    $cancel = get_query_vals("tblcustomerservices", "COUNT(*)", "nextduedate LIKE '" . (int) $year . "-$counter-%' AND servicestatus='Cancelled'");

    $chartdata['rows'][] = array(
        'c' => array(
            array(
                'v' => $month),
            array(
                'v' => $signup[0],
                'f' => $signup[0]
            ),
            array(
                'v' => $cancel[0],
                'f' => $cancel[0]
            )
        )
    );
    $reportdata["tablevalues"][] = array($month . ' ' . $year, $signup[0], $cancel[0]);
}


$chartdata['cols'][] = array('label' => 'Days Range', 'type' => 'string');
$chartdata['cols'][] = array('label' => 'New SignUp', 'type' => 'number');
$chartdata['cols'][] = array('label' => 'Cancellation', 'type' => 'number');

$args = array();
$args['colors'] = '#aded7a,#f34869';
$args['chartarea'] = '80,30,90%,350';

$reportdata["headertext"] = $chart->drawChart('Column', $chartdata, $args, '400px');
//
//if ($startdate && $enddate) {
//    $query = "select COUNT(*), CONCAT_WS('-',YEAR(`date`), MONTH(`date`)) as period from ra_orders where '" . $startdata . "' AND '" . $enddate . "' group by YEAR(`date`), MONTH(`date`),status";
//    $array = array();
//    $result = full_query_i($query);
//    while ($data = mysqli_fetch_array($result)) {
//        $array[] = $data;
//        $reportdata["tablevalues"][] = array($data['subtotal'], $data['tax'], $data['total']);
//    }
//} else {
//    $query = "select COUNT(*) as total, CONCAT_WS('-',YEAR(`date`), MONTH(`date`)) as period,status from ra_orders where status='Active' group by YEAR(`date`), MONTH(`date`)";
//    $array = array();
//    $result = full_query_i($query);
//    while ($data = mysqli_fetch_assoc($result)) {
//        $array[] = $data;
//        $reportdata["tablevalues"][] = array($data['period'], $data['total']);
//    }
//}
//echo "<pre>", print_r($array, 1), "</pre>";
?>