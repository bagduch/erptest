<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	header("Location: clientarea.php");
	exit();
}

$_SESSION['loginurlredirect'] = html_entity_decode($_SERVER['REQUEST_URI']);

if (RA_Session::get("2faverifyc")) {
	$templatefile = "logintwofa";

	if (RA_Session::get("2fabackupcodenew")) {
		$smartyvalues['newbackupcode'] = true;
	}
	else {
		if ($ra->get_req_var("incorrect")) {
			$smartyvalues['incorrect'] = true;
		}
	}

	$twofa = new RA_2FA();

	if ($twofa->setClientID(RA_Session::get("2faclientid"))) {
		if (!$twofa->isActiveClients() || !$twofa->isEnabled()) {
			RA_Session::destroy();
			redir();
		}


		if ($ra->get_req_var("backupcode")) {
			$smartyvalues['backupcode'] = true;
		}
		else {
			$challenge = $twofa->moduleCall("challenge");

			if ($challenge) {
				$smartyvalues['challenge'] = $challenge;
			}
			else {
				$smartyvalues['error'] = "Bad 2 Factor Auth Module. Please contact support.";
			}
		}
	}
	else {
		$smartyvalues['error'] = "An error occurred. Please try again.";
	}
}
else {
	$templatefile = "login";
	$smartyvalues['loginpage'] = true;
	$smartyvalues['formaction'] = "dologin.php";

	if ($ra->get_req_var("incorrect")) {
		$smartyvalues['incorrect'] = true;
	}
}

outputClientArea($templatefile);
exit();
?>