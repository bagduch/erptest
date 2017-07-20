<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

$reportdata["title"] = "Total Sale";
$reportdata["description"] = "This report allows you to review all the credits issued to clients between 2 dates you specify";

if (!$year)
    $year = date("Y");

$reportdata["headertext"] = '<form method="post" action="' . $PHP_SELF . '?report=' . $report . '">
<p align="center">Start Date: <input type="text" name="startdate" value="' . $startdate . '" class="datepick" /> End Date: <input type="text" name="enddate" value="' . $enddate . '" class="datepick" /> <input type="submit" value="Generate Report"></p>
</form>';

$reportdata["tableheadings"] = array("GST", "Sub", "Total");

if ($startdate && $enddate) {
    $query = "select * from tblinvoices where date between '" . $startdata . "' AND '" . $enddate . "' AND status='Paid'";
    $array = array();
    $result = full_query_i($query);
    while ($data = mysqli_fetch_array($result)) {
        $array[] = $data;
        $reportdata["tablevalues"][] = array($data['subtotal'], $data['tax'], $data['total']);
    }
}
?>