<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$pmonth = str_pad((int)$month, 2, "0", STR_PAD_LEFT);

$reportdata["title"] = "Income by Product for ".$months[(int)$month]." ".$year;
$reportdata["description"] = "This report provides a breakdown per product/service of invoices paid in a given month. Please note this excludes overpayments & other payments made to deposit funds (credit), and includes invoices paid from credit added in previous months, and thus may not match the income total for the month.";
$reportdata["currencyselections"] = true;

$reportdata["tableheadings"] = array("Product Name","Units Sold","Value");

$products = $addons = array();

# Loop Through Products
$result = full_query_i("SELECT tblcustomerservices.packageid,COUNT(*),SUM(ra_bill_lineitems.amount) FROM ra_bill_lineitems INNER JOIN ra_bills ON ra_bills.id=ra_bill_lineitems.invoiceid INNER JOIN tblhosting ON tblcustomerservices.id=ra_bill_lineitems.relid INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.datepaid LIKE '".(int)$year."-".$pmonth."-%' AND (ra_bill_lineitems.type='Hosting' OR ra_bill_lineitems.type LIKE 'ProrataProduct%') AND currency=".(int)$currencyid." GROUP BY tblcustomerservices.packageid");
while ($data = mysqli_fetch_array($result)) {
    $products[$data[0]] = array("amount" => $data[2],"unitssold" => $data[1]);
}

# Loop Through Product Discounts
$result = full_query_i("SELECT tblcustomerservices.packageid,COUNT(*),SUM(ra_bill_lineitems.amount) FROM ra_bill_lineitems INNER JOIN ra_bills ON ra_bills.id=ra_bill_lineitems.invoiceid INNER JOIN tblhosting ON tblcustomerservices.id=ra_bill_lineitems.relid INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.datepaid LIKE '".(int)$year."-".$pmonth."-%' AND ra_bill_lineitems.type='PromoHosting' AND currency=".(int)$currencyid." GROUP BY  tblcustomerservices.packageid");
while ($data = mysqli_fetch_array($result)) {
    $products[$data[0]]["amount"] += $data[2];
}

# Loop Through Addons
$result = full_query_i("SELECT ra_catalog_user_sales_addons.addonid,COUNT(*),SUM(ra_bill_lineitems.amount) FROM ra_bill_lineitems INNER JOIN ra_bills ON ra_bills.id=ra_bill_lineitems.invoiceid INNER JOIN ra_catalog_user_sales_addons ON ra_catalog_user_sales_addons.id=ra_bill_lineitems.relid INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.datepaid LIKE '".(int)$year."-".$pmonth."-%' AND ra_bill_lineitems.type='Addon' AND currency=".(int)$currencyid." GROUP BY  ra_catalog_user_sales_addons.addonid");
while ($data = mysqli_fetch_array($result)) {
    $addons[$data[0]] = array("amount" => $data[2],"unitssold" => $data[1]);
}

$total = 0;
$itemtotal = 0;
$firstdone = false;
$result = select_query_i("ra_catalog","ra_catalog.id,ra_catalog.name,ra_catalog_groups.name AS groupname","","ra_catalog_groups`.`order` ASC,`ra_catalog`.`order` ASC,`name","ASC","","ra_catalog_groups ON ra_catalog.gid=ra_catalog_groups.id");
while($data = mysqli_fetch_array($result)) {
	$pid = $data["id"];
	$group = $data["groupname"];
	$prodname = $data["name"];

    if ($group!=$prevgroup) {
        $total += $itemtotal;
        if ($firstdone) {
            $reportdata["tablevalues"][] = array('','<strong>Sub-Total</strong>','<strong>'.formatCurrency($itemtotal).'</strong>');
            $chartdata['rows'][] = array('c'=>array(array('v'=>$prevgroup),array('v'=>$itemtotal,'f'=>formatCurrency($itemtotal))));
        }
        $reportdata["tablevalues"][] = array("**<strong>$group</strong>");
        $itemtotal = 0;
    }

    $amount = $products[$pid]["amount"];
    $number = $products[$pid]["unitssold"];

    $itemtotal += $amount;

	if (!$amount) $amount="0.00";
	if (!$number) $number="0";
    $amount = formatCurrency($amount);

    $reportdata["tablevalues"][] = array($prodname,$number,$amount);

    $prevgroup = $group;
    $firstdone = true;

}

