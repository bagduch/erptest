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

define("CLIENTAREA", true);
require "init.php";
include "includes/clientfunctions.php";
$username = trim($ra->get_req_var("username"));
$password = trim($ra->get_req_var("password"));
$hash = $ra->get_req_var("hash");
$goto = $ra->get_req_var("goto");
$gotourl = "";

if ($goto) {
	$goto = trim($goto);

	if (substr($goto, 0, 7) == "http://" || substr($goto, 0, 8) == "https://") {
		$goto = "";
	}

	$gotourl = html_entity_decode($goto);
}
else {
	if (isset($_SESSION['loginurlredirect'])) {
		$gotourl = $_SESSION['loginurlredirect'];

		if (substr($gotourl, 0 - 15) == "&incorrect=true" || substr($gotourl, 0 - 15) == "?incorrect=true") {
			$gotourl = substr($gotourl, 0, strlen($gotourl) - 15);
		}


		if (((substr($gotourl, 0 - 28) == "&incorrect=true&backupcode=1" || substr($gotourl, 0 - 28) == "?incorrect=true&backupcode=1") || substr($gotourl, 0 - 28) == "&backupcode=1&incorrect=true") || substr($gotourl, 0 - 28) == "?backupcode=1&incorrect=true") {
			$gotourl = substr($gotourl, 0, strlen($gotourl) - 28);
		}

		unset($_SESSION['loginurlredirect']);
	}
}


if (!$gotourl) {
	$gotourl = "clientarea.php";
}


if ($ra->get_req_var("newbackupcode")) {
	header("Location: " . $gotourl);
	exit();
}

$loginsuccess = $istwofa = false;

if (!$loginsuccess) {
	if (validateClientLogin($username, $password)) {
		$loginsuccess = true;

		if ($rememberme) {
			wSetCookie("User", $_SESSION['uid'] . ":" . sha1($_SESSION['upw'] . $ra->get_hash()), time() + 60 * 60 * 24 * 365);
		}
		else {
			wDelCookie("User");
		}
	}
	else {
		if (isset($_SESSION['2faverifyc'])) {
			$istwofa = true;
		}
		else {
			if ($hash) {
				$autoauthkey = "";
				require "configuration.php";

				if ($autoauthkey) {
					$login_uid = $login_cid = "";

					if ($timestamp < time() - 15 * 60 || time() < $timestamp) {
						exit("Link expired");
					}

					$hashverify = sha1($email . $timestamp . $autoauthkey);

					if ($hashverify == $hash) {
						$result = select_query_i("tblclients", "id,password,language", array("email" => $email, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
						$data = mysqli_fetch_array($result);
						$login_uid = $data['id'];
						$login_pwd = $data['password'];
						$language = $data['language'];

						if (!$login_uid) {
							$result = select_query_i("tblcontacts", "id,userid,password", array("email" => $email, "subaccount" => "1", "password" => array("sqltype" => "NEQ", "value" => "")));
							$data = mysqli_fetch_array($result);
							$login_cid = $data['id'];
							$login_uid = $data['userid'];
							$login_pwd = $data['password'];
							$result = select_query_i("tblclients", "id,language", array("id" => $login_uid, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
							$data = mysqli_fetch_array($result);
							$login_uid = $data['id'];
							$language = $data['language'];
						}


						if ($login_uid) {
							$fullhost = gethostbyaddr($remote_ip);
							update_query("tblclients", array("lastlogin" => "now()", "ip" => $remote_ip, "host" => $fullhost), array("id" => $login_uid));
							$_SESSION['uid'] = $login_uid;

							if ($login_cid) {
								$_SESSION['cid'] = $login_cid;
							}

							$haship = ($CONFIG['DisableSessionIPCheck'] ? "" : $ra->get_user_ip());
							$_SESSION['upw'] = sha1($login_uid . $login_cid . $login_pwd . $haship . substr(sha1($ra->get_hash()), 0, 20));
							$_SESSION['tkval'] = genRandomVal();

							if ($language) {
								$_SESSION['Language'] = $language;
							}

							run_hook("ClientLogin", array("userid" => $login_uid));
							$loginsuccess = true;
						}
					}
				}
			}
		}
	}
}


if (!$istwofa && !$loginsuccess) {
	if (strpos($gotourl, "?")) {
		$gotourl .= "&incorrect=true";
	}
	else {
		$gotourl .= "?incorrect=true";
	}
}

$gotourl = preg_replace("/^\/+/", "/", $gotourl);
header("Location: " . $gotourl);
exit();
?>
