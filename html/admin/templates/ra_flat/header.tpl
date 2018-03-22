<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {if $meta}{$meta}{/if}
    <title>{$pagetitle}</title>

    <!-- Bootstrap core CSS     -->
    <link href="templates/{$template}/assets/css/bootstrap.min.css" rel="stylesheet" >

    <!--  Material Dashboard CSS    -->
    <link href="templates/{$template}/assets/css/amaze.css" rel="stylesheet" >

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="templates/{$template}/assets/css/customer.css" rel="stylesheet" >

    <!--     Fonts and icons     -->
    <link href="templates/{$template}/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="templates/{$template}/assets/css/font-muli.css" rel='stylesheet' type='text/css'>
    <link href="templates/{$template}/assets/css/themify-icons.css" rel="stylesheet">

    <link href="templates/{$template}/assets/vendors/sweetalert/css/sweetalert2.min.css" rel="Stylesheet" >
    <script src="templates/{$template}/assets/vendors/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="templates/{$template}/assets/js/jquery.easy-autcomplete.min.js" type="text/javascript"></script>
    {$smartyvalues.headeroutput}
    {$headoutput}
</head>
<body>
    {$headeroutput}
    <div class="wrapper">
        <div class="sidebar" data-background-color="brown" data-active-color="danger">
            <!--
                        Tip 1: you can change the color of the sidebar's background using: data-background-color="white | brown"
                        Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
            -->
            <div class="logo">
                <a href="index.php" class="simple-text">
                    Unlimited Internet
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="index.php" class="simple-text">
                    UI
                </a>
            </div>
            <div class="sidebar-wrapper">
                {include file="$template/sidebar.tpl"}

            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button onclick="goBack()" class="btn btn-round btn-white btn-fill btn-just-icon">
                            <i class="ti-arrow-left"></i>
                        </button>

                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"> { $pagetitle } </a>

                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-bell"></i>
                                    <span class="notification">6</span>
                                    <p class="hidden-lg hidden-md">
                                        Notifications
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply+$sidebarstats.invoices.overdue} New Notices</a>
                                    </li>
                                    <li><!-- start message -->
                                    <li>
                                        <a href="orders.php?status=Pending"> 
                                            <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.orders.pending} New Orders</span> </a> 
                                    </li>
                                    <li>
                                        <a href="invoices.php?status=Overdue"> <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.invoices.overdue} Invoices Overdue</span> </a> 
                                    </li>
                            </li>
                        </ul>
                        </li>
                        <li>
                            <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-layout-grid3-alt"></i>
                                <p class="hidden-lg hidden-md">Apps</p>
                                <ul class="dropdown-menu">
                                    <li>here</li>

                                </ul>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-user"></i>
                                <p class="hidden-lg hidden-md">Profile</p>
                                <ul class="dropdown-menu">
                                    <li><a href="myaccount.php"><i class="fa fa-user"></i> Admin Profile</a></li>
                                    <li><a href="#notes"><i class="fa fa-gear"></i> My Notes</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/"><i class="fa fa-gear"></i> Client Area</a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>

                                </ul>
                            </a>
                        </li>
                        <li>
                            <a href="/" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-settings"></i>
                                <p class="hidden-lg hidden-md">Front Portal</p>
                            </a>
                        </li>
                        <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="searchbox">
                    <input type="text" name="value" autocomplete="off" class="form-control" id="intellisearchval" placeholder="Search...">
                    <div id="searchresults">
                        <div class="resultbox">
                            <div id="searchresultsscroller"></div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">

