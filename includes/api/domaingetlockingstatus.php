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

if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("RegGetRegistrarLock")) {
	require ROOTDIR . "/includes/registrarfunctions.php";
}

$result = select_query_i("tbldomains", "id,domain,registrar,registrationperiod", array("id" => $domainid));
$data = mysqli_fetch_array($result);
$domainid = $data[0];

if (!$domainid) {
	$apiresults = array("result" => "error", "message" => "Domain ID Not Found");
	return false;
}

$domain = $data['domain'];
$registrar = $data['registrar'];
$regperiod = $data['registrationperiod'];
$domainparts = explode(".", $domain, 2);
$params = array();
$params['domainid'] = $domainid;
$params['sld'] = $domainparts[0];
$params['tld'] = $domainparts[1];
$params['regperiod'] = $regperiod;
$params['registrar'] = $registrar;
$params['lockenabled'] = $lockenabled;
$lockstatus = RegGetRegistrarLock($params);

if (!$lockstatus) {
	$lockstatus = "Unknown";
}

$apiresults = array("result" => "success", "lockstatus" => $lockstatus);
?>