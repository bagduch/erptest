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

class RA_ClientArea 
{
	private $pagetitle = "";
    private $breadcrumb = array( );
    private $templatefile = "";
    private $templatevars = array( );
    private $nowrapper = false;
    private $inorderform = false;
    private $insupportmodule = false;
    private $smarty = "";

	public function __construct() {
		global $smartyvalues;
		$smartyvalues = array();
	}

	public function setPageTitle($text) {
		global $ra;
		$this->pagetitle = $text;
	}

	public function addToBreadCrumb($link, $text) {
		$this->breadcrumb[] = array($link, $text);
	}

	public function getUserID() {
		return (int)RA_Session::get("uid");
	}

	public function isLoggedIn() {
		return $this->getUserID() ? true : false;
	}

	public function requireLogin() {
		global $ra;

		if ($this->isLoggedIn()) {
			if (RA_Session::get("2fabackupcodenew")) {
				$this->setTemplate("logintwofa");
				$twofa = new RA_2FA();

				if ($twofa->setClientID($this->getUserID())) {
					$backupcode = $twofa->generateNewBackupCode();
					$this->assign("newbackupcode", $backupcode);
					RA_Session::delete("2fabackupcodenew");
				}
				else {
					$this->assign("newbackupcodeerror", true);
				}

				$this->output();
				exit();
			}

			return true;
		}

		$_SESSION['loginurlredirect'] = html_entity_decode($_SERVER['REQUEST_URI']);

		if (RA_Session::get("2faverifyc")) {
			$this->setTemplate("logintwofa");

			if (RA_Session::get("2fabackupcodenew")) {
				$this->assign("newbackupcode", true);
			}
			else {
				if ($ra->get_req_var("incorrect")) {
					$this->assign("incorrect", true);
				}
			}

			$twofa = new RA_2FA();

			if ($twofa->setClientID(RA_Session::get("2faclientid"))) {
				if (!$twofa->isActiveClients() || !$twofa->isEnabled()) {
					RA_Session::destroy();
					redir();
				}


				if ($ra->get_req_var("backupcode")) {
					$this->assign("backupcode", true);
				}
				else {
					$challenge = $twofa->moduleCall("challenge");

					if ($challenge) {
						$this->assign("challenge", $challenge);
					}
					else {
						$this->assign("error", "Bad 2 Factor Auth Module. Please contact support.");
					}
				}
			}
			else {
				$this->assign("error", "An error occurred. Please try again.");
			}
		}
		else {
			$this->setTemplate("login");
			$this->assign("loginpage", true);
			$this->assign("formaction", "dologin.php");

			if ($ra->get_req_var("incorrect")) {
				$this->assign("incorrect", true);
			}
		}

		$this->output();
		exit();
	}

	public function setTemplate($filename) {
		$this->templatefile = $filename;
	}

	public function assign($key, $value) {
		$this->templatevars[$key] = $value;
		$this->smarty->assign($key, $value);
	}

	public static function getRawStatus($val) {
		$val = strtolower($val);
		$val = str_replace(" ", "", $val);
		$val = str_replace("-", "", $val);
		return $val;
	}

	public function startSmartyIfNotStarted() {
		if (is_object($this->smarty)) {
			return true;
		}

		return $this->startSmarty();
	}

	public function startSmarty() {
		global $ra;
		global $smarty;

		require ROOTDIR . "/includes/smarty/Smarty.class.php";

		if (!$smarty) {
			$smarty = new Smarty();
		}

		$this->smarty = &$smarty;

		$this->smarty->caching = 0;
		$this->smarty->template_dir = ROOTDIR . "/templates/";
		$this->smarty->compile_dir = $ra->get_template_compiledir_name();
		return true;
	}

	public function getCurrentPageName() {
		$filename = $_SERVER['PHP_SELF'];
		$filename = substr($filename, strrpos($filename, "/"));
		$filename = str_replace("/", "", $filename);
		$filename = explode(".", $filename);
		$filename = $filename[0];
		return $filename;
	}

	public function registerDefaultTPLVars() {
		global $ra;
		global $_LANG;

		$this->assign("template", $ra->get_sys_tpl_name());
		$this->assign("language", $ra->get_client_language());
		$this->assign("LANG", $_LANG);
		$this->assign("companyname", $ra->get_config("CompanyName"));
		$this->assign("logo", $ra->get_config("LogoURL"));
		$this->assign("charset", $ra->get_config("Charset"));
		$this->assign("pagetitle", $this->pagetitle);
		$this->assign("filename", $this->getCurrentPageName());
		$this->assign("token", generate_token("plain"));

		if ($ra->in_ssl() && $ra->get_config("SystemSSLURL")) {
			$this->assign("systemurl", $ra->get_config("SystemSSLURL") . "/");
		}
		else {
			if ($ra->get_config("SystemURL") != "http://www.yourdomain.com/ra") {
				$this->assign("systemurl", $ra->get_config("SystemURL") . "/");
			}
		}


		if ($ra->get_config("SystemSSLURL")) {
			$this->assign("systemsslurl", $ra->get_config("SystemSSLURL") . "/");
		}

		$this->assign("todaysdate", date("l, jS F Y"));
		$this->assign("date_day", date("d"));
		$this->assign("date_month", date("m"));
		$this->assign("date_year", date("Y"));
	}

