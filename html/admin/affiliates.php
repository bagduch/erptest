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
    update_query("tblaffiliates", array("paytype" => $paymenttype, "payamount" => $payamount, "onetime" => $onetime, "visitors" => $visitors, "balance" => $balance, "withdrawn" => $withdrawn), array("id" => $id));
    logActivity("Affiliate ID " . $id . " Details Updated");
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletecommission") {
    check_token("RA.admin.default");
    delete_query("tblaffiliatespending", array("id" => $cid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletehistory") {
    check_token("RA.admin.default");
    delete_query("tblaffiliateshistory", array("id" => $hid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletereferral") {
    check_token("RA.admin.default");
    delete_query("tblaffiliatesaccounts", array("id" => $affaccid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "deletewithdrawal") {
    check_token("RA.admin.default");
    delete_query("tblaffiliateswithdrawals", array("id" => $wid));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "addcomm") {
    check_token("RA.admin.default");
    $amount = format_as_currency($amount);
    insert_query("tblaffiliateshistory", array("affiliateid" => $id, "date" => toMySQLDate($date), "affaccid" => $refid, "description" => $description, "amount" => $amount));
    update_query("tblaffiliates", array("balance" => "+=" . $amount), array("id" => (int) $id));
    redir("action=edit&id=" . $id);
    exit();
}


if ($action == "withdraw") {
    check_token("RA.admin.default");
    insert_query("tblaffiliateswithdrawals", array("affiliateid" => $id, "date" => "now()", "amount" => $amount));
    update_query("tblaffiliates", array("balance" => "-=" . $amount, "withdrawn" => "+=" . $amount), array("id" => (int) $id));

    if ($payouttype == "1") {
        $result = select_query_i("tblaffiliates", "", array("id" => (int) $id));
        $data = mysqli_fetch_array($result);
        $id = (int) $data['id'];
        $clientid = (int) $data['clientid'];
        addTransaction($clientid, "", "Affiliate Commissions Withdrawal Payout", "0", "0", $amount, $paymentmethod, $transid);
    } else {
        if ($payouttype == "2") {
            $result = select_query_i("tblaffiliates", "", array("id" => (int) $id));
            $data = mysqli_fetch_array($result);
            $id = (int) $data['id'];
            $clientid = (int) $data['clientid'];
            insert_query("tblcredit", array("clientid" => $clientid, "date" => "now()", "description" => "Affiliate Commissions Withdrawal", "amount" => $amount));
            update_query("tblclients", array("credit" => "+=" . $amount), array("id" => $clientid));
            logActivity("Processed Affiliate Commissions Withdrawal to Credit Balance - User ID: " . $clientid . " - Amount: " . $amount);
        }
    }

    redir("action=edit&id=" . $id);
    exit();
}


if ($sub == "delete") {
    check_token("RA.admin.default");
    delete_query("tblaffiliates", array("id" => $ide));
    logActivity("Affiliate " . $ide . " Deleted");
    redir();
}



if ($action == "") {
    $aInt->sortableTableInit("clientname", "ASC");
    $query = "FROM `tblaffiliates` INNER JOIN tblclients ON tblclients.id=tblaffiliates.clientid WHERE tblaffiliates.id!=''";

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

    $result = full_query_i("SELECT COUNT(tblaffiliates.id) " . $query);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    $aInt->deleteJSConfirm("doDelete", "affiliates", "deletesure", "affiliates.php?sub=delete&ide=");


    if ((((($orderby == "id" || $orderby == "date") || $orderby == "clientname") || $orderby == "visitors") || $orderby == "balance") || $orderby == "withdrawn") {
        
    } else {
        $orderby = "clientname";
    }

    $query .= " ORDER BY ";
    $query .= ($orderby == "clientname" ? "tblclients.firstname " . $order . ",tblclients.lastname" : $orderby);
    $query .= " " . $order;
    $query = "SELECT tblaffiliates.*,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblclients.groupid,tblclients.currency,(SELECT COUNT(*) FROM tblaffiliatesaccounts WHERE tblaffiliatesaccounts.affiliateid=tblaffiliates.id) AS signups " . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
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
        $result = select_query_i("tblaffiliates", "", array("id" => $id));
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
        $result = select_query_i("tblclients", "", array("id" => $clientid));
        $data = mysqli_fetch_array($result);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $result = select_query_i("tblaffiliatesaccounts", "COUNT(id)", array("affiliateid" => $id));
        $data = mysqli_fetch_array($result);
        $signups = $data[0];
        $result = select_query_i("tblaffiliatespending", "COUNT(*),SUM(tblaffiliatespending.amount)", array("affiliateid" => $id), "clearingdate", "DESC", "", "tblaffiliatesaccounts ON tblaffiliatesaccounts.id=tblaffiliatespending.affaccid INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");
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
        $numrows = get_query_val("tblaffiliatesaccounts", "COUNT(*)", array("tblaffiliatesaccounts.affiliateid" => $id), "", "", "", "tblcustomerservices ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");

        if ((((($orderby == "id" || $orderby == "regdate") || $orderby == "clientname") || $orderby == "name") || $orderby == "lastpaid") || $orderby == "servicestatus") {
            
        } else {
            $orderby = "regdate";
        }

        $result = select_query_i("tblaffiliatesaccounts", "tblaffiliatesaccounts.id,tblaffiliatesaccounts.lastpaid,tblaffiliatesaccounts.relid, concat(tblclients.firstname,' ',tblclients.lastname,'|||',tblclients.currency) as clientname,tblservices.name,tblcustomerservices.userid,tblcustomerservices.servicestatus,tblcustomerservices.domain,tblcustomerservices.amount,tblcustomerservices.firstpaymentamount,tblcustomerservices.regdate,tblcustomerservices.billingcycle", array("tblaffiliatesaccounts.affiliateid" => $id), "" . $orderby, "" . $order, $page * $limit . ("," . $limit), "tblcustomerservices ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");

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
        $result = select_query_i("tblaffiliatespending", "tblaffiliatespending.id,tblaffiliatespending.affaccid,tblaffiliatespending.amount,tblaffiliatespending.clearingdate,tblaffiliatesaccounts.relid,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblservices.name,tblcustomerservices.userid,tblcustomerservices.servicestatus,tblcustomerservices.billingcycle", array("affiliateid" => $id), "clearingdate", "ASC", "", "tblaffiliatesaccounts ON tblaffiliatesaccounts.id=tblaffiliatespending.affaccid INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");

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
        $result = select_query_i("tblaffiliateshistory", "tblaffiliateshistory.*,(SELECT CONCAT(tblclients.id,'|||',tblclients.firstname,'|||',tblclients.lastname,'|||',tblclients.companyname,'|||',tblservices.name,'|||',tblcustomerservices.id,'|||',tblcustomerservices.billingcycle,'|||',tblcustomerservices.servicestatus) FROM tblaffiliatesaccounts INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid WHERE tblaffiliatesaccounts.id=tblaffiliateshistory.affaccid) AS referraldata", array("affiliateid" => $id), "date", "DESC");

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

        $result = select_query_i("tblaffiliatesaccounts", "tblaffiliatesaccounts.*,(SELECT CONCAT(tblclients.firstname,'|||',tblclients.lastname,'|||',tblcustomerservices.userid,'|||',tblservices.name,'|||',tblcustomerservices.servicestatus,'|||',tblcustomerservices.domain,'|||',tblcustomerservices.amount,'|||',tblcustomerservices.regdate,'|||',tblcustomerservices.billingcycle) FROM tblcustomerservices INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid WHERE tblcustomerservices.id=tblaffiliatesaccounts.relid) AS referraldata", array("affiliateid" => $id));

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
        $result = select_query_i("tblaffiliateswithdrawals", "", array("affiliateid" => $id), "id", "DESC");

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
