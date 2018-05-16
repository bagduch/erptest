<?php

/** RA - Version 0.1 **/

/**
 * Load a gateway module
 *
 * Modules names must contain only valid characters:
 *  - alphanumeric
 *  - hyphen
 *  - underscore
 *
 * @param $paymentmethod string Basename of the file to include
 *
 * @return boolean True success, otherwise false
 */
function loadGatewayModule($paymentmethod) {
    $paymentmethod = RA_Gateways::makesafename($paymentmethod);

    if (!$paymentmethod) {
        return false;
    }

    $base_path = fetchGatewayModuleDirectory();
    $expected_file = $base_path . "/" . $paymentmethod . ".php";

    $state = false;

    if (file_exists($expected_file)) {
        ob_start();
        $state = (include_once($expected_file)) ? true : false;
        ob_end_clean();
    }

    return $state;
}

function fetchGatewayModuleDirectory() {
    return ROOTDIR . "/modules/gateways";
}

function paymentMethodsSelection($blankselection = "", $tabindex = false) {
    global $paymentmethod;

    if ($tabindex) {
        $tabindex = " tabindex=\"" . $tabindex . "\"";
    }

    $code = "<select class=\"form-control\" name=\"paymentmethod\"" . $tabindex . ">";


    $result = select_query_i("tblpaymentgateways", "gateway,value", array("setting" => "name"), "order", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $dbcongateway = $data['gateway'];
        $dbconvalue = $data['value'];
        if ($dbcongateway == $blankselection) {
            $select = "selected";
        } else {
            $select = "";
        }

        $code .= "<option value=\"" . $dbcongateway . "\" " . $select . "";

        if ($paymentmethod == $dbcongateway) {
            $code .= " selected";
        }

        $code .= ">" . $dbconvalue . "</option>";
    }

    $code .= "</select>";
    return $code;
}

function checkActiveGateway() {
    if (count(getGatewaysArray())) {
        return true;
    }

    return false;
}

function getGatewaysArray() {
    global $gatewayarray;
    $result = select_query_i("tblpaymentgateways", "gateway,value", array("setting" => "name"), "order", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $gatewayarray[$data['gateway']] = $data['value'];
    }

    return $gatewayarray;
}

function getGatewayName($modulename) {
    return get_query_val("tblpaymentgateways", "value", array("gateway" => $modulename, "setting" => "name"));
}

function showPaymentGatewaysList($disabledgateways = "") {
    global $paymentmethod;

    if (!$disabledgateways) {
        $disabledgateways = array();
    }

    $result = select_query_i("tblpaymentgateways", "gateway,value", array("setting" => "name"), "order", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $spgwgateway = $data['gateway'];
        $spgwvalue = $data['value'];
        $result2 = select_query_i("tblpaymentgateways", "value", array("setting" => "type", "gateway" => $spgwgateway));
        $data2 = mysqli_fetch_array($result2);
        $gatewaytype = $data2[0];
        $result2 = select_query_i("tblpaymentgateways", "value", array("setting" => "visible", "gateway" => $spgwgateway));
        $data2 = mysqli_fetch_array($result2);

        if ($data2['value'] == "on" && !in_array($spgwgateway, $disabledgateways)) {
            $gateway[$spgwgateway] = array("sysname" => $spgwgateway, "name" => $spgwvalue, "type" => $gatewaytype);
        }
    }

    return $gateway;
}

function getVariables($gateway) {
    return getGatewayVariables($gateway);
}

