<?php
/** RA - Version 0.1 **/

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("List Invoices", false);
$aInt->requiredFiles(array("gatewayfunctions", "invoicefunctions", "processinvoices"));
$aInt->inClientsProfile = true;

if ($delete || $massdelete) {
	checkPermission("Delete Invoice");
}


if (($markpaid || $markunpaid) || $markcancelled) {
	checkPermission("Manage Invoice");
}

$aInt->valUserID($userid);

if ($markpaid) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		$result2 = select_query_i("ra_bills", "paymentmethod", array("id" => $invid));
		$data = mysqli_fetch_array($result2);
		$paymentmethod = $data['paymentmethod'];
		addInvoicePayment($invid, "", "", "", $paymentmethod);
		run_hook("InvoicePaid", array("invoiceid" => $invoiceid));
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($markunpaid) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		update_query("ra_bills", array("status" => "Unpaid", "datepaid" => "0000-00-00 00:00:00"), array("id" => $invid));
		logActivity("Reactivated Invoice - Invoice ID: " . $invid, $userid);
		run_hook("InvoiceUnpaid", array("invoiceid" => $invid));
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($markcancelled) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		update_query("ra_bills", array("status" => "Cancelled"), array("id" => $invid));
		logActivity("Cancelled Invoice - Invoice ID: " . $invid, $userid);
		run_hook("InvoiceCancelled", array("invoiceid" => $invid));
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($duplicateinvoice) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		$result_duplicate = select_query_i("ra_bills", "userid,invoicenum,date,duedate,datepaid,subtotal,credit,tax,tax2,total,taxrate2,status,paymentmethod,notes", array("id" => $invid));
		$data_duplicate = mysqli_fetch_assoc($result_duplicate);
		$datefrom = fromMySQLDate($data_duplicate['date']);
		$date = toMySQLDate($datefrom);
		$duedatefrom = fromMySQLDate($data_duplicate['duedate']);
		$duedate = toMySQLDate($duedatefrom);
		$datepaidfrom = fromMySQLDate($data_duplicate['datepaid']);
		$datepaid = toMySQLDate($datepaidfrom);
		insert_query("ra_bills", array("userid" => $data_duplicate['userid'], "invoicenum" => $data_duplicate['invoicenum'], "date" => $date, "duedate" => $duedate, "datepaid" => $datepaid, "subtotal" => $data_duplicate['subtotal'], "credit" => $data_duplicate['credit'], "tax" => $data_duplicate['tax'], "tax2" => $data_duplicate['tax2'], "total" => $data_duplicate['total'], "taxrate2" => $data_duplicate['taxrate2'], "status" => $data_duplicate['status'], "paymentmethod" => $data_duplicate['paymentmethod'], "notes" => $data_duplicate['notes']), array("id" => $invid));
		logActivity("Duplicated Invoice(s) - Invoice ID: " . $invid, $userid);
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($massdelete) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		delete_query("ra_bills", array("id" => $invid));
		logActivity("Deleted Invoice - Invoice ID: " . $invid, $userid);
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($paymentreminder) {
	check_token("RA.admin.default");
	foreach ($selectedinvoices as $invid) {
		sendMessage("Invoice Payment Reminder", $invid);
		logActivity("Invoice Payment Reminder Sent - Invoice ID: " . $invid, $userid);
	}


	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($merge) {
	check_token("RA.admin.default");

	if (count($selectedinvoices) < 2) {
		if ($page) {
			$userid .= "&page=" . $page;
		}

		redir("userid=" . $userid . "&mergeerr=1");
		exit();
	}

	$selectedinvoices = db_escape_numarray($selectedinvoices);
	sort($selectedinvoices);
	$endinvoiceid = end($selectedinvoices);
	update_query("ra_bill_lineitems", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
	update_query("ra_transactions", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
	update_query("ra_orders", array("invoiceid" => $endinvoiceid), "invoiceid IN (" . db_build_in_array($selectedinvoices) . ")");
	$result = select_query_i("ra_bills", "SUM(credit)", "id IN (" . db_build_in_array($selectedinvoices) . ")");
	$data = mysqli_fetch_array($result);
	$totalcredit = $data[0];
	update_query("ra_bills", array("credit" => $totalcredit), array("id" => $endinvoiceid));
	unset($selectedinvoices[count($selectedinvoices) - 1]);
	delete_query("ra_bills", "id IN (" . db_build_in_array($selectedinvoices) . ")");
	updateInvoiceTotal($endinvoiceid);
	logActivity("Merged Invoice IDs " . db_build_in_array($selectedinvoices) . (" to Invoice ID: " . $endinvoiceid), $userid);

	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}


if ($masspay) {
	check_token("RA.admin.default");

	if (count($selectedinvoices) < 2) {
		if ($page) {
			$userid .= "&page=" . $page;
		}

		redir("userid=" . $userid . "&masspayerr=1");
		exit();
	}

	$invoiceid = createInvoices($userid);
	$paymentmethod = getClientsPaymentMethod($userid);
	$invoiceitems = array();
	foreach ($selectedinvoices as $invoiceid) {
		$result = select_query_i("ra_bills", "", array("id" => $invoiceid));
		$data = mysqli_fetch_array($result);
		$subtotal += $data['subtotal'];
		$credit += $data['credit'];
		$tax += $data['tax'];
		$tax2 += $data['tax2'];
		$thistotal = $data['total'];
		$result = select_query_i("ra_transactions", "SUM(amountin)", array("invoiceid" => $invoiceid));
		$data = mysqli_fetch_array($result);
		$thispayments = $data[0];
		$thistotal = $thistotal - $thispayments;
		insert_query("ra_bill_lineitems", array("userid" => $userid, "type" => "Invoice", "relid" => $invoiceid, "description" => $_LANG['invoicenumber'] . $invoiceid, "amount" => $thistotal, "duedate" => "now()", "paymentmethod" => $paymentmethod));
	}

	$invoiceid = createInvoices($userid, true, true);
	redir("userid=" . $userid . "&masspayid=" . $invoiceid . "&filter=1");
}


if ($delete) {
	check_token("RA.admin.default");
	checkPermission("Delete Invoice");
	delete_query("ra_bills", array("id" => $invoiceid));
	logActivity("Deleted Invoice - Invoice ID: " . $invoiceid, $userid);

	if ($page) {
		$userid .= "&page=" . $page;
	}

	redir("userid=" . $userid . "&filter=1");
}

ob_start();
$aInt->deleteJSConfirm("doDelete", "invoices", "delete", "clientsinvoices.php?userid=" . $userid . "&delete=true&invoiceid=");
$jquerycode .= "$(\".invtooltip\").tooltip({cssClass:\"invoicetooltip\"});";

if ($mergeerr) {
	infoBox($aInt->lang("invoices", "mergeerror"), $aInt->lang("invoices", "mergeerrordesc"));
}


if ($masspayerr) {
	infoBox($aInt->lang("invoices", "masspay"), $aInt->lang("invoices", "mergeerrordesc"));
}


if ($masspayid) {
	infoBox($aInt->lang("invoices", "masspay"), $aInt->lang("invoices", "masspaysuccess") . " - <a href=\"invoices.php?action=edit&id=" . (int)$masspayid . "\">" . $aInt->lang("fields", "invoicenum") . $masspayid . "</a>");
}

echo $infobox;
$filt = new RA_Filter("clinv");
$filterops = array("serviceid", "addonid", "domainid", "clientname", "invoicenum", "lineitem", "paymentmethod", "invoicedate", "duedate", "datepaid", "totalfrom" . "totalto", "status");
$filt->setAllowedVars($filterops);
$filters = array();
$filters[] = "userid='" . (int)$userid . "'";

if ($serviceid = $filt->get("serviceid")) {
	$filters[] = "id IN (SELECT invoiceid FROM ra_bill_lineitems WHERE type='Hosting' AND relid='" . (int)$serviceid . "')";
}


if ($addonid = $filt->get("addonid")) {
	$filters[] = "id IN (SELECT invoiceid FROM ra_bill_lineitems WHERE type='Addon' AND relid='" . (int)$addonid . "')";
}


if ($domainid = $filt->get("domainid")) {
	$filters[] = "id IN (SELECT invoiceid FROM ra_bill_lineitems WHERE type IN ('DomainRegister','DomainTransfer','Domain') AND relid='" . (int)$domainid . "')";
}


if ($clientname = $filt->get("clientname")) {
	$filters[] = "concat(firstname,' ',lastname) LIKE '%" . db_escape_string($clientname) . "%'";
}


if ($invoicenum = $filt->get("invoicenum")) {
	$filters[] = "(ra_bills.id='" . db_escape_string($invoicenumber) . "' OR ra_bills.invoicenum='" . db_escape_string($invoicenumber) . "')";
}


if ($lineitem = $filt->get("lineitem")) {
	$filters[] = "ra_bills.id IN (SELECT invoiceid FROM ra_bill_lineitems WHERE userid=" . (int)$userid . " AND description LIKE '%" . db_escape_string($lineitem) . "%')";
}

if ($paymentmethod = $filt->get("paymentmethod")) {
	$filters[] = "ra_bills.paymentmethod='" . db_escape_string($paymentmethod) . "'";
}


if ($invoicedate = $filt->get("invoicedate")) {
	$filters[] = "ra_bills.date='" . toMySQLDate($invoicedate) . "'";
}


if ($duedate = $filt->get("duedate")) {
	$filters[] = "ra_bills.duedate='" . toMySQLDate($duedate) . "'";
}


if ($datepaid = $filt->get("datepaid")) {
	$filters[] = "ra_bills.datepaid>='" . toMySQLDate($datepaid) . "' AND ra_bills.datepaid<='" . toMySQLDate($datepaid) . " 23:59:59'";
}


if ($totalfrom = $filt->get("totalfrom")) {
	$filters[] = "ra_bills.total>='" . db_escape_string($totalfrom) . "'";
}


if ($totalto = $filt->get("totalto")) {
	$filters[] = "ra_bills.total<='" . db_escape_string($totalto) . "'";
}


if ($status = $filt->get("status")) {
	if ($status == "Overdue") {
		$filters[] = "ra_bills.status='Unpaid' AND ra_bills.duedate<'" . date("Ymd") . "'";
	}
	else {
		$filters[] = "ra_bills.status='" . db_escape_string($status) . "'";
	}
}

$filt->store();
releaseSession();
echo "\r\n";
echo "<s";
echo "cript src=\"../includes/jscript/jquerytt.js\"></script>

<form action=\"";
echo $PHP_SELF;
echo "?userid=";
echo $userid;
echo "\" method=\"post\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "invoicenum");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"invoicenum\" size=\"25\" value=\"";
echo $clientname;
echo "\"></td><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "invoicedate");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"invoicedate\" size=\"15\" value=\"";
echo $invoicedate;
echo "\" class=\"datepick\"></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "lineitem");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"lineitem\" size=\"40\" value=\"";
echo $lineitem;
echo "\"></td><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "duedate");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"duedate\" size=\"15\" value=\"";
echo $duedate;
echo "\" class=\"datepick\"></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "paymentmethod");
echo "</td><td class=\"fieldarea\">";
echo paymentMethodsSelection($aInt->lang("global", "any"));
echo "</td><td width=\"15%\" class=\"fieldlabel\">";
echo $aInt->lang("fields", "datepaid");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"datepaid\" size=\"15\" value=\"";
echo $datepaid;
echo "\" class=\"datepick\"></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "status");
echo "</td><td class=\"fieldarea\">";
echo "<s";
echo "elect name=\"status\">
<option value=\"\">";
echo $aInt->lang("global", "any");
echo "</option>
<option value=\"Unpaid\"";

if ($status == "Unpaid") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "unpaid");
echo "</option>
<option value=\"Overdue\"";

if ($status == "Overdue") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "overdue");
echo "</option>
<option value=\"Paid\"";

if ($status == "Paid") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "paid");
echo "</option>
<option value=\"Cancelled\"";

