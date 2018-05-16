<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
if ($action == "edit") {
    $reqperm = "Add/Edit Client Notes";
} else {
    $reqperm = "View Clients Notes";
}
if ($_POST['update']) {
    $array = array(
        "note" => $_POST['notes'],
        "assignto" => $_POST['assign'],
        "modified" => "now()",
        "duedate" => toMySQLDate($_POST['duedate']),
        "sticky" => $_POST['done'] ? 1 : 0
    );
    update_query("ra_notes", $array, array("id" => $_POST['id']));
    exit();
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
//    check_token("RA.admin.default");
//    checkPermission("Add/Edit Client Notes");
    if (!isset($_POST['account'])) {
        $account = $userid;
    } else {
        $account = $_POST['account'];
    }

    if (isset($_POST['duedate'])) {
        $duedate = $_POST['duedate'];
    } else {
        $duedate = 'now()';
    }

    if (isset($_POST['assignto'])) {
        $assingto = $_POST['assignto'];
    } else {
        $assingto = $_SESSION['adminid'];
    }
    insert_query("ra_notes", array(
        "rel_id" => $account,
        "adminid" => $_SESSION['adminid'],
        "type" => $_POST['rel_type'],
        "created" => "now()",
        "duedate" => toMySQLDate($duedate),
        "flag" => $_POST['imports'],
        "assignto" => $_POST['assign'],
        "modified" => "now()",
        "note" => $_POST['notes'],
        "sticky" => $sticky));
    logActivity("Added Note - User ID: " . $userid,$userid,$account);
    redir("userid=" . $userid);
    exit();
} else {
    if ($sub == "save") {
        check_token("RA.admin.default");
        checkPermission("Add/Edit Client Notes");
        update_query(
                "ra_notes", array(
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
            delete_query("ra_notes", array("id" => $id));
            logActivity("Deleted Note - User ID: " . $userid . " - ID: " . $id);
            redir("userid=" . $userid);
            exit();
        }
    }
}

$aInt->deleteJSConfirm("doDelete", "clients", "deletenote", "clientsnotes.php?userid=" . $userid . "&sub=delete&id=");
$aInt->sortableTableInit("created", "ASC");
$result = select_query_i("ra_notes", "COUNT(*)", array("userid" => $userid), "created", "ASC", "", "ra_admin ON ra_admin.id=ra_notes.adminid");
$data = mysqli_fetch_array($result);
$numrows = $data[0];

$query = "select tbn.*,CONCAT(tba.firstname,' ',tba.lastname) as name,CONCAT(tbaa.firstname,' ',tbaa.lastname) as assignname from ra_notes as tbn 
INNER JOIN ra_admin AS tba on (tba.id=tbn.adminid) 
LEFT JOIN ra_admin AS tbaa on (tbaa.id=tbn.assignto) 
LEFT JOIN ra_orders as tbo on (tbo.id=tbn.rel_id and tbn.type='order')
LEFT JOIN tblcustomerservices as tbcs on (tbcs.id=tbn.rel_id and tbn.type='account')
where (tbn.rel_id=" . $userid . " and tbn.type='client') OR tbo.userid=" . $userid . " OR tbcs.userid=" . $userid . " ORDER BY tbn.flag DESC";

$result = full_query_i($query);
while ($data = mysqli_fetch_array($result)) {
    $noteid = $data['id'];
    $duedate =fromMySQLDate($data['duedate']);
    $created = $data['created'];
    $modified = $data['modified'];
    $note = $data['note'];
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
    $tabledata[] = array($data['type'], $created, $note, $data['name'], $data['assignname'], $duedate, $modified, $importantnote, "<a href=\"" . $PHP_SELF . "?userid=" . $userid . "&action=edit&id=" . $noteid . "\"class=\"btn btn-success editnotes\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $noteid . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>", $noteid);
}

//$table = $aInt->sortableTable(array($aInt->lang("fields", "created"), $aInt->lang("fields", "note"), $aInt->lang("fields", "admin"), "Assignee", "Due Date", $aInt->lang("fields", "lastmodified"), "", "", ""), $tabledata);


if ($action == "edit") {
    $notesdata = get_query_vals("ra_notes", "note,sticky,assignto,duedate", array("id" => $id));
    $note = $notesdata['note'];
    $importantnote = ($notesdata['sticky'] ? " checked" : "");
    $result = select_query_i("ra_admin", "id,firstname,lastname");
    $select = "<select class=\"form-control\" name=\"assignto\">";
    while ($data = mysqli_fetch_array($result)) {
        if ($data['id'] == $notesdata['assignto']) {
            $current = "SELECTED";
        } else {
            $current = "";
        }

        $select .= "<option value='" . $data['id'] . "' " . $current . ">" . $data['firstname'] . " " . $data['lastname'] . "</option>";
    }
    $select .= "</select>";


    $aInt->assign("notesdata", $notesdata);
    $aInt->assign("PHP_SELF", $PHP_SELF);
    $aInt->assign("id", $id);
    $aInt->assign("userid", $userid);
    $aInt->assign("select", $select);
} else {
    sprintf("<form method=\"post\" action=\"%s?userid=%s&sub=add\">", $PHP_SELF, $userid);
    $form .= "<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td class=\"fieldarea\"><textarea name=\"note\" rows=\"6\"></textarea></td><td align=\"center\" width=\"60\"><input type=\"submit\" value=\"";
    $form .= $aInt->lang("global", "addnew");
    $form .= "\" class=\"button\" /><br /><label><input type=\"checkbox\" class=\"checkbox\" name=\"sticky\" value=\"1\" /> ";
    $form .= $aInt->lang("clientsummary", "stickynotescheck");
    $form .= "</label></td></tr>
</table>
</form>
";
}


$result = select_query_i("ra_admin", "id,firstname,lastname");
while ($data = mysqli_fetch_array($result)) {
    $adminlist[$data['id']] = $data['firstname'] . " " . $data['lastname'];
}
$aInt->assign("adminlist", $adminlist);
$aInt->assign("tabledata", $tabledata);
$aInt->assign("formbottom", $form);
$aInt->template = "client/notes";


$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
