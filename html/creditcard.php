<?php
/** RA - Version 0.1 **/


define("CLIENTAREA", true);
require "init.php";
require "includes/ccfunctions.php";
require "includes/clientfunctions.php";
require "includes/gatewayfunctions.php";
require "includes/invoicefunctions.php";
$pagetitle = $_LANG['ordercheckout'];
$pageicon = "";
$breadcrumbnav = $_LANG['ordercheckout'];
$templatefile = "creditcard";
initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);
$invoiceid = (int)$ra->get_req_var("invoiceid");

if (!$_SESSION['uid'] || !$invoiceid) {
	redir("", "clientarea.php");
}

$result = select_query_i("ra_bills", "", array("id" => $invoiceid, "userid" => $_SESSION['uid']));
$data = mysqli_fetch_array($result);
$invoiceid = $data['id'];
$status = $data['status'];
$total = $data['total'];

if ($status != "Unpaid") {
	redir("", "clientarea.php");
}

$gateways = new RA_Gateways();
$params = getCCVariables($invoiceid);
$fromorderform = false;

if (isset($_SESSION['cartccdetail'])) {
	$cartccdetail = unserialize(base64_decode(decrypt($_SESSION['cartccdetail'])));
	$cctype = $cartccdetail[0];
	$ccnumber = $cartccdetail[1];
	$ccexpirymonth = $cartccdetail[2];
	$ccexpiryyear = $cartccdetail[3];
	$ccstartmonth = $cartccdetail[4];
	$ccstartyear = $cartccdetail[5];
	$ccissuenum = $cartccdetail[6];
	$cccvv = $cartccdetail[7];
	$nostore = $cartccdetail[8];
	unset($_SESSION['cartccdetail']);
	$action = "submit";

	if (ccFormatNumbers($ccnumber)) {
		$ccinfo = "new";
	}

	$fromorderform = true;
}


