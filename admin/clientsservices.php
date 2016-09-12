<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Clients Products/Services");
$aInt->requiredFiles(
        array(
            "clientfunctions",
            "gatewayfunctions",
            "modulefunctions",
            "servicefunctions",
            "orderfunctions",
            "customfieldfunctions",
            "configoptionsfunctions",
            "invoicefunctions",
            "processinvoices")
);
$aInt->inClientsProfile = true;
$id = (int) $ra->get_req_var("id") ? : (int) $ra->get_req_var("hostingid");
$userid = (int) $ra->get_req_var("userid");
$aid = $ra->get_req_var("aid");
$action = $ra->get_req_var("action");

// Check if modifications are being made
$modop = $ra->get_req_var("modop");
if ($modop) {
    checkPermission("Perform Server Operations");
}
$clientdata = new RA_ClientService($userid, $id);

if ($clientdata->errorbox != "") {
    $aInt->gracefulExit($clientdata->errorbox);
}

// if neither userid nor id are defined after that, I guess we just take the very first service?
if (!$userid && !$id) {
    $userid = get_query_val("tblclients", "id", "", "id", "ASC", "0,1");
}


// if only userid is supplied, then validate
if ($userid && !$id) {
    $aInt->valUserID($userid);
    if (!$userid) {
        $aInt->gracefulExit("Invalid User ID");
    }
}


//echo "<pre>", print_r($clientdata->servicedata, 1), "</pre>";
$frm = new RA_Form();

if ($frm->issubmitted()) {
    check_token("RA.admin.default");
    if ($_POST['addonid']) {
        $id = $clientdata->addaddon($_POST['addonid']);
        logActivity("Add Addon - User ID: " . $userid . " - Addon ID: " . $id, $userid);
        redir("userid=" . $userid);
    }
}


if ($action == "delete") {
    check_token("RA.admin.default");
    checkPermission("Delete Clients Products/Services");
    run_hook("ServiceDelete", array("userid" => $userid, "serviceid" => $id));
    delete_query("tblcustomerservices", array("id" => $id));
    delete_query("tblserviceaddons", array("hostingid" => $id));
    //  delete_query("tblcustomerservicesconfigoptions", array("relid" => $id));
    full_query_i("DELETE FROM tblcustomfieldsvalues WHERE relid='" . db_escape_string($id) . "'");
    logActivity("Deleted Service - User ID: " . $userid . " - Service ID: " . $id, $userid);
    redir("userid=" . $userid);
}


if ($action == "deladdon") {
    check_token("RA.admin.default");
    checkPermission("Delete Clients Products/Services");
    run_hook("AddonDeleted", array("id" => $aid));
    delete_query("tblcustomerservices", array("id" => $aid));
    logActivity("Deleted Addon - User ID: " . $userid . " - Service ID: " . $id . " - Addon ID: " . $aid, $userid);
    redir("userid=" . $userid . "&id=" . $id);
}


$adminbuttonarray = "";

if ($module) {
    if (!isValidforPath($module)) {
        exit("Invalid Server Module Name");
    }

    $modulepath = ROOTDIR . "/modules/servers/" . $module . "/" . $module . ".php";

    if (file_exists($modulepath)) {
        require_once $modulepath;
    }


    if (function_exists($module . "_AdminCustomButtonArray")) {
        $adminbuttonarray = call_user_func($module . "_AdminCustomButtonArray");
    }
}


if ($modop == "create") {
    check_token("RA.admin.default");
    $result = ServerCreateAccount($id);
    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=create&ajaxupdate=1");
}


if ($modop == "suspend") {
    check_token("RA.admin.default");
    $result = ServerSuspendAccount($id, $suspreason);
    wSetCookie("ModCmdResult", $result);

    if ($result == "success" && $suspemail == "true") {
        sendMessage("Service Suspension Notification", $id);
    }

    redir("userid=" . $userid . "&id=" . $id . "&act=suspend&ajaxupdate=1");
}


if ($modop == "unsuspend") {
    check_token("RA.admin.default");
    $result = ServerUnsuspendAccount($id);
    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=unsuspend&ajaxupdate=1");
}


if ($modop == "terminate") {
    check_token("RA.admin.default");
    $result = ServerTerminateAccount($id);
    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=terminate&ajaxupdate=1");
}


if ($modop == "changepackage") {
    check_token("RA.admin.default");
    $result = ServerChangePackage($id);
    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=updown&ajaxupdate=1");
}


if ($modop == "changepw") {
    check_token("RA.admin.default");
    $result = ServerChangePassword($id);
    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=pwchange&ajaxupdate=1");
}


if ($modop == "custom") {
    check_token("RA.admin.default");
    $result = ServerCustomFunction($id, $ac);

    if (substr($result, 0, 9) == "redirect|") {
        exit($result);
    }

    wSetCookie("ModCmdResult", $result);
    redir("userid=" . $userid . "&id=" . $id . "&act=custom&ajaxupdate=1");
}


if (in_array($ra->get_req_var("act"), array("create", "suspend", "unsuspend", "terminate", "updown", "pwchange", "custom"))) {

    if ($result = wGetCookie("ModCmdResult")) {
        if ($result != "success") {
            infoBox($aInt->lang("services", "moduleerror"), $result, "error");
        } else {
            infoBox($aInt->lang("services", "modulesuccess"), $aInt->lang("services", $act . "success"), "success");
        }
    }
}


if ($ra->get_req_var("success")) {
    infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
}

$regdate = fromMySQLDate($regdate);
$nextduedate = fromMySQLDate($nextduedate);
$overidesuspenduntil = fromMySQLDate($overidesuspenduntil);

