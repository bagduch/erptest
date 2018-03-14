<?php

define("CLIENTAREA", true);
require "init.php";
require "includes/clientfunctions.php";
require "includes/customfieldfunctions.php";

if (isset($_SESSION['uid']) && ($_SESSION['uid'] > 0)) {
	redir("", "clientarea.php");
}

$capatacha = clientAreaInitCaptcha();
$firstname = $ra->get_req_var("firstname");
$lastname = $ra->get_req_var("lastname");
$companyname = $ra->get_req_var("companyname");
$email = $ra->get_req_var("email");
$address1 = $ra->get_req_var("address1");
$address2 = $ra->get_req_var("address2");
$city = $ra->get_req_var("city");
$state = $ra->get_req_var("state");
$postcode = $ra->get_req_var("postcode");
$country = $ra->get_req_var("country");
$phonenumber = $ra->get_req_var("phonenumber");
$password = $ra->get_req_var("password");
// handle mm/dd default parsing
try {
    $dateofbirth=DateTime::createFromFormat('d/m/Y',$ra->get_req_var("dateofbirth"))->format("Y-m-d");
} catch() {
    $dateofbirth="2016-01-01";
 }
$sendemail = "on";
$additionaldata = "";

$customfield = $ra->get_req_var("customfield");
$errormessage = "";

if ($ra->get_req_var("register")) {
	check_token();
	$errormessage = checkDetailsareValid("", true);

	if (!$errormessage) {
        $userid = addClient(
            $firstname, 
            $lastname, 
            $companyname, 
            $email, 
            $address1, 
            $address2, 
            $city, 
            $state, 
            $postcode, 
            $country, 
            $phonenumber, 
            $password,
            $dateofbirth,
            $sendemail,
            $additionaldata
        );
		run_hook("ClientAreaRegister", array("userid" => $userid));
		redir("", "clientarea.php");
	}
}

$pagetitle = $_LANG['clientregistertitle'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"register.php\">" . $_LANG['clientregistertitle'] . "</a>";
$pageicon = "images/order_big.gif";
initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);
$templatefile = "clientregister";

if (!$CONFIG['AllowClientRegister']) {
	$smarty->assign("noregistration", true);
}

include "includes/countries.php";
$countriesdropdown = getCountriesDropDown("New Zealand");
$smarty->assign("errormessage", $errormessage);
$smarty->assign("clientfirstname", $firstname);
$smarty->assign("clientlastname", $lastname);
$smarty->assign("clientcompanyname", $companyname);
$smarty->assign("clientemail", $email);
$smarty->assign("clientaddress1", $address1);
$smarty->assign("clientaddress2", $address2);
$smarty->assign("clientcity", $city);
$smarty->assign("clientstate", $state);
$smarty->assign("clientpostcode", $postcode);
$smarty->assign("clientcountriesdropdown", $countriesdropdown);
$smarty->assign("clientphonenumber", $phonenumber);
$customfields = getCustomFields("client", "", "", "", "on", $customfield);
$smarty->assign("customfields", $customfields);
$smarty->assign("capatacha", $capatacha);
$smarty->assign("recapatchahtml", clientAreaReCaptchaHTML());
$smarty->assign("accepttos", $CONFIG['EnableTOSAccept']);
$smarty->assign("tosurl", $CONFIG['TermsOfService']);
outputClientArea($templatefile);
?>
