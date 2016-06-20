<?php

/* 
 * dbfunctions_simple.php: all DB functions which don't actually require DB access
 */

function db_make_safe_date($date) {
	$dateparts = explode("-", $date);
	$date = (int)$dateparts[0] . "-" . str_pad((int)$dateparts[1], 2, "0", STR_PAD_LEFT) . "-" . str_pad((int)$dateparts[2], 2, "0", STR_PAD_LEFT);
	return db_escape_string($date);
}

function db_make_safe_human_date($date) {
	$date = toMySQLDate($date);
	return db_make_safe_date($date);
}

function db_is_valid_amount($amount) {
	return preg_match('/^-?[0-9\.]+$/', $amount) === 1 ? true : false;
}

function tokenizeOrderby($input, $default_ordering = "ASC") {
	$field_separator = ",";
	$field_begin = "`";
	$field_end = "`";
	$seg_qualifier = ".";
	$qualifier = $field_end . $seg_qualifier . $field_begin;
	$order_up_rev = "CSA ";
	$order_down_rev = "CSED ";

	if ($default_ordering) {
		$default_ordering = trim($default_ordering);
	}
	else {
		$default_ordering = "ASC";
	}

	$default_ordering_rev = strrev(" " . $default_ordering);

	if ($default_ordering_rev != $order_up_rev && $default_ordering_rev != $order_down_rev) {
		$default_ordering_rev = $order_up_rev;
	}

	$tokenizedFields = array();
	$i = 0;
	$field = strtok($input, $field_separator);

	while ($i < 30 && $field !== false) {
		$field = trim($field);

		if (!$field) {
			continue;
		}


		while (strpos($field, $field_begin) === 0) {
			$field = substr($field, 1);
		}

		$rev_field = strrev($field);
		$ordering_field_rev = "";

		if (strpos($rev_field, $order_up_rev) === 0) {
			$ordering_field_rev .= $order_up_rev;
			$rev_field = substr($rev_field, strlen($order_up_rev));
		}
		else {
			if (strpos($rev_field, $order_down_rev) === 0) {
				$ordering_field_rev .= $order_down_rev;
				$rev_field = substr($rev_field, strlen($order_down_rev));
			}
			else {
				$ordering_field_rev .= $default_ordering_rev;
			}
		}


		while (strpos($rev_field, $field_end) === 0) {
			$rev_field = substr($rev_field, 1);
		}

		$field = strrev($rev_field);
		$field_parts = explode($qualifier, $field, 2);
		$safe_field_parts = array();
		foreach ($field_parts as $key => $part) {
			$tmp_part = db_make_safe_field($part);

			if ($tmp_part === trim($part)) {
				$safe_field_parts[] = $tmp_part;
				continue;
			}
		}


		if (1 < count($safe_field_parts)) {
			$field = implode($qualifier, $safe_field_parts);
		}
		else {
			$field = array_shift($safe_field_parts);
		}


		if ($field) {
			$tokenizedFields[] = $field_begin . $field . $field_end . strrev($ordering_field_rev);
		}

		$field = strtok($field_separator);
		++$i;
	}

	return $tokenizedFields;
}

function db_build_quoted_field($key) {
	$field_quote = "`";
	$parts = explode(".", $key, 3);
	foreach ($parts as $k => $name) {
		$clean_name = db_make_safe_field($name);

		if ($clean_name !== $name) {
			exit("Unexpected input field parameter in database query.");
		}

		$parts[$k] = $field_quote . $clean_name . $field_quote;
	}

	return implode(".", $parts);
}

function db_escape_array($array) {
	$array = array_map("db_escape_string", $array);
	return $array;
}

function db_escape_numarray($array) {
	$array = array_map("intval", $array);
	return $array;
}

function db_build_in_array($array, $allow_empty = false) {
	$in = "";
	foreach ($array as $k => $v) {

		if (!trim($v) && !$allow_empty) {
			unset($array[$k]);
			continue;
		}


		if (is_numeric($v)) {
			$v;
			continue;
		}

		$array[$k] = "'" . db_escape_string($v) . "'";
	}

	return implode(",", $array);
}

function db_make_safe_field($field) {
	return db_escape_string(preg_replace("/[^a-z0-9_.,]/i", "", $field));
}

function db_build_update_array($fields, $arrayhandler = "serialize") {
	global $ra;

	$array = array();
	foreach ($fields as $key) {
		$array[$key] = $ra->get_req_var($key);

		if (is_array($array[$key])) {
			if ($arrayhandler == "serialize") {
				$array[$key] = serialize($array[$key]);
				continue;
			}


			if ($arrayhandler == "implode") {
				$array[$key] = implode(",", $array[$key]);
				continue;
			}

			continue;
		}
	}

	return $array;
}

$query_count = 0;

?>