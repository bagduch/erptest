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
function getClientsDetails($userid = "", $contactid = "") {
    global $encryption_key;

    require ROOTDIR . "/includes/countries.php";
    require ROOTDIR . "/includes/countriescallingcodes.php";

    if (!$userid) {
        $userid = $_SESSION['uid'];
    }

    $result = select_query_i("tblclients", "", array("id" => $userid));
    $data = mysqli_fetch_array($result);

    if ($contactid == "billing") {
        $contactid = $data['billingcid'];
    }


    if ($contactid) {
        $result = select_query_i("tblcontacts", "", array("userid" => $userid, "id" => $contactid));

        if (mysqli_num_rows($result)) {
            $data = array_merge($data, mysqli_fetch_array($result));
            $data['id'] = $userid;
        } else {
            update_query("tblclients", array("billingcid" => ""), array("id" => $userid));
        }
    }

    $details['id'] = $details['userid'] = $data['id'];
    $details['firstname'] = $data['firstname'];
    $details['lastname'] = $data['lastname'];
    $details['email_notification'] = $data['email_notification'];
    $details['txt_notification'] = $data['txt_notification'];
    $details['companyname'] = $data['companyname'];
    $details['email'] = $data['email'];
    $details['address1'] = $data['address1'];
    $details['address2'] = $data['address2'];
    $details['city'] = $data['city'];
    $details['state'] = $data['state'];
    $details['postcode'] = $data['postcode'];
    $details['country'] = $details['countrycode'] = $data['country'];
    $details['countryname'] = $countries[$data['country']];
    $details['phonecc'] = $countrycallingcodes[$data['country']];
    $details['phonenumber'] = $data['phonenumber'];
    $details['mobilenumber'] = $data['mobilenumber'];
    $details['notes'] = $data['notes'];
    $details['password'] = $data['password'];
    $details['twofaenabled'] = ($data['authmodule'] ? true : false);
    $details['currency'] = $data['currency'];
    $details['defaultgateway'] = $data['defaultgateway'];
    $details['cctype'] = $data['cardtype'];
    $details['cclastfour'] = $data['cardlastfour'];
    $details['securityqid'] = $data['securityqid'];
    $details['securityqans'] = decrypt($data['securityqans']);
    $details['groupid'] = $data['groupid'];
    $details['status'] = $data['status'];
    $details['credit'] = $data['credit'];
    $details['taxexempt'] = $data['taxexempt'];
    $details['latefeeoveride'] = $data['latefeeoveride'];
    $details['overideduenotices'] = $data['overideduenotices'];
    $details['separateinvoices'] = $data['separateinvoices'];
    $details['disableautocc'] = $data['disableautocc'];
    $details['emailoptout'] = $data['emailoptout'];
    $details['overrideautoclose'] = $data['overrideautoclose'];
    $details['language'] = $data['language'];
    $lastlogin = $data['lastlogin'];
    $ipaddr = $data['ip'];
    $host = $data['host'];

    if ($lastlogin == "0000-00-00 00:00:00") {
        $details['lastlogin'] = "No Login Logged";
    } else {
        $details['lastlogin'] = "Date: " . fromMySQLDate($lastlogin, "time") . ("<br>IP Address: " . $ipaddr . "<br>Host: " . $host);
    }


    if (!function_exists("getCustomFields")) {
        require dirname(__FILE__) . "/customfieldfunctions.php";
    }

    $customfields = getCustomFields("client", "", $userid, "on");
    $i = 1;
    foreach ($customfields as $value) {
        $details["customfields" . $i] = $value['value'];
        $details['customfields'][] = array("id" => $value['id'], "value" => $value['value']);
        ++$i;
    }

    $details['billingcid'] = $data['billingcid'];

    if ($contactid) {
        $details['domainemails'] = $data['domainemails'];
        $details['generalemails'] = $data['generalemails'];
        $details['invoiceemails'] = $data['invoiceemails'];
        $details['productemails'] = $data['productemails'];
        $details['supportemails'] = $data['supportemails'];
    }

    return $details;
}

