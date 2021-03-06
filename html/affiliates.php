<?php

/** RA - Version 0.1 **/
define("CLIENTAREA", true);
require "init.php";
include "includes/affiliatefunctions.php";
include "includes/ticketfunctions.php";
$pagetitle = $_LANG['affiliatestitle'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"affiliates.php\">" . $_LANG['affiliatestitle'] . "</a>";
$pageicon = "images/affiliate_big.gif";
initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

if (isset($_SESSION['uid'])) {
    checkContactPermission("affiliates");
    $result = select_query_i("ra_partners", "", array("clientid" => $_SESSION['uid']));
    $data = mysqli_fetch_array($result);
    $id = $affiliateid = $data['id'];

    if (!$affiliateid) {
        if (isset($_REQUEST['activate'])) {
            check_token();
            affiliateActivate($_SESSION['uid']);
            redir();
        }

        $result = select_query_i("ra_user", "currency", array("id" => $_SESSION['uid']));
        $data = mysqli_fetch_array($result);
        $clientcurrency = $data['currency'];
        $bonusdeposit = convertCurrency($CONFIG['AffiliateBonusDeposit'], 1, $clientcurrency);
        $templatefile = "affiliatessignup";
        $smarty->assign("affiliatesystemenabled", $CONFIG['AffiliateEnabled']);
        $smarty->assign("bonusdeposit", formatCurrency($bonusdeposit));
        $smarty->assign("payoutpercentage", $CONFIG['AffiliateEarningPercent'] . "%");
    } else {
        $templatefile = "affiliates";
        $currency = getCurrency($_SESSION['uid']);
        $date = $data['date'];
        $date = fromMySQLDate($date);
        $visitors = $data['visitors'];
        $balance = $data['balance'];
        $withdrawn = $data['withdrawn'];
        $result = select_query_i("ra_partnersaccounts", "COUNT(id)", array("affiliateid" => $id));
        $data = mysqli_fetch_array($result);
        $signups = $data[0];
        $result = select_query_i("ra_partnerspending", "SUM(ra_partnerspending.amount)", array("affiliateid" => $id), "clearingdate", "DESC", "", "ra_partnersaccounts ON ra_partnersaccounts.id=ra_partnerspending.affaccid INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");
        $data = mysqli_fetch_array($result);
        $pendingcommissions = $data[0];
        $conversionrate = round($signups / $visitors * 100, 2);
        $smarty->assign("affiliateid", $id);
        $smarty->assign("referrallink", $CONFIG['SystemURL'] . "/aff.php?aff=" . $id);
        $smarty->assign("date", $date);
        $smarty->assign("visitors", $visitors);
        $smarty->assign("signups", $signups);
        $smarty->assign("conversionrate", $conversionrate);
        $smarty->assign("pendingcommissions", formatCurrency($pendingcommissions));
        $smarty->assign("balance", formatCurrency($balance));
        $smarty->assign("withdrawn", formatCurrency($withdrawn));
        $affpayoutmin = $CONFIG['AffiliatePayout'];
        $affpayoutmin = convertCurrency($affpayoutmin, 1, $currency['id']);

        if ($affpayoutmin <= $balance) {
            $smarty->assign("withdrawlevel", "true");

            if ($action == "withdrawrequest") {
                $deptid = "";

                if ($CONFIG['AffiliateDepartment']) {
                    $deptid = get_query_val("ra_ticket_teams", "id", array("id" => $CONFIG['AffiliateDepartment']));
                }


                if (!$deptid) {
                    $deptid = get_query_val("ra_ticket_teams", "id", array("hidden" => ""), "order", "ASC");
                }

                $message = "Affiliate Account Withdrawal Request.  Details below:

Client ID: " . $_SESSION['uid'] . ("
Affiliate ID: " . $id . "
Balance: " . $balance);
                $ticketdetails = openNewTicket($_SESSION['uid'], $_SESSION['cid'], $deptid, "Affiliate Withdrawal Request", $message, "Medium");
                redir("withdraw=1");
            }
        }


        if ($ra->get_req_var("withdraw")) {
            $smarty->assign("withdrawrequestsent", "true");
        }

        $content .= "
<p><b>" . $_LANG['affiliatesreferals'] . "</b></p>
<table align=\"center\" id=\"affiliates\" cellspacing=\"1\">
<tr><td id=\"affiliatesheading\">" . $_LANG['affiliatessignupdate'] . "</td><td id=\"affiliatesheading\">" . $_LANG['affiliateshostingpackage'] . "</td><td id=\"affiliatesheading\">" . $_LANG['affiliatesamount'] . "</td><td id=\"affiliatesheading\">" . $_LANG['affiliatescommision'] . "</td><td id=\"affiliatesheading\">" . $_LANG['affiliatesstatus'] . "</td></tr>
";
        $numitems = get_query_val("ra_partnersaccounts", "COUNT(*)", array("affiliateid" => $affiliateid), "", "", "", "tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");
        list($orderby, $sort, $limit) = clientAreaTableInit("affiliates", "regdate", "DESC", $numitems);
        $smartyvalues['orderby'] = $orderby;
        $smartyvalues['sort'] = strtolower($sort);

        if ($orderby == "product") {
            $orderby = "ra_catalog`.`name";
        } else {
            if ($orderby == "amount") {
                $orderby = "tblhosting`.`amount";
            } else {
                if ($orderby == "billingcycle") {
                    $orderby = "tblhosting`.`billingcycle";
                } else {
                    if ($orderby == "status") {
                        $orderby = "tblhosting`.`servicestatus";
                    } else {
                        $orderby = "tblhosting`.`regdate";
                    }
                }
            }
        }

        $referrals = array();
        $result = select_query_i("ra_partnersaccounts", "ra_partnersaccounts.*,ra_catalog.name,tblcustomerservices.userid,tblcustomerservices.servicestatus,tblcustomerservices.amount,tblcustomerservices.firstpaymentamount,tblcustomerservices.regdate,tblcustomerservices.billingcycle", array("affiliateid" => $affiliateid), $orderby, $sort, $limit, "tblcustomerservices ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");

        while ($data = mysqli_fetch_array($result)) {
            $affaccid = $data['id'];
            $lastpaid = $data['lastpaid'];
            $relid = $data['relid'];
            $userid = $data['userid'];
            $firstpaymentamount = $data['firstpaymentamount'];
            $amount = $data['amount'];
            $date = $data['regdate'];
            $service = $data['name'];
            $billingcycle = $data['billingcycle'];
            $status = $data['servicestatus'];
            $date = fromMySQLDate($date);
            $currency = getCurrency($userid);
            $commission = calculateAffiliateCommission($affiliateid, $relid, $lastpaid);
            $commission = formatCurrency($commission);

            if (!$domain) {
                $domain = "";
            }

            $lastpaid = ($lastpaid == "0000-00-00" ? "Never" : fromMySQLDate($lastpaid));
            $status = $_LANG["clientarea" . strtolower($status)];
            $billingcyclelang = strtolower($billingcycle);
            $billingcyclelang = str_replace(" ", "", $billingcyclelang);
            $billingcyclelang = str_replace("-", "", $billingcyclelang);
            $billingcyclelang = $_LANG["orderpaymentterm" . $billingcyclelang];

            if ($billingcycle == "Free" || $billingcycle == "Free Account") {
                $amountdesc = $billingcyclelang;
            } else {
                if ($billingcycle == "One Time") {
                    $amountdesc = formatCurrency($firstpaymentamount) . " " . $billingcyclelang;
                } else {
                    $amountdesc = ($firstpaymentamount != $amount ? formatCurrency($firstpaymentamount) . " " . $_LANG['affiliatesinitialthen'] . " " : "");
                    $amountdesc .= formatCurrency($amount) . " " . $billingcyclelang;
                }
            }

            $referrals[] = array("id" => $affaccid, "date" => $date, "service" => $service, "package" => $service, "userid" => $userid, "amount" => $amount, "billingcycle" => $billingcyclelang, "amountdesc" => $amountdesc, "commission" => $commission, "lastpaid" => $lastpaid, "status" => $status);
        }

        $smarty->assign("referrals", $referrals);
        $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
        $commissionhistory = array();
        $result = select_query_i("ra_partnershistory", "", array("affiliateid" => $affiliateid), "id", "DESC", "0,10");

        while ($data = mysqli_fetch_array($result)) {
            $historyid = $data['id'];
            $date = $data['date'];
            $affaccid = $data['affaccid'];
            $amount = $data['amount'];
            $date = fromMySQLDate($date);
            $commissionhistory[] = array("date" => $date, "referralid" => $affaccid, "amount" => $CONFIG['CurrencySymbol'] . $amount);
        }

        $smarty->assign("commissionhistory", $commissionhistory);
        $withdrawalshistory = array();
        $result = select_query_i("ra_partnerswithdrawals", "", array("affiliateid" => $id), "id", "DESC");

        while ($data = mysqli_fetch_array($result)) {
            $historyid = $data['id'];
            $date = $data['date'];
            $amount = $data['amount'];
            $date = fromMySQLDate($date);
            $withdrawalshistory[] = array("date" => $date, "amount" => $CONFIG['CurrencySymbol'] . $amount);
        }

        $smarty->assign("withdrawalshistory", $withdrawalshistory);
        $affiliatelinkscode = html_entity_decode($CONFIG['AffiliateLinks']);
        $affiliatelinkscode = str_replace("[AffiliateLinkCode]", $CONFIG['SystemURL'] . "/aff.php?aff=" . $id, $affiliatelinkscode);
        $affiliatelinkscode = str_replace("<(", "&lt;", $affiliatelinkscode);
        $affiliatelinkscode = str_replace(")>", "&gt;", $affiliatelinkscode);
        $smarty->assign("affiliatelinkscode", $affiliatelinkscode);
    }
} else {
    $goto = "affiliates";
    include "login.php";
}


if ($CONFIG['AffiliateEnabled'] != "on") {
    $smarty->assign("inactive", "true");
}

outputClientArea($templatefile);
?>
