<div class="nav navbar-nav side-nav">

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
            "supporttickets.php" => "View Tickets",
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
            "supportannouncements.php" > "Announcements",
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
<div class="panel-group" id="accordion">
{foreach from=$accordion item=section key=sectionname}
  <div class="panel panel-default">
    {if $section.members|is_array}
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{$sectionname}">
          <span class="glyphicon glyphicon-{$section.glyphicon}">
              {$section.name}
          </span>
        </a>
      </h4>
    </div>
    {if $sidebar eq $sectionname}
    <div id="collapse{$sectionname}" class="panel-collapse collapse in" aria-expanded="true">
    {else}
    <div id="collapse{$sectionname}" class="panel-collapse collapse">
    {/if}
      <div class="panel-body">
        <table class="table">
        {foreach from=$section.members item=member key=url}
          <tr><td><a href="{$url}">{$member}</a></td></tr>
        {/foreach}
        </table>
      </div>
    </div>
    {else}
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <span class="glyphicon glyphicon-{$section.glyphicon}">
            <a href="{$section.url}">
              {$sectionname}
            </a>
          </span>
        </h4>
      </div>
    </div>
    {/if}
  </div>
{/foreach}
</div>

  <div><span> {$_ADMINLANG.global.advancedsearch}</span>
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
        <input type="text" name="q" autocomplete="off" style="width:85%;" />
        <input type="submit" value="{$_ADMINLANG.global.search}" class="button" />
      </form>
    </div>
  </div>
    <span><img src="images/icons/admins.png" alt="" width="16" height="16" /> {$_ADMINLANG.global.staffonline}</span>
    <div>{$adminsonline}</div>
</div>
