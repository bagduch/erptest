<?php

/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 * */
define("ADMINAREA", true);
require "../init.php";
$action = $ra->get_req_var("action");

if ($action == "edit" || $action == "invtooltip") {
    $reqperm = "Manage Invoice";
} else {
    if ($action == "createinvoice") {
        $reqperm = "Create Invoice";
    } else {
        $reqperm = "List Invoices";
    }
}

$aInt = new RA_Admin($reqperm);

if ($action == "edit") {
    $pageicon = "invoicesedit";
    $pagetitle = $aInt->lang("fields", "invoicenum") . $id;
} else {
    $pageicon = "invoices";
    $pagetitle = $aInt->lang("invoices", "title");
}

$aInt->title = $pagetitle;
$aInt->sidebar = "billing";
$aInt->icon = $pageicon;
$aInt->requiredFiles(
        array(
            "clientfunctions",
            "invoicefunctions",
            "gatewayfunctions",
            "processinvoices",
            "ccfunctions"
        )
);
$invoiceid = (int) $ra->get_req_var("invoiceid");
$status = $ra->get_req_var("status");

/* if (!in_array($status, array("Unpaid", "Overdue", "Paid", "Cancelled", "Refunded", "Collections"))) {
  $status = "";
  } */


if ($action == "invtooltip") {
    check_token("RA.admin.default");
    echo "<table bgcolor=\"#cccccc\" cellspacing=\"1\" cellpadding=\"3\"><tr bgcolor=\"#efefef\" style=\"text-align:center;font-weight:bold;\"><td>" . $aInt->lang("fields", "description") . "</td><td>" . $aInt->lang("fields", "amount") . "</td></tr>";
    $currency = getCurrency($userid);
    $result = select_query_i("tblinvoiceitems", "", array("invoiceid" => $id), "id", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $lineid = $data['id'];
        echo "<tr bgcolor=\"#ffffff\"><td width=\"275\">" . nl2br($data['description']) . "</td><td width=\"100\" style=\"text-align:right;\">" . formatCurrency($data['amount']) . "</td></tr>";
    }

    $data = get_query_vals("tblinvoices", "subtotal,credit,tax,tax2,taxrate,taxrate2,total", array("id" => $id), "id", "ASC");
    echo "<tr bgcolor=\"#efefef\" style=\"text-align:right;font-weight:bold;\"><td>" . $aInt->lang("fields", "subtotal") . "&nbsp;</td><td>" . formatCurrency($data['subtotal']) . "</td></tr>";

    if ($CONFIG['TaxEnabled']) {
        if (0 < $data['tax']) {
            echo "<tr bgcolor=\"#efefef\" style=\"text-align:right;font-weight:bold;\"><td>" . $data['taxrate'] . "% " . $aInt->lang("fields", "tax") . "&nbsp;</td><td>" . formatCurrency($data['tax']) . "</td></tr>";
        }


        if (0 < $data['tax2']) {
            echo "<tr bgcolor=\"#efefef\" style=\"text-align:right;font-weight:bold;\"><td>" . $data['taxrate2'] . "% " . $aInt->lang("fields", "tax") . "&nbsp;</td><td>" . formatCurrency($data['tax2']) . "</td></tr>";
        }
    }

    echo "<tr bgcolor=\"#efefef\" style=\"text-align:right;font-weight:bold;\"><td>" . $aInt->lang("fields", "credit") . "&nbsp;</td><td>" . formatCurrency($data['credit']) . "</td></tr>";
    echo "<tr bgcolor=\"#efefef\" style=\"text-align:right;font-weight:bold;\"><td>" . $aInt->lang("fields", "totaldue") . "&nbsp;</td><td>" . formatCurrency($data['total']) . "</td></tr>";
    echo "</table>";
    exit();
}
if ($action == "createinvoice") {
    check_token("RA.admin.default");

    if (!checkActiveGateway()) {
        $aInt->gracefulExit($aInt->lang("gateways", "nonesetup"));
    }

    $gateway = getClientsPaymentMethod($userid);

    if ($CONFIG['TaxEnabled'] == "on") {
        $clientsdetails = getClientsDetails($userid);

        if (!$clientsdetails['taxexempt']) {
            $state = $clientsdetails['state'];
            $country = $clientsdetails['country'];
            $taxdata = getTaxRate(1, $state, $country);
            $taxdata2 = getTaxRate(2, $state, $country);
            $taxrate = $taxdata['rate'];
            $taxrate2 = $taxdata2['rate'];
        }
    }

    $duedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBefore'], date("Y")));
    $invoiceid = insert_query("tblinvoices", array("date" => "now()", "duedate" => $duedate, "userid" => $userid, "status" => "Draft", "paymentmethod" => $gateway, "taxrate" => $taxrate, "taxrate2" => $taxrate2));
    logActivity("Created Manual Invoice - Invoice ID: " . $invoiceid, $userid);

    if (1 < $CONFIG['InvoiceIncrement']) {
        $invoiceincrement = $CONFIG['InvoiceIncrement'] - 1;
        $counter = 1;

        while ($counter <= $invoiceincrement) {
            $tempinvoiceid = insert_query("tblinvoices", array("date" => "now()"));
            delete_query("tblinvoices", array("id" => $tempinvoiceid));
            $counter += 1;
        }
    }

    run_hook("InvoiceCreationAdminArea", array("invoiceid" => $invoiceid));
    redir("action=edit&id=" . $invoiceid);
    exit();
}
$filters = new RA_Filter();
if ($ra->get_req_var("markpaid")) {
    check_token("RA.admin.default");
    checkPermission("Manage Invoice");
    foreach ($selectedinvoices as $invid) {
        $result2 = select_query_i("tblinvoices", "paymentmethod, ppi", array("id" => $invid));
        $data = mysqli_fetch_array($result2);
        $paymentmethod = $data['paymentmethod'];
        addInvoicePayment($invid, "", "", "", $paymentmethod);

        if ($data['ppi'] == 0) {
            update_query("tblinvoices", array("ppi" => "1"), array("id" => $invid));
            continue;
        }
    }

    $filters->redir();
}
if ($ra->get_req_var("markunpaid")) {
    check_token("RA.admin.default");
    checkPermission("Manage Invoice");
    foreach ($selectedinvoices as $invid) {
        update_query("tblinvoices", array("status" => "Unpaid", "datepaid" => "0000-00-00 00:00:00"), array("id" => $invid));
        logActivity("Reactivated Invoice - Invoice ID: " . $invid);
        run_hook("InvoiceUnpaid", array("invoiceid" => $invid));
    }

    $filters->redir();
}
if ($ra->get_req_var("markcancelled")) {
    check_token("RA.admin.default");
    checkPermission("Manage Invoice");
    foreach ($selectedinvoices as $invid) {
        update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invid));
        logActivity("Cancelled Invoice - Invoice ID: " . $invid);
        run_hook("InvoiceCancelled", array("invoiceid" => $invid));
    }

    $filters->redir();
}
if ($ra->get_req_var("duplicateinvoice")) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        $result_duplicate = select_query_i("tblinvoices", "userid,invoicenum,date,duedate,datepaid,subtotal,credit,tax,tax2,total,taxrate2,status,paymentmethod,notes", array("id" => $invid));
        $data_duplicate = mysqli_fetch_assoc($result_duplicate);
        $datefrom = fromMySQLDate($data_duplicate['date']);
        $date = toMySQLDate($datefrom);
        $duedatefrom = fromMySQLDate($data_duplicate['duedate']);
        $duedate = toMySQLDate($duedatefrom);
        $datepaidfrom = fromMySQLDate($data_duplicate['datepaid']);
        $datepaid = toMySQLDate($datepaidfrom);
        insert_query("tblinvoices", array("userid" => $data_duplicate['userid'], "invoicenum" => $data_duplicate['invoicenum'], "date" => $date, "duedate" => $duedate, "datepaid" => $datepaid, "subtotal" => $data_duplicate['subtotal'], "credit" => $data_duplicate['credit'], "tax" => $data_duplicate['tax'], "tax2" => $data_duplicate['tax2'], "total" => $data_duplicate['total'], "taxrate2" => $data_duplicate['taxrate2'], "status" => $data_duplicate['status'], "paymentmethod" => $data_duplicate['paymentmethod'], "notes" => $data_duplicate['notes']), array("id" => $invid));
        logActivity("Duplicated Invoice(s) - Invoice ID: " . $invid, $userid);
    }

    $filters->redir();
}
if ($ra->get_req_var("massdelete")) {
    check_token("RA.admin.default");
    checkPermission("Delete Invoice");
    foreach ($selectedinvoices as $invid) {
        delete_query("tblinvoices", array("id" => $invid));
        logActivity("Deleted Invoice - Invoice ID: " . $invid);
    }

    $filters->redir();
}
if ($ra->get_req_var("paymentreminder")) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        sendMessage("Invoice Payment Reminder", $invid);
        logActivity("Invoice Payment Reminder Sent - Invoice ID: " . $invid);
    }

    $filters->redir();
}
if ($ra->get_req_var("delete")) {
    check_token("RA.admin.default");
    checkPermission("Delete Invoice");
    delete_query("tblinvoices", array("id" => $invoiceid));
    logActivity("Deleted Invoice - Invoice ID: " . $invoiceid);
    $filters->redir();
}

