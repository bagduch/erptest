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
$aInt = new RA_Admin("View Activity Log");
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);

$result = select_query_i("tblactivitylog", "DISTINCT user", "", "user", "ASC");


$useroption = "";
while ($data = mysqli_fetch_array($result)) {
    $user = $data['user'];
    $useroption .= "<option";

    if ($user == $username) {
        $useroption .= " selected";
    }

    $useroption .= ">" . $user . "</option>";
}

$aInt->sortableTableInit("date");
$where = "userid='" . (int) $userid . "' AND ";

if ($date) {
    $where .= "date>'" . toMySQLDate($date) . "' AND date<='" . toMySQLDate($date) . "235959' AND ";
}


if ($username) {
    $where .= "user='" . db_escape_string($username) . "' AND ";
}


if ($description) {
    $where .= "description LIKE '%" . db_escape_string($description) . "%' AND ";
}


if ($ipaddress) {
    $where .= " ipaddr='" . db_escape_string($ipaddress) . "' AND ";
}


if ($where) {
    $where = substr($where, 0, 0 - 5);
}

$result = select_query_i("tblactivitylog", "COUNT(*)", $where, "id", "DESC");
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$patterns[] = "/- User ID: (.*?) /";
$patterns[] = "/Service ID: (.*?) /";
$patterns[] = "/Domain ID: (.*?) /";
$patterns[] = "/Invoice ID: (.*?) /";
$patterns[] = "/Quote ID: (.*?) /";
$patterns[] = "/Order ID: (.*?) /";
$patterns[] = "/Transaction ID: (.*?) /";
$replacements[] = "";
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

$table = $aInt->sortableTable(array("Date", "Description", "User", "IP Address"), $tabledata);
$content = ob_get_contents();

$aInt->assign("useroption", $useroption);
$aInt->assign("table", $table);
$aInt->template = "client/clientlog";
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>