	public function getCurrencyOptions() {
		$currenciesarray = array();
		$result = select_query_i("tblcurrencies", "id,code,`default`", "", "code", "ASC");

		while ($data = mysqli_fetch_array($result)) {
			$currenciesarray[] = array("id" => $data['id'], "code" => $data['code'], "default" => $data['default']);
		}


		if (count($currenciesarray) == 1) {
			$currenciesarray = "";
		}

		return $currenciesarray;
	}

	public function getLanguageSwitcherHTML() {
		global $ra;

		if (!$ra->get_config("AllowLanguageChange")) {
			return false;
		}

		$setlanguage = "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'];
		$count = 0;
		foreach ($_GET as $k => $v) {
			$prefix = ($count == 0 ? "?" : "&");
			$setlanguage .= $prefix . htmlentities($k) . "=" . htmlentities($v);
			++$count;
		}

		$setlanguage .= "\" name=\"languagefrm\" id=\"languagefrm\"><strong>" . $ra->get_lang("language") . ":</strong> <select name=\"language\" onchange=\"languagefrm.submit()\">";
		foreach ($ra->getValidLanguages() as $lang) {
			$setlanguage .= "<option";

			if ($lang == $ra->get_client_language()) {
				$setlanguage .= " selected=\"selected\"";
			}

			$setlanguage .= ">" . ucfirst($lang) . "</option>";
		}

		$setlanguage .= "</select></form>";
		return $setlanguage;
	}

	public function initPage() {
		global $ra;
		global $_LANG;
		global $clientsdetails;

		$this->startSmartyIfNotStarted();

		if ($this->isLoggedIn()) {
			$this->assign("loggedin", true);

			if (!function_exists("getClientsDetails")) {
				require ROOTDIR . "/includes/clientfunctions.php";
			}

			$clientsdetails = getClientsDetails();
			$this->assign("clientsdetails", $clientsdetails);
			$this->assign("clientsstats", getClientsStats($_SESSION['uid']));

			if (isset($_SESSION['cid'])) {
				$result = select_query_i("tblcontacts", "id,firstname,lastname,email,permissions", array("id" => $_SESSION['cid'], "userid" => $_SESSION['uid']));
				$data = mysqli_fetch_array($result);
				$loggedinuser = array("contactid" => $data['id'], "firstname" => $data['firstname'], "lastname" => $data['lastname'], "email" => $data['email']);
				$contactpermissions = explode(",", $data[4]);
			}
			else {
				$loggedinuser = array("userid" => $_SESSION['uid'], "firstname" => $clientsdetails['firstname'], "lastname" => $clientsdetails['lastname'], "email" => $clientsdetails['email']);
				$contactpermissions = array("profile", "contacts", "products", "manageproducts", "domains", "managedomains", "invoices", "tickets", "affiliates", "emails", "orders");
			}

			$this->assign("loggedinuser", $loggedinuser);
			$this->assign("contactpermissions", $contactpermissions);
			return null;
		}

		$this->assign("loggedin", false);
	}

	public function getSingleTPLOutput($templatepath, $templatevars) {
		global $ra;
		global $smartyvalues;

		$this->startSmartyIfNotStarted();
		$this->registerDefaultTPLVars();

		if (is_array($smartyvalues)) {
			foreach ($smartyvalues as $key => $value) {
				$this->assign($key, $value);
			}
		}

		foreach ($this->templatevars as $key => $value) {
			$this->smarty->assign($key, $value);
		}

		foreach ($templatevars as $key => $value) {
			$this->smarty->assign($key, $value);
		}

		$templatecode = $this->smarty->fetch(ROOTDIR . $templatepath);
		$this->smarty->clear_all_assign();
		return $templatecode;
	}

	public function runClientAreaOutputHook($hookname) {
		$hookres = run_hook($hookname, $this->templatevars);
		$output = "";
		foreach ($hookres as $data) {

			if ($data) {
				$output .= $data . "\r\n";
				continue;
			}
		}

		return $output;
	}

