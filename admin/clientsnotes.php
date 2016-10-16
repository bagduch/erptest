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

if ($action == "edit") {
    $reqperm = "Add/Edit Client Notes";
} else {
    $reqperm = "View Clients Notes";
}

$aInt = new RA_Admin($reqperm);
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);
$id = (int) $id;
if (intval($sticky) > 0) {
    $sticky = 1;
} else {
    $sticky = 0;
}

if ($sub == "add") {
    check_token("RA.admin.default");
    checkPermission("Add/Edit Client Notes");
    insert_query(
            "tblnotes", array(
        "userid" => $userid,
        "adminid" => $_SESSION['adminid'],
        "created" => "now()",
        "modified" => "now()",
        "note" => $note,
        "sticky" => $sticky
            )
    );
    logActivity("Added Note - User ID: " . $userid);
    redir("userid=" . $userid);
    exit();
} else {
    if ($sub == "save") {
        check_token("RA.admin.default");
        checkPermission("Add/Edit Client Notes");
        update_query(
                "tblnotes", array(
            "note" => $note,
            "sticky" => $sticky,
            "modified" => "now()"
                ), array(
            "id" => $id
                )
        );
        logActivity("Updated Note - User ID: " . $userid . " - ID: " . $id);
        redir("userid=" . $userid);
        exit();
    } else {
        if ($sub == "delete") {
            check_token("RA.admin.default");
            checkPermission("Delete Client Notes");
            delete_query("tblnotes", array("id" => $id));
            logActivity("Deleted Note - User ID: " . $userid . " - ID: " . $id);
            redir("userid=" . $userid);
            exit();
        }
    }
}

$aInt->deleteJSConfirm("doDelete", "clients", "deletenote", "clientsnotes.php?userid=" . $userid . "&sub=delete&id=");
ob_start();
$aInt->sortableTableInit("created", "ASC");
$result = select_query_i("tblnotes", "COUNT(*)", array("userid" => $userid), "created", "ASC", "", "tbladmins ON tbladmins.id=tblnotes.adminid");
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$result = select_query_i("tblnotes", "tblnotes.*,(SELECT CONCAT(firstname,' ',lastname) FROM tbladmins WHERE tbladmins.id=tblnotes.adminid) AS adminuser", array("userid" => $userid), "modified", "DESC");

while ($data = mysqli_fetch_array($result)) {
    $noteid = $data['id'];
    $created = $data['created'];
    $modified = $data['modified'];
    $note = $data['note'];
    $admin = $data['adminuser'];

    if (!$admin) {
        $admin = "Admin Deleted";
    }

    $note = nl2br($note);
    $note = autoHyperLink($note);
    $created = fromMySQLDate($created, "time");
    $modified = fromMySQLDate($modified, "time");
    $importantnote = ($data['sticky'] ? "high" : "low");
    $tabledata[] = array($created, $note, $admin, $modified, "<img src=\"images/" . $importantnote . "priority.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("clientsummary", "importantnote") . "\">", "<a href=\"" . $PHP_SELF . "?userid=" . $userid . "&action=edit&id=" . $noteid . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "edit") . "\"></a>", "<a href=\"#\" onClick=\"doDelete('" . $noteid . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\"></a>");
}

echo $aInt->sortableTable(array($aInt->lang("fields", "created"), $aInt->lang("fields", "note"), $aInt->lang("fields", "admin"), $aInt->lang("fields", "lastmodified"), "", "", ""), $tabledata);
echo "
<br>

";

if ($action == "edit") {
    $notesdata = get_query_vals("tblnotes", "note, sticky", array("userid" => $userid, "id" => $id));
    $note = $notesdata['note'];
    $importantnote = ($notesdata['sticky'] ? " checked" : "");
    sprintf("<form method=\"post\" action=\"%s?userid=%s&sub=save&id=%s\">", $PHP_SELF, $userid, $id
    );
    echo "<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">";
    echo "<tr><td class=\"fieldarea\">";
    sprintf("<tr><td class=\"fieldarea\"><textarea name=\"note\" rows=\"6\">%s</textarea>", $note);
    echo "</td><td align=\"center\" width=\"60\">";
    sprintf("<input type=\"submit\" value=\"%s\" class=\"button\">", $aInt->lang("global", "savechanges"));
    echo "<br /><label><input type=\"checkbox\" class=\"checkbox\" name=\"sticky\" value=\"1\"";
    echo $importantnote;
    echo " /> ";
    echo $aInt->lang("clientsummary", "stickynotescheck");
    echo "</label></td></tr></table></form>";
} else {
    sprintf("<form method=\"post\" action=\"%s?userid=%s&sub=add\">", $PHP_SELF, $userid);
    echo "<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldarea\"><textarea name=\"note\" rows=\"6\"></textarea></td><td align=\"center\" width=\"60\"><input type=\"submit\" value=\"";
    echo $aInt->lang("global", "addnew");
    echo "\" class=\"button\" /><br /><label><input type=\"checkbox\" class=\"checkbox\" name=\"sticky\" value=\"1\" /> ";
    echo $aInt->lang("clientsummary", "stickynotescheck");
    echo "</label></td></tr>
</table>
</form>
";
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