if ($action == "submit") {
	if (!$fromorderform) {
		check_token();
	}


	if ($nostore && !$CONFIG['CCAllowCustomerDelete']) {
		$nostore = "";
	}


	if (!$fromorderform) {
		$errormessage = checkDetailsareValid($_SESSION['uid'], false, false, false, false);

		if ($cccvv2) {
			$cccvv = $cccvv2;
		}


		if (!$cccvv) {
			$errormessage .= "<li>" . $_LANG['creditcardccvinvalid'];
		}


		if (!$errormessage) {
			$result = select_query_i("ra_user", "", array("id" => $_SESSION['uid']));
			$data = mysqli_fetch_array($result);
			$old_firstname = $data['firstname'];
			$old_lastname = $data['lastname'];
			$old_address1 = $data['address1'];
			$old_address2 = $data['address2'];
			$old_city = $data['city'];
			$old_state = $data['state'];
			$old_postcode = $data['postcode'];
			$old_country = $data['country'];
			$old_phonenumber = $data['phonenumber'];
			$email = $data['email'];
			$billingcid = $data['billingcid'];

			if ($billingcid) {
				$table = "ra_user_contacts";
				$array = array("firstname" => $firstname, "lastname" => $lastname, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
				$where = array("id" => $billingcid, "userid" => $_SESSION['uid']);
				update_query($table, $array, $where);
			}
			else {
				if (((((((($firstname != $old_firstname || $lastname != $old_lastname) || $address1 != $old_address1) || $address2 != $old_address2) || $city != $old_city) || $state != $old_state) || $postcode != $old_postcode) || $country != $old_country) || $phonenumber != $old_phonenumber) {
					$table = "ra_user_contacts";
					$array = array("userid" => $_SESSION['uid'], "firstname" => $firstname, "lastname" => $lastname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
					$billingcid = insert_query($table, $array);
					update_query("ra_user", array("billingcid" => $billingcid), array("id" => $_SESSION['uid']));
				}
			}


			if ($ccinfo == "new") {
				$errormessage .= updateCCDetails($_SESSION['uid'], $cctype, $ccnumber, $cccvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum, $nostore);
			}
		}
	}


	if (!$errormessage) {
		if ($ccinfo == "new") {
			$params['cardtype'] = $cctype;
			$params['cardnum'] = ccFormatNumbers($ccnumber);
			$params['cardexp'] = ccFormatDate(ccFormatNumbers($ccexpirymonth . $ccexpiryyear));
			$params['cardstart'] = ccFormatDate(ccFormatNumbers($ccstartmonth . $ccstartyear));
			$params['cardissuenum'] = ccFormatNumbers($ccissuenum);
			$params['gatewayid'] = get_query_val("ra_user", "gatewayid", array("id" => $_SESSION['uid']));
		}


		if (function_exists($params['paymentmethod'] . "_3dsecure")) {
			$params['cccvv'] = $cccvv;
			$buttoncode = call_user_func($params['paymentmethod'] . "_3dsecure", $params);
			$buttoncode = str_replace("<form", "<form target=\"3dauth\"", $buttoncode);
			$smartyvalues['code'] = $buttoncode;
			$smartyvalues['width'] = "400";
			$smartyvalues['height'] = "500";

			if ($buttoncode == "success" || $buttoncode == "declined") {
				$result = $buttoncode;
			}
			else {
				$templatefile = "3dsecure";
				outputClientArea($templatefile);
				exit();
			}
		}
		else {
			$result = captureCCPayment($invoiceid, $cccvv, true);
		}


		if ($params['paymentmethod'] == "offlinecc") {
			sendAdminNotification("account", "Offline Credit Card Payment Submitted", "<p>An offline credit card payment has just been submitted.  Details are below:</p><p>Client ID: " . $_SESSION['uid'] . "<br />Invoice ID: " . $invoiceid . "</p>");
			redir("id=" . $invoiceid . "&offlinepaid=true", "viewinvoice.php");
		}


		if ($result == "success") {
			redir("id=" . $invoiceid . "&paymentsuccess=true", "viewinvoice.php");
			exit();
		}
		else {
			$errormessage = "<li>" . $_LANG['creditcarddeclined'];
			$action = "";

			if ($ccinfo == "new") {
				updateCCDetails($_SESSION['uid'], "", "", "", "", "");
			}
		}
	}
}

$clientsdetails = getClientsDetails($_SESSION['uid'], "billing");
$cardtype = $clientsdetails['cctype'];
$cardnum = $clientsdetails['cclastfour'];

if (!$errormessage || $fromorderform) {
	$firstname = $clientsdetails['firstname'];
	$lastname = $clientsdetails['lastname'];
	$email = $clientsdetails['email'];
	$address1 = $clientsdetails['address1'];
	$address2 = $clientsdetails['address2'];
	$city = $clientsdetails['city'];
	$state = $clientsdetails['state'];
	$postcode = $clientsdetails['postcode'];
	$country = $clientsdetails['country'];
	$phonenumber = $clientsdetails['phonenumber'];
}

include "includes/countries.php";
$result = select_query_i("ra_transactions", "SUM(amountin)-SUM(amountout)", array("invoiceid" => $invoiceid));
$data = mysqli_fetch_array($result);
$amountpaid = $data[0];
$balance = $total - $amountpaid;
$smartyvalues = array("firstname" => $firstname, "lastname" => $lastname, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "countriesdropdown" => getCountriesDropDown($country), "phonenumber" => $phonenumber, "acceptedcctypes" => explode(",", $CONFIG['AcceptedCardTypes']), "ccinfo" => $ccinfo, "cardtype" => $cardtype, "cardnum" => $cardnum, "cctype" => $cctype, "ccnumber" => $ccnumber, "ccexpirymonth" => $ccexpirymonth, "ccexpiryyear" => $ccexpiryyear, "ccstartmonth" => $ccstartmonth, "ccstartyear" => $ccstartyear, "ccissuenum" => $ccissuenum, "cccvv" => $cccvv, "errormessage" => $errormessage, "invoiceid" => $invoiceid, "total" => formatCurrency($total), "balance" => formatCurrency($balance), "showccissuestart" => $CONFIG['ShowCCIssueStart'], "shownostore" => $CONFIG['CCAllowCustomerDelete']);
$smartyvalues['months'] = $gateways->getCCDateMonths();
$smartyvalues['startyears'] = $gateways->getCCStartDateYears();
$smartyvalues['expiryyears'] = $smartyvalues['years'] = $gateways->getCCExpiryDateYears();

if (function_exists($params['paymentmethod'] . "_remoteinput")) {
	$buttoncode = call_user_func($params['paymentmethod'] . "_remoteinput", $params);
	$buttoncode = str_replace("<form", "<form target=\"3dauth\"", $buttoncode);
	$smartyvalues['remotecode'] = $buttoncode;
}

outputClientArea($templatefile);
?>