<?php

/**
 *
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Custom Client Fields");
$aInt->title = $aInt->lang("customfields", "clienttitle");
$aInt->sidebar = "config";
$aInt->icon = "customfields";
$aInt->helplink = "Custom Fields";
$action = $ra->get_req_var("action");
$id = $ra->get_req_var("id");

if ($action == "save") {
    check_token("RA.admin.default");

    if ($fieldname) {
        foreach ($fieldname as $fid => $value) {
            update_query("tblcustomfields", array("fieldname" => $value, "fieldtype" => $fieldtype[$fid], "description" => $description[$fid], "fieldoptions" => $fieldoptions[$fid], "regexpr" => html_entity_decode($regexpr[$fid]), "adminonly" => $adminonly[$fid], "required" => $required[$fid], "showorder" => $showorder[$fid], "showinvoice" => $showinvoice[$fid], "sortorder" => $sortorder[$fid]), array("id" => $fid));
        }
    }

    if ($addfieldname) {
        insert_query("tblcustomfields", array("type" => "client", "fieldname" => $addfieldname, "fieldtype" => $addfieldtype, "description" => $adddescription, "fieldoptions" => $addfieldoptions, "regexpr" => html_entity_decode($addregexpr), "adminonly" => $addadminonly, "required" => $addrequired, "showorder" => $addshoworder, "showinvoice" => $addshowinvoice, "sortorder" => $addsortorder));
    }

    redir("success=true");
} else {
    if ($action == "delete") {
        check_token("RA.admin.default");
        delete_query("tblcustomfields", array("id" => $id));
        delete_query("tblcustomfieldsvalues", array("fieldid" => $id));
        redir("deleted=true");
    }
}

$aInt->deleteJSConfirm("doDelete", "customfields", "delsure", $_SERVER['PHP_SELF'] . "?action=delete&id=");


if ($ra->get_req_var("success")) {
    infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
}

//echo $infobox;

$result = select_query_i("tblcustomfields", "", array("type" => "client"), "sortorder` ASC,`id", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $fid = $data['id'];
    $fieldname = $data['fieldname'];
    $fieldtype = $data['fieldtype'];
    $description = $data['description'];
    $fieldoptions = $data['fieldoptions'];
    $regexpr = $data['regexpr'];
    $adminonly = $data['adminonly'];
    $required = $data['required'];
    $showorder = $data['showorder'];
    $showinvoice = $data['showinvoice'];
    $sortorder = $data['sortorder'];

    $cfid[] = $data;
}


$aInt->assign('cfids', $cfid);
$aInt->assign('infobox', $infobox);
$aInt->template = "configcustomfields";
$aInt->display();
?>