<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View TXT Templates");
$aInt->title = $aInt->lang("emailtpls", "title");
$aInt->sidebar = "config";
$aInt->icon = "massmail";
$aInt->helplink = "Email Templates";
$menuselect = "$('#menu').multilevelpushmenu('expand','System');";

if ($action == 'new') {
    $smsid = insert_query("tblsmstemplate", array('smsgrp' => $_POST['smsgrp'], 'name' => $_POST['name']));
    redir("action=edit&id=" . $smsid);
    exit();
} elseif ($action == "edit") {
    $result = select_query_i("tblsmstemplate", "*", array('id' => $id));
    $sms = mysqli_fetch_assoc($result);
    $aInt->assign("sms", $sms);
    $template = "smstemplate/edit";
} elseif ($action == "update") {
    $id = $_POST['id'];
    $data = array(
        'smsgrp' => $_POST['smsgrp'],
        'name' => $_POST['name'],
        'message' => $_POST['message']
    );
    $result = update_query("tblsmstemplate", $data, array('id' => $id));

redir();
} else {

    $data = array();
    $result = select_query_i("tblsmstemplate", "*");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['smsgrp']][] = $row;
    }

    $aInt->assign("temdata", $data);
    $template = "smstemplate/view";
}

$aInt->template = $template;
$aInt->jscode = $jscode;
$aInt->jquerycode = $jquerycode . $menuselect;
$aInt->display();