if ($status == "Cancelled") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "cancelled");
echo "</option>
<option value=\"Refunded\"";

if ($status == "Refunded") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "refunded");
echo "</option>
<option value=\"Collections\"";

if ($status == "Collections") {
	echo " selected";
}

echo ">";
echo $aInt->lang("status", "collections");
echo "</option>
</select></td><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "totaldue");
echo "</td><td class=\"fieldarea\">";
echo $aInt->lang("filters", "from");
echo " <input type=\"text\" name=\"totalfrom\" size=\"10\" value=\"";
echo $totalfrom;
echo "\"> ";
echo $aInt->lang("filters", "to");
echo " <input type=\"text\" name=\"totalto\" size=\"10\" value=\"";
echo $totalto;
echo "\"></td></tr>
<tr></tr>
</table>
<img src=\"images/spacer.gif\" height=\"5\" width=\"1\" /><br />
<div align=\"center\"><input type=\"submit\" value=\"";
echo $aInt->lang("global", "search");
echo "\" class=\"button\" /> <input type=\"button\" value=\"";
echo $aInt->lang("invoices", "create");
echo "\" class=\"button\" onClick=\"window.location='invoices.php?action=createinvoice&userid=";
echo $userid . generate_token("link");
echo "'\" class=\"btn-success\" /></div>
</form>

