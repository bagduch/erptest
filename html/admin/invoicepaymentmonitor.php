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
            "period" => $day,
            "suspension" => $_POST['suspension'],
        );
        update_query("tblinvoices", array("status" => "Payment Plan"), array("id" => $id));
        if ($_POST['newpaymentplan']) {
            insert_query("tblinvoicepaymentmonitor", $array);
            logActivity("Invoice change to payment plan - Invoice ID: " . $_POST['id'], $_SESSION['adminid']);
        } else {
            update_query("tblinvoicepaymentmonitor", $array, array('invoice_id' => $_POST['id']));
            logActivity("Update payment plan - Invoice ID: " . $_POST['id'], $_SESSION['adminid']);
        }
    }
} elseif ($action == 'edit') {
    check_token("RA.admin.default");
    checkPermission("Edit Invoice Payment Monitor");
} else {
    check_token("RA.admin.default");
    checkPermission("View Invoice Payment Monitor");


    $paymentplan = array();
    $query = "select ti.total,tc.firstname,tc.lastname,tc.companyname,tc.email,tc.mobilenumber,tipm.invoice_id,ti.notes,tipm.suspension,tipm.date,tipm.duedate,tipm.period from tblinvoicepaymentmonitor as tipm"
            . " INNER JOIN tblinvoices as ti on tipm.invoice_id = ti.id "
            . " INNER JOIN tblclients as tc on tc.id = ti.userid"
            . " Order by tipm.date DESC";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_assoc($result)) {
        $paymentplan[$data['invoice_id']] = $data;
        $paymentplan[$data['invoice_id']]['days'] = (strtotime($data['duedate']) - strtotime($data['date'])) / (60 * 60 * 24);
        $paymenttimes = intdiv($paymentplan[$data['invoice_id']]['days'], $data['period']);
        $paymentplan[$data['invoice_id']]['amount'] = floor(100 * $data['total'] / $paymenttimes + 0.99) / 100;
        $balanace = $data['total'];
        $query2 = select_query_i("tblaccounts", "amountin,date", array("invoiceid" => $data['invoice_id']));
        if ($query2->num_rows != 0) {
            while ($trans = mysqli_fetch_assoc($query2)) {
                $paymentplan[$data['invoice_id']]['transections'][] = array(
                    "date" => $trans['date'],
                    'amount' => $trans['amountin']
                );
                $balanace = $balanace - $trans['amountin'];
            }
        } else {
            $paymentplan[$data['invoice_id']]['transections'] = array();
        }
        $paymentplan[$data['invoice_id']]['balance'] = formatCurrency($balanace);
    }
    $aInt->assign("paymentplan", $paymentplan);
    $aInt->template = "invoices/paymentmonitor";
}

$aInt->display();
