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

class RA_Service
{
	private $id = "";
	private $userid = "";
	private $data = array();
	private $moduleparams = array();
	private $moduleresults = array();
	private $addons_names = null;
	private $addons_to_pids = array();
	private $addons_downloads = array();
	private $associated_download_ids = array();

	public function __construct($serviceid, $userid = "") {
		$this->setServiceID($serviceid, $userid);
		return $this;
	}

	public function setServiceID($serviceid, $userid = "") {
		$this->id = $serviceid;
		$this->userid = $userid;
		$this->data = array();
		$this->moduleparams = array();
		$this->moduleresults = array();
		return $this->getServicesData();
	}

	public function getServicesData() {
		$where = array("tblcustomerservices.id" => $this->id);

		if ($this->userid) {
			$where["tblcustomerservices.userid"] = $this->userid;
		}

		$result = select_query_i("tblcustomerservices", "tblcustomerservices.*,ra_catalog_groups.name AS groupname,ra_catalog.name AS productname,ra_catalog.type,ra_catalog.servertype", $where, "", "", "", "ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_catalog_groups ON ra_catalog_groups.id=ra_catalog.gid");
		$data = mysqli_fetch_array($result);

		if ($data['id']) {
			$data['pid'] = $data['packageid'];
			$data['status'] = $data['servicestatus'];
			$data['password'] = decrypt($data['password']);

			if ($data['downloads']) {
				$this->associated_download_ids = unserialize($data['downloads']);
			}

			$this->data = $data;
			return true;
		}

		return false;
	}

	public function isNotValid() {
		return !count($this->data) ? true : false;
	}

	public function getData($var) {
		return isset($this->data[$var]) ? $this->data[$var] : "";
	}

	public function getID() {
		return $this->getData("id");
	}

	public function getServerInfo() {
		if (!$this->getData("server")) {
			return array();
		}

		$result = select_query_i("ra_integration", "", array("id" => $this->getData("server")));
		$serverarray = mysqli_fetch_assoc($result);
		return $serverarray;
	}

	public function getSuspensionReason() {
		global $ra;

		if ($this->getData("status") != "Suspended") {
			return "";
		}

		$suspendreason = $this->getData("suspendreason");

		if (!$suspendreason) {
			$suspendreason = $ra->get_lang("suspendreasonoverdue");
		}

		return $suspendreason;
	}

	public function getBillingCycleDisplay() {
		global $ra;

		$lang = strtolower($this->getData("billingcycle"));
		$lang = str_replace(" ", "", $lang);
		$lang = str_replace("-", "", $lang);
		return $ra->get_lang("orderpaymentterm" . $lang."text");
	}

	public function getStatusDisplay() {
		global $ra;

		$lang = strtolower($this->getData("status"));
		$lang = str_replace(" ", "", $lang);
		$lang = str_replace("-", "", $lang);
		return $ra->get_lang("clientarea" . $lang);
	}

	public function getPaymentMethod() {
		$this->getData("paymentmethod");
		$displayname = $paymentmethod = get_query_val("ra_modules_gateways", "value", array("gateway" => $paymentmethod, "setting" => "name"));
		return $displayname ? $displayname : $paymentmethod;
	}

	public function getAllowProductUpgrades() {
		if ($this->getData("status") == "Active" && $this->getData("upgradepackages")) {
			unserialize($this->getData("upgradepackages"));
			$upgradepackages = $upgradepackages = count($upgradepackages);
			return $upgradepackages ? true : false;
		}

		return false;
	}

	public function getAllowConfigOptionsUpgrade() {
		if ($this->getData("status") == "Active" && $this->getData("configoptionsupgrade")) {
			return true;
		}

		return false;
	}

	public function getAllowChangePassword() {
		if ($this->getData("status") == "Active") {
			return true;
		}

		return false;
	}

	public function getModule() {
		global $ra;

		return $ra->sanitize("0-9a-z_-", $this->getData("servertype"));
	}

	public function getPredefinedAddonsOnce() {
		if (is_array($this->addons_names)) {
			return $this->addons;
		}

		return $this->getPredefinedAddons();
	}

