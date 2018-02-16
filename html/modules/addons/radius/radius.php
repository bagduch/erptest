<?php
if(!defined("RA"))
	die("This file cannot be accessed directly");

function radius_config() {
    return array(
	    "name" => "RADIUS",
	    "description" => "Broadband management",
	    "version" => "1.0",
	    "author" => "HD",
        "language" => "english",
        "fields" => array(
            "valid_radius_pids" => array(
                "FriendlyName" => "Valid Radius PIDs",
                "Type" => "text",
                "Size" => 128,
                "Description" => "Valid PIDs of products to be handled by this module"
            )
        )
    );
}

function radius_clientarea($vars) {
    require_once '/var/www/my/modules/addons/radius/clientarea.php'; 

//    echo getcwd();
//    var_dump($vars);


    $request = isset($_GET['page']) ? $_GET['page'] : 'index';

    if ($_SESSION['adminid'] > 0 || TRUE) { 
        if (FALSE) { //enable debugging 
            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('log_errors', TRUE);
            ini_set('display_startup_errors', TRUE);
        }
        $returnarray = array(
            'pagetitle' => "Broadband Usage Meter",
            'templatefile' => "templates/clientarea.tpl",
            'breadcrumb' => array($vars['modulelink']=>'Broadband Usage Meter'),
            'requirelogin' => true,
            'vars' => $smarty
        );
        if (isset($smarty['this_account']['username'])) {
            $returnarray['breadcrumb'][$vars['modulelink'].'&account='.$smarty['this_account']['id']] =  $smarty['this_account']['username'];
        }
        return $returnarray;
    }
	return array(
        'pagetitle' => 'Broadband Usage Unavailable',
	    'templatefile' => "templates/unavailable.tpl",
        'breadcrumb' => array($vars['modulelink'].'&page=unavailable'=>'Unavailable'),
        'requirelogin' => true
        );
}

function radius_output($vars) {
//    memprof_enable();

    if (FALSE) {
            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('log_errors', TRUE);
            ini_set('display_startup_errors', TRUE);
    }
	global $templates_compiledir;
	
    require 'radius.class.php';
	
//	if($_POST['initiate_cronjob'] == "yes") {
//		require 'radius_cron.php';
//		return;
//	}
	
	$request = isset($_GET['page']) ? $_GET['page'] : 'index';
	
	require_once "controllers/".$request.".controller.php";
	
	$_smarty = new Smarty();
	$_smarty->compile_dir = $templates_compiledir;
	
	if(isset($smarty)) {
		foreach($smarty as $key => $value)
			$_smarty->assign($key, $value);
	}
	
	$_smarty->display(sprintf("%s/views/%s.view.tpl", dirname(__FILE__), $request));
}

?>