";
$currency = getCurrency($userid);
$gatewaysarray = getGatewaysArray();
$aInt->sortableTableInit("duedate", "DESC");
$result = select_query_i("ra_bills", "COUNT(*)", implode(" AND ", $filters));
$data = mysqli_fetch_array($result);
$numrows = $data[0];
$qryorderby = $orderby;

if ($qryorderby == "id") {
	$qryorderby = "ra_bills`.`invoicenum` " . $order . ",`ra_bills`.`id";
}

$result = select_query_i("ra_bills", "", implode(" AND ", $filters), $qryorderby, $order, $page * $limit . ("," . $limit));

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$invoicenum = $data['invoicenum'];
	$date = $data['date'];
	$duedate = $data['duedate'];
	$datepaid = $data['datepaid'];
	$credit = $data['credit'];
	$total = $data['total'];
	$paymentmethod = $data['paymentmethod'];
	$paymentmethod = $gatewaysarray[$paymentmethod];
	$status = $data['status'];
	$status = getInvoiceStatusColour($status, false);
	$date = fromMySQLDate($date);
	$duedate = fromMySQLDate($duedate);
	$datepaid = ($datepaid == "0000-00-00 00:00:00" ? "-" : fromMySQLDate($datepaid));
	$total = formatCurrency($credit + $total);

	if (!$invoicenum) {
		$invoicenum = $id;
	}

	$tabledata[] = array("<input type=\"checkbox\" name=\"selectedinvoices[]\" value=\"" . $id . "\" class=\"checkall\">", "<a href=\"invoices.php?action=edit&id=" . $id . "\">" . $invoicenum . "</a>", $date, $duedate, $datepaid, "<a href=\"invoices.php?action=invtooltip&id=" . $id . "&userid=" . $userid . generate_token("link") . ("\" class=\"invtooltip\" lang=\"\">" . $total . "</a>"), $paymentmethod, $status, "<a href=\"invoices.php?action=edit&id=" . $id . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
}

