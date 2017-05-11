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
$aInt = new RA_Admin("Configure Admin Roles");
$aInt->title = $aInt->lang("setup", "adminroles");
$aInt->sidebar = "config";
$aInt->icon = "adminroles";
$aInt->helplink = "Administrator Roles";
$aInt->requiredFiles(array("reportfunctions"));
$chart = new RAChart();
$menuselect = "$('#menu').multilevelpushmenu('expand','Staff Management');";
if ($action == "addrole") {
    check_token("RA.admin.default");
    $adminrole = insert_query("tbladminroles", array("name" => $name));
    redir("action=edit&id=" . $adminrole);
    exit();
}


if ($action == "duplicaterole") {
    check_token("RA.admin.default");
    $result = select_query_i("tbladminroles", "", array("id" => $existinggroup));
    $data = mysqli_fetch_array($result);
    $widgets = $data['widgets'];
    $systememails = $data['systememails'];
    $accountemails = $data['accountemails'];
    $supportemails = $data['supportemails'];
    $roleid = insert_query("tbladminroles", array("name" => $newname, "widgets" => $widgets, "systememails" => $systememails, "accountemails" => $accountemails, "supportemails" => $supportemails));
    $result = select_query_i("tbladminperms", "", array("roleid" => $existinggroup));

    while ($data = mysqli_fetch_array($result)) {
        insert_query("tbladminperms", array("roleid" => $roleid, "permid" => $data['permid']));
    }

    redir("action=edit&id=" . $roleid);
    exit();
}


if ($action == "save") {
    check_token("RA.admin.default");

    if (!empty($report)) {
        $reportdata = "";
        foreach ($report as $row) {
            $reportdata .= $row . ",";
        }
    } else {
        $reportdata = "";
    }
    update_query("tbladminroles", array("name" => $name, "widgets" => implode(",", $widget), "report" => $reportdata, "systememails" => $systememails, "accountemails" => $accountemails, "supportemails" => $supportemails), array("id" => $id));
    delete_query("tbladminperms", array("roleid" => $id));

    if ($adminperms) {
        foreach ($adminperms as $k => $v) {
            insert_query("tbladminperms", array("roleid" => $id, "permid" => $k));
        }
    }

    redir("saved=true");
}


if ($action == "delete") {
    check_token("RA.admin.default");
    $admincount = get_query_val("tbladmins", "COUNT(id)", array("roleid" => $id));

    if ($admincount) {
        redir();
    }

    delete_query("tbladminroles", array("id" => $id));
    delete_query("tbladminperms", array("roleid" => $id));
    redir("deleted=true");
}

