<?php
/**
 *
 * @ RA FULL DECODED & NULLED
 *
 * 
 * 
 * 
 * 
 *
 * */

function offlinecc_config() {
	$configarray = array( "FriendlyName" => array( "Type" => "System", "Value" => "Offline Credit Card" ), "RemoteStorage" => true );
	return $configarray;
}


function offlinecc_capture($params) {
	return false;
}


if (!defined( "RA" )) {
	exit( "This file cannot be accessed directly" );
}

?>