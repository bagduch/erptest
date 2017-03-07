<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Edit Clients Details");
$aInt->requiredFiles(array("clientfunctions"));
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);

if ($action == "save") {
    check_token("RA.admin.default");
    checkPermission("Edit Clients Details");

    if ($subaccount) {
        $subaccount = "1";
        $result = select_query_i("tblclients", "COUNT(*)", array("email" => $email));
        $data = mysqli_fetch_array($result);
        $result = select_query_i("tblcontacts", "COUNT(*)", array("email" => $email, "id" => array("sqltype" => "NEQ", "value" => $contactid)));
        $data2 = mysqli_fetch_array($result);

        if ($data[0] + $data2[0]) {
            $querystring = "";
            foreach ($_REQUEST as $k => $v) {

                if (!is_array($v) && $k != "action") {
                    $querystring .= "&" . $k . "=" . urlencode($v);
                    continue;
                }
            }

            redir("error=" . $_LANG['ordererroruserexists'] . $querystring);
            exit();
        }
    } else {
        $subaccount = "0";
    }


    if ($domainemails) {
        $domainemails = 1;
    }


    if ($generalemails) {
        $generalemails = 1;
    }


    if ($invoiceemails) {
        $invoiceemails = 1;
    }


    if ($productemails) {
        $productemails = 1;
    }


    if ($supportemails) {
        $supportemails = 1;
    }


    if ($affiliateemails) {
        $affiliateemails = 1;
    }


    if ($contactid == "addnew") {
        if ($password && $password != $aInt->lang("fields", "password")) {
            $array['password'] = generateClientPW($password);
        }

        $contactid = addContact($userid, $firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password, $permissions, $generalemails, $productemails, $domainemails, $invoiceemails, $supportemails);
        logActivity("Added Contact - User ID: " . $userid . " - Contact ID: " . $contactid);
    } else {
        logActivity("Contact Modified - User ID: " . $userid . " - Contact ID: " . $contactid);
        $oldcontactdata = get_query_vals("tblcontacts", "", array("userid" => $_SESSION['uid'], "id" => $id));

        if ($permissions) {
            $permissions = implode(",", $permissions);
        }

        $table = "tblcontacts";
        $array = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber, "mobilenumber" => $mobilenumber, "subaccount" => $subaccount, "permissions" => $permissions, "domainemails" => $domainemails, "generalemails" => $generalemails, "invoiceemails" => $invoiceemails, "productemails" => $productemails, "supportemails" => $supportemails, "affiliateemails" => $affiliateemails);

        if ($password && $password != $aInt->lang("fields", "entertochange")) {
            $array['password'] = generateClientPW($password);
        }

        $where = array("id" => $contactid);
        update_query($table, $array, $where);
        run_hook("ContactEdit", array_merge(array("userid" => $userid, "contactid" => $contactid, "olddata" => $oldcontactdata), $array));
    }

    redir("userid=" . $userid . "&contactid=" . $contactid);
    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("tblcontacts", array("id" => $contactid, "userid" => $userid));
    update_query("tblclients", array("billingcid" => ""), array("id" => $userid, "billingcid" => $contactid));
    run_hook("ContactDelete", array("userid" => $userid, "contactid" => $contactid));
    redir("userid=" . $userid);
    exit();
}



if ($error) {
    infoBox($aInt->lang("global", "validationerror"), $error);
}

$result = select_query_i("tblcontacts", "", array("userid" => $userid), "firstname` ASC,`lastname", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $contactlistid = $data['id'];

    if (!$contactid) {
        $contactid = $contactlistid;
    }

    $contactlistfirstname = $data['firstname'];
    $contactlistlastname = $data['lastname'];
    $contactlistemail = $data['email'];
    $contactlistop.= "<option value=\"" . $contactlistid . "\"";

    if ($contactlistid == $contactid) {
        $contactlistop.= " selected";
    }

    $contactlistop.= ">" . $contactlistfirstname . " " . $contactlistlastname . " - " . $contactlistemail . "</option>";
}

ob_start();

if (!$contactid) {
    $contactid = "addnew";
}

$contactlistop.= "<option value=\"addnew\"";

if ($contactid == "addnew") {
    $contactlistop.= " selected";
}

$contactlistop.= ">";
$contactlistop.= $aInt->lang("global", "addnew");
$contactlistop.= "</option>";
$aInt->deleteJSConfirm("deleteContact", "clients", "deletecontactconfirm", "?action=delete&userid=" . $userid . "&contactid=");

if ($resetpw) {
    check_token("RA.admin.default");
    sendMessage("Automated Password Reset", $userid, array("contactid" => $contactid));
    infoBox($aInt->lang("clients", "resetsendpassword"), $aInt->lang("clients", "passwordsuccess"));
    echo $infobox;
}


if ($contactid && $contactid != "addnew") {
    $result = select_query_i("tblcontacts", "", array("userid" => $userid, "id" => $contactid));
    $data = mysqli_fetch_assoc($result);
    $data['permissions'] = explode(",", $data['permissions']);
    $aInt->assign("cdata", $data);
    $contactid = $data['id'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $companyname = $data['companyname'];
    $email = $data['email'];
    $address1 = $data['address1'];
    $address2 = $data['address2'];
    $city = $data['city'];
    $state = $data['state'];
    $postcode = $data['postcode'];
    $country = $data['country'];
    $phonenumber = $data['phonenumber'];
    $mobilenumber = $data['mobilenumber'];
    $subaccount = $data['subaccount'];
    $password = $data['password'];
    $permissions = explode(",", $data['permissions']);
    $generalemails = $data['generalemails'];
    $productemails = $data['productemails'];
    $domainemails = $data['domainemails'];
    $invoiceemails = $data['invoiceemails'];
    $supportemails = $data['supportemails'];
    $affiliateemails = $data['affiliateemails'];
    $password = $aInt->lang("fields", "entertochange");
}

if (!is_array($permissions)) {
    $permissions = array();
}

if ($contactid != "addnew") {
    
}
include "../includes/countries.php";
$aInt->assign("userid", $userid);
$aInt->assign("contactid", $contactid);
$aInt->assign("token", generate_token("plain"));
$aInt->assign("countrydrop", getCountriesDropDown($country, "", "12"));
$aInt->assign("infobox", $infobox);
$aInt->assign("contactlist", $contactlistop);
$aInt->assign("PHP_SELF", $_SERVER['PHP_SELF']);
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->template = "client/contacts";
$aInt->display();
?>
