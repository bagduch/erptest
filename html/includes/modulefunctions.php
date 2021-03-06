<?php

/**
 * getModuleType - show server to module association
 * @param  int $id ID of a server from ra_integration
 * @return string name of the module which uses/handles that server
 */
function getModuleType($id) {
	$result = select_query_i("ra_integration", "type", array("id" => $id));
	$data = mysqli_fetch_array($result);
	$type = $data['type'];
	return $type;
}

/**
 * ModuleBuildParams
 * What on earth is this crap?
 * @param int $id id of a service from tblcustomerservices
 */
function ModuleBuildParams($id) {

// heredoc due to multiline
  $query = <<< EOD
SELECT
	tcs.id serviceid,
	tcs.userid userid,
	tcs.description description,
	tcs.servicestatus servicestatus,
	tcs.serverid serverid,
	ts.id packageid,
	ts.type producttype,
	ts.servertype moduletype
FROM tblcustomerservices tcs
LEFT JOIN ra_catalog ts ON tcs.packageid=ts.id
WHERE tcs.id=%d
EOD;
	$result = full_query_i(sprintf($query,(int)$id));
	$params = mysqli_fetch_assoc($result);

  // Make sure there's actually a module to provide params to
	if (!$params['moduletype']) {
		return false;
	}

	$customfields = array();


	$query = <<<EOD
SELECT
  tcf.fieldname fieldname,
	tcfv.value value
FROM ra_catalog_user_sales_fieldslinks tcfl
LEFT JOIN ra_catalog_user_sales_fields tcf
  ON (tcfl.cfid=tcf.cfid)
LEFT JOIN ra_catalog_user_sales_fieldsvalues tcfv
  ON (tcfv.cfid=tcf.cfid AND tcfv.relid=242)
WHERE tcfl.serviceid=48
EOD;
	$result = full_query_i($query);

	while ($data = mysqli_fetch_array($result)) {
		$customfieldname = $data[0];
		$customfieldvalue = $data[1];

		if (strpos($customfieldname, "|")) {
			$customfieldname = explode("|", $customfieldname);
			$customfieldname = trim($customfieldname[0]);
		}


		if (strpos($customfieldvalue, "|")) {
			$customfieldvalue = explode("|", $customfieldvalue);
			$customfieldvalue = trim($customfieldvalue[0]);
		}

		$customfields[$customfieldname] = $customfieldvalue;
	}

	$params['customfields'] = $customfields;
	$configoptions = array();
	$result = full_query_i("SELECT ra_catalog_user_sales_addons_options.optionname,ra_catalog_user_sales_addons_options.optiontype,ra_catalog_user_sales_addons_optionssub.optionname,tblhostingconfigoptions.qty FROM ra_catalog_user_sales_addons_options,ra_catalog_user_sales_addons_optionssub,tblhostingconfigoptions,ra_catalog_user_sales_addons_links WHERE tblhostingconfigoptions.configid=ra_catalog_user_sales_addons_options.id AND tblhostingconfigoptions.optionid=ra_catalog_user_sales_addons_optionssub.id AND tblhostingconfigoptions.relid=" . (int)$id . " AND ra_catalog_user_sales_addons_links.gid=ra_catalog_user_sales_addons_options.gid AND ra_catalog_user_sales_addons_links.pid=" . (int)$pid);

	while ($data = mysqli_fetch_array($result)) {
		$configoptionname = $data[0];
		$configoptiontype = $data[1];
		$configoptionvalue = $data[2];
		$configoptionqty = $data[3];

		if (strpos($configoptionname, "|")) {
			$configoptionname = explode("|", $configoptionname);
			$configoptionname = trim($configoptionname[0]);
		}


		if (strpos($configoptionvalue, "|")) {
			$configoptionvalue = explode("|", $configoptionvalue);
			$configoptionvalue = trim($configoptionvalue[0]);
		}


		if ($configoptiontype == "3" || $configoptiontype == "4") {
			$configoptionvalue = $configoptionqty;
		}

		$configoptions[$configoptionname] = $configoptionvalue;
	}

	$params['configoptions'] = $configoptions;

	if (!function_exists("getClientsDetails")) {
		require dirname(__FILE__) . "/clientfunctions.php";
	}

	$clientsdetails = getClientsDetails($userid);
	$clientsdetails['fullstate'] = $clientsdetails['state'];
	$clientsdetails = foreignChrReplace($clientsdetails);
	$params['clientsdetails'] = $clientsdetails;

	if ($server) {
		$result = select_query_i("ra_integration", "", array("id" => $server));
		$data = mysqli_fetch_array($result);
		$params['server'] = true;
		$params['serverip'] = $data['ipaddress'];
		$params['serverhostname'] = $data['hostname'];
		$params['serverusername'] = html_entity_decode($data['username']);
		$params['serverpassword'] = html_entity_decode(decrypt($data['password']));
		$params['serveraccesshash'] = html_entity_decode($data['accesshash']);
		$params['serversecure'] = $data['secure'];
	}
	else {
		$params['server'] = false;
		$params['serverip'] = $params['serverhostname'] = $params['serverusername'] = $params['serverpassword'] = $params['serveraccesshash'] = $params['serversecure'] = "";
	}


	if (!function_exists($params['moduletype'] . "_ConfigOptions")) {
		$modulepath = ROOTDIR . "/modules/servers/" . $params['moduletype'] . "/" . $params['moduletype'] . ".php";

		if (file_exists($modulepath)) {
			require $modulepath;
		}
		else {
			logActivity("Required Product Module '" . $params['moduletype'] . "' Missing");
		}
	}

	$GLOBALS['moduleparams'] = $params;
	return $params;
}

