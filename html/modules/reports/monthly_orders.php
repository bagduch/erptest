<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Sales by Product for ".$months[(int)$month]." ".$year;
$reportdata["description"] = "This report gives a breakdown of the number of units sold of each product per month";

$reportdata["currencyselections"] = true;

$total = 0;

$datefilter = $year.'-'.$month.'%';

$reportdata["tableheadings"] = array("Product Name","Units Sold","Value");

$result = select_query_i("ra_catalog","ra_catalog.id,ra_catalog.name,ra_catalog_groups.name AS groupname","","ra_catalog_groups`.`order` ASC,`ra_catalog`.`order` ASC,`name","ASC","","ra_catalog_groups ON ra_catalog.gid=ra_catalog_groups.id");
while($data = mysqli_fetch_array($result)) {
	$pid = $data["id"];
	$group = $data["groupname"];
	$prodname = $data["name"];

    if ($group!=$prevgroup) $reportdata["tablevalues"][] = array("**<b>$group</b>");

    $result2 = select_query_i("tblcustomerservices","COUNT(*),SUM(tblcustomerservices.firstpaymentamount)","tblcustomerservices.packageid='$pid' AND tblcustomerservices.servicestatus='Active' AND tblcustomerservices.regdate LIKE '".$datefilter."' AND ra_user.currency='$currencyid'","","","","ra_user ON ra_user.id=tblcustomerservices.userid");
    $data = mysqli_fetch_array($result2);
    $number = $data[0];
    $amount = $data[1];

    $total += $amount;

    $amount = formatCurrency($amount);

    $reportdata["tablevalues"][] = array($prodname,$number,$amount);

    $prevgroup = $group;

}

$reportdata["tablevalues"][] = array("**<b>Addons</b>");

$result = select_query_i("tbladdons","","","name","ASC");
while($data = mysqli_fetch_array($result)) {

    $pid = $data["id"];
    $prodname = $data["name"];

    $result2 = select_query_i("ra_catalog_user_sales_addons","COUNT(*),SUM(ra_catalog_user_sales_addons.setupfee+ra_catalog_user_sales_addons.recurring)","ra_catalog_user_sales_addons.addonid='$pid' AND ra_catalog_user_sales_addons.status='Active' AND ra_catalog_user_sales_addons.regdate LIKE '$datefilter' AND ra_user.currency='$currencyid'","","","","tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");
    $data = mysqli_fetch_array($result2);
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
