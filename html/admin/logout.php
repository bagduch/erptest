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

define("ADMINAREA", true);
require "../init.php";
$auth = new RA_Auth();

if ($auth->logout()) {
 	redir("logout=1", "login.php");
}

// redir("", "login.php");
?>
