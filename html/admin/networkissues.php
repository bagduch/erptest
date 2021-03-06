<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Manage Network Issues");
$aInt->title = "Network Issues";
$aInt->sidebar = "support";
$aInt->icon = "networkissues";
$upd = fromMySQLDate(date("Y-m-d H:i:s"), true);
if ($action == "save") {
    check_token("RA.admin.default");

    if (!$startdate) {
        $startdate = $upd;
    }

    $errormessage = "";

    if (!$description) {
        $errormessage = "A description of the issue is required";
    }


    if (!$startdate) {
        $errormessage = "The start date is required";
    }


//    if ($type == "Server" && !$server) {
//        $errormessage = "For a server affecting issue, you must select a server";
//    }
//
//    if (($type == "Service" || $type == "Other") && !$affecting) {
//        $errormessage = "For a system or other type of issue, you must specify ";
//    }
//
//
//    if ($type != "Server") {
//        $server = 0;
//    }


    if (!$type) {
        $errormessage = "You must choose a type for the issue";
    }


    if (!$title) {
        $errormessage = "A title is required summarising the issue";
    }


    if ($errormessage) {
        $action = "manage";
    } else {
        $startdate = toMySQLDate($startdate);

        if ($enddate) {
            $enddate = toMySQLDate($enddate);
        } else {
            $enddate = "NULL";
        }

        $updatearray = array("startdate" => $startdate, "enddate" => $enddate, "title" => $title, "description" => html_entity_decode($description), "type" => $type, "affecting" => $affecting, "priority" => $priority, "status" => $status, "lastupdate" => "now()");

        if ($id) {
            update_query("ra_system_status", $updatearray, array("id" => $id));
            run_hook("NetworkIssueEdit", array_merge(array("id" => $id), $updatearray));

            if ($status == "Resolved") {
                run_hook("NetworkIssueClose", array("id" => $id));
            }
        } else {
            $nwid = insert_query("ra_system_status", $updatearray);
            run_hook("NetworkIssueAdd", array_merge(array("id" => $nwid), $updatearray));
        }

        redir();
        exit();
    }
}


if ($action == "close") {
    check_token("RA.admin.default");
    update_query("ra_system_status", array("status" => "Resolved", "enddate" => "now()"), array("id" => $id));
    run_hook("NetworkIssueClose", array("id" => $id));
    redir("view=resolved");
    exit();
}


if ($action == "reopen") {
    check_token("RA.admin.default");
    update_query("ra_system_status", array("status" => "In Progress", "enddate" => "NULL"), array("id" => $id));
    run_hook("NetworkIssueReopen", array("id" => $id));
    redir();
    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    run_hook("NetworkIssueDelete", array("id" => $id));
    delete_query("ra_system_status", array("id" => $id));
    redir();
    exit();
}

$t_query = "select name from ra_catalog_groups";
$t_result = full_query_i($t_query);

if (0 < mysqli_num_rows($t_result)) {
    while ($t_row = mysqli_fetch_row($t_result)) {
        $type_options[] = $t_row[0];
    }
}
$p_query = "SHOW COLUMNS FROM ra_system_status LIKE 'priority'";
$p_result = full_query_i($p_query);

if (0 < mysqli_num_rows($p_result)) {
    $p_row = mysqli_fetch_row($p_result);
    preg_match("/^enum\(\'(.*)\'\)$/", $p_row[1], $matches);
    $priority_options = explode("','", $matches[1]);
}

$s_query = "SHOW COLUMNS FROM ra_system_status LIKE 'status'";
$s_result = full_query_i($s_query);

if (0 < mysqli_num_rows($s_result)) {
    $s_row = mysqli_fetch_row($s_result);
    preg_match("/^enum\(\'(.*)\'\)$/", $s_row[1], $matches);
    $status_options = explode("','", $matches[1]);
}

$server_query = "SELECT id, name FROM ra_integration";
$server_result = full_query_i($server_query);
ob_start();

