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
$reportdata["description"] = "Counting Region numbers";
$sql = "select * from tblcustomerservices as tbcs INNER JOIN ra_user as tbc on tbcs.userid=tbc.id";
$regionlist = array();
$result = full_query_i($sql);
while ($data = mysqli_fetch_array($result)) {
    if ($data['servicestatus'] == 'Pending') {
        $regionlist[$data['state']]['pending'] ++;
    }
    if ($data['servicestatus'] == 'Active') {
        $regionlist[$data['state']]['active'] ++;
    }
    if ($data['servicestatus'] == 'Suspended') {
        $regionlist[$data['state']]['suspend'] ++;
    }
    if ($data['servicestatus'] == 'Terminated') {
        $regionlist[$data['state']]['terminated'] ++;
    } if ($data['servicestatus'] == 'Cancelled') {
        $regionlist[$data['state']]['cancel'] ++;
    }
    if ($data['servicestatus'] == 'Draft') {
        $regionlist[$data['state']]['draft'] ++;
    }
}
$reportdata["tableheadings"] = array("Region", "Pending", "Active", 'Suspended', 'Termination', 'Cancelled', 'Draft', "Total");
foreach ($regionlist as $state => $row) {
    $active = isset($row['active']) ? $row['active'] : 0;
    $pending = isset($row['pending']) ? $row['pending'] : 0;
    $suspend = isset($row['suspend']) ? $row['suspend'] : 0;
    $terminated = isset($row['terminated']) ? $row['terminated'] : 0;
    $active = isset($row['active']) ? $row['active'] : 0;
    $draft = isset($row['draft']) ? $row['draft'] : 0;
    $cancel = isset($row['cancel']) ? $row['cancel'] : 0;


    $total = $active + $pending + $suspend + $terminated + $cancel + $draft;
    $reportdata["tablevalues"][] = array(
        $state, $pending, $active, $suspend, $terminated, $cancel, $draft, $total
    );
}
