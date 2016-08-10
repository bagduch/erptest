
<div class="topbar">
  <div class="left"><a href="index.php">{$_ADMINLANG.home.title}</a> | <a href="myaccount.php">Staff Account</a> | <a href="orders.php?status=Pending"><span class="stat">{$sidebarstats.orders.pending}</span> {$_ADMINLANG.stats.pendingorders}</a> | <a href="invoices.php?status=Overdue"><span class="stat">{$sidebarstats.invoices.overdue}</span> {$_ADMINLANG.stats.overdueinvoices}</a> | <a href="supporttickets.php"><span class="stat">{$sidebarstats.tickets.awaitingreply}</span> {$_ADMINLANG.stats.ticketsawaitingreply}</a> | <a id="logout" href="logout.php">{$_ADMINLANG.global.logout}</a></div>
  <div class="right date"> {$smarty.now|date_format:"%A, %d %B %Y, %H:%M"} </div>
</div>
<div class="header"> </div>

<!-- START MENU -->
<div class="navigation">
  <ul id="menu">
    <li><a {if in_array("List Clients",$admin_perms)}href="clients.php"{/if} title="Clients">{$_ADMINLANG.clients.title}</a>
      <ul>
        {if in_array("List Clients",$admin_perms)}
        <li><a href="clients.php">{$_ADMINLANG.clients.viewsearch}</a></li>
        {/if}
        {if in_array("Add New Client",$admin_perms)}
        <li><a href="clientsadd.php">{$_ADMINLANG.clients.addnew}</a></li>
        {/if}
        {*  {if in_array("List Services",$admin_perms)}
        <li class="expand"><a href="clientshostinglist.php">{$_ADMINLANG.services.title}</a>
          <ul>
            <li><a href="clientshostinglist.php?listtype=hostingaccount">- {$_ADMINLANG.services.listhosting}</a></li>
            <li><a href="clientshostinglist.php?listtype=reselleraccount">- {$_ADMINLANG.services.listreseller}</a></li>
            <li><a href="clientshostinglist.php?listtype=server">- {$_ADMINLANG.services.listservers}</a></li>
            <li><a href="clientshostinglist.php?listtype=other">- {$_ADMINLANG.services.listother}</a></li>
          </ul>
        </li>
        {/if}
        {if in_array("View Cancellation Requests",$admin_perms)}
        <li><a href="cancelrequests.php">{$_ADMINLANG.clients.cancelrequests}</a></li>
        {/if}*}
        {if in_array("Manage Affiliates",$admin_perms)}
        <li><a href="affiliates.php">{$_ADMINLANG.affiliates.manage}</a></li>
        {/if}
        {if in_array("Mass Mail",$admin_perms)}
        <li><a href="massmail.php">{$_ADMINLANG.clients.massmail}</a></li>
        {/if}
      </ul>
    </li>
    <li><a {if in_array("View Orders",$admin_perms)}href="orders.php"{/if} title="Orders">{$_ADMINLANG.orders.title}</a>
      <ul>
        {if in_array("View Orders",$admin_perms)}
        <li><a href="orders.php">{$_ADMINLANG.orders.listall}</a></li>
        {/if}
        {if in_array("Add New Order",$admin_perms)}
        <li><a href="ordersadd.php">{$_ADMINLANG.orders.addnew}</a></li>
        {/if}
      </ul>
    </li>
    <li><a {if in_array("List Transactions",$admin_perms)}href="transactions.php"{/if} title="Billing">{$_ADMINLANG.billing.title}</a>
      <ul>
        {if in_array("List Transactions",$admin_perms)}
        <li><a href="transactions.php">{$_ADMINLANG.billing.transactionslist}</a></li>
        {/if}
        {if in_array("List Invoices",$admin_perms)}
        <li class="expand"><a href="invoices.php">{$_ADMINLANG.invoices.title}</a>
          <ul>
            <li><a href="invoices.php?status=Paid">- {$_ADMINLANG.status.paid}</a></li>
            <li><a href="invoices.php?status=Unpaid">- {$_ADMINLANG.status.unpaid}</a></li>
            <li><a href="invoices.php?status=Overdue">- {$_ADMINLANG.status.overdue}</a></li>
            <li><a href="invoices.php?status=Cancelled">- {$_ADMINLANG.status.cancelled}</a></li>
            <li><a href="invoices.php?status=Refunded">- {$_ADMINLANG.status.refunded}</a></li>
            <li><a href="invoices.php?status=Collections">- {$_ADMINLANG.status.collections}</a></li>
          </ul>
        </li>
        {/if}
        {*   {if in_array("View Billable Items",$admin_perms)}
        <li class="expand"><a href="billableitems.php">{$_ADMINLANG.billableitems.title}</a>
          <ul>
            <li><a href="billableitems.php?status=Uninvoiced">- {$_ADMINLANG.billableitems.uninvoiced}</a></li>
            <li><a href="billableitems.php?status=Recurring">- {$_ADMINLANG.billableitems.recurring}</a></li>
            {if in_array("Manage Billable Items",$admin_perms)}
            <li><a href="billableitems.php?action=manage">- {$_ADMINLANG.billableitems.addnew}</a></li>
            {/if}
          </ul>
        </li>
        {/if}
        {if in_array("Manage Quotes",$admin_perms)}
        <li class="expand"><a href="quotes.php">{$_ADMINLANG.quotes.title}</a>
          <ul>
            <li><a href="quotes.php?validity=Valid">- {$_ADMINLANG.status.valid}</a></li>
            <li><a href="quotes.php?validity=Expired">- {$_ADMINLANG.status.expired}</a></li>
            <li><a href="quotes.php?action=manage">- {$_ADMINLANG.quotes.createnew}</a></li>
          </ul>
        </li>
        {/if}
        {if in_array("Offline Credit Card Processing",$admin_perms)}
        <li><a href="offlineccprocessing.php">{$_ADMINLANG.billing.offlinecc}</a></li>
        {/if}
        {if in_array("View Gateway Log",$admin_perms)}
        <li><a href="gatewaylog.php">{$_ADMINLANG.billing.gatewaylog}</a></li>
        {/if}
        *}
      </ul>
      {if in_array("View Products/Services",$admin_perms) || in_array("Configure Product Addons",$admin_perms) || in_array("Configure Product Bundles",$admin_perms) || in_array("Configure Domain Pricing",$admin_perms) || in_array("Configure Domain Registrars",$admin_perms) || in_array("Configure Servers",$admin_perms)}
    <li class="expand"><a href="#">{$_ADMINLANG.setup.servicestitle}</a>
      <ul>
        {if in_array("View Products/Services",$admin_perms)}
        <li><a href="configservices.php">{$_ADMINLANG.setup.services}</a></li>
        {/if}
        {if in_array("View Products/Services",$admin_perms)}
        <li><a href="configservices.php?action=creategroup">{$_ADMINLANG.setup.cservicesg}</a></li>
        {/if}
        {if in_array("View Products/Services",$admin_perms)}
        <li><a href="configcustomfieldsgroup.php">{$_ADMINLANG.setup.services}</a></li>
        {/if}
        {if in_array("View Products/Services",$admin_perms)}
        <li><a href="configproductoptions.php">{$_ADMINLANG.setup.configoptions}</a></li>
        {/if}
        {if in_array("Configure Product Addons",$admin_perms)}
        <li><a href="configaddons.php">{$_ADMINLANG.setup.saddons}</a></li>
        {/if}
      </ul>
    </li>
    {/if}
    </li>
    <li><a {if in_array("Support Center Overview",$admin_perms)}href="supportcenter.php"{/if} title="Support">{$_ADMINLANG.support.title}</a>
      <ul>
        {if in_array("Support Center Overview",$admin_perms)}
        <li><a href="supportcenter.php">{$_ADMINLANG.support.supportoverview}</a></li>
        {/if}
        {if in_array("Manage Announcements",$admin_perms)}
        <li><a href="supportannouncements.php">{$_ADMINLANG.support.announcements}</a></li>
        {/if}
        {if in_array("Manage Downloads",$admin_perms)}
        <li><a href="supportdownloads.php">{$_ADMINLANG.support.downloads}</a></li>
        {/if}
        {if in_array("Manage Knowledgebase",$admin_perms)}
        <li><a href="supportkb.php">{$_ADMINLANG.support.knowledgebase}</a></li>
        {/if}
        {if in_array("List Support Tickets",$admin_perms)}
        <li class="expand"><a href="supporttickets.php">{$_ADMINLANG.support.supporttickets}</a>
          <ul>
            <li><a href="supporttickets.php?view=flagged">- {$_ADMINLANG.support.flagged}</a></li>
            <li><a href="supporttickets.php?view=active">- {$_ADMINLANG.support.allactive}</a></li>
            <li><a href="supporttickets.php?view=Open">- Open</a></li>
            <li><a href="supporttickets.php?view=Answered">- Answered</a></li>
            <li><a href="supporttickets.php?view=Customer-Reply">- Customer-Reply</a></li>
            <li><a href="supporttickets.php?view=On Hold">- On Hold</a></li>
            <li><a href="supporttickets.php?view=In Progress">- In Progress</a></li>
            <li><a href="supporttickets.php?view=Closed">- Closed</a></li>
          </ul>
        </li>
        {/if}
        {if in_array("Open New Ticket",$admin_perms)}
        <li><a href="supporttickets.php?action=open">{$_ADMINLANG.support.opennewticket}</a></li>
        {/if}
        {if in_array("Manage Predefined Replies",$admin_perms)}
        <li><a href="supportticketpredefinedreplies.php">{$_ADMINLANG.support.predefreplies}</a></li>
        {/if}
        {if in_array("Manage Network Issues",$admin_perms)}
        <li class="expand"><a href="networkissues.php">{$_ADMINLANG.networkissues.title}</a>
          <ul>
            <li><a href="networkissues.php">- {$_ADMINLANG.networkissues.open}</a></li>
            <li><a href="networkissues.php?view=scheduled">- {$_ADMINLANG.networkissues.scheduled}</a></li>
            <li><a href="networkissues.php?view=resolved">- {$_ADMINLANG.networkissues.resolved}</a></li>
            <li><a href="networkissues.php?action=manage">- {$_ADMINLANG.networkissues.addnew}</a></li>
          </ul>
        </li>
        {/if}
      </ul>
    </li>
    {if in_array("View Reports",$admin_perms)}
    <li><a title="Reports" href="reports.php">{$_ADMINLANG.reports.title}</a>
      <ul>
        <li><a href="reports.php?report=daily_performance">Daily Performance</a></li>
        <li><a href="reports.php?report=income_forecast">Income Forecast</a></li>
        <li><a href="reports.php?report=annual_income_report">Annual Income Report</a></li>
        <li><a href="reports.php?report=new_customers">New Customers</a></li>
        <li><a href="reports.php?report=ticket_feedback_scores">Ticket Feedback Scores</a></li>
        <li><a href="reports.php?report=pdf_batch">Batch Invoice PDF Export</a></li>
        <li><a href="reports.php">More...</a></li>
      </ul>
    </li>
    {/if}
    <li><a title="Utilities" href="">{$_ADMINLANG.utilities.title}</a>
      <ul>
        {if in_array("To-Do List",$admin_perms)}
        <li><a href="todolist.php">{$_ADMINLANG.utilities.todolist}</a></li>
        {/if}
        {if in_array("View Activity Log",$admin_perms)}
        <li><a href="systemactivitylog.php">{$_ADMINLANG.utilities.activitylog}</a></li>
        {/if}
        {if in_array("View Admin Log",$admin_perms)}
        <li><a href="systemadminlog.php">{$_ADMINLANG.utilities.adminlog}</a></li>
        {/if}
        {if in_array("View Email Message Log",$admin_perms)}
        <li><a href="systememaillog.php">{$_ADMINLANG.utilities.emaillog}</a></li>
        {/if}
        {if in_array("View Ticket Mail Import Log",$admin_perms)}
        <li><a href="systemmailimportlog.php">{$_ADMINLANG.utilities.ticketmaillog}</a></li>
        {/if}
      </ul>
    </li>
    <li><a title="Setup" href="">{$_ADMINLANG.setup.title}</a>
      <ul>
        {if in_array("Configure General Settings",$admin_perms)}
        <li><a href="configgeneral.php">{$_ADMINLANG.setup.general}</a></li>
        {/if}
        {if in_array("Configure Automation Settings",$admin_perms)}
        <li><a href="configauto.php">{$_ADMINLANG.setup.automation}</a></li>
        {/if}
        {if in_array("Configure Addon Modules",$admin_perms)}
        <li><a href="configaddonmods.php">{$_ADMINLANG.setup.addonmodules}</a></li>
        {/if}
        
        {if in_array("Configure Administrators",$admin_perms) || in_array("Configure Admin Roles",$admin_perms) || in_array("Configure Two-Factor Authentication",$admin_perms)}
        <li class="expand"><a href="#">{$_ADMINLANG.setup.staff}</a>
          <ul>
            {if in_array("Configure Administrators",$admin_perms)}
            <li><a href="configadmins.php">{$_ADMINLANG.setup.admins}</a></li>
            {/if}
            {if in_array("Configure Admin Roles",$admin_perms)}
            <li><a href="configadminroles.php">{$_ADMINLANG.setup.adminroles}</a></li>
            {/if}
            {if in_array("Configure Two-Factor Authentication",$admin_perms)}
            <li><a href="configtwofa.php">{$_ADMINLANG.setup.twofa}</a></li>
            {/if}
          </ul>
        </li>
        {else}
        <li><a href="myaccount.php">{$_ADMINLANG.global.myaccount}</a></li>
        {/if}
        {if in_array("Configure Currencies",$admin_perms) || in_array("Configure Payment Gateways",$admin_perms) || in_array("Configure Tax Setup",$admin_perms) || in_array("View Promotions",$admin_perms)}
        <li class="expand"><a href="#">{$_ADMINLANG.setup.payments}</a>
          <ul>
            {if in_array("Configure Currencies",$admin_perms)}
            <li><a href="configcurrencies.php">{$_ADMINLANG.setup.currencies}</a></li>
            {/if}
            {if in_array("Configure Payment Gateways",$admin_perms)}
            <li><a href="configgateways.php">{$_ADMINLANG.setup.gateways}</a></li>
            {/if}
            {if in_array("Configure Tax Setup",$admin_perms)}
            <li><a href="configtax.php">{$_ADMINLANG.setup.tax}</a></li>
            {/if}
            {if in_array("View Promotions",$admin_perms)}
            <li><a href="configpromotions.php">{$_ADMINLANG.setup.promos}</a></li>
            {/if}
          </ul>
        </li>
        {/if}
        {if in_array("View Products/Services",$admin_perms) || in_array("Configure Product Addons",$admin_perms) || in_array("Configure Product Bundles",$admin_perms) || in_array("Configure Servers",$admin_perms)}
        <li class="expand"><a href="#">{$_ADMINLANG.setup.products}</a>
          <ul>
            {if in_array("View Products/Services",$admin_perms)}
            <li><a href="configproducts.php">{$_ADMINLANG.setup.products}</a></li>
            {/if}
            {if in_array("View Products/Services",$admin_perms)}
            <li><a href="configservices.php">{$_ADMINLANG.setup.services}</a></li>
            {/if}
            {if in_array("View Products/Services",$admin_perms)}
            <li><a href="configproductoptions.php">{$_ADMINLANG.setup.configoptions}</a></li>
            {/if}
            {if in_array("Configure Product Addons",$admin_perms)}
            <li><a href="configaddons.php">{$_ADMINLANG.setup.addons}</a></li>
            {/if}
            {if in_array("Configure Product Bundles",$admin_perms)}
            <li><a href="configbundles.php">{$_ADMINLANG.setup.bundles}</a></li>
            {/if}
            {if in_array("Configure Servers",$admin_perms)}
            <li><a href="configservers.php">{$_ADMINLANG.setup.servers}</a></li>
            {/if}
          </ul>
        </li>
        {/if}
        {if in_array("Configure Support Departments",$admin_perms) || in_array("Configure Ticket Statuses",$admin_perms) || in_array("Configure Support Departments",$admin_perms) || in_array("Configure Spam Control",$admin_perms)}
        <li class="expand"><a href="#">{$_ADMINLANG.support.title}</a>
          <ul>
            {if in_array("Configure Support Departments",$admin_perms)}
            <li><a href="configticketdepartments.php">{$_ADMINLANG.setup.supportdepartments}</a></li>
            {/if}
            {if in_array("Configure Ticket Statuses",$admin_perms)}
            <li><a href="configticketstatuses.php">{$_ADMINLANG.setup.ticketstatuses}</a></li>
            {/if}
            {if in_array("Configure Support Departments",$admin_perms)}
            <li><a href="configticketescalations.php">{$_ADMINLANG.setup.escalationrules}</a></li>
            {/if}
            {if in_array("Configure Spam Control",$admin_perms)}
            <li><a href="configticketspamcontrol.php">{$_ADMINLANG.setup.spam}</a></li>
            {/if}
          </ul>
        </li>
        {/if}
        {if in_array("View Email Templates",$admin_perms)}
        <li><a href="configemailtemplates.php">{$_ADMINLANG.setup.emailtpls}</a></li>
        {/if}
        {if in_array("Configure Addon Modules",$admin_perms)}
        <li><a href="configaddonmods.php">{$_ADMINLANG.setup.addonmodules}</a></li>
        {/if}
        {if in_array("Configure Client Groups",$admin_perms)}
        <li><a href="configclientgroups.php">{$_ADMINLANG.setup.clientgroups}</a></li>
        {/if}
        {if in_array("Configure Custom Client Fields",$admin_perms)}
        <li><a href="configcustomfields.php">{$_ADMINLANG.setup.customclientfields}</a></li>
        {/if}
        {if in_array("Configure Order Statuses",$admin_perms) || in_array("Configure Security Questions",$admin_perms) || in_array("View Banned IPs",$admin_perms) || in_array("Configure Banned Emails",$admin_perms)}
        <li class="expand"><a href="#">{$_ADMINLANG.setup.other}</a>
          <ul>
            {if in_array("Configure Order Statuses",$admin_perms)}
            <li><a href="configorderstatuses.php">{$_ADMINLANG.setup.orderstatuses}</a></li>
            {/if}
            {if in_array("Configure Security Questions",$admin_perms)}
            <li><a href="configsecurityqs.php">{$_ADMINLANG.setup.securityqs}</a></li>
            {/if}
            {if in_array("View Banned IPs",$admin_perms)}
            <li><a href="configbannedips.php">{$_ADMINLANG.setup.bannedips}</a></li>
            {/if}
            {if in_array("Configure Banned Emails",$admin_perms)}
            <li><a href="configbannedemails.php">{$_ADMINLANG.setup.bannedemails}</a></li>
            {/if}
          </ul>
        </li>
        {/if}
      </ul>
    </li>
    </li>
  </ul>
