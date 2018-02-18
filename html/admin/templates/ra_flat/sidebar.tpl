<ul class="nav">
    <li class="active">
        <a href="index.php">
            <i class="ti-panel"></i>
            <p>Dashboard</p>
        </a>
    </li>
      <li><a href="addonmodules.php?module=radius"><i class="ti-dashboard"></i>Radius</a></li>
    <li><a href="addonmodules.php?module=tolls"><i class="ti-headphone"></i>Tolls Phone</a></li>
    <li>
        <a data-toggle="collapse" href="#Customers" class="collapsed" aria-expanded="false">
            <i class="ti-bar-chart-alt"></i>
            <p>Customers
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Customers" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li>
                    <a href="clients.php">View Customers</a>
                </li>
                <li>
                    <a href="clientsadd.php">Add New Client</a>
                </li>
                <li>
                    <a href="cancelrequests.php">Cancellation Requests</a>
                </li>
                <li>
                    <a href="affiliates.php">Manage Affiliates</a>
                </li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#Orders" class="collapsed" aria-expanded="false">
            <i class="ti-package"></i>
            <p>Orders
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Orders" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="orders.php">List Orders</a></li>
                <li><a href="orders.php?status=Pending">Pending Orders</a></li>
                <li><a href="orders.php?status=Active">Active Orders</a></li>
                <li><a href="orders.php?status=Cancelled">Cancelled Orders</a></li>
                <li><a href="ordersadd.php">Add New Order</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#Billing" class="collapsed" aria-expanded="false">
            <i class="ti-clipboard"></i>
            <p>Billing
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Billing" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="gatewaylog.php">Gateway Logs</a></li>
                <li><a href="invoices.php">All Invoices</a></li>
                <li><a href="invoices.php?status=Overdue">Overdue</a></li>
                <li><a href="invoices.php?status=Refunded">Refunded</a></li>
                <li><a href="invoices.php?status=Collections">Collections</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#Support" class="collapsed" aria-expanded="false">
            <i class="ti-view-list-alt"></i>
            <p>Support
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Support" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="supportcenter.php">Support Overview</a></li>
                <li><a href="supporttickets.php?action=list">View Tickets</a></li>
                <li><a href="supporttickets.php?action=open">Open Tickets</a></li>
                <li><a href="supportticketpredefinedreplies.php">Predefined Replies</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#Services" class="collapsed" aria-expanded="false">
            <i class="ti-package"></i>
            <p>Services
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Services" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="configservices.php">Services</a></li>
                <li><a href="configproducts.php">Product</a></li>
                <li><a href="configservices.php?action=creategroup">Create Service Group</a></li>
                <li><a href="configcustomfieldsgroup.php">Custom Fields</a></li>
                <li><a href="configaddons.php">Service Products</a></li>
            </ul>
        </div>
    </li>
  
    <li>
        <a data-toggle="collapse" href="#Reports" class="collapsed" aria-expanded="false">
            <i class="ti-stats-up"></i>
            <p>Reports
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Reports" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="reports.php">All Reports</a></li>
                <li><a href="reports.php?report=HD_GST_calculator">GST Calculator</a></li>
                <li><a href="reports.php?report=annual_income_report">Annual Income Report</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#utilities" class="collapsed" aria-expanded="false">
            <i class="ti-hummer"></i>
            <p>Utilities
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="utilities" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="supportannouncements.php">Announcements</a></li>
                <li><a href="supportkb.php">Knowledgebase</a></li>
                <li><a href="networkissues.php">Network Notices</a></li>
                <li><a href="systemactivitylog.php">Activity Log</a></li>
                <li><a href="systemadminlog.php">Admin Log</a></li>
                <li><a href="systemmodulelog.php">Module/API Log</a></li>
                <li><a href="systememaillog.php">Email Message Log</a></li>
                <li><a href="systemmailimportlog.php">Ticket Mail Import Log</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a data-toggle="collapse" href="#System" class="collapsed" aria-expanded="false">
            <i class="ti-settings"></i>
            <p>System
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="System" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="configgeneral.php">General Settings</a></li>
                <li><a href="configauto.php">Automation Settings</a></li>
                <li><a href="configclientgroups.php">Client Groups</a></li>
                <li><a href="clientfields.php">Client Fields</a></li>
                <li><a href="configemailtemplates.php">Email Templates</a></li>
                <li><a href="configesmstemplates.php">TxT Templates</a></li>
                <li><a href="configaddonmods.php">Addon Modules</a></li>
                <li><a href="configgeneral.php">Currencies</a></li>
                <li><a href="configgateways.php">Payment Gateways</a></li>
                <li><a href="configtax.php">Tax Rules</a></li>
                <li><a href="configpromotions.php">Promotions</a></li>
            </ul>
        </div>
    </li>

    <li>
        <a data-toggle="collapse" href="#Staff" class="collapsed" aria-expanded="false">
            <i class="ti-user"></i>
            <p>Staff Management
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="Staff" role="navigation" aria-expanded="false" style="height: 0px;">
            <ul class="nav">
                <li><a href="configadmins.php">Administrator Users</a></li>
                <li><a href="configadminroles.php">Administrator Roles</a></li>
            </ul>
        </div>
    </li>



</ul>