function getClientsStats($userid) {
    global $CONFIG;
    global $currency;

    $currency = getCurrency($userid);
    $stats = array();
    $result = select_query_i("tblinvoices", "COUNT(*),SUM(total)-COALESCE(SUM((SELECT SUM(amountin)-SUM(amountout) FROM tblaccounts WHERE tblaccounts.invoiceid=tblinvoices.id)),0),(SELECT SUM(amountin-fees-amountout) FROM tblaccounts WHERE userid=" . (int) $userid . "),(SELECT credit FROM tblclients WHERE id=" . (int) $userid . ")", array("userid" => $userid, "status" => "Unpaid", "(select count(id) from tblinvoiceitems where invoiceid=tblinvoices.id and type='Invoice')" => array("sqltype" => "<=", "value" => 0)));
    $data = mysqli_fetch_array($result);
    $stats['numdueinvoices'] = $data[0];
    $stats['dueinvoicesbalance'] = formatCurrency($data[1]);
    $stats['income'] = formatCurrency($data[2]);
    $stats['incredit'] = (0 < $data[3] ? true : false);
    $stats['creditbalance'] = formatCurrency($data[3]);
    $result = select_query_i("tblinvoices", "COUNT(*),SUM(total)-COALESCE(SUM((SELECT SUM(amountin)-SUM(amountout) FROM tblaccounts WHERE tblaccounts.invoiceid=tblinvoices.id)),0)", array("userid" => $userid, "status" => "Unpaid", "duedate" => array("sqltype" => "<", "value" => date("Ymd")), "(select count(id) from tblinvoiceitems where invoiceid=tblinvoices.id and type='Invoice')" => array("sqltype" => "<=", "value" => 0)));
    $data = mysqli_fetch_array($result);
    $stats['numoverdueinvoices'] = $data[0];
    $stats['overdueinvoicesbalance'] = formatCurrency($data[1]);
    $stats['overdueinvoicesbalancenumber'] = number_format(round($data[1], 2),2);
    $invoicestats = array();
    $result = select_query_i("tblinvoices", "status,COUNT(*),SUM(total)", "userid=" . (int) $userid . " GROUP BY status");

    while ($data = mysqli_fetch_array($result)) {
        $invoicestats[$data[0]] = $data;
    }

    $stats['numpaidinvoices'] = (isset($invoicestats['Paid'][1]) ? $invoicestats['Paid'][1] : 0);
    $stats['paidinvoicesamount'] = (isset($invoicestats['Paid'][2]) ? formatCurrency($invoicestats['Paid'][2]) : 0);
    $stats['numunpaidinvoices'] = (isset($invoicestats['Unpaid'][1]) ? $invoicestats['Unpaid'][1] : 0);
    $stats['unpaidinvoicesamount'] = (isset($invoicestats['Unpaid'][2]) ? formatCurrency($invoicestats['Unpaid'][2]) : 0);
    $stats['numcancelledinvoices'] = (isset($invoicestats['Cancelled'][1]) ? $invoicestats['Cancelled'][1] : 0);
    $stats['cancelledinvoicesamount'] = (isset($invoicestats['Cancelled'][2]) ? formatCurrency($invoicestats['Cancelled'][2]) : 0);
    $stats['numrefundedinvoices'] = (isset($invoicestats['Refunded'][1]) ? $invoicestats['Refunded'][1] : 0);
    $stats['refundedinvoicesamount'] = (isset($invoicestats['Refunded'][2]) ? formatCurrency($invoicestats['Refunded'][2]) : 0);
    $stats['numcollectionsinvoices'] = (isset($invoicestats['Collections'][1]) ? $invoicestats['Collections'][1] : 0);
    $stats['collectionsinvoicesamount'] = (isset($invoicestats['Collections'][2]) ? formatCurrency($invoicestats['Collections'][2]) : 0);
    $productstats = array();
    $result = full_query_i("SELECT tblservices.type,servicestatus,COUNT(*) FROM tblcustomerservices INNER JOIN tblservices ON tblcustomerservices.packageid=tblservices.id WHERE tblcustomerservices.userid=" . (int) $userid . " GROUP BY servicestatus,tblservices.type");

    while ($data = mysqli_fetch_array($result)) {
        $productstats[$data[0]][$data[1]] = $data[2];
    }

    $stats['productsnumactivehosting'] = (isset($productstats['hostingaccount']['Active']) ? $productstats['hostingaccount']['Active'] : 0);
    $stats['productsnumpedinghosting'] = (isset($productstats['hostingaccount']['Pending']) ? $productstats['hostingaccount']['Pending'] : 0);
    $stats['productsnumcancelledhosting'] = (isset($productstats['hostingaccount']['Cancelled']) ? $productstats['hostingaccount']['Cancelled'] : 0);
    $stats['productsnumhosting'] = 0;

    if (array_key_exists("hostingaccount", $productstats) && is_array($productstats['hostingaccount'])) {
        foreach ($productstats['hostingaccount'] as $status => $count) {
            $stats['productsnumhosting'] += $count;
        }
    }


    $stats['servicenumactive'] = (isset($productstats['services']['Active']) ? $productstats['services']['Active'] : 0);
    $stats['servicenumpending'] = (isset($productstats['services']['Pending']) ? $productstats['services']['Pending'] : 0);
    $stats['servicenumcancel'] = (isset($productstats['services']['Cancelled']) ? $productstats['services']['Cancelled'] : 0);

    $stats['servicenumtotal'] = $stats['servicenumactive'] + $stats['servicenumpending'] + $stats['servicenumcancel'];
    if (array_key_exists("services", $productstats) && is_array($productstats['services'])) {
        foreach ($productstats['services'] as $status => $count) {
            $stats['services'] += $count;
        }
    }

    $stats['productnumactive'] = (isset($productstats['product']['Active']) ? $productstats['product']['Active'] : 0);
    $stats['productnumpending'] = (isset($productstats['product']['Pending']) ? $productstats['product']['Pending'] : 0);
    $stats['productnumcancel'] = (isset($productstats['product']['Cancelled']) ? $productstats['product']['Cancelled'] : 0);

    $stats['producttotal'] = $stats['productnumactive'] + $stats['productnumpending'] + $stats['productnumcancel'];
    if (array_key_exists("product", $productstats) && is_array($productstats['product'])) {
        foreach ($productstats['product'] as $status => $count) {
            $stats['product'] += $count;
        }
    }


    $stats['productsnumactivereseller'] = (isset($productstats['reselleraccount']['Active']) ? $productstats['reselleraccount']['Active'] : 0);
    $stats['productsnumpendingreseller'] = (isset($productstats['reselleraccount']['Pending']) ? $productstats['reselleraccount']['Pending'] : 0);
    $stats['productsnumcancelledreseller'] = (isset($productstats['reselleraccount']['Cancelled']) ? $productstats['reselleraccount']['Cancelled'] : 0);
    $stats['productsnumreseller'] = 0;

    if (array_key_exists("reselleraccount", $productstats) && is_array($productstats['reselleraccount'])) {
        foreach ($productstats['reselleraccount'] as $status => $count) {
            $stats['productsnumreseller'] += $count;
        }
    }

    $stats['productsnumactiveservers'] = (isset($productstats['server']['Active']) ? $productstats['server']['Active'] : 0);
    $stats['productsnumpendingservers'] = (isset($productstats['server']['Pending']) ? $productstats['server']['Pending'] : 0);
    $stats['productsnumcancelledservers'] = (isset($productstats['server']['Cancelled']) ? $productstats['server']['Cancelled'] : 0);
    $stats['productsnumservers'] = 0;

    if (array_key_exists("server", $productstats) && is_array($productstats['server'])) {
        foreach ($productstats['server'] as $status => $count) {
            $stats['productsnumservers'] += $count;
        }
    }

    $stats['productsnumactiveother'] = (isset($productstats['other']['Active']) ? $productstats['other']['Active'] : 0);
    $stats['productsnumpendingother'] = (isset($productstats['other']['Pending']) ? $productstats['other']['Pending'] : 0);
    $stats['productsnumcancelledother'] = (isset($productstats['other']['Cancelled']) ? $productstats['other']['Cancelled'] : 0);
    $stats['productsnumother'] = 0;

    if (array_key_exists("other", $productstats) && is_array($productstats['other'])) {
        foreach ($productstats['other'] as $status => $count) {
            $stats['productsnumother'] += $count;
        }
    }

    $stats['productsnumactive'] = $stats['productsnumactivehosting'] + $stats['productsnumactivereseller'] + $stats['productsnumactiveservers'] + $stats['productsnumactiveother'];
    $stats['productsnumpending'] = $stats['productsnumpendinghosting'] + $stats['productsnumpendingreseller'] + $stats['productsnumpendingservers'] + $stats['productsnumpendingother'];
    $stats['productsnumcancelled'] = $stats['productsnumcancelledhosting'] + $stats['productsnumcancelledreseller'] + $stats['productsnumcancelledservers'] + $stats['productsnumcancelledother'];
    $stats['productsnumtotal'] = $stats['productsnumhosting'] + $stats['productsnumreseller'] + $stats['productsnumservers'] + $stats['productsnumother'];
    $domainstats = array();
    select_query_i("tblcustomerservices", "status,COUNT(*)", "userid=" . (int) $userid . " GROUP BY status");

    while ($data = mysqli_fetch_array($result)) {
        $domainstats[$data[0]] = $data[1];
    }

    $stats['numactivedomains'] = (isset($domainstats['Active']) ? $domainstats['Active'] : 0);
    $stats['numdomains'] = 0;
    foreach ($domainstats as $count) {
        $stats['numdomains'] += $count;
    }

    $quotestats = array();
    $result = select_query_i("tblquotes", "stage,COUNT(*)", "userid=" . (int) $userid . " GROUP BY stage");

    while ($data = mysqli_fetch_array($result)) {
        $quotestats[$data[0]] = $data[1];
    }

    $stats['numacceptedquotes'] = (isset($quotestats['Accepted']) ? $quotestats['Accepted'] : 0);
    $stats['numquotes'] = 0;
    foreach ($quotestats as $count) {
        $stats['numquotes'] += $count;
    }

    $statusfilter = array();
    $result = select_query_i("tblticketstatuses", "title", array("showactive" => "1"));

    while ($data = mysqli_fetch_array($result)) {
        $statusfilter[] = $data[0];
    }

    $ticketstats = array();
    $result = select_query_i("tbltickets", "status,COUNT(*)", "userid=" . (int) $userid . " GROUP BY status");

    while ($data = mysqli_fetch_array($result)) {
        $ticketstats[$data[0]] = $data[1];
    }

    $stats['numactivetickets'] = $stats['numtickets'] = 0;
    foreach ($ticketstats as $status => $count) {

        if (in_array($status, $statusfilter)) {
            $stats['numactivetickets'] += $count;
        }

        $stats['numtickets'] += $count;
    }

    $result = select_query_i("tblaffiliatesaccounts", "COUNT(*)", array("clientid" => $userid), "", "", "", "tblaffiliates ON tblaffiliatesaccounts.affiliateid=tblaffiliates.id");
    $data = mysqli_fetch_array($result);
    $stats['numaffiliatesignups'] = $data[0];
    return $stats;
}

