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
function paystation_config() {
    global $CONFIG;

    $configarray = array(
        "FriendlyName" => array(
            "Type" => "System",
            "Value" => "Paystation"),
        "UsageNotes" => array(
            "Type" => "System",
            "Value" => "You must enable IPN inside your Paystation account and set the URL to " . $CONFIG['SystemURL']),
        "paystationid" => array(
            "FriendlyName" => "Paystation ID",
            "Type" => "text",
            "Size" => "40"
        ),
        "gatewayid" => array(
            "FriendlyName" => "Gateway ID",
            "Type" => "text",
            "Size" => "40"
        ),
        "hashkey" => array(
            "FriendlyName" => "Hash Key",
            "Type" => "text",
            "Size" => "40"
        ),
        "url" => array(
            "FriendlyName" => "WSDL URL",
            "Type" => "text",
            "Size" => "40"
        ),
        "testmode" => array(
            "FriendlyName" => "Test Mode:",
            "Type" => "yesno",
            "Description" => "test mode"
        ),
    );
    return $configarray;
}

function paystation_link($params) {
    global $CONFIG;

    $invoiceid = $params['invoiceid'];
    $paystationemails = $params['email'];
    $paystationemails = explode(",", $paystationemails);
    $paystationemail = trim($paystationemails[0]);
    $recurrings = getRecurringBillingValues($invoiceid);
    $primaryserviceid = $recurrings['primaryserviceid'];
    $firstpaymentamount = $recurrings['firstpaymentamount'];
    $firstcycleperiod = $recurrings['firstcycleperiod'];
    $firstcycleunits = strtoupper(substr($recurrings['firstcycleunits'], 0, 1));
    $recurringamount = $recurrings['recurringamount'];
    $recurringcycleperiod = $recurrings['recurringcycleperiod'];
    $recurringcycleunits = strtoupper(substr($recurrings['recurringcycleunits'], 0, 1));

    if ($params['clientdetails']['country'] == "US" || $params['clientdetails']['country'] == "CA") {
        $phonenumber = preg_replace("/[^0-9]/", "", $params['clientdetails']['phonenumber']);
        $phone1 = substr($phonenumber, 0, 3);
        $phone2 = substr($phonenumber, 3, 3);
        $phone3 = substr($phonenumber, 6);
    } else {
        $phone1 = $params['clientdetails']['phonecc'];
        $phone2 = $params['clientdetails']['phonenumber'];
    }

    $subnotpossible = false;

    if (!$recurrings) {
        $subnotpossible = true;
    }


    if ($recurrings['overdue']) {
        $subnotpossible = true;
    }


    if ($params['forceonetime']) {
        $subnotpossible = true;
    }


    if ($recurringamount <= 0) {
        $subnotpossible = true;
    }


    if (90 < $firstcycleperiod && $firstcycleunits == "D") {
        $subnotpossible = true;
    }


    if (24 < $firstcycleperiod && $firstcycleunits == "M") {
        $subnotpossible = true;
    }


    if (5 < $firstcycleperiod && $firstcycleunits == "Y") {
        $subnotpossible = true;
    }

    $code = "<table><tr>";

    if (!$subnotpossible) {
        $code .= "<td><form action=\"https://www.paystation.com/cgi-bin/webscr\" method=\"post\" name=\"paymentfrm\">
<input type=\"hidden\" name=\"cmd\" value=\"_xclick-subscriptions\">
<input type=\"hidden\" name=\"business\" value=\"" . $paystationemail . "\">
<input type=\"hidden\" name=\"item_name\" value=\"" . $params['description'] . "\">
<input type=\"hidden\" name=\"no_shipping\" value=\"" . ($params['requireshipping'] ? "2" : "1") . "\">
<input type=\"hidden\" name=\"address_override\" value=\"" . ($params['overrideaddress'] ? "1" : "0") . "\">
<input type=\"hidden\" name=\"first_name\" value=\"" . $params['clientdetails']['firstname'] . "\">
<input type=\"hidden\" name=\"last_name\" value=\"" . $params['clientdetails']['lastname'] . "\">
<input type=\"hidden\" name=\"address1\" value=\"" . $params['clientdetails']['address1'] . "\">
<input type=\"hidden\" name=\"city\" value=\"" . $params['clientdetails']['city'] . "\">
<input type=\"hidden\" name=\"state\" value=\"" . $params['clientdetails']['state'] . "\">
<input type=\"hidden\" name=\"zip\" value=\"" . $params['clientdetails']['postcode'] . "\">
<input type=\"hidden\" name=\"country\" value=\"" . $params['clientdetails']['country'] . "\">
<input type=\"hidden\" name=\"night_phone_a\" value=\"" . $phone1 . "\">
<input type=\"hidden\" name=\"night_phone_b\" value=\"" . $phone2 . "\">";

        if ($phone3) {
            $code .= "<input type=\"hidden\" name=\"night_phone_c\" value=\"" . $phone3 . "\">";
        }

        $code .= "<input type=\"hidden\" name=\"no_note\" value=\"1\">
<input type=\"hidden\" name=\"currency_code\" value=\"" . $params['currency'] . "\">
<input type=\"hidden\" name=\"bn\" value=\"RA_ST\">";

        if ($firstpaymentamount) {
            $code .= "
<input type=\"hidden\" name=\"a1\" value=\"" . $firstpaymentamount . "\">
<input type=\"hidden\" name=\"p1\" value=\"" . $firstcycleperiod . "\">
<input type=\"hidden\" name=\"t1\" value=\"" . $firstcycleunits . "\">";
        }

        $code .= "
<input type=\"hidden\" name=\"a3\" value=\"" . $recurringamount . "\">
<input type=\"hidden\" name=\"p3\" value=\"" . $recurringcycleperiod . "\">
<input type=\"hidden\" name=\"t3\" value=\"" . $recurringcycleunits . "\">
<input type=\"hidden\" name=\"src\" value=\"1\">
<input type=\"hidden\" name=\"sra\" value=\"1\">
<input type=\"hidden\" name=\"charset\" value=\"" . $CONFIG['Charset'] . "\">
<input type=\"hidden\" name=\"custom\" value=\"" . $primaryserviceid . "\">
<input type=\"hidden\" name=\"return\" value=\"" . $params['returnurl'] . "&paymentsuccess=true\">
<input type=\"hidden\" name=\"cancel_return\" value=\"" . $params['returnurl'] . "&paymentfailed=true\">
<input type=\"hidden\" name=\"notify_url\" value=\"" . $params['systemurl'] . "/modules/gateways/callback/paystation.php\">
<input type=\"hidden\" name=\"rm\" value=\"2\">";

        if (!$firstpaymentamount && $params['modifysubscriptions']) {
            $code .= "
<input type=\"hidden\" name=\"modify\" value=\"1\">";
        }

        $code .= "
<input type=\"image\" src=\"https://www.paystation.com/en_US/i/btn/x-click-but20.gif\" border=\"0\" name=\"submit\" alt=\"Subscribe with Paystation for Automatic Payments\">
</form></td>";
    }


    if ((!$subnotpossible && $params['forcesubscriptions'] ) && !$params['forceonetime']) {
        
    } else {
        $code .= "<td><form action=\"https://www.paystation.com/cgi-bin/webscr\" method=\"post\">
<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
<input type=\"hidden\" name=\"business\" value=\"" . $paystationemail . "\">";

        if ($params['style']) {
            $code .= "<input type=\"hidden\" name=\"page_style\" value=\"" . $params['style'] . "\">";
        }

        $code .= "<input type=\"hidden\" name=\"item_name\" value=\"" . $params['description'] . "\">
<input type=\"hidden\" name=\"amount\" value=\"" . $params['amount'] . "\">
<input type=\"hidden\" name=\"tax\" value=\"0.00\">
<input type=\"hidden\" name=\"no_note\" value=\"1\">
<input type=\"hidden\" name=\"no_shipping\" value=\"" . ($params['requireshipping'] ? "2" : "1") . "\">
<input type=\"hidden\" name=\"address_override\" value=\"" . ($params['overrideaddress'] ? "1" : "0") . "\">
<input type=\"hidden\" name=\"first_name\" value=\"" . $params['clientdetails']['firstname'] . "\">
<input type=\"hidden\" name=\"last_name\" value=\"" . $params['clientdetails']['lastname'] . "\">
<input type=\"hidden\" name=\"address1\" value=\"" . $params['clientdetails']['address1'] . "\">
<input type=\"hidden\" name=\"city\" value=\"" . $params['clientdetails']['city'] . "\">
<input type=\"hidden\" name=\"state\" value=\"" . $params['clientdetails']['state'] . "\">
<input type=\"hidden\" name=\"zip\" value=\"" . $params['clientdetails']['postcode'] . "\">
<input type=\"hidden\" name=\"country\" value=\"" . $params['clientdetails']['country'] . "\">
<input type=\"hidden\" name=\"night_phone_a\" value=\"" . $phone1 . "\">
<input type=\"hidden\" name=\"night_phone_b\" value=\"" . $phone2 . "\">";

        if ($phone3) {
            $code .= "<input type=\"hidden\" name=\"night_phone_c\" value=\"" . $phone3 . "\">";
        }

        $code .= "<input type=\"hidden\" name=\"charset\" value=\"" . $CONFIG['Charset'] . "\">
<input type=\"hidden\" name=\"currency_code\" value=\"" . $params['currency'] . "\">
<input type=\"hidden\" name=\"custom\" value=\"" . $params['invoiceid'] . "\">
<input type=\"hidden\" name=\"return\" value=\"" . $params['returnurl'] . "&paymentsuccess=true\">
<input type=\"hidden\" name=\"cancel_return\" value=\"" . $params['returnurl'] . "&paymentfailed=true\">
<input type=\"hidden\" name=\"notify_url\" value=\"" . $params['systemurl'] . "/modules/gateways/callback/paystation.php\">
<input type=\"hidden\" name=\"bn\" value=\"RA_ST\">
<input type=\"hidden\" name=\"rm\" value=\"2\">
<input type=\"image\" src=\"https://www.paystation.com/en_US/i/btn/x-click-but03.gif\" border=\"0\" name=\"submit\" alt=\"Make a one time payment with Paystation\">
</form></td>";
    }

    $code .= "</tr></table>";
    return $code;
}

