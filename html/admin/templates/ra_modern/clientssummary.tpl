{strip}




{if $notes}
<div class="row">
    <div id="clientsnotes" class="col-lg-12">
        {foreach from=$notes item=note}
            <div class="panel panel-warning">
                <div class="panel-heading panel-title">
                    <tr>
                        <td>{$note.adminuser}</td>
                        <td align="right">{$note.modified}</td>
                    </tr>
                </div>
                <div class="panel-body"> {$note.note}
                    <div style="float:right;">
                        <a href="clientsnotes.php?userid={$clientsdetails.userid}&action=edit&id={$note.id}">
                            <img src="images/edit.gif" width="16" height="16" align="absmiddle" />
                        </a>
                    </div>
                </div>
            </div>
        {/foreach} 
    </div>
</div>
{/if}





<div class="row">
 <div id="exemptccetc" class="col-lg-12">
    {$_ADMINLANG.clientsummary.settingtaxexempt}: 
    <span id="taxstatus" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
        <strong class="{if $clientsdetails.taxstatus == "Yes"}textgreen{else}textred{/if}">
            {$clientsdetails.taxstatus}
        </strong>
    </span> 
    &nbsp;&nbsp;
    {$_ADMINLANG.clientsummary.settingautocc}: 
    <span id="autocc" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
        <strong class="{if $clientsdetails.autocc == "Yes"}textgreen{else}textred{/if}">
            {$clientsdetails.autocc}
        </strong>
    </span> 
    &nbsp;&nbsp;
    {$_ADMINLANG.clientsummary.settingreminders}: 
    <span id="overduenotices" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
        <strong class="{if $clientsdetails.overduenotices == "Yes"}textgreen{else}textred{/if}">
            {$clientsdetails.overduenotices}
        </strong>
    </span> 
    &nbsp;&nbsp;
    {$_ADMINLANG.clientsummary.settinglatefees}: 
    <span id="latefees" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
        <strong class="{if $clientsdetails.latefees == "Yes"}textgreen{else}textred{/if}">
            {$clientsdetails.latefees}
        </strong>
    </span> 
</div>
</div>




{foreach from=$addons_html item=addon_html}
    <div style="margin-top:10px;">
        {$addon_html}
    </div>
{/foreach}

  <div class="row">
    <div id="clientsinformation" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">
          {$_ADMINLANG.clientsummary.infoheading}
        </div>
        <div class="panel-body">
          <table class="clientssummarystats" cellspacing="0" cellpadding="2">
            <tr>
              <td width="110">{$_ADMINLANG.fields.firstname}</td>
              <td>{$clientsdetails.firstname}</td>
            </tr>
            <tr class="altrow">
              <td>{$_ADMINLANG.fields.lastname}</td>
              <td>{$clientsdetails.lastname}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.companyname}</td>
              <td>{$clientsdetails.companyname}</td>
            </tr>
            <tr class="altrow">
              <td>{$_ADMINLANG.fields.email}</td>
              <td>{$clientsdetails.email}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.address1}</td>
              <td>{$clientsdetails.address1}</td>
            </tr>
            <tr class="altrow">
              <td>{$_ADMINLANG.fields.address2}</td>
              <td>{$clientsdetails.address2}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.city}</td>
              <td>{$clientsdetails.city}</td>
            </tr>
            <tr class="altrow">
              <td>{$_ADMINLANG.fields.state}</td>
              <td>{$clientsdetails.state}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.postcode}</td>
              <td>{$clientsdetails.postcode}</td>
            </tr>
            <tr class="altrow">
              <td>{$_ADMINLANG.fields.country}</td>
              <td>{$clientsdetails.country} - {$clientsdetails.countrylong}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.phonenumber}</td>
              <td>{$clientsdetails.phonenumber}</td>
            </tr>
            <tr>
              <td>{$_ADMINLANG.fields.mobilenumber}</td>
              <td>{$clientsdetails.mobilenumber}</td>
            </tr>
          </table>
        <ul>
            <li><a href="clientssummary.php?userid={$clientsdetails.userid}&resetpw=true&token={$csrfToken}"><img src="images/icons/resetpw.png" border="0" align="absmiddle" /> {$_ADMINLANG.clients.resetsendpassword}</a>
            <li><a href="../dologin.php?username={$clientsdetails.email|urlencode}&token={$csrfToken}"><img src="images/icons/clientlogin.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.loginasclient}</a>
            <li><a href="orders.php?clientid={$clientsdetails.userid}"><img src="images/icons/orders.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.vieworders}</a>
            <li><a href="ordersadd.php?userid={$clientsdetails.userid}"><img src="images/icons/ordersadd.png" border="0" align="absmiddle" /> {$_ADMINLANG.orders.addnew}</a>
        </ul>
        </div>
        </div>
        </div>


        <div id="contactssubaccounts" class="col-lg-4">
