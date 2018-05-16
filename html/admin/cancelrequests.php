<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Cancellation Requests");
$aInt->title = $aInt->lang("clients", "cancelrequests");
$aInt->sidebar = "clients";
$aInt->icon = "cancelrequests";
$aInt->helplink = "Cancellation Requests";
$menuselect = "$('#menu').multilevelpushmenu('expand','Customers');";
if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("ra_cancellations", array("id" => $id));
    redir();
    exit();
}

$aInt->deleteJSConfirm("doDelete", "clients", "cancelrequestsdelete", "?action=delete&id=");

$aInt->sortableTableInit("date", "ASC");
$query = "FROM ra_cancellations INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_cancellations.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_catalog_groups ON ra_catalog_groups.id=ra_catalog.gid INNER JOIN ra_user ON tblcustomerservices.userid=ra_user.id WHERE ";
$filter = false;
$criteria = array();

if ($reason) {
    $criteria[] = "ra_cancellations.reason LIKE '%" . db_escape_string($reason) . "%'";
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
    $criteria[] = "ra_cancellations.relid=" . (int) $serviceid;
    $filter = true;
}


if ($type) {
    $criteria[] = "ra_cancellations.type='" . db_escape_string($type) . "'";
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
$result = full_query_i("SELECT COUNT(ra_cancellations.id) " . $query);
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$query .= " ORDER BY ra_cancellations.date ASC";
$query = "SELECT ra_cancellations.*,tblcustomerservices.description,tblcustomerservices.nextduedate,ra_catalog.name AS productname,ra_catalog_groups.name AS groupname,tblcustomerservices.id AS productid,tblcustomerservices.userid,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid " . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
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
    $tabledata[] = array($date, $xname, "<textarea rows=3 cols=64 readonly>" . $reason . "</textarea>", $type, "<a href=\"#\" onClick=\"doDelete('" . $id2 . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
}

$table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "product"), $aInt->lang("fields", "reason"), $aInt->lang("fields", "type"), ""), $tabledata);
$aInt->assign("clientdropdown", $aInt->clientsDropDown($userid, "", "userid", true));
$aInt->assign("PHP_SELF", $PHP_SELF);
$aInt->assign("table", $table);
$aInt->assign("completed", $completed);
$aInt->template = "client/cancelrequest";
$aInt->jquerycode = $jquerycode;
$aInt->jquerycode .=$menuselect;
$aInt->jscode = $jscode;
$aInt->display();
?>