function paystation_refund($params) {
    if ($params['sandbox']) {
        $url = "https://api-3t.sandbox.paystation.com/nvp";
    } else {
        $url = "https://api-3t.paystation.com/nvp";
    }

    $postfields = array();
    $postfields['VERSION'] = "3.0";
    $postfields['METHOD'] = "RefundTransaction";
    $postfields['BUTTONSOURCE'] = "RA_WPP_DP";
    $postfields['USER'] = $params['apiusername'];
    $postfields['PWD'] = $params['apipassword'];
    $postfields['SIGNATURE'] = $params['apisignature'];
    $postfields['TRANSACTIONID'] = $params['transid'];
    $postfields['REFUNDTYPE'] = "Partial";
    $postfields['AMT'] = $params['amount'];
    $postfields['CURRENCYCODE'] = $params['currency'];
    $result = curlCall($url, $postfields);
    $resultsarray2 = explode("&", $result);
    foreach ($resultsarray2 as $line) {
        $line = explode("=", $line);
        $resultsarray[$line[0]] = urldecode($line[1]);
    }


    if (strtoupper($resultsarray['ACK']) == "SUCCESS") {
        return array("status" => "success", "rawdata" => $resultsarray, "transid" => $resultsarray['REFUNDTRANSACTIONID'], "fees" => $resultsarray['FEEREFUNDAMT']);
    }

    return array("status" => "error", "rawdata" => $resultsarray);
}

function makePaystationMerchantSession($min = 8, $max = 8, $last_four) {

    # seed the random number generator - straight from PHP manual
    $seed = (double) microtime() * getrandmax() * $last_four;
    srand($seed);

    # make a string of $max characters with ASCII values of 40-122
    $p = 0;
    $pass = "";
    while ($p < $max):
        $r = 123 - (rand() % 75);
        $pass.=chr($r);
        $p++;
    endwhile;

    # get rid of all non-alphanumeric characters
    $pass = preg_replace("/[^a-zA-NP-Z1-9+]/", "", $pass);

    # if string is too short, remake it
    if (strlen($pass) < $min):
        $pass = makePaystationMerchantSession($min, $max, $last_four);
    endif;

    return $pass;
}

function paystation_api_call($url, $options) {

    $defined_vars = get_defined_vars();
    //use curl to get reponse	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $options);
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    if (curl_error($ch)) {
        echo curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

if (!defined("RA")) {
    exit("This file cannot be accessed directly");
}
?>