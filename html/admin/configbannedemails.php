<?php
/** RA - Version 0.1 **/


define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Banned Emails");
$aInt->title = $aInt->lang("bans", "emailtitle");
$aInt->sidebar = "config";
$aInt->icon = "configbans";
$aInt->helplink = "Security/Ban Control";

if ($email) {
	check_token("RA.admin.default");
	insert_query("ra_bannedemails", array("domain" => $email));
	redir("success=true");
	exit();
}


if ($action == "delete") {
	check_token("RA.admin.default");
	delete_query("ra_bannedemails", array("id" => $id));
	redir("delete=true");
	exit();
}

ob_start();

if ($success) {
	infoBox($aInt->lang("bans", "emailaddsuccess"), $aInt->lang("bans", "emailaddsuccessinfo"));
}


if ($delete) {
	infoBox($aInt->lang("bans", "emaildelsuccess"), $aInt->lang("bans", "emaildelsuccessinfo"));
}

echo $infobox;
$aInt->deleteJSConfirm("doDelete", "bans", "emaildelsure", "?action=delete&id=");
echo $aInt->Tabs(array($aInt->lang("global", "add")), true);
echo "
<div id=\"tab0box\" class=\"tabbox\">
  <div id=\"tab_content\">

<form method=\"post\" action=\"";
echo $PHP_SELF;
echo "\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "email");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"email\" size=\"50\"> (";
echo $aInt->lang("bans", "onlydomain");
echo ")</td></tr>
</table>

<img src=\"images/spacer.gif\" height=\"10\" width=\"1\"><br>
<div align=\"center\"><input type=\"submit\" value=\"";
echo $aInt->lang("bans", "addbannedemail");
echo "\" class=\"button\"></div>

</form>

  </div>
</div>

<br>

";
$aInt->sortableTableInit("nopagination");
$result = select_query_i("ra_bannedemails", "", "", "domain", "ASC");

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$domain = $data['domain'];
	$count = $data['count'];
	$tabledata[] = array($domain, $count, "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
}

echo $aInt->sortableTable(array($aInt->lang("bans", "emaildomain"), $aInt->lang("bans", "usagecount"), ""), $tabledata);
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>