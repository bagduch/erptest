<?php
/**
 * Lara Theme Settings Hook
 *
 * Setting module for the Lara admin template.
 * Please refer to the full documentation @ http://www.whmcsadmintheme.com for more details.
 *
 * @package    WHMCS
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @copyright  Copyright (c) WHMCSAdminTheme 2016
 * @link       http://www.whmcsadmintheme.com
 */

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

function lara_settings_update_SmartyVars($vars){
		$return = array();
		if ($vars['template'] == "lara"){
			## General Settings
			$table = "mod_laraSettings";
			$fields = "name,value";
			$where = array();
			$result = select_query($table,$fields,$where);
			$settingsArray = array();
			while ($data = mysql_fetch_array($result)) {
				$settingsArray[$data['name']] = $data['value'];
			}
			$return['lara_general_settings'] = $settingsArray;
			
			## User Settings
			$cAdminID = $_SESSION['adminid'];
			$table = "mod_laraUserSettings";
			$fields = "admin_id,name,value";
			$where = array("admin_id"=>$cAdminID);
			$result = select_query($table,$fields,$where);
			while ($data = mysql_fetch_array($result)) {
				$ckey = $data['name'];
				$vkey = $data['value'];
				$return['lara_'.$ckey]= $vkey;
			}
			$adminEmail = get_query_vals("tbladmins", "email", array("id" => $_SESSION['adminid']));
			if (!empty($adminEmail['email'])){
				$return['lara_adminemail']= $adminEmail['email'];
				$return['lara_adminemail_md5']= md5(strtolower($adminEmail['email']));
			}
		}
		return $return;
}

add_hook("AdminAreaPage",1,"lara_settings_update_SmartyVars");

function widget_lr_google_analytics_permissions($vars) {
	if (($vars['filename'] === "configadminroles") && (isset($_GET["action"])) && ($_GET["action"] === "edit" ) ){
		$cTemplate ="";
		if ($vars['template'] === "lara"){ $cTemplate = "lara"; }
			$head_return = '';
			$head_return = '<script type="text/javascript">
			$(document).ready(function(){
				var cTemplate ="'.$cTemplate.'";
				function fillWidgetsTable(widgets, wtable){
					var maxColumns = Math.ceil(widgets.length / 3);
					var i = 1;
					var currentColumn = 1;
					$.each(widgets, function( x, widget ) {
						if ($(widget).children("input").val() === "lrgawidget"){
							$("#lr_widget_table_main").append(widget[\'outerHTML\']+"</br>");
							return true;
						}else{
							$("#"+wtable+"_"+currentColumn).append(widget[\'outerHTML\']+"</br>");
						}
						if (maxColumns <= i) {
							currentColumn++;
							i = 1;
							return true;
						}
						i++;
					});					
				}
                var widgetsPlaceHolder = $("form[name=\'frmperms\'] .fieldlabel:eq(2)").next("td.fieldarea");
				var afterWidgetsPlaceHolder = $("form[name=\'frmperms\'] tr:eq(3)");
                 
				var lr = $("form[name=\'frmperms\'] label.checkbox-inline [value^=lrgawidget]").parents("label").get().reverse(); 
				 $("form[name=\'frmperms\'] label.checkbox-inline [value^=lrgawidget]").parents("label").detach();
				var wi = $("form[name=\'frmperms\'] label.checkbox-inline [name^=widget]").parents("label").get().reverse(); 

                widgetsPlaceHolder.empty(); 

				widgetsPlaceHolder.append("<table width=\"100%\" id=\"pre_widget_tables\"><tbody><tr><td width=\"33%\" valign=\"top\" id=\"pre_widget_table_1\"></td><td width=\"33%\" valign=\"top\" id=\"pre_widget_table_2\"></td><td width=\"33%\" valign=\"top\" id=\"pre_widget_table_3\"></td></tr></tbody></table>");
				fillWidgetsTable(wi, "pre_widget_table");
				
				if (cTemplate === "lara"){
					afterWidgetsPlaceHolder.after("<tr><td class=\"fieldlabel\">Lara Widgets</td><td class=\"fieldarea\"><table width=\"100%\" id=\"lr_widget_table\"><tbody><tr><td colspan=\"3\" id=\"lr_widget_table_main\" style=\"background-color: #367fa9 ; color: #FFFFFF;\"></td></tr><tr><td width=\"33%\" valign=\"top\" id=\"lr_widget_table_1\"></td><td width=\"33%\" valign=\"top\" id=\"lr_widget_table_2\"></td><td width=\"33%\" valign=\"top\" id=\"lr_widget_table_3\"></td></tr></tbody></table></td></tr>");	
					fillWidgetsTable(lr, "lr_widget_table");
					
					if(!$("#lr_widget_table [value=\'lrgawidget\']").is(":checked")) {
						$("#lr_widget_table input").prop("checked", false);
						$("#lr_widget_table input").prop("disabled", true);
						$("#lr_widget_table [value=\'lrgawidget\']").prop("disabled", false);						
					}
					
					$("#lr_widget_table [value=\'lrgawidget\']").on(\'click\', function (e) {
						if($(this).is(":checked")) { 
						   $("#lr_widget_table input").prop("disabled", false);
						   $("#lr_widget_table input").prop("checked", true);
						   $("#lr_widget_table input[value=\'lrgawidget_perm_admin\']").prop("checked", false);
						}else{
						   $("#lr_widget_table input").prop("checked", false);
						   $("#lr_widget_table input").prop("disabled", true);
						   $(this).prop("disabled", false);
						}
					});	
				}
            });
			
			</script>';
			return $head_return;
	}

	if (($vars['filename'] === "index") && ($vars['sidebar'] === "home") ){
		if ($vars['template'] != "lara"){ 
			$head_return = '';
			$head_return = '<script type="text/javascript">
			$(document).ready(function(){
				$(".homewidget[id^=lrgawidget]").remove();
            });
			</script>';
			return $head_return;
		}
	}	
}

add_hook("AdminAreaHeadOutput",1,"widget_lr_google_analytics_permissions"); 

function widget_lrgawidget($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'Google Analytics', 'content' => $content );
}
 
function widget_lrgawidget_perm_admin($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'Administrator [Change Settings]', 'content' => $content );
}

function widget_lrgawidget_perm_sessions($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Sessions', 'content' => $content );
}

function widget_lrgawidget_perm_countries($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Countries', 'content' => $content );
}

function widget_lrgawidget_perm_browsers($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Browsers', 'content' => $content );
}

function widget_lrgawidget_perm_languages($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Languages', 'content' => $content );
}

function widget_lrgawidget_perm_os($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Operating Systems', 'content' => $content );
}

function widget_lrgawidget_perm_screenres($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Screen Resolutions', 'content' => $content );
}

function widget_lrgawidget_perm_sources($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Sources', 'content' => $content );
}

function widget_lrgawidget_perm_keywords($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Keywords', 'content' => $content );
}

function widget_lrgawidget_perm_pages($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'View Pages', 'content' => $content );
}

function widget_lrgawidget_perm_daterange($vars) {
    $content = '<p>Place Holder</p>';
    return array( 'title' => 'Change Date Range', 'content' => $content );
}

add_hook("AdminHomeWidgets",1,"widget_lrgawidget"); 
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_admin"); 
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_sessions");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_countries");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_browsers");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_languages");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_os");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_screenres");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_sources");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_keywords");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_pages");
add_hook("AdminHomeWidgets",1,"widget_lrgawidget_perm_daterange"); 
