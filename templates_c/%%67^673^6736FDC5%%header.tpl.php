<?php /* Smarty version 2.6.28, created on 2016-10-11 15:25:21
         compiled from ra/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ra/header.tpl', 86, false),)), $this); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $this->_tpl_vars['pagetitle']; ?>
</title>

        <!-- http://getbootstrap.com/getting-started/ -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/css/main.css" /> 
        <link rel="stylesheet" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/dist/css/AdminLTE.css">
        <link rel="stylesheet" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" />
        <!-- iCheck -->
        <link rel="stylesheet" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/iCheck/all.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/datatables/dataTables.bootstrap.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!-- jQuery 2.2.3 -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.6 -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/bootstrap/js/bootstrap.min.js"></script>
        <!-- Morris.js charts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/morris/morris.min.js"></script>
        <!-- Sparkline -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/sparkline/jquery.sparkline.min.js"></script>
        <!-- jvectormap -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/knob/jquery.knob.js"></script>
        <!-- daterangepicker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- iCheck 1.0.1 -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/iCheck/icheck.min.js"></script>
        <!-- DataTables -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- Slimscroll -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/dist/js/app.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <!-- InputMask -->
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/input-mask/jquery.inputmask.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="templates/<?php echo $this->_tpl_vars['template']; ?>
/plugins/input-mask/jquery.inputmask.extensions.js"></script>

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
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="smalldate"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %d %B %Y, %H:%M") : smarty_modifier_date_format($_tmp, "%A, %d %B %Y, %H:%M")); ?>
</li>
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success"><?php echo $this->_tpl_vars['sidebarstats']['orders']['pending']+$this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']; ?>
</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have <?php echo $this->_tpl_vars['sidebarstats']['orders']['pending']+$this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']; ?>
 messages</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <li>test</li>
                                            <li>test2</li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="supporttickets.php"><?php echo $this->_tpl_vars['_ADMINLANG']['stats']['ticketsawaitingreply']; ?>
 <span class="badge"><?php echo $this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']; ?>
</span></a></li>
                                </ul>
                            </li> 
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning"><?php echo $this->_tpl_vars['sidebarstats']['orders']['pending']+$this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']+$this->_tpl_vars['sidebarstats']['invoices']['overdue']; ?>
</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"><?php echo $this->_tpl_vars['sidebarstats']['orders']['pending']+$this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']+$this->_tpl_vars['sidebarstats']['invoices']['overdue']; ?>
 New Notices</li>
                                    <li>
                                        <ul class="menu">
                                            <li>
                                                <a href="orders.php?status=Pending"> 
                                                    <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message"><?php echo $this->_tpl_vars['sidebarstats']['orders']['pending']; ?>
 New Orders</span> </a> 
                                            </li>
                                            <li>
                                                <a href="invoices.php?status=Overdue"> <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message"><?php echo $this->_tpl_vars['sidebarstats']['invoices']['overdue']; ?>
 Invoices Overdue</span> </a> 
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="supporttickets.php"><?php echo $this->_tpl_vars['_ADMINLANG']['stats']['ticketsawaitingreply']; ?>
 <span class="badge"><?php echo $this->_tpl_vars['sidebarstats']['tickets']['awaitingreply']; ?>
</span></a></li>
                                </ul>
                            </li>
                            <li class="dropdown user-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->_tpl_vars['admin_username']; ?>
<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="myaccount.php"><i class="fa fa-user"></i> Admin Profile</a></li>
                                    <li><a href="#notes"><i class="fa fa-gear"></i> My Notes</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/"><i class="fa fa-gear"></i> Client Area</a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>

                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['template'])."/sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </section>
            </aside>


            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <?php echo $this->_tpl_vars['pagetitle']; ?>
 
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"> <?php echo $this->_tpl_vars['pagetitle']; ?>
 </li>
                    </ol>
                </section>
                <section class="content">
                    