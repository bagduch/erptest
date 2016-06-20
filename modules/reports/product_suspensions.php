<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Product Suspensions";
$reportdata["description"] = "This report allows you to review all suspended products and the reasons specified for their suspensions";

$reportdata["tableheadings"] = array("Service ID","Client Name","Product Name","Domain","Next Due Date","Suspend Reason");

$result = select_query_i("tblcustomerservices","tblcustomerservices.*,tblclients.firstname,tblclients.lastname,tblservices.name",array("servicestatus"=>"Suspended"),"id","ASC","","tblclients ON tblclients.id=tblcustomerservices.userid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid");
while ($data = mysqli_fetch_array($result)) {
	$serviceid = $data["id"];
    $userid = $data["userid"];
	$clientname = $data["firstname"]." ".$data["lastname"];
    $productname = $data["name"];
    $domain = $data["domain"];
    $nextduedate = $data["nextduedate"];
    $suspendreason = $data["suspendreason"];

    if (!$suspendreason) $suspendreason = 'Overdue on Payment';

	$nextduedate = fromMySQLDate($nextduedate);

	$reportdata["tablevalues"][] = array('<a href="clientshosting.php?userid='.$userid.'&id='.$serviceid.'">'.$serviceid.'</a>','<a href="clientssummary.php?userid='.$userid.'">'.$clientname.'</a>',$productname,$domain,$nextduedate,$suspendreason);

}

$data["footertext"] = '';

?>