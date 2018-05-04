<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {if isset($meta)}{$meta}{/if}
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
    <link href="templates/{$template}/assets/css/component.css" rel="stylesheet">
    <script src="templates/{$template}/assets/vendors/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="templates/{$template}/assets/js/jquery.easy-autcomplete.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/jquery-form/form@4.2.2/dist/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>

    {if isset($smartyvalues.headeroutput)}{$smartyvalues.headeroutput}{/if}
    {$headoutput}
</head>
<body>
    {$headeroutput}
    <div id="morphsearch" class="morphsearch">
        <form class="morphsearch-form">
            <input class="morphsearch-input" type="search" placeholder="Search..."/>
            <button class="morphsearch-submit" type="submit">Search</button>
        </form>
        <div class="morphsearch-content">

        </div>
        <span class="morphsearch-close"></span>
    </div>
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

                </div>
            </nav>
            <div class="content">

                <div class="container-fluid">

