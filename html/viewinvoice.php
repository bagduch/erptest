<?php
/** RA - Version 0.1 **/

define("CLIENTAREA", true);
require "init.php";
require "includes/gatewayfunctions.php";
require "includes/invoicefunctions.php";
require "includes/clientfunctions.php";
require "includes/countries.php";
$id = $invoiceid = (int)$ra->get_req_var("id");
$breadcrumbnav = "<a href=\"index.php\">" . $ra->get_lang("globalsystemname") . "</a> > <a href=\"clientarea.php\">" . $ra->get_lang("clientareatitle") . "</a> > <a href=\"clientarea.php?action=invoices\">" . $_LANG['invoices'] . "</a> > <a href=\"viewinvoice.php?id=" . $invoiceid . "\">" . $pagetitle . "</a>";
initialiseClientArea($ra->get_lang("invoicestitle") . $invoiceid, "", $breadcrumbnav);

if (!isset($_SESSION['uid']) && !isset($_SESSION['adminid'])) {
	$goto = "viewinvoice";
	require "login.php";
	exit();
}

$invoice = new RA_Invoice();
$invoice->setID($invoiceid);
$invoiceexists = $invoice->loadData();
$allowedaccess = (isset($_SESSION['adminid']) ? true : $invoice->isAllowed());

if (!$invoiceexists || !$allowedaccess) {
	$smarty->assign("error", "on");
	$template_output = $smarty->fetch($ra->get_sys_tpl_name() . "/viewinvoice.tpl");
	echo $template_output;
	exit();
}


if ((($invoice->getData("status") == "Paid" && isset($_SESSION['orderdetails'])) && $_SESSION['orderdetails']['InvoiceID'] == $invoiceid) && !$_SESSION['orderdetails']['paymentcomplete']) {
	$_SESSION['orderdetails']['paymentcomplete'] = true;
	redir("a=complete", "cart.php");
}

$gateway = $ra->get_req_var("gateway");

if ($gateway) {
	check_token();
	$gateways = new RA_Gateways();
	$validgateways = $gateways->getAvailableGateways($invoiceid);

	if (array_key_exists($gateway, $validgateways)) {
		update_query("ra_bills", array("paymentmethod" => $gateway), array("id" => $invoiceid));
		run_hook("InvoiceChangeGateway", array("invoiceid" => $invoiceid, "paymentmethod" => $gateway));
	}

	redir("id=" . $invoiceid);
}

$creditbal = get_query_val("ra_user", "credit", array("id" => $invoice->getData("userid")));

if ($invoice->getData("status") == "Unpaid" && 0 < $creditbal) {
	$balance = $invoice->getData("balance");
	$creditamount = $ra->get_req_var("creditamount");

	if ($ra->get_req_var("applycredit") && 0 < $creditamount) {
		check_token();

		if ($creditbal < $creditamount) {
			echo $_LANG['invoiceaddcreditovercredit'];
			exit();
		}
		else {
			if ($balance < $creditamount) {
				echo $_LANG['invoiceaddcreditoverbalance'];
				exit();
			}
			else {
				applyCredit($invoiceid, $invoice->getData("userid"), $creditamount);
			}
		}

		redir("id=" . $invoiceid);
	}

	$smartyvalues['manualapplycredit'] = true;
	$smartyvalues['totalcredit'] = formatCurrency($creditbal) . generate_token("form");

	if (!$creditamount) {
		$creditamount = ($balance <= $creditbal ? $balance : $creditbal);
	}

	$smartyvalues['creditamount'] = $creditamount;
}

$outputvars = $invoice->getOutput();
$smartyvalues = array_merge($smartyvalues, $outputvars);
$invoiceitems = $invoice->getLineItems();
$smartyvalues['invoiceitems'] = $invoiceitems;
$transactions = $invoice->getTransactions();
$smartyvalues['transactions'] = $transactions;
$paymentbutton = (($invoice->getData("status") == "Unpaid" && 0 < $invoice->getData("balance")) ? $invoice->getPaymentLink() : "");
$smartyvalues['paymentbutton'] = $paymentbutton;
$smartyvalues['offlinepaid'] = $ra->get_req_var("offlinepaid");

if ($ra->get_config("AllowCustomerChangeInvoiceGateway")) {
	$smartyvalues['allowchangegateway'] = true;
	$gateways = new RA_Gateways();
	$availablegateways = $gateways->getAvailableGateways($invoiceid);
	$frm = new RA_Form();
	$gatewaydropdown = generate_token("form") . $frm->dropdown("gateway", $availablegateways, $invoice->getData("paymentmodule"), "submit()");
	$smartyvalues['gatewaydropdown'] = $gatewaydropdown;
}
else {
	$smartyvalues['allowchangegateway'] = false;
}

outputClientArea("viewinvoice", true);
?>