<div class="panel panel-primary">
          <div class="panel-heading panel-title">
            {$_ADMINLANG.clientsummary.contactsheading}
          </div>
          <div class="panel-body">
            {foreach key=num from=$contacts item=contact}
            <tr class="{cycle values=",altrow"}">
              <td align="center"><a href="clientscontacts.php?userid={$clientsdetails.userid}&contactid={$contact.id}">{$contact.firstname} {$contact.lastname}</a> - {$contact.email}</td>
            </tr>
            {foreachelse}
            <tr>
              <td align="center">{$_ADMINLANG.clientsummary.nocontacts}</td>
            </tr>
            {/foreach}
          </div>
          <ul>
            <li><a href="clientscontacts.php?userid={$clientsdetails.userid}&contactid=addnew"><img src="images/icons/clientsadd.png" border="0" align="absmiddle" /> {$_ADMINLANG.clients.addcontact}</a>
          </ul>
        </div>
    </div>


    <div id="invoicesbilling" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.billingheading}</div>
        <div class="panel-body">
        <table class="clientssummarystats" cellspacing="0" cellpadding="2">
          <tr>
            <td width="110">{$_ADMINLANG.status.paid}</td>
            <td>{$stats.numpaidinvoices} ({$stats.paidinvoicesamount})</td>
          </tr>
          <tr class="altrow">
            <td>{$_ADMINLANG.status.unpaid}/{$_ADMINLANG.status.due}</td>
            <td>{$stats.numdueinvoices} ({$stats.dueinvoicesbalance})</td>
          </tr>
          <tr>
            <td>{$_ADMINLANG.status.cancelled}</td>
            <td>{$stats.numcancelledinvoices} ({$stats.cancelledinvoicesamount})</td>
          </tr>
          <tr class="altrow">
            <td>{$_ADMINLANG.status.refunded}</td>
            <td>{$stats.numrefundedinvoices} ({$stats.refundedinvoicesamount})</td>
          </tr>
          <tr>
            <td>{$_ADMINLANG.status.collections}</td>
            <td>{$stats.numcollectionsinvoices} ({$stats.collectionsinvoicesamount})</td>
          </tr>
          <tr class="altrow">
            <td><strong>{$_ADMINLANG.billing.income}</strong></td>
            <td><strong>{$stats.income}</strong></td>
          </tr>
          <tr>
            <td>{$_ADMINLANG.clients.creditbalance}</td>
            <td>{$stats.creditbalance}</td>
          </tr>
        </table>
        <ul>
          <li><a href="invoices.php?action=createinvoice&userid={$clientsdetails.userid}&token={$csrfToken}"><img src="images/icons/invoicesedit.png" border="0" align="absmiddle" /> {$_ADMINLANG.invoices.create}</a>
          <li><a href="#" onClick="showDialog('addfunds');return false"><img src="images/icons/addfunds.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.createaddfunds}</a>
          <li><a href="#" onClick="showDialog('geninvoices');return false"><img src="images/icons/ticketspredefined.png" border="0" align="absmiddle" /> {$_ADMINLANG.invoices.geninvoices}</a>
          <li><a href="clientsbillableitems.php?userid={$clientsdetails.userid}&action=manage"><img src="images/icons/billableitems.png" border="0" align="absmiddle" /> {$_ADMINLANG.billableitems.additem}</a>
          <li><a href="#" onClick="openCCDetails();return false"><img src="images/icons/offlinecc.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.ccinfo}</a>
        </ul>
      </div>
      </div>
      </div>




      <div id="otherinformation" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.otherinfoheading}</div>
        <div class="panel-body">
        <table class="clientssummarystats" cellspacing="0" cellpadding="2">
          <tr>
            <td width="110">{$_ADMINLANG.fields.status}</td>
            <td>{$clientsdetails.status}</td>
          </tr>
          <tr class="altrow">
            <td>{$_ADMINLANG.fields.clientgroup}</td>
            <td>{$clientgroup.name}</td>
          </tr>
          <tr>
            <td>{$_ADMINLANG.fields.signupdate}</td>
            <td>{$signupdate}</td>
          </tr>
          <tr class="altrow">
            <td>{$_ADMINLANG.clientsummary.clientfor}</td>
            <td>{$clientfor}</td>
          </tr>
          <tr>
            <td width="110">{$_ADMINLANG.clientsummary.lastlogin}</td>
            <td>{$lastlogin}</td>
          </tr>
        </table>
      </div>
    </div>
    </div>



    <div id="productsservices" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.services.title}</div>
          <div class="panel-body">
                <table class="clientssummarystats" cellspacing="0" cellpadding="2">
                  <tr>
                    <td>{$_ADMINLANG.orders.sharedhosting}</td>
                    <td>{$stats.productsnumactivehosting} ({$stats.productsnumhosting} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.orders.resellerhosting}</td>
                    <td>{$stats.productsnumactivereseller} ({$stats.productsnumreseller} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.orders.server}</td>
                    <td>{$stats.productsnumactiveservers} ({$stats.productsnumservers} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.orders.other}</td>
                    <td>{$stats.productsnumactiveother} ({$stats.productsnumother} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.domains.title}</td>
                    <td>{$stats.numactivedomains} ({$stats.numdomains} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.stats.acceptedquotes}</td>
                    <td>{$stats.numacceptedquotes} ({$stats.numquotes} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.support.supporttickets}</td>
                    <td>{$stats.numactivetickets} ({$stats.numtickets} Total)</td>
                  </tr>
                  <tr>
                    <td>{$_ADMINLANG.stats.affiliatesignups}</td>
                    <td>{$stats.numaffiliatesignups}</td>
                  </tr>
                </table>
          </div>
        </div>
        </div>



    <div id="clientsfiles" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.filesheading}</div>
        <div class="panel-body">
          <table>
              {foreach key=num from=$files item=file}
              <tr>
                <td align="center"><a href="../dl.php?type=f&id={$file.id}"><img src="../images/file.png" align="absmiddle" vspace="1" border="0" /> {$file.title}</a> {if $file.adminonly}({$_ADMINLANG.clientsummary.fileadminonly}){/if} <img src="images/icons/delete.png" align="absmiddle" border="0" onClick="deleteFile('{$file.id}')" /></td>
              </tr>
              {foreachelse}
              <tr>
                <td align="center">{$_ADMINLANG.clientsummary.nofiles}</td>
              </tr>
              {/foreach}
          </table>
          <ul>
            <li><a href="#" id="addfile"><img src="images/icons/add.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.fileadd}</a>
          </ul>
          <form method="post" action="clientssummary.php?userid={$clientsdetails.userid}&action=uploadfile">
            <table>
              <tr>
                <td>{$_ADMINLANG.clientsummary.filetitle}</td>
                <td><input type="text" name="title" style="width:90%" /></td>
              </tr>
              <tr>
                <td>{$_ADMINLANG.clientsummary.filename}</td>
                <td><input type="file" name="uploadfile" style="width:90%" /></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="checkbox" name="adminonly" value="1" />
                  {$_ADMINLANG.clientsummary.fileadminonly} &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="submit" value="{$_ADMINLANG.global.submit}" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      </div>


    <div id="clientsemails" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.emailsheading}</div>
        <div class="table-body">
        <table class="clientssummarystats" cellspacing="0" cellpadding="2">
          {foreach key=num from=$lastfivemail item=email}
          <tr class="{cycle values=",altrow"}">
            <td align="center">{$email.date} - <a href="#" onClick="window.open('clientsemails.php?&displaymessage=true&id={$email.id}','','width=650,height=400,scrollbars=yes');return false">{$email.subject}</a></td>
          </tr>
          {foreachelse}
          <tr>
            <td align="center">{$_ADMINLANG.clientsummary.noemails}</td>
          </tr>
          {/foreach}
        </table>
      </div>
    </div>
    </div>


    <div id="otheractions" class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.actionsheading}</div>
        <div class="panel-body">
        <ul>
          {foreach from=$customactionlinks item=customactionlink}
          <li>{$customactionlink}</li>
          {/foreach}
          <li><a href="clientsnotes.php?userid={$clientsdetails.userid}"><img src="images/icons/ticketsopen.png" border="0" align="absmiddle" />Add Note</a>
          <li><a href="reports.php?report=client_statement&userid={$clientsdetails.userid}"><img src="images/icons/reports.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.accountstatement}</a>
          <li><a href="supporttickets.php?action=open&userid={$clientsdetails.userid}"><img src="images/icons/ticketsopen.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.newticket}</a>
          <li><a href="supporttickets.php?view=any&client={$clientsdetails.userid}"><img src="images/icons/ticketsother.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.viewtickets}</a>
          <li><a href="{if $affiliateid}affiliates.php?action=edit&id={$affiliateid}{else}clientssummary.php?userid={$clientsdetails.userid}&activateaffiliate=true&token={$csrfToken}{/if}"><img src="images/icons/affiliates.png" border="0" align="absmiddle" /> {if $affiliateid}{$_ADMINLANG.clientsummary.viewaffiliate}{else}{$_ADMINLANG.clientsummary.activateaffiliate}{/if}</a>
          <li><a href="#" onClick="window.open('clientsmerge.php?userid={$clientsdetails.userid}','movewindow','width=500,height=280,top=100,left=100,scrollbars=1');return false"><img src="images/icons/clients.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.mergeclients}</a>
          <li><a href="#" onClick="closeClient();return false" style="color:#000000;"><img src="images/icons/delete.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.closeclient}</a>
          <li><a href="#" onClick="deleteClient();return false" style="color:#CC0000;"><img src="images/icons/delete.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.deleteclient}</a>
        </ul>
      </div>
      </div>




      <div id="sendclientemail" class="panel panel-primary">
        <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.sendemailheading}</div>
        <div class="panel-body">
        <form action="clientsemails.php?userid={$clientsdetails.userid}&action=send&type=general" method="post">
          <input type="hidden" name="id" value="{$clientsdetails.userid}">
          <div align="center">{$messages}
            <input type="submit" value="{$_ADMINLANG.global.go}" class="button">
          </div>
        </form>
      </div>
      </div>
    </div>

  </div>
