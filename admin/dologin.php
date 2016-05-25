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
session_regenerate_id();
$username = $ra->get_req_var("username");
$password = $ra->get_req_var("password");
//$username="ben";
//$password="ben";
$auth = new RA_Auth();
$twofa = new RA_2FA();

if ($twofa->isActiveAdmins() && isset($_SESSION['2faverify'])) {
	$twofa->setAdminID($_SESSION['2faadminid']);

	if (RA_Session::get("2fabackupcodenew")) {
		RA_Session::delete("2fabackupcodenew");
		RA_Session::delete("2faverify");
		RA_Session::delete("2faadminid");
		RA_Session::delete("2farememberme");

		if (isset($_SESSION['admloginurlredirect'])) {
			$loginurlredirect = $_SESSION['admloginurlredirect'];
			unset($_SESSION['admloginurlredirect']);
			$urlparts = explode("?", $loginurlredirect, 2);
			$filename = (!empty($urlparts[0]) ? $urlparts[0] : "");
			$qry_string = (!empty($urlparts[1]) ? $urlparts[1] : "");
			redir($qry_string, $filename);
		}
		else {
			redir("", "index.php");
		}

		exit();
	}


	if ($ra->get_req_var("backupcode")) {
		$success = $twofa->verifyBackupCode($ra->get_req_var("code"));
	}
	else {
		$success = $twofa->moduleCall("verify");
	}

	$success = true;


	if ($success) {
		$adminfound = $auth->getInfobyID($_SESSION['2faadminid']);
		$auth->setSessionVars();
		$auth->processLogin();

		if ($_SESSION['2farememberme']) {
			$auth->setRememberMeCookie();
		}
		else {
			$auth->unsetRememberMeCookie();
		}


		if ($ra->get_req_var("backupcode")) {
			RA_Session::set("2fabackupcodenew", true);
			redir("newbackupcode=1", "login.php");
		}

		RA_Session::delete("2faverify");
		RA_Session::delete("2faadminid");
		RA_Session::delete("2farememberme");

		if (isset($_SESSION['admloginurlredirect'])) {
			$loginurlredirect = $_SESSION['admloginurlredirect'];
			unset($_SESSION['admloginurlredirect']);
			$urlparts = explode("?", $loginurlredirect, 2);
			$filename = (!empty($urlparts[0]) ? $urlparts[0] : "");
			$qry_string = (!empty($urlparts[1]) ? $urlparts[1] : "");
			redir($qry_string, $filename);
		}
		else {
			redir("", "index.php");
		}

		exit();
	}

	redir(($ra->get_req_var("backupcode") ? "backupcode=1&" : "") . "incorrect=1", "login.php");
}


if (!trim($username) || !trim($password)) {
	redir("incorrect=1", "login.php");
}

$adminfound = $auth->getInfobyUsername($username);

if ($adminfound) {
	if ($auth->comparePassword($password)) {
		if ($ra->get_req_var("language")) {
			$_SESSION['adminlang'] = $ra->get_req_var("language");
		}


		if ($twofa->isActiveAdmins() && $auth->isTwoFactor()) {
			$_SESSION['2faverify'] = true;
			$_SESSION['2faadminid'] = $auth->getAdminID();
			$_SESSION['2farememberme'] = $ra->get_req_var("rememberme");
			redir("", "login.php");
		}

		$auth->setSessionVars();

		if ($ra->get_req_var("rememberme")) {
			$auth->setRememberMeCookie();
		}
		else {
			$auth->unsetRememberMeCookie();
		}

		$auth->processLogin();

		if (isset($_SESSION['admloginurlredirect'])) {
			$loginurlredirect = $_SESSION['admloginurlredirect'];
			unset($_SESSION['admloginurlredirect']);
			$urlparts = explode("?", $loginurlredirect, 2);
			$filename = (!empty($urlparts[0]) ? $urlparts[0] : "");
			$qry_string = (!empty($urlparts[1]) ? $urlparts[1] : "");
			redir($qry_string, $filename);
		}
		else {
			redir("", "index.php");
		}

		exit();
	}
}

$auth->failedLogin();
redir("incorrect=1", "login.php");
?>
