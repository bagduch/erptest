<?php
/** RA - Version 0.1 **/


function apiXMLOutput($val, $lastk = "") {
	foreach ($val as $k => $v) {

		if (is_array($v)) {
			if (is_numeric($k)) {
				echo "<" . $lastk . ">\n";
			}
			else {
				if (!is_numeric(key($v)) && count($v)) {
					echo "<" . $k . ">\n";
				}
			}

			apiXMLOutput($v, $k);

			if (is_numeric($k)) {
				echo "</" . $lastk . ">\n";
				continue;
			}


			if (!is_numeric(key($v)) && count($v)) {
				echo "</" . $k . ">\n";
				continue;
			}

			continue;
		}

		$v = html_entity_decode($v);

		if (strpos($v, "<![CDATA[") === false && htmlspecialchars($v) != $v) {
			$v = ("<![CDATA[" . $v . "]") . "]>";
		}

		echo "<" . $k . ">" . $v . "</" . $k . ">\n";
	}

}

$silent = "true";
require "../init.php";
require "adminfunctions.php";
unset($api_access_key);
unset($api_enable_logging);
$templates_compiledir2 = $templates_compiledir;
require ROOTDIR . "/configuration.php";
$templates_compiledir = $templates_compiledir2;
$responsetype = $_REQUEST['responsetype'];
$apiresults = array();
$action = preg_replace("/[^0-9a-zA-Z]/i", "", $_POST['action']);
$allowed = true;

if ($_POST['accesskey'] && $api_access_key) {
	if ($_POST['accesskey'] != $api_access_key) {
		$apiresults = array("result" => "error", "message" => "Invalid Access Key");
		$allowed = false;
	}
}
else {
	$apiallowedips = $CONFIG['APIAllowedIPs'];
	$apiallowedips = unserialize($apiallowedips);
	$allowedips = array();
	foreach ($apiallowedips as $ip) {

		if (0 < strlen(trim($ip['ip']))) {
			$allowedips[] = $ip['ip'];
			continue;
		}
	}


	if (!in_array($remote_ip, $allowedips)) {
		$apiresults = array("result" => "error", "message" => "Invalid IP " . $remote_ip);
		$allowed = false;
	}
}


if ($allowed) {
	$result = select_query_i("ra_admin", "id,disabled", array("username" => $_POST['username'], "password" => $_POST['password']));
	$data = mysqli_fetch_array($result);
	$adminid = $data['id'];
	$admindisabled = $data['disabled'];

	if ($admindisabled) {
		$apiresults = array("result" => "error", "message" => "Administrator Account Disabled");
		$allowed = false;
	}
	else {
		if (!$adminid) {
			$result = select_query_i("ra_admin", "loginattempts", array("username" => $login_unm));
			$data = mysqli_fetch_array($result);
			$loginattempts = $data['loginattempts'] + 1;

			if ("3" <= $loginattempts) {
				$expire_date = mktime(date("H"), date("i") + $CONFIG['InvalidLoginBanLength'], date("s"), date("m"), date("d"), date("Y"));
				$expire_date = date("Y-m-d H:i:s", $expire_date);
				insert_query("ra_bannedips", array("ip" => $remote_ip, "reason" => "3 Invalid API Login Attempts", "expires" => $expire_date));
				update_query("ra_admin", array("loginattempts" => "0"), array("username" => $_POST['username']));
			}

			update_query("ra_admin", array("loginattempts" => "+1"), array("username" => $_POST['username']));
			$apiresults = array("result" => "error", "message" => "Authentication Failed");
			$allowed = false;
		}
		else {
			$_SESSION['adminid'] = $adminid;

			if (!checkPermission("API Access", true)) {
				$apiresults = array("result" => "error", "message" => "Access Denied");
				$allowed = false;
			}
		}
	}


	if ($allowed) {
		if (isValidforPath($action)) {
			switch ($action) {
			case "adduser":
				$action = "addclient";
				break;

			case "getclientsdata":
			case "getclientsdatabyemail":
				$action = "getclientsdetails";
			}


			if (file_exists(ROOTDIR . "/includes/api/" . $action . ".php")) {
				include ROOTDIR . "/includes/api/" . $action . ".php";
			}
			else {
				$apiresults = array("result" => "error", "message" => "Command Not Found");
			}
		}
		else {
			$apiresults = array("result" => "error", "message" => "Invalid API Command Value");
		}
	}
}

$userresponsetype = $_REQUEST['responsetype'];

if ($userresponsetype != $responsetype && ($userresponsetype != "xml" && $userresponsetype != "json")) {
	$userresponsetype = "xml";
}

ob_start();

if (count($apiresults)) {
	if ($userresponsetype == "json") {
		$apiresults = json_encode($apiresults);
		echo $apiresults;
		exit();
	}
	else {
		if ($userresponsetype == "xml") {
			echo "<?xml version=\"1.0\" encoding=\"" . $CONFIG['Charset'] . "\"?>\n<RAapi version=\"" . $CONFIG['Version'] . ("\">\n<action>" . $action . "</action>\n");
			apiXMLOutput($apiresults);
			echo "</RAapi>";
		}
		else {
			if ($responsetype) {
				exit("result=error;message=This API function can only return XML response format;");
			}

			foreach ($apiresults as $k => $v) {
				echo "" . $k . "=" . $v . ";";
			}
		}
	}
}

$apioutput = ob_get_contents();
ob_end_clean();
echo $apioutput;

if ($api_enable_logging) {
	$fh = fopen("apilog.txt", "a");
	$stringData = "
Date: " . date("Y-m-d H:i:s") . "

Request: " . print_r($_REQUEST, true) . "

Response: " . $apioutput . "
----------------------";
	fwrite($fh, $stringData);
	fclose($fh);
}

?>