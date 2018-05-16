<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Manage Affiliates");
$aInt->title = $aInt->lang("affiliates", "title");
$aInt->sidebar = "clients";
$aInt->icon = "affiliates";
$aInt->helplink = "Affiliates";
$menuselect = "$('#menu').multilevelpushmenu('expand','Customers');";
$aInt->requiredFiles(array("invoicefunctions", "gatewayfunctions"));

if ($action == "save") {
    check_token("RA.admin.default");
    update_query("ra_partners", array("paytype" => $paymenttype, "payamount" => $payamount, "onetime" => $onetime, "visitors" => $visitors, "balance" => $balance, "withdrawn" => $withdrawn), array("id" => $id));
    logActivity("Affiliate ID " . $id . " Details Updated");
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletecommission") {
    check_token("RA.admin.default");
    delete_query("ra_partnerspending", array("id" => $cid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletehistory") {
    check_token("RA.admin.default");
    delete_query("ra_partnershistory", array("id" => $hid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletereferral") {
    check_token("RA.admin.default");
    delete_query("ra_partnersaccounts", array("id" => $affaccid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletewithdrawal") {
    check_token("RA.admin.default");
    delete_query("ra_partnerswithdrawals", array("id" => $wid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "addcomm") {
    check_token("RA.admin.default");
    $amount = format_as_currency($amount);
    insert_query("ra_partnershistory", array("affiliateid" => $id, "date" => toMySQLDate($date), "affaccid" => $refid, "description" => $description, "amount" => $amount));
    update_query("ra_partners", array("balance" => "+=" . $amount), array("id" => (int) $id));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "withdraw") {
    check_token("RA.admin.default");
    insert_query("ra_partnerswithdrawals", array("affiliateid" => $id, "date" => "now()", "amount" => $amount));
    update_query("ra_partners", array("balance" => "-=" . $amount, "withdrawn" => "+=" . $amount), array("id" => (int) $id));

    if ($payouttype == "1") {
        $result = select_query_i("ra_partners", "", array("id" => (int) $id));
        $data = mysqli_fetch_array($result);
        $id = (int) $data['id'];
        $clientid = (int) $data['clientid'];
        addTransaction($clientid, "", "Affiliate Commissions Withdrawal Payout", "0", "0", $amount, $paymentmethod, $transid);
    } else {
        if ($payouttype == "2") {
            $result = select_query_i("ra_partners", "", array("id" => (int) $id));
            $data = mysqli_fetch_array($result);
            $id = (int) $data['id'];
            $clientid = (int) $data['clientid'];
            insert_query("ra_transactions_credit", array("clientid" => $clientid, "date" => "now()", "description" => "Affiliate Commissions Withdrawal", "amount" => $amount));
            update_query("ra_user", array("credit" => "+=" . $amount), array("id" => $clientid));
            logActivity("Processed Affiliate Commissions Withdrawal to Credit Balance - User ID: " . $clientid . " - Amount: " . $amount);
        }
    }

    redir("action=edit&id=" . $id);
    exit();
}


if ($sub == "delete") {
    check_token("RA.admin.default");
    delete_query("ra_partners", array("id" => $ide));
    logActivity("Affiliate " . $ide . " Deleted");
    redir();
}



if ($action == "") {
    $aInt->sortableTableInit("clientname", "ASC");
    $query = "FROM `ra_partners` INNER JOIN ra_user ON ra_user.id=ra_partners.clientid WHERE ra_partners.id!=''";

    if ($client) {
        $query .= " AND concat(firstname,' ',lastname) LIKE '%" . db_escape_string($client) . "%'";
    }


    if ($visitors) {
        $visitorstype = ($visitorstype == "greater" ? ">" : "<");
        $query .= " AND visitors " . $visitorstype . " '" . db_escape_string($visitors) . "'";
    }


    if ($balance) {
        $balancetype = ($balancetype == "greater" ? ">" : "<");
        $query .= " AND balance " . $balancetype . " '" . db_escape_string($balance) . "'";
    }


    if ($withdrawn) {
        $withdrawntype = ($withdrawntype == "greater" ? ">" : "<");
        $query .= " AND withdrawn " . $withdrawntype . " '" . db_escape_string($withdrawn) . "'";
    }

    $result = full_query_i("SELECT COUNT(ra_partners.id) " . $query);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    $aInt->deleteJSConfirm("doDelete", "affiliates", "deletesure", "affiliates.php?sub=delete&ide=");


    if ((((($orderby == "id" || $orderby == "date") || $orderby == "clientname") || $orderby == "visitors") || $orderby == "balance") || $orderby == "withdrawn") {
        
    } else {
        $orderby = "clientname";
    }

    $query .= " ORDER BY ";
    $query .= ($orderby == "clientname" ? "ra_user.firstname " . $order . ",ra_user.lastname" : $orderby);
    $query .= " " . $order;
    $query = "SELECT ra_partners.*,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid,ra_user.currency,(SELECT COUNT(*) FROM ra_partnersaccounts WHERE ra_partnersaccounts.affiliateid=ra_partners.id) AS signups " . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $date = $data['date'];
        $userid = $data['clientid'];
        $visitors = $data['visitors'];
        $balance = $data['balance'];
        $withdrawn = $data['withdrawn'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $companyname = $data['companyname'];
        $groupid = $data['groupid'];
        $currency = $data['currency'];
        $signups = $data['signups'];
        $currency = getCurrency("", $currency);
        $balance = formatCurrency($balance);
        $withdrawn = formatCurrency($withdrawn);
        $date = fromMySQLDate($date);
        $tabledata[] = array("<input type=\"checkbox\" name=\"selectedclients[]\" value=\"" . $id . "\" class=\"checkall\" />", "<a href=\"affiliates.php?action=edit&id=" . $id . "\">" . $id . "</a>", $date, $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid), $visitors, $signups, $balance, $withdrawn, "<a href=\"" . $PHP_SELF . "?action=edit&id=" . $id . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    $tableformurl = "sendmessage.php?type=affiliate&multiple=true";
    $tableformbuttons = "<input type=\"submit\" value=\"" . $aInt->lang("global", "sendmessage") . "\" class=\"button\">";
    $table = $aInt->sortableTable(array("checkall", array("id", $aInt->lang("fields", "id")), array("date", $aInt->lang("affiliates", "signupdate")), array("clientname", $aInt->lang("fields", "clientname")), array("visitors", $aInt->lang("affiliates", "visitorsref")), $aInt->lang("affiliates", "signups"), array("balance", $aInt->lang("fields", "balance")), array("withdrawn", $aInt->lang("affiliates", "withdrawn")), "", ""), $tabledata, $tableformurl, $tableformbuttons);
} else {
    if ($action == "edit") {
        if ($pay == "true") {
            $error = AffiliatePayment($affaccid, "");

            if ($error) {
                infoBox($aInt->lang("affiliates", "paymentfailed"), $error);
            } else {
                infoBox($aInt->lang("affiliates", "paymentsuccess"), $aInt->lang("affiliates", "paymentsuccessdetail"));
            }
        }

        echo $infobox;
        $result = select_query_i("ra_partners", "", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $id = $data['id'];

        if (!$id) {
            $aInt->gracefulExit("Invalid Affiliate ID. Please Try Again...");
        }

        $date = $data['date'];
        $clientid = $data['clientid'];
        $visitors = $data['visitors'];
        $balance = $data['balance'];
        $withdrawn = $data['withdrawn'];
        $paymenttype = $data['paytype'];
        $payamount = $data['payamount'];
        $onetime = $data['onetime'];
        $result = select_query_i("ra_user", "", array("id" => $clientid));
        $data = mysqli_fetch_array($result);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $result = select_query_i("ra_partnersaccounts", "COUNT(id)", array("affiliateid" => $id));
        $data = mysqli_fetch_array($result);
        $signups = $data[0];
        $result = select_query_i("ra_partnerspending", "COUNT(*),SUM(ra_partnerspending.amount)", array("affiliateid" => $id), "clearingdate", "DESC", "", "ra_partnersaccounts ON ra_partnersaccounts.id=ra_partnerspending.affaccid INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");
        $data = mysqli_fetch_array($result);
        $pendingcommissions = $data[0];
        $pendingcommissionsamount = $data[1];
        $currency = getCurrency($clientid);
        $date = fromMySQLDate($date);
        $pendingcommissionsamount = formatCurrency($pendingcommissionsamount);
        $conversionrate = round($signups / $visitors * 100, 2);
        $aInt->deleteJSConfirm("doAccDelete", "affiliates", "refdeletesure", "affiliates.php?action=deletereferral&id=" . $id . "&affaccid=");
        $aInt->deleteJSConfirm("doPendingCommissionDelete", "affiliates", "pendeletesure", "affiliates.php?action=deletecommission&id=" . $id . "&cid=");
        $aInt->deleteJSConfirm("doAffHistoryDelete", "affiliates", "pytdeletesure", "affiliates.php?action=deletehistory&id=" . $id . "&hid=");
        $aInt->deleteJSConfirm("doWithdrawHistoryDelete", "affiliates", "witdeletesure", "affiliates.php?action=deletewithdrawal&id=" . $id . "&wid=");
        $aInt->sortableTableInit("regdate", "DESC");
        $tabledata = "";
        $mysqli_errors = true;
        $numrows = get_query_val("ra_partnersaccounts", "COUNT(*)", array("ra_partnersaccounts.affiliateid" => $id), "", "", "", "tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");

        if ((((($orderby == "id" || $orderby == "regdate") || $orderby == "clientname") || $orderby == "name") || $orderby == "lastpaid") || $orderby == "servicestatus") {
            
        } else {
            $orderby = "regdate";
        }

        $result = select_query_i("ra_partnersaccounts", "ra_partnersaccounts.id,ra_partnersaccounts.lastpaid,ra_partnersaccounts.relid, concat(ra_user.firstname,' ',ra_user.lastname,'|||',ra_user.currency) as clientname,ra_catalog.name,tblcustomerservices.userid,tblcustomerservices.servicestatus,tblcustomerservices.domain,tblcustomerservices.amount,tblcustomerservices.firstpaymentamount,tblcustomerservices.regdate,tblcustomerservices.billingcycle", array("ra_partnersaccounts.affiliateid" => $id), "" . $orderby, "" . $order, $page * $limit . ("," . $limit), "tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");

        while ($data = mysqli_fetch_array($result)) {
            $affaccid = $data['id'];
            $lastpaid = $data['lastpaid'];
            $relid = $data['relid'];
            $clientname = $data['clientname'];
            $clientname = explode("|||", $clientname, 2);
            $currency = $clientname[1];
            $clientname = $clientname[0];
            $userid = $data['userid'];
            $firstpaymentamount = $data['firstpaymentamount'];
            $amount = $data['amount'];
            $domain = $data['domain'];
            $date = $data['regdate'];
            $service = $data['name'];
            $billingcycle = $data['billingcycle'];
            $status = $data['servicestatus'];
            $commission = calculateAffiliateCommission($id, $relid, $lastpaid);
            $currency = getCurrency("", $currency);
            $commission = formatCurrency($commission);

            if ($billingcycle == "Free" || $billingcycle == "Free Account") {
                $amountdesc = "Free";
            } else {
                if ($billingcycle == "One Time") {
                    $amountdesc = formatCurrency($firstpaymentamount) . " " . $billingcycle;
                } else {
                    $amountdesc = ($firstpaymentamount != $amount ? formatCurrency($firstpaymentamount) . " " . $aInt->lang("affiliates", "initiallythen") . " " : "");
                    $amountdesc .= formatCurrency($amount) . " " . $billingcycle;
                }
            }

            $date = fromMySQLDate($date);

            if (!$domain) {
                $domain = "";
            }


            if ($lastpaid == "0000-00-00") {
                $lastpaid = $aInt->lang("affiliates", "never");
            } else {
                $lastpaid = fromMySQLDate($lastpaid);
            }

            $tabledata[] = array($affaccid, $date, "<a href=\"clientssummary.php?userid=" . $userid . "\">" . $clientname . "</a>", "<a href=\"clientshosting.php?userid=" . $userid . "&id=" . $relid . "\">" . $service . "</a><br>" . $amountdesc, $commission, $lastpaid, $status, "<a href=\"affiliates.php?action=edit&id=" . $id . "&pay=true&affaccid=" . $affaccid . "\">" . $aInt->lang("affiliates", "manual") . "<br>" . $aInt->lang("affiliates", "payout") . "</a>", "<a href=\"#\" onClick=\"doAccDelete('" . $affaccid . "');return false\"><img src=\"images/delete.gif\" border=\"0\"></a>");
        }

        $table = $aInt->sortableTable(array(array("id", $aInt->lang("fields", "id")), array("regdate", $aInt->lang("affiliates", "signupdate")), array("clientname", $aInt->lang("fields", "clientname")), array("name", $aInt->lang("fields", "product")), $aInt->lang("affiliates", "commission"), array("lastpaid", $aInt->lang("affiliates", "lastpaid")), array("servicestatus", $aInt->lang("affiliates", "productstatus")), " ", ""), $tabledata, $tableformurl, $tableformbuttons);

        $currency = getCurrency($clientid);
        $aInt->sortableTableInit("nopagination");
        $tabledata = "";
        $result = select_query_i("ra_partnerspending", "ra_partnerspending.id,ra_partnerspending.affaccid,ra_partnerspending.amount,ra_partnerspending.clearingdate,ra_partnersaccounts.relid,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_catalog.name,tblcustomerservices.userid,tblcustomerservices.servicestatus,tblcustomerservices.billingcycle", array("affiliateid" => $id), "clearingdate", "ASC", "", "ra_partnersaccounts ON ra_partnersaccounts.id=ra_partnerspending.affaccid INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");

        while ($data = mysqli_fetch_array($result)) {
            $pendingid = $data['id'];
            $affaccid = $data['affaccid'];
            $amount = $data['amount'];
            $clearingdate = $data['clearingdate'];
            $relid = $data['relid'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];
            $userid = $data['userid'];
            $service = $data['name'];
            $billingcycle = $data['billingcycle'];
            $status = $data['servicestatus'];
            $clearingdate = fromMySQLDate($clearingdate);
            $amount = formatCurrency($amount);
            $tabledata[] = array($affaccid, $aInt->outputClientLink($userid, $firstname, $lastname, $companyname), "<a href=\"clientshosting.php?userid=" . $userid . "&id=" . $relid . "\">" . $service . "</a>", $status, $amount, $clearingdate, "<a href=\"#\" onClick=\"doPendingCommissionDelete('" . $pendingid . "');return false\"><img src=\"images/delete.gif\" border=\"0\"></a>");
        }

        $table = $aInt->sortableTable(array($aInt->lang("affiliates", "refid"), $aInt->lang("fields", "clientname"), $aInt->lang("fields", "product"), $aInt->lang("affiliates", "productstatus"), $aInt->lang("fields", "amount"), $aInt->lang("affiliates", "clearingdate"), ""), $tabledata);

        $aInt->sortableTableInit("nopagination");
        $tabledata = "";
        $result = select_query_i("ra_partnershistory", "ra_partnershistory.*,(SELECT CONCAT(ra_user.id,'|||',ra_user.firstname,'|||',ra_user.lastname,'|||',ra_user.companyname,'|||',ra_catalog.name,'|||',tblcustomerservices.id,'|||',tblcustomerservices.billingcycle,'|||',tblcustomerservices.servicestatus) FROM ra_partnersaccounts INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE ra_partnersaccounts.id=ra_partnershistory.affaccid) AS referraldata", array("affiliateid" => $id), "date", "DESC");

        while ($data = mysqli_fetch_array($result)) {
            $historyid = $data['id'];
            $date = $data['date'];
            $affaccid = $data['affaccid'];
            $description = $data['description'];
            $amount = $data['amount'];
            $referraldata = $data['referraldata'];
            $referraldata = explode("|||", $referraldata);
            $userid = $firstname = $lastname = $companyname = $service = $relid = $billingcycle = $status = "";

            if ($affaccid) {
                $userid = $referraldata[0];
                $firstname = $referraldata[1];
                $lastname = $referraldata[2];
                $companyname = $referraldata[3];
                $service = $referraldata[4];
                $relid = $referraldata[5];
                $billingcycle = $referraldata[6];
                $status = $referraldata[7];
            }

            $date = fromMySQLDate($date);
            $amount = formatCurrency($amount);

            if (!$description) {
                $description = "&nbsp;";
            }

            $tabledata[] = array($date, $affaccid, $aInt->outputClientLink($userid, $firstname, $lastname, $companyname), "<a href=\"clientshosting.php?userid=" . $userid . "&id=" . $relid . "\">" . $service . "</a>", $status, $description, $amount, "<a href=\"#\" onClick=\"doAffHistoryDelete('" . $historyid . "');return false\"><img src=\"images/delete.gif\" border=\"0\"></a>");
        }

        $table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("affiliates", "refid"), $aInt->lang("fields", "clientname"), $aInt->lang("fields", "product"), $aInt->lang("affiliates", "productstatus"), "Description", $aInt->lang("fields", "amount"), ""), $tabledata);

        $result = select_query_i("ra_partnersaccounts", "ra_partnersaccounts.*,(SELECT CONCAT(ra_user.firstname,'|||',ra_user.lastname,'|||',tblcustomerservices.userid,'|||',ra_catalog.name,'|||',tblcustomerservices.servicestatus,'|||',tblcustomerservices.domain,'|||',tblcustomerservices.amount,'|||',tblcustomerservices.regdate,'|||',tblcustomerservices.billingcycle) FROM tblcustomerservices INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE tblcustomerservices.id=ra_partnersaccounts.relid) AS referraldata", array("affiliateid" => $id));

        while ($data = mysqli_fetch_array($result)) {
            $affaccid = $data['id'];
            $lastpaid = $data['lastpaid'];
            $relid = $data['relid'];
            $referraldata = $data['referraldata'];
            $referraldata = explode("|||", $referraldata);
            $firstname = $referraldata[0];
            $lastname = $referraldata[1];
            $userid = $referraldata[2];
            $service = $referraldata[3];
            $status = $referraldata[4];
            $domain = $referraldata[5];
            $amount = $referraldata[6];
            $date = $referraldata[7];
            $billingcycle = $referraldata[8];

            if (!$domain) {
                $domain = "";
            }


            if ($lastpaid == "0000-00-00") {
                $lastpaid = $aInt->lang("affiliates", "never");
            } else {
                $lastpaid = fromMySQLDate($lastpaid);
            }

            //echo "<option value=\"" . $affaccid . "\">ID " . $affaccid . " - " . $firstname . " " . $lastname . " - " . $service . "</option>";
        }


        $aInt->sortableTableInit("nopagination");
        $tabledata = "";
        $result = select_query_i("ra_partnerswithdrawals", "", array("affiliateid" => $id), "id", "DESC");

        while ($data = mysqli_fetch_array($result)) {
            $historyid = $data['id'];
            $date = $data['date'];
            $amount = $data['amount'];
            $date = fromMySQLDate($date);
            $amount = formatCurrency($amount);
            $tabledata[] = array($date, $amount, "<a href=\"#\" onClick=\"doWithdrawHistoryDelete('" . $historyid . "');return false\"><img src=\"images/delete.gif\" border=\"0\"></a>");
        }

        $table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "amount"), ""), $tabledata);
    }
}


$aInt->assign("PHP_SELF", $PHP_SELF);
$aInt->assign("table", $table);
$aInt->template = "client/affiliates";
$aInt->jquerycode = $jquerycode;
$aInt->jquerycode .=$menuselect;
$aInt->jscode = $jscode;
$aInt->display();
?>