function getCountriesDropDown($selected = "", $fieldname = "", $tabindex = "") {
    global $countries;
    global $CONFIG;
    global $_LANG;

    if (!$selected) {
        $selected = $CONFIG['DefaultCountry'];
    }


    if (!$fieldname) {
        $fieldname = "country";
    }


    if ($tabindex) {
        $tabindex = (" tabindex=\"" . $tabindex . "\"");
    }

    $dropdowncode = ("<select class=\"form-control country\" name=\"" . $fieldname . "\" ") . $tabindex . ">";
    foreach ($countries as $countriesvalue1 => $countriesvalue2) {
        $dropdowncode .= "<option value=\"" . $countriesvalue2 . "\"";

        if ($countriesvalue2 == $selected) {
            $dropdowncode .= " selected=\"selected\"";
        }

        $dropdowncode .= ">" . $countriesvalue2 . "</option>";
    }

    $dropdowncode .= "</select>";
    return $dropdowncode;
}

function getClientNotes($userid, $limit = "", $table = true) {
    $notes = array();
    if ($limit != "") {
        $limitquery = "limit 5";
    } else {
        $limitquery = "";
    }
    $query = "select tbn.*,CONCAT(tba.firstname,' ',tba.lastname) as name,CONCAT(tbaa.firstname,' ',tbaa.lastname) as assignname from tblnotes as tbn 
INNER JOIN tbladmins AS tba on (tba.id=tbn.adminid) 
INNER JOIN tbladmins AS tbaa on (tbaa.id=tbn.assignto) 
LEFT JOIN tblorders as tbo on (tbo.id=tbn.rel_id and tbn.type='order')
LEFT JOIN tblcustomerservices as tbcs on (tbcs.id=tbn.rel_id  and tbn.type='account')
where (tbn.rel_id=" . $userid . " and tbn.type='client') OR tbo.userid=" . $userid . " OR tbcs.userid=" . $userid . " ORDER BY tbn.flag DESC " . $limitquery;

    $result = full_query_i($query);
    while ($data = mysqli_fetch_assoc($result)) {

        if ($table) {
            if (strtotime($data['duedate']) == strtotime(date("d.m.Y"))) {
                $data['color'] = "warning";
            } else if (strtotime($data['duedate']) < strtotime(date("d.m.Y"))) {
                $data['color'] = "danger";
            } else {
                $data['color'] = "success";
            }
            $data['created'] = fromMySQLDate($data['created'], 1);
            $data['modified'] = fromMySQLDate($data['modified'], 1);
            $data['note'] = autoHyperLink(nl2br($data['note']));
            $data['type'] = $data['type'] == 'client' ? 'clientssummary.php?userid=' . $data['rel_id'] : "clientsservices.php?id=" . $data['rel_id'];
            $notes[] = $data;
        } else {
            $notes [] = $data;
        }
    }

    return $notes;
}

function checkDetailsareValid($uid = "", $signup = false, $checkemail = true, $captcha = true, $checkcustomfields = true) {
    global $ra;
    global $CONFIG;

    $validate = new RA_Validate();
    $validate->setOptionalFields($ra->get_config("ClientsProfileOptionalFields"));

    if (!$signup) {
        $validate->setOptionalFields($ra->get_config("ClientsProfileUneditableFields"));
    }

    $validate->validate("required", "firstname", "clientareaerrorfirstname");
    $validate->validate("required", "lastname", "clientareaerrorlastname");

    if (($signup || $checkemail) && $validate->validate("required", "email", "clientareaerroremail")) {
        if ($validate->validate("email", "email", "clientareaerroremailinvalid")) {
            if ($validate->validate("banneddomain", "email", "clientareaerrorbannedemail")) {
                $validate->validate("uniqueemail", "email", "ordererroruserexists", array($uid, ""));
            }
        }
    }

    $validate->validate("required", "address1", "clientareaerroraddress1");
    $validate->validate("required", "city", "clientareaerrorcity");
    $validate->validate("required", "state", "clientareaerrorstate");
    $validate->validate("required", "postcode", "clientareaerrorpostcode");
    $validate->validate("postcode", "postcode", "clientareaerrorpostcode2");
    $validate->validate("required", "phonenumber", "clientareaerrorphonenumber");
    $validate->validate("phone", "phonenumber", "clientareaerrorphonenumber2");
    $validate->validate("required", "country", "clientareaerrorcountry");

    if ($signup && $validate->validate("required", "password", "ordererrorpassword")) {
        if ($validate->validate("pwstrength", "password", "pwstrengthfail")) {
            if ($validate->validate("required", "password2", "clientareaerrorpasswordconfirm")) {
                $validate->validate("match_value", "password", "clientareaerrorpasswordnotmatch", "password2");
            }
        }
    }


    if ($checkcustomfields) {
        $validate->validateCustomFields("client", "", $signup);
    }


    if ($signup) {
        $securityquestions = getSecurityQuestions();

        if ($securityquestions) {
            $validate->validate("required", "securityqans", "securityanswerrequired");
        }


        if ($captcha) {
            $validate->validate("captcha", "code", "captchaverifyincorrect");
        }


        if ($ra->get_config("EnableTOSAccept")) {
            $validate->validate("required", "accepttos", "ordererroraccepttos");
        }
    }

    run_validate_hook($validate, "ClientDetailsValidation", $_POST);
    $errormessage = $validate->getHTMLErrorOutput();
    return $errormessage;
}

