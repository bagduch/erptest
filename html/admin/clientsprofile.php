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
$aInt = new RA_Admin("Edit Clients Details", false);
$aInt->requiredFiles(array("clientfunctions", "customfieldfunctions", "gatewayfunctions"));
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);


if ($ra->get_req_var("save")) {
    check_token("RA.admin.default");
    $email = trim($email);
    $password = trim($password);
    $result = select_query_i("tblclients", "COUNT(*)", "email='" . db_escape_string($email) . "' AND id!='" . db_escape_string($userid) . "'");
    $data = mysqli_fetch_array($result);

    if ($data[0]) {
        redir("userid=" . $userid . "&emailexists=1");
        exit();
    } else {
        $errormessage = "";
        run_hook("ClientDetailsValidation", array());
        $_SESSION['profilevalidationerror'] = $errormessage;
        $oldclientsdetails = getClientsDetails($userid);
        $table = "tblclients";
        $array = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber, "currency" => $_POST['currency'], "notes" => $notes, "status" => $status, "taxexempt" => $taxexempt, "latefeeoveride" => $latefeeoveride, "overideduenotices" => $overideduenotices, "separateinvoices" => $separateinvoices, "disableautocc" => $disableautocc, "emailoptout" => $emailoptout, "overrideautoclose" => $overrideautoclose, "language" => $language, "billingcid" => $billingcid, "groupid" => $groupid);

        if (!$twofaenabled) {
            $array['authmodule'] = "";
            $array['authdata'] = "";
        }

        $where = array("id" => $userid);
        update_query($table, $array, $where);

        if ($password && $password != $aInt->lang("fields", "entertochange")) {
            if ($CONFIG['NOMD5']) {
                if ($password != decrypt($oldclientsdetails['password'])) {
                    update_query("tblclients", array("password" => generateClientPW($password)), array("id" => $userid));
                    run_hook("ClientChangePassword", array("userid" => $userid, "password" => $password));
                }
            } else {
                update_query("tblclients", array("password" => generateClientPW($password)), array("id" => $userid));
                run_hook("ClientChangePassword", array("userid" => $userid, "password" => $password));
            }
        }

        $customfields = getClientfieldshtml($userid);
        foreach ($customfields as $k => $v) {
            $k = $v['id'];
            $customfieldsarray[$k] = $_POST['customfield'][$k];
        }

        $updatefieldsarray = array("firstname" => "First Name", "lastname" => "Last Name", "companyname" => "Company Name", "email" => "Email Address", "address1" => "Address 1", "address2" => "Address 2", "city" => "City", "state" => "State", "postcode" => "Postcode", "country" => "Country", "phonenumber" => "Phone Number", "billingcid" => "Billing Contact");
        $updatedtickboxarray = array("latefeeoveride" => "Late Fees Override", "overideduenotices" => "Overdue Notices", "taxexempt" => "Tax Exempt", "separateinvoices" => "Separate Invoices", "disableautocc" => "Disable CC Processing", "emailoptout" => "Marketing Emails Opt-out", "overrideautoclose" => "Auto Close");
        $changelist = array();
        foreach ($updatefieldsarray as $field => $displayname) {

            if ($array[$field] != $oldclientsdetails[$field]) {
                $changelist[] = "" . $displayname . ": '" . $oldclientsdetails[$field] . "' to '" . $array[$field] . "'";
                continue;
            }
        }

        foreach ($updatedtickboxarray as $field => $displayname) {
            $oldfield = ($oldclientsdetails[$field] ? "Enabled" : "Disabled");
            $newfield = ($array[$field] ? "Enabled" : "Disabled");

            if ($oldfield != $newfield) {
                $changelist[] = "" . $displayname . ": '" . $oldfield . "' to '" . $newfield . "'";
                continue;
            }
        }

        saveClientFields($userid, $customfieldsarray);
        clientChangeDefaultGateway($userid, $paymentmethod);

        if (!count($changelist)) {
            $changelist[] = "No Changes";
        }

        logActivity("Client Profile Modified - " . implode(", ", $changelist) . (" - User ID: " . $userid), $userid);
        run_hook("AdminClientProfileTabFieldsSave", $_REQUEST);
        run_hook("ClientEdit", array_merge(array("userid" => $userid, "olddata" => $oldclientsdetails), $array));
        redir("userid=" . $userid . "&success=true");
        exit();
    }
}

releaseSession();

if ($ra->get_req_var("emailexists")) {
    infoBox($aInt->lang("clients", "duplicateemail"), $aInt->lang("clients", "duplicateemailexp"), "error");
} else {
    if ($_SESSION['profilevalidationerror']) {
        infoBox($aInt->lang("global", "validationerror"), $_SESSION['profilevalidationerror'], "error");
        unset($_SESSION['profilevalidationerror']);
    } else {
        if ($ra->get_req_var("success")) {
            infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"), "success");
        } else {
            if ($ra->get_req_var("resetpw")) {
                check_token("RA.admin.default");
                sendMessage("Automated Password Reset", $userid);
                infoBox($aInt->lang("clients", "resetsendpassword"), $aInt->lang("clients", "passwordsuccess"), "success");
            }
        }
    }
}


$clientsdetails = getClientsDetails($userid);


if ($CONFIG['NOMD5']) {
    $password = decrypt($clientsdetails['password']);
} else {
    $password = $aInt->lang("fields", "entertochange");
}

$result = select_query_i("tblclientgroups", "", "", "groupname", "ASC");

$groupid = array();
while ($data = mysqli_fetch_assoc($result)) {
    $groupid[$data['id']] = array(
        'groupname' => $data['groupname'],
        'groupcolour' => $data['groupcolour']
    );
}


$status = array("Active", "Inactive", "Archived");
include "../includes/countries.php";


$billingcid = array();
$result = select_query_i("tblcontacts", "", array("userid" => $userid), "firstname` ASC,`lastname", "ASC");
while ($data = mysqli_fetch_array($result)) {
    $billingcid[$data['id']] = $data;
}
$result = select_query_i("tblcurrencies", "id,code,`default`", "", "code", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $currencyoption .= "<option value=\"" . $data['id'] . "\"";

    if (($currency && $data['id'] == $currency) || (!$currency && $data['default'])) {
        $currencyoption .= " selected";
    }

    $currencyoption .= ">" . $data['code'] . "</option>";
}


$clientfields = getClientfieldshtml($userid);
$clientfieldshtml = array();
foreach ($clientfields as $key => $row) {

    if ($key % 2 == 1 && $key != 0) {
        $clientfieldshtml['left'][] = $clientfields[$key];
    } else {
        $clientfieldshtml["right"][] = $clientfields[$key];
    }
}

$aInt->assign("infobox", $infobox);
$aInt->assign("clientfields", $clientfieldshtml);
$aInt->assign("currencyoption", $currencyoption);
$aInt->assign("paymmentmethod", paymentMethodsSelection($aInt->lang("clients", "changedefault"), 21));
$aInt->assign("billingcid", $billingcid);
$aInt->assign("coutries", $countries);
$aInt->assign("status", $status);
$aInt->assign('groupid', $groupid);
$aInt->assign('clientsdetails', $clientsdetails);
$aInt->template = "clientsprofile";
$aInt->display();
?>
