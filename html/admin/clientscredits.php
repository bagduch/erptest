<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Credit Log", false);
$aInt->title = $aInt->lang("credit", "creditmanagement");
ob_start();
$aInt->inClientsProfile = true;
$aInt->valUserID($userid);
$currency = getCurrency($userid);
$clientsdetails = getClientsDetails($userid);
$result = select_query_i("tblclients", "", array("id" => $userid));
$data = mysqli_fetch_array($result);
$name = stripslashes($data['firstname'] . " " . $data['lastname']);
$creditbalance = $data['credit'];

if ($action == "") {
    if ($sub == "add") {
        checkPermission("Manage Credits");
        check_token("RA.admin.default");
        insert_query("tblcredit", array("clientid" => $userid, "date" => toMySQLDate($date), "description" => $description, "amount" => $amount));
        update_query("tblclients", array("credit" => "+=" . $amount), array("id" => (int) $userid));
        logActivity("Added Credit - User ID: " . $userid . " - Amount: " . formatCurrency($amount), $userid);
        redir("userid=" . $userid);
        exit();
    }


    if ($sub == "remove") {
        $credit = 0 - $amount;
        checkPermission("Manage Credits");
        check_token("RA.admin.default");
        insert_query("tblcredit", array("clientid" => $userid, "date" => toMySQLDate($date), "description" => $description, "amount" => $credit));
        update_query("tblclients", array("credit" => "-=" . $amount), array("id" => (int) $userid));
        logActivity("Removed Credit - User ID: " . $userid . " - Amount: " . formatCurrency($amount), $userid);
        redir("userid=" . $userid);
        exit();
    }


    if ($sub == "save") {
        checkPermission("Manage Credits");
        check_token("RA.admin.default");
        update_query("tblcredit", array("date" => toMySQLDate($date), "description" => $description, "amount" => $amount), array("id" => $id));
        logActivity("Edited Credit - Credit ID: " . $id . " - User ID: " . $userid, $userid);
        redir("userid=" . $userid);
        exit();
    }


    if ($sub == "delete") {
        checkPermission("Manage Credits");
        check_token("RA.admin.default");
        $result = select_query_i("tblcredit", "", array("id" => $ide));
        $data = mysqli_fetch_array($result);
        $amount = $data['amount'];
        $creditbalance = $creditbalance - $amount;

        if ($creditbalance < 0) {
            $creditbalance = 0;
        }

        update_query("tblclients", array("credit" => $creditbalance), array("id" => (int) $userid));
        delete_query("tblcredit", array("id" => $ide));
        logActivity("Deleted Credit - Credit ID: " . $ide . " - User ID: " . $userid, $userid);
        redir("userid=" . $userid);
        exit();
    }

    $result = select_query_i("tblclients", "", array("id" => $userid));
    $data = mysqli_fetch_array($result);
    $creditbalance = formatCurrency($data['credit']);
    echo "
<p>";
    echo $aInt->lang("credit", "info");
    echo "</p>
<div class=\"card\"><input type=\"button\" class=\"button btn-success\" value=\"";
    echo $aInt->lang("credit", "addcredit");
    echo "\" onClick=\"window.location='";
    echo $PHP_SELF;
    echo "?userid=";
    echo $userid;
    echo "&action=add'\"> <input type=\"button\" value=\"";
    echo $aInt->lang("credit", "removecredit");
    echo "\" onClick=\"window.location='";
    echo $PHP_SELF;
    echo "?userid=";
    echo $userid;
    echo "&action=remove'\"  class=\"button btn-inverse\"></div>
<p>";
    echo $aInt->lang("fields", "client");
    echo ": <B>";
    echo $name;
    echo "</B> (";
    echo $aInt->lang("fields", "balance");
    echo ": ";
    echo $creditbalance;
    echo ")</p>
<br />

";
    echo "<s";
    echo "cript language=\"JavaScript\">
function doDelete(id) {
if (confirm(\"";
    echo $aInt->lang("credit", "deleteq");
    echo "\")) {
window.location='";
    echo $PHP_SELF;
    echo "?userid=";
    echo $userid;
    echo "&sub=delete&ide='+id+'";
    echo generate_token("link");
    echo "';
}}
</script>

";
    $aInt->sortableTableInit("nopagination");
    $patterns = $replacements = array();
    $patterns[] = "/ Invoice #(.*?) /";
    $replacements[] = " <a href=\"invoices.php?action=edit&id=$1\" target=\"_blank\">Invoice #$1</a>";
    $result = select_query_i("tblcredit", "", array("clientid" => $userid), "date", "DESC");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $date = $data['date'];
        $date = fromMySQLDate($date);
        $description = $data['description'];
        $amount = $data['amount'];
        $description = preg_replace($patterns, $replacements, $description . " ");
        $tabledata[] = array($date, nl2br(trim($description)), formatCurrency($amount), "<a href=\"" . $PHP_SELF . "?userid=" . $userid . "&action=edit&id=" . $id . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    $table = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "description"), $aInt->lang("fields", "amount"), "", ""), $tabledata);
    $aInt->assign("table", $table);

    $template = 'client/credit';
} elseif ($action == "add" || $action == "remove") {
    checkPermission("Manage Credits");
    $date = getTodaysDate();
    $amount = "0.00";

    if ($action == "add") {
        $title = $aInt->lang("credit", "addcredit");
    } else {
        $title = $aInt->lang("credit", "removecredit");
    }

    $result = select_query_i("tblclients", "", array("id" => $userid));
    $data = mysqli_fetch_array($result);
    $creditbalance = formatCurrency($data['credit']);
    $template = 'client/addcredit';
} elseif ($action == "edit") {
    checkPermission("Manage Credits");
    $result = select_query_i("tblcredit", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $id = $data['id'];
    $date = $data['date'];
    $date = fromMySQLDate($date);
    $description = $data['description'];
    $amount = $data['amount'];
    echo "
<form method=\"post\" action=\"";
    echo $PHP_SELF;
    echo "?userid=";
    echo $userid;
    echo "&sub=save&id=";
    echo $id;
    echo "\">

<p><b>";
    echo $aInt->lang("credit", "addcredit");
    echo "</b></p>

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
    echo $aInt->lang("fields", "date");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"date\" size=\"12\" value=\"";
    echo $date;
    echo "\"></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "description");
    echo "</td><td class=\"fieldarea\"><textarea name=\"description\" cols=\"75\" rows=\"4\">";
    echo $description;
    echo "</textarea></td></tr>
<tr><td class=\"fieldlabel\">";
    echo $aInt->lang("fields", "amount");
    echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"amount\" size=\"15\" value=\"";
    echo $amount;
    echo "\"></td></tr>
</table>

<p align=center><input type=\"submit\" value=\"";
    echo $aInt->lang("global", "savechanges");
    echo "\" class=\"button\"></p>

</form>

";
} else {
    
}



$content = ob_get_contents();
ob_end_clean();
$today = fromMySQLDate(date('Y-m-d'));
$aInt->assign("action", $action);
$aInt->assign("today", $today);
$aInt->assign("creditbalance", $creditbalance);
$aInt->assign("name", $name);
$aInt->assign("userid", $userid);
$aInt->assign("token", generate_token("plain"));
$aInt->content = $content;
$aInt->template = $template;
$aInt->display();
?>