	public function getPredefinedAddons() {
		$this->addons_names = array();
		$result = select_query_i("tbladdons", "", "");

		while ($data = mysqli_fetch_array($result)) {
			$addon_id = $data['id'];
			$addon_packages = $data['packages'];
			$addon_packages = explode(",", $addon_packages);
			$this->addons_names[$addon_id] = $data['name'];
			$this->addons_to_pids[$addon_id] = $addon_packages;
			$this->addon_downloads[$addon_id] = explode(",", $data['downloads']);
		}

		return $this->addons_names;
	}

	private function addAssociatedDownloadID($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $id) {

				if (is_numeric($id)) {
					$this->associated_download_ids[] = $id;
					continue;
				}
			}
		}
		else {
			if (is_numeric($mixed)) {
				$this->associated_download_ids[] = $mixed;
			}
			else {
				return false;
			}
		}

		return true;
	}

	public function hasProductGotAddons() {
		$addons = array();
		foreach ($this->addons_to_pids as $addonid => $pids) {

			if (in_array($this->getData("pid"), $pids)) {
				$addons[] = $addonid;
				continue;
			}
		}

		return $addons;
	}

	public function getAddons() {
		global $ra;
		$predefinedaddons = $this->getPredefinedAddonsOnce();
		$addons = array();
		$result = select_query_i("ra_catalog_user_sales_addons", "", array("serviceid" => $this->id), "id", "DESC");

		while ($data = mysqli_fetch_array($result)) {

			$addon_id = $data['id'];
			$addon_addonid = $data['addonid'];
			$addon_regdate = $data['regdate'];
			$addon_name = $data['name'];
			$addon_setupfee = $data['setupfee'];
			$addon_recurring = $data['recurring'];
			$addon_paymentmethod = $data['paymentmethod'];
			$addon_billingcycle = $data['billingcycle'];
			$addon_free = $data['free'];
			$addon_status = $data['status'];
			$addon_nextduedate = $data['nextduedate'];

			if ($addon_addonid) {
				if (!$addon_name) {
					$addon_name = $predefinedaddons[$addon_addonid];
				}
				$addon_downloads = $this->addon_downloads[$addon_addonid];
				if (count($addon_downloads)) {
					$this->addAssociatedDownloadID($addon_downloads);
				}
			}
			$addon_regdate = fromMySQLDate($addon_regdate, 0, 1);
			$addon_nextduedate = fromMySQLDate($addon_nextduedate, 0, 1);
			$addon_pricing = "";
			if (substr($addon_billingcycle, 0, 4) == "Free") {
				$addon_pricing = $ra->get_lang("orderfree");
				$addon_nextduedate = "-";
			}
			else {
				if ($addon_billingcycle == "One Time") {
					$addon_nextduedate = "-";
				}
				if (0 < $addon_setupfee) {
					$addon_pricing .= formatCurrency($addon_setupfee) . " " . $ra->get_lang("ordersetupfee") . " + ";
				}

				$modifiedpt = str_replace(array("-", " "), "", strtolower($addon_billingcycle));

				if (0 < $addon_recurring) {
					$addon_pricing .= formatCurrency($addon_recurring) . " " . $ra->get_lang("orderpaymentterm" . $modifiedpt) . "<br>";
				}


				if (!$addon_pricing) {
					$addon_pricing = $ra->get_lang("orderfree");
				}
			}

			$rawstatus = strtolower($addon_status);

			if ($addon_status == "Active") {
				$xcolor = "clientareatableactive";
				$addon_status = $ra->get_lang("clientareaactive");
			}
			else {
				if ($addon_status == "Suspended") {
					$xcolor = "clientareatablesuspended";
					$addon_status = $ra->get_lang("clientareasuspended");
				}
				else {
					if ($addon_status == "Pending") {
						$xcolor = "clientareatablepending";
						$addon_status = $ra->get_lang("clientareapending");
					}
					else {
						if ($addon_status == "Cancelled") {
							$xcolor = "clientareatableterminated";
							$addon_status = $ra->get_lang("clientareacancelled");
						}
						else {
							if ($addon_status == "Fraud") {
								$xcolor = "clientareatableterminated";
								$addon_status = $ra->get_lang("clientareafraud");
							}
							else {
								$xcolor = "clientareatableterminated";
								$addon_status = $ra->get_lang("clientareaterminated");
							}
						}
					}
				}
			}

			$addons[] = array("id" => $addon_id, "regdate" => $addon_regdate, "name" => $addon_name, "pricing" => $addon_pricing, "paymentmethod" => $addon_paymentmethod, "nextduedate" => $addon_nextduedate, "status" => $addon_status, "rawstatus" => $rawstatus, "class" => $xcolor);
		}

		return $addons;
	}

	public function getAssociatedDownloads() {
		if (!count($this->associated_download_ids)) {
			return array();
		}

		$downloadsarray = array();
		$result = select_query_i("tbldownloads", "", "id IN (" . db_build_in_array(db_escape_numarray($this->associated_download_ids)) . ")", "id", "DESC");

		while ($data = mysqli_fetch_array($result)) {
			$dlid = $data['id'];
			$category = $data['category'];
			$type = $data['type'];
			$title = $data['title'];
			$description = $data['description'];
			$downloads = $data['downloads'];
			$location = $data['location'];
			$fileext = explode(".", $location);
			$fileext = end($fileext);
			$type = "zip";

			if ($fileext == "doc") {
				$type = "doc";
			}


			if ((($fileext == "gif" || $fileext == "jpg") || $fileext == "jpeg") || $fileext == "png") {
				$type = "picture";
			}


			if ($fileext == "txt") {
				$type = "txt";
			}

			$type = "<img src=\"images/" . $type . ".png\" align=\"absmiddle\" alt=\"\" />";
			$downloadsarray[] = array("id" => $dlid, "catid" => $category, "type" => $type, "title" => $title, "description" => $description, "downloads" => $downloads, "link" => "dl.php?type=d&id=" . $dlid . "&serviceid=" . $this->getID());
		}

		return $downloadsarray;
	}

	public function getCustomFields() {
		return getCustomFields("product", $this->getData("pid"), $this->getData("id"), "", "", "", true);
	}

	public function getConfigurableOptions() {
		return getCartConfigOptions($this->getData("pid"), "", $this->getData("billingcycle"), $this->getData("id"));
	}

	public function getAllowCancellation() {
		global $ra;

		if ($this->getData("status") == "Active" || $this->getData("status") == "Suspended") {
			return $ra->get_config("ShowCancellationButton") ? true : false;
		}

		return false;
	}

	public function getDiskUsageStats() {
		global $ra;

		$diskusage = $this->getData("diskusage");
		$disklimit = $this->getData("disklimit");
		$bwusage = $this->getData("bwusage");
		$bwlimit = $this->getData("bwlimit");
		$lastupdate = $this->getData("lastupdate");

		if ($disklimit == "0") {
			$disklimit = $ra->get_lang("clientareaunlimited");
			$diskpercent = "0%";
		}
		else {
			$diskpercent = round($diskusage / $disklimit * 100, 0) . "%";
		}


		if ($bwlimit == "0") {
			$bwlimit = $ra->get_lang("clientareaunlimited");
			$bwpercent = "0%";
		}
		else {
			$bwpercent = round($bwusage / $bwlimit * 100, 0) . "%";
		}

		$lastupdate = ($lastupdate == "0000-00-00 00:00:00" ? "" : fromMySQLDate($lastupdate, 1, 1));
		return array("diskusage" => $diskusage, "disklimit" => $disklimit, "diskpercent" => $diskpercent, "bwusage" => $bwusage, "bwlimit" => $bwlimit, "bwpercent" => $bwpercent, "lastupdate" => $lastupdate);
	}

	public function hasFunction($function) {
		$mod = new RA_Module("servers");
		$module = $this->getModule();

		if (!$module) {
			$this->moduleresults = array("error" => "Service not assigned to a module");
			return false;
		}

		$loaded = $mod->load($module);

		if (!$loaded) {
			$this->moduleresults = array("error" => "Product module not found");
			return false;
		}

		return $mod->isExists($function);
	}

	public function moduleCall($function, $vars = "") {
		$mod = new RA_Module("servers");
		$module = $this->getModule();

		if (!$module) {
			$this->moduleresults = array("error" => "Service not assigned to a module");
			return false;
		}

		$loaded = $mod->load($module);

		if (!$loaded) {
			$this->moduleresults = array("error" => "Product module not found");
			return false;
		}

		$params = $this->buildParams($vars);
		$results = $mod->call($function, $params);

		if ($results == false) {
			$this->moduleresults = array("error" => "Function not found");
			return false;
		}


		if (is_array($results)) {
			$results = array("data" => $results);
		}
		else {
			$results = (($results == "success" || !$results) ? array() : array("error" => $results, "data" => $results));
		}

		$this->moduleresults = $results;
		return (isset($results['error']) && $results['error']) ? false : true;
	}

	public function buildParams($vars = "") {
		if (count($this->moduleparams)) {
			$params = $this->moduleparams;

			if (is_array($vars)) {
				$params = array_merge($params, $vars);
			}

			return $params;
		}

		$params = array();
		$params['accountid'] = $this->getData("id");
		$params['serviceid'] = $this->getData("id");
		$params['domain'] = $this->getData("domain");
		$params['username'] = $this->getData("username");
		$params['password'] = html_entity_decode($this->getData("password"));
		$params['packageid'] = $this->getData("pid");
		$params['pid'] = $this->getData("pid");
		$params['serverid'] = $this->getData("server");
		$params['type'] = $this->getData("type");
		$params['producttype'] = $this->getData("type");
		$params['moduletype'] = $this->getModule();
		$fields = array();
		$counter = 1;

		while ($counter <= 10) {
			$fields[] = "configoption" . $counter;
			$counter += 1;
		}

		$moduleconfigops = get_query_vals("ra_catalog", implode(",", $fields), array("id" => $this->getData("pid")));
		foreach ($fields as $field) {
			$params[$field] = $moduleconfigops[$field];
		}

		$customfields = array();
		$result = full_query_i("SELECT ra_catalog_user_sales_fields.fieldname,ra_catalog_user_sales_fieldsvalues.value FROM ra_catalog_user_sales_fields,ra_catalog_user_sales_fieldsvalues WHERE ra_catalog_user_sales_fields.cfid=ra_catalog_user_sales_fieldsvalues.fieldid AND ra_catalog_user_sales_fieldsvalues.relid='" . (int)$this->getData("id") . "' AND ra_catalog_user_sales_fields.relid='" . (int)$this->getData("pid") . "'");

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
		$result = full_query_i("SELECT ra_catalog_user_sales_addons_options.optionname,ra_catalog_user_sales_addons_options.optiontype,ra_catalog_user_sales_addons_optionssub.optionname,tblhostingconfigoptions.qty FROM ra_catalog_user_sales_addons_options,ra_catalog_user_sales_addons_optionssub,tblhostingconfigoptions,ra_catalog_user_sales_addons_links WHERE tblhostingconfigoptions.configid=ra_catalog_user_sales_addons_options.id AND tblhostingconfigoptions.optionid=ra_catalog_user_sales_addons_optionssub.id AND tblhostingconfigoptions.relid='" . (int)$this->getData("id") . "' AND ra_catalog_user_sales_addons_links.gid=ra_catalog_user_sales_addons_options.gid AND ra_catalog_user_sales_addons_links.pid='" . (int)$this->getData("pid") . "'");

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

		$clientsdetails = getClientsDetails($this->getData("userid"));
		$clientsdetails['fullstate'] = $clientsdetails['state'];
		$clientsdetails = foreignChrReplace($clientsdetails);
		$params['clientsdetails'] = $clientsdetails;
		$data = $this->getServerInfo();

		if (count($data)) {
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
			$params['serverip'] = "";
			$params['serverhostname'] = "";
			$params['serverusername'] = "";
			$params['serverpassword'] = "";
			$params['serveraccesshash'] = "";
			$params['serversecure'] = "";
		}

		$this->moduleparams = $params;

		if (is_array($vars)) {
			$params = array_merge($params, $vars);
		}

		return $params;
	}

	public function getModuleReturn($var = "") {
		if (!$var) {
			return $this->moduleresults;
		}

		return isset($this->moduleresults[$var]) ? $this->moduleresults[$var] : "";
	}

	public function getLastError() {
		return $this->getModuleReturn("error");
	}
}

?>
