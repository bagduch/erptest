<?php /* Smarty version 2.6.28, created on 2016-10-07 11:44:04
         compiled from ra/sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'is_array', 'ra/sidebar.tpl', 115, false),)), $this); ?>


<?php 
$accordion = array(
    "home" => array (
        "name" => "Home",
        "url" => "index.php",
        "glyphicon" => "home"
    ),
    "clients" => array(
        "name" => "Customers",
        "glyphicon" => "user",
        "members" => array(
            "clients.php" => "View Customers",
            "clientsadd.php" => "Add New Client",
            "cancelrequests.php" => "Cancellation Requests",
            "affiliates.php" => "Manage Affiliates"
        )
    ),
    "orders" => array(
        "name" => "Orders",
        "glyphicon" => "shopping-cart",
        "members" => array(
            "orders.php" => "List Orders",
            "orders.php?status=Pending" => "Pending Orders",
            "orders.php?status=Active" => "Active Orders",
            "orders.php?status=Cancelled" => "Cancelled Orders",
            "ordersadd.php" => "Add New Order"
        )
    ),
    "services" => array(
        "name" => "Services",
        "glyphicon" => "user",
        "members" => array(
            "configservices.php" => "Services",
            "configservices.php?action=creategroup" => "Create Service Group",
            "configcustomfieldsgroup.php?action=managegroup" => "Custom Field Group",
            "configcustomfieldsgroup.php" => "Custom Fields",
            "configaddons.php" => "Service Products"
        )
    ),
    "billing" => array(
        "name" => "Billing",
        "glyphicon" => "usd",
        "members" => array(
            "transactions.php" => "Transactions",
            "gatewaylog.php" => "Gateway Logs",
            "invoices.php" => "All Invoices",
            "invoices.php?status=Overdue" => "Overdue",
            "invoices.php?status=Refunded" => "Refunded",
            "invoices.php?status=Collections" => "Collections"
        )
    ),
    "support" => array(
        "name" => "Support",
        "glyphicon" => "file",
        "members" => array(
            "supportcenter.php" => "Support Overview",
            "supporttickets.php?action=list" => "View Tickets",
            "supporttickets.php?action=open" => "Open Ticket",
            "supportticketpredefinedreplies.php" => "Predefined Replies"
        )
    ),
    "reports" => array(
        "name" => "Reports",
        "glyphicon" => "file",
        "members" => array(
            "reports.php" => "All Reports",
            "reports.php?report=sales_tax_liability" => "GST Calculator",
            "reports.php?report=annual_income_report" => "Annual Income Report",
            "reports.php?report=new_customers" => "Signup Report"
        )
    ),
    "utilities" => array(
        "name" => "Utilities",
        "glyphicon" => "file",
        "members" => array(
            "supportannouncements.php" => "Announcements",
            "supportkb.php" => "Knowledgebase",
            "networkissues.php" => "Network Notices",
            "systemactivitylog.php" => "Activity Log",
            "systemadminlog.php" => "Admin Log",
            "systemmodulelog.php" => "Module/API Log",
            "systememaillog.php" => "Email Message Log",
            "systemmailimportlog.php" => "Ticket Mail Import Log"
        )
    )

            
);
$this->assign('accordion', $accordion);
 ?>
<div class="user-panel">
    <div class="pull-left image">
        <img src="templates/<?php echo $this->_tpl_vars['template']; ?>
/dist/img/avatar.png" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p><?php echo $this->_tpl_vars['adminsonline']; ?>
</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>

</div>
<form action="#" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
        </span>
    </div>
</form>
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
        <?php $_from = $this->_tpl_vars['accordion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sectionname'] => $this->_tpl_vars['section']):
?>
            <?php if (((is_array($_tmp=$this->_tpl_vars['section']['members'])) ? $this->_run_mod_handler('is_array', true, $_tmp) : is_array($_tmp))): ?>
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-<?php echo $this->_tpl_vars['section']['glyphicon']; ?>
"></i> <span><?php echo $this->_tpl_vars['section']['name']; ?>
</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php $_from = $this->_tpl_vars['section']['members']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['url'] => $this->_tpl_vars['member']):
?>
                        <li class="active"><a href="<?php echo $this->_tpl_vars['url']; ?>
"><i class="fa fa-circle-o"></i><?php echo $this->_tpl_vars['member']; ?>
</a></li>
                            <?php endforeach; endif; unset($_from); ?>
                </ul>
            </li>
        <?php else: ?>
            <li><a href="<?php echo $this->_tpl_vars['section']['url']; ?>
"><i class="glyphicon glyphicon-<?php echo $this->_tpl_vars['section']['glyphicon']; ?>
"></i> <span><?php echo $this->_tpl_vars['section']['name']; ?>
</span></a></li>
            <?php endif; ?>

    <?php endforeach; endif; unset($_from); ?>

</ul>
<form method="get" action="search.php" class="sidebar-form">

    <table class="table">
        <tr>
            <td>
                <select class="form-control" name="type" id="searchtype" onchange="populate(this)">
                    <option value="clients">Clients </option>
                    <option value="orders">Orders </option>
                    <option value="services">Services </option>
                    <option value="domains">Domains </option>
                    <option value="invoices">Invoices </option>
                    <option value="tickets">Tickets </option>
                </select>

            </td>
            <td>
                <select class="form-control" name="field" id="searchfield">
                    <option>Client ID</option>
                    <option selected="selected">Client Name</option>
                    <option>Company Name</option>
                    <option>Email Address</option>
                    <option>Address 1</option>
                    <option>Address 2</option>
                    <option>City</option>
                    <option>State</option>
                    <option>Postcode</option>
                    <option>Country</option>
                    <option>Phone Number</option>
                    <option>CC Last Four</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"> <input  class="form-control" type="text" name="q" autocomplete="off" style="width:85%;" /></td>
        </tr>
        <tr>
            <td colspan="2">   
                <input type="submit" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['search']; ?>
" class="button" />
            </td>
        </tr>
    </table>

</form>

