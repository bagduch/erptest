<?php
if (!defined("RA"))
    die("This file cannot be accessed directly");

function tolls_config() {
    $configarray = array(
        "name" => "Tolls",
        "description" => "This module hooks into an ui configured tolls service. Does billing, provides usage. By activating this module you agree to terms and conditions as per Hosting Direct's terms and conditions (see web-site). You agree that you maintain back-ups and perform audits on your own data. You will not hold Hosting Direct liable for errors or omissions.",
        "version" => "1.0",
        "author" => "Unlimited Internet",
        "language" => "english",
        "fields" => array(
            "remote_dsn" => array("FriendlyName" => "Tolls Database DSN", "Type" => "text", "Size" => "60", "Default" => "mysql:host=localhost;port=3306;dbname=radius", "Description" => "e.g. mysql:host=localhost;port=3306;dbname=tolls"),
            "remote_user" => array("FriendlyName" => "Tolls Database Username", "Type" => "text", "Size" => "60", "Default" => "radius", "Description" => ""),
            "remote_pass" => array("FriendlyName" => "Tolls Database Password", "Type" => "password", "Size" => "60", "Default" => "", "Description" => ""),
            "option_email_cron_to" => array("FriendlyName" => "Email Cron To", "Type" => "textarea", "Cols" => "60", "Rows" => 5, "Description" => "New email on each line, send a report of the cron to these emails."),
            "api_url" => array("FriendlyName" => "API: Url", "Type" => "text", "Size" => "60", "Default" => "", "Description" => ""),
            "api_username" => array("FriendlyName" => "API: Username", "Type" => "text", "Size" => "30", "Default" => "", "Description" => "Requires you to set-up an API user with acccess and enable API usage"),
            "api_password" => array("FriendlyName" => "API: Password", "Type" => "password", "Size" => "30", "Default" => "", "Description" => ""),
        /*

          "option_realms"				=> array ("FriendlyName" => "Radius Realms", "Type" => "textarea", "Cols" => "60", "Rows"=>5, "Description" => "New realm on each line, only required if you are not entering realm names in with your usernames in RA."),
          "option_email_cron_to"		=> array ("FriendlyName" => "Email Cron To", "Type" => "textarea", "Cols" => "60", "Rows"=>5, "Description" => "New email on each line, send a report of the cron to these emails."),
          "option_enable_clientarea"	=> array ("FriendlyName" => "Enable Client Area", "Type" => "yesno", "Size" => "25", "Description" => "Enable"),
          "client_graph_area_width"		=> array ("FriendlyName" => "Client Area Graph Width", "Type" => "text", "Size" => "4", "Default"=>"640", "Description" => ""),
          "client_graph_area_height"		=> array ("FriendlyName" => "Client Area Graph Height", "Type" => "text", "Size" => "4", "Default"=>"480", "Description" => ""),

          "option2" => array ("FriendlyName" => "Option2", "Type" => "password", "Size" => "25", "Description" => "Password"),
          "option3" => array ("FriendlyName" => "Option3", "Type" => "yesno", "Size" => "25", "Description" => "Sample Check Box"),
          "option4" => array ("FriendlyName" => "Option4", "Type" => "textarea", "Size" => "25", "Description" => "Textarea"),
          "option5" => array ("FriendlyName" => "Option5", "Type" => "dropdown", "Options" => "1,2,3,4,5", "Description" => "Sample Dropdown"),
         */
    ));
    return $configarray;
}

function tolls_activate() {

    # Create Custom DB Table
//
//    $query = "CREATE TABLE IF NOT EXISTS `mod_tolls_billing` (
//      `client_id` int(11) NOT NULL,
//      `hosting_id` int(11) NOT NULL,
//      `date_billing_period` varchar(10) NOT NULL,
//      `date_from` date DEFAULT NULL,
//      `date_stop` date DEFAULT NULL,
//      `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//      `data_used` decimal(10,2) DEFAULT NULL,
//      `data_block` decimal(10,2) DEFAULT NULL,
//      `data_overage` decimal(10,5) DEFAULT NULL,
//      `invoice_id` int(11) DEFAULT NULL,
//      UNIQUE KEY `client_id_3` (`client_id`,`hosting_id`,`date_billing_period`),
//      KEY `client_id` (`client_id`),
//      KEY `product_id` (`hosting_id`),
//      KEY `client_id_2` (`client_id`,`hosting_id`),
//      KEY `date_from` (`date_from`)
//      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
//    $result = mysql_query($query);
//
//
//    # Return Result
//    return array('status' => 'success', 'description' => 'Done');
//    return array('status' => 'error', 'description' => 'Error?');
//    return array('status' => 'info', 'description' => 'Message');
}

function tolls_deactivate() {

    # Remove Custom DB Table
    #$query = "DROP TABLE `mod_addonexample`";
    #$result = mysql_query($query);
    # Return Result
    # return array('status'=>'success','description'=>'If successful, you can return a message to show the user here');
    #return array('status'=>'error','description'=>'If an error occurs you can return an error message for display here');
    return array('status' => 'info', 'description' => 'mod_ not deleted (has billing information)');
}

