<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("ADMINAREA", true);
require "../init.php";
if ($action == "edit") {
    $reqperm = "Edit Invoice Payment Monitor";
} else {
    $reqperm = "View Invoice Payment Monitor";
}

$aInt = new RA_Admin($reqperm);
$aInt->title = $aInt->lang("transactions", "title");
$aInt->sidebar = "billing";
$aInt->icon = "transactions";
$aInt->requiredFiles(array("gatewayfunctions", "invoicefunctions"));

if ($action == "getpaymentplan") {

    if (isset($_POST['days'])) {


        $day = $_POST['days'];
        if ($_POST['paymenttype'] == "fornightly") {
            $day = 14;
        } elseif ($_POST['paymenttype'] == "weekly") {
            $day = 7;
        } else {
            $day = 30;
        }
        $paymenttimes = intdiv($_POST['days'], $day);
        $eachpay = floor(100 * $_POST['balance'] / $paymenttimes + 0.99) / 100;
        $dateformat = str_replace(array("DD", "MM", "YYYY"), array("d", "m", "Y"), $CONFIG['DateFormat']);
        $firsttday = date($dateformat, strtotime(toMySQLDate($_POST['start']) . "+" . $day . " days"));
        $data = array("amount" => $eachpay, "start" => $firsttday);
        header('Content-type: application/json');
        echo json_encode($data);
    }
    exit();
}

if ($action == "add") {
    check_token("RA.admin.default");
    checkPermission("Edit Invoice Payment Monitor");

    if (isset($_POST['id'])) {
        if ($_POST['paymenttype'] == "fornightly") {
            $day = 14;
        } elseif ($_POST['paymenttype'] == "weekly") {
            $day = 7;
        } else {
            $day = 30;
        }
        $duedate = date('Y-m-d', strtotime(toMySQLDate($_POST['date']) . "+" . $_POST['daystopay'] . " days"));
        $array = array(
            "invoice_id" => $_POST['id'],
            "date" => toMySQLDate($_POST['date']),
            "duedate" => $duedate,
            "nextduedate" => date('Y-m-d', strtotime(toMySQLDate($_POST['date']) . "+" . $day . " days")),
            "period" => $day,
            "suspension" => $_POST['suspension'],
        );
        update_query("ra_bills", array("status" => "Payment Plan"), array("id" => $id));
        if ($_POST['newpaymentplan']) {
            insert_query("ra_bill_payment_monitor", $array);
            logActivity("Invoice change to payment plan - Invoice ID: " . $_POST['id'], $_SESSION['adminid']);
        } else {
            update_query("ra_bill_payment_monitor", $array, array('invoice_id' => $_POST['id']));
            logActivity("Update payment plan - Invoice ID: " . $_POST['id'], $_SESSION['adminid']);
        }
    }
} elseif ($action == 'edit') {
    check_token("RA.admin.default");
    checkPermission("Edit Invoice Payment Monitor");
} else {
    check_token("RA.admin.default");
    checkPermission("View Invoice Payment Monitor");


    $paymentplan = getAllInvoicePaymentplans();
    $aInt->assign("paymentplan", $paymentplan);
    $aInt->template = "invoices/paymentmonitor";
}

$aInt->display();
