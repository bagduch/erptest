<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$password = decrypt($_POST['password2']);
$apiresults = array("result" => "success", "password" => $password);
?>