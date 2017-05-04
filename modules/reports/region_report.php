<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 *  userid
 *  */

if (!defined("RA"))
    die("This file cannot be accessed directly");
$reportdata["title"] = "Service by Region";
$reportdata["description"] = "Region";
$sql = "select * from tblcustomerservices as tbcs INNER JOIN tblclients as tbc on tbcs.userid=tbc.id";
$regionlist = array();
$result = full_query_i($sql);
while ($data = mysqli_fetch_array($result)) {
    if ($data['servicestatus'] == 'Pending') {
        $regionlist[$data['state']]['pending'] ++;
    }
    if ($data['servicestatus'] == 'Active') {
        $regionlist[$data['state']]['active'] ++;
    }
}
$reportdata["tableheadings"] = array("Region", "Pending", "Active", "Total");
foreach ($regionlist as $state => $row) {
    $active = isset($row['active']) ? $row['active'] : 0;
    $pending = isset($row['pending']) ? $row['pending'] : 0;
    $total = $active + $pending;
    $reportdata["tablevalues"][] = array(
        $state, $pending, $active, $total
    );
}