if ($action == "") {
    $aInt->deleteJSConfirm("doDelete", "invoices", "delete", $_SERVER['PHP_SELF'] . "?status=" . $status . "&delete=true&invoiceid=");
    $name = "invoices";
    $orderby = "duedate";
    $sort = "DESC";
    $pageObj = new RA_Pagination($name, $orderby, $sort);
    $pageObj->digestCookieData();
    $tbl = new RA_ListTable($pageObj);
    $tbl->setColumns(
            array(
                "checkall",
                array("id", $aInt->lang("fields", "invoicenum")),
                array("clientname", $aInt->lang("fields", "clientname")),
                array("date", $aInt->lang("fields", "invoicedate")),
                array("duedate", $aInt->lang("fields", "duedate")),
                array("total", $aInt->lang("fields", "total")),
                array("paymentmethod", $aInt->lang("fields", "paymentmethod")),
                array("status", $aInt->lang("fields", "status")),
                "",
                ""
            )
    );
    $invoicesModel = new RA_Invoices($pageObj);

    if (checkPermission("View Income Totals", true)) {
        $invoicetotals = $invoicesModel->getInvoiceTotals();

        if (count($invoicetotals)) {
            $topwrap = "<div class=\"contentbox\" style=\"font-size:18px;\">";
            foreach ($invoicetotals as $vals) {
                $topwrap.= "<b>" . $vals['currencycode'] . "</b> "
                        . $aInt->lang("status", "draft") . ": <span class=\"textgreen\"><b>" . $vals['draft'] . "</b></span> "
                        . $aInt->lang("status", "paid") . ": <span class=\"textgreen\"><b>" . $vals['paid'] . "</b></span> "
                        . $aInt->lang("status", "unpaid") . ": <span class=\"textred\"><b>" . $vals['unpaid'] . "</b></span> "
                        . $aInt->lang("status", "overdue") . ": <span class=\"textblack\"><b>" . $vals['overdue'] . "</b></span><br />";
            }

            $topwrap.= "</div><br />";
        }
    }


    $clientid = $filters->get("clientid");
    $invoicenum = $filters->get("invoicenum");
    $clientname = $filters->get("clientname");
    $invoicedate = $filters->get("invoicedate");
    $lineitem = $filters->get("lineitem");
    $duedate = $filters->get("duedate");
    $paymentmethod = $filters->get("paymentmethod");
    $datepaid = $filters->get("datepaid");
    $status = $filters->get("status");
    $totalto = $filters->get("totalto");
    $totalfrom = $filters->get("totalfrom");
    $totalto = $filters->get("totalto");

    $jquerycode = "$(\".invtooltip\").tooltip({cssClass:\"invoicetooltip\"});";
    $aInt->jquerycode = $jquerycode;
    $filters->store();
    $criteria = array("clientid" => $clientid, "clientname" => $clientname, "invoicenum" => $invoicenum, "lineitem" => $lineitem, "paymentmethod" => $paymentmethod, "invoicedate" => $invoicedate, "duedate" => $duedate, "datepaid" => $datepaid, "totalfrom" => $totalfrom, "totalto" => $totalto, "status" => $status);
    $invoicesModel->execute($criteria);
    $numresults = $pageObj->getNumResults();

    if ($filters->isActive() && $numresults == 1) {
        $invoice = $pageObj->getOne();
        redir("action=edit&id=" . $invoice['id'], "invoices.php");
    } else {
        $invoicelist = $pageObj->getData();
        foreach ($invoicelist as $invoice) {
            $linkopen = "<a href=\"invoices.php?action=edit&id=" . $invoice['id'] . "\">";
            $linkclose = "</a>";
            $tbl->addRow(array("<input type=\"checkbox\" name=\"selectedinvoices[]\" value=\"" . $invoice['id'] . "\" class=\"checkall\">", $linkopen . $invoice['invoicenum'] . $linkclose, $invoice['clientname'], $invoice['date'], $invoice['duedate'], "<a href=\"invoices.php?action=invtooltip&id=" . $invoice['id'] . "&userid=" . $invoice['userid'] . generate_token("link") . "\" class=\"invtooltip\" lang=\"\">" . $invoice['totalformatted'] . "</a>", $invoice['paymentmethod'], $invoice['statusformatted'], $linkopen . "<img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit\">" . $linkclose, "<a href=\"#\" onClick=\"doDelete('" . $invoice['id'] . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete\"></a>"));
        }

        $tbl->setMassActionBtns("<input type=\"submit\" value=\"" . $aInt->lang("invoices", "markpaid") . "\" class=\"btn-success\" name=\"markpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markunpaid") . "\" name=\"markunpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markunpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markcancelled") . "\" name=\"markcancelled\" onclick=\"return confirm('" . $aInt->lang("invoices", "markcancelledconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "duplicateinvoice") . "\" name=\"duplicateinvoice\" onclick=\"return confirm('" . $aInt->lang("invoices", "duplicateinvoiceconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "sendreminder") . "\" name=\"paymentreminder\" onclick=\"return confirm('" . $aInt->lang("invoices", "sendreminderconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("global", "delete") . "\" class=\"btn-danger\" name=\"massdelete\"  onclick=\"return confirm('" . $aInt->lang("invoices", "massdeleteconfirm", "1") . "')\" />");
        $table = $tbl->output();
        $aInt->assign("table", $table);
        $aInt->assign("PHP_SELF", $_SERVER['PHP_SELF']);
        $aInt->assign("topwrap", $topwrap);
        $aInt->assign("paymentmethod", paymentMethodsSelection($aInt->lang("global", "any")));
        $aInt->template = "invoices/view";
        unset($clientlist);
        unset($invoicesModel);
    }
} else {
    if ($action == "edit") {
        $result = select_query_i("tblinvoices", "userid,paymentmethod", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $userid = $data[0];
        $oldpaymentmethod = $data[1];
        if ($saveoptions) {
            check_token("RA.admin.default");
            update_query("tblinvoices", array("date" => toMySQLDate($invoicedate), "duedate" => toMySQLDate($datedue), "paymentmethod" => $paymentmethod, "invoicenum" => $invoicenum, "taxrate" => $taxrate, "taxrate2" => $taxrate2, "status" => $status), array("id" => $id));
            updateInvoiceTotal($id);

            if ($oldpaymentmethod != $paymentmethod) {
                run_hook("InvoiceChangeGateway", array("invoiceid" => $id, "paymentmethod" => $paymentmethod));
            }

            logActivity("Modified Invoice Options - Invoice ID: " . $id, $userid);
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($save == "notes") {
            check_token("RA.admin.default");
            update_query("tblinvoices", array("notes" => $notes), array("id" => $id));
            logActivity("Modified Invoice Notes - Invoice ID: " . $id, $userid);
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($sub == "statuscancelled") {
            check_token("RA.admin.default");
            update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $id));
            logActivity("Cancelled Invoice - Invoice ID: " . $id, $userid);
            run_hook("InvoiceCancelled", array("invoiceid" => $id));
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($sub == "statusunpaid") {
            check_token("RA.admin.default");
            update_query("tblinvoices", array("status" => "Unpaid"), array("id" => $id));
            logActivity("Reactivated Invoice - Invoice ID: " . $id, $userid);
            run_hook("InvoiceUnpaid", array("invoiceid" => $id));
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($sub == "markpaid") {
            check_token("RA.admin.default");
            checkPermission("Add Transaction");

            if ($sendconfirmation == "on") {
                $sendconfirmation = "";
            } else {
                $sendconfirmation = "on";
            }

            addInvoicePayment($id, $transid, $amount, $fees, $paymentmethod, $sendconfirmation, $date);
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($sub == "save") {
            check_token("RA.admin.default");

            if ($description) {
                foreach ($description as $lineid => $desc) {
                    update_query("tblinvoiceitems", array("description" => $desc, "amount" => $amount[$lineid], "taxed" => $taxed[$lineid]), array("id" => $lineid));
                }
            }


            if ($adddescription) {
                insert_query("tblinvoiceitems", array(
                    "invoiceid" => $id,
                    "userid" => $userid,
                    "description" => $adddescription,
                    "amount" => $addamount,
                    "taxed" => $addtaxed
                        )
                );
            }


            if ($selaction == "delete" && is_array($itemids)) {
                foreach ($itemids as $itemid) {
                    delete_query("tblinvoiceitems", array("id" => $itemid));
                }
            }


            if ($selaction == "split" && is_array($itemids)) {
                $result = select_query_i("tblinvoices", "userid,date,duedate,taxrate,taxrate2,paymentmethod", array("id" => $id));
                $data = mysqli_fetch_array($result);
                $userid = $data[0];
                $date = $data[1];
                $duedate = $data[2];
                $taxrate = $data[3];
                $taxrate2 = $data[4];
                $paymentmethod = $data[5];
                $result = select_query_i("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $id));
                $data = mysqli_fetch_array($result);
                $totalitemscount = $data[0];

                if (count($itemids) < $totalitemscount) {
                    $invoiceid = insert_query("tblinvoices", array("date" => $date, "duedate" => $duedate, "userid" => $userid, "status" => "Unpaid", "paymentmethod" => $paymentmethod, "taxrate" => $taxrate, "taxrate2" => $taxrate2));

                    if (1 < $CONFIG['InvoiceIncrement']) {
                        $invoiceincrement = $CONFIG['InvoiceIncrement'] - 1;
                        $counter = 1;

                        while ($counter <= $invoiceincrement) {
                            $tempinvoiceid = insert_query("tblinvoices", array("date" => "now()"));
                            delete_query("tblinvoices", array("id" => $tempinvoiceid));
                            $counter += 1;
                        }
                    }

                    foreach ($itemids as $itemid) {
                        update_query("tblinvoiceitems", array("invoiceid" => $invoiceid), array("id" => $itemid));
                    }

                    updateInvoiceTotal($invoiceid);
                    updateInvoiceTotal($id);
                    logActivity("Split Invoice - Invoice ID: " . $id . " to Invoice ID: " . $invoiceid, $userid);
                    redir("action=edit&id=" . $invoiceid);
                    exit();
                }
            }

            updateInvoiceTotal($id);
            $result = select_query_i("tblinvoices", "userid", array("id" => $id));
            $data = mysqli_fetch_array($result);
            $userid = $data[0];
            logActivity("Modified Invoice - Invoice ID: " . $id, $userid);
            redir("action=edit&id=" . $id);
            exit();
        }


        if ($addcredit != "0.00" && $addcredit) {
            check_token("RA.admin.default");
            $result2 = select_query_i("tblinvoices", "userid,subtotal,credit,total", array("id" => $id));
            $data = mysqli_fetch_array($result2);
            $userid = $data['userid'];
            $subtotal = $data['subtotal'];
            $credit = $data['credit'];
            $total = $data['total'];
            $result2 = select_query_i("tblaccounts", "SUM(amountin)-SUM(amountout)", array("invoiceid" => $id));
            $data = mysqli_fetch_array($result2);
            $amountpaid = $data[0];
            $balance = $total - $amountpaid;

            if ($CONFIG['TaxType'] == "Inclusive") {
                $subtotal = $total;
            }

            $addcredit = round($addcredit, 2);
            $balance = round($balance, 2);
            $result2 = select_query_i("tblclients", "credit", array("id" => $userid));
            $data = mysqli_fetch_array($result2);
            $totalcredit = $data['credit'];

            if ($totalcredit < $addcredit) {
                infoBox("An Error Occurred", "You cannot apply more credit than the client's credit balance");
            } else {
                if ($balance < $addcredit) {
                    infoBox("An Error Occurred", "You cannot apply more credit than the invoice total");
                } else {
                    applyCredit($id, $userid, $addcredit);
                    $currency = getCurrency($userid);
                    infoBox("Success", formatCurrency($addcredit) . " credit was successfully added to the invoice");
                }
            }
        }


        if ($removecredit != "0.00" && $removecredit != "") {
            check_token("RA.admin.default");
            $result2 = select_query_i("tblinvoices", "userid,subtotal,credit,total", array("id" => $id));
            $data = mysqli_fetch_array($result2);
            $userid = $data['userid'];
            $subtotal = $data['subtotal'];
            $credit = $data['credit'];
            $total = $data['total'];

            if ($credit < $removecredit) {
                infoBox("An Error Occurred", "You cannot remove more credit than the invoice has applied");
            } else {
                update_query("tblinvoices", array("credit" => "-=" . $removecredit), array("id" => (int) $id));
                updateInvoiceTotal($id);
                update_query("tblclients", array("credit" => "+=" . $removecredit), array("id" => (int) $userid));
                insert_query("tblcredit", array("clientid" => $userid, "date" => "now()", "description" => "Credit Removed from Invoice #" . $id, "amount" => $removecredit));
                logActivity("Credit Removed - Amount: " . $removecredit . " - Invoice ID: " . $id, $userid);
                $currency = getCurrency($userid);
                infoBox("Success", formatCurrency($removecredit) . " credit was successfully removed from the invoice");
            }
        }


        if ($sub == "delete") {
            check_token("RA.admin.default");
            delete_query("tblinvoiceitems", array("id" => $iid));
            updateInvoiceTotal($id);
            redir("action=edit&id=" . $id);
            exit();
        }

        $result = select_query_i("tblinvoices", "tblpaymentgateways.value", array("tblpaymentgateways.setting" => "type", "tblinvoices.id" => $id), "", "", "", "tblclients ON tblclients.id=tblinvoices.userid INNER JOIN tblpaymentgateways ON tblpaymentgateways.gateway=tblinvoices.paymentmethod");
        $data = mysqli_fetch_array($result);
        $type = $data['value'];

        if ($tplname) {
            check_token("RA.admin.default");
            sendMessage($tplname, $id, "", true);
        }


        if ($type == "CC") {
            if ($sub == "attemptpayment") {
                check_token("RA.admin.default");
                $data = get_query_vals("tblclients", "cardtype,gatewayid", array("id" => $userid));

                if ($data[0] || $data[1]) {
                    logActivity("Admin Initiated Payment Capture - Invoice ID: " . $id, $userid);

                    if (captureCCPayment($id)) {
                        infoBox($aInt->lang("invoices", "capturesuccessful"), $aInt->lang("invoices", "capturesuccessfulmsg"), "success");
                    } else {
                        infoBox($aInt->lang("invoices", "captureerror"), $aInt->lang("invoices", "captureerrormsg"), "error");
                    }
                } else {
                    infoBox($aInt->lang("invoices", "captureerror"), "No Credit Card Details are stored for this client so the capture could not be attempted", "info");
                }
            }


            if ($sub == "initiatepayment") {
                check_token("RA.admin.default");
                $data = get_query_vals("tblclients", "gatewayid", array("id" => $userid));
                logActivity("Admin Initiated Payment Attempt - Invoice ID: " . $id, $userid);

                if (captureCCPayment($id)) {
                    infoBox($aInt->lang("invoices", "initiatepaymentsuccessful"), $aInt->lang("invoices", "initiatepaymentsuccessfulmsg"), "success");
                } else {
                    infoBox($aInt->lang("invoices", "initiatepaymenterror"), $aInt->lang("invoices", "initiatepaymenterrormsg"), "error");
                }
            }
        }


        if ($sub == "refund" && $transid) {
            check_token("RA.admin.default");
            checkPermission("Refund Invoice Payments");
            logActivity("Admin Initiated Refund - Invoice ID: " . $id . " - Transaction ID: " . $transid);

            if ($refundtype == "sendtogateway") {
                $sendtogateway = true;
            } else {
                if ($refundtype == "addascredit") {
                    $addascredit = true;
                }
            }

            $result = refundInvoicePayment($transid, $amount, $sendtogateway, $addascredit, $sendemail, $refundtransid);

            if ($result == "manual") {
                infoBox($aInt->lang("invoices", "refundsuccess"), $aInt->lang("invoices", "refundmanualsuccessmsg"));
            } else {
                if ($result == "amounterror") {
                    infoBox($aInt->lang("invoices", "refundfailed"), $aInt->lang("invoices", "refundamounterrormsg"));
                } else {
                    if ($result == "success") {
                        infoBox($aInt->lang("invoices", "refundsuccess"), $aInt->lang("invoices", "refundsuccessmsg"));
                    } else {
                        if ($result == "creditsuccess") {
                            infoBox($aInt->lang("invoices", "refundsuccess"), $aInt->lang("invoices", "refundcreditmsg"));
                        } else {
                            infoBox($aInt->lang("invoices", "refundfailed"), $aInt->lang("invoices", "refundfailedmsg"));
                        }
                    }
                }
            }
        }


        if ($sub == "deletetrans") {
            check_token("RA.admin.default");
            checkPermission("Delete Transaction");
            delete_query("tblaccounts", array("id" => $ide));
            logActivity("Deleted Transaction - Transaction ID: " . $ide);
            redir("action=edit&id=" . $id);
            exit();
        }
        $gatewaysarray = getGatewaysArray();
        $result = select_query_i("tblinvoices", "tblinvoices.*,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblclients.groupid,tblclients.state,tblclients.country", array("tblinvoices.id" => $id), "", "", "", "tblclients ON tblclients.id=tblinvoices.userid");
        $invoices = mysqli_fetch_array($result);
        if (!$invoices['id']) {
            $aInt->gracefulExit("Invoice ID Not Found");
        } else {
            $currency = getCurrency($userid);
            $result = select_query_i("tblaccounts", "COUNT(id),SUM(amountin)-SUM(amountout)", array("invoiceid" => $id));
            $accounts = mysqli_fetch_array($result);
            $transcount = $accounts[0];
            $amountpaid = $accounts[1];
            $balance = $invoices['total'] - $amountpaid;
            $balance = $rawbalance = sprintf("%01.2f", $balance);
            $invoices['totalcurrency'] = formatCurrency($invoices['total']);
            $invoices['subtotalcurrency'] = formatCurrency($invoices['subtotal']);
            $invoices['creditcurrency'] = formatCurrency($invoices['credit']);
            $invoices['subtotalcurrency'] = formatCurrency($invoices['subtotal']);
            loadGatewayModule($invoices['paymentmethod']);


            $details = array();
            $result = select_query_i("tblinvoiceitems", "", array("invoiceid" => $id), "id", "ASC");
            while ($data = mysqli_fetch_array($result)) {
                $details[$data['id']] = $data;
                $linecount = explode("\r\n", $data['description']);
                $details[$data['id']]['linecount'] = count($linecount);
            }
            $transactions = array();
            $result = select_query_i("tblaccounts", "", array("invoiceid" => $id), "date` ASC,`id", "ASC");
            while ($data = mysqli_fetch_array($result)) {
                $transactions[] = $data;
            }
            if (empty($transactions)) {
                $transactions = 0;
            }

            $aInt->assign("paymentmethod", paymentMethodsSelection());
            $aInt->assign("transactions", $transactions);
            $aInt->assign("details", $details);
            $aInt->assign("invoice", $invoices);
            $aInt->assign("balancecurrency", formatCurrency($balance));
            $aInt->assign("balance", $balance);
            $aInt->assign("tokens", get_token());
        }
        $aInt->template = "invoices/edit";
    }
}


$aInt->display();
?>
