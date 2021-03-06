<?php
/** RA - Version 0.1 **/


define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Order Statuses");
$aInt->title = $aInt->lang("setup", "orderstatuses");
$aInt->sidebar = "config";
$aInt->icon = "clients";
$aInt->helplink = "Order Statuses";

if ($action == "save") {
	check_token("RA.admin.default");

	if ($id) {
		update_query("ra_orderstatuses", array("title" => $title, "color" => $color, "showpending" => $showpending, "showactive" => $showactive, "showcancelled" => $showcancelled, "sortorder" => $sortorder), array("id" => $id));
		header("Location: configorderstatuses.php?update=true");
	}
	else {
		insert_query("ra_orderstatuses", array("title" => $title, "color" => $color, "showpending" => $showpending, "showactive" => $showactive, "showcancelled" => $showcancelled, "sortorder" => $sortorder));
		header("Location: configorderstatuses.php?added=true");
	}

	exit();
}


if ($action == "delete") {
	check_token("RA.admin.default");

	if (4 < $id) {
		$title = get_query_val("ra_orderstatuses", "title", array("id" => $id));
		update_query("ra_orders", array("status" => "Cancelled"), array("status" => $title));
		delete_query("ra_orderstatuses", array("id" => $id));
		header("Location: configorderstatuses.php?delete=true");
	}
	else {
		header("Location: configorderstatuses.php");
	}

	exit();
}

ob_start();

if ($added) {
	infoBox($aInt->lang("orderstatusconfig", "addtitle"), $aInt->lang("orderstatusconfig", "adddesc"));
}


if ($update) {
	infoBox($aInt->lang("orderstatusconfig", "edittitle"), $aInt->lang("orderstatusconfig", "editdesc"));
}


if ($delete) {
	infoBox($aInt->lang("orderstatusconfig", "deltitle"), $aInt->lang("orderstatusconfig", "deldesc"));
}

echo $infobox;
$aInt->deleteJSConfirm("doDelete", "orderstatusconfig", "delsure", "?action=delete&id=");
echo "
<p>";
echo $aInt->lang("orderstatusconfig", "pagedesc");
echo "</p>

<p>";
echo "<s";
echo "trong>";
echo $aInt->lang("fields", "options");
echo ":</strong> <a href=\"";
echo $PHP_SELF;
echo "\"><img src=\"images/icons/add.png\" align=\"top\" /> ";
echo $aInt->lang("global", "addnew");
echo "</a></p>

";
$aInt->sortableTableInit("nopagination");
$result = select_query_i("ra_orderstatuses", "", "", "sortorder", "ASC");

while ($data = mysqli_fetch_assoc($result)) {
	$statusid = $data['id'];
	$title = $data['title'];
	$color = $data['color'];
	$showpending = $data['showpending'];
	$showactive = $data['showactive'];
	$showcancelled = $data['showcancelled'];
	$sortorder = $data['sortorder'];
	$showpending = ($showpending ? "<img src=\"images/icons/tick.png\">" : "<img src=\"images/icons/disabled.png\">");
	$showactive = ($showactive ? "<img src=\"images/icons/tick.png\">" : "<img src=\"images/icons/disabled.png\">");
	$showcancelled = ($showcancelled ? "<img src=\"images/icons/tick.png\">" : "<img src=\"images/icons/disabled.png\">");

	if (4 < $statusid) {
		$delete = "<a href=\"#\" onClick=\"doDelete('" . $statusid . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>";
	}
	else {
		$delete = "";
	}

	$tabledata[] = array("<span style=\"font-weight:bold;color:" . $color . "\">" . $title . "</span>", $showpending, $showactive, $showcancelled, $sortorder, "<a href=\"" . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $statusid . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", $delete);
}

echo $aInt->sortableTable(array($aInt->lang("fields", "title"), $aInt->lang("orderstatusconfig", "includeinpending"), $aInt->lang("orderstatusconfig", "includeinactive"), $aInt->lang("orderstatusconfig", "includeincancelled"), $aInt->lang("products", "sortorder"), "", ""), $tabledata);
echo "
";
echo "<s";
echo "cript type=\"text/javascript\" src=\"../includes/jscript/jquery.miniColors.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../includes/jscript/css/jquery.miniColors.css\" />
";
$jquerycode = "$(\".colorpicker\").miniColors();";
echo "
<h2>";

if ($action == "edit") {
	$data = get_query_vals("ra_orderstatuses", "", array("id" => $id));
	extract($data);
	echo $aInt->lang("orderstatusconfig", "edit");
}
else {
	$title = $showpending = $showactive = $showcancelled = "";
	$color = "#000000";
	echo $aInt->lang("orderstatusconfig", "addnew");
}

echo "</h2>

<form method=\"post\" action=\"";
echo $PHP_SELF;
echo "?action=save&id=";
echo $id;
echo "\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"25%\" class=\"fieldlabel\">";
echo $aInt->lang("clientsummary", "filetitle");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"title\" size=\"30\" value=\"";
echo $title;
echo "\"";

if ($id && $id <= 4) {
	echo " readonly=\"true\"";
}

echo " /></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("orderstatusconfig", "color");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"color\" size=\"10\" value=\"";
echo $color;
echo "\" class=\"colorpicker\" /></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("orderstatusconfig", "includeinpending");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"showpending\" value=\"1\"";

if ($showpending) {
	echo " checked";
}

echo " /></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("orderstatusconfig", "includeinactive");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"showactive\" value=\"1\"";

if ($showactive) {
	echo " checked";
}

echo " /></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("orderstatusconfig", "includeincancelled");
echo "</td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"showcancelled\" value=\"1\"";

if ($showcancelled) {
	echo " checked";
}

echo " /></td></tr>
<tr><td width=\"25%\" class=\"fieldlabel\">";
echo $aInt->lang("products", "sortorder");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"sortorder\" size=\"10\" value=\"";
echo $sortorder;
echo "\" /></td></tr>
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