<?php

/**
 * RA_Module - represents all types of modules
 */
class RA_Module {
	private $type = null;
	private $loadedmodule = null;

	const FUNCTIONDOESNTEXIST = "!Function not found in module!";
	const MODULETYPES = [ "addons", "fraud","gateways","reports","service","widgets" ]

  /**
   * Constructor
   * @param string $type type of module (see MODULETYPES const)
   */
	public function __construct($type) {
		if ($type) {
			$this->setType($type);
		}
	}

  /**
   * Sets the module type
   * @param string $type type of module (see MODULETYPES)
   */
	function setType($type) {
		if (in_array($type,MODULETYPES)) {
			$this->type = $type;
		}
	}
  /**
   * getType()
   * @return string module type
   */
	function getType() {
		if (!isnull($this->type)) {
			return $this->$type;
		} else {
			return false;
		}
	}

  /**
   * setLoadedModule sets the module for the current instantiation
   * @param string $module Name of module (directory name)
   * Module names can only consist of a-z 0-9 _ -
   * Doesn't check that the module actually exists
   */
	private function setLoadedModule($module) {
		if (preg_match('/^([a-z0-9\-_])+$/',$module) {
			$this->loadedmodule = $module;
		} else {
			return false;
		}
	}

  /**
   * getLoadedModule
   * @return string name of currently loaded module, or false if nothing
   */
	private function getLoadedModule() {
		if (!isnull($this->loadedmodule)) {
			return $this->loadedmodule;
		} else {
			return false;
		}
	}

  /**
   * getList gets a list of all modules of a certain type
   * Checks for ${module}/${module}.php in the moduletype directory
   * @param  string $type Type of module to search for
   * @return array array of module names
   */
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

  /**
   * load Actually loads the main module file
   * (and pulls through function/property definitions)
   * @param  string $module Name of module to load
   * @return boolean for success or false
   */
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

  /**
   * calls the function inside the module
   * call("funcname",[1,2,3,4]) translates to modulename_funcname([1,2,3,4]) -
   * That is, parameters on both sides must be inside a single array
   * @param  string $name   Name of function (without modname_ prefix)
   * @param  array  $params Array of parameters to pass to the called function
   * @return mixed return vals from called function, or FUCTIONDOESNOTEXIST
   *   string if the function doesn't exist
   */
	public function call($name, $params = array()) {
		global $ra;

		if ($this->isExists($name)) {
			$response = call_user_func($this->getLoadedModule() . "_" . $name, $params);
			return $response;
		}

		return FUNCTIONDOESNTEXIST;
	}

  /**
   * Checks whether the named function exists
   * @param  string  $name  Name of the function (without modname_ prefix)
   * @return boolean   Whether function exists
   */
	public function isExists($name) {
		return function_exists($this->getLoadedModule() . "_" . $name);
	}
}

?>
