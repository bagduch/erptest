<?php
/** RA - Version 0.1 **/

define("CLIENTAREA", true);
require "init.php";

if (isset($_SESSION['uid'])) {
	require "/vendor/smarty/smarty/libs/Smarty.class.php";
	$smarty = new Smarty();
	$smarty->template_dir = "templates/" . $ra->get_sys_tpl_name() . "/";
	$smarty->compile_dir = $templates_compiledir;
	$smarty->assign("template", $ra->get_sys_tpl_name());
	$smarty->assign("LANG", $_LANG);
	$smarty->assign("logo", $CONFIG['LogoURL']);
	$smarty->assign("companyname", $CONFIG['CompanyName']);
	$id = $ra->get_req_var("id");
	$result = select_query_i("ra_user_mail", "", array("id" => $id, "userid" => $_SESSION['uid']));
	$data = mysqli_fetch_array($result);
	$date = $data['date'];
	$subject = $data['subject'];
	$message = $data['message'];
	$date = fromMySQLDate($date, "time");
	$smarty->assign("date", $date);
	$smarty->assign("subject", $subject);
	$smarty->assign("message", $message);
	$template_output = $smarty->fetch("viewemail.tpl");
	echo $template_output;
	return 1;
}

redir("", "index.php");
?>
