<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

$reportdata["title"] = "Client Sources";
$reportdata["description"] = "This report provides a summary of the answers clients have given to the How Did You Find Us? or Where did you hear about us? custom field signup question";
$reportdata["title"] = "Cancelled Customers";
$reportdata["description"] = "Cancelled Customers List";

$query = "SELECT tc.firstname,tc.lastname,tc.companyname,tc.email,tcs.*,ts.name FROM tblcustomerservices as tcs inner join ra_catalog as ts on ts.id=tcs.packageid inner join ra_user as tc on tc.id=tcs.userid where tcs.servicestatus='cancelled'";
$result = full_query_i($query);
$reportdata["tableheadings"] = array("Service ID", "Client ID", "Client Name", "Company", "Email", "Service", "Cancelled Date", "");
while ($data = mysqli_fetch_assoc($result)) {
    $reportdata["tablevalues"][] = array($data['id'], $data['userid'], $data['firstname'] . " " . $data['lastname'], $data['companyname'], $data['email'], $data['name'], $data['lastupdate']);
}