<div class="row">

      <form method="post" action="{$smarty.server.PHP_SELF}?userid={$clientsdetails.userid}&action=massaction">
  
  {literal}<script language="javascript">
$(document).ready(function(){
    $("#prodsall").click(function () {
        $(".checkprods").attr("checked",this.checked);
    });
    $("#addonsall").click(function () {
        $(".checkaddons").attr("checked",this.checked);
    });
    $("#domainsall").click(function () {
        $(".checkdomains").attr("checked",this.checked);
    });
});
</script>{/literal}
<div class="clientsservices col-lg-12">
  <div class="panel panel-primary">
      <div class="panel-heading"><div class="panel-title">{$_ADMINLANG.services.title}</div></div>
      <div class="sui-grid sui-grid-core">
          <table class="sui-table sui-hover sui-selectable">
            <thead>
            <tr class="sui-columnheader">
              <th class="sui-headercell"><input type="checkbox" id="prodsall" />Select</th>
              <th class="sui-headercell" data-field="10">{$_ADMINLANG.fields.id}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.product}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.amount}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.billingcycle}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.signupdate}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.nextduedate}</th>
              <th class="sui-headercell">{$_ADMINLANG.fields.status}</th>
              <th class="sui-headercell">Edit</th>
            </thead>
            </tr>
            {foreach key=num from=$servicessummary item=product}
            <tr class="sui-row">
              <td class="sui-cell"><input type="checkbox" name="selproducts[]" value="{$product.id}" class="checkprods" /></td>
              <td class="sui-cell"><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$product.id}">{$product.idshort}</a></td>
              <td class="sui-cell">{$product.dpackage} - <a href="http://{$product.domain}" target="_blank">{$product.domain}</a></td>
              <td class="sui-cell">{$product.amount}</td>
              <td class="sui-cell">{$product.dbillingcycle}</td>
              <td class="sui-cell">{$product.regdate}</td>
              <td class="sui-cell">{$product.nextduedate}</td>
              <td class="sui-cell">{$product.domainstatus}</td>
              <td class="sui-cell"><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$product.id}"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
            </tr>
            {foreachelse}
            <tr>
              <td colspan="9">{$_ADMINLANG.global.norecordsfound}</td>
            </tr>
            {/foreach}
          </table>
        </div>
    </div>
    </div>
  </div>
  <div class="row">
