<?php

function load_admin_home_widgets() {
    global $aInt;
    global $hooks;
    global $allowedwidgets;
    global $jquerycode;
    global $jscode;

    $hookjquerycode = "";
    $hook_name = "AdminHomeWidgets";
    $allowedwidgets = explode(",", $allowedwidgets);
    $args = array("adminid" => $_SESSION['adminid'], "loading" => "<img src=\"images/loading.gif\" align=\"absmiddle\" /> " . $aInt->lang("global", "loading"));

    if (!array_key_exists($hook_name, $hooks)) {
        return array();
    }

    reset($hooks[$hook_name]);
    $results = array();

    while (list($key, $hook) = each($hooks[$hook_name])) {
        $widgetname = substr($hook['hook_function'], 7);


        if (in_array($widgetname, $allowedwidgets) && function_exists($hook['hook_function'])) {
            $res = call_user_func($hook['hook_function'], $args);

            if (is_array($res)) {
                if (array_key_exists("jquerycode", $res)) {
                    $hookjquerycode .= $res['jquerycode'] . "\r\n";
                }


                if (array_key_exists("jscode", $res)) {
                    $jscode .= $res['jscode'] . "\r\n";
                }

                if (strpos($widgetname, "left") !== false) {
                    $results['left'][] = array_merge(array("name" => $widgetname), $res);
                } else {
                    $results['right'][] = array_merge(array("name" => $widgetname), $res);
                }
            }
        }
    }

    $jquerycode .= "setTimeout(function(){
        " . $hookjquerycode . "
    }, 4000);";
    return $results;
}

if (!function_exists("curl_init")) {
    echo "<div style=\"border: 1px dashed #cc0000;font-family:Tahoma;background-color:#FBEEEB;width:100%;padding:10px;color:#cc0000;\"><strong>Critical Error</strong><br>CURL is not installed or is disabled on your server and it is required for ra to run</div>";
    exit();
}
define("ADMINAREA", true);
require "../init.php";
if (!checkPermission("Main Homepage", true) && checkPermission("Support Center Overview", true)) {
    redir("", "supportcenter.php");
}

$aInt = new RA_Admin("Main Homepage", false);
$aInt->title = $aInt->lang("global", "hometitle");
$aInt->sidebar = "home";
$aInt->icon = "home";
$aInt->requiredFiles(array("clientfunctions", "invoicefunctions", "gatewayfunctions", "ccfunctions", "processinvoices", "reportfunctions"));
$aInt->template = "homepage";
$chart = new RAChart();
$action = $ra->get_req_var("action");
//
//if ($ra->get_req_var("createinvoices") || $ra->get_req_var("generateinvoices")) {
//	check_token("RA.admin.default");
//    
//	checkPermission("Generate Due Invoices");
//	createInvoices("", $noemails);
//	redir("generatedinvoices=1&count=" . $invoicecount);
//}
if ($ra->get_req_var("generatedinvoices")) {
    infoBox($aInt->lang("invoices", "gencomplete"), (int) $ra->get_req_var("count") . " Invoices Created");
}
//if ($ra->get_req_var("attemptccpayments")) {
//	check_token("RA.admin.default");
//	checkPermission("Attempts CC Captures");
//	$_SESSION['AdminHomeCCCaptureResultMsg'] = ccProcessing();
//	redir("attemptedccpayments=1");
//}
//
//if ($ra->get_req_var("attemptedccpayments") && isset($_SESSION['AdminHomeCCCaptureResultMsg'])) {
//	infoBox($aInt->lang("invoices", "attemptcccapturessuccess"), $_SESSION['AdminHomeCCCaptureResultMsg']);
//	unset($_SESSION['AdminHomeCCCaptureResultMsg']);
//}

releaseSession();
if ($action == "savenotes") {
    check_token("RA.admin.default");
    update_query("ra_admin", array("notes" => $notes), array("id" => $_SESSION['adminid']));
    redir();
}
if ($ra->get_req_var("saveorder")) {
    check_token("RA.admin.default");
    update_query("ra_admin", array("homewidgets" => $widgetdata), array("id" => $_SESSION['adminid']));
    exit();
}
if (isset($_POST['noteid'])) {
    $array = array(
        "duedate" => toMySQLDate($_POST['updatetime']),
        //   "modified" => "now()",
        "note" => $_POST['notesdata'],
        "sticky" => $_POST['done'],
    );

    update_query("ra_notes", $array, array("id" => $_POST['noteid']));
    if ($_POST['done']) {
        logActivity("Notes Update match as done - User ID: " . $userid, $userid);
    } else {
        logActivity("Notes Update new Due Date " . $_POST['updatetime'] . " - User ID: " . $userid, $userid);
    }
}

