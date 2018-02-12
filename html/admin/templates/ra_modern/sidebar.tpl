


    {php}
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
    {/php}
    <section class="sidebar">
        <div class="user-panel">
           {* <div class="pull-left image">
                <img src="templates/{$template}/dist/img/avatar.png" class="img-circle" alt="User Image">
            </div>*}
            <div class="pull-left info">
                <p> {$adminsonline}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
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
            {foreach from=$accordion item=section key=sectionname}
                {if $section.members|is_array}
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>{$section.name}</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {foreach from=$section.members item=member key=url}
                                <li><a href="{$url}"><i class="fa fa-circle-o"></i>{$member}</a></li>
                                    {/foreach}
                        </ul>
                    </li>
                {else}
                    <li>
                        <a href="{$section.url}">
                            <i class="fa fa-th"></i> <span>{$section.name}</span>
                        </a>
                    </li>
                {/if}
            {/foreach}

        </ul>
    </section>

