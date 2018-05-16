<?php
/** RA - Version 0.1 **/


define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Email Message Log");
$aInt->title = $aInt->lang("system", "emailmessagelog");
$aInt->sidebar = "utilities";
$aInt->icon = "logs";
$menuselect = "$('#menu').multilevelpushmenu('expand','Utilities');";
$aInt->sortableTableInit("date");
$result = select_query_i("ra_user_mail,ra_user", "COUNT(ra_user_mail.id)", "ra_user_mail.userid=ra_user.id");
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$result = select_query_i("ra_user_mail,ra_user", "ra_user_mail.id,ra_user_mail.date,ra_user_mail.subject,ra_user_mail.userid,ra_user.firstname,ra_user.lastname", "ra_user_mail.userid=ra_user.id", "ra_user_mail`.`id", "DESC", $page * $limit . ("," . $limit));

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$date = $data['date'];
	$subject = $data['subject'];
	$userid = $data['userid'];
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	$tabledata[] = array(fromMySQLDate($date, "time"), "<a href=\"#\" onClick=\"window.open('clientsemails.php?&displaymessage=true&id=" . $id . "','','width=650,height=400,scrollbars=yes');return false\">" . $subject . "</a>", "<a href=\"clientssummary.php?userid=" . $userid . "\">" . $firstname . " " . $lastname . "</a>", "<a href=\"sendmessage.php?resend=true&emailid=" . $id . "\"><img src=\"images/icons/resendemail.png\" border=\"0\" alt=\"" . $aInt->lang("emails", "resendemail") . "\"></a>");
}

$content = $aInt->sortableTable(array($aInt->lang("fields", "date"), $aInt->lang("fields", "subject"), $aInt->lang("system", "recepient"), ""), $tabledata);
$aInt->content = $content;
$aInt->jquerycode.=$menuselect;
$aInt->display();
?>