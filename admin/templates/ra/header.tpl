<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
<title>RA - {$pagetitle}</title>
<link href="templates/{$template}/style.css" rel="stylesheet" type="text/css" />
<link href="../includes/jscript/css/ui.all.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../includes/jscript/jquery.js"></script>
<script type="text/javascript" src="../includes/jscript/jqueryui.js"></script>
<script type="text/javascript" src="../includes/jscript/textext.js"></script>
<script type="text/javascript">
var datepickerformat = "{$datepickerformat}";
{if $jquerycode}$(document).ready(function(){ldelim}
    {$jquerycode}
{rdelim});
{/if}
{if $jscode}{$jscode}
{/if}
</script>
<script type="text/javascript" src="templates/{$template}/head.js"></script>
<script type="text/javascript" src="../includes/jscript/adminsearchbox.js"></script>
{$headoutput}
</head>
<body>
{$headeroutput}
<div class="topbar">
  <div class="left"><a href="index.php">{$_ADMINLANG.home.title}</a> | <a href="myaccount.php">Staff Account</a> | <a href="orders.php?status=Pending"><span class="stat">{$sidebarstats.orders.pending}</span> {$_ADMINLANG.stats.pendingorders}</a> | <a href="invoices.php?status=Overdue"><span class="stat">{$sidebarstats.invoices.overdue}</span> {$_ADMINLANG.stats.overdueinvoices}</a> | <a href="supporttickets.php"><span class="stat">{$sidebarstats.tickets.awaitingreply}</span> {$_ADMINLANG.stats.ticketsawaitingreply}</a> | <a id="logout" href="logout.php">{$_ADMINLANG.global.logout}</a></div>
  <div class="right date"> {$smarty.now|date_format:"%A, %d %B %Y, %H:%M"} </div>
</div>
<div class="header"> </div>

{include file="$template/menu.tpl"}

<div id="sidebaropen"{if !$minsidebar} style="display:none;"{/if}> <a href="#" onclick="sidebarOpen();return false"><img src="templates/{$template}/images/opensidebar.png" border="0" /></a> </div>
<div id="sidebar"{if $minsidebar} style="display:none;"{/if}> {include file="$template/sidebar.tpl"} </div>
<div class="contentarea" id="contentarea"{if !$minsidebar} style="margin-left:209px;"{/if}>
<div style="float:left;width:100%;">
<h1>{$pagetitle}</h1>