if (!$action) {
    if ($saved) {
        infoBox($aInt->lang("global", "changesuccess"), $aInt->lang("global", "changesuccessdesc"));
    }


    if ($deleted) {
        infoBox($aInt->lang("adminroles", "deletesuccess"), $aInt->lang("adminroles", "deletesuccessinfo"));
    }

    $aInt->deleteJSConfirm("doDelete", "adminroles", "suredelete", $_SERVER['PHP_SELF'] . "?action=delete&id=");

    $aInt->sortableTableInit("nopagination");
    $result = select_query_i("tbladminroles", "", "", "name", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $deletejs = (3 < $data['id'] ? "doDelete('" . $data['id'] . "')" : "alert('" . $aInt->lang("adminroles", "nodeldefault", 1) . "')");
        $assigned = array();
        $result2 = select_query_i("tbladmins", "id,username,disabled", array("roleid" => $data['id']), "username", "ASC");

        while ($data2 = mysqli_fetch_array($result2)) {
            $assigned[] = "<a href=\"configadmins.php?action=manage&id=" . $data2['id'] . "\"" . ($data2['disabled'] ? " style=\"color:#ccc;\"" : "") . ">" . $data2['username'] . "</a>";
        }


        if (count($assigned)) {
            $deletejs = "alert('" . $aInt->lang("adminroles", "nodelinuse", 1) . "')";
        } else {
            $assigned[] = $aInt->lang("global", "none");
        }

        $tabledata[] = array($data['name'], implode(", ", $assigned), "<a href=\"" . $PHP_SELF . "?action=edit&id=" . $data['id'] . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"" . $deletejs . "\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    $table = $aInt->sortableTable(array($aInt->lang("fields", "groupname"), $aInt->lang("supportticketdepts", "assignedadmins"), "", ""), $tabledata);
    $aInt->assign('table', $table);
    $template = "view";
    $aInt->assign("infobox", $infobox);
    $aInt->template = "configadminroles/" . $template;
} elseif ($action == "add") {
    $template = "add";
    $aInt->template = "configadminroles/" . $template;
} elseif ($action == "duplicate") {
    $existinggrouphtml = "<select class='form-control' name=\"existinggroup\">";
    $result = select_query_i("tbladminroles", "", "", "name", "ASC");
    while ($data = mysqli_fetch_array($result)) {
        $existinggrouphtml.= "<option value=\"" . $data['id'] . "\">" . $data['name'] . "</otpion>";
    }
    $existinggrouphtml.= "</select>";
    $aInt->assign("existinggrouphtml", $existinggrouphtml);
    $template = "dupicate";
    $aInt->template = "configadminroles/" . $template;
} else {
    $result = select_query_i("tbladminroles", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $name = $data['name'];
    $widgets = $data['widgets'];
    $systememails = $data['systememails'];
    $accountemails = $data['accountemails'];
    $supportemails = $data['supportemails'];
    $widgets = explode(",", $widgets);
    $adminpermsarray = getAdminPermsArray();
    $totalpermissions = count($adminpermsarray);
    $totalpermissionspercolumn = round($totalpermissions / 3);
    $rowcount = 0;
    $colcount = 0;
    $permissionsfieldhtml = "<table width=\"100%\"><tr><td valign=\"top\" width=\"34%\">";
    foreach ($adminpermsarray as $k => $v) {
        $permissionsfieldhtml.= ("<input type=\"checkbox\" name=\"adminperms[" . $k . "]") . "\" id=\"adminperms" . $k . "\"";
        $result = select_query_i("tbladminperms", "COUNT(*)", array("roleid" => $id, "permid" => $k));
        $data = mysqli_fetch_array($result);
        if ($data[0]) {
            $permissionsfieldhtml.= " checked";
        }
        $permissionsfieldhtml.= "> <label for=\"adminperms" . $k . "\">" . $aInt->lang("permissions", $k) . "</label><br>";
        ++$rowcount;
        if ($rowcount == $totalpermissionspercolumn) {
            if ($colcount < 2) {
                $permissionsfieldhtml.= "</td><td valign=\"top\" width=\"33%\">";
            }
            $rowcount = 0;
            ++$colcount;
            continue;
        }
    }
    $permissionsfieldhtml .="</td></tr></table>";
    $widgethtml = "<table width=\"100%\"><tr><td width=\"33%\" valign=\"top\">";
    $hooksdir = ROOTDIR . "/modules/widgets/";
    if (is_dir($hooksdir)) {
        $dh = opendir($hooksdir);
        while (false !== $hookfile = readdir($dh)) {
            if (is_file($hooksdir . $hookfile) && $hookfile != "index.php") {
                $extension = explode(".", $hookfile);
                $extension = end($extension);
                if ($extension == "php") {
                    include $hooksdir . $hookfile;
                }
            }
        }
    }
    closedir($dh);



    $reporthtml = "<table><tr><td>";

    $reportdir = ROOTDIR . "/modules/reports/";
    if (is_dir($reportdir)) {
        $dh = opendir($reportdir);
        while (false !== $reportfile = readdir($dh)) {
            if (is_file($reportdir . $reportfile) && $reportfile != "index.php") {
                $extension = explode(".", $reportfile);
                $reportfilename = str_replace("_", " ", $extension[0]);

                $reporthtml.="<input id='" . $extension[0] . "' type='checkbox' name='report[]' value='" . $extension[0] . "'>";
                $reporthtml.="<label for='" . $extension[0] . "'>" . ucwords($reportfilename) . "</label><br />";
            }
        }
    }
    $reporthtml .= "</td></tr></table>";

    closedir($dh);

    function load_admin_home_widgets() {
        global $aInt;
        global $hooks;
        $hook_name = "AdminHomeWidgets";
        $args = array("adminid" => $_SESSION['adminid'], "loading" => "<img src=\"images/loading.gif\" align=\"absmiddle\" /> " . $aInt->lang("global", "loading"));
        if (!array_key_exists($hook_name, $hooks)) {
            return array();
        }
        reset($hooks[$hook_name]);
        $results = array();
        while (list($key, $hook) = each($hooks[$hook_name])) {
            $widgetname = substr($hook['hook_function'], 7);
            if (function_exists($hook['hook_function'])) {
                $res = call_user_func($hook['hook_function'], $args);
                if ($res) {
                    $results[$widgetname] = $res['title'];
                }
            }
        }
        return $results;
    }

    $listwidgets = load_admin_home_widgets();
    asort($listwidgets);
    $totalportlets = ceil(count($listwidgets) / 3);
    $i = 0;
    foreach ($listwidgets as $k => $v) {
        $widgethtml.= "<input type=\"checkbox\" name=\"widget[]\" value=\"" . $k . "\" id=\"widget" . $k . "\"";
        if (in_array($k, $widgets)) {
            $widgethtml.= " checked";
        }
        $widgethtml.= " /> <label for=\"widget" . $k . "\">" . $v . "</label><br />";
        if ($totalportlets <= $i) {
            $widgethtml.= "</td><td width=\"33%\" valign=\"top\">";
            $i = 0;
            continue;
        }

        ++$i;
    }

    $widgethtml.= "</td></tr></table>";

    $aInt->assign("reporthtml", $reporthtml);
    $aInt->assign("supportemails", $supportemails);
    $aInt->assign("accountemails", $accountemails);
    $aInt->assign("systememails", $systememails);
    $aInt->assign('PHP_SELF', $PHP_SELF);
    $aInt->assign("id", $id);
    $aInt->assign("name", $name);
    $aInt->assign("widgethtml", $widgethtml);
    $aInt->assign('permissionsfieldhtml', $permissionsfieldhtml);
    $template = "edit";
    $aInt->template = "configadminroles/" . $template;
}

$aInt->jscode = $jscode;
$aInt->jquerycode .= $menuselect;
$aInt->display();
?>