if ($ra->get_req_var("dismissgs")) {
    $roleid = get_query_val("ra_admin", "roleid", array("id" => $_SESSION['adminid']));
    $result = select_query_i("ra_adminroles", "widgets", array("id" => $roleid));
    $data = mysqli_fetch_array($result);
    $widgets = $data['widgets'];
    $widgets = explode(",", $widgets);
    foreach ($widgets as $k => $v) {

        if ($v == "getting_started") {
            unset($widgets[$k]);
            continue;
        }
    }

    update_query("ra_adminroles", array("widgets" => implode(",", $widgets)), array("id" => $roleid));
    exit();
}
if ($ra->get_req_var("getincome")) {
    check_token("RA.admin.default");

    if (!checkPermission("View Income Totals", true)) {
        return false;
    }

    $stats = getAdminHomeStats("income");
    echo "<a href=\"transactions.php\"><img src=\"images/icons/transactions.png\" align=\"absmiddle\" border=\"0\"> <b>" . $aInt->lang("billing", "income") . "</b></a> " . $aInt->lang("billing", "incometoday") . ": <span class=\"textgreen\"><b>" . $stats['income']['today'] . "</b></span> " . $aInt->lang("billing", "incomethismonth") . ": <span class=\"textred\"><b>" . $stats['income']['thismonth'] . "</b></span> " . $aInt->lang("billing", "incomethisyear") . ": <span class=\"textblack\"><b>" . $stats['income']['thisyear'] . "</b></span>";
    exit();
}

$templatevars['infobox'] = $infobox;
$query = "SELECT COUNT(*) FROM ra_modules_gateways WHERE setting='type' AND value='CC'";
$result = full_query_i($query);
$data = mysqli_fetch_array($result);

if ($data[0]) {
    $templatevars['showattemptccbutton'] = true;
}


if ($CONFIG['MaintenanceMode']) {
    $templatevars['maintenancemode'] = true;
}