	public static function getConditionalLinks() {
		global $ra;

		$calinkupdatecc = (isset($_SESSION['calinkupdatecc']) ? $_SESSION['calinkupdatecc'] : CALinkUpdateCC());
		$security = (isset($_SESSION['calinkupdatesq']) ? $_SESSION['calinkupdatesq'] : CALinkUpdateSQ());

		if (!$security) {
			$twofa = new RA_2FA();

			if ($twofa->isActiveClients()) {
				$security = true;
			}
		}

		return array("updatecc" => $calinkupdatecc, "updatesq" => $security, "security" => $security, "addfunds" => $ra->get_config("AddFundsEnabled"), "masspay" => $ra->get_config("EnableMassPay"), "affiliates" => $ra->get_config("AffiliateEnabled"), "domainreg" => $ra->get_config("AllowRegister"), "domaintrans" => $ra->get_config("AllowTransfer"), "domainown" => $ra->get_config("AllowOwnDomain"), "pmaddon" => get_query_val("tbladdonmodules", "value", array("module" => "project_management", "setting" => "clientenable")));
	}

	public function buildBreadCrumb() {
		$breadcrumb = array();
		foreach ($this->breadcrumb as $vals) {
			$breadcrumb[] = "<a href=\"" . $vals[0] . "\">" . $vals[1] . "</a>";
		}

		return implode(" > ", $breadcrumb);
	}

	public function output() {
		global $ra;
		global $smartyvalues;

		if (!$this->templatefile) {
			exit("Missing Template File '" . $this->templatefile . "'");
		}

		$this->registerDefaultTPLVars();
		$this->assign("breadcrumbnav", $this->buildBreadCrumb());
		$this->assign("langchange", ($ra->get_config("AllowLanguageChange") ? true : false));
		$this->assign("setlanguage", $this->getLanguageSwitcherHTML());
		$this->assign("currencies", $this->getCurrencyOptions());
		$this->assign("twitterusername", $ra->get_config("TwitterUsername"));
		$this->assign("condlinks", $this->getConditionalLinks());

		if (is_array($smartyvalues)) {
			foreach ($smartyvalues as $key => $value) {
				$this->assign($key, $value);
			}
		}

		foreach ($this->templatevars as $key => $value) {
			$this->smarty->assign($key, $value);
		}


		if (isset($GLOBALS['pagelimit'])) {
			$smartyvalues['itemlimit'] = $GLOBALS['pagelimit'];
		}

		$hookvars = $this->templatevars;
		unset($hookvars['LANG']);
		$hookres = run_hook("ClientAreaPage", $hookvars);
		foreach ($hookres as $arr) {
			foreach ($arr as $k => $v) {
				$hookvars[$k] = $v;
				$this->assign($k, $v);
			}
		}

		$this->assign("headoutput", $this->runClientAreaOutputHook("ClientAreaHeadOutput"));
		$this->assign("headeroutput", $this->runClientAreaOutputHook("ClientAreaHeaderOutput"));
		$this->assign("footeroutput", $this->runClientAreaOutputHook("ClientAreaFooterOutput"));

		if (!$this->nowrapper) {
			$header_file = $this->smarty->fetch($ra->get_sys_tpl_name() . "/header.tpl");
			$footer_file = $this->smarty->fetch($ra->get_sys_tpl_name() . "/footer.tpl");
		}


		if ($this->inorderform) {
			global $orderfrm;

			$body_file = $this->smarty->fetch(ROOTDIR . "/templates/orderforms/" . $orderfrm->getTemplate() . "/" . $this->templatefile . ".tpl");
		}
		else {
			if ($this->insupportmodule) {
				$body_file = $this->smarty->fetch(ROOTDIR . "/templates/" . $ra->get_config("SupportModule") . "/" . $this->templatefile . ".tpl");
			}
			else {
				if (substr($this->templatefile, 0, 1) == "/") {
					$body_file = $this->smarty->fetch(ROOTDIR . $this->templatefile);
				}
				else {
					$body_file = $this->smarty->fetch(ROOTDIR . "/templates/" . $ra->get_sys_tpl_name() . "/" . $this->templatefile . ".tpl");
				}
			}
		}

		$this->smarty->clear_all_assign();

		if (isset($_SESSION['adminid'])) {
			$adminloginlink = "<div class=\"adminreturndiv\">Logged in as Administrator | <a href=\"" . $ra->get_admin_folder_name() . "/";

			if (isset($_SESSION['uid'])) {
				$adminloginlink .= "clientssummary.php?userid=" . $_SESSION['uid'] . "&return=1";
			}

			$adminloginlink .= "\" style=\"color:#6699ff\">Return to Admin Area</a></div>

    ";
		}
		else {
			$adminloginlink = "";
		}


		if ($this->nowrapper) {
			$template_output = $body_file;
		}
		else {
			$template_output = $header_file . "

" . $body_file . "

" . $copyrighttext . "

" . $adminloginlink . "

" . $footer_file;
		}


		if (!in_array($this->templatefile, array("3dsecure", "forwardpage", "viewinvoice"))) {
			$template_output = preg_replace('/(<form\W[^>]*\bmethod=(\'|"|)POST(\'|"|)\b[^>]*>)/i', '$1' . "\n" . generate_token(), $template_output);

		}

		echo $template_output;
		exit();
	}
}

?>
