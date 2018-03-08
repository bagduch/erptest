<?php

define("CLIENTAREA", true);
require "init.php";
require "includes/clientfunctions.php";
$pagetitle = $_LANG['pwreset'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"clientarea.php\">" . $_LANG['clientareatitle'] . "</a> > <a href=\"pwreset.php\">" . $_LANG['pwreset'] . "</a>";
initialiseClientArea($pagetitle, "", $breadcrumbnav);
$action = $ra->get_req_var("action");
$email = $ra->get_req_var("email");
$answer = $ra->get_req_var("answer");
$key = $ra->get_req_var("key");
$success = $ra->get_req_var("success");
$smartyvalues['action'] = $action;
$smartyvalues['email'] = $email;
$smartyvalues['key'] = $key;
$smartyvalues['answer'] = $answer;

if ($action == "reset") {
	check_token();
	$templatefile = "pwreset";
	$errormessage = doResetPWEmail($email, $answer);


	if ($errormessage) {
		$smartyvalues['errormessage'] = $errormessage;
	}
	else {
		$smartyvalues['success'] = true;
	}
}
else {
	if ($key) {
		$invalidlink = doResetPWKeyCheck($key);

		if ($newpw && !$invalidlink) {
			$errormessage = doResetPW($key, $newpw, $confirmpw);

			if (!$errormessage) {
				$smartyvalues['success'] = true;
			}
		}

		$smartyvalues['invalidlink'] = $invalidlink;
		$smartyvalues['errormessage'] = $errormessage;
		$templatefile = "pwresetvalidation";
	}
	else {
		if ($success) {
			$smartyvalues['success'] = true;
			$templatefile = "pwresetvalidation";
		}
		else {
			$templatefile = "pwreset";
		}
	}
}

outputClientArea($templatefile);
// vim: ai ts=4 sts=4 et sw=4 ft=php
?>