</div>

<!-- END MENU -->

<div id="sidebaropen"{if !$minsidebar} style="display:none;"{/if}> <a href="#" onclick="sidebarOpen();return false"><img src="templates/{$template}/images/opensidebar.png" border="0" /></a> </div>
<div id="sidebar"{if $minsidebar} style="display:none;"{/if}> 
  <!-- START Sidebar--> 
  {if $sidebar eq "home"}
  <ul class="menu">
    <li><a href="clientsadd.php"><img src="images/icons/clientsadd.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.clients.addnew}</a></li>
    <li><a href="ordersadd.php"><img src="images/icons/ordersadd.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.orders.addnew}</a></li>
    <li><a href="todolist.php"><img src="images/icons/todolist.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.utilities.todolistcreatenew}</a></li>
    <li><a href="supporttickets.php?action=open"><img src="images/icons/tickets.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.support.opennewticket}</a></li>
  </ul>
  {elseif $sidebar eq "clients"} <span class="header"><img src="images/icons/clients.png" class="absmiddle" alt="Clients" width="16" height="16" /> {$_ADMINLANG.clients.title}</span>
  <ul class="menu">
    <li><a href="clients.php">{$_ADMINLANG.clients.viewsearch}</a></li>
    <li><a href="clientsadd.php">{$_ADMINLANG.clients.addnew}</a></li>
  </ul>
  <span class="header"><img src="images/icons/products.png" alt="Products/Services" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.services.title}</span>
  <ul class="menu">
    <li><a href="clientshostinglist.php">{$_ADMINLANG.services.listall}</a></li>
    <li><a href="cancelrequests.php">{$_ADMINLANG.clients.cancelrequests}</a></li>
  </ul>
  <span class="header"><img src="images/icons/affiliates.png" alt="Affiliates" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.affiliates.title}</span>
  <ul class="menu">
    <li><a href="affiliates.php">{$_ADMINLANG.affiliates.manage}</a></li>
  </ul>
  {elseif $sidebar eq "orders"} <span class="header"><img src="images/icons/orders.png" alt="Affiliates" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.orders.title}</span>
  <ul class="menu">
    <li><a href="orders.php">{$_ADMINLANG.orders.listall}</a></li>
    <li><a href="orders.php?status=Pending">- {$_ADMINLANG.orders.listpending}</a></li>
    <li><a href="orders.php?status=Active">- {$_ADMINLANG.orders.listactive}</a></li>
    <li><a href="orders.php?status=Fraud">- {$_ADMINLANG.orders.listfraud}</a></li>
    <li><a href="orders.php?status=Cancelled">- {$_ADMINLANG.orders.listcancelled}</a></li>
    <li><a href="ordersadd.php">{$_ADMINLANG.orders.addnew}</a></li>
  </ul>
  {elseif $sidebar eq "billing"} <span class="header"><img src="images/icons/transactions.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.billing.title}</span>
  <ul class="menu">
    <li><a href="transactions.php">{$_ADMINLANG.billing.transactionslist}</a></li>
    <li><a href="gatewaylog.php">{$_ADMINLANG.billing.gatewaylog}</a></li>
    <li><a href="offlineccprocessing.php">{$_ADMINLANG.billing.offlinecc}</a></li>
  </ul>
  <span class="header"><img src="images/icons/invoices.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.invoices.title}</span>
  <ul class="menu">
    <li><a href="invoices.php">{$_ADMINLANG.invoices.listall}</a></li>
    <li><a href="invoices.php?status=Draft">- Draft</a></li>
    <li><a href="invoices.php?status=Paid">- {$_ADMINLANG.status.paid}</a></li>
    <li><a href="invoices.php?status=Unpaid">- {$_ADMINLANG.status.unpaid}</a></li>
    <li><a href="invoices.php?status=Overdue">- {$_ADMINLANG.status.overdue}</a></li>
    <li><a href="invoices.php?status=Cancelled">- {$_ADMINLANG.status.cancelled}</a></li>
    <li><a href="invoices.php?status=Refunded">- {$_ADMINLANG.status.refunded}</a></li>
    <li><a href="invoices.php?status=Collections">- {$_ADMINLANG.status.collections}</a></li>
  </ul>
  <span class="header"><img src="images/icons/billableitems.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.billableitems.title}</span>
  <ul class="menu">
    <li><a href="billableitems.php">{$_ADMINLANG.billableitems.listall}</a></li>
    <li><a href="billableitems.php?status=Uninvoiced">- {$_ADMINLANG.billableitems.uninvoiced}</a></li>
    <li><a href="billableitems.php?status=Recurring">- {$_ADMINLANG.billableitems.recurring}</a></li>
    <li><a href="billableitems.php?action=manage">{$_ADMINLANG.billableitems.addnew}</a></li>
  </ul>
  {elseif $sidebar eq "support"}
  
  {if $inticket} <span class="header"><img src="images/icons/support.png" alt="Support Center" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.support.ticketinfo}</span> <span class="ticketheader">{$_ADMINLANG.fields.client}</span>
  <div class="ticketinfo smallfont"> {if $userid}<a href="clientssummary.php?userid={$userid}"{if $clientgroupcolour} style="background-color:{$clientgroupcolour}"{/if} target="_blank">{$clientname}</a>{if $contactid} (<a href="clientscontacts.php?userid={$userid}&contactid={$contactid}"{if $clientgroupcolour} style="background-color:{$clientgroupcolour}"{/if} target="_blank">{$contactname}</a>){/if}{else}{$_ADMINLANG.support.notregclient}{/if} </div>
  <span class="ticketheader">{$_ADMINLANG.support.department}</span>
  <div class="ticketinfo">
    <select id="deptid" onchange="updateTicket('deptid')">
      
