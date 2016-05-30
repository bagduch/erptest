<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

function widget_system_overview($vars) {
    global $ra,$_ADMINLANG;

    $title = $_ADMINLANG['home']['sysoverview'];

    if ($ra->get_req_var('getsystemoverview')) {

        $activeclients = get_query_val("tblclients","COUNT(id)","status='Active'");
        $totalclients = get_query_val("tblclients","COUNT(id)","");
        $clientsactive = ($activeclients==0 || $totalclients==0) ? '0' : round((($activeclients/$totalclients)*100),0);

        $activeservices = get_query_val("tblcustomerservices","COUNT(id)","domainstatus='Active'");
        $totalservices = get_query_val("tblcustomerservices","COUNT(id)","");
        $servicesactive = ($activeservices==0 || $totalservices==0) ? '0' : round((($activeservices/$totalservices)*100),0);

        $unpaidinvoices = get_query_val("tblinvoices","COUNT(id)","status='Unpaid'");
        $overdueinvoices = get_query_val("tblinvoices","COUNT(id)","status='Unpaid' AND duedate<'".date("Ymd")."'");
        $overduestatus = ($overdueinvoices==0 || $unpaidinvoices==0) ? '0' : round((($overdueinvoices/$unpaidinvoices)*100),0);

        echo '
<div id="systemoverviewstats" style="display: block;">

	<div class="sys-overview-row" style="margin-top: -10px !important;">
	    <h4><a href="clients.php">Clients - <span>'.$totalclients.'</span></a></h4>
	    <div class="percentbar">
	    <div class="active" style="width:'.$clientsactive.'%">'.$clientsactive.'% Active</div>
	    </div>
	</div>

	<div class="sys-overview-row">
	    <h4><a href="clientshostinglist.php">Services - <span>'.$totalservices.'</span></a></h4>
	    <div class="percentbar">
	    <div class="active" style="width:'.$servicesactive.'%">'.$servicesactive.'% Active</div>
	    </div>
	</div>


	<div class="sys-overview-row">
	    <h4><a href="invoices.php?status=Unpaid">Unpaid Invoices - <span>'.$unpaidinvoices.'</span></a></h4>
	    <div class="percentbar">
	    <div class="overdue" style="width:'.$overduestatus.'%">'.$overduestatus.'% Overdue</div>
	    </div>
	</div>
</div>
';
        exit;

    }

    $adminusername = get_query_val("tbladmins","username",array("id"=>$vars['adminid']));
    $lastlogin = get_query_vals("tbladminlog","lastvisit,ipaddress",array("adminusername"=>$adminusername),"lastvisit","DESC","1,1");
    $lastlogindate = ($lastlogin[0]) ? fromMySQLDate($lastlogin[0],true) : '(None Recorded)';
    $lastloginip = ($lastlogin[1]) ? $lastlogin[1] : '-';

    $content = '
<style>
#systemoverviewstats {
    display: none;
}
#systemoverviewstats div.percentbar {
    width: 100%;
    height: 24px;
    border: 1px solid #ccc;
    background-color: #efefef;
}
#systemoverviewstats div.percentbar div.active {
    height: 24px;
    line-height: 24px;
    background-color: #84B429;
    color: #fff;
    font-weight: bold;
    text-align: center;
    overflow: hidden;
}
#systemoverviewstats div.percentbar div.overdue {
    height: 24px;
    line-height: 24px;
    background-color: #cc0000;
    color: #fff;
    font-weight: bold;
    text-align: center;
}
#systemoverviewstats td {
    text-align: center;
    font-weight: bold;
    height: 35px;
}
.lastlogin {
    padding:10px;
    text-align: center;
}
</style>

<div id="systemoverviewstats">'.$vars['loading'].'</div>

<div class="lastlogin">'.$_ADMINLANG['home']['lastlogin'].': <strong>'.$lastlogindate.'</strong> '.$_ADMINLANG['home']['lastloginip'].' <strong>'.$lastloginip.'</strong></div>

';

