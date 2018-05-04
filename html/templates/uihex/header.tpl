<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<head>
    <meta charset="UTF-8">
    <title>{$companyname} - {$pagetitle}{if isset($kbarticle.title)} - {$kbarticle.title}{/if}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' id='bootstrap-css'  href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css' type='text/css' media='all' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="templates/{$template}/css/owl.carousel.css">
    <link rel='stylesheet' href="templates/{$template}/css/animate.min.css" />
    <link rel='stylesheet' id='hexa-css' href='templates/{$template}/css/ui-portal.css' type='text/css' media='all' />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="templates/{$template}/js/client.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.en-NZ.min.js" charset="UTF-8"></script>
    <link id="bsdp-css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.min.css" rel="stylesheet" />

    <script src="templates/{$template}/js/enable_datepick.js"></script>



    {$headoutput}
</head>
<body>
    <div class="bodybg"></div>
    <div class="bodybg1"></div>
    {if (isset($formaction) && $formaction == 'dologin.php') || $filename == 'logout' || $filename == 'pwreset' || $filename == 'contact'}
        <!--<div class="hexa-container hidden-xs"><a href="contact.php" class="btn btn-xs btn-default hexa-btn" data-original-title="{$LANG.contactus}"><i class="fa fa-send-o"></i></a></div>-->
        <div class="login-wrapper">
            <div class="container">
                <!--<div class="row">
                    <div class="col-md-4 col-md-offset-4 logo-page">
                        <a href="index.php" title="{$companyname}"><span class="logo"><span aria-hidden="true" class="icon icon"></span>{$companyname}</span></a>
                    </div>
                </div>-->
            {else}
                {$headeroutput}
                <!--<div class="hexa-container hidden-xs"><a href="contact.php" class="btn btn-xs btn-default hexa-btn" data-original-title="{$LANG.contactus}"><i class="fa fa-send-o"></i></a></div>-->
                <div class="container">
                    <div class="row head">
                        <nav class="navbar" role="navigation">
                            <div class="navbar-header">
                                <div class="navbar-brand"><a href="clientarea.php"><img src="/templates/uihex/img/ui-logo-332x80.png"><h6>Client Area</h6></a><i class="fa fa-bars fa-lg btn-nav-toggle-responsive"></i></div>
                            </div>
                            <div class="row menu">
                                <div class="top-menu">
                                    <div class="menu-holder" data-original-title="" title="">
                                        {if isset($loggedin) && $loggedin}
                                            <ul class="nav  nav-list">
                                                <li {if $filename eq "clientarea" and $smarty.get.action eq ""} class="active"{/if}><a href="clientarea.php" data-original-title="{$LANG.clientareanavhome}"><span aria-hidden="true" class="icon icon-home"></span><span class="hidden-minibar {php}echo @$hide{/php} "> {$LANG.clientareanavhome}</span></a></li>
                                                <li {if $filename eq "clientarea" and $smarty.get.action eq "details"} class="active"{/if}{if $filename eq "clientarea" and $smarty.get.action eq "changepw"} class="active"{/if}><a href="clientarea.php?action=details" data-original-title="{$LANG.account}"><span aria-hidden="true" class="icon icon-home"></span><span class="hidden-minibar {php}echo @$hide{/php} "> {$LANG.account}</span></a></li>
                                                <li {if $filename eq "clientarea" and $smarty.get.action eq "Services"} class="active"{/if}><a href="clientarea.php?action=services" data-original-title="{$LANG.navservices}"><span aria-hidden="true" class="icon icon-layers"></span><span class="hidden-minibar {php}echo @$hide{/php}"> {$LANG.navservices}</span><span class="badge badge-default pull-right">{$clientsstats.productsnumactive}</span></a></li>


                                                <li {if $filename eq "clientarea" and $smarty.get.action eq "invoices"} class="active"{/if}{if $filename eq "clientarea" and $smarty.get.action eq "transection"} class="active"{/if}{if $filename eq "clientarea" and $smarty.get.action eq "creditus"} class="active"{/if}{if $filename eq "clientarea" and $smarty.get.action eq "creditcard"} class="active"{/if}><a href="clientarea.php?action=invoices" data-original-title="{$LANG.navbilling}"><span class="hidden-minibar {php}echo @$hide{/php}"> {$LANG.navbilling}</span></a></li>

                                                <li {if $filename eq "submitticket"} class="active"{/if}{if $filename eq "supporttickets"} class="active"{/if}{if $filename eq "downloads"} class="active"{/if}{if $filename eq "knowledgebase"} class="active"{/if}><a href="supporttickets.php" data-original-title="{$LANG.navsupport}"><span class="hidden-minibar {php}echo @$hide{/php}"> {$LANG.navsupport}</span></a></li>
                                                {if $condlinks.affiliates} <li {if $filename eq "affiliates"} class="active"{/if}><a href="affiliates.php" data-original-title="{$LANG.affiliatestitle}"><span aria-hidden="true" class="icon icon-users"></span><span class="hidden-minibar {php}echo @$hide{/php}">{$LANG.affiliatestitle}</span></a></li>{/if}
                                            </ul>
                                        {else}
                                            <ul class="nav nav-list">
                                                <li class="nav-toggle"><button class="btn  btn-nav-toggle"><i class="fa toggle-left fa-angle-double-left" style="color:#eee;"></i> </button></li>
                                                <li {if $filename eq "myorder"} class="active"{/if}><a href="myorder.php" data-original-title="{$LANG.ordertitle}"><span aria-hidden="true" class="icon icon-rocket"></span><span class="hidden-minibar {php}echo @$hide{/php}"> {$LANG.ordertitle}</span></a></li>
                                                <li {if $filename eq "contact"} class="active"{/if}><a href="contact.php" data-original-title="{$LANG.contactus}"><span aria-hidden="true" class="icon icon-envelope"></span><span class="hidden-minibar {php}echo @$hide{/php}"> {$LANG.contactus}</span></a></li>
                                                {if $condlinks.affiliates} <li {if $filename eq "affiliates"} class="active"{/if}><a href="affiliates.php" data-original-title="{$LANG.affiliatestitle}"><span aria-hidden="true" class="icon icon-users"></span><span class="hidden-minibar {php}echo @$hide{/php}">{$LANG.affiliatestitle}</span></a></li>{/if}
                                                <li {if $filename eq "downloads"} class="active"{/if}><a href="downloads.php" data-original-title="{$LANG.downloadstitle}"><span aria-hidden="true" class="icon icon-cloud-download"></span><span class="hidden-minibar {php}echo @$hide{/php}">{$LANG.downloadstitle}</span></a></li>
                                                <li {if $filename eq "knowledgebase"} class="active"{/if}><a href="knowledgebase.php" data-original-title="{$LANG.knowledgebasetitle}"><span aria-hidden="true" class="icon icon-question"></span><span class="hidden-minibar {php}echo @$hide{/php}">{$LANG.knowledgebasetitle}</span></a></li>
                                            </ul>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            {if isset($loggedin) && $loggedin}
                                <div class="collapse navbar-collapse">
                                    <ul class="nav navbar-nav user-menu navbar-right" id="user-menu">
                                        <!--<li><a href="clientarea.php?action=details" class="user dropdown-toggle" data-toggle="dropdown"><span class="username"><img src="https://secure.gravatar.com/avatar/{php}$userid = $this->_tpl_vars['clientsdetails']['userid'];$result = mysqli_query("SELECT email FROM tblclients WHERE id=$userid");$data = mysqli_fetch_array($result);$email = $data["email"];echo md5( strtolower( trim( $email ) ) );{/php}?s=50&d=mm" class="user-avatar" alt="">  {$clientsdetails.firstname} {$clientsdetails.lastname} </span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="clientarea.php?action=details"><span aria-hidden="true" class="icon icon-user"></span> {$LANG.editaccountdetails}</a></li>
                                                <li><a href="clientarea.php?action=changepw"><span aria-hidden="true" class="icon icon-lock"></span> {$LANG.clientareanavchangepw}</a></li>
                                                <li class="divider"></li>
                                                {if $condlinks.addfunds}<li><a href="clientarea.php?action=addfunds"><span aria-hidden="true" class="icon icon-plus"></span> {$LANG.addfunds}</a></li>{/if}
                                                {if $condlinks.updatecc}<li><a href="clientarea.php?action=creditcard"><span aria-hidden="true" class="icon icon-credit-card"></span> {$LANG.navmanagecc}</a></li>{/if}
                                                <li class="divider"></li>
                                                <li><a href="logout.php"><span aria-hidden="true" class="icon icon-logout"></span> {$LANG.logouttitle}</a></li>
                                            </ul>
                                        </li>   -->
                                        <!--<li><a href="clientarea.php?action=emails" class="settings"><span aria-hidden="true" class="icon icon-envelope"></span></a></li>-->
                                        <li><a href="cart.php?a=view" class="settings"><span aria-hidden="true" class="icon icon-basket"><span class="badge cart bg-success">
                                          {php}$cartitems = count (@$_SESSION['cart']['products']) + count (@$_SESSION['cart']['addons']) + count (@$_SESSION['cart']['domains']);echo @$cartitems{/php}
                                        </span></span></a></li>
                                        <li><a href="#" class="settings dropdown-toggle" data-toggle="dropdown"><span aria-hidden="true" class="icon icon-bell"></span><span class="badge noti bg-success"></span></a>
                                            <ul class="dropdown-menu notifications" id="noti">
                                                {if $clientsstats.numoverdueinvoices>0 AND in_array('invoices',$contactpermissions)}
                                                    <li>
                                                        <a href="clientarea.php?action=masspay&amp;all=true">
                                                            <span aria-hidden="true" class="icon noty-icon bg-danger icon-docs"></span>
                                                            <span class="description">{$LANG.youhaveoverdueinvoices|sprintf:$clientsstats.numoverdueinvoices}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                                {if !$clientsstats.incredit AND in_array('invoices',$contactpermissions)}
                                                    <li>
                                                        <a href="clientarea.php?action=addfunds">
                                                            <span aria-hidden="true" class="icon noty-icon bg-success icon-wallet"></span>
                                                            <span class="description">{$LANG.availcreditbaldesc|sprintf:$clientsstats.creditbalance}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                                {if isset($ccexpiringsoon) && $ccexpiringsoon AND in_array('invoices',$contactpermissions)}
                                                    <li>
                                                        <a href="clientarea.php?action=creditcard">
                                                            <span aria-hidden="true" class="icon noty-icon bg-danger icon-credit-card"></span>
                                                            <span class="description">{$LANG.ccexpiringsoon}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </li>
                                        <li><a href="logout.php" class="settings"><span aria-hidden="true" class="icon icon-logout"></span></a></li>
                                    </ul>
                                </div>
                            {else}
                                <div class="collapse navbar-collapse">
                                    <ul class="nav navbar-nav user-menu navbar-right" id="user-menu">
                                        <li><a href="login.php"><span aria-hidden="true" class="icon icon-login"></span> {$LANG.login}</a></li>
                                    </ul>
                                </div>
                            {/if}
                        </nav>
                    </div>
                </div>
                {php}
 if(isset($_COOKIE['minibar']) && $_COOKIE['minibar']=='1')
 {
 $mini="mini-sidebar";
 $hide="hide";
 $diplay="style='display: none';";
 }else {
 $mini="";
 $hide="";
 $diplay="";
 }
                {/php}
                <div class="site-holder container {php}echo $mini{/php}">
                    <div class="box-holder">
                        <div class="content onload">
                            <div class="row">
                                <div class="col-md-12">
                                {/if}
