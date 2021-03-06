<?php
/** RA - Version 0.1 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("List Addons");
$aInt->title = $aInt->lang("services", "listaddons");
$aInt->sidebar = "clients";
$aInt->icon = "productaddons";
$aInt->requiredFiles(array("gatewayfunctions"));
ob_start();
$predefinedaddons = array();
$result = select_query_i("tbladdons", "", "");

while ($data = mysqli_fetch_array($result)) {
	$addon_id = $data['id'];
	$addon_name = $data['name'];
	$predefinedaddons[$addon_id] = $addon_name;
}

$aInt->sortableTableInit("id", "DESC");
$query = "FROM ra_catalog_user_sales_addons INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid INNER JOIN ra_catalog ON tblcustomerservices.packageid=ra_catalog.id WHERE ra_catalog_user_sales_addons.id!='' ";

if ($clientname) {
	$query .= "AND concat(firstname,' ',lastname) LIKE '%" . db_escape_string($clientname) . "%' ";
}


if ($addon) {
	$query .= (is_numeric($addon) ? "AND ra_catalog_user_sales_addons.addonid='" . $addon . "'" : "AND ra_catalog_user_sales_addons.name='" . db_escape_string($addon) . "' ");
}


if ($type != "") {
	$query .= "AND ra_catalog.type='" . db_escape_string($type) . "' ";
}


if ($package != "") {
	$query .= "AND ra_catalog.id='" . db_escape_string($package) . "' ";
}


if ($billingcycle != "") {
	$query .= "AND ra_catalog_user_sales_addons.billingcycle='" . db_escape_string($billingcycle) . "' ";
}


if ($server != "") {
	$query .= "AND tblcustomerservices.server='" . db_escape_string($server) . "' ";
}


if ($paymentmethod != "") {
	$query .= "AND ra_catalog_user_sales_addons.paymentmethod='" . db_escape_string($paymentmethod) . "' ";
}


if ($status != "") {
	$query .= "AND ra_catalog_user_sales_addons.status='" . db_escape_string($status) . "' ";
}


if ($domain != "") {
	$query .= "AND tblcustomerservices.domain LIKE '%" . db_escape_string($domain) . "%' ";
}

$result = full_query_i("SELECT COUNT(ra_catalog_user_sales_addons.id) " . $query);
$data = mysqli_fetch_array($result);
$numrows = $data[0];
echo $aInt->Tabs(array($aInt->lang("global", "searchfilter")), true);
echo "
<div id=\"tab0box\" class=\"tabbox\">
  <div id=\"tab_content\">

<form action=\"";
echo $PHP_SELF;
echo "\" method=\"post\">

<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "addon");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"addon\">
<option value=\"\">";
echo $aInt->lang("global", "any");
echo "</option>
";
$result = select_query_i("tbladdons", "id,name", "", "name", "ASC");

while ($data = mysqli_fetch_array($result)) {
	$addon_id = $data['id'];
	$addon_name = $data['name'];
	$predefinedaddons[$addon_id] = $addon_name;
	echo "<option value=\"" . $addon_id . "\"";
	if ($addon == $addon_id) {
		echo " selected";
	}
	echo ">" . $addon_name . "</option>";
}

$query2 = "SELECT DISTINCT name FROM ra_catalog_user_sales_addons WHERE name!='' ORDER BY name ASC";
$result2 = full_query_i($query2);

while ($data = mysqli_fetch_array($result2)) {
	$addon_name = $data['name'];
	echo "<option";

	if ($addon == $addon_name) {
		echo " selected";
	}

	echo ">" . $addon_name . "</option>";
}

echo "</select></td><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "producttype");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"type\">
<option value=\"\">";
echo $aInt->lang("global", "any");
echo "</option>
<option value=\"hostingaccount\"";

if ($type == "hostingaccount") {
	echo " selected";
}

echo ">";
echo $aInt->lang("orders", "sharedhosting");
echo "</option>
<option value=\"reselleraccount\"";

if ($type == "reselleraccount") {
	echo " selected";
}

echo ">";
echo $aInt->lang("orders", "resellerhosting");
echo "</option>
<option value=\"server\"";

if ($type == "server") {
	echo " selected";
}

echo ">";
echo $aInt->lang("orders", "server");
echo "</option>
<option value=\"other\"";

if ($type == "other") {
	echo " selected";
}

echo ">";
echo $aInt->lang("orders", "other");
echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "product");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"package\">";
echo $aInt->productDropDown($package, 0, true);
echo "</select></td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "paymentmethod");
echo "</td><td class=\"fieldarea\">";
echo paymentMethodsSelection($aInt->lang("global", "any"));
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "billingcycle");
echo "</td><td class=\"fieldarea\">";
echo $aInt->cyclesDropDown($billingcycle, true);
echo "</td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "status");
echo "</td><td class=\"fieldarea\">";
echo $aInt->productStatusDropDown($status, true);
echo "</td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "domain");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"domain\" size=\"35\" value=\"";
echo $domain;
echo "\"></td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "clientname");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"clientname\" size=\"25\" value=\"";
echo $clientname;
echo "\"></td></tr>
</table>

<img src=\"images/spacer.gif\" height=\"10\" width=\"1\"><br>
<DIV ALIGN=\"center\"><input type=\"submit\" value=\"";
echo $aInt->lang("global", "search");
echo "\" class=\"button\"></DIV>

</form>

  </div>
</div>

<br>

";
$query .= "ORDER BY ";

if ($orderby == "addon") {
	$query .= "ra_catalog_user_sales_addons.name";
}
else {
	if ($orderby == "product") {
		$query .= "ra_catalog.name";
	}
	else {
		if ($orderby == "amount") {
			$query .= "recurring";
		}
		else {
			if ($orderby == "clientname") {
				$query .= "ra_user.firstname " . $order . ",ra_user.lastname";
			}
			else {
				$query .= $orderby;
			}
		}
	}
}

$query .= " " . $order;
$query = "SELECT ra_catalog_user_sales_addons.*,ra_catalog_user_sales_addons.name AS addonname,tblcustomerservices.domain,tblcustomerservices.userid,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid,ra_user.currency,ra_catalog.name,ra_catalog.type " . $query . " LIMIT " . (int)$page * $limit . "," . (int)$limit;
$result = full_query_i($query);

while ($data = mysqli_fetch_array($result)) {
	$aid = $data['id'];
	$id = $data['hostingid'];
	$addonid = $data['addonid'];
	$userid = $data['userid'];
	$addonname = $data['addonname'];
	$domain = $data['domain'];
	$dtype = $data['type'];
	$dpackage = $data['name'];
	$upgrades = $data['upgrades'];
	$dpaymentmethod = $data['paymentmethod'];
	$amount = $data['recurring'];
	$billingcycle = $data['billingcycle'];
	$nextduedate = $data['nextduedate'];
	$status = $data['status'];

	if (!$addonname) {
		$addonname = $predefinedaddons[$addonid];
	}

	$regdate = fromMySQLDate($regdate);
	$nextduedate = fromMySQLDate($nextduedate);
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	$companyname = $data['companyname'];
	$groupid = $data['groupid'];
	$currency = $data['currency'];

	if (!$domain) {
		$domain = "(" . $aInt->lang("addons", "nodomain") . ")";
	}

	$currency = getCurrency("", $currency);
	$amount = formatCurrency($amount);

	if (($billingcycle == "One Time" || $billingcycle == "Free Account") || $billingcycle == "Free") {
		$nextduedate = "-";
	}

	$billingcycle = $aInt->lang("billingcycles", str_replace(array("-", "account", " "), "", strtolower($billingcycle)));
	$tabledata[] = array("<input type=\"checkbox\" name=\"selectedclients[]\" value=\"" . $id . "\" class=\"checkall\" />", "<a href=\"clientsservices.php?userid=" . $userid . "&id=" . $id . "&aid=" . $aid . "\">" . $aid . "</a>", $addonname . " <span class=\"label " . strtolower($status) . "\">" . $status . "</span>", "<a href=\"clientsservices.php?userid=" . $userid . "&id=" . $id . "\">" . $dpackage . "</a>", $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid), $billingcycle, $amount, $nextduedate);
}

$tableformurl = "sendmessage.php?type=product&multiple=true";
$tableformbuttons = "<input type=\"submit\" value=\"" . $aInt->lang("global", "sendmessage") . "\" class=\"button\">";
echo $aInt->sortableTable(array("checkall", array("id", $aInt->lang("fields", "id")), array("addon", $aInt->lang("fields", "addon")), array("product", $aInt->lang("fields", "product")), array("clientname", $aInt->lang("fields", "clientname")), array("billingcycle", $aInt->lang("fields", "billingcycle")), array("amount", $aInt->lang("fields", "price")), array("nextduedate", $aInt->lang("fields", "nextduedate"))), $tabledata, $tableformurl, $tableformbuttons);
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>