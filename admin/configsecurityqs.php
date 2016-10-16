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
 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Security Questions");
$aInt->title = $aInt->lang("setup", "securityqs");
$aInt->sidebar = "config";
$aInt->icon = "securityquestions";
$aInt->helplink = "Security Questions";

if ($action == "savequestion") {
	check_token("RA.admin.default");

	if ($id) {
		update_query("tbladminsecurityquestions", array("question" => encrypt($addquestion)), array("id" => $id));
		header("Location: configsecurityqs.php?update=true");
	}
	else {
		insert_query("tbladminsecurityquestions", array("question" => encrypt($addquestion)));
		header("Location: configsecurityqs.php?added=true");
	}
}


if ($action == "delete") {
	check_token("RA.admin.default");
	$result = select_query_i("tblclients", "", array("securityqid" => $id));
	$numaccounts = mysqli_num_rows($result);

	if (0 < $numaccounts) {
		header("Location: configsecurityqs.php?deleteerror=true");
		exit();
	}
	else {
		delete_query("tbladminsecurityquestions", array("id" => $id));
		header("Location: configsecurityqs.php?deletesuccess=true");
		exit();
	}
}

ob_start();

if ($deletesuccess) {
	infoBox($aInt->lang("securityquestionconfig", "delsuccess"), $aInt->lang("securityquestionconfig", "delsuccessinfo"));
}


if ($deleteerror) {
	infoBox($aInt->lang("securityquestionconfig", "error"), $aInt->lang("securityquestionconfig", "errorinfo"));
}


if ($added) {
	infoBox($aInt->lang("securityquestionconfig", "addsuccess"), $aInt->lang("securityquestionconfig", "changesuccessinfo"));
}


if ($update) {
	infoBox($aInt->lang("securityquestionconfig", "changesuccess"), $aInt->lang("securityquestionconfig", "changesuccessinfo"));
}

echo $infobox;
$aInt->deleteJSConfirm("doDelete", "securityquestionconfig", "delsuresecurityquestion", "?action=delete&id=");
echo "
<h2>";
echo $aInt->lang("securityquestionconfig", "questions");
echo "</h2>

";
$aInt->sortableTableInit("nopagination");
$result = select_query_i("tbladminsecurityquestions", "", "");

while ($data = mysqli_fetch_assoc($result)) {
	$count = select_query_i("tblclients", "count(securityqid) as cnt", array("securityqid" => $data['id']));
	$count_data = mysqli_fetch_assoc($count);
	$cnt = (is_null($count_data['cnt']) ? "0" : $count_data['cnt']);
	$tabledata[] = array(decrypt($data['question']), $cnt, "<a href=\"" . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $data['id'] . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $data['id'] . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
}

echo $aInt->sortableTable(array($aInt->lang("securityquestionconfig", "question"), $aInt->lang("securityquestionconfig", "uses"), "", ""), $tabledata);
echo "
<h2>";

if ($action == "edit") {
	$result = select_query_i("tbladminsecurityquestions", "", array("id" => $id));
	$data = mysqli_fetch_array($result);
	$question = decrypt($data['question']);
	echo $aInt->lang("securityquestionconfig", "edit");
}
else {
	echo $aInt->lang("securityquestionconfig", "add");
}

echo "</h2>

<form method=\"post\" action=\"";
echo $PHP_SELF;
echo "?action=savequestion&id=";
echo $id;
echo "\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "securityquestion");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"addquestion\" value=\"";
echo $question;
echo "\" size=\"100\" /></td></tr>
</table>
<p align=center><input type=\"submit\" value=\"";
echo $aInt->lang("global", "savechanges");
echo "\" class=\"button\"></p>
</form>

";
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>