$total += $itemtotal;
$reportdata["tablevalues"][] = array('','<strong>Sub-Total</strong>','<strong>'.formatCurrency($itemtotal).'</strong>');
$chartdata['rows'][] = array('c'=>array(array('v'=>$group),array('v'=>$itemtotal,'f'=>formatCurrency($itemtotal))));

$reportdata["tablevalues"][] = array("**<strong>Addons</strong>");

$itemtotal = 0;
$result = select_query_i("tbladdons","id,name","","name","ASC");
while($data = mysqli_fetch_array($result)) {

    $addonid = $data["id"];
    $prodname = $data["name"];

    $amount = $addons[$addonid]["amount"];
    $number = $addons[$addonid]["unitssold"];

    $itemtotal += $amount;

	if (!$amount) $amount="0.00";
	if (!$number) $number="0";
    $amount = formatCurrency($amount);

    $reportdata["tablevalues"][] = array($prodname,$number,$amount);

    $prevgroup = $group;

}

$itemtotal += $addons[0]["amount"];
$number = $addons[0]["unitssold"];
$amount = $addons[0]["amount"];
if (!$amount) $amount="0.00";
if (!$number) $number="0";
$reportdata["tablevalues"][] = array('Miscellaneous Custom Addons',$number,formatCurrency($amount));

$total += $itemtotal;
$reportdata["tablevalues"][] = array('','<strong>Sub-Total</strong>','<strong>'.formatCurrency($itemtotal).'</strong>');
$chartdata['rows'][] = array('c'=>array(array('v'=>"Addons"),array('v'=>$itemtotal,'f'=>formatCurrency($itemtotal))));

$itemtotal = 0;
$reportdata["tablevalues"][] = array("**<strong>Miscellaneous</strong>");

$result = full_query_i("SELECT COUNT(*),SUM(ra_bill_lineitems.amount) FROM ra_bill_lineitems INNER JOIN ra_user ON ra_user.id=ra_bills.userid INNER JOIN ra_bills ON ra_bills.id=ra_bill_lineitems.invoiceid WHERE ra_bills.datepaid LIKE '".(int)$year."-".$pmonth."-%' AND ra_bill_lineitems.type='Item' AND currency=".(int)$currencyid);
$data = mysqli_fetch_array($result);
$itemtotal += $data[1];
$number = $data[0];
$amount = $data[1];
if (!$amount) $amount="0.00";
if (!$number) $number="0";
$reportdata["tablevalues"][] = array('Billable Items',$number,formatCurrency($amount));

$result = full_query_i("SELECT COUNT(*),SUM(ra_bill_lineitems.amount) FROM ra_bill_lineitems INNER JOIN ra_bills ON ra_bills.id=ra_bill_lineitems.invoiceid INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.datepaid LIKE '".(int)$year."-".$pmonth."-%' AND ra_bill_lineitems.type='' AND currency=".(int)$currencyid);
$data = mysqli_fetch_array($result);
$itemtotal += $data[1];
$reportdata["tablevalues"][] = array('Custom Invoice Line Items',$data[0],formatCurrency($data[1]));

$total += $itemtotal;
$reportdata["tablevalues"][] = array('','<strong>Sub-Total</strong>','<strong>'.formatCurrency($itemtotal).'</strong>');
$chartdata['rows'][] = array('c'=>array(array('v'=>"Miscellaneous"),array('v'=>$itemtotal,'f'=>formatCurrency($itemtotal))));

$total = formatCurrency($total);

$chartdata['cols'][] = array('label'=>'Days Range','type'=>'string');
$chartdata['cols'][] = array('label'=>'Value','type'=>'number');

$args = array();
$args['legendpos'] = 'right';

$reportdata["footertext"] = $chart->drawChart('Pie',$chartdata,$args,'300px');

$reportdata["monthspagination"] = true;

?>