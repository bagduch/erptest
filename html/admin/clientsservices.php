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
$id = (int) $ra->get_req_var("id") ?: (int) $ra->get_req_var("hostingid");
$userid = (int) $ra->get_req_var("userid");
$aid = $ra->get_req_var("aid");
$action = $ra->get_req_var("action");
// Check if modifications are being made
$modop = $ra->get_req_var("modop");
if ($modop) {
    checkPermission("Perform Server Operations");
}
$clientdata = new RA_ClientService($userid, $id);
$id = $clientdata->id;
$userid = $clientdata->userid;



//echo "<pre>",  print_r($clientdata->servicedata,1),"</pre>";
if ($clientdata->errorbox != "" && $clientdata->errorbox != "No Addons") {
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
$servicefield = getServiceCustomFields($clientdata->servicedata['packageid'], $clientdata->servicedata['id']);
$len = count($servicefield);
$firsthalf = array_slice($servicefield, 0, $len / 2);
$secondhalf = array_slice($servicefield, $len / 2);
if ($_POST['frm1']) {
    check_token("RA.admin.default");
    if ($_POST['addonid']) {
        $addonid = $clientdata->addaddon($_POST['addonid'], $_POST['paymentmethod']);
        logActivity("Add Addon - User ID: " . $userid . " - Addon ID: " . $addonid, $userid, $id);
        redir("userid=" . $userid);
    }
    $logDetail = "";
    foreach ($servicefield as $key => $row) {
        if ($_POST['customefield'][$key] != $row['value']) {
            delete_query("tblcustomfieldsvalues", array("cfid" => $key, "relid" => $id));
            insert_query("tblcustomfieldsvalues", array("value" => $_POST['customefield'][$key], "cfid" => $key, "relid" => $id));
            $logDetail .= "Customer Field '" . $servicefield[$key]['fieldname'] . "' change from '" . $row['value'] . "' to '" . $_POST['customefield'][$key] . "' ";
        }
    }


    $data = array(
        "packageid" => $_POST['packageid'],
        "description" => $_POST['description'],
        "servicestatus" => $_POST['status'],
        "regdate" => toMySQLDate($_POST['regdate']),
        "amount" => $_POST['amount'],
        "firstpaymentamount" => $_POST['firstpaymentamount'],
        "nextduedate" => toMySQLDate($_POST['nextduedate']),
        "billingcycle" => $_POST['billingcycle'],
        "paymentmethod" => $_POST['paymentmethod'],
        "lastupdate" => "now()"
    );
    if (isset($_POST['account'])) {
        insert_query("tblnotes", array(
            "rel_id" => $_POST['account'],
            "adminid" => $_SESSION['adminid'],
            "type" => $_POST['rel_type'],
            "created" => "now()",
            "duedate" => $_POST['duedate'],
            "flag" => $_POST['imports'] == "" ? 0 : 1,
            "assignto" => $_POST['assign'],
            "modified" => "now()",
            "note" => $_POST['notes'],
            "sticky" => isset($sticky) ? 0 : 1));
    }
}
foreach ($data as $key => $row) {
    if ($clientdata->servicedata[$key] != $row && $key != "lastupdate") {
        $logDetail .= "Field '" . $key . "' update value from '" . $clientdata->servicedata[$key] . "' to '" . $row . "' ";
    }
}

if ($logDetail != "") {
    $clientdata->updateService($data, $id);
    logActivity("Account Update - User ID: " . $userid . " - Update Account ID: " . $id . " " . $logDetail, $userid, $id);
    redir("userid=" . $userid . "&id=" . $id);
}


if ($action == "delete") {
    check_token("RA.admin.default");
    checkPermission("Delete Clients Products/Services");
    run_hook("ServiceDelete", array("userid" => $userid, "serviceid" => $id));
    delete_query("tblcustomerservices", array("id" => $id));
    delete_query("tblserviceaddons", array("hostingid" => $id));
    //  delete_query("tblcustomerservicesconfigoptions", array("relid" => $id));
    full_query_i("DELETE FROM tblcustomfieldsvalues WHERE relid='" . db_escape_string($id) . "'");
    logActivity("Deleted Service - User ID: " . $userid . " - Service ID: " . $id, $userid, $id);
    redir("userid=" . $userid);
}
if ($action == "deladdon") {
    check_token("RA.admin.default");
    checkPermission("Delete Clients Products/Services");
    run_hook("AddonDeleted", array("id" => $aid));
    delete_query("tblcustomerservices", array("id" => $aid));
    logActivity("Deleted Addon - User ID: " . $userid . " - Service ID: " . $id . " - Addon ID: " . $aid, $userid, $id);
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

$servicesarray = getServiceAndProductdata("service", $userid);
$accountlog = array();
$resutlt = select_query_i("tblactivitylog", "*", array("account_id" => $id), "date", "DESC");
while ($data = mysqli_fetch_array($resutlt)) {

    $accountlog[] = $data;
}

$accountinvoice = array();



$result = select_query_i("tbladmins", "");
while ($data = mysqli_fetch_assoc($result)) {
    $templatevars['adminlist'][] = $data;
}

$query = "select tbn.*,CONCAT(tba.firstname,' ',tba.lastname) as name from tblnotes as tbn 
INNER JOIN tbladmins AS tba on (tba.id=tbn.adminid)
LEFT JOIN tblorders as tbo on (tbo.id=tbn.rel_id and tbn.type='order')
LEFT JOIN tblcustomerservices as tbcs on (tbcs.id=tbn.rel_id  and tbn.type='account')
where tbn.rel_id = " . $id . " ORDER BY tbn.flag DESC";
$result = full_query_i($query);
while ($data = mysqli_fetch_array($result)) {
    $noteid = $data['id'];
    $duedate = $data['duedate'];
    $created = $data['created'];
    $modified = $data['modified'];
    $note = $data['note'];
    $admin = $data['name'];
    $assigned = $data['assignee'];

    $note = nl2br($note);
    $note = autoHyperLink($note);
    $created = fromMySQLDate($created, "time");
    $modified = fromMySQLDate($modified, "time");
    if ($data['flag']) {
        $importantnote = "<img src=\"images/highpriority.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("clientsummary", "importantnote") . "\">";
    } else {
        $importantnote = "<img src=\"images/success.png\" width=\"16\" />";
    }

    $tabledata[] = array($data['type'], $created, $note, $admin, $assigned, $duedate, $modified, $importantnote, "<a href=\"" . $PHP_SELF . "?userid=" . $userid . "&action=edit&id=" . $noteid . "\"class=\"btn btn-success editnotes\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $noteid . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>",
        $noteid);
}
$aInt->assign("tabledata", $tabledata);
$aInt->assign("adminlist", $templatevars['adminlist']);
$aInt->assign("service", array("Pending", "Active", "Draft", "Suspended", "Terminated", "Cancelled", "Fraud"));
$aInt->assign("userid", $userid);
$aInt->assign("accountlog", $accountlog);
$aInt->assign("token", get_token());
$aInt->assign('alladdons', $clientdata->addons);
$aInt->assign("servicesarr", $servicesarray['servicesarr']);
$aInt->assign("totalservice", $servicesarray['total']);
$aInt->assign("userarray", $servicesarray['userarray']);
$aInt->assign('lang', $lang);
$aInt->assign('emaildropdown', $emailarr);
$aInt->assign('contentbox', $contentbox);
$aInt->assign('ordertable', $ordertable);
$aInt->assign('content', $content);
$aInt->assign("services", $clientdata->servicedata);
$aInt->assign("status", $aInt->productStatusDropDown($clientdata->servicedata['servicestatus']));
$aInt->assign("promo", $clientdata->getPromocode());
$aInt->assign("servicefield", !empty($firsthalf) ? $firsthalf : FALSE);
$aInt->assign("servicefieldnd", $secondhalf);
$aInt->assign("servicedrop", $aInt->productDropDown($clientdata->servicedata['packageid']));
$aInt->assign("billingcycle", $aInt->cyclesDropDown($clientdata->servicedata['billingcycle'], "", "Free"));
$aInt->assign("paymentmethod", paymentMethodsSelection($clientdata->servicedata['paymentmethod']));
if ($userid) {
    $aInt->template = "clientsservices/view";
}


$aInt->display();
?>