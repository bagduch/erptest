<?php

/**
 * @ RA
 * */
define("CLIENTAREA", true);
require "init.php";
require "includes/orderfunctions.php";
//require "includes/domainfunctions.php";
require "includes/whoisfunctions.php";
require "includes/configoptionsfunctions.php";
require "includes/customfieldfunctions.php";
require "includes/clientfunctions.php";
require "includes/invoicefunctions.php";
require "includes/processinvoices.php";
require "includes/gatewayfunctions.php";
require "includes/fraudfunctions.php";
require "includes/modulefunctions.php";
require "includes/ccfunctions.php";
require "includes/cartfunctions.php";
require 'includes/servicefunctions.php';
initialiseClientArea($_LANG['carttitle'], "", "<a href=\"cart.php\">" . $_LANG['carttitle'] . "</a>");
checkContactPermission("orders");
$orderfrm = new RA_OrderForm();
$cart = new RA_Carts($orderfrm, $ra);
$a = $ra->get_req_var("a");
$gid = $ra->get_req_var("gid");
$pid = (int) $ra->get_req_var("pid");
$aid = (int) $ra->get_req_var("aid");
$ajax = $ra->get_req_var("ajax");
$sld = $ra->get_req_var("sld");
$tld = $ra->get_req_var("tld");
$description = $ra->get_req_var("description");
$step = $ra->get_req_var("step");
$submit = $ra->get_req_var("submit");
$checkout = $ra->get_req_var("checkout");
$validatepromo = $ra->get_req_var("validatepromo");
$orderfrmtpl = $ra->get_config("OrderFormTemplate");
if (!isValidforPath($orderfrmtpl)) {
    exit("Invalid Order Form Template Name");
}
$orderconf = array();
$orderfrmconfig = ROOTDIR . "/templates/orderforms/" . $orderfrmtpl . "/config.php";


$orderform = true;
$nowrapper = false;
$errormessage = $allowcheckout = "";
$userid = (isset($_SESSION['uid']) ? $_SESSION['uid'] : "");
$currencyid = (isset($_SESSION['currency']) ? $_SESSION['currency'] : "");
$currency = getCurrency($userid, $currencyid);
$smartyvalues['currency'] = $currency;
$smartyvalues['ipaddress'] = $remote_ip;
$smartyvalues['ajax'] = ($ajax ? true : false);
$numproducts = (isset($_SESSION['cart']['products']) ? count($_SESSION['cart']['products']) : 0);
$numaddons = (isset($_SESSION['cart']['addons']) ? count($_SESSION['cart']['addons']) : 0);
//$numdomains = (isset($_SESSION['cart']['domains']) ? count($_SESSION['cart']['domains']) : 0);
$numrenewals = (isset($_SESSION['cart']['renewals']) ? count($_SESSION['cart']['renewals']) : 0);
$smartyvalues['numitemsincart'] = $numproducts + $numaddons + $numrenewals;

$templatefile = "myorder";
if (!$templatefile) {
    redir();
    exit();
}
$smartyvalues['carttpl'] = $orderfrm->getTemplate();
outputClientArea($templatefile, true);
?>