$tableformurl = $_SERVER['PHP_SELF'] . "?userid=" . $userid . "&filter=1";

if ($page) {
	$tableformurl .= "&page=" . $page;
}

$tableformbuttons = "<input type=\"submit\" value=\"" . $aInt->lang("invoices", "markpaid") . "\" class=\"btn-success\" name=\"markpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markunpaid") . "\" name=\"markunpaid\" onclick=\"return confirm('" . $aInt->lang("invoices", "markunpaidconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "markcancelled") . "\" name=\"markcancelled\" onclick=\"return confirm('" . $aInt->lang("invoices", "markcancelledconfirm", "1") . "')\" />   <input type=\"submit\" value=\"" . $aInt->lang("invoices", "duplicateinvoice") . "\" name=\"duplicateinvoice\" onclick=\"return confirm('" . $aInt->lang("invoices", "duplicateinvoiceconfirm", "1") . "')\" />   <input type=\"submit\" value=\"" . $aInt->lang("invoices", "sendreminder") . "\" name=\"paymentreminder\" onclick=\"return confirm('" . $aInt->lang("invoices", "sendreminderconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "merge") . "\" name=\"merge\" onclick=\"return confirm('" . $aInt->lang("invoices", "mergeconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("invoices", "masspay") . "\" name=\"masspay\" onclick=\"return confirm('" . $aInt->lang("invoices", "masspayconfirm", "1") . "')\" /> <input type=\"submit\" value=\"" . $aInt->lang("global", "delete") . "\" class=\"btn-danger\" name=\"massdelete\" onclick=\"return confirm('" . $aInt->lang("invoices", "massdeleteconfirm", "1") . "')\" />";
echo $aInt->sortableTable(array("checkall", array("id", $aInt->lang("fields", "invoicenum")), array("date", $aInt->lang("fields", "invoicedate")), array("duedate", $aInt->lang("fields", "duedate")), array("datepaid", $aInt->lang("fields", "datepaid")), array("total", $aInt->lang("fields", "total")), array("paymentmethod", $aInt->lang("fields", "paymentmethod")), array("status", $aInt->lang("fields", "status")), "", ""), $tabledata, $tableformurl, $tableformbuttons);
$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->template ="client/invoices";
$aInt->display();
?>