$jquerycode = "$(\".homecolumn\").sortable({
	handle : '.widget-header',
    connectWith: ['.homecolumn'],
    stop: function() { saveHomeWidgets(); }
});
$(\".homewidget\").find(\".widget-header\").prepend(\"<span class='ui-icon ui-icon-minusthick'></span>\");
resHomeWidgets();
$(\".widget-header .ui-icon\").click(function() {
    $(this).toggleClass(\"ui-icon-minusthick\").toggleClass(\"ui-icon-plusthick\");
	$(this).parents(\".homewidget:first\").find(\".widget-content\").toggle();
    saveHomeWidgets();
});
";
$data = get_query_vals("ra_admin", "ra_admin.homewidgets,ra_adminroles.widgets", array("ra_admin.id" => $_SESSION['adminid']), "", "", "", "ra_adminroles ON ra_adminroles.id=ra_admin.roleid");
$homewidgets = $data['homewidgets'];
$allowedwidgets = $data['widgets'];

if (!$homewidgets) {
    $homewidgets = "getting_started:true,system_overview:true,income_overview:true,client_activity:true,admin_activity:true,activity_log:true|my_notes:true,orders_overview:true,sysinfo:true,RA_news:true,network_status:true,todo_list:true,income_forecast:true,open_invoices:true";
}

$homewidgets = explode("|", $homewidgets);
$homewidgetscol1 = explode(",", $homewidgets[0]);
foreach ($homewidgetscol1 as $k => $v) {
    $v = explode(":", $v);

    if (!$v[0]) {
        unset($homewidgetscol1[$k]);
        continue;
    }
}

$homewidgetscol1 = implode(",", $homewidgetscol1);
$homewidgetscol2 = explode(",", $homewidgets[1]);
foreach ($homewidgetscol2 as $k => $v) {
    $v = explode(":", $v);

    if (!$v[0]) {
        unset($homewidgetscol2[$k]);
        continue;
    }
}

$homewidgetscol2 = implode(",", $homewidgetscol2);
$jscode = "var savedOrders = new Array();
savedOrders[1] = \"" . $homewidgetscol1 . "\";
savedOrders[2] = \"" . $homewidgetscol2 . "\";
function saveHomeWidgets() {
    var orderdata = '';
    $(\".homecolumn\").each(function(index, value){
        var colid = value.id;
        var order = $(\"#\"+colid).sortable(\"toArray\");
        for (var i = 0, n = order.length; i < n; i++) {
            var v = $('#' + order[i]).find('.widget-content').is(':visible');
            order[i] = order[i] + \":\" + v;
        }
        orderdata = orderdata + order + \"|\";
    });";

if ($aInt->chartFunctions) {
    $jscode .= "redrawCharts()";
}

$csrfToken = generate_token("plain");
$jscode .= "    $.post(\"index.php\", { saveorder: \"1\", widgetdata: orderdata, token: \"" . $csrfToken . "\" });
}
function resHomeWidgets() {
    var IDs = '';
    var IDsp = '';
    var widgetID = '';
    var visible = '';
    var widget = '';
    for (var z = 1, y = 2; z <= y; z++) {
    	if (savedOrders[z]) {
            IDs = savedOrders[z].split(',');
            for (var i = 0, n = IDs.length; i < n; i++) {
                IDsp = (IDs[i].split(':'));
                widgetID = IDsp[0];
                visible = IDsp[1];
                widget = $(\".homecolumn\").find('#' + widgetID).appendTo($('#homecol'+z));
                if (visible === 'false') {
                    widget.find(\".ui-icon\").toggleClass(\"ui-icon-minusthick\").toggleClass(\"ui-icon-plusthick\");
                    widget.find(\".widget-content\").hide();
                }
            }
        }
    }
}";
$hooksdir = ROOTDIR . "/modules/widgets/";

if (is_dir($hooksdir)) {
    $dh = opendir($hooksdir);
//
    while (false !== ($hookfile = readdir($dh))) {
//  print_r($hookfile);
        if (is_file($hooksdir . $hookfile) && $hookfile != "index.php") {
            $extension = explode(".", $hookfile);
            $extension = end($extension);
//   echo "<pre>",$hooksdir . $hookfile,"</pre>";
//
            if ($extension == "php") {
                include $hooksdir . $hookfile;
            }
        }
    }
}
//
closedir($dh);
$templatevars['widgets'] = load_admin_home_widgets();

if (checkPermission("View Income Totals", true)) {
    $templatevars['viewincometotals'] = true;
    $jquerycode .= "jQuery.post(\"index.php\", { getincome: 1, token: \"" . generate_token("plain") . "\" },
    function(data){
        jQuery(\"#incometotals\").html(data);
    });";
}

$invoicedialog = $aInt->jqueryDialog(
        "geninvoices", $aInt->lang("invoices", "geninvoices"), $aInt->lang("invoices", "geninvoicessendemails"), array($aInt->lang("global", "yes") => "window.location='index.php?generateinvoices=true" . generate_token("link") . "'",
    $aInt->lang("global", "no") => "window.location='index.php?generateinvoices=true&noemails=true" . generate_token("link") . "'")
);
$cccapturedialog = $aInt->jqueryDialog("cccapture", $aInt->lang("invoices", "attemptcccaptures"), $aInt->lang("invoices", "attemptcccapturessure"), array($aInt->lang("global", "yes") => "window.location='index.php?attemptccpayments=true" . generate_token("link") . "'", $aInt->lang("global", "no") => ""));
$addons_html = run_hook("AdminHomepage", array());
$templatevars['addons_html'] = $addons_html;


$query = "select tbl1.* from ra_adminlog tbl1 inner join (select * from ra_adminlog order by lastvisit DESC) as tbl2 on tbl1.id = tbl2.id GROUP By tbl1.adminusername order by lastvisit DESC";
$result = full_query_i($query);
$adminlogin = array();
while ($data = mysqli_fetch_array($result)) {
    $adminlogin[] = $data;
}


$clientlogquery = "select firstname,lastname,ip,lastlogin from ra_user order by lastlogin DESC LIMIT 4";
$result = full_query_i($clientlogquery);
$clientlog = array();
while ($data = mysqli_fetch_array($result)) {
    $clientlog[] = $data;
}

$end_date = date("Y-m-d");
$start_date = date("Y-m-d", strtotime("-15 days", strtotime("now")));
$query = "select * from ra_orders where date between '" . $start_date . "' AND '" . $end_date . "' order by date desc";
$result = full_query_i($query);
$orders = array();

$templatevars['notes'] = array();

$query = "select tbn.*,CONCAT(tba.firstname,' ',tba.lastname) as name from ra_notes as tbn 
INNER JOIN ra_admin AS tba on (tba.id=tbn.adminid) where tbn.sticky=0 and tbn.assignto='" . $_SESSION['adminid'] . "'";
$result = full_query_i($query);
while ($data = mysqli_fetch_assoc($result)) {
    if (strtotime($data['duedate']) == strtotime(date("d.m.Y"))) {
        $data['color'] = "warning";
    } else if (strtotime($data['duedate']) < strtotime(date("d.m.Y"))) {
        $data['color'] = "danger";
    } else {
        $data['color'] = "success";
    }
    $data['duedate'] = fromMySQLDate($data['duedate']);
    $data['assignto'] = $data['assignto'];
    $data['type'] = $data['type'] == 'client' ? 'clientssummary.php?userid=' . $data['rel_id'] : "clientsservices.php?id=" . $data['rel_id'];
    $data['created'] = fromMySQLDate($data['created'], 1);
    $data['modified'] = fromMySQLDate($data['modified'], 1);
    $data['note'] = autoHyperLink(nl2br($data['note']));
    $templatevars['notes'][] = $data;
}


$templatevars["order"] = $orders;

$templatevars["clientlogin"] = $clientlog;
$aInt->jscode = $jscode;
$aInt->jquerycode = $jquerycode;
$aInt->templatevars = $templatevars;
$aInt->display();
?>
