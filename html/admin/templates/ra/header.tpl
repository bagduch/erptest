<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {if $meta}{$meta}{/if}
    <title>{$pagetitle}</title>

    <!-- http://getbootstrap.com/getting-started/ -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="templates/{$template}/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="templates/{$template}/css/main.css" /> 
    <link rel="stylesheet" href="templates/{$template}/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="templates/{$template}/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="templates/{$template}/plugins/select2/select2.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="templates/{$template}/plugins/iCheck/all.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="templates/{$template}/plugins/morris/morris.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="templates/{$template}/plugins/datatables/dataTables.bootstrap.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="templates/{$template}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- jQuery 2.2.3 -->
    <script src="templates/{$template}/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> 
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

    <!-- AngularJs -->


    <!-- Bootstrap 3.3.6 -->
    <script src="templates/{$template}/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="templates/{$template}/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="templates/{$template}/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="templates/{$template}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="templates/{$template}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="templates/{$template}/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="templates/{$template}/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="templates/{$template}/plugins/select2/select2.full.min.js"></script>
    <!-- datepicker -->
    <script src="templates/{$template}/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="templates/{$template}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="templates/{$template}/plugins/iCheck/icheck.min.js"></script>
    <!-- DataTables -->
    <script src="templates/{$template}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="templates/{$template}/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Slimscroll -->
    <script src="templates/{$template}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="templates/{$template}/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="templates/{$template}/dist/js/app.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {*        <script src="templates/{$template}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>*}
    <!-- AdminLTE for demo purposes -->
    <!-- InputMask -->
    <script src="templates/{$template}/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="templates/{$template}/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="templates/{$template}/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="templates/{$template}/plugins/chartjs/Chart.min.js"></script>
    <script src="templates/{$template}/js/jquery.multilevelpushmenu.min.js"></script>
    <script src="templates/{$template}/js/admin.js"></script>
    {literal}
        <script>
        {/literal}{$jscode}{literal}

        </script>
    {/literal}
    {$headoutput}
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>UI</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Unlimited</b> Internet</span>
            </a>
            <nav class="navbar navbar-static-top">
                {*   <a href="#" class="sidebar-toggle" onclick="hidemenu();" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                </a>*}
                <div class='breadcrumb-wrap'>
                    <ol class="breadcrumb">
                        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                            {foreach from=$breadcrumb item=row}
                            <li class="active"><a href="{$row.url}">{$row.pagetitle}</a> </li>
                            {/foreach}
                    </ol>
                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="search-bar">         <div class="pull-right">
                                <div class="input-group">
                                    <input type="text" name="value" autocomplete="off" class="form-control" id="intellisearchval" placeholder="Search...">
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div id="searchresults">
                                <div id="searchresultsscroller"></div>
                                <div class="pull-right">
                                    <a class="btn btn-danger" href="#" onclick="searchclose()">{$_ADMINLANG.clientsummary.close} </a>
                                </div>
                            </div></li>
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have {$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply} messages</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            {foreach from=$tickets item=data}
                                                <a href="/admin/supporttickets.php?action=viewticket&id={$data.id}">
                                                    <div class="pull-left">
                                                        <div class="profit {$data.profile}-profit">{$data.profile}</div>
                                                    </div>
                                                    <h4>
                                                        {$data.title}
                                                        <small><i class="fa fa-clock-o"></i> {$data.lastreply}</small>
                                                    </h4>
                                                    <p>{$data.message}</p>
                                                </a>
                                            {/foreach}
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="supporttickets.php">{$_ADMINLANG.stats.ticketsawaitingreply} <span class="badge">{$sidebarstats.tickets.awaitingreply}</span></a></li>
                            </ul>
                        </li> 
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply+$sidebarstats.invoices.overdue}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply+$sidebarstats.invoices.overdue} New Notices</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="orders.php?status=Pending"> 
                                                <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.orders.pending} New Orders</span> </a> 
                                        </li>
                                        <li>
                                            <a href="invoices.php?status=Overdue"> <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.invoices.overdue} Invoices Overdue</span> </a> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="supporttickets.php">{$_ADMINLANG.stats.ticketsawaitingreply} <span class="badge">{$sidebarstats.tickets.awaitingreply}</span></a></li>
                            </ul>
                        </li>
                        <li class="dropdown user-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {$admin_username}<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="myaccount.php"><i class="fa fa-user"></i> Admin Profile</a></li>
                                <li><a href="#notes"><i class="fa fa-gear"></i> My Notes</a></li>
                                <li class="divider"></li>
                                <li><a href="/"><i class="fa fa-gear"></i> Client Area</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>
                            </ul>
                        </li>

                    </ul>

            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                {include file="$template/sidebar_new.tpl"}
            </section>
        </aside>


        <div class="content-wrapper">
            <section class="content">
                {*<div class="col-lg-12">
                <h1><small>new extra note for page<!-- NEEDS CODING INTO $pagetitle_note --></small></h1>
                <!-- BREAK INTO ALERT BOX -->
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                NOTICE - THIS IS AN ALERT FOR THIS PAGE - CODED ALERTS NEEDS AN INTERFACE <br>                         
                </div>
                <!-- ALERT BOX END -->
                </div>*}
