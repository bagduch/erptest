<?php


ini_set("log_errors", 1);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
ini_set('memory_limit', '1024M');
ini_set("error_log", "templates_c/php_errors.log");
error_reporting(E_ALL);

function getValidLanguages($admin = "") {
    global $ra;

    $langs = $ra->getValidLanguages($admin);
    return $langs;
}

function htmlspecialchars_array($arr) {
    global $ra;

    return $ra->sanitize_input_vars($arr);
}

//error_reporting(0);
include dirname(__FILE__) . "/includes/classes/class.init.php";

if (!class_exists("RA_Init")) {
    echo "<div style=\"border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:100%;padding:10px;color:#cc0000;\"></div>";
    exit();
}

$ra = new RA_Init();
$test = $ra->init();

if ((defined("CLIENTAREA") && $CONFIG['MaintenanceMode']) && !$_SESSION['adminid']) {
    if ($CONFIG['MaintenanceModeURL']) {
        header("Location: " . $CONFIG['MaintenanceModeURL']);
        exit();
    }

    echo "<div style=\"border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:100%;padding:10px;color:#cc0000;\"><br>" . $CONFIG['MaintenanceModeMessage'] . "</div>";
    exit();
}


if ((defined("CLIENTAREA") && isset($_SESSION['uid'])) && !isset($_SESSION['adminid'])) {
    $twofa = new RA_2FA();
    $twofa->setClientID($_SESSION['uid']);

    if (($twofa->isForced() && !$twofa->isEnabled()) && $twofa->isActiveClients()) {
        if ($ra->get_filename() == "clientarea" && ($ra->get_req_var("action") == "security" || $ra->get_req_var("2fasetup"))) {

        } else {
            redir("action=security&2fasetup=1&enforce=1", "clientarea.php");
        }
    }
}


if (isset($_SESSION['currency']) && is_array($_SESSION['currency'])) {
    $_SESSION['currency'] = $_SESSION['currency']['id'];
}


if (!isset($_SESSION['uid']) && isset($_REQUEST['currency'])) {
    $result = select_query_i("tblcurrencies", "id", array("id" => (int) $_REQUEST['currency']));
    $data = mysqli_fetch_array($result);

    if ($data['id']) {
        $_SESSION['currency'] = $data['id'];
    }
}


if (defined("CLIENTAREA") && isset($_REQUEST['language'])) {
    $ra->set_client_language($_REQUEST['language']);
}

$ra->loadLanguage();

ob_start();
require ROOTDIR . "/includes/hookfunctions.php";
ob_end_clean();
?>
