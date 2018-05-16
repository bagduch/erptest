<?php
/** RA - Version 0.1 **/

function doUnsubscribe($email, $key) {
	global $ra;
	global $_LANG;

	$ra->get_hash();

	if (!$email) {
		return $_LANG['pwresetemailrequired'];
	}

	$result = select_query_i("ra_user", "id,email,emailoptout", array("email" => $email));
	$data = mysqli_fetch_array($result);
	$userid = $data['id'];
	$email = $data['email'];
	$emailoptout = $data['emailoptout'];
	$newkey = sha1($email . $userid . $cc_encryption_hash);

	if ($newkey == $key) {
		if (!$userid) {
			return $_LANG['unsubscribehashinvalid'];
		}


		if ($emailoptout == 1) {
			return $_LANG['alreadyunsubscribed'];
		}

		update_query("ra_user", array("emailoptout" => "1"), array("id" => $userid));
		sendMessage("Unsubscribe Confirmation", $userid);
		logActivity("Unsubscribed From Marketing Emails - User ID:" . $userid, $userid);
		return null;
	}

	return $_LANG['unsubscribehashinvalid'];
}

define("CLIENTAREA", true);
require "init.php";
$pagetitle = $_LANG['unsubscribe'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a> > <a href=\"clientarea.php\">" . $_LANG['clientareatitle'] . "</a> > <a href=\"unsubscribe.php\">" . $_LANG['unsubscribe'] . "</a>";
initialiseClientArea($pagetitle, "", $breadcrumbnav);
$email = $ra->get_req_var("email");
$key = $ra->get_req_var("key");

if ($email) {
	$errormessage = doUnsubscribe($email, $key);
	$smartyvalues['errormessage'] = $errormessage;

	if (!$errormessage) {
		$smartyvalues['successful'] = true;
	}

	$templatefile = "unsubscribe";
	outputClientArea($templatefile);
	return 1;
}

redir("index.php");
?>