if ($disklimit == "0") {
    $disklimit = $aInt->lang("global", "unlimited");
}


if ($bwlimit == "0") {
    $bwlimit = $aInt->lang("global", "unlimited");
}

$currency = getCurrency($userid);
$data = get_query_vals("tblcancelrequests", "id,type,reason", array("relid" => $id), "id", "DESC");
$cancelid = $data['id'];
$canceltype = $data['type'];
$autoterminatereason = $data['reason'];
$autoterminateendcycle = false;

if ($canceltype == "End of Billing Period") {
    $autoterminateendcycle = ($cancelid ? true : false);
}

$clientnotes = array();
$result = select_query_i("tblnotes", "tblnotes.*,(SELECT CONCAT(firstname,' ',lastname) FROM tbladmins WHERE tbladmins.id=tblnotes.adminid) AS adminuser", array("userid" => $userid, "sticky" => "1"), "modified", "DESC");

while ($data = mysqli_fetch_assoc($result)) {
    $data['created'] = fromMySQLDate($data['created'], 1);
    $data['modified'] = fromMySQLDate($data['modified'], 1);
    $data['note'] = autoHyperLink(nl2br($data['note']));
    $clientnotes[] = $data;
}



$aInt->assign('id', $id);
$aInt->assign('servicesarr', $servicesarr);


if ($cancelid) {
    if (!$infobox) {
        infoBox($aInt->lang("services", "cancrequest"), $aInt->lang("services", "cancrequestinfo") . "<br />" . $_ADMINLANG['fields']['reason'] . ": " . $autoterminatereason);
    }
}


$emailarr = array();
$emailarr['newmessage'] = $aInt->lang("emails", "newmessage");
$result = select_query_i("tblemailtemplates", "", array("type" => "product", "language" => ""), "name", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $messagename = $data['name'];
    $custom = $data['custom'];
    $emailarr[$messagename] = ($custom ? array("#efefef", $messagename) : $messagename);
}





if ($ra->get_req_var("ajaxupdate")) {
    $content = preg_replace('/(<form\W[^>]*\bmethod=(\'|"|)POST(\'|"|)\b[^>]*>)/i', '$1' . "\n" . generate_token(), $content);

//        echo $content;
//        exit();
} else {
    $content = "";
    $content .= $aInt->jqueryDialog("modcreate", $aInt->lang("services", "confirmcommand"), $aInt->lang("services", "createsure"), array($aInt->lang("global", "yes") => "runModuleCommand('create')", $aInt->lang("global", "no") => ""), "", "450");
    $content .= $aInt->jqueryDialog("modsuspend", $aInt->lang("services", "confirmcommand"), $aInt->lang("services", "suspendsure") . "<br /><div align=\"center\">" . $aInt->lang("services", "suspendreason") . ": <input type=\"text\" id=\"suspreason\" size=\"20\" /><br /><br /><input type=\"checkbox\" id=\"suspemail\" /> " . $aInt->lang("services", "suspendsendemail") . "</div>", array($aInt->lang("global", "yes") => "runModuleCommand('suspend')", $aInt->lang("global", "no") => ""), "", "450");
    $content .= $aInt->jqueryDialog("modunsuspend", $aInt->lang("services", "confirmcommand"), $aInt->lang("services", "unsuspendsure"), array($aInt->lang("global", "yes") => "runModuleCommand('unsuspend')", $aInt->lang("global", "no") => ""), "", "450");
    $content .= $aInt->jqueryDialog("modterminate", $aInt->lang("services", "confirmcommand"), $aInt->lang("services", "terminatesure"), array($aInt->lang("global", "yes") => "runModuleCommand('terminate')", $aInt->lang("global", "no") => ""), "", "450");
    $content .= $aInt->jqueryDialog("modchangepackage", $aInt->lang("services", "confirmcommand"), $aInt->lang("services", "chgpacksure"), array($aInt->lang("global", "yes") => "runModuleCommand('changepackage')", $aInt->lang("global", "no") => ""), "", "450");
    $content .= $aInt->jqueryDialog("delete", $aInt->lang("services", "deleteproduct"), $aInt->lang("services", "proddeletesure"), array($aInt->lang("global", "yes") => "window.location='" . $PHP_SELF . "?userid=" . $userid . "&id=" . $id . "&action=delete" . generate_token("link") . "'", $aInt->lang("global", "no") => ""), "180", "450");
}




$aInt->assign("userid", $userid);
$aInt->assign("token", get_token());
$aInt->assign('addons', $clientdata->addons);
$aInt->assign('lang', $lang);
$aInt->assign('emaildropdown', $emailarr);
$aInt->assign('userid', $userid);
$aInt->assign('contentbox', $contentbox);
$aInt->assign('ordertable', $ordertable);
$aInt->assign('content', $content);
$aInt->assign("services", $clientdata->servicedata);
$aInt->assign("status", $aInt->productStatusDropDown($clientdata->servicedata['servicestatus']));
$aInt->assign("promo", $clientdata->getPromocode());
$aInt->assign("servicefield", getServiceCustomFields($clientdata->servicedata['packageid'], $clientdata->servicedata['id']));
$aInt->assign("servicedrop", $aInt->productDropDown($clientdata->servicedata['packageid']));
$aInt->assign("billingcycle", $aInt->cyclesDropDown($clientdata->servicedata['billingcycle'], "", "Free"));
$aInt->assign("paymentmethod", paymentMethodsSelection($clientdata->servicedata['paymentmethod']));
$aInt->template = "clientsservices/view";
$aInt->display();
?>
