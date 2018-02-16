<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require(dirname(dirname(__FILE__)) . '/hdtolls/models/hdtolls.php');
$hdtolls = new hdtolls();
$result = $hdtolls->db->query("INSERT INTO `mod_hdtolls_options`
	VALUES
	(null,
	'" . $hdtolls->db->real_escape_string($_POST['hostingid']) . "',
	'" . $hdtolls->db->real_escape_string($_POST['zone']) . "',
	'default',
	" . ((@$_POST['type'] == "talktime" && $_POST['value'] !== '') ? "'" . $hdtolls->db->real_escape_string($_POST['value']) . "'" : 'null') . ",
	" . ((@$_POST['type'] == "rate" && $_POST['value'] !== '') ? "'" . $hdtolls->db->real_escape_string($_POST['value']) . "'": 'null') . ")
	
	ON DUPLICATE KEY UPDATE
	
	`" . ((@$_POST['type'] == "talktime") ? "freemins" : $hdtolls->db->real_escape_string($_POST['type'])) . "` = " . ($_POST['value'] !== '' ? "'" . $hdtolls->db->real_escape_string($_POST['value']) . "'" : 'null') . "
");

if($result) {
	$result2 = $hdtolls->db->query("
	SELECT `" . (@$_POST['type'] == "talktime" ? "freemins" : $_POST['type']) . "`
	
	FROM `mod_hdtolls_options`
	
	WHERE `hostingid` = '" . $hdtolls->db->real_escape_string($_POST['hostingid']) . "'
		AND `zone` = '" . $hdtolls->db->real_escape_string($_POST['zone']) . "'
	");
	
	if($result2->num_rows) {
		$row = $result2->fetch_row();
		echo $row[0];
	}
	else {
		echo "Updated, but cannot get new value.";
	}
}
else {
	echo $hdtolls->db->error;
}

?>