{foreach from=$departments item=department}

      <option value="{$department.id}"{if $department.id eq $deptid} selected{/if}>{$department.name}</option>
      
{/foreach}

    </select>
  </div>
  <span class="ticketheader">{$_ADMINLANG.support.assignedto}</span>
  <div class="ticketinfo">
    <select id="flagto" onchange="updateTicket('flagto')">
      <option value="0">{$_ADMINLANG.global.none}</option>
      
{foreach from=$staff item=staffmember}

      <option value="{$staffmember.id}"{if $staffmember.id eq $flag} selected{/if}>{$staffmember.name}</option>
      
{/foreach}

    </select>
    <a href="#" onclick="$('#flagto').val({$adminid});$('#flagto').trigger('change');return false">{$_ADMINLANG.support.me}</a> </div>
  <span class="ticketheader">{$_ADMINLANG.support.priority}</span>
  <div class="ticketinfo">
    <select id="priority" onchange="updateTicket('priority')">
      <option value="High"{if $priority eq "High"} selected{/if}>{$_ADMINLANG.status.high}</option>
      <option value="Medium"{if $priority eq "Medium"} selected{/if}>{$_ADMINLANG.status.medium}</option>
      <option value="Low"{if $priority eq "Low"} selected{/if}>{$_ADMINLANG.status.low}</option>
    </select>
  </div>
  <span class="ticketheader">{$_ADMINLANG.support.staffparticipants}</span>
  <div class="ticketinfo smallfont"> {foreach from=$staffinvolved item=staffname}
    {$staffname}<br />
    {foreachelse}
    No Replies Yet
    {/foreach} </div>
  <span class="ticketheader">{$_ADMINLANG.support.tags}</span>
  <div class="ticketinfo">
    <textarea id="ticketTags" rows="1" style="width:175px;"></textarea>
  </div>
  <br />
  {else} <span class="header"><img src="images/icons/support.png" alt="Support Center" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.support.title}</span>
  <ul class="menu">
    <li><a href="supportannouncements.php">{$_ADMINLANG.support.announcements}</a></li>
    <li><a href="supportdownloads.php">{$_ADMINLANG.support.downloads}</a></li>
    <li><a href="supportkb.php">{$_ADMINLANG.support.knowledgebase}</a></li>
    <li><a href="supporttickets.php?action=open">{$_ADMINLANG.support.opennewticket}</a></li>
    <li><a href="supportticketpredefinedreplies.php">{$_ADMINLANG.support.predefreplies}</a></li>
  </ul>
  {/if} <span class="header"><img src="images/icons/tickets.png" alt="Filter Tickets" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.support.filtertickets}</span>
  <form method="post" action="supporttickets.php">
    <span class="header">{$_ADMINLANG.fields.status}</span>
    <select name="view">
      <option value="any">- Any -</option>
      <option value=""{if $ticketfilterdata.view eq ""} selected{/if}>{$_ADMINLANG.support.awaitingreply} ({$ticketsawaitingreply})</option>
      <option value="flagged"{if $ticketfilterdata.view eq "flagged"} selected{/if}>{$_ADMINLANG.support.flagged} ({$ticketsflagged})</option>
      <option value="active"{if $ticketfilterdata.view eq "active"} selected{/if}>{$_ADMINLANG.support.allactive} ({$ticketsallactive})</option>
      
