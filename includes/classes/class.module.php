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

class RA_Module {
	private $type = "";
	private $loadedmodule = "";

	const FUNCTIONDOESNTEXIST = "!Function not found in module!";

	public function __construct($type = "") {
		if ($type) {
			$this->setType($type);
		}

	}

	function settype($type) {
		global $ra;

		$type = $ra->sanitize("a-z", $type);
		$this->type = $type;
	}

	function gettype() {
		global $ra;

		$type = $ra->sanitize("a-z", $this->type);
		return $type;
	}

	private function setLoadedModule($module) {
		$this->loadedmodule = $module;
	}

	private function getLoadedModule() {
		return $this->loadedmodule;
	}

	public function getList($type = "") {
		if ($type) {
			$this->setType($type);
		}

		$modules = array();
		$dirpath = ROOTDIR . "/modules/" . $this->getType() . "/";

		if (!is_dir($dirpath)) {
			return false;
		}

		$dh = opendir($dirpath);

		while (false !== $module = readdir($dh)) {
			if (is_file($dirpath . ("/" . $module . "/" . $module . ".php"))) {
				$modules[] = $module;
			}
		}

		sort($modules);
		return $modules;
	}

	public function load($module) {
		global $ra;

		$module = $ra->sanitize("0-9a-z_-", $module);
		$modpath = ROOTDIR . "/modules/" . $this->getType() . ("/" . $module . "/" . $module . ".php");

		if (!file_exists($modpath)) {
			return false;
		}

		include_once $modpath;
		$this->setLoadedModule($module);
		return true;
	}

	public function call($name, $params = array()) {
		global $ra;

		if ($this->isExists($name)) {
			$response = call_user_func($this->getLoadedModule() . "_" . $name, $params);
			return $response;
		}

		return FUNCTIONDOESNTEXIST;
	}

	public function isExists($name) {
		return function_exists($this->getLoadedModule() . "_" . $name);
	}
}

?>