<div class="clientsproducts col-lg-12">
<div class="panel panel-primary">
      <div class="panel-heading"><div class="panel-title">{$_ADMINLANG.products.title}</div></div>
      <div class="panel-body">
      <div class="sui-grid sui-grid-core">
        <table class="sui-table sui-hover sui-selectable">
        <thead>
          <tr class="sui-columnheader">
            <th width="20"><input type="checkbox" id="addonsall" /></th>
            <th>ID</th>
            <th>{$_ADMINLANG.addons.name}</th>
            <th>{$_ADMINLANG.fields.amount}</th>
            <th>{$_ADMINLANG.fields.billingcycle}</th>
            <th>{$_ADMINLANG.fields.signupdate}</th>
            <th>{$_ADMINLANG.fields.nextduedate}</th>
            <th>{$_ADMINLANG.fields.status}</th>
            <th width="20"></th>
          </tr>
        </thrad>
          {foreach key=num from=$productssummary item=addon}
          <tr>
            <td><input type="checkbox" name="seladdons[]" value="{$addon.id}" class="checkaddons" /></td>
            <td><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$addon.serviceid}&aid={$addon.id}">{$addon.idshort}</a></td>
            <td style="padding-left:5px;padding-right:5px">{$addon.addonname}<br>
              {$addon.dpackage} - <a href="http://{$addon.domain}" target="_blank">{$addon.domain}</a></td>
            <td>{$addon.amount}</td>
            <td>{$addon.dbillingcycle}</td>
            <td>{$addon.regdate}</td>
            <td>{$addon.nextduedate}</td>
            <td>{$addon.status}</td>
            <td><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$addon.serviceid}&aid={$addon.id}"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
          </tr>
          {foreachelse}
          <tr>
            <td colspan="9">{$_ADMINLANG.global.norecordsfound}</td>
          </tr>
          {/foreach}
        </table>
        </div>
    </div>
  </div>