if ($action == "") {
    if ($view == "scheduled") {
        $pagetitle = "Scheduled";
        $where = array("status" => "Scheduled");
    } elseif ($view == "resolved") {
        $pagetitle = "Resolved";
        $where = array("status" => "Resolved");
    } else {
        $pagetitle = "Open";
        $where = "status!='Resolved' AND status!='Scheduled'";
    }


    $result = select_query_i("ra_system_status", "*", $where, "lastupdate", "DESC");
    $aInt->deleteJSConfirm("doDelete", "global", "deleteconfirm", "?action=delete&id=");
    echo "
<p>";
    echo "<s";
    echo "trong>";
    echo $aInt->lang("fields", "options");
    echo ":</strong> <a href=\"networkissues.php\">";
    echo $aInt->lang("networkissues", "open");
    echo "</a> | <a href=\"networkissues.php?view=scheduled\">";
    echo $aInt->lang("networkissues", "scheduled");
    echo "</a> | <a href=\"networkissues.php?view=resolved\">";
    echo $aInt->lang("networkissues", "resolved");
    echo "</a> | <a href=\"?action=manage\"><img src=\"images/icons/add.png\" border=\"0\" align=\"absmiddle\" /> ";
    echo $aInt->lang("networkissues", "addnew");
    echo "</a></p>

<h2>";
    echo $pagetitle;
    echo " Issues</h2>

";
    $aInt->sortableTableInit("nopagination");

    if (mysqli_num_rows($result)) {

        while ($open_row = mysqli_fetch_assoc($result)) {
            $enddate = $open_row['enddate'];
            $enddate = ($enddate ? fromMySQLDate($enddate, true) : "None");

            if ($open_row['server']) {
                $open_row->type .= " (" . $open_row['server'] . ")";
            }


            if ($open_row['status'] == "Resolved") {
                $actions = "<a href=\"" . $_SERVER['PHP_SELF'] . "?action=reopen&id=" . $open_row['id'] . generate_token("link") . "\">Reopen</a>";
            } else {
                $actions = "<a href=\"" . $_SERVER['PHP_SELF'] . "?action=close&id=" . $open_row['id'] . generate_token("link") . "\">Close</a>";
            }

            $tabledata[] = array("<a href=\"" . $_SERVER['PHP_SELF'] . "?action=manage&id=" . $open_row['id'] . "\">" . $open_row['title'] . "</a>", $open_row['type'], $open_row['priority'], $open_row['status'], fromMySQLDate($open_row['startdate'], true), $enddate, $actions, "<a href=\"#\" onClick=\"doDelete('" . $open_row['id'] . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete\"></a>");
        }
    }

    echo $aInt->sortableTable(array("Title", "Type", "Priority", "Status", "Start Date", "End Date", " ", ""), $tabledata);
} else {
    if ($action == "manage") {
        if ($errormessage) {
            infoBox("Validation Failed", $errormessage);
            echo $infobox;
        }

        echo "<script type=\"text/javascript\" src=\"../includes/jscript/jquery-ui-timepicker-addon.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../includes/jscript/css/jquery-ui-timepicker-addon.css\" />
<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "?action=save\">";

        if ($id) {
            $pagetitle = "Modify Existing Issue";
            $result = select_query_i("ra_system_status", "", array("id" => $id));
            $data = mysqli_fetch_array($result);
            $title = $data['title'];
            $startdate = $data['startdate'];
            $enddate = $data['enddate'];
            $description = $data['description'];
            $type = $data['type'];
            $affecting = $data['affecting'];
            $server = $data['server'];
            $priority = $data['priority'];
            $status = $data['status'];
            $lastupdate = $data['lastupdate'];
            $startts = ($startdate ? MySQL2Timestamp($startdate) : "");
            $endts = ($enddate ? MySQL2Timestamp($enddate) : "");
            $startdate = fromMySQLDate($startdate, true);

            if ($enddate) {
                $enddate = fromMySQLDate($enddate, true);
            }

            echo "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\" />";
        } else {
            $pagetitle = "Create New Issue";

            if (!$startdate) {
                $startdate = $upd;
                $startts = ($startdate ? MySQL2Timestamp($startdate) : "");
            }


            if (!$type) {
                $type = "Server";
            }
        }


        if (($CONFIG['DateFormat'] == "DD/MM/YYYY" || $CONFIG['DateFormat'] == "DD.MM.YYYY") || $CONFIG['DateFormat'] == "DD-MM-YYYY") {
            $localdateformat = "dd/mm/yy";
        } else {
            if ($CONFIG['DateFormat'] == "MM/DD/YYYY") {
                $localdateformat = "mm/dd/yy";
            } else {
                if ($CONFIG['DateFormat'] == "YYYY/MM/DD" || $CONFIG['DateFormat'] == "YYYY-MM-DD") {
                    $localdateformat = "yy/mm/dd";
                }
            }
        }

        $jquerycode = "
$(\"#startdate\").datetimepicker({showSecond:false,ampm:false,dateFormat: \"" . $localdateformat . "\", timeFormat: \"hh:mm\",";

        if ($startts) {
            $jquerycode .= "defaultDate: new Date('" . date("j", $startts) . " " . date("F", $startts) . " " . date("Y", $startts) . "'),hour: " . date("H", $startts) . ",minute: " . date("i", $startts) . ",";
        }

        $jquerycode .= "});
$(\"#enddate\").datetimepicker({showSecond:false,ampm:false,";

        if ($endts) {
            $jquerycode .= "defaultDate: new Date('" . date("j", $endts) . " " . date("F", $endts) . " " . date("Y", $endts) . "'),hour: " . date("H", $endts) . ",minute: " . date("i", $endts) . ",";
        }

        $jquerycode .= "dateFormat: \"" . $localdateformat . "\",timeFormat: \"hh:mm:ss\",});";
        echo "<h2>" . $pagetitle . "</h2>";
        echo "
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">Title</td><td class=\"fieldarea\"><input type=\"text\" name=\"title\" size=\"50\" value=\"";
        echo $title;
        echo "\" /></td></tr>
<tr><td class=\"fieldlabel\">Type</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"type\" id=\"affectingtype\">";
        foreach ($type_options as $row => $value) {

            if ($value == $type) {
                echo "<option value=\"" . $value . "\" selected>" . $value . "</option>";
                continue;
            }

            echo "<option value=\"" . $value . "\">" . $value . "</option>";
        }

        echo "</select></td></tr>

<tr id=\"affectingother\"";

        if ($type == "Server") {
            echo "style=\"display:none;\"";
        }

        echo "><td class=\"fieldlabel\">System/Other</td><td class=\"fieldarea\"><input type=\"text\" name=\"affecting\" size=\"50\" value=\"";
        echo $affecting;
        echo "\" /></td></tr>
<tr><td class=\"fieldlabel\">Priority</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"priority\">";
        foreach ($priority_options as $row => $value) {
            echo "<option value=\"" . $value . "\"";

            if ($value == $priority) {
                echo " selected";
            }

            echo ">" . $value . "</option>";
        }

        echo "</select></td></tr>
<tr><td class=\"fieldlabel\">Status</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"status\">";
        foreach ($status_options as $row => $value) {
            echo "<option value=\"" . $value . "\"";

            if ($value == $status) {
                echo " selected";
            }

            echo ">" . $value . "</option>";
        }

        echo "</select></td></tr>
<tr><td class=\"fieldlabel\">Start Date</td><td class=\"fieldarea\"><input type=\"text\" id=\"startdate\" name=\"startdate\" value=\"";
        echo $startdate;
        echo "\" style=\"width:120px;\" /></td></tr>
<tr><td class=\"fieldlabel\">End Date</td><td class=\"fieldarea\"><input type=\"text\" id=\"enddate\" name=\"enddate\" value=\"";
        echo $enddate;
        echo "\" style=\"width:120px;\" /></td></tr>
</table>

<p>";
        echo "<s";
        echo "trong>";
        echo $aInt->lang("fields", "description");
        echo "</strong></p>

<textarea name=\"description\" id=\"message\" rows=20 style=\"width:100%\" class=\"tinymce\">";
        echo $description;
        echo "</textarea>

<p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Save Changes\" class=\"button\" /></p>

</form>

";
        $aInt->richTextEditor();
    }
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode . $menuselect;
$aInt->jscode = $jscode;
$aInt->display();
?>