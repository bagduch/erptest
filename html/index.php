<?php

/** RA - Version 0.1 **/
define("CLIENTAREA", true);

require "init.php";

$pagetitle = $_LANG['globalsystemname'];
$breadcrumbnav = "<a href=\"index.php\">" . $_LANG['globalsystemname'] . "</a>";
$templatefile = "homepage";
$pageicon = "";


initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

if ($m = $ra->get_req_var("m")) {
    $module = preg_replace("/[^a-zA-Z0-9._]/", "", $m);
    $modulepath = ROOTDIR . "/modules/addons/" . $module . "/" . $module . ".php";

    if (!file_exists($modulepath)) {
        redir();
    }

    require $modulepath;

    if (!function_exists($module . "_clientarea")) {
        redir();
    }

    $configarray = call_user_func($module . "_config");

    if (!isValidforPath($module)) {
        exit("Invalid Addon Module Name");
    }

    $modulevars = array();
    $result = select_query_i("ra_modules", "", array("module" => $module));

    while ($data = mysqli_fetch_array($result)) {
        $modulevars[$data['setting']] = $data['value'];
    }


    if (!count($modulevars)) {
        redir();
    }

    $modulevars['modulelink'] = "index.php?m=" . $module;
    $_ADDONLANG = array();
    $calanguage = $ra->get_client_language();

    if (!isValidforPath($calanguage)) {
        exit("Invalid Client Area Language Name");
    }

    $addonlangfile = ROOTDIR . ("/modules/addons/" . $module . "/lang/" . $calanguage . ".php");

    if (file_exists($addonlangfile)) {
        require $addonlangfile;
    } else {
        if ($configarray['language']) {
            if (!isValidforPath($configarray['language'])) {
                exit("Invalid Addon Module Default Language Name");
            }

            $addonlangfile = ROOTDIR . ("/modules/addons/" . $module . "/lang/") . $configarray['language'] . ".php";

            if (file_exists($addonlangfile)) {
                require $addonlangfile;
            }
        }
    }


    if (count($_ADDONLANG)) {
        $modulevars['_lang'] = $_ADDONLANG;
    }

    $results = call_user_func($module . "_clientarea", $modulevars);

    if (!is_array($results)) {
        redir();
    }


    if (!isValidforPath($module)) {
        exit("Invalid Addon Module Name");
    }

    $templatefile = "/modules/addons/" . $module . "/" . $results['templatefile'] . ".tpl";
    $pagetitle = $results['pagetitle'];
    $smartyvalues['pagetitle'] = $pagetitle;

    if (is_array($results['breadcrumb'])) {
        foreach ($results['breadcrumb'] as $k => $v) {
            $breadcrumbnav .= " > <a href=\"" . $k . "\">" . $v . "</a>";
        }
    } else {
        $breadcrumbnav .= $results['breadcrumb'];
    }

    $smartyvalues['breadcrumbnav'] = $breadcrumbnav;

    if (is_array($results['vars'])) {
        foreach ($results['vars'] as $k => $v) {
            $smartyvalues[$k] = $v;
        }
    }


    if ($results['requirelogin'] && !$_SESSION['uid']) {
        require "login.php";
    }

    outputClientArea($templatefile);
    exit();
}


if ($ra->get_config("DefaultToClientArea")) {
    redir("", "clientarea.php");
}

$announcements = array();
$result = select_query_i("tblannouncements", "", array("published" => "on"), "date", "DESC", "0,3");

while ($data = mysqli_fetch_array($result)) {
    $id = $data['id'];
    $date = $data['date'];
    $title = $data['title'];
    $announcement = $data['announcement'];
    $result2 = select_query_i("tblannouncements", "", array("parentid" => $id, "language" => $_SESSION['Language']));
    $data = mysqli_fetch_array($result2);

    if ($data['title']) {
        $title = $data['title'];
    }


    if ($data['announcement']) {
        $announcement = $data['announcement'];
    }

    $date = fromMySQLDate($date);
    $announcements[] = array("id" => $id, "date" => $date, "title" => $title, "urlfriendlytitle" => getModRewriteFriendlyString($title), "text" => $announcement);
}

$smartyvalues['announcements'] = $announcements;
$smartyvalues['seofriendlyurls'] = $CONFIG['SEOFriendlyUrls'];

if ($CONFIG['AllowRegister']) {
    $smartyvalues['registerdomainenabled'] = true;
}


if ($CONFIG['AllowTransfer']) {
    $smartyvalues['transferdomainenabled'] = true;
}

if ($CONFIG['AllowOwnDomain']) {
    $smartyvalues['owndomainenabled'] = true;
}
$captcha = clientAreaInitCaptcha();

if (!empty(RA_Session::get("uid"))) {
    $client = new RA_Client(RA_Session::get("uid"));
    $exdetails = $client->getDetails();
    $smartyvalues['name'] = $ra->get_req_var_if($e, "firstname", $exdetails) . " " . $ra->get_req_var_if($e, "lastname", $exdetails);
} else {
    $smartyvalues['name'] = "";
}


$smartyvalues['captcha'] = $smartyvalues['capatacha'] = $captcha;
$smartyvalues['recaptchahtml'] = $smartyvalues['recapatchahtml'] = clientAreaReCaptchaHTML();

outputClientArea($templatefile);
?>
