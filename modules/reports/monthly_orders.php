<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Sales by Product for ".$months[(int)$month]." ".$year;
$reportdata["description"] = "This report gives a breakdown of the number of units sold of each product per month";

$reportdata["currencyselections"] = true;

$total = 0;

$datefilter = $year.'-'.$month.'%';

$reportdata["tableheadings"] = array("Product Name","Units Sold","Value");

$result = select_query("tblservices","tblservices.id,tblservices.name,tblservicegroups.name AS groupname","","tblservicegroups`.`order` ASC,`tblservices`.`order` ASC,`name","ASC","","tblservicegroups ON tblservices.gid=tblservicegroups.id");
while($data = mysql_fetch_array($result)) {
	$pid = $data["id"];
	$group = $data["groupname"];
	$prodname = $data["name"];

    if ($group!=$prevgroup) $reportdata["tablevalues"][] = array("**<b>$group</b>");

    $result2 = select_query("tblcustomerservices","COUNT(*),SUM(tblcustomerservices.firstpaymentamount)","tblcustomerservices.packageid='$pid' AND tblcustomerservices.servicestatus='Active' AND tblcustomerservices.regdate LIKE '".$datefilter."' AND tblclients.currency='$currencyid'","","","","tblclients ON tblclients.id=tblcustomerservices.userid");
    $data = mysql_fetch_array($result2);
    $number = $data[0];
    $amount = $data[1];

    $total += $amount;

    $amount = formatCurrency($amount);

    $reportdata["tablevalues"][] = array($prodname,$number,$amount);

    $prevgroup = $group;

}

$reportdata["tablevalues"][] = array("**<b>Addons</b>");

$result = select_query("tbladdons","","","name","ASC");
while($data = mysql_fetch_array($result)) {

    $pid = $data["id"];
    $prodname = $data["name"];

    $result2 = select_query("tblserviceaddons","COUNT(*),SUM(tblserviceaddons.setupfee+tblserviceaddons.recurring)","tblserviceaddons.addonid='$pid' AND tblserviceaddons.status='Active' AND tblserviceaddons.regdate LIKE '$datefilter' AND tblclients.currency='$currencyid'","","","","tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");
    $data = mysql_fetch_array($result2);
    $number = $data[0];
    $amount = $data[1];

    $total += $amount;

    $amount = formatCurrency($amount);

    $reportdata["tablevalues"][] = array($prodname,$number,$amount);

    $prevgroup = $group;

}

$total = formatCurrency($total);

$reportdata["footertext"] = '<p align="center"><strong>Total: '.$total.'</strong></p>';

$reportdata["monthspagination"] = true;

?>
