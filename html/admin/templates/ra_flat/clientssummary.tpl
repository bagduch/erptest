{strip}
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="callout pull-right">
                    <div class="row">
                        <div id="exemptccetc">
                            {$_ADMINLANG.clientsummary.settingtaxexempt}:
                            <span id="taxstatus" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.taxstatus == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp;{$clientsdetails.taxstatus}
                                </strong>
                            </span> &nbsp;&nbsp; {$_ADMINLANG.clientsummary.settingautocc}:
                            <span id="autocc" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.autocc == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp; {$clientsdetails.autocc}
                                </strong>
                            </span> &nbsp;&nbsp; {$_ADMINLANG.clientsummary.settingreminders}:
                            <span id="overduenotices" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.overduenotices == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp;{$clientsdetails.overduenotices}
                                </strong>
                            </span> &nbsp;&nbsp; {$_ADMINLANG.clientsummary.settinglatefees}:
                            <span id="latefees" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.latefees == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp;{$clientsdetails.latefees}
                                </strong>
                            </span>&nbsp;&nbsp; {$_ADMINLANG.clientsummary.settingemail}:
                            <span id="email" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.email_notification == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp;{$clientsdetails.email_notification}
                                </strong>
                            </span>
                            &nbsp;&nbsp; {$_ADMINLANG.clientsummary.settingtxt}:
                            <span id="txt" class="csajaxtoggle" style="text-decoration:underline;cursor:pointer">
                                <strong class="{if $clientsdetails.txt_notification == "Yes"}textgreen{else}textred{/if}">
                                    &nbsp;{$clientsdetails.txt_notification}
                                </strong>
                            </span>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    {foreach from=$notes item=data} {if $data.flag && $adminid eq $data.assignto && $data.sticky eq '0'}
                            <div class="col-lg-3 col-xs-6">
                                <div class="alert alert-{$data.color} alert-dismissible">
                                    <form class="notesupdate{$data.id}" method="post" action="">
                                        <input type="hidden" name="noteid" value="{$data.id}">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="icon fa fa-warning"></i> Notes {$data.modified}</h4>
                                        <table class="table">
                                            <tr>
                                                <td colspa="2">{$data.name}: </td>
                                            </tr>
                                            <tr>
                                                <td colspa="2">
                                                    <textarea name="notesdata" class="form-control" style="color:black">{$data.note}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspa="2">
                                                    <input class="datepick form-control" name="updatetime" style="color:black;display: inline-block" type="text" value="{$data.duedate}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="done">
                                                            done
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="notesdone btn btn-default">Update</div>
                                                    <a style='color:black;margin-left:10px' class="btn btn-default" href="{$data.type}">View</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        {/if} {/foreach}
                    </div>
                </div>
            </div>
            {foreach from=$addons_html item=addon_html}
                <div style="margin-top:10px;">
                    {$addon_html}
                </div>
            {/foreach}
            <div class="row">
                <div class="col-md-12">
                    {$infobox}
                    <div id="clientsinformation" class="col-lg-3 col-sm-6">
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
                                    <tr>
                                        <td>{$_ADMINLANG.fields.city}</td>
                                        <td>{$clientsdetails.city}</td>
                                    </tr>
                                    <tr class="altrow">
                                        <td>{$_ADMINLANG.fields.dob}</td>
                                        <td>{$clientsdetails.dateofbirth}</td>
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
                                    <li>
                                        <a href="clientssummary.php?userid={$clientsdetails.userid}&resetpw=true&token={$csrfToken}"><img src="images/icons/resetpw.png" border="0" align="absmiddle" /> {$_ADMINLANG.clients.resetsendpassword}</a>
                                    <li>
                                        <a href="../dologin.php?username={$clientsdetails.email|urlencode}&token={$csrfToken}"><img src="images/icons/clientlogin.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.loginasclient}</a>
                                    <li>
                                        <a href="orders.php?clientid={$clientsdetails.userid}"><img src="images/icons/orders.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.vieworders}</a>
                                    <li>
                                        <a href="ordersadd.php?userid={$clientsdetails.userid}"><img src="images/icons/ordersadd.png" border="0" align="absmiddle" /> {$_ADMINLANG.orders.addnew}</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="invoicesbilling" class="col-lg-3 col-sm-6">
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
                                    <li>
                                        <a href="invoices.php?action=createinvoice&userid={$clientsdetails.userid}&token={$csrfToken}"><img src="images/icons/invoicesedit.png" border="0" align="absmiddle" /> {$_ADMINLANG.invoices.create}</a>
                                 {*   <li>
                                        <a href="#" onClick="showDialog('addfunds');
                                                return false"><img src="images/icons/addfunds.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.createaddfunds}</a>
                                    <li>
                                        <a href="#" onclick="demo.showSwal('generate-invoice')"><img src="images/icons/ticketspredefined.png" border="0" align="absmiddle" /> {$_ADMINLANG.invoices.geninvoices}</a>*}
                                    <li>
                                        <a href="#" onClick="openCCDetails();
                                                return false"><img src="images/icons/offlinecc.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.ccinfo}</a>
                                </ul>
                            </div>
                        </div>
                        <div id="otherinformation">
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
                    </div>

                    <div id="productsservices" class="col-lg-3 col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading panel-title">{$_ADMINLANG.services.title}</div>
                            <div class="panel-body">
                                <table class="clientssummarystats" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <td>{$_ADMINLANG.orders.service}</td>
                                        <td>{$stats.servicenumactive} ({$stats.servicenumtotal} Total)</td>
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

                        <div class="panel panel-primary">
                            <div class="panel-heading panel-title">{$_ADMINLANG.products.title}</div>
                            <div class="panel-body">
                                <table class="clientssummarystats" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <td>{$_ADMINLANG.orders.product}</td>
                                        <td>{$stats.productsnumactiveother} ({$stats.productsnumother} Total)</td>
                                    </tr>


                                    <tr>
                                        <td>{$_ADMINLANG.stats.affiliatesignups}</td>
                                        <td>{$stats.numaffiliatesignups}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="clientsfiles">
                            <div class="panel panel-primary">
                                <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.filesheading}</div>
                                <div class="panel-body">
                                    <table>
                                        {foreach key=num from=$files item=file}
                                            <tr>
                                                <td align="center">
                                                    <a href="../dl.php?type=f&id={$file.id}"><img src="../images/file.png" align="absmiddle" vspace="1" border="0" /> {$file.title}</a> {if $file.adminonly}({$_ADMINLANG.clientsummary.fileadminonly}){/if} <img src="images/icons/delete.png" align="absmiddle" border="0"
                                                                                                                                                                                                        onClick="deleteFile('{$file.id}')" /></td>
                                            </tr>
                                        {foreachelse}
                                            <tr>
                                                <td align="center">{$_ADMINLANG.clientsummary.nofiles}</td>
                                            </tr>
                                        {/foreach}
                                    </table>
                                    <ul>
                                        
                                    </ul>
                                    <form method="post" action="clientssummary.php?userid={$clientsdetails.userid}&action=uploadfile" enctype="multipart/form-data">
                                        <table>
                                            <tr>
                                                <td>{$_ADMINLANG.clientsummary.filetitle}</td>
                                                <td><input class="form-control" type="text" name="title" style="width:90%" /></td>
                                            </tr>
                                            <tr>
                                                <td>{$_ADMINLANG.clientsummary.filename}</td>
                                                <td><input class="form-control" type="file" name="uploadfile" style="width:90%" /></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="checkbox" name="adminonly" value="1" /> {$_ADMINLANG.clientsummary.fileadminonly} &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input class="btn btn-default" type="submit" value="{$_ADMINLANG.global.submit}" /></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="otheractions" class="col-lg-3 col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.actionsheading}</div>
                            <div class="panel-body">
                                <ul>
                                    {foreach from=$customactionlinks item=customactionlink}
                                        <li>{$customactionlink}</li>
                                        {/foreach}
                                    <li>
                                        <a data-toggle="modal" data-target="#addnotes" href="#"><img src="images/icons/ticketsopen.png" border="0" align="absmiddle" />Add Note</a>
                                    <li>
                                        <a href="reports.php?report=client_statement&userid={$clientsdetails.userid}"><img src="images/icons/reports.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.pdf}</a>
                                    <li>
                                        <a href="supporttickets.php?action=open&userid={$clientsdetails.userid}"><img src="images/icons/ticketsopen.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.newticket}</a>
                                    <li>
                                        <a href="supporttickets.php?view=any&client={$clientsdetails.userid}"><img src="images/icons/ticketsother.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.viewtickets}</a>
                                    <li>
                                        <a href="{if $affiliateid}affiliates.php?action=edit&id={$affiliateid}{else}clientssummary.php?userid={$clientsdetails.userid}&activateaffiliate=true&token={$csrfToken}{/if}"><img src="images/icons/affiliates.png" border="0" align="absmiddle" /> {if $affiliateid}{$_ADMINLANG.clientsummary.viewaffiliate}{else}{$_ADMINLANG.clientsummary.activateaffiliate}{/if}</a>
                                    <li>
                                        <a href="#" onClick="window.open('clientsmerge.php?userid={$clientsdetails.userid}', 'movewindow', 'width=500,height=280,top=100,left=100,scrollbars=1');
                                                return false"><img src="images/icons/clients.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.mergeclients}</a>
                                    <li>
                                        <a href="#" onClick="closeClient();
                                                return false" style="color:#000000;"><img src="images/icons/delete.png" border="0" align="absmiddle" /> {$_ADMINLANG.clientsummary.closeclient}</a>
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div id="addnotes" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="post" action="clientsnotes.php?sub=add">
                                        <input type="hidden" name="userid" value="{$clientsdetails.userid}">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Add Notes</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Notes</label>
                                                <textarea name="notes" class="form-control" rows="4"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Assign To</label>
                                                <select class="form-control" name="flag">
                                                    {foreach from=$adminlist item=row}
                                                        <option value="{$row.id}">{$row.firstname} {$row.lastname}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Due Date</label>
                                                <input class="datepick form-control" type="text" name="duedate">
                                            </div>
                                            <div class="form-group">

                                                <input type="checkbox" name="import" value="1"> <label>Important</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary addnotes">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <div id="sendclientemail" class="panel panel-primary">
                            <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.sendemailheading}</div>
                            <div class="panel-body">
                                <form action="clientsemails.php?userid={$clientsdetails.userid}&action=send&type=general" method="post">
                                    <input type="hidden" name="id" value="{$clientsdetails.userid}">
                                    <div align="center">
                                        {$messages}
                                        <input type="submit" value="{$_ADMINLANG.global.go}" class="button">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="sendclienttxt" class="panel panel-primary">
                            <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.sendtxtheading}</div>
                            <div class="panel-body">
                                <form action="clientstxt.php?userid={$clientsdetails.userid}&action=send&type=general" method="post">
                                    <input type="hidden" name="id" value="{$clientsdetails.userid}">
                                    <div align="center">{$messages}
                                        <input type="submit" value="{$_ADMINLANG.global.go}" class="button">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="clientsemails">
                            <div class="panel panel-primary">
                                <div class="panel-heading panel-title">{$_ADMINLANG.clientsummary.emailsheading}</div>
                                <div class="table-body">
                                    <table class="clientssummarystats" cellspacing="0" cellpadding="2">
                                        {foreach key=num from=$lastfivemail item=email}
                                            <tr class="{cycle values=" ,altrow "}">
                                                <td align="center">{$email.date} - <a href="#" onClick="window.open('clientsemails.php?&displaymessage=true&id={$email.id}', '', 'width=650,height=400,scrollbars=yes');
                                                        return false">{$email.subject}</a></td>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="content">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                            <li class="active">
                                <a href="#pill1" data-toggle="tab">Services</a>
                            </li>
                            <li>
                                <a href="#pill4" data-toggle="tab">Products</a>
                            </li>
                            <li>
                                <a href="#pill2" data-toggle="tab">Latest Five Notes</a>
                            </li>
                            <li>
                                <a href="#pill3" data-toggle="tab">Invoices</a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="pill1">
                        <form method="post" action="{$smarty.server.PHP_SELF}?userid={$clientsdetails.userid}&action=massaction">
                            <table id="servicetable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="20"><input type="checkbox" id="prodsall" /></th>
                                        <th data-field="10">{$_ADMINLANG.fields.id}</th>
                                        <th>{$_ADMINLANG.fields.product}</th>
                                        <th>{$_ADMINLANG.fields.amount}</th>
                                        <th>{$_ADMINLANG.fields.billingcycle}</th>
                                        <th>{$_ADMINLANG.fields.signupdate}</th>
                                        <th>{$_ADMINLANG.fields.nextduedate}</th>
                                        <th>{$_ADMINLANG.fields.status}</th>
                                        <th>Edit</th>
                                </thead>
                                </tr>
                                {foreach key=num from=$servicessummary item=product}
                                    <tr class="sui-row">
                                        <td class="sui-cell"><input type="checkbox" name="selproducts[]" value="{$product.id}" class="checkprods" /></td>
                                        <td class="sui-cell"><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$product.id}">{$product.id}</a></td>
                                        <td class="sui-cell">{$product.dpackage} - {$product.description}</td>
                                        <td class="sui-cell">{$product.amount}</td>
                                        <td class="sui-cell">{$product.dbillingcycle}</td>
                                        <td class="sui-cell">{$product.regdate}</td>
                                        <td class="sui-cell">{$product.nextduedate}</td>
                                        <td class="sui-cell">{$product.servicestatus}</td>
                                        <td class="sui-cell">
                                            <a class="btn btn-success" href="clientsservices.php?userid={$clientsdetails.userid}&id={$product.id}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                {foreachelse}
                                    <tr>
                                        <td colspan="9">{$_ADMINLANG.global.norecordsfound}</td>
                                    </tr>
                                {/foreach}
                            </table>
                        </form>
                    </div>
                    <div class="tab-pane" id="pill2">
                        {if $lastfivenotes}
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="sui-columnheader">
                                        <th>Create Date</th>
                                        <th>Notes</th>
                                        <th>Create Admin</th>
                                        <th>Assign to</th>
                                        <th>Due Date</th>
                                        <th>Update Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thrad>
                                    {foreach key=num from=$lastfivenotes item=item}
                                        <tr>
                                            <td>{$item.created}</td>
                                            <td>{$item.note }</td>
                                            <td>{$item.name}</td>
                                            <td>{$item.assignname}</td>
                                            <td>{$item.duedate}</td>
                                            <td>{$item.modified}</td>
                                            <td>{$item.sticky}</td>
                                            <td>
                                                <a href="clientsnotes.php?userid=8019&amp;action=edit&amp;id={$item.id}" class="btn btn-success editnotes"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <a href="#" onclick="doDelete('{$item.id}');
                                                        return false" class="btn btn-danger"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    {foreachelse}
                                        <tr>
                                            <td colspan="9">{$_ADMINLANG.global.norecordsfound}</td>
                                        </tr>
                                    {/foreach}
                            </table>
                        {/if}
                    </div>
                    <div class="tab-pane" id="pill3">
                        <div class="clientsinvoices"></div>
                    </div>
                    <div class="tab-pane" id="pill4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="sui-columnheader">
                                    <th width="10"><input type="checkbox" id="addonsall" /></th>
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
                                {if isset($productssummary)}
                                  {foreach key=num from=$productssummary item=addon}
                                      <tr>
                                          <td><input type="checkbox" name="seladdons[]" value="{$addon.id}" class="checkaddons" /></td>
                                          <td><a href="clientsservices.php?userid={$clientsdetails.userid}&id={$addon.serviceid}&aid={$addon.id}">{$addon.idshort}</a></td>
                                          <td style="padding-left:5px;padding-right:5px">{$addon.addonname}<br> {$addon.dpackage} - <a href="http://{$addon.domain}" target="_blank">{$addon.domain}</a></td>
                                          <td>{$addon.amount}</td>
                                          <td>{$addon.dbillingcycle}</td>
                                          <td>{$addon.regdate}</td>
                                          <td>{$addon.nextduedate}</td>
                                          <td>{$addon.status}</td>
                                          <td>
                                              <a href="clientsservices.php?userid={$clientsdetails.userid}&id={$addon.serviceid}&aid={$addon.id}"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>
                                          </td>
                                      </tr>
                                  {foreachelse}
                                      <tr>
                                          <td colspan="9">{$_ADMINLANG.global.norecordsfound}</td>
                                      </tr>
                                  {/foreach}
                                {/if}
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div id="addnotes" class="modal" tabindex="-1" role="dialog" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="clientsnotes.php?sub=add">
                        <input type="hidden" name="userid" value="{$clientsdetails.userid}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Notes</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Assign To</label>
                                <select class="form-control" name="flag">
                                    {foreach from=$adminlist item=row}
                                        <option value="{$row.id}">{$row.firstname} {$row.lastname}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Due Date</label>
                                <input class="datepick form-control" type="text" name="duedate">
                            </div>
                            <div class="form-group">

                                <input type="checkbox" name="import" value="1">  <label>Important</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary addnotes">Save changes</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {literal}
            <!-- DataTables -->

            <script type="text/javascript">

                $(document).ready(function () {
              
                    $("#prodsall").click(function () {
                        $(".checkprods").attr("checked", this.checked);
                    });
                    $("#addonsall").click(function () {
                        $(".checkaddons").attr("checked", this.checked);
                    });
                    $("#domainsall").click(function () {
                        $(".checkdomains").attr("checked", this.checked);
                    });
                });
                $.ajax({
                    method: "POST",
                    url: "clientsinvoices.php?userid={/literal}{$userid}{literal}",
                    data: {ajax: 1},
                }).then(function (data) {
                    $('.clientsinvoices').html(data);
                });
           
                $(".updatetime").click(function () {
                    $(this).closest('form').submit();
                });
                $(".notesdone").click(function (e) {
                    $("input[name='done']").val(1);
                    $(this).closest('form').submit();
                });
                $(".addnotes").click(function (e) {
                    e.preventDefault();
                    var token = $("input[name='token']").val();
                    var notes = $("textarea[name='notes']").val();
                    var assign = $("select[name='flag']").val();
                    var duedate = $("input[name='duedate']").val();
                    var imports = $("input[name='import']").val();
                    $.ajax({
                        url: "clientsnotes.php?sub=add",
                        method: "post",
                        data: {
                            "userid": {/literal}{$clientsdetails.userid}{literal},
                            "token": token,
                            "notes": notes,
                            "rel_type": "client",
                            "assign": assign,
                            "duedate": duedate,
                            "imports": imports
                        }
                    })
                            .then(function () {
                                $('#addnotes').modal('hide');
                                location.reload();
                            });
                });

            </script>

            {/literal}
                {/strip}
