<?php
if(!defined("RA")){die("This file cannot be accessed directly");}
ini_set('display_errors', 1);
error_reporting(E_ALL);;


if(isset($_GET['i'])){
	$sql = "SELECT * FROM mod_hdtolls_log_index WHERE date_index='" . $_GET['i'] . "'";
	$result = mysql_query($sql);
	$data['row'] = mysql_fetch_assoc($result, MYSQL_ASSOC);
	if(isset($data['row']['errors'])){$data['row']['errors'] = implode('<br />', json_decode($data['row']['errors'], true));}

	if(isset($data['row']['lastlog'])){

		$data['row']['rawlog'] = $data['row']['lastlog'];

		$log = preg_split('/[\r\n]+/is', $data['row']['lastlog'], 0, PREG_SPLIT_NO_EMPTY);
		$data['row']['log'] = array();
		foreach($log as $key=>$row){if(trim($row)!=''){
			$data['row']['log'][] = str_getcsv($row);
		}}
	}
}
