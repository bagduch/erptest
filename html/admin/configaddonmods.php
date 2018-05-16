<?php

/**
 *
 * @ RA
 * 
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Configure Addon Modules");
$aInt->title = $aInt->lang("utilities", "addonmodules");
$aInt->sidebar = "config";
$aInt->icon = "admins";
$aInt->helplink = "Addon Modules Management";
$aInt->requiredFiles(array("modulefunctions"));
$menuselect = "$('#menu').multilevelpushmenu('expand','System');";

if (!isset($CONFIG['ActiveAddonModules'])) {
    insert_query("ra_config", array("setting" => "ActiveAddonModules", "value" => ""));
}


if (!isset($CONFIG['AddonModulesPerms'])) {
    insert_query("ra_config", array("setting" => "AddonModulesPerms", "value" => ""));
}


if (!isset($CONFIG['AddonModulesHooks'])) {
    insert_query("ra_config", array("setting" => "AddonModulesHooks", "value" => ""));
}

$activemodules = explode(",", $CONFIG['ActiveAddonModules']);
$addon_modules = $addonmodulehooks = array();

if (is_dir(ROOTDIR . "/modules/addons/")) {
    $dh = opendir(ROOTDIR . "/modules/addons/");
    while (false !== $file = readdir($dh)) {
        $modfilename = ROOTDIR . ("/modules/addons/" . $file . "/" . $file . ".php");
        if (is_file($modfilename)) {
            require $modfilename;
            $configarray = call_user_func($file . "_config");
            $addon_modules[$file] = $configarray;
        }
    }
}


if (is_dir(ROOTDIR . "/modules/admin/")) {
    $dh = opendir(ROOTDIR . "/modules/admin/");

    while (false !== $file = readdir($dh)) {
        if (is_file(ROOTDIR . ("/modules/admin/" . $file . "/" . $file . ".php")) && $file != "index.php") {
            $friendlytitle = str_replace("_", " ", $file);
            $friendlytitle = titleCase($friendlytitle);
            $addon_modules[$file] = array("name" => $friendlytitle, "version" => $aInt->lang("addonmodules", "legacy"), "author" => "-");
        }
    }

    closedir($dh);
}


ksort($addon_modules);
$action = $ra->get_req_var("action");

if ($action == "save") {
    check_token("RA.admin.default");
    $exvars = array();
    $result = select_query_i("ra_modules", "", "");

    while ($data = mysqli_fetch_array($result)) {
        $exvars[$data['module']][$data['setting']] = $data['value'];
    }

    delete_query("ra_modules", array("setting" => "access"));
    foreach ($access as $module => $roleids) {
        $allowedroleids = "";
        foreach ($roleids as $roleid => $v) {
            $allowedroleids[] = $roleid;
        }

        insert_query("ra_modules", array("module" => $module, "setting" => "access", "value" => implode(",", $allowedroleids)));
    }

    foreach ($addon_modules as $module => $vals) {

        if (in_array($module, $activemodules)) {
            foreach ($vals['fields'] as $key => $values) {

                if (isset($exvars[$module][$key])) {
                    update_query("ra_modules", array("value" => trim($_POST['fields'][$module][$key])), array("module" => $module, "setting" => $key));
                    continue;
                }

                insert_query("ra_modules", array("module" => $module, "setting" => $key, "value" => trim($_POST['fields'][$module][$key])));
            }

            continue;
        }
    }

    $module = "";
    foreach ($_POST as $k => $v) {

        if (substr($k, 0, 6) == "msave_") {
            $module = substr($k, 6);
            continue;
        }
    }

    redir("savedref=true#" . $module);
}
if ($action == "activate") {
    check_token("RA.admin.default");

    if (!array_key_exists($module, $addon_modules)) {
        $aInt->gracefulExit("Invalid Module Name. Please Try Again.");
    }


    if (function_exists($module . "_activate")) {
        $response = call_user_func($module . "_activate");
    }

    wSetCookie("AddonModActivate", $response);

    if (!$response || (is_array($response) && ($response['status'] == "success" || $response['status'] == "info"))) {
        $activemodules[] = $module;
        sort($activemodules);
        update_query("ra_config", array("value" => implode(",", $activemodules)), array("setting" => "ActiveAddonModules"));

        if ($addon_modules[$module]['version'] != $aInt->lang("addonmodules", "nooutput")) {
            insert_query("ra_modules", array("module" => $module, "setting" => "version", "value" => $addon_modules[$module]['version']));
        }
    }

    redir("activated=true");
    exit();
}
if ($action == "deactivate") {
    check_token("RA.admin.default");

    if (!array_key_exists($module, $addon_modules)) {
        $aInt->gracefulExit("Invalid Module Name. Please Try Again.");
    }


    if (function_exists($module . "_deactivate")) {
        $response = call_user_func($module . "_deactivate");
    }

    wSetCookie("AddonModActivate", $response);

    if (!$response || (is_array($response) && ($response['status'] == "success" || $response['status'] == "info"))) {
        delete_query("ra_modules", array("module" => $module));
        foreach ($activemodules as $k => $mod) {

            if ($mod == $module) {
                unset($activemodules[$k]);
                continue;
            }
        }

        sort($activemodules);
        update_query("ra_config", array("value" => implode(",", $activemodules)), array("setting" => "ActiveAddonModules"));
    }

    redir("deactivated=true");
    exit();
}

if ($action == "") {
    if ($ra->get_req_var("saved")) {
        infoBox($aInt->lang("addonmodules", "changesuccess"), $aInt->lang("addonmodules", "changesuccessinfo"));
    }
    if ($ra->get_req_var("activated")) {
        $response = wGetCookie("AddonModActivate", 1);
        $desc = $status = "";
        if (is_array($response)) {
            if ($response['description']) {
                $desc = $response['description'];
            }
            if (in_array($response['status'], array("info", "success", "error"))) {
                $status = $response['status'];
            }
        }
        $title = $aInt->lang("addonmodules", "moduleactivated");
        if (!$desc) {
            $desc = $aInt->lang("addonmodules", "moduleactivatedinfo");
        }
        if (!$status) {
            $status = "success";
        }
        infoBox($title, $desc, $status);
    }


    if ($ra->get_req_var("deactivated")) {
        $response = wGetCookie("AddonModActivate", 1);
        $desc = $status = "";

        if (is_array($response)) {
            if ($response['description']) {
                $desc = $response['description'];
            }


            if (in_array($response['status'], array("info", "success", "error"))) {
                $status = $response['status'];
            }
        }

        $title = $aInt->lang("addonmodules", "moduledeactivated");

        if (!$status) {
            $status = "success";
        }

        infoBox($title, $desc, $status);
    }

//    echo $infobox;
    $aInt->deleteJSConfirm("deactivateMod", "addonmodules", "deactivatesure", $_SERVER['PHP_SELF'] . "?action=deactivate&module=");
    $jscode = "function showConfig(module) {
    $(\"#\"+module+\"config\").fadeToggle();
}";
//    echo "<p>" . $aInt->lang("addonmodules", "description") . "</p>
//
//<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
//<input type=\"hidden\" name=\"action\" value=\"save\" />
//
//<div class=\"tablebg\">
//<table class=\"datatable\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
//<tr><th>" . $aInt->lang("addonmodules", "module") . "</th><th width=\"100\">" . $aInt->lang("global", "version") . "</th><th width=\"100\">" . $aInt->lang("addonmodules", "author") . "</th><th width=\"350\"></th></tr>
//";
    $modulevars = $addonmodulesperms = array();
    $result = select_query_i("ra_modules", "", "");

    while ($data = mysqli_fetch_array($result)) {
        $modulevars[$data['module']][$data['setting']] = $data['value'];
    }

    $moduletable = array();
    $adminrolelist = array();
    foreach ($addon_modules as $module => $vals) {


        $bgcolor = (in_array($module, $activemodules) ? "FDF4E8" : "fff");
        if (array_key_exists("logo", $vals)) {
            $logo = $vals['logo'];
        }


        if (array_key_exists("premium", $vals)) {
            $premium = " <span class=\"label closed\">Premium</span>";
        }


        if ($vals['description']) {
            $des = $vals['description'];
        }

        if (!in_array($module, $activemodules)) {
            //    $active = "<input type=\"button\" value=\"" . $aInt->lang("addonmodules", "activate") . "\" onclick=\"window.location='" . $_SERVER['PHP_SELF'] . "?action=activate&module=" . $module . generate_token("link") . "'\" class=\"btn-success\" /> ";
            $active = true;
        } else {
            //  $noactive = "<input type=\"button\" value=\"" . $aInt->lang("addonmodules", "activate") . "\" disabled=\"disabled\" class=\"btn disabled\" /> ";
            $active = false;
        }


        if (in_array($module, $activemodules)) {
            $deactive = true;
        } else {
            $deactive = false;
        }

        if (file_exists(ROOTDIR . ("/modules/addons/" . $module . "/hooks.php"))) {
            $addonmodulehooks[] = $module;
        }
        if ($vals['version'] != $aInt->lang("addonmodules", "legacy") && $modulevars[$module]['version'] != $vals['version']) {
            if (function_exists($module . "_upgrade")) {
                call_user_func($module . "_upgrade", $modulevars);
            }
            update_query("ra_modules", array("value" => $vals['version']), array("module" => $module, "setting" => "version"));
        }

        foreach ($vals['fields'] as $key => $values) {
            $values['Value'] = $modulevars[$module][$key];
            $values['Name'] = "fields[" . $module . "][" . $key . "]";
            $configvalues[$module][] = array(
                'name' => "fields[" . $module . "][" . $key . "]",
                'Value' => $modulevars[$module][$key],
                'FriendlyName' => $values['FriendlyName'],
                'fieldvalue' => moduleConfigFieldOutput($values),
            );
        }
        $allowedroles = explode(",", $modulevars[$module]['access']);
        $result = select_query_i("ra_adminroles", "", "", "name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $checked = "";

            if (in_array($data['id'], $allowedroles)) {
                $addonmodulesperms[$data['id']][$module] = $vals['name'];
                $checked = " checked";
            }
//            $adminrolelist .= "<label><input type=\"checkbox\" name=\"access[" . $module . "][" . $data['id'] . "]\" value=\"1\"" . $checked . " /> " . $data['name'] . "</label> ";

            $adminrolelist[$module][$data['id']] = $data;
            $adminrolelist[$module][$data['id']]['check'] = $checked;
        }

        $moduletable[$module] = array(
            'bgcolor' => $bgcolor,
            'des' => $des,
            'active' => $active,
            'deactive' => $deactive,
            'showconfig' => in_array($module, $activemodules) ? "" : " disabled",
            'name' => $vals['name'],
            'version' => $vals['version'],
            'author' => $vals['author'],
            'module' => $module,
            'log' => $logo,
        );
    }
//   echo "<pre>", print_r($configvalues, 1), "</pre>";
    $aInt->assign("adminrolelist", $adminrolelist);
    $aInt->assign("configvalues", $configvalues);
    $aInt->assign("token", generate_token("link"));
    $aInt->assign("moduletable", $moduletable);

    update_query("ra_config", array("value" => implode(",", $addonmodulehooks)), array("setting" => "AddonModulesHooks"));
    update_query("ra_config", array("value" => serialize($addonmodulesperms)), array("setting" => "AddonModulesPerms"));
}


if ($ra->get_req_var("savedref")) {
    redir("saved=true");
}


$aInt->assign('infobox', $infobox);
$aInt->template = "configaddonmods";
$aInt->content = $content;
$aInt->jscode = $jscode;
$aInt->jquerycode .= $menuselect;
$aInt->display();
?>