function checkContactDetails($cid = "", $reqpw = false, $prefix = "") {
    global $ra;

    $subaccount = $ra->get_req_var("subaccount");
    $validate = new RA_Validate();
    $validate->setOptionalFields($ra->get_config("ClientsProfileOptionalFields"));
    $validate->validate("required", $prefix . "firstname", "clientareaerrorfirstname");
    $validate->validate("required", $prefix . "lastname", "clientareaerrorlastname");

    if ($validate->validate("required", $prefix . "email", "clientareaerroremail")) {
        if ($validate->validate("email", $prefix . "email", "clientareaerroremailinvalid")) {
            if ($validate->validate("banneddomain", $prefix . "email", "clientareaerrorbannedemail")) {
                if ($subaccount) {
                    $validate->validate("uniqueemail", $prefix . "email", "ordererroruserexists", array("", $cid));
                }
            }
        }
    }

    $validate->validate("required", $prefix . "address1", "clientareaerroraddress1");
    $validate->validate("required", $prefix . "city", "clientareaerrorcity");
    $validate->validate("required", $prefix . "state", "clientareaerrorstate");
    $validate->validate("required", $prefix . "postcode", "clientareaerrorpostcode");
    $validate->validate("postcode", $prefix . "postcode", "clientareaerrorpostcode2");
    $validate->validate("required", $prefix . "phonenumber", "clientareaerrorphonenumber");
    $validate->validate("phone", $prefix . "phonenumber", "clientareaerrorphonenumber2");
    $validate->validate("country", $prefix . "country", "clientareaerrorcountry");

    if (($subaccount && $reqpw) && $validate->validate("required", "password", "ordererrorpassword")) {
        if ($validate->validate("pwstrength", "password", "pwstrengthfail")) {
            if ($validate->validate("required", "password2", "clientareaerrorpasswordconfirm")) {
                $validate->validate("match_value", "password", "clientareaerrorpasswordnotmatch", "password2");
            }
        }
    }

    run_validate_hook($validate, "ContactDetailsValidation", $_POST);
    $errormessage = $validate->getHTMLErrorOutput();
    return $errormessage;
}

