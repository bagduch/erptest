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

class RA_Cookie {
	public function __construct() {
	}

	public static function get($name, $decodearray = false) {
		$val = (array_key_exists("ra" . $name, $_COOKIE) ? $_COOKIE["ra" . $name] : "");

		if ($decodearray) {
			$val = json_decode(base64_decode($val), true);
			$val = (is_array($val) ? htmlspecialchars_array($val) : array());
		}

		return $val;
	}

	public static function set($name, $value, $expires = 0, $secure = false) {
		if (is_array($value)) {
			$value = base64_encode(json_encode($value));
		}


		if (!is_numeric($expires)) {
			if (substr($expires, 0 - 1) == "m") {
				$expires = time() + substr($expires, 0, 0 - 1) * 30 * 24 * 60 * 60;
			}
			else {
				$expires = 0;
			}
		}

		return setcookie("ra" . $name, $value, $expires, "/", null, $secure, true);
	}

	public static function delete($name) {
		unset($_COOKIE["ra" . $name]);
		return self::set($name, null, 0 - 86400);
	}
}

?>