<?php

/**
*
 * @ RA
 *
 **/

define("ADMINAREA", true);
require "../init.php";
$adminfolder = $ra->get_admin_folder_name();
$adminpermsarray = getAdminPermsArray();

if (!$adminpermsarray[$permid]) {
	exit();
}

$result = select_query_i("tbladmins", "language", array("id" => $_SESSION['adminid']));
$data = mysqli_fetch_array($result);
$language = $data['language'];
$_ADMINLANG = array();

if ($_SESSION['adminlang']) {
	$language = $_SESSION['adminlang'];
}


if (!isValidforPath($language)) {
	exit("Invalid Admin Language Name");
}

$langfilepath = ROOTDIR . "/" . $adminfolder . "/lang/" . $language . ".php";

if (file_exists($langfilepath)) {
	include $langfilepath;
}
else {
	include ROOTDIR . "/" . $adminfolder . "/lang/english.php";
}

logActivity("Access Denied to " . $adminpermsarray[$permid]);
echo "
<html>
<head>
<title>RA- ";
echo $_ADMINLANG['permissions']['accessdenied'];
echo "</title>
<link href=\"templates/original/style.css\" rel=\"stylesheet\" type=\"text/css\" />
</head>
<body>
<br /><br /><br /><br /><br />
<p align=\"center\" style=\"font-size:24px;\">";
echo $_ADMINLANG['permissions']['accessdenied'];
echo "</p>
<p align=\"center\" style=\"font-size:18px;color:#FF0000;\">";
echo $_ADMINLANG['permissions']['nopermission'];
echo "</p>
<br /><br />
<p align=\"center\" style=\"font-size:18px;\">";
echo $_ADMINLANG['permissions']['action'];
echo ": ";
echo $adminpermsarray[$permid];
echo "</p>
<br /><br /><br />
<p align=\"center\"><input type=\"button\" value=\" &laquo; ";
echo $_ADMINLANG['global']['goback'];
echo " \" onClick=\"javascript:history.go(-1)\"></p>
<br /><br />
</body>
</html>";
?>