function tolls_upgrade($vars) {
    /*
      $version = $vars['version'];

      # Run SQL Updates for V1.0 to V1.1
      if ($version < 1.1) {
      $query = "ALTER `mod_addonexample` ADD `demo2` TEXT NOT NULL ";
      $result = mysql_query($query);
      }

      # Run SQL Updates for V1.1 to V1.2
      if ($version < 1.2) {
      $query = "ALTER `mod_addonexample` ADD `demo3` TEXT NOT NULL ";
      $result = mysql_query($query);
      }
     */
}

function tolls_safevars($vars) {
    if (isset($vars['remote_dsn'])) {
        unset($vars['remote_dsn']);
    }
    if (isset($vars['remote_user'])) {
        unset($vars['remote_user']);
    }
    if (isset($vars['remote_pass'])) {
        unset($vars['remote_pass']);
    }
    return $vars;
}

function tolls_output($vars) {


    define('IS_MODULE', '1');
    $vars = tolls_safevars($vars);
    $LANG = $vars['_lang'];
    $data['module'] = 'tolls';
    $data['modulelink'] = $vars['modulelink'];

    // Determine Controller
    if (isset($_GET['v'])) {
        $data['v'] = strtolower($_GET['v']);
    } else {
        $data['v'] = 'usage';
    }
    if ($data['v'] != "api") {

        // Load Controller
        if (isset($data['v']) && $data['v'] == 'cron' && file_exists(dirname(__FILE__) . '/cron.php')) {

            require(dirname(__FILE__) . '/cron.php');
        } else if (isset($data['v']) && file_exists(dirname(__FILE__) . '/controllers/' . strtolower($data['v']) . '.php')) {

            require(dirname(__FILE__) . '/controllers/' . strtolower($data['v']) . '.php');
        } else {
            header("Status: 404 Not Found");
            ?>404, page not found<?php
            return;
        }

        // Load View
        global $templates_compiledir, $customadminpath, $email;
//	require($_SERVER['DOCUMENT_ROOT'] . '/' . $customadminpath . '/lang/english.php');
//	$data['_ADMINLANG'] = $_ADMINLANG;
//	if(!defined('SMARTY_DIR')){define('SMARTY_DIR', dirname(str_replace("\\","/",getcwd())).'/includes/smarty/');}
//	require_once(SMARTY_DIR . 'Smarty.class.php');
//	if(!function_exists('logActivity')){function logActivity($error_msg){trigger_error("Smarty error: <pre>$error_msg</pre>");}}
        $_smarty = new Smarty();
        $_smarty->template_dir = $_SERVER['DOCUMENT_ROOT'];
        $_smarty->compile_dir = $templates_compiledir;
        if (isset($data)) {
            foreach ($data as $key => $row) {
                $_smarty->assign($key, $row);
            }
        }
        //echo "<pre>",print_r($_smarty,1),"</pre>";
        $_smarty->display(dirname(__FILE__) . '/templates/' . $data['v'] . '.tpl');
    } else {
        require(dirname(__FILE__) . '/controllers/api.php');
        exit;
    }
}

function tolls_sidebar($vars) {
    $vars = tolls_safevars($vars);
    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $option_realms = $vars['option_realms'];
    $LANG = $vars['_lang'];

    $data['module'] = 'tolls';

    $sidebar = '<span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" />&nbsp;HD Tolls</span>
<ul class="menu">
        <li><a href="addonmodules.php?module=' . $data['module'] . '">Home</a></li>
        <li><a href="addonmodules.php?module=' . $data['module'] . '&amp;v=usage" onclick="this.innerHTML=\'Please wait...\';">Client usage</a></li>
        <li><a href="addonmodules.php?module=' . $data['module'] . '&amp;v=pricing&amp;zone=local">Client pricing</a></li>
        <li><a href="addonmodules.php?module=' . $data['module'] . '&amp;v=usage_billing&amp;zone=local">Billing report</a></li>
    </ul>';

    return $sidebar;
}

function tolls_clientarea($vars) {
    $vars = tolls_safevars($vars);
    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $option_realms = $vars['option_realms'];
    $LANG = $vars['_lang'];

    # Client Area id off
    /* if(!isset($vars['option_enable_clientarea']) || $vars['option_enable_clientarea']!='on' || ){
      return array(
      'pagetitle' => 'Usage',
      'breadcrumb' => array('index.php?m=tolls'=>'SIP'),
      'templatefile' => 'templates/clientareadisabled',
      'requirelogin' => false, # or false
      'vars' => $data,
      );
      } */

    # Client Area

    if (file_exists('modules/addons/tolls/controllers/clientareatollsusage.php')) {
        include('modules/addons/tolls/controllers/clientareatollsusage.php');
    } else {
        echo 'Error ' . __FILE__ . ' ' . __LINE__;
    }

    //  echo "<pre>",  print_r($data,1),"</pre>";
    #echo '<!--' . print_r($vars['option_enable_frontend'], true) . '-->';   
    //    echo  basename(__FILE__, strrchr(__FILE__, "."))."/templates/";
    return array(
        'pagetitle' => 'My SIP usage',
        'breadcrumb' => array('index.php?m=tolls' => 'SIP'),
        'templatefile' => 'templates/clientareatollsusage',
        'requirelogin' => true, # or false
        'vars' => $data,
    );
}
