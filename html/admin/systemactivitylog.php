<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Activity Log");
$aInt->title = $aInt->lang("system", "activitylog");
$aInt->sidebar = "utilities";
$aInt->icon = "logs";
$menuselect = "";

$query = "SELECT DISTINCT user FROM tblactivitylog ORDER BY user ASC";
$result = full_query_i($query);
$option = "";
while ($data = mysqli_fetch_array($result)) {
    $user = $data['user'];
    $option .= "<option";

    if ($user == $username) {
        $option .= " selected";
    }

    $option .= ">" . $user . "</option>";
}
$result = select_query_i("tblactivitylog", "", "userid=0", "id", "DESC", $CONFIG['ActivityLimit'] . ",9999");
while ($data = mysqli_fetch_array($result)) {
    delete_query("tblactivitylog", array("id" => $data['id']));
}
$aInt->sortableTableInit("date");
$where = " AND description not like 'Cron Job%'";
if ($date) {
    $where .= " AND date>'" . toMySQLDate($date) . "' AND date<='" . toMySQLDate($date) . "235959'";
}
if ($username) {
    $where .= " AND user='" . db_escape_string($username) . "'";
}
if ($description) {
    $where .= " AND description LIKE '%" . db_escape_string($description) . "%'";
}
if ($ipaddress) {
    $where .= " AND ipaddr='" . db_escape_string($ipaddress) . "'";
}
if ($where) {
    $where = substr($where, 5);
}
$aInt->assign("option",$option);
$aInt->assign("description",$description);
$aInt->assign("date",$date);
$aInt->assign("ipaddress",$ipaddress);
$result = select_query_i("tblactivitylog", "COUNT(*)", $where);
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$patterns = $replacements = array();
$patterns[] = "/User ID: (.*?) /";
$patterns[] = "/Service ID: (.*?) /";
$patterns[] = "/Domain ID: (.*?) /";
$patterns[] = "/Invoice ID: (.*?) /";
$patterns[] = "/Quote ID: (.*?) /";
$patterns[] = "/Order ID: (.*?) /";
$patterns[] = "/Transaction ID: (.*?) /";
$replacements[] = "<a href=\"clientssummary.php?userid=$1\">User ID: $1</a> ";
$replacements[] = "<a href=\"clientsservices.php?id=$1\">Service ID: $1</a> ";
$replacements[] = "<a href=\"clientsdomains.php?id=$1\">Domain ID: $1</a> ";
$replacements[] = "<a href=\"invoices.php?action=edit&id=$1\">Invoice ID: $1</a> ";
$replacements[] = "<a href=\"quotes.php?action=manage&id=$1\">Quote ID: $1</a> ";
$replacements[] = "<a href=\"orders.php?action=view&id=$1\">Order ID: $1</a> ";
$replacements[] = "<a href=\"transactions.php?action=edit&id=$1\">Transaction ID: $1</a> ";
$result = select_query_i("tblactivitylog", "", $where, "id", "DESC", $page * $limit . ("," . $limit));

while ($data = mysqli_fetch_array($result)) {
    $id = $data['id'];
    $description = $data['description'];
    $username = $data['user'];
    $date = $data['date'];
    $ipaddr = $data['ipaddr'];
    $description .= " ";
    $description = RAHtmlspecialchars($description);
    $description = preg_replace($patterns, $replacements, $description);
    $tabledata[] = array(fromMySQLDate($date, "time"), "<div align=\"left\">" . $description . "</div>", $username, $ipaddr);
}

$table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "description"), $aInt->lang("fields", "username"), $aInt->lang("fields", "ipaddress")), $tabledata);
$aInt->assign("table",$table);

$aInt->template = "log/system";
$aInt->jquerycode = $jquerycode . $menuselect;
$aInt->display();
?>