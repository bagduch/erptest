{if $maintenancemode}
<div class="errorbox" style="font-size:14px;"> {$_ADMINLANG.home.maintenancemode} </div>
<br />
{/if}

{$infobox}
<h3>{$_ADMINLANG.global.welcomeback} {$admin_username}!</h3>
{foreach from=$addons_html item=addon_html}
<div style="margin-bottom:15px;">{$addon_html}</div>
{/foreach}
<div class="homecolumn" id="homecol1">
  <div style="display:block; width:80%; border:1px solid #DDDDDD; height:150px; padding:1em; margin:1em; position:relative;
    -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
       -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;"> <strong>TODO ADD WIDGETS :) HERE! COL1</strong> {foreach from=$widgets item=widget}
    <div class="homewidget" id="{$widget.name}">
      <div class="widget-header">{$widget.title}</div>
      <div class="widget-content"> {$widget.content} </div>
    </div>
    {/foreach} </div>
</div>
<div class="homecolumn" id="homecol2">
  <div style="display:block; width:80%; border:1px solid #DDDDDD; height:150px; padding:1em; margin:1em; position:relative;
    -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
       -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;"> <strong>TODO ADD WIDGETS :) HERE! COL2</strong> </div>
</div>
{if $viewincometotals}
<div id="incometotals" style="float:right;position:relative;top:-35px;font-size:18px;"><a href="transactions.php"><img src="images/icons/transactions.png" align="absmiddle" border="0"> <b>{$_ADMINLANG.billing.income}</b></a> <img src="images/loading.gif" align="absmiddle" /> {$_ADMINLANG.global.loading}</div>
{/if}
<div style="clear:both;"></div>
<div id="geninvoices" title="{$_ADMINLANG.invoices.geninvoices}">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>{$_ADMINLANG.invoices.geninvoicessendemails}</p>
</div>
<div id="cccapture" title="{$_ADMINLANG.invoices.attemptcccaptures}">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>{$_ADMINLANG.invoices.attemptcccapturessure}</p>
</div>
