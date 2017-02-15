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
$aInt = new RA_Admin("Configure Client Groups");
$aInt->title = $aInt->lang("clientgroups", "title");
$aInt->sidebar = "config";
$aInt->icon = "clients";
$aInt->helplink = "Client Groups";
$menuselect = "$('#menu').multilevelpushmenu('expand','Customers');";
if ($action == "savegroup") {
    check_token("RA.admin.default");
    insert_query("tblclientgroups", array("groupname" => $groupname, "groupcolour" => $groupcolour, "discountpercent" => $discountpercent, "susptermexempt" => $susptermexempt, "separateinvoices" => $separateinvoices));
    header("Location: configclientgroups.php?added=true");
    exit();
}


if ($action == "updategroup") {
    check_token("RA.admin.default");
    update_query("tblclientgroups", array("groupname" => $groupname, "groupcolour" => $groupcolour, "discountpercent" => $discountpercent, "susptermexempt" => $susptermexempt, "separateinvoices" => $separateinvoices), array("id" => $groupid));
    header("Location: configclientgroups.php?update=true");
    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    $result = select_query_i("tblclients", "", array("groupid" => $id));
    $numaccounts = mysqli_num_rows($result);

    if (0 < $numaccounts) {
        header("Location: configclientgroups.php?deleteerror=true");
        exit();
    } else {
        delete_query("tblclientgroups", array("id" => $id));
        header("Location: configclientgroups.php?deletesuccess=true");
        exit();
    }
}
if ($ation == "") {
    $aInt->sortableTableInit("nopagination");
    $result = select_query_i("tblclientgroups", "", "");

    while ($data = mysqli_fetch_assoc($result)) {
        $suspterm = ($data['susptermexempt'] == "on" ? $aInt->lang("global", "yes") : $aInt->lang("global", "no"));
        $separateinv = ($data['separateinvoices'] == "on" ? $aInt->lang("global", "yes") : $aInt->lang("global", "no"));
        $groupcol = ($data['groupcolour'] ? "<div style=\"width:75px;background-color:" . $data['groupcolour'] . "\">" . $aInt->lang("clientgroups", "sample") . "</div>" : "");
        $tabledata[] = array($data['groupname'], $groupcol, $data['discountpercent'], $suspterm, $separateinv, "<a href=\"" . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $data['id'] . "\" class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $data['id'] . "');return false\" class=\"btn btn-danger\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    $table = $aInt->sortableTable(array($aInt->lang("clientgroups", "groupname"), $aInt->lang("clientgroups", "groupcolour"), $aInt->lang("clientgroups", "perdiscount"), $aInt->lang("clientgroups", "susptermexempt"), $aInt->lang("clients", "separateinvoices"), "", ""), $tabledata);
    $setaction = ($action == "edit" ? "updategroup" : "savegroup");
    $url = $PHP_SELF . "?action=" . $setaction;
    $groupname;
    $groupcolour;
    $discountpercent;
    $susptermexempt;
    $separateinvoices;
    $template = "client/clientgroup";
}

if ($action == "edit") {
    $result = select_query_i("tblclientgroups", "", array("id" => $id));
    $data = mysqli_fetch_assoc($result);
    $aInt->assign("groupid", $id);
    $aInt->assign("editdata", $data);
    $template = "client/clientgroupedit";
}

if ($added) {
    infoBox($aInt->lang("clientgroups", "addsuccess"), $aInt->lang("clientgroups", "addsuccessinfo"));
}

if ($update) {
    infoBox($aInt->lang("clientgroups", "editsuccess"), $aInt->lang("clientgroups", "editsuccessinfo"));
}


if ($deletesuccess) {
    infoBox($aInt->lang("clientgroups", "delsuccess"), $aInt->lang("clientgroups", "delsuccessinfo"));
}


if ($deleteerror) {
    infoBox($aInt->lang("global", "erroroccurred"), $aInt->lang("clientgroups", "delerrorinfo"));
}

$aInt->jquerycode = $jquerycode;
$aInt->jquerycode .=$menuselect;
$aInt->jscode = $jscode;
$aInt->assign("table", $table);
$aInt->assign("token", get_token('plain'));
$aInt->assign("url", $url);
$aInt->template = $template;
$aInt->display();
?>