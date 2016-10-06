<?php

/**
 *
 * @ RA
 *
 *  */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Cancellation Requests");
$aInt->title = $aInt->lang("clients", "cancelrequests");
$aInt->sidebar = "clients";
$aInt->icon = "cancelrequests";
$aInt->helplink = "Cancellation Requests";

if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("tblcancelrequests", array("id" => $id));
    redir();
    exit();
}

$aInt->deleteJSConfirm("doDelete", "clients", "cancelrequestsdelete", "?action=delete&id=");

$aInt->sortableTableInit("date", "ASC");
$query = "FROM tblcancelrequests INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblcancelrequests.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblservicegroups ON tblservicegroups.id=tblservices.gid INNER JOIN tblclients ON tblcustomerservices.userid=tblclients.id WHERE ";
$filter = false;
$criteria = array();

if ($reason) {
    $criteria[] = "tblcancelrequests.reason LIKE '%" . db_escape_string($reason) . "%'";
    $filter = true;
}


if ($description) {
    $criteria[] = "tblcustomerservices.description LIKE '%" . db_escape_string($description) . "%'";
    $filter = true;
}


if ($userid) {
    $criteria[] = "tblcustomerservices.userid=" . (int) $userid;
    $filter = true;
}


if ($serviceid) {
    $criteria[] = "tblcancelrequests.relid=" . (int) $serviceid;
    $filter = true;
}


if ($type) {
    $criteria[] = "tblcancelrequests.type='" . db_escape_string($type) . "'";
    $filter = true;
}


if (!$filter) {
    if ($completed) {
        $criteria[] = "(tblcustomerservices.servicestatus='Cancelled' OR tblcustomerservices.servicestatus='Terminated') ";
    } else {
        $criteria[] = "(tblcustomerservices.servicestatus!='Cancelled' AND tblcustomerservices.servicestatus!='Terminated') ";
    }
}

$query .= implode(" AND ", $criteria);
$result = full_query_i("SELECT COUNT(tblcancelrequests.id) " . $query);
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$query .= " ORDER BY tblcancelrequests.date ASC";
$query = "SELECT tblcancelrequests.*,tblcustomerservices.description,tblcustomerservices.nextduedate,tblservices.name AS productname,tblservicegroups.name AS groupname,tblcustomerservices.id AS productid,tblcustomerservices.userid,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblclients.groupid " . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
$result = full_query_i($query);

while ($data = mysqli_fetch_array($result)) {
    ++$clicount;
    $id2 = $data['id'];
    $date = $data['date'];
    $relid = $data['relid'];
    $reason = $data['reason'];
    $type = $data['type'];
    $date = fromMySQLDate($date, "time");
    $description = $data['description'];
    $productname = $data['productname'];
    $groupname = $data['groupname'];
    $productid = $data['productid'];
    $userid = $data['userid'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $companyname = $data['companyname'];
    $groupid = $data['groupid'];
    $nextduedate = $data['nextduedate'];
    $nextduedate = fromMySQLDate($nextduedate);
    $xname = "<a href=\"clientshosting.php?userid=" . $userid . "&id=" . $productid . "\">" . $groupname . " - " . $productname . "</a><br>" . $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid);

    if ($description) {
        $xname .= " (" . $description . ")";
    }

    $type = ($type == "Immediate" ? $aInt->lang("clients", "cancelrequestimmediate") : $aInt->lang("clients", "cancelrequestendofperiod") . ("<br>(" . $nextduedate . ")"));
    $tabledata[] = array($date, $xname, "<textarea rows=3 cols=64 readonly>" . $reason . "</textarea>", $type, "<a href=\"#\" onClick=\"doDelete('" . $id2 . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\"></a>");
}

$table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "product"), $aInt->lang("fields", "reason"), $aInt->lang("fields", "type"), ""), $tabledata);
$aInt->assign("clientdropdown", $aInt->clientsDropDown($userid, "", "userid", true));
$aInt->assign("PHP_SELF", $PHP_SELF);
$aInt->assign("table", $table);
$aInt->assign("completed", $completed);
$aInt->template = "client/cancelrequest";
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>