{foreach from=$ticketstatuses item=status}
    
      <option value="{$status.title}"{if $status.title eq $ticketfilterdata.view} selected{/if}>{$status.title} ({$status.count})</option>
      
{/foreach}

    </select>
    <span class="header">{$_ADMINLANG.support.department}</span>
    <select name="deptid">
      <option value="">- Any -</option>
      
{foreach from=$ticketdepts item=dept}
    
      <option value="{$dept.id}"{if $dept.id eq $ticketfilterdata.deptid} selected{/if}>{$dept.name}</option>
      
{/foreach}

    </select>
    <span class="header">{$_ADMINLANG.support.subjectmessage}</span>
    <input type="text" name="subject" value="{$ticketfilterdata.subject}" />
    <span class="header">{$_ADMINLANG.fields.email}</span>
    <input type="text" name="email" value="{$ticketfilterdata.email}" />
    <input type="submit" value="Filter &raquo;" />
  </form>
  <br />
  {if $inticketlist} <span class="header"><img src="images/icons/tickets.png" alt="Tag Cloud" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.support.tagcloud}</span>
  <div class="tagcloud">{$tagcloud}</div>
  {/if}
  
  {if !$inticket} <span class="header"><img src="images/icons/networkissues.png" alt="Network Issues" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.networkissues.title}</span>
  <ul class="menu">
    <li><a href="networkissues.php">- {$_ADMINLANG.networkissues.open}</a></li>
    <li><a href="networkissues.php?view=scheduled">- {$_ADMINLANG.networkissues.scheduled}</a></li>
    <li><a href="networkissues.php?view=resolved">- {$_ADMINLANG.networkissues.resolved}</a></li>
    <li><a href="networkissues.php?action=manage">{$_ADMINLANG.networkissues.addnew}</a></li>
  </ul>
  {/if}
  
  {elseif $sidebar eq "reports"} <span class="header"><img src="images/icons/reports.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.reports.title}</span>
  <ul class="menu">
    {foreach from=$text_reports key=filename item=reporttitle}
    <li><a href="reports.php?report={$filename}">{$reporttitle}</a></li>
    {/foreach}
  </ul>
  {elseif $sidebar eq "browser"} <span class="header"><img src="images/icons/browser.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.browser.bookmarks}</span>
  <ul class="menu">
    <li><a href="http://www.ra.com/" target="brwsrwnd">RA Homepage</a></li>
    <li><a href="https://www.ra.com/clients/" target="brwsrwnd">RA Client Area</a></li>
    {foreach from=$browserlinks item=link}
    <li><a href="{$link.url}" target="brwsrwnd">{$link.name} <img src="images/delete.gif" width="10" border="0" onclick="doDelete('{$link.id}')"></a></li>
    {/foreach}
  </ul>
  <form method="post" action="browser.php?action=add">
    <input type="hidden" name="token" value="{$csrfToken}" />
    <span class="header"><img src="images/icons/browseradd.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.browser.addnew}</span>
    <ul class="menu">
      <li>{$_ADMINLANG.browser.sitename}:<br>
        <input type="text" name="sitename" size="25" style="font-size:9px;">
        <br>
        {$_ADMINLANG.browser.url}:<br>
        <input type="text" name="siteurl" size="25" value="http://" style="font-size:9px;">
        <br>
        <input type="submit" value="{$_ADMINLANG.browser.add}" style="font-size:9px;">
      </li>
    </ul>
  </form>
  {elseif $sidebar eq "utilities"} <span class="header"><img src="images/icons/utilities.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.utilities.title}</span>
  <ul class="menu">
    <li><a href="todolist.php">{$_ADMINLANG.utilities.todolist}</a></li>
  </ul>
  <span class="header"><img src="images/icons/logs.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.utilities.logs}</span>
  <ul class="menu">
    <li><a href="systemactivitylog.php">{$_ADMINLANG.utilities.activitylog}</a></li>
    <li><a href="systemadminlog.php">{$_ADMINLANG.utilities.adminlog}</a></li>
    <li><a href="systemmodulelog.php">{$_ADMINLANG.utilities.modulelog}</a></li>
    <li><a href="systememaillog.php">{$_ADMINLANG.utilities.emaillog}</a></li>
    <li><a href="systemmailimportlog.php">{$_ADMINLANG.utilities.ticketmaillog}</a></li>
  </ul>
  {elseif $sidebar eq "addonmodules"}
  
  {$addon_module_sidebar} <span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.utilities.addonmodules}</span>
  <ul class="menu">
    {foreach from=$addon_modules key=filename item=addontitle}
    <li><a href="addonmodules.php?module={$filename}">{$addontitle}</a></li>
    {/foreach}
  </ul>
  {elseif $sidebar eq "config"} <span class="header"><img src="images/icons/config.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.setup.config}</span>
  <ul class="menu">
    <li><a href="configgeneral.php">{$_ADMINLANG.setup.general}</a></li>
    <li><a href="configauto.php">{$_ADMINLANG.setup.automation}</a></li>
    <li><a href="configemailtemplates.php">{$_ADMINLANG.setup.emailtpls}</a></li>
    <li><a href="configaddonmods.php">{$_ADMINLANG.setup.addonmodules}</a></li>
    <li><a href="configclientgroups.php">{$_ADMINLANG.setup.clientgroups}</a></li>
    <li><a href="configfraud.php">{$_ADMINLANG.setup.fraud}</a></li>
  </ul>
  <span class="header"><img src="images/icons/admins.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.setup.staff}</span>
  <ul class="menu">
    <li><a href="configadmins.php">{$_ADMINLANG.setup.admins}</a></li>
    <li><a href="configadminroles.php">{$_ADMINLANG.setup.adminroles}</a></li>
    <li><a href="configtwofa.php">{$_ADMINLANG.setup.twofa}</a></li>
  </ul>
  <span class="header"><img src="images/icons/income.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.setup.payments}</span>
  <ul class="menu">
    <li><a href="configcurrencies.php">{$_ADMINLANG.setup.currencies}</a></li>
    <li><a href="configgateways.php">{$_ADMINLANG.setup.gateways}</a></li>
    <li><a href="configtax.php">{$_ADMINLANG.setup.tax}</a></li>
    <li><a href="configpromotions.php">{$_ADMINLANG.setup.promos}</a></li>
  </ul>
  <span class="header"><img src="images/icons/products.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.setup.services}</span>
  <ul class="menu">
    <li><a href="configserivces.php">{$_ADMINLANG.setup.services}</a></li>
    <li><a href="configproductoptions.php">{$_ADMINLANG.setup.configoptions}</a></li>
    <li><a href="configaddons.php">{$_ADMINLANG.setup.addons}</a></li>
    <li><a href="configbundles.php">{$_ADMINLANG.setup.bundles}</a></li>
    <li><a href="configdomains.php">{$_ADMINLANG.setup.domainpricing}</a></li>
    <li><a href="configregistrars.php">{$_ADMINLANG.setup.registrars}</a></li>
    <li><a href="configservers.php">{$_ADMINLANG.setup.servers}</a></li>
    <li><a href="configcustomfieldsgroup.php">Product/Service Custom Fields</a></li>
  </ul>
  <span class="header"><img src="images/icons/support.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.support.title}</span>
  <ul class="menu">
    <li><a href="configticketdepartments.php">{$_ADMINLANG.setup.supportdepartments}</a></li>
    <li><a href="configticketstatuses.php">{$_ADMINLANG.setup.ticketstatuses}</a></li>
    <li><a href="configticketescalations.php">{$_ADMINLANG.setup.escalationrules}</a></li>
    <li><a href="configticketspamcontrol.php">{$_ADMINLANG.setup.spam}</a></li>
  </ul>
  <span class="header"><img src="images/icons/configother.png" class="absmiddle" width="16" height="16" /> {$_ADMINLANG.setup.other}</span>
  <ul class="menu">
    <li><a href="configcustomfields.php">{$_ADMINLANG.setup.customclientfields}</a></li>
    <li><a href="configorderstatuses.php">{$_ADMINLANG.setup.orderstatuses}</a></li>
    <li><a href="configsecurityqs.php">{$_ADMINLANG.setup.securityqs}</a></li>
    <li><a href="configbannedips.php">{$_ADMINLANG.setup.bannedips}</a></li>
    <li><a href="configbannedemails.php">{$_ADMINLANG.setup.bannedemails}</a></li>
    <li><a href="configbackups.php">{$_ADMINLANG.setup.backups}</a></li>
  </ul>
  {/if} <span class="header"><img src="images/icons/search.png" alt="" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.global.advancedsearch}</span>
  <div class="smallfont">
    <form method="get" action="search.php">
      <select name="type" id="searchtype" onchange="populate(this)">
        <option value="clients">Clients </option>
        <option value="orders">Orders </option>
        <option value="services">Services </option>
        <option value="domains">Domains </option>
        <option value="invoices">Invoices </option>
        <option value="tickets">Tickets </option>
      </select>
      <select name="field" id="searchfield">
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
      <input type="text" name="q" style="width:85%;" />
      <input type="submit" value="{$_ADMINLANG.global.search}" class="button" />
    </form>
  </div>
  <br />
  <span class="header"><img src="images/icons/admins.png" alt="" width="16" height="16" class="absmiddle" /> {$_ADMINLANG.global.staffonline}</span>
  <div class="smallfont">{$adminsonline}</div>
  <div class="controls"><a href="#" onclick="sidebarClose();return false">&laquo; Minimise Sidebar</a></div>
  
  <!-- END Sidebar--> 
</div>
<div class="contentarea" id="contentarea"{if !$minsidebar} style="margin-left:209px;"{/if}>
<div style="float:left;width:100%;">
<h1>{$pagetitle}</h1>