function getGatewayVariables($gateway, $invoiceid = "", $amount = "0.00") {
    global $ra;
    global $CONFIG;
    global $_LANG;
    global $clientsdetails;


    $res = loadGatewayModule($gateway);

    if (!$res) {
        exit("Gateway Module '" . $gateway . "' is Missing or Invalid");
    }

    $gateway = RA_Gateways::makesafename($gateway);

    if (!function_exists($gateway . "_link")) {
        eval("function " . $gateway . "_link($params) { return '<form method=\"post\" action=\"" . $params['systemurl'] . "/creditcard.php\" name=\"paymentfrm\"><input type=\"hidden\" name=\"invoiceid\" value=\"" . $params['invoiceid'] . "\"><input type=\"submit\" value=\"" . $params['langpaynow'] . "\"></form>'; }");
    }

    $GATEWAY = array();
    $GATEWAY['paymentmethod'] = $gateway;
    $result = select_query_i("tblpaymentgateways", "", array("gateway" => $gateway));

    while ($data = mysqli_fetch_array($result)) {
        $gVgwsetting = $data['setting'];
        $gVgwvalue = $data['value'];
        $GATEWAY["" . $gVgwsetting] = "" . $gVgwvalue;
    }

    $GATEWAY['companyname'] = $CONFIG['CompanyName'];

    $GATEWAY['systemurl'] = $CONFIG['SystemURL'];

    $GATEWAY['returnurl'] = $GATEWAY['systemurl'];
    $GATEWAY['langpaynow'] = $_LANG['invoicespaynow'];

    if ($invoiceid) {
        $clientsdetails['fullstate'] = $clientsdetails['state'];

        $result = select_query_i("tblclients", "tblinvoices.invoicenum,tblclients.currency,tblcurrencies.code", array("tblinvoices.id" => $invoiceid), "", "", "", "tblinvoices ON tblinvoices.userid=tblclients.id INNER JOIN tblcurrencies ON tblcurrencies.id=tblclients.currency");
        $data = mysqli_fetch_array($result);
        $invoicenum = $data['invoicenum'];
        $invoice_currency_id = $data['currency'];
        $invoice_currency_code = $data['code'];

        if (!trim($invoicenum)) {
            $invoicenum = $invoiceid;
        }

        $GATEWAY['description'] = $CONFIG['CompanyName'] . " - " . $_LANG['invoicenumber'] . $invoicenum;
        $GATEWAY['invoiceid'] = $invoiceid;
        $GATEWAY['clientdetails'] = $clientsdetails;
        $GATEWAY['returnurl'] = $GATEWAY['systemurl'] . "/viewinvoice.php?id=" . $invoiceid;

        if ($GATEWAY['convertto']) {
            $result = select_query_i("tblcurrencies", "code", array("id" => $GATEWAY['convertto']));
            $data = mysqli_fetch_array($result);
            $converto_currency_code = $data['code'];
            $converto_amount = convertCurrency($amount, $invoice_currency_id, $GATEWAY['convertto']);
            $GATEWAY['amount'] = format_as_currency($converto_amount);
            $GATEWAY['currency'] = $converto_currency_code;
            $GATEWAY['basecurrencyamount'] = format_as_currency($amount);
            $GATEWAY['basecurrency'] = $invoice_currency_code;
        }


        if (!$GATEWAY['currency']) {
            $GATEWAY['amount'] = format_as_currency($amount);
            $GATEWAY['currency'] = $invoice_currency_code;
        }
    }

    return $GATEWAY;
}

function logTransaction($gateway, $data, $result) {
    global $params;

    $invoicedata = "";

    if ($params['invoiceid']) {
        $invoicedata .= ("Invoice ID => " . $params['invoiceid'] . "\r\n");
    }


    if ($params['clientdetails']['userid']) {
        $invoicedata .= ("User ID => " . $params['clientdetails']['userid'] . "\r\n");
    }


    if ($params['amount']) {
        $invoicedata .= ("Amount => " . $params['amount'] . "\r\n");
    }


    if (is_array($data)) {
        $logdata = "";
        foreach ($data as $k => $v) {
            $logdata .= ("" . $k . " => " . $v . "\r\n");
        }
    } else {
        $logdata = $data;
    }

    $array = array("date" => "now()", "gateway" => $gateway, "data" => $invoicedata . $logdata, "result" => $result);
    insert_query("tblgatewaylog", $array);
    run_hook("LogTransaction", $array);
}

function checkCbInvoiceID($invoiceid, $gateway = "Unknown") {
    $result = select_query_i("tblinvoices", "id", array("id" => (int) $invoiceid));
    $data = mysqli_fetch_array($result);
    $id = $data['id'];

    if (!$id) {
        logTransaction($gateway, $_REQUEST, "Invoice ID Not Found");
        exit();
    }

    return $id;
}

function checkCbTransID($transid) {
    $result = select_query_i("tblaccounts", "id", array("transid" => $transid));
    $num_rows = mysqli_num_rows($result);

    if ($num_rows) {
        exit();
    }
}