function addClient($firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password, $dob, $securityqid = "", $securityqans = "", $sendemail = "on", $additionaldata = "") {
    global $ra;
    global $remote_ip;

    if (!$country) {
        $country = $ra->get_config("DefaultCountry");
    }

    $fullhost = gethostbyaddr($remote_ip);
    $currency = (is_array($_SESSION['currency']) ? $_SESSION['currency'] : getCurrency("", $_SESSION['currency']));
    $password_hash = generateClientPW($password);
    $table = "tblclients";
    $array = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber, "password" => $password_hash, "dateofbirth" => $dob, "lastlogin" => "now()", "securityqid" => $securityqid, "securityqans" => encrypt($securityqans), "ip" => $remote_ip, "host" => $fullhost, "status" => "Active", "datecreated" => "now()", "language" => isset($_SESSION['Language']) ? $_SESSION['Language'] : "", "currency" => $currency['id']);

    $uid = insert_query($table, $array);
    logActivity("Created Client " . $firstname . " " . $lastname . " - User ID: " . $uid);

    if ($additionaldata) {
        update_query("tblclients", $additionaldata, array("id" => $uid));
    }


    if (!function_exists("saveCustomFields")) {
        require ROOTDIR . "/includes/customfieldfunctions.php";
    }

    saveClientFields($uid, $_POST['customfield']);

    if ($sendemail) {
        sendMessage("Client Signup Email", $uid, array("client_password" => $password));
    }

    $_SESSION['uid'] = $uid;
    $haship = ($ra->get_config("DisableSessionIPCheck") ? "" : $ra->get_user_ip());
    $_SESSION['upw'] = sha1($uid . $password_hash . $haship . substr(sha1($ra->get_hash()), 0, 20));
    $_SESSION['tkval'] = genRandomVal();
    run_hook("ClientAdd", array("userid" => $uid, "firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber, "password" => $password, "dateofbirth" => $dob));
    run_hook("ClientLogin", array("userid" => $uid));
    return $uid;
}

function addContact($userid, $firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password = "", $permissions = array(), $generalemails = "", $productemails = "", $domainemails = "", $invoiceemails = "", $supportemails = "") {
    global $CONFIG;

    if (!$country) {
        $country = $CONFIG['DefaultCountry'];
    }

    $subaccount = ($password ? "1" : "0");

    if ($permissions) {
        $permissions = implode(",", $permissions);
    }

    $table = "tblcontacts";
    $array = array("userid" => $userid, "firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber, "subaccount" => $subaccount, "password" => generateClientPW($password), "permissions" => $permissions, "generalemails" => $generalemails, "productemails" => $productemails, "domainemails" => $domainemails, "invoiceemails" => $invoiceemails, "supportemails" => $supportemails);
    $contactid = insert_query($table, $array);
    run_hook("ContactAdd", array_merge($array, array("contactid" => $contactid)));
    logActivity("Added Contact - Contact ID: " . $contactid . " - User ID: " . $userid, $userid);
    return $contactid;
}

function deleteClient($userid) {
    $userid = (int) get_query_val("tblclients", "id", array("id" => (int) $userid));

    if (!$userid) {
        return false;
    }

    run_hook("PreDeleteClient", array("userid" => $userid));
    delete_query("tblclients", array("id" => $userid));
    delete_query("tblcontacts", array("userid" => $userid));
    delete_query("tblserviceconfigoptions", "relid IN (SELECT id FROM tblcustomerservices WHERE userid=" . $userid . ")");
    $result = select_query_i("tblcustomerservices", "id", array("userid" => $userid));

    while ($data = mysqli_fetch_array($result)) {
        $domainlistid = $data['id'];
        delete_query("tblserviceaddons", array("hostingid" => $domainlistid));
    }

    $result = select_query_i("tblcustomfields", "id", array("type" => "client"));

    while ($data = mysqli_fetch_array($result)) {
        $customfieldid = $data['id'];
        delete_query("tblcustomfieldsvalues", array("fieldid" => $customfieldid, "relid" => $userid));
    }

    $result = select_query_i("tblcustomfields", "id,relid", array("type" => "product"));

    while ($data = mysqli_fetch_array($result)) {
        $customfieldid = $data['id'];
        $customfieldpid = $data['relid'];
        $result2 = select_query_i("tblcustomerservices", "id", array("userid" => $userid, "packageid" => $customfieldpid));

        while ($data = mysqli_fetch_array($result2)) {
            $hostingid = $data['id'];
            delete_query("tblcustomfieldsvalues", array("fieldid" => $customfieldid, "relid" => $hostingid));
        }
    }

    delete_query("tblorders", array("userid" => $userid));
    delete_query("tblcustomerservices", array("userid" => $userid));
    delete_query("tblcustomerservices", array("userid" => $userid));
    delete_query("tblemails", array("userid" => $userid));
    delete_query("tblinvoices", array("userid" => $userid));
    delete_query("tblinvoiceitems", array("userid" => $userid));
    delete_query("tbltickets", array("userid" => $userid));
    delete_query("tblaffiliates", array("clientid" => $userid));
    delete_query("tblnotes", array("userid" => $userid));
    delete_query("tblcredit", array("clientid" => $userid));
    delete_query("tblactivitylog", array("userid" => $userid));
    delete_query("tblsslorders", array("userid" => $userid));
    logActivity("Client Deleted - ID: " . $userid);
    return true;
}

function getSecurityQuestions($questionid = "") {
    if ($questionid) {
        $query = select_query_i("tbladminsecurityquestions", "", array("question" => $questionid));
    } else {
        $query = select_query_i("tbladminsecurityquestions", "", "");
    }

    $results = array();

    while ($data = mysqli_fetch_assoc($query)) {
        $results[] = array("id" => $data['id'], "question" => decrypt($data['question']));
    }

    return $results;
}

function generateClientPW($plain, $salt = "", $ignoreconfig = false) {
    return password_hash(html_entity_decode($plain), PASSWORD_DEFAULT);
}

function checkContactPermission($reqperm, $noredirect = "") {
    if (!isset($_SESSION['cid'])) {
        return true;
    }

    $result = select_query_i("tblcontacts", "permissions", array("id" => $_SESSION['cid'], "userid" => $_SESSION['uid']));
    $data = mysqli_fetch_array($result);
    $permissions = $data['permissions'];
    $permissions = explode(",", $permissions);

    if (!in_array($reqperm, $permissions)) {
        global $ca;
        global $_LANG;
        global $smartyvalues;

        if ($noredirect) {
            return false;
        }

        foreach ($permissions as $key => $permission) {
            $permissions[$key] = $_LANG["subaccountperms" . $permission];
        }


        if (is_object($ca)) {
            $ca->assign("allowedpermissions", $permissions);
            $ca->assign("requiredpermission", $reqperm);
            $ca->setTemplate("contactaccessdenied");
            $ca->output();
            exit();
        }

        $smartyvalues['allowedpermissions'] = $permissions;
        $smartyvalues['requiredpermission'] = $reqperm;
        $templatefile = "contactaccessdenied";
        outputClientArea($templatefile);
        exit();
    }

    return true;
}

function validateClientLogin($username, $password, $twofadone = false) {
    global $CONFIG;
    global $ra;

    if ($username && (($password || $_SESSION['adminid']) || $twofadone)) {
        
    } else {
        return false;
    }


    if (isset($_SESSION['uid'])) {
        unset($_SESSION['uid']);
    }


    if (isset($_SESSION['cid'])) {
        unset($_SESSION['cid']);
    }


    if (isset($_SESSION['upw'])) {
        unset($_SESSION['upw']);
    }

    $login_uid = $login_cid = $login_pwd = $loginsharematch = "";
    $where = array();
    $where['email'] = $username;

    if (!$_SESSION['adminid']) {
        $where['status'] = array("sqltype" => "NEQ", "value" => "Closed");
    }

    $result = select_query_i("tblclients", "", $where);
    $data = mysqli_fetch_array($result);
    $login_uid = $data['id'];
    $login_pwd = $data['password'];
    $language = $data['language'];
    $authmodule = $data['authmodule'];

    if (!$login_uid) {
        $result = select_query_i("tblcontacts", "", array("email" => $username, "subaccount" => "1", "password" => array("sqltype" => "NEQ", "value" => "")));
        $data = mysqli_fetch_array($result);
        $login_cid = $data['id'];
        $login_uid = $data['userid'];
        $login_pwd = $data['password'];
        $result = select_query_i("tblclients", "id,language", array("id" => $login_uid, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
        $data = mysqli_fetch_array($result);
        $login_uid = $data['id'];
        $language = $data['language'];
    }


    if (!$login_uid) {
        $hookresults = run_hook("ClientLoginShare", array("username" => $username, "password" => $password));
        foreach ($hookresults as $hookres) {

            if ($hookres) {
                $hookid = $hookres['id'];
                $hookemail = $hookres['email'];

                if ($hookid) {
                    $result = select_query_i("tblclients", "", array("id" => $hookid));
                } else {
                    $result = select_query_i("tblclients", "", array("email" => $hookemail));
                }

                $data = mysqli_fetch_array($result);
                $login_uid = $data['id'];

                if ($login_uid) {
                    $loginsharematch = true;
                    $login_pwd = $data['password'];
                    $language = $data['language'];
                    continue;
                }


                if ($hookres['create']) {
                    addClient($hookres['firstname'], $hookres['lastname'], $hookres['companyname'], $hookres['email'], $hookres['address1'], $hookres['address2'], $hookres['city'], $hookres['state'], $hookres['postcode'], $hookres['country'], $hookres['phonenumber'], $hookres['password'], "", "", false);
                    return true;
                }

                continue;
            }
        }
    }


    if ($login_uid) {
        $check_pwd = $login_pwd;

        $adminallowedclientlogin = false;

        if (isset($_SESSION['adminid'])) {
            $adminroleid = get_query_val("tbladmins", "roleid", array("id" => $_SESSION['adminid']));
            $adminallowedclientlogin = get_query_val("tbladminperms", "permid", array("roleid" => $adminroleid, "permid" => "120"));
        }


        if (((password_verify($password, $check_pwd) || (isset($_SESSION['adminid']) && $adminallowedclientlogin)) || $loginsharematch)) {


            if (!isset($_SESSION['adminid'])) {
                $fullhost = gethostbyaddr($ra->get_user_ip());
                update_query("tblclients", array("lastlogin" => "now()", "ip" => $ra->get_user_ip(), "host" => $fullhost), array("id" => $login_uid));
            }

            $_SESSION['uid'] = $login_uid;

            if ($login_cid) {
                $_SESSION['cid'] = $login_cid;
            }

            $haship = ($CONFIG['DisableSessionIPCheck'] ? "" : $ra->get_user_ip());
            $_SESSION['upw'] = sha1($login_uid . $login_cid . $login_pwd . $haship . substr(sha1($ra->get_hash()), 0, 20));

            if (!isset($_SESSION['adminid'])) {
                set_token(genRandomVal());
            }


            if ($language && !isset($_SESSION['adminid'])) {
                $_SESSION['Language'] = $language;
            }

            run_hook("ClientLogin", array("userid" => $login_uid));
            return true;
        }
    }


    if ($login_uid) {
        logActivity("Failed Login Attempt - User ID: " . $login_uid, $login_uid);
    }

    return false;
}

function createCancellationRequest($userid, $serviceid, $reason, $type) {
    global $CONFIG;
    global $currency;

    $existing = get_query_val("tblcancelrequests", "COUNT(id)", array("relid" => $serviceid));

    if ($existing == 0) {
        if (!in_array($type, array("Immediate", "End of Billing Period"))) {
            $type = "End of Billing Period";
        }

        insert_query("tblcancelrequests", array("date" => "now()", "relid" => $serviceid, "reason" => $reason, "type" => $type));

        if ($type == "End of Billing Period") {
            logActivity("Automatic Cancellation Requested for End of Current Cycle - Service ID: " . $serviceid, $userid);
        } else {
            logActivity("Automatic Cancellation Requested Immediately - Service ID: " . $serviceid, $userid);
        }

        $data = get_query_vals("tblcustomerservices", "domain,freedomain", array("tblcustomerservices.id" => $serviceid), "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");
        $domain = $data[0];
        $freedomain = $data[1];

        if ($freedomain && $domain) {
            $data = get_query_vals("tblcustomerservices", "id,recurringamount,registrationperiod,dnsmanagement,emailforwarding,idprotection", array("userid" => $userid, "domain" => $domain), "status", "ASC");
            $domainid = $data['id'];
            $recurringamount = $data['recurringamount'];
            $regperiod = $data['registrationperiod'];
            $dnsmanagement = $data['dnsmanagement'];
            $emailforwarding = $data['emailforwarding'];
            $idprotection = $data['idprotection'];

            if ($recurringamount <= 0) {
                $currency = getCurrency($userid);
                $result = select_query_i("tblpricing", "msetupfee,qsetupfee,ssetupfee", array("type" => "domainaddons", "currency" => $currency['id'], "relid" => 0));
                $data = mysqli_fetch_array($result);
                $domaindnsmanagementprice = $data['msetupfee'] * $regperiod;
                $domainemailforwardingprice = $data['qsetupfee'] * $regperiod;
                $domainidprotectionprice = $data['ssetupfee'] * $regperiod;
                $domainparts = explode(".", $domain, 2);

                $temppricelist = getTLDPriceList("." . $domainparts[1], "", true, $userid);
                $recurringamount = $temppricelist[$regperiod]['renew'];

                if ($dnsmanagement) {
                    $recurringamount += $domaindnsmanagementprice;
                }


                if ($emailforwarding) {
                    $recurringamount += $domainemailforwardingprice;
                }


                if ($idprotection) {
                    $recurringamount += $domainidprotectionprice;
                }

                update_query("tblcustomerservices", array("recurringamount" => $recurringamount), array("id" => $domainid));
            }
        }

        run_hook("CancellationRequest", array("userid" => $userid, "relid" => $serviceid, "reason" => $reason, "type" => $type));

        if ($CONFIG['CancelInvoiceOnCancellation']) {
            $result = select_query_i("tblinvoiceitems", "tblinvoiceitems.id,tblinvoiceitems.invoiceid", array("type" => "Hosting", "relid" => $serviceid, "status" => "Unpaid", "tblinvoices.userid" => $userid), "", "", "", "tblinvoices ON tblinvoices.id=tblinvoiceitems.invoiceid");

            while ($data = mysqli_fetch_array($result)) {
                $itemid = $data['id'];
                $invoiceid = $data['invoiceid'];
                $result2 = select_query_i("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $invoiceid));
                $data = mysqli_fetch_array($result2);
                $itemcount = $data[0];

                if (1 < $itemcount && $itemcount <= 4) {
                    $itemcount -= get_query_val("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $invoiceid, "type" => "PromoHosting", "relid" => $serviceid));
                    $itemcount -= get_query_val("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $invoiceid, "type" => "GroupDiscount"));
                    $itemcount -= get_query_val("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $invoiceid, "type" => "LateFee"));
                }


                if ($itemcount == 1) {
                    update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invoiceid));
                    logActivity("Cancelled Outstanding Product Renewal Invoice - Invoice ID: " . $invoiceid . " - Service ID: " . $serviceid, $userid);
                    run_hook("InvoiceCancelled", array("invoiceid" => $invoiceid));
                }

                delete_query("tblinvoiceitems", array("id" => $itemid));
                delete_query("tblinvoiceitems", array("invoiceid" => $invoiceid, "type" => "PromoHosting", "relid" => $serviceid));
                delete_query("tblinvoiceitems", array("invoiceid" => $invoiceid, "type" => "GroupDiscount"));
                updateInvoiceTotal($invoiceid);
                logActivity("Removed Outstanding Product Renewal Invoice Line Item - Invoice ID: " . $invoiceid . " - Service ID: " . $serviceid, $userid);
            }
        }

        return "success";
    }

    return "Existing Cancellation Request Exists";
}

function recalcRecurringProductPrice($serviceid, $userid = "", $pid = "", $billingcycle = "", $configoptionsrecurring = "empty", $promoid = 0, $includesetup = false) {
    if ((!$userid || !$pid) || !$billingcycle) {
        $result = select_query_i("tblcustomerservices", "userid,packageid,billingcycle", array("id" => $serviceid));
        $data = mysqli_fetch_array($result);

        if (!$userid) {
            $userid = $data['userid'];
        }


        if (!$pid) {
            $pid = $data['packageid'];
        }


        if (!$billingcycle) {
            $billingcycle = $data['billingcycle'];
        }
    }

    global $currency;

    $currency = getCurrency($userid);
    $result = select_query_i("tblpricing", "", array("type" => "product", "currency" => $currency['id'], "relid" => $pid));
    $data = mysqli_fetch_array($result);

    if ($billingcycle == "Monthly") {
        $amount = $data['monthly'];
    } elseif ($billingcycle == "Quarterly") {
        $amount = $data['quarterly'];
    } elseif ($billingcycle == "Semi-Annually") {
        $amount = $data['semiannually'];
    } elseif ($billingcycle == "Annually") {
        $amount = $data['annually'];
    } elseif ($billingcycle == "Biennially") {
        $amount = $data['biennially'];
    } elseif ($billingcycle == "Triennially") {
        $amount = $data['triennially'];
    } else {
        $amount = 0;
    }


    if ($includesetup === true) {
        $setupvar = substr(strtolower($billingcycle), 0, 1);
        $amount += $data[$setupvar . "setupfee"];
    }


    if ($configoptionsrecurring == "empty") {
        if (!function_exists("getCartConfigOptions")) {
            require ROOTDIR . "/includes/configoptionsfunctions.php";
        }

        $configoptions = getCartConfigOptions($pid, "", $billingcycle, $serviceid);
        foreach ($configoptions as $configoption) {
            $amount += $configoption['selectedrecurring'];

            if ($includesetup === true) {
                $amount += $configoption['selectedsetup'];
                continue;
            }
        }
    } else {
        $amount += $configoptionsrecurring;
    }


    if ($promoid) {
        $amount -= recalcPromoAmount($pid, $userid, $serviceid, $billingcycle, $amount, $promoid);
    }

    return $amount;
}

function getClientsServicesSummary($userid, $aInt) {
    $result = select_query_i(
            "tblcustomerservices", // table
            "tblcustomerservices.*,tblservices.name", // select fields
            array("userid" => $userid), // where
            "tblcustomerservices`.`id", "DESC", "", "tblservices ON tblservices.id=tblcustomerservices.packageid"
    );
    while ($data = mysqli_fetch_array($result)) {

        if ($data['billingcycle'] == "One Time" || $data['billingcycle'] == "Free Account") {
            $nextduedate = null;
            $amount = formatCurrency($data['firstpaymentamount']);
        } else {
            $nextduedate = fromMySQLDate($data['nextduedate']);
            $amount = formatCurrency($data['amount']);
        }

        $billingcycle = $aInt->lang("billingcycles", str_replace(array("-", "account", " "), "", strtolower($data['billingcycle'])));
        $servicessummary[] = array(
            "id" => (int) $data['id'],
            "regdate" => fromMySQLDate($data['regdate']),
            "description" => $data['description'],
            "dpackage" => $data['name'],
            "dpaymentmethod" => $data['paymentmethod'],
            "amount" => $amount,
            "dbillingcycle" => $billingcycle,
            "nextduedate" => $nextduedate,
            "servicestatus" => $aInt->lang("status", strtolower($data['servicestatus']))
        );
    }
    return $servicessummary;
}

function closeClient($userid) {
    update_query("tblclients", array("status" => "Closed"), array("id" => $userid));
    update_query("tblcustomerservices", array("servicestatus" => "Cancelled"), array("userid" => $userid, "servicestatus" => "Pending"));
    update_query("tblcustomerservices", array("servicestatus" => "Cancelled"), array("userid" => $userid, "servicestatus" => "Active"));
    update_query("tblcustomerservices", array("servicestatus" => "Terminated"), array("userid" => $userid, "servicestatus" => "Suspended"));
    $result = select_query_i("tblcustomerservices", "id", array("userid" => $userid));

    while ($data = mysqli_fetch_array($result)) {
        $domainlistid = $data['id'];
        update_query("tblserviceaddons", array("status" => "Cancelled"), array("hostingid" => $domainlistid, "status" => "Pending"));
        update_query("tblserviceaddons", array("status" => "Cancelled"), array("hostingid" => $domainlistid, "status" => "Active"));
        update_query("tblserviceaddons", array("status" => "Terminated"), array("hostingid" => $domainlistid, "status" => "Suspended"));
    }

    update_query("tblcustomerservices", array("status" => "Cancelled"), array("userid" => $userid, "status" => "Pending"));
    update_query("tblcustomerservices", array("status" => "Cancelled"), array("userid" => $userid, "status" => "Active"));
    update_query("tblcustomerservices", array("status" => "Cancelled"), array("userid" => $userid, "status" => "Pending-Transfer"));
    update_query("tblinvoices", array("status" => "Cancelled"), array("userid" => $userid, "status" => "Unpaid"));
    update_query("tblbillableitems", array("invoiceaction" => "0"), array("userid" => $userid));
    logActivity("Client Status changed to Closed - User ID: " . $userid, $userid);
    run_hook("ClientClose", array("userid" => $userid));
}

function getClientsPaymentMethod($userid) {
    $paymentmethod = get_query_val("tblclients", "defaultgateway", array("id" => $userid));

    if (!$paymentmethod) {
        $paymentmethod = get_query_val("tblinvoices", "paymentmethod", array("userid" => $userid), "id", "DESC", "0,1");
    }


    if (!$paymentmethod) {
        $paymentmethod = get_query_val("tblpaymentgateways", "gateway", "gateway!='' AND setting='name'", "order", "ASC", "0,1");
    }

    return $paymentmethod;
}

function clientChangeDefaultGateway($userid, $paymentmethod) {
    $defaultgateway = get_query_val("tblclients", "defaultgateway", array("id" => $userid));

    if (($_SESSION['adminid'] && !$paymentmethod) && $defaultgateway) {
        update_query("tblclients", array("defaultgateway" => ""), array("id" => $userid));
    }


    if ($paymentmethod && $paymentmethod != $defaultgateway) {
        if ($paymentmethod == "none") {
            update_query("tblclients", array("defaultgateway" => ""), array("id" => $userid));
        }

        $paymentmethod = get_query_val("tblpaymentgateways", "gateway", array("gateway" => $paymentmethod));

        if (!$paymentmethod) {
            return false;
        }

        update_query("tblclients", array("defaultgateway" => $paymentmethod), array("id" => $userid));
        update_query("tblcustomerservices", array("paymentmethod" => $paymentmethod), array("userid" => $userid));
        update_query("tblserviceaddons", array("paymentmethod" => $paymentmethod), "hostingid IN (SELECT id FROM tblcustomerservices WHERE userid=" . (int) $userid . ")");
        update_query("tblcustomerservices", array("paymentmethod" => $paymentmethod), array("userid" => $userid));
        update_query("tblinvoices", array("paymentmethod" => $paymentmethod), array("userid" => $userid, "status" => "Unpaid"));
    }
}

function recalcPromoAmount($pid, $userid, $serviceid, $billingcycle, $recurringamount, $promoid) {
    global $currency;

    $currency = getCurrency($userid);
    $recurringdiscount = $used = "";
    $result = select_query_i("tblpromotions", "", array("id" => $promoid));
    $data = mysqli_fetch_array($result);
    $id = $data['id'];
    $type = $data['type'];
    $recurring = $data['recurring'];
    $value = $data['value'];

    if ($recurring) {
        if ($type == "Percentage") {
            $recurringdiscount = $recurringamount * ($value / 100);
        } else {
            if ($type == "Fixed Amount") {
                if ($currency['id'] != 1) {
                    $value = convertCurrency($value, 1, $currency['id']);
                }


                if ($recurringamount < $value) {
                    $recurringdiscount = $recurringamount;
                } else {
                    $recurringdiscount = $value;
                }
            } else {
                if ($type == "Price Override") {
                    if ($currency['id'] != 1) {
                        $value = convertCurrency($value, 1, $currency['id']);
                    }

                    $recurringdiscount = $recurringamount - $value;
                }
            }
        }
    }

    return $recurringdiscount;
}

function doResetPWEmail($email, $answer = "") {
    global $CONFIG;
    global $_LANG;
    global $securityquestion;

    if (!$email) {
        return $_LANG['pwresetemailrequired'];
    }

    $result = select_query_i("tblclients", "id,password,securityqid,securityqans", array("email" => $email, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
    $data = mysqli_fetch_array($result);
    $userid = $data['id'];
    $password = $data['password'];
    $securityqid = $data['securityqid'];
    $securityqans = $data['securityqans'];

    if (!$userid) {
        $result = select_query_i("tblcontacts", "tblcontacts.id,tblcontacts.userid,tblcontacts.password", array("tblcontacts.email" => $email, "tblcontacts.subaccount" => "1", "tblclients.status" => array("sqltype" => "NEQ", "value" => "Closed")), "", "", "", "tblclients ON tblclients.id=tblcontacts.userid");
        $data = mysqli_fetch_array($result);
        $contactid = $data['id'];
        $userid = $data['userid'];
        $password = $data['password'];
    }


    if (!$userid) {
        return $_LANG['pwresetemailnotfound'];
    }


    if ($securityqid) {
        $result = select_query_i("tbladminsecurityquestions", "", array("id" => $securityqid));
        $data = mysqli_fetch_array($result);
        $securityquestion = decrypt($data['question']);

        if (!$answer) {
            return "";
        }


        if ($answer != decrypt($securityqans)) {
            return $_LANG['pwresetsecurityquestionincorrect'];
        }
    }

    $resetkey = md5($userid . rand(100000, 999999) . $password);

    if ($contactid) {
        update_query("tblcontacts", array("pwresetkey" => $resetkey, "pwresetexpiry" => time() + 2 * 60 * 60), array("id" => $contactid));
    } else {
        update_query("tblclients", array("pwresetkey" => $resetkey, "pwresetexpiry" => time() + 2 * 60 * 60), array("id" => $userid));
    }

    $reseturl = ($CONFIG['SystemSSLURL'] ? $CONFIG['SystemSSLURL'] : $CONFIG['SystemURL']);
    $reseturl .= "/pwreset.php?key=" . $resetkey;
    sendMessage("Password Reset Validation", $userid, array("pw_reset_url" => $reseturl, "contactid" => $contactid));
    logActivity("Password Reset Requested", $userid);
}

function doResetPWKeyCheck($key) {
    global $_LANG;

    $result = select_query_i("tblclients", "id,pwresetexpiry", array("pwresetkey" => $key));
    $data = mysqli_fetch_array($result);
    $userid = $data['id'];
    $pwresetexpiry = $data['pwresetexpiry'];

    if (!$userid) {
        $result = select_query_i("tblcontacts", "id,userid,pwresetexpiry", array("pwresetkey" => $key));
        $data = mysqli_fetch_array($result);
        $contactid = $data['id'];
        $userid = $data['userid'];
        $pwresetexpiry = $data['pwresetexpiry'];
    }


    if (!$userid) {
        return $_LANG['pwresetkeyinvalid'];
    }


    if ($pwresetexpiry < time()) {
        return $_LANG['pwresetkeyexpired'];
    }
}

function doResetPW($key, $newpw, $confirmpw) {
    global $_LANG;

    $newpw = html_entity_decode($newpw);
    $confirmpw = html_entity_decode($confirmpw);

    if (!$key) {
        return $_LANG['pwresetemailrequired'];
    }

    $result = select_query_i("tblclients", "id,email,pwresetexpiry", array("pwresetkey" => $key));
    $data = mysqli_fetch_array($result);
    $userid = $data['id'];
    $email = $data['email'];
    $pwresetexpiry = $data['pwresetexpiry'];

    if (!$userid) {
        $result = select_query_i("tblcontacts", "id,email,userid,pwresetexpiry", array("pwresetkey" => $key));
        $data = mysqli_fetch_array($result);
        $contactid = $data['id'];
        $userid = $data['userid'];
        $pwresetexpiry = $data['pwresetexpiry'];
        $email = $data['email'];
    }


    if (!$userid) {
        return $_LANG['pwresetemailnotfound'];
    }


    if ($pwresetexpiry < time()) {
        return $_LANG['pwresetkeyexpired'];
    }

    $validate = new RA_Validate();

    if ($validate->validate("required", "newpw", "ordererrorpassword")) {
        if ($validate->validate("pwstrength", "newpw", "pwstrengthfail")) {
            if ($validate->validate("required", "confirmpw", "clientareaerrorpasswordconfirm")) {
                $validate->validate("match_value", "newpw", "clientareaerrorpasswordnotmatch", "confirmpw");
            }
        }
    }


    if (!$validate->hasErrors()) {
        if ($contactid) {
            update_query("tblcontacts", array("password" => generateClientPW($newpw), "pwresetkey" => "", "pwresetexpiry" => ""), array("id" => $contactid));
        } else {
            update_query("tblclients", array("password" => generateClientPW($newpw), "pwresetkey" => "", "pwresetexpiry" => ""), array("id" => $userid));
        }

        run_hook("ClientChangePassword", array("userid" => $userid, "password" => $newpw));
        logActivity("Password Reset Completed", $userid);
        sendMessage("Password Reset Confirmation", $userid, array("contactid" => $contactid));
        validateClientLogin($email, $newpw);
        redir("success=true", "pwreset.php");
    }

    return $validate->getHTMLErrorOutput();
}

?>
