<?php
/** RA - Version 0.1 **/

function mailin_config() {
	$configarray = array( "FriendlyName" => array( "Type" => "System", "Value" => "Mail In Payment" ), "instructions" => array( "FriendlyName" => "Bank Transfer Instructions", "Type" => "textarea", "Rows" => "5", "Value" => "Bank Name:
Payee Name:
Sort Code:
Account Number:", "Description" => "The instructions you want displaying to customers who choose this payment method - the invoice number will be shown underneath the text entered above" ) );
	return $configarray;
}


function mailin_link($params) {
	global $_LANG;

	$code = "<p>" . nl2br( $params['instructions'] ) . "<br />" . $_LANG['invoicerefnum'] . ": " . $params['invoiceid'] . "</p>";
	return $code;
}


if (!defined( "RA" )) {
	exit( "This file cannot be accessed directly" );
}

?>