function callback3DSecureRedirect($invoiceid, $success = false) {
    global $CONFIG;

    $systemurl = $CONFIG['SystemURL'];

    if ($success) {
        $redirectPage = $systemurl . ("/viewinvoice.php?id=" . $invoiceid . "&paymentsuccess=true");
    } else {
        $redirectPage = $systemurl . ("/viewinvoice.php?id=" . $invoiceid . "&paymentfailed=true");
    }

    echo "<html>
<head>
<title>" . $CONFIG['CompanyName'] . "</title>
</head>
<body onload=\"document.frmResultPage.submit();\">
<form name=\"frmResultPage\" method=\"post\" action=\"" . $redirectPage . "\" target=\"_parent\">
<noscript>
    <br><br>
    <center>
    <p style=\"color:#cc0000;\"><b>Processing Your Transaction</b></p>
    <p>JavaScript is currently disabled or is not supported by your browser.</p>
    <p>Please click Submit to continue the processing of your transaction.</p>
    <input type=\"submit\" value=\"Submit\">
    </center>
</noscript>
</form>
</body>
</html>";
    exit();
}

function getRecurringBillingValues($invoiceid) {
    global $CONFIG;

    $firstcycleperiod = $firstcycleunits = "";
    $invoiceid = (int) $invoiceid;
    $result = select_query_i("tblinvoiceitems", "tblinvoiceitems.relid,tblinvoiceitems.taxed,tblcustomerservices.userid,tblcustomerservices.amount,tblcustomerservices.billingcycle,tblcustomerservices.packageid,tblcustomerservices.regdate,tblcustomerservices.nextduedate", array("invoiceid" => $invoiceid, "type" => "Hosting"), "tblinvoiceitems`.`id", "ASC", "", "tblcustomerservices ON tblcustomerservices.id=tblinvoiceitems.relid");
    $data = mysqli_fetch_array($result);
    $relid = $data['relid'];
    $taxed = $data['taxed'];
    $userid = $data['userid'];
    $recurringamount = $data['amount'];
    $billingcycle = $data['billingcycle'];
    $packageid = $data['packageid'];
    $regdate = $data['regdate'];
    $nextduedate = $data['nextduedate'];

    if ((!$relid || $billingcycle == "One Time") || $billingcycle == "Free Account") {
        return false;
    }

    $result = select_query_i("tblinvoices", "total,taxrate,taxrate2,paymentmethod,(SELECT SUM(amountin)-SUM(amountout) FROM tblaccounts WHERE invoiceid=tblinvoices.id) AS amountpaid", array("id" => $invoiceid));
    $data = mysqli_fetch_array($result);
    $total = $data['total'];
    $taxrate = $data['taxrate'];
    $taxrate2 = $data['taxrate2'];
    $paymentmethod = $data['paymentmethod'];
    $amountpaid = $data['amountpaid'];
    $firstpaymentamount = $total - $amountpaid;
    $recurringcycleperiod = getBillingCycleMonths($billingcycle);
    $recurringcycleunits = "Months";

    if (12 <= $recurringcycleperiod) {
        $recurringcycleperiod = $recurringcycleperiod / 12;
        $recurringcycleunits = "Years";
    }

    $recurringamount = 0;
    $query = "SELECT tblcustomerservices.amount,tblinvoiceitems.taxed FROM tblinvoiceitems INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblinvoiceitems.relid WHERE tblinvoiceitems.invoiceid='" . (int) $invoiceid . "' AND tblinvoiceitems.type='Hosting' AND tblcustomerservices.billingcycle='" . db_escape_string($billingcycle) . "'";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $prodamount = $data[0];
        $taxed = $data[1];

        if ($CONFIG['TaxType'] == "Exclusive" && $taxed) {
            if ($CONFIG['TaxL2Compound']) {
                $prodamount = $prodamount + $prodamount * ($taxrate / 100);
                $prodamount = $prodamount + $prodamount * ($taxrate2 / 100);
            } else {
                $prodamount = $prodamount + $prodamount * ($taxrate / 100) + $prodamount * ($taxrate2 / 100);
            }
        }

        $recurringamount += $prodamount;
    }

    $query = "SELECT tblserviceaddons.recurring,tblserviceaddons.tax FROM tblinvoiceitems INNER JOIN tblserviceaddons ON tblserviceaddons.id=tblinvoiceitems.relid WHERE tblinvoiceitems.invoiceid='" . (int) $invoiceid . "' AND tblinvoiceitems.type='Addon' AND tblserviceaddons.billingcycle='" . db_escape_string($billingcycle) . "'";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $addonamount = $data[0];
        $addontax = $data[1];

        if ($CONFIG['TaxType'] == "Exclusive" && $addontax) {
            if ($CONFIG['TaxL2Compound']) {
                $addonamount = $addonamount + $addonamount * ($taxrate / 100);
                $addonamount = $addonamount + $addonamount * ($taxrate2 / 100);
            } else {
                $addonamount = $addonamount + $addonamount * ($taxrate / 100) + $addonamount * ($taxrate2 / 100);
            }
        }

        $recurringamount += $addonamount;
    }


    if (in_array($billingcycle, array("Annually", "Biennially", "Triennially"))) {
        $cycleregperiods = array("Annually" => "1", "Biennially" => "2", "Triennially" => "3");
        $query = "SELECT SUM(tbldomains.recurringamount) FROM tblinvoiceitems INNER JOIN tbldomains ON tbldomains.id=tblinvoiceitems.relid WHERE tblinvoiceitems.invoiceid='" . (int) $invoiceid . "' AND tblinvoiceitems.type IN ('DomainRegister','DomainTransfer','Domain') AND tbldomains.registrationperiod='" . db_escape_string($cycleregperiods[$billingcycle]) . "'";
        $result = full_query_i($query);
        $data = mysqli_fetch_array($result);
        $domainamount = $data[0];

        if ($CONFIG['TaxType'] == "Exclusive" && $CONFIG['TaxDomains']) {
            if ($CONFIG['TaxL2Compound']) {
                $domainamount = $domainamount + $domainamount * ($taxrate / 100);
                $domainamount = $domainamount + $domainamount * ($taxrate2 / 100);
            } else {
                $domainamount = $domainamount + $domainamount * ($taxrate / 100) + $domainamount * ($taxrate2 / 100);
            }
        }

        $recurringamount += $domainamount;
    }

    $result = select_query_i("tblinvoices", "duedate", array("id" => $invoiceid));
    $data = mysqli_fetch_array($result);
    $invoiceduedate = $data['duedate'];
    $invoiceduedate = str_replace("-", "", $invoiceduedate);
    $overdue = ($invoiceduedate < date("Ymd") ? true : false);
    $result = select_query_i("tblservices", "proratabilling,proratadate,proratachargenextmonth", array("id" => $packageid));
    $data = mysqli_fetch_array($result);
    $proratabilling = $data['proratabilling'];
    $proratadate = $data['proratadate'];
    $proratachargenextmonth = $data['proratachargenextmonth'];

    if ($regdate == $nextduedate && $proratabilling) {
        $orderyear = substr($regdate, 0, 4);
        $ordermonth = substr($regdate, 5, 2);
        $orderday = substr($regdate, 8, 2);

        if (!function_exists("getProrataValues")) {
            require ROOTDIR . "/includes/invoicefunctions.php";
        }

        $proratavals = getProrataValues($billingcycle, 0, $proratadate, $proratachargenextmonth, $orderday, $ordermonth, $orderyear);
        $firstcycleperiod = $proratavals['days'];
        $firstcycleunits = "Days";
    }


    if (!$firstcycleperiod) {
        $firstcycleperiod = $recurringcycleperiod;
    }


    if (!$firstcycleunits) {
        $firstcycleunits = $recurringcycleunits;
    }

    $result = select_query_i("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "convertto"));
    $data = mysqli_fetch_array($result);
    $convertto = $data[0];

    if ($convertto) {
        $currency = getCurrency($userid);
        $firstpaymentamount = convertCurrency($firstpaymentamount, $currency['id'], $convertto);
        $recurringamount = convertCurrency($recurringamount, $currency['id'], $convertto);
    }

    $firstpaymentamount = format_as_currency($firstpaymentamount);
    $recurringamount = format_as_currency($recurringamount);
    $returndata = array();
    $returndata['primaryserviceid'] = $relid;

    if ($firstpaymentamount != $recurringamount) {
        $returndata['firstpaymentamount'] = $firstpaymentamount;
        $returndata['firstcycleperiod'] = $firstcycleperiod;
        $returndata['firstcycleunits'] = $firstcycleunits;
    }

    $returndata['recurringamount'] = $recurringamount;
    $returndata['recurringcycleperiod'] = $recurringcycleperiod;
    $returndata['recurringcycleunits'] = $recurringcycleunits;
    $returndata['overdue'] = $overdue;
    return $returndata;
}

?>