function ServerCreateAccount($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "create";

	if (!$params['username']) {
		$params['username'] = createServerUsername($params['domain']);
		update_query("tblcustomerservices", array("username" => $params['username']), array("id" => $func_id));
	}


	if (!$params['password']) {
		$params['password'] = createServerPassword();
		update_query("tblcustomerservices", array("password" => encrypt($params['password'])), array("id" => $func_id));
	}

	$hookresults = run_hook("PreModuleCreate", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_CreateAccount")) {
		$result = call_user_func($params['moduletype'] . "_CreateAccount", $params);

		if ($result == "success") {
			logActivity("Module Create Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			update_query("tblcustomerservices", array("servicestatus" => "Active"), array("id" => $func_id));
			run_hook("AfterModuleCreate", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Create Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerSuspendAccount($func_id, $suspendreason = "") {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "suspend";
	$params['suspendreason'] = ($suspendreason ? $suspendreason : "Overdue on Payment");
	$hookresults = run_hook("PreModuleSuspend", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_SuspendAccount")) {
		$result = call_user_func($params['moduletype'] . "_SuspendAccount", $params);

		if ($result == "success") {
			$reason = ($suspendreason ? " - Reason: " . $suspendreason : "");
			logActivity("Module Suspend Successful" . $reason . (" - Service ID: " . $func_id), $params['clientsdetails']['userid']);
			update_query("tblcustomerservices", array("servicestatus" => "Suspended", "suspendreason" => $suspendreason), array("id" => $func_id));
			run_hook("AfterModuleSuspend", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Suspend Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerUnsuspendAccount($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "unsuspend";
	$hookresults = run_hook("PreModuleUnsuspend", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_UnsuspendAccount")) {
		$result = call_user_func($params['moduletype'] . "_UnsuspendAccount", $params);

		if ($result == "success") {
			logActivity("Module Unsuspend Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			update_query("tblcustomerservices", array("servicestatus" => "Active", "suspendreason" => ""), array("id" => $func_id));
			run_hook("AfterModuleUnsuspend", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Unsuspend Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerTerminateAccount($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "terminate";
	$hookresults = run_hook("PreModuleTerminate", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_TerminateAccount")) {
		$result = call_user_func($params['moduletype'] . "_TerminateAccount", $params);

		if ($result == "success") {
			logActivity("Module Terminate Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			update_query("tblcustomerservices", array("servicestatus" => "Terminated"), array("id" => $func_id));
			update_query("ra_catalog_user_sales_addons", array("status" => "Terminated"), array("hostingid" => $func_id));
			run_hook("AfterModuleTerminate", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Terminate Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerRenew($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "renew";
	$hookresults = run_hook("PreModuleRenew", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_Renew")) {
		$result = call_user_func($params['moduletype'] . "_Renew", $params);

		if ($result == "success") {
			logActivity("Module Renewal Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			run_hook("AfterModuleRenew", array("params" => $params));
			return $result;
		}

		logActivity("Module Renewal Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
		return $result;
	}


	if ($hookabort) {
		$result = "Function Aborted by Action Hook Code";
		logActivity("Module Renewal Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
		return $result;
	}

	return "notsupported";
}

function ServerChangePassword($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "changepw";
	$hookresults = run_hook("PreModuleChangePassword", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_ChangePassword")) {
		$result = call_user_func($params['moduletype'] . "_ChangePassword", $params);

		if ($result == "success") {
			logActivity("Module Change Password Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			run_hook("AfterModuleChangePassword", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Change Password Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerLoginLink($func_id) {
	$params = ModuleBuildParams($func_id);

	if (function_exists($params['moduletype'] . "_LoginLink")) {
		$result = call_user_func($params['moduletype'] . "_LoginLink", $params);
		return $result;
	}

}

function ServerChangePackage($func_id) {
	$params = ModuleBuildParams($func_id);
	$params['action'] = "upgrade";
	$hookresults = run_hook("PreModuleChangePackage", array("params" => $params));
	$hookabort = false;
	foreach ($hookresults as $hookvals) {
		foreach ($hookvals as $k => $v) {

			if ($k == "abortcmd" && $v == true) {
				$hookabort = true;
				continue;
			}
		}
	}


	if (!$hookabort && function_exists($params['moduletype'] . "_ChangePackage")) {
		$result = call_user_func($params['moduletype'] . "_ChangePackage", $params);

		if ($result == "success") {
			logActivity("Module Upgrade/Downgrade Successful - Service ID: " . $func_id, $params['clientsdetails']['userid']);
			run_hook("AfterModuleChangePackage", array("params" => $params));
			return $result;
		}
	}
	else {
		$result = "Function Not Supported by Module";

		if ($hookabort) {
			$result = "Function Aborted by Action Hook Code";
		}
	}

	logActivity("Module Upgrade/Downgrade Failed - Service ID: " . $func_id . " - Error: " . $result, $params['clientsdetails']['userid']);
	return $result;
}

function ServerCustomFunction($func_id, $func_name) {
	$params = ModuleBuildParams($func_id);

	if (function_exists($params['moduletype'] . "_" . $func_name)) {
		$result = call_user_func($params['moduletype'] . "_" . $func_name, $params);
	}
	else {
		$result = "Function Not Supported by Module";
	}

	return $result;
}

function ServerClientArea($func_id) {
	$params = ModuleBuildParams($func_id);

	if (function_exists($params['moduletype'] . "_ClientArea")) {
		$result = call_user_func($params['moduletype'] . "_ClientArea", $params);
	}
	else {
		$result = "";
	}

	return $result;
}

function ServerUsageUpdate() {
	$result2 = select_query_i("ra_integration", "", array("disabled" => "0"), "name", "ASC");

	while ($data = mysqli_fetch_array($result2)) {
		$servertype = $data['type'];
		$params['serverid'] = $data['id'];
		$params['serverip'] = $data['ipaddress'];
		$params['serverhostname'] = $data['hostname'];
		$params['serverusername'] = $data['username'];
		$params['serverpassword'] = decrypt($data['password'], $encryption_key);
		$params['serveraccesshash'] = $data['accesshash'];
		$params['serversecure'] = $data['secure'];

		if (!function_exists($servertype . "_ConfigOptions")) {
			if (!isValidforPath($servertype)) {
				exit("Invalid Server Module Name");
			}

			require ROOTDIR . "/modules/servers/" . $servertype . "/" . $servertype . ".php";
		}


		if (function_exists($servertype . "_UsageUpdate")) {
			logActivity("Cron Job: Running Usage Stats Update for Server ID " . $data['id']);
			$res = call_user_func($servertype . "_UsageUpdate", $params);
		}
	}

}

function createServerUsername($domain) {
	global $CONFIG;

	if (!$domain && !$CONFIG['GenerateRandomUsername']) {
		return "";
	}


	if (!$CONFIG['GenerateRandomUsername']) {
		$domain = strtolower($domain);
		$username = preg_replace("/[^a-z]/", "", $domain);
		$username = substr($username, 0, 8);
		$result = select_query_i("tblcustomerservices", "COUNT(*)", array("username" => $username));
		$data = mysqli_fetch_array($result);
		$username_exists = $data[0];
		$suffix = 0;

		while (0 < $username_exists) {
			++$suffix;
			$trimlength = 8 - strlen($suffix);
			$username = substr($username, 0, $trimlength) . $suffix;
			$result = select_query_i("tblcustomerservices", "COUNT(*)", array("username" => $username));
			$data = mysqli_fetch_array($result);
			$username_exists = $data[0];
		}
	}
	else {
		$lowercase = "abcdefghijklmnopqrstuvwxyz";
		$str = "";
		$seeds_count = strlen($lowercase) - 1;
		$i = 0;

		while ($i < 8) {
			$str .= $lowercase[rand(0, $seeds_count)];
			++$i;
		}

		$username = "";
		$i = 0;

		while ($i < 8) {
			$randomnum = rand(0, strlen($str) - 1);
			$username .= $str[$randomnum];
			$str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
			++$i;
		}

		$result = select_query_i("tblcustomerservices", "COUNT(*)", array("username" => $username));
		$data = mysqli_fetch_array($result);
		$username_exists = $data[0];

		while (0 < $username_exists) {
			$username = "";
			$str = "";
			$i = 0;

			while ($i < 8) {
				$str .= $lowercase[rand(0, $seeds_count)];
				++$i;
			}

			$i = 0;

			while ($i < 8) {
				$randomnum = rand(0, strlen($str) - 1);
				$username .= $str[$randomnum];
				$str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
				++$i;
			}

			$result = select_query_i("tblcustomerservices", "COUNT(*)", array("username" => $username));
			$data = mysqli_fetch_array($result);
			$username_exists = $data[0];
		}
	}

	return $username;
}

function createServerPassword() {
	$numbers = "0123456789";
	$lowercase = "abcdefghijklmnopqrstuvwxyz";
	$uppercase = "ABCDEFGHIJKLMNOPQRSTUVYWXYZ";
	$str = "";
	$seeds_count = strlen($numbers) - 1;
	$i = 0;

	while ($i < 4) {
		$str .= $numbers[rand(0, $seeds_count)];
		++$i;
	}

	$seeds_count = strlen($lowercase) - 1;
	$i = 0;

	while ($i < 4) {
		$str .= $lowercase[rand(0, $seeds_count)];
		++$i;
	}

	$seeds_count = strlen($uppercase) - 1;
	$i = 0;

	while ($i < 2) {
		$str .= $uppercase[rand(0, $seeds_count)];
		++$i;
	}

	$password = "";
	$i = 0;

	while ($i < 10) {
		$randomnum = rand(0, strlen($str) - 1);
		$password .= $str[$randomnum];
		$str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
		++$i;
	}

	return $password;
}

function getServerID($servertype, $servergroup) {
	if (!$servergroup) {
		$result = select_query_i("ra_integration", "id,maxaccounts,(SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.server=ra_integration.id AND (servicestatus='Active' OR servicestatus='Suspended')) AS usagecount", array("type" => $servertype, "active" => "1", "disabled" => "0"));
		$data = mysqli_fetch_array($result);
		$serverid = $data['id'];
		$maxaccounts = $data['maxaccounts'];
		$usagecount = $data['usagecount'];

		if ($serverid) {
			if ($maxaccounts <= $usagecount) {
				$result = full_query_i("SELECT id,((SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.server=ra_integration.id AND (servicestatus='Active' OR servicestatus='Suspended'))/maxaccounts) AS percentusage FROM ra_integration WHERE type='" . db_escape_string($servertype) . "' AND id!=" . (int)$serverid . " AND disabled=0 ORDER BY percentusage ASC");
				$data = mysqli_fetch_array($result);

				if ($data['id']) {
					$serverid = $data['id'];
					update_query("ra_integration", array("active" => ""), array("type" => $servertype));
					update_query("ra_integration", array("active" => "1"), array("type" => $servertype, "id" => $serverid));
				}
			}
		}
	}
	else {
		$result = select_query_i("ra_integration_groups", "filltype", array("id" => $servergroup));
		$data = mysqli_fetch_array($result);
		$filltype = $data['filltype'];
		$serverslist = "";
		$result = select_query_i("ra_integration_groupsrel", "serverid", array("groupid" => $servergroup));

		while ($data = mysqli_fetch_array($result)) {
			$serverslist .= (int)$data['serverid'] . ",";
		}

		$serverslist = substr($serverslist, 0, 0 - 1);

		if ($filltype == 1) {
			$result = full_query_i("SELECT id,((SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.server=ra_integration.id AND (servicestatus='Active' OR servicestatus='Suspended'))/maxaccounts) AS percentusage FROM ra_integration WHERE id IN (" . $serverslist . ") AND disabled=0 ORDER BY percentusage ASC");
			$data = mysqli_fetch_array($result);
			$serverid = $data['id'];
		}
		else {
			if ($filltype == 2) {
				$result = select_query_i("ra_integration", "id,maxaccounts,(SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.server=ra_integration.id AND (servicestatus='Active' OR servicestatus='Suspended')) AS usagecount", "id IN (" . $serverslist . ") AND active='1' AND disabled=0");
				$data = mysqli_fetch_array($result);
				$serverid = $data['id'];
				$maxaccounts = $data['maxaccounts'];
				$usagecount = $data['usagecount'];

				if ($serverid) {
					if ($maxaccounts <= $usagecount) {
						$result = full_query_i("SELECT id,((SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.server=ra_integration.id AND (servicestatus='Active' OR servicestatus='Suspended'))/maxaccounts) AS percentusage FROM ra_integration WHERE id IN (" . $serverslist . ") AND disabled=0 AND id!=" . (int)$serverid . " ORDER BY percentusage ASC");
						$data = mysqli_fetch_array($result);

						if ($data['id']) {
							$serverid = $data['id'];
							update_query("ra_integration", array("active" => ""), array("type" => $servertype));
							update_query("ra_integration", array("active" => "1"), array("type" => $servertype, "id" => $serverid));
						}
					}
				}
			}
		}
	}

	return $serverid;
}

function RebuildModuleHookCache() {
	global $CONFIG;

	$hooksarray = array();
	$dh = opendir(ROOTDIR . "/modules/service/");

	while (false !== $module = readdir($dh)) {
		if (is_file(ROOTDIR . ("/modules/service/" . $module . "/hooks.php"))) {
			$hooksarray[] = $module;
		}
	}

	closedir($dh);

	if (isset($CONFIG['ModuleHooks'])) {
		update_query("ra_config", array("value" => implode(",", $hooksarray)), array("setting" => "ModuleHooks"));
		return null;
	}

	insert_query("ra_config", array("setting" => "ModuleHooks", "value" => implode(",", $hooksarray)));
}

function moduleConfigFieldOutput($values) {
	if (!$values['Value']) {
		$values['Value'] = $values['Default'];
	}


	if ($values['Type'] == "text") {
		$code = "<input class=\"form-control\" type=\"text\" name=\"" . $values['Name'] . "\" size=\"" . $values['Size'] . "\" value=\"" . $values['Value'] . "\" />";

		if ($values['Description']) {
			$code .= " " . $values['Description'];
		}
	}
	else {
		if ($values['Type'] == "password") {
			$code = "<input class=\"form-control\" type=\"password\" name=\"" . $values['Name'] . "\" size=\"" . $values['Size'] . "\" value=\"" . $values['Value'] . "\" />";

			if ($values['Description']) {
				$code .= " " . $values['Description'];
			}
		}
		else {
			if ($values['Type'] == "yesno") {
				$code = "<label><input  type=\"checkbox\" name=\"" . $values['Name'] . "\"";

				if ($values['Value']) {
					$code .= " checked=\"checked\"";
				}

				$code .= " /> " . $values['Description'] . "</label>";
			}
			else {
				if ($values['Type'] == "dropdown") {
					$code = "<select name=\"" . $values['Name'] . "\">";
					$options = explode(",", $values['Options']);
					foreach ($options as $tempval) {
						$code .= "<option value=\"" . $tempval . "\"";

						if ($values['Value'] == $tempval) {
							$code .= " selected=\"selected\"";
						}

						$code .= ">" . $tempval . "</option>";
					}

					$code .= "</select>";

					if ($values['Description']) {
						$code .= " " . $values['Description'];
					}
				}
				else {
					if ($values['Type'] == "radio") {
						$code = "";

						if ($values['Description']) {
							$code .= $values['Description'] . "<br />";
						}

						$options = explode(",", $values['Options']);

						if (!$values['Value']) {
							$values['Value'] = $options[0];
						}

						foreach ($options as $tempval) {
							$code .= "<label><input type=\"radio\" name=\"" . $values['Name'] . "\" value=\"" . $tempval . "\"";

							if ($values['Value'] == $tempval) {
								$code .= " checked=\"checked\"";
							}

							$code .= " /> " . $tempval . "</label><br />";
						}
					}
					else {
						if ($values['Type'] == "textarea") {
							$cols = ($values['Cols'] ? $values['Cols'] : "60");
							$rows = ($values['Rows'] ? $values['Rows'] : "5");
							$code = "<textarea name=\"" . $values['Name'] . "\" cols=\"" . $cols . "\" rows=\"" . $rows . "\">" . $values['Value'] . "</textarea>";

							if ($values['Description']) {
								$code .= "<br />" . $values['Description'];
							}
						}
						else {
							$code = $values['Description'];
						}
					}
				}
			}
		}
	}

	return $code;
}

?>