</div>
  <img src="images/spacer.gif" width="1" height="4" /><br />
    </div>
  
    </div>
    </div>
</div>
</div>
<p align="center">
  <input type="button" value="{$_ADMINLANG.clientsummary.massupdateitems}" class="button" onclick="$('#massupdatebox').slideToggle()" />
<div id="massupdatebox" style="width:75%;background-color:#f7f7f7;border:1px dashed #cccccc;padding:10px;margin-left:auto;margin-right:auto;display:none;">
  <h2 style="text-align:center;margin:0 0 10px 0">{$_ADMINLANG.clientsummary.massupdateitems}</h2>
  <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
    <tr>
      <td width="15%" class="fieldlabel" nowrap>{$_ADMINLANG.fields.firstpaymentamount}</td>
      <td class="fieldarea"><input type="text" size="20" name="firstpaymentamount" /></td>
      <td width="15%" class="fieldlabel" nowrap>{$_ADMINLANG.fields.recurringamount}</td>
      <td class="fieldarea"><input type="text" size="20" name="recurringamount" /></td>
    </tr>
    <tr>
      <td class="fieldlabel" width="15%">{$_ADMINLANG.fields.nextduedate}</td>
      <td class="fieldarea"><input type="text" size="20" name="nextduedate" class="datepick" />
        &nbsp;&nbsp;
        <input type="checkbox" name="proratabill" id="proratabill" />
        <label for="proratabill">{$_ADMINLANG.clientsummary.createproratainvoice}</label></td>
      <td width="15%" class="fieldlabel">{$_ADMINLANG.fields.billingcycle}</td>
      <td class="fieldarea"><select name="billingcycle">
          <option value="">- {$_ADMINLANG.global.nochange} -</option>
          <option value="Free Account">{$_ADMINLANG.billingcycles.free}</option>
          <option value="One Time">{$_ADMINLANG.billingcycles.onetime}</option>
          <option value="Monthly">{$_ADMINLANG.billingcycles.monthly}</option>
          <option value="Quarterly">{$_ADMINLANG.billingcycles.quarterly}</option>
          <option value="Semi-Annually">{$_ADMINLANG.billingcycles.semiannually}</option>
          <option value="Annually">{$_ADMINLANG.billingcycles.annually}</option>
          <option value="Biennially">{$_ADMINLANG.billingcycles.biennially}</option>
          <option value="Triennially">{$_ADMINLANG.billingcycles.triennially}</option>
        </select></td>
    </tr>
    <tr>
      <td class="fieldlabel" width="15%">{$_ADMINLANG.fields.paymentmethod}</td>
      <td class="fieldarea">{$paymentmethoddropdown}</td>
      <td class="fieldlabel" width="15%">{$_ADMINLANG.fields.status}</td>
      <td class="fieldarea"><select name="status">
          <option value="">- {$_ADMINLANG.global.nochange} -</option>
          <option value="Pending">{$_ADMINLANG.status.pending}</option>
          <option value="Active">{$_ADMINLANG.status.active}</option>
          <option value="Suspended">{$_ADMINLANG.status.suspended}</option>
          <option value="Terminated">{$_ADMINLANG.status.terminated}</option>
          <option value="Cancelled">{$_ADMINLANG.status.cancelled}</option>
          <option value="Fraud">{$_ADMINLANG.status.fraud}</option>
        </select></td>
    </tr>
    <tr>
      <td class="fieldlabel" width="15%">{$_ADMINLANG.services.modulecommands}</td>
      <td class="fieldarea" colspan="3"><input type="submit" name="masscreate" value="{$_ADMINLANG.modulebuttons.create}" class="button" />
        <input type="submit" name="masssuspend" value="{$_ADMINLANG.modulebuttons.suspend}" class="button" />
        <input type="submit" name="massunsuspend" value="{$_ADMINLANG.modulebuttons.unsuspend}" class="button" />
        <input type="submit" name="massterminate" value="{$_ADMINLANG.modulebuttons.terminate}" class="button" />
        <input type="submit" name="masschangepackage" value="{$_ADMINLANG.modulebuttons.changepackage}" class="button" />
        <input type="submit" name="masschangepw" value="{$_ADMINLANG.modulebuttons.changepassword}" class="button" /></td>
    </tr>
    <tr>
      <td class="fieldlabel" width="15%">{$_ADMINLANG.services.overrideautosusp}</td>
      <td class="fieldarea" colspan="3"><input type="checkbox" name="overideautosuspend" id="overridesuspend" />
        <label for="overridesuspend">{$_ADMINLANG.services.nosuspenduntil}</label>
        <input type="text" name="overidesuspenduntil" class="datepick" /></td>
    </tr>
  </table>
  <br />
  <div align="center">
    <input type="submit" name="massupdate" value="{$_ADMINLANG.global.submit}" />
  </div>
</div>
</form>
</td>
</tr>
</table>
{/strip}