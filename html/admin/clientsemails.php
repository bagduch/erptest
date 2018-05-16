<?php

/**
 *
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Email Message Log");
$aInt->inClientsProfile = true;

if ($displaymessage == "true") {
    $aInt->title = $aInt->lang("emails", "viewemail");
    $result = select_query_i("ra_user_mail", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $date = $data['date'];
    $to = (is_null($data['to']) ? $aInt->lang("emails", "registeredemail") : $data['to']);
    $cc = $data['cc'];
    $bcc = $data['bcc'];
    $subject = $data['subject'];
    $message = $data['message'];
    $content = "<p><b>" . $aInt->lang("emails", "to") . ":</b> " . $to . "<br />";

    if ($cc) {
        $content .= "<b>" . $aInt->lang("emails", "cc") . ":</b> " . $cc . "<br />";
    }

    if ($bcc) {
        $content .= "<b>" . $aInt->lang("emails", "bcc") . ":</b> " . $bcc . "<br />";
    }

    $content .= "<b>" . $aInt->lang("emails", "subject") . ":</b> " . $subject . "</p>
    " . $message;
    $aInt->title = $aInt->lang("emails", "viewemailmessage");
    $aInt->content = $content;
    $aInt->displayPopUp();
    exit();
}

if ($action == "send" && $messagename == "newmessage") {
    redir("type=" . $type . "&id=" . $id, "sendmessage.php");
}

if ($action == "delete") {
    check_token("RA.admin.default");
    delete_query("ra_user_mail", array("id" => $id));
    redir("userid=" . $userid);
}

$aInt->valUserID($userid);

$jscode = "";

if ($action == "send") {
    check_token("RA.admin.default");
    sendMessage($messagename, $id, "", true);
}

$aInt->deleteJSConfirm("doDelete", "emails", "suredelete", "clientsemails.php?userid=" . $userid . "&action=delete&id=");
$aInt->sortableTableInit("date", "DESC");
$result = select_query_i("ra_user_mail", "COUNT(*)", array("userid" => $userid));
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$result = select_query_i("ra_user_mail", "", array("userid" => $userid), $orderby, $order);
while ($data = mysqli_fetch_array($result)) {
    $id = $data['id'];
    $date = $data['date'];
    $date = fromMySQLDate($date, "time");
    $subject = $data['subject'];

    if ($subject == "") {
        $subject = $aInt->lang("emails", "nosubject");
    }

    $tabledata[] = array($date, "<a href=\"#\" onClick=\"window.open('clientsemails.php?&displaymessage=true&id=" . $id . "','','width=650,height=400,scrollbars=yes');return false\">" . $subject . "</a>", "<a href=\"sendmessage.php?resend=true&emailid=" . $id . "\"><img src=\"images/icons/resendemail.png\" border=\"0\" alt=\"" . $aInt->lang("emails", "resendemail") . "\"></a>", "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $aInt->lang("global", "delete") . "\" /></a>");
}

$table = $aInt->sortableTable(array(array("date", $aInt->lang("fields", "date")), array("subject", $aInt->lang("emails", "subject")), "", ""), $tabledata);
$aInt->template = 'client/email';
$aInt->assign('table',$table);
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>
