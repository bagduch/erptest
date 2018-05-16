<?php

/** RA - Version 0.1 **/
function random_password($length = 8) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Add New Client", false);
$aInt->title = $aInt->lang("clients", "addnew");
$aInt->sidebar = "clients";
$aInt->icon = "clientsadd";
$aInt->requiredFiles(array("clientfunctions", "servicefunctions", "customfieldfunctions", "gatewayfunctions"));

if ($action == "add") {
    check_token("RA.admin.default");
    $result = select_query_i("tblclients", "COUNT(*)", array("email" => $email));
    $data = mysqli_fetch_array($result);

    if ($data[0]) {
        infoBox($aInt->lang("clients", "duplicateemail"), $aInt->lang("clients", "duplicateemailexp"), "error");
    } elseif (!trim($email) && !$cccheck) {
        infoBox($aInt->lang("global", "validationerror"), $aInt->lang("clients", "invalidemail"), "error");
    } elseif (!$cccheck && trim($email)) {
        $emaildomain = explode("@", $email, 2);
        $emaildomain = $emaildomain[1];

        if (!preg_match('/^([a-zA-Z0-9&\'.])+([\.a-zA-Z0-9+_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
            $errormessage .= "<li>" . $_LANG['clientareaerroremailinvalid'];
            infoBox($aInt->lang("global", "validationerror"), $aInt->lang("clients", "invalidemail"), "error");
        } else {
            $query = "subaccount=1 AND email='" . mysqli_real_escape_string($email) . "'";
            $result = select_query_i("tblcontacts", "COUNT(*)", $query);
            $data = mysqli_fetch_array($result);

            if ($data[0]) {
                infoBox($aInt->lang("clients", "duplicateemail"), $aInt->lang("clients", "duplicateemailexp"), "error");
            }
        }
    } else {
        
    }
    if (!$infobox) {
        $_SESSION['currency'] = $currency;
        $userid = addClient(
                $firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $mobilenumber, $password, $dateofbirth, $sendemail, array(
            "notes" => $notes,
            "status" => $status,
            "credit" => $credit,
            "taxexempt" => $taxexempt,
            "latefeeoveride" => $latefeeoveride,
            "overideduenotices" => $overideduenotices,
            "language" => "en",
            "billingcid" => $billingcid,
            "lastlogin" => "00000000000000",
            "groupid" => $groupid,
            "separateinvoices" => $separateinvoices,
            "disableautocc" => $disableautocc,
            "defaultgateway" => $paymentmethod
                )
        );
        unset($_SESSION['uid']);
        unset($_SESSION['upw']);
        echo json_encode(array("success" => true, "userid" => $userid));
        exit();
    } else {
        echo json_encode(array("success" => false, "error" => $infobox));
        exit();
    }
}

releaseSession();




include "../includes/countries.php";
$countrydrop = getCountriesDropDown("New Zealand", "", 13);

$langoption = "";
foreach ($ra->getValidLanguages() as $lang) {
    $langoption .= "<option value = \"" . $lang . "\">" . ucfirst($lang) . "</option>";
}



if ($latefeeoveride == "on") {
    //  echo " checked";
}

$paymentmethoddrop = paymentMethodsSelection($aInt->lang("clients", "changedefault"), 20);

if ($overideduenotices == "on") {
    //  echo " checked";
}

$result = select_query_i("tblcontacts", "", array("userid" => $userid), "firstname` ASC,`lastname", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $contactoption .= "<option value=\"" . $data['id'] . "\"";

    if ($data['id'] == $billingcid) {
        $contactoption .= " selected";
    }

    $contactoption .= ">" . $data['firstname'] . " " . $data['lastname'] . "</option>";
}


$result = select_query_i("tblcurrencies", "id,code,`default`", "", "code", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $currencyoption .= "<option value=\"" . $data['id'] . "\"";

    if (($currency && $data['id'] == $currency) || (!$currency && $data['default'])) {
        $currencyoption .= " selected";
    }

    $currencyoption .= ">" . $data['code'] . "</option>";
}

$result = select_query_i("tblclientgroups", "", "", "groupname", "ASC");
while ($data = mysqli_fetch_assoc($result)) {
    $groupoption .= "<option style='background-color:" . $data['groupcolour'] . "' value='" . $data['id'] . "'";
    if ($data['id'] == $groupid) {
        $groupoption .= " selected";
    }

    $groupoption .= ">" . $data['groupname'] . "</option>";
}

$taxindex = 27;
$customfields = getCustomFields("client", "", $userid, "on", "");
$x = 0;
foreach ($customfields as $customfield) {
    ++$x;
    //  echo "<td class=\"fieldlabel\">" . $customfield['name'] . "</td><td class=\"fieldarea\">" . str_replace(array("<input", "<select", "<textarea"), array("<input tabindex=\"" . $taxindex . "\"", "<select tabindex=\"" . $taxindex . "\"", "<textarea tabindex=\"" . $taxindex . "\""), $customfield['input']) . "</td>";

    if ($x % 2 == 0 || $x == count($customfields)) {
        //    echo "</tr><tr>";
    }

    ++$taxindex;
}

$clientfields = getClientfieldshtml();
$clientfieldshtml = array();
foreach ($clientfields as $key => $row) {

    if ($key % 2 == 1 && $key != 0) {
        $clientfieldshtml[$key - 1][] = $clientfields[$key];
    } else {
        $clientfieldshtml[$key][] = $clientfields[$key];
    }
}

include "../includes/countries.php";
$status = array("Active", "Inactive", "Closed");


$aInt->assign("password", random_password(10));
$aInt->assign("clientfields", $clientfieldshtml);
$aInt->assign("status", $status);
$aInt->assign("infobox", $infobox);
$aInt->assign("coutries", $countries);
$aInt->assign("formurl", $_SERVER["PHP_SELF"]);
$aInt->assign('contactoption', $contactoption);
$aInt->assign("currencyoption", $currencyoption);
$aInt->assign("securityoption", $securityoption);
$aInt->assign("groupoption", $groupoption);
$aInt->assign("countrydrop", $countrydrop);
$aInt->assign("paymentmethoddrop", $paymentmethoddrop);
$aInt->assign("langoption", $langoption);
//$aInt->content = $content;


$aInt->template = 'client/clientsadd';
$aInt->display();
?>
