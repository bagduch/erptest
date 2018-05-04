<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Products/Services");
$aInt->title = "Client Custom Fields";
$aInt->sidebar = "config";
$aInt->icon = "configoptions";
$aInt->helplink = "Configurable Options";
$aInt->requiredFiles(array("clientfunctions", "servicefunctions", "customfieldfunctions", "gatewayfunctions"));
if ($action == "") {
    $result = select_query_i("tblclientfields", "");
    while ($data = mysqli_fetch_assoc($result)) {
        $clientfields[$data['cfid']] = $data;
    }
    $aInt->assign('datas', $clientfields);
}

if ($action = "deletefield") {
    if (isset($_POST['deletefieldid'])) {
        delete_query("tblclientfields", array('cfid' => $_POST['deletefieldid']));
        logActivity($_SESSION['admin_id'] . " removed customer fields" . $_POST['deletefieldid']);
    }
}
if ($action == "save") {
    if (isset($_POST['fieldname'])) {
        foreach ($_POST['fieldname'] as $fid => $value) {
            $customfieldname = array(
                "fieldname" => $value,
                "fieldtype" => $_POST['fieldtype'][$fid],
                "description" => $_POST['description'][$fid],
                "fieldoptions" => $_POST['fieldoptions'][$fid],
                "regexpr" => html_entity_decode($_POST['regexpr'][$fid]),
                "adminonly" => $_POST['adminonly'][$fid == "on" ? 1 : 0],
                "required" => $_POST['required'][$fid] == "on" ? 1 : 0,
                "showorder" => $_POST['showorder'][$fid] == "on" ? 1 : 0,
                "showinvoice" => $_POST['showinvoice'][$fid] == "on" ? 1 : 0,
                "sortorder" => $_POST['sortorder'][$fid]
            );
            update_query("tblclientfields", $customfieldname, array("cfid" => $fid));
        }
        if (isset($_POST['updatelinkfieldname'])) {
            foreach ($_POST['updatelinkfieldname'] as $upfid => $uvalue) {
                $updatelinkfield = array(
                    "fieldname" => $_POST['updatelinkfieldname'][$upfid],
                    "fieldtype" => $_POST['updatelinkfieldtype'][$upfid],
                    "description" => "",
                    "fieldoptions" => "",
                    "regexpr" => "",
                    "adminonly" => 0,
                    "required" => $_POST['updatelinkrequired'][$upfid] == "on" ? 1 : 0,
                    "showorder" => 0,
                    "showinvoice" => 0,
                    "sortorder" => 0
                );
                update_query("tblclientfields", $updatelinkfield, array("cfid" => $upfid));
            }
        }
    }
    if (isset($_POST['addfieldname']) && $_POST['addfieldname'] != "") {
        $addcustomfieldname = array(
            "fieldname" => $_POST['addfieldname'],
            "fieldtype" => $_POST['addfieldtype'],
            "description" => $_POST['adddescription'],
            "fieldoptions" => $_POST['addfieldoptions'],
            "regexpr" => html_entity_decode($_POST['addregexpr']),
            "adminonly" => $_POST['addadminonly'] == "on" ? 1 : 0,
            "required" => $_POST['addrequired'] == "on" ? 1 : 0,
            "showorder" => $_POST['addshoworder'] == "on" ? 1 : 0,
            "showinvoice" => $_POST['addshowinvoice'] == "on" ? 1 : 0,
            "sortorder" => $_POST['addsortorder']
        );
        $nfid = insert_query("tblclientfields", $addcustomfieldname);
    }
    redir();
}




$aInt->template = "client/clientfields";
$aInt->display();
?>