<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined("RA"))
    die("This file cannot be accessed directly");
$reportdata["title"] = "Suspend Customers";
$reportdata["description"] = "Suspend Customers List";

$query = "SELECT tc.firstname,tc.lastname,tc.companyname,tc.email,tcs.*,ts.name FROM tblcustomerservices as tcs inner join ra_catalog as ts on ts.id=tcs.packageid inner join ra_user as tc on tc.id=tcs.userid where tcs.servicestatus='suspended'";
$result = full_query_i($query);
$reportdata["tableheadings"] = array("Service ID", "Client ID", "Client Name", "Company", "Email", "Service", "Suspended Date", "");
while ($data = mysqli_fetch_assoc($result)) {
    $reportdata["tablevalues"][] = array($data['id'], $data['userid'], $data['firstname'] . " " . $data['lastname'], $data['companyname'], $data['email'], $data['name'], $data['lastupdate']);
}