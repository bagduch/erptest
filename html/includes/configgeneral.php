<?php

function cleanSystemURL($url, $secure = false, $keepempty = false) {
	global $ra;

	if ($url == "" || !preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $url)) {
		if ($keepempty == true) {
			return "";
		}


		if ($secure == true) {
			$url = "https://" . $_SERVER['SERVER_NAME'] . preg_replace('#/[^/]*\.php$#simU', '/', $_SERVER['PHP_SELF']);
		}
		else {
			$url = "http://" . $_SERVER['SERVER_NAME'] . preg_replace('#/[^/]*\.php$#simU', '/', $_SERVER['PHP_SELF']);
		}
	}
	else {
		$url = str_replace('\\', '', trim($url));

			if (!preg_match('~^(?:ht)tps?://~i', $url)) {
			if ($secure == true) {
				$url = "https://" . $url;
			}
			else {
				$url = "http://" . $url;
			}
		}

		$url = preg_replace('~^https?://[^/]+$~', ';/', $url);
	}


	if (substr($url, 0 - 1) != "/") {
		$url .= "/";
	}

	return str_replace("/" . $ra->get_admin_folder_name() . "/", "/", $url);
}