$statusfilter = array();
$result = select_query("tblticketstatuses","title",array("showawaiting"=>"1"));
while ($data = mysql_fetch_array($result)) $statusfilter[] = $data[0];
$result = full_query("SELECT COUNT(*) FROM tbltickets WHERE status IN (".db_build_in_array($statusfilter).")");
$data = mysql_fetch_array($result);
$ticketsawaitingreply = $data[0];
$result = full_query("SELECT COUNT(*) FROM tblcancelrequests INNER JOIN tblhosting ON tblcustomerservices.id=tblcancelrequests.relid WHERE (tblcustomerservices.domainstatus!='Cancelled' AND tblcustomerservices.domainstatus!='Terminated')");
$data = mysql_fetch_array($result);
$cancellationrequests = $data[0];
$result = full_query("SELECT COUNT(*) FROM tbltodolist WHERE status!='Completed' AND status!='Postponed' AND duedate<='".date("Y-m-d")."'");
$data = mysql_fetch_array($result);
$todoitemsdue = $data[0];
$result = full_query("SELECT COUNT(*) FROM tblnetworkissues WHERE status!='Scheduled' AND status!='Resolved'");
$data = mysql_fetch_array($result);
$opennetworkissues = $data[0];

    $jquerycode = 'jQuery.post("index.php", { getsystemoverview: 1 },
    function(data){
        jQuery("#systemoverviewstats").html(data);
        jQuery("#systemoverviewstats").slideDown();
        jQuery("#sysoverviewbanner").html("<div>'.$_ADMINLANG['global']['attentionitems'].': &nbsp; <a href=\"supporttickets.php\"><strong>'.$ticketsawaitingreply.'</strong> '.$_ADMINLANG['stats']['ticketsawaitingreply'].'</a> &nbsp;-&nbsp; <a href=\"cancelrequests.php\"><strong>'.$cancellationrequests.'</strong> '.$_ADMINLANG['stats']['pendingcancellations'].'</a> &nbsp;-&nbsp; <a href=\"todolist.php\"><strong>'.$todoitemsdue.'</strong> '.$_ADMINLANG['stats']['todoitemsdue'].'</a> &nbsp;-&nbsp; <a href=\"networkissues.php\"><strong>'.$opennetworkissues.'</strong> '.$_ADMINLANG['stats']['opennetworkissues'].'</a></div>");
});';

    return array('title'=>$title,'content'=>$content,'jquerycode'=>$jquerycode);

}

function widget_system_overview_home_banner() {
    return '<div id="sysoverviewbanner" class="red-banner"><div><img style="position: relative; top: 20px;" src="images/red-loader.gif" /></div></div>';
}

add_hook("AdminHomeWidgets",1,"widget_system_overview");
add_hook("AdminHomepage",1,"widget_system_overview_home_banner");

/*
#systemoverviewstats div {
    float: left;
    margin: 3px;
    padding: 5px;
    width: 30%;
    line-height: 24px;
    min-width: 250px;
    border: 1px solid #d9d9d9;
    background-color: #f2f2f2;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    overflow: hidden;
}
#systemoverviewstats div:hover {
    cursor: hand;
    cursor: pointer;
}
#systemoverviewstats div:hover a.view {
    color: #1A4D80;
    background-color: #fff;
    border: 1px solid #1A4D80;
}
#systemoverviewstats div div.statval {
    margin: 0 5px 0 0;
    padding: 0;
    min-width: 60px;
    font-family: Trebuchet MS,Tahoma;
    font-size: 24px;
    color: #777;
    text-decoration: none;
    text-align: center;
    border: 0;
    overflow: hidden;
}
#systemoverviewstats div a.view {
    float: right;
    margin: 0;
    padding: 0 7px;
    font-family: Tahoma;
    font-size: 20px;
    text-transform: uppercase;
    text-decoration: none;
    color: #fff;
    background-color: #1A4D80;
    border: 1px solid #1A4D80;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}
#systemoverviewstats div a.view:hover {
    color: #1A4D80;
    background-color: #fff;
    border: 1px solid #1A4D80;
}
*/

?>