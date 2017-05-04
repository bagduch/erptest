<?php

/**
 *
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("List Invoices", false);
$aInt->requiredFiles(array("gatewayfunctions", "invoicefunctions", "processinvoices"));
$aInt->inClientsProfile = true;
if ($delete || $massdelete) {
    checkPermission("Delete Invoice");
}
if (($markpaid || $markunpaid) || $markcancelled) {
    checkPermission("Manage Invoice");
}
$aInt->valUserID($userid);
if ($markpaid) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        $result2 = select_query_i("tblinvoices", "paymentmethod", array("id" => $invid));
        $data = mysqli_fetch_array($result2);
        $paymentmethod = $data['paymentmethod'];
        addInvoicePayment($invid, "", "", "", $paymentmethod);
        run_hook("InvoicePaid", array("invoiceid" => $invoiceid));
    }
    if ($page) {
        $userid .= "&page=" . $page;
    }
    redir("userid=" . $userid . "&filter=1");
}

if ($markunpaid) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        update_query("tblinvoices", array("status" => "Unpaid", "datepaid" => "0000-00-00 00:00:00"), array("id" => $invid));
        logActivity("Reactivated Invoice - Invoice ID: " . $invid, $userid);
        run_hook("InvoiceUnpaid", array("invoiceid" => $invid));
    }


    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($markcancelled) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invid));
        logActivity("Cancelled Invoice - Invoice ID: " . $invid, $userid);
        run_hook("InvoiceCancelled", array("invoiceid" => $invid));
    }


    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($duplicateinvoice) {
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

    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($massdelete) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        delete_query("tblinvoices", array("id" => $invid));
        logActivity("Deleted Invoice - Invoice ID: " . $invid, $userid);
    }


    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($paymentreminder) {
    check_token("RA.admin.default");
    foreach ($selectedinvoices as $invid) {
        sendMessage("Invoice Payment Reminder", $invid);
        logActivity("Invoice Payment Reminder Sent - Invoice ID: " . $invid, $userid);
    }


    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($merge) {
    check_token("RA.admin.default");

    if (count($selectedinvoices) < 2) {
        if ($page) {
            $userid .= "&page=" . $page;
        }

        redir("userid=" . $userid . "&mergeerr=1");
        exit();
    }

    $selectedinvoices = db_escape_numarray($selectedinvoices);
    sort($selectedinvoices);
    $endinvoiceid = end($selectedinvoices);
    update_query("tblinvoiceitems", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
    update_query("tblaccounts", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
    update_query("tblorders", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
    $result = select_query_i("tblinvoices", "SUM(credit)", "id IN (" . db_build_in_array($selectedinvoices) . ")");
    $data = mysqli_fetch_array($result);
    $totalcredit = $data[0];
    update_query("tblinvoices", array("credit" => $totalcredit), array("id" => $endinvoiceid));
    unset($selectedinvoices[count($selectedinvoices) - 1]);
    delete_query("tblinvoices", "id IN (" . db_build_in_array($selectedinvoices) . ")");
    updateInvoiceTotal($endinvoiceid);
    logActivity("Merged Invoice IDs " . db_build_in_array($selectedinvoices) . (" to Invoice ID: " . $endinvoiceid), $userid);

    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}


if ($masspay) {
    check_token("RA.admin.default");

    if (count($selectedinvoices) < 2) {
        if ($page) {
            $userid .= "&page=" . $page;
        }

        redir("userid=" . $userid . "&masspayerr=1");
        exit();
    }

    $invoiceid = createInvoices($userid);
    $paymentmethod = getClientsPaymentMethod($userid);
    $invoiceitems = array();
    foreach ($selectedinvoices as $invoiceid) {
        $result = select_query_i("tblinvoices", "", array("id" => $invoiceid));
        $data = mysqli_fetch_array($result);
        $subtotal += $data['subtotal'];
        $credit += $data['credit'];
        $tax += $data['tax'];
        $tax2 += $data['tax2'];
        $thistotal = $data['total'];
        $result = select_query_i("tblaccounts", "SUM(amountin)", array("invoiceid" => $invoiceid));
        $data = mysqli_fetch_array($result);
        $thispayments = $data[0];
        $thistotal = $thistotal - $thispayments;
        insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Invoice", "relid" => $invoiceid, "description" => $_LANG['invoicenumber'] . $invoiceid, "amount" => $thistotal, "duedate" => "now()", "paymentmethod" => $paymentmethod));
    }

    $invoiceid = createInvoices($userid, true, true);
    redir("userid=" . $userid . "&masspayid=" . $invoiceid . "&filter=1");
}


if ($delete) {
    check_token("RA.admin.default");
    checkPermission("Delete Invoice");
    delete_query("tblinvoices", array("id" => $invoiceid));
    logActivity("Deleted Invoice - Invoice ID: " . $invoiceid, $userid);

    if ($page) {
        $userid .= "&page=" . $page;
    }

    redir("userid=" . $userid . "&filter=1");
}

$aInt->deleteJSConfirm("doDelete", "invoices", "delete", "clientsinvoices.php?userid=" . $userid . "&delete=true&invoiceid=");
$jquerycode .= "$(\".invtooltip\").tooltip({cssClass:\"invoicetooltip\"});";

if ($mergeerr) {
    infoBox($aInt->lang("invoices", "mergeerror"), $aInt->lang("invoices", "mergeerrordesc"));
}


if ($masspayerr) {
    infoBox($aInt->lang("invoices", "masspay"), $aInt->lang("invoices", "mergeerrordesc"));
}

if ($addpayment) {
    check_token("RA.admin.default");
    checkPermission("Add Transaction");

    if ($sendconfirmation == "on") {
        $sendconfirmation = "";
    } else {
        $sendconfirmation = "on";
    }


    addInvoicePayment($_POST['id'], $_POST['transid'], $_POST['amount'], $_POST['fees'], $_POST['paymentmethod'], $_POST['sendconfirmation'], $_POST['date']);
}

if ($masspayid) {
    infoBox($aInt->lang("invoices", "masspay"), $aInt->lang("invoices", "masspaysuccess") . " - <a href=\"invoices.php?action=edit&id=" . (int) $masspayid . "\">" . $aInt->lang("fields", "invoicenum") . $masspayid . "</a>");
}

$infobox;
$filt = new RA_Filter("clinv");
$filterops = array("serviceid", "addonid", "domainid", "clientname", "invoicenum", "lineitem", "paymentmethod", "invoicedate", "duedate", "datepaid", "totalfrom" . "totalto", "status");
$filt->setAllowedVars($filterops);
$filters = array();
$filters[] = "userid='" . (int) $userid . "'";

if ($serviceid = $filt->get("serviceid")) {
    $filters[] = "id IN (SELECT invoiceid FROM tblinvoiceitems WHERE relid='" . (int) $serviceid . "')";
}


if ($addonid = $filt->get("addonid")) {
    $filters[] = "id IN (SELECT invoiceid FROM tblinvoiceitems WHERE type='Addon' AND relid='" . (int) $addonid . "')";
}


if ($domainid = $filt->get("domainid")) {
    $filters[] = "id IN (SELECT invoiceid FROM tblinvoiceitems WHERE type IN ('DomainRegister','DomainTransfer','Domain') AND relid='" . (int) $domainid . "')";
}


if ($clientname = $filt->get("clientname")) {
    $filters[] = "concat(firstname,' ',lastname) LIKE '%" . db_escape_string($clientname) . "%'";
}


if ($invoicenum = $filt->get("invoicenum")) {
    $filters[] = "(tblinvoices.id='" . db_escape_string($invoicenumber) . "' OR tblinvoices.invoicenum='" . db_escape_string($invoicenumber) . "')";
}


if ($lineitem = $filt->get("lineitem")) {
    $filters[] = "tblinvoices.id IN (SELECT invoiceid FROM tblinvoiceitems WHERE userid=" . (int) $userid . " AND description LIKE '%" . db_escape_string($lineitem) . "%')";
}

if ($paymentmethod = $filt->get("paymentmethod")) {
    $filters[] = "tblinvoices.paymentmethod='" . db_escape_string($paymentmethod) . "'";
}


if ($invoicedate = $filt->get("invoicedate")) {
    $filters[] = "tblinvoices.date='" . toMySQLDate($invoicedate) . "'";
}


if ($duedate = $filt->get("duedate")) {
    $filters[] = "tblinvoices.duedate='" . toMySQLDate($duedate) . "'";
}


if ($datepaid = $filt->get("datepaid")) {
    $filters[] = "tblinvoices.datepaid>='" . toMySQLDate($datepaid) . "' AND tblinvoices.datepaid<='" . toMySQLDate($datepaid) . " 23:59:59'";
}


if ($totalfrom = $filt->get("totalfrom")) {
    $filters[] = "tblinvoices.total>='" . db_escape_string($totalfrom) . "'";
}


if ($totalto = $filt->get("totalto")) {
    $filters[] = "tblinvoices.total<='" . db_escape_string($totalto) . "'";
}


if ($status = $filt->get("status")) {
    if ($status == "Overdue") {
        $filters[] = "tblinvoices.status='Unpaid' AND tblinvoices.duedate<'" . date("Ymd") . "'";
    } else {
        $filters[] = "tblinvoices.status='" . db_escape_string($status) . "'";
    }
}

$filt->store();
releaseSession();

$currency = getCurrency($userid);
$gatewaysarray = getGatewaysArray();
$aInt->sortableTableInit("duedate", "DESC");
$result = select_query_i("tblinvoices", "COUNT(*)", implode(" AND ", $filters));
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$qryorderby = $orderby;

if ($qryorderby == "id") {
    $qryorderby = "tblinvoices`.`invoicenum` " . $order . ",`tblinvoices`.`id";
}

$result = select_query_i("tblinvoices", "", implode(" AND ", $filters), $qryorderby, $order, $page * $limit . ("," . $limit));

$invoicedata = array();
while ($data = mysqli_fetch_array($result)) {
    $id = $data['id'];
    $bresult = select_query_i("tblaccounts", "COUNT(id),SUM(amountin)-SUM(amountout)", array("invoiceid" => $id));
    $accounts = mysqli_fetch_array($bresult);
    $transcount = $accounts[0];
    $amountpaid = $accounts[1];
    $balance = $data['total'] - $amountpaid;
    $balance = $rawbalance = sprintf("%01.2f", $balance);
    $invoicenum = $data['invoicenum'];
    $date = $data['date'];
    $duedate = $data['duedate'];
    $datepaid = $data['datepaid'];
    $credit = $data['credit'];
    $total = $data['total'];
    $paymentmethod = $data['paymentmethod'];
    $paymentmethod = $gatewaysarray[$paymentmethod];
    $status = $data['status'];
    $status = getInvoiceStatusColour($status, false);
    $date = fromMySQLDate($date);
    $duedate = fromMySQLDate($duedate);
    $datepaid = ($datepaid == "0000-00-00 00:00:00" ? "-" : fromMySQLDate($datepaid));
    $total = formatCurrency($credit + $total);

    if (!$invoicenum) {
        $invoicenum = $id;
    }

    $invoicedata[$id] = $data;
    $invoicedata[$id]['balance'] = $balance;
    $tabledata[] = array("<input type=\"checkbox\" name=\"selectedinvoices[]\" value=\"" . $id . "\" class=\"checkall\">", "<a href=\"invoices.php?action=edit&id=" . $id . "\">" . $invoicenum . "</a>", $date, $duedate, $datepaid, "<a href=\"invoices.php?action=invtooltip&id=" . $id . "&userid=" . $userid . generate_token("link") . ("\" class=\"invtooltip\" lang=\"\">" . $total . "</a>"), $balance, $paymentmethod, $status, "<a href=\"../dl.php?type=i&id=" . $id . "\" class=\"btn btn-warning tableitem\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download\"><i class=\"fa fa-file-pdf-o\"></i></a><a href=\"#paymentadd" . $id . "\" data-toggle=\"modal\" class=\"btn btn-info tableitem\"><i data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add Fund\" class=\"fa fa-money\"></i></a><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\" href=\"invoices.php?action=edit&id=" . $id . "\"class=\"btn btn-success tableitem\"><i class=\"fa fa-pencil\" aria-hidden=\"true\" ></i></a><a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger tableitem\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
}

$tableformurl = $_SERVER['PHP_SELF'] . "?userid=" . $userid . "&filter=1";

if ($page) {
    $tableformurl .= "&page=" . $page;
}

$createinvoice = "<input type=\"button\" value=\"Create Invoice\" class=\"button\" onclick=\"window.location ='invoices.php?action=createinvoice&amp;userid=" . $userid . "&amp;token=" . generate_token('plain') . "'\">";
$tableformbuttons = "<input type=\"submit\" value=\"" . $aInt->lang("invoices", "markpaid") . "\" class=\"btn-success\" name=\"markpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markunpaid") . "\" name=\"markunpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markunpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markcancelled") . "\" name=\"markcancelled\" onclick=\"return confirm('" . $aInt->lang("invoices", "markcancelledconfirm", "1") . "')\" />    <input type=\"submit\" value=\"" . $aInt->lang("invoices", "merge") . "\" name=\"merge\" onclick=\"return confirm('" . $aInt->lang("invoices", "mergeconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("global", "delete") . "\" class=\"btn-danger\" name=\"massdelete\" onclick=\"return confirm('" . $aInt->lang("invoices", "massdeleteconfirm", "1") . "')\" /> " . $createinvoice;




$table = $aInt->sortableTable(
        array("checkall",
    array("id", $aInt->lang("fields", "invoicenum")),
    array("date", $aInt->lang("fields", "invoicedate")),
    array("duedate", $aInt->lang("fields", "duedate")),
    array("datepaid", $aInt->lang("fields", "datepaid")),
    array("total", $aInt->lang("fields", "total")),
    array("balance", $aInt->lang("fields", "balance")),
    array("paymentmethod", $aInt->lang("fields", "paymentmethod")),
    array("status", $aInt->lang("fields", "status")), "", ""), $tabledata, $tableformurl, $tableformbuttons);


if ($_POST['ajax']) {
    echo $table;
    exit();
}


$paymentdropdown = paymentMethodsSelection();

$aInt->assign("userid", $userid);
$aInt->assign("token", generate_token("link"));
$aInt->assign("paymentdropdown", $paymentdropdown);
$aInt->assign("intable", $table);
$aInt->assign("invoicedata", $invoicedata);
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->template = "client/invoices";
$aInt->display();
?>