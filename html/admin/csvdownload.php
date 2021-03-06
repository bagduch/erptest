<?php
/** RA - Version 0.1 **/


function csv_clean($var) {
	$var = html_entity_decode($var, ENT_QUOTES);
	$var = strip_tags($var);
	$var = str_replace(",", "", $var);
	return $var;
}

function csv_output($query) {
	global $fields;

	$result = full_query_i($query);

	while ($data = mysqli_fetch_array($result)) {
		foreach ($fields as $field) {
			echo csv_clean($data[$field]) . ",";
		}

		echo "\r\n";
	}

}

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("CSV Downloads");
header("Pragma: public");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, private");
header("Cache-Control: private", false);
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: binary");
$report = $ra->get_req_var("report");
$type = $ra->get_req_var("type");
$print = $ra->get_req_var("print");
$currencyid = $ra->get_req_var("currencyid");
$month = $ra->get_req_var("month");
$year = $ra->get_req_var("year");

if ($report) {
	require "../includes/reportfunctions.php";
	$chart = new RAChart();
	$currencies = array();
	$result = select_query_i("ra_currency", "", "", "code", "ASC");

	while ($data = mysqli_fetch_array($result)) {
		$id = $data['id'];
		$code = $data['code'];
		$currencies[$id] = $code;

		if (!$currencyid && $data['default']) {
			$currencyid = $id;
		}


		if ($data['default']) {
			$defaultcurrencyid = $id;
		}
	}

	$currency = getCurrency("", $currencyid);
	$months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$month = (int)$month;
	$year = (int)$year;

	if (!$month) {
		$month = date("m");
	}


	if (!$year) {
		$year = date("Y");
	}

	$currentmonth = $months[(int)$month];
	$currentyear = $year;
	$month = str_pad($month, 2, "0", STR_PAD_LEFT);
	$gateways = new RA_Gateways();
	$data = $reportdata = $chartsdata = $args = array();
	$report = preg_replace("/[^0-9a-z-_]/i", "", $report);
	$reportfile = "../modules/reports/" . $report . ".php";

	if (file_exists($reportfile)) {
		require $reportfile;
	}
	else {
		exit("Report File Not Found");
	}

	$rows = $trow = array();
	foreach ($reportdata['tableheadings'] as $heading) {
		$trow[] = $heading;
	}

	$rows[] = $trow;

	if ($reportdata['tablevalues']) {
		foreach ($reportdata['tablevalues'] as $values) {
			$trow = array();
			foreach ($values as $value) {

				if (substr($value, 0, 2) == "**") {
					$trow[] = csv_clean(substr($value, 2));
					continue;
				}

				$trow[] = csv_clean($value);
			}

			$rows[] = $trow;
		}
	}

	header("Content-disposition: attachment; filename=" . $report . "_export_" . date("Ymd") . ".csv");
	echo strip_tags($reportdata['title']) . "\r\n";
	foreach ($rows as $row) {
		echo implode(",", $row) . "\r\n";
	}

	return 1;
}


if ($type == "pdfbatch") {
	require ROOTDIR . "/includes/countries.php";
	require ROOTDIR . "/includes/clientfunctions.php";
	require ROOTDIR . "/includes/invoicefunctions.php";
	$result = select_query_i("ra_modules_gateways", "gateway,value", array("setting" => "name"), "order", "ASC");

	while ($data = mysqli_fetch_array($result)) {
		$gatewaysarray[$data['gateway']] = $data['value'];
	}

	$invoice = new RA_Invoice();
	$invoice->pdfCreate($aInt->lang("reports", "pdfbatch") . " " . date("Y-m-d"));
	$orderby = "id";

	if ($sortorder == "Invoice Number") {
		$orderby = "invoicenum";
	}
	else {
		if ($sortorder == "Date Paid") {
			$orderby = "datepaid";
		}
		else {
			if ($sortorder == "Due Date") {
				$orderby = "duedate";
			}
			else {
				if ($sortorder == "Client ID") {
					$orderby = "userid";
				}
				else {
					if ($sortorder == "Client Name") {
						$orderby = "ra_user`.`firstname` ASC,`ra_user`.`lastname";
					}
				}
			}
		}
	}

	$clientWhere = ((is_numeric($userid) && 0 < $userid) ? "AND ra_bills.userid=" . (int)$userid : "");

	if ($filterby == "Date Created") {
		$filterby = "date";
	}
	else {
		if ($filterby == "Due Date") {
			$filterby = "duedate";
		}
		else {
			$filterby = "datepaid";
			$dateto .= " 23:59:59";
		}
	}

	$statuses_in_clause = db_build_in_array($statuses);
	$paymentmethods_in_clause = db_build_in_array($paymentmethods);
	$batchpdf_where_clause = "ra_bills." . $filterby . " >= '" . toMySQLDate($datefrom) . ("' AND ra_bills." . $filterby . "<='") . toMySQLDate($dateto) . "' AND ra_bills.status IN (" . $statuses_in_clause . ")" . " AND ra_bills.paymentmethod IN (" . $paymentmethods_in_clause . ")" . $clientWhere;
	$batchpdfresult = select_query_i("ra_bills", "ra_bills.id", $batchpdf_where_clause, $orderby, "ASC", "", "ra_user ON ra_user.id=ra_bills.userid");
	$numrows = mysqli_num_rows($batchpdfresult);

	if (!$numrows) {
		redir("report=pdf_batch&noresults=1", "reports.php");
	}
	else {
		header("Content-Disposition: attachment; filename=\"" . $aInt->lang("reports", "pdfbatch") . " " . date("Y-m-d") . ".pdf\"");
	}


	while ($data = mysqli_fetch_array($batchpdfresult)) {
		$invoice->pdfInvoicePage($data['id']);
	}

	$pdfdata = $invoice->pdfOutput();
	echo $pdfdata;
}

?>