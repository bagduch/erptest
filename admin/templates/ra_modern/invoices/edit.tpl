
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab0box" aria-controls="tab0box" role="tab" data-toggle="tab">Summary</a></li>
                <li role="presentation" class=""><a href="#tab1box" aria-controls="tab1box" role="tab" data-toggle="tab">Add Payment</a></li>
                <li role="presentation" class=""><a href="#tab2box" aria-controls="tab2box" role="tab" data-toggle="tab">Options</a></li>
                <li role="presentation" class=""><a href="#tab3box" aria-controls="tab3box" role="tab" data-toggle="tab">Credit</a></li>
                <li role="presentation" class=""><a href="#tab4box" aria-controls="tab4box" role="tab" data-toggle="tab">Refund</a></li>
                <li role="presentation" class=""><a href="#tab5box" aria-controls="tab5box" role="tab" data-toggle="tab">Notes</a></li>
                <li class="pull-right">
                    <button onclick="downloadPDF()" class="btn btn-default"><i class="fa fa-fw fa-download"></i> Download</button>
                </li>
                <li class="pull-right">
                    <button onclick="viewPDF()" class="btn btn-default"><i class="fa fa-fw fa-print"></i> Print</button>
                </li>
                <li class="pull-right">
                    <button onclick="printVersion()" class="btn btn-default"><i class="fa fa-fw fa-clipboard"></i> View As Client</button>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" id="tab0box" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                <tbody>
                                    <tr><td width="35%" >Client Name</td>
                                        <td class="grey-backgroud"><a href="clientssummary.php?userid={$invoice.userid}">{$invoice.firstname} {$invoice.lastname}</a> 
                                            (<a href="clientsinvoices.php?userid={$invoice.userid}">View Invoices</a>)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Invoice Date</td>
                                        <td class="grey-backgroud">{$invoice.date}</td>
                                    </tr>
                                    <tr>
                                        <td>Due Date</td>
                                        <td class="grey-backgroud">{$invoice.duedate}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Due</td>
                                        <td class="grey-backgroud">{$invoice.totalcurrency}</td>
                                    </tr>
                                    <tr>
                                        <td>Balance</td>
                                        <td class="grey-backgroud"><b><font color="#cc0000">{$balancecurrency}</font></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <span class="invoicestatus Unpaid">{$invoice.status}</span>
                            <br>Payment Method: <b>{$invoice.paymentmethod}</b>
                            <br><img src="images/spacer.gif" width="1" height="10"><br>
                            <form method="post" action="invoices.php?action=edit&id={$invoice.id}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" name="tplname">
                                            <option>Invoice Created</option>
                                            <option>Credit Card Invoice Created</option>
                                            <option>Invoice Payment Reminder</option>
                                            <option>First Invoice Overdue Notice</option>
                                            <option>Second Invoice Overdue Notice</option>
                                            <option>Third Invoice Overdue Notice</option>
                                            <option>Credit Card Payment Due</option>
                                            <option>Credit Card Payment Failed</option>
                                            <option>Invoice Payment Confirmation</option>
                                            <option>Credit Card Payment Confirmation</option>
                                            <option>Invoice Refund Confirmation</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="btn btn-default" type="submit" value="Send Email">
                                    </div>
                                </div>
                            </form>
                            <img src="images/spacer.gif" width="1" height="5"><br>

                            <button type="button" class="button btn btn-success" onclick="attemptpayment()" >Attempt Capture</button>
                            <button type="button" class="btn btn-default" onclick="markCancelled()" >Mark Cancelled</button>
                            <button type="button" class="btn btn-default" onclick="markUnpaid()" {if $invoice.status eq "Unpaid"}disabled{/if}>Mark Unpaid</button>
                            <br><img src="images/spacer.gif" width="1" height="5"><br>

                        </div>
                    </div>
                </div>
                <div  role="tabpanel" id="tab1box" class="tabbox tab-pane">
                    <form method="post" action="/admin/invoices.php">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="{$invoice.id}">
                        <input type="hidden" name="sub" value="markpaid">

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tr>
                                        <td width="20%" >Date</td>
                                        <td>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" name="date" value="{$invoice.date}" class="datepick form-control pull-right">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td >Payment Method</td>
                                        <td >
                                            {$paymentmethod}
                                        </td>
                                    </tr>
                                    <tr>   <td >Transaction ID</td>
                                        <td ><input type="text" class="form-control" name="transid" size="25"></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody>
                                        <tr>

                                            <td width="20%" >Amount</td>
                                            <td ><input type="text" class="form-control" name="amount" value="{$balance}" size="10"></td>
                                        </tr>
                                        <tr>

                                            <td >Transaction Fees</td>
                                            <td ><input type="text" class="form-control" name="fees" value="{$invoice.fees}" size="10"></td></tr>
                                        <tr>

                                            <td >Send Email</td>
                                            <td ><input type="checkbox" name="sendconfirmation" checked=""> Tick to Send Confirmation Email</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <img src="images/spacer.gif" width="1" height="10"><br>
                        <div align="center"><input type="submit" value="Add Payment" class="button btn btn-success"></div>
                    </form>


                </div>
                <div  role="tabpanel" id="tab2box" class="tabbox tab-pane">


                    <form method="post" action="/admin/invoices.php">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="saveoptions" value="true">
                        <input type="hidden" name="id" value="{$invoice.id}">

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tr>
                                        <td width="20%" >Invoice Date</td>
                                        <td ><input type="text" name="invoicedate" value="{$invoice.date}" class="datepick form-control"></td>
                                    </tr>
                                    <tr>  
                                        <td >Payment Method</td>
                                        <td >
                                            {$paymentmethod}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >Invoice #</td>
                                        <td ><input class="form-control" type="text" name="invoicenum" value="" size="12"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody>
                                        <tr>
                                            <td width="20%" >Due Date</td>
                                            <td ><input type="text" name="datedue" value="{$invoice.duedate}" class="datepick form-control"></td>
                                        </tr>
                                        <tr>

                                            <td >Tax Rate</td>
                                            <td >
                                                <div class="form-inline">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">1</div>
                                                        <input type="text" class="form-control" name="taxrate" value="0.00" size="6"><div class="input-group-addon">%</div>
                                                    </div>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">2</div>
                                                        <input type="text" class="form-control" name="taxrate2" value="0.00" size="6"><div class="input-group-addon">%</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td >Status</td>
                                            <td >
                                                <select class="form-control" name="status">
                                                    <option value="Unpaid" selected="">Unpaid</option>
                                                    <option value="Paid">Paid</option>
                                                    <option value="Cancelled">Cancelled</option>
                                                    <option value="Refunded">Refunded</option>
                                                    <option value="Collections">Collections</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <img src="images/spacer.gif" width="1" height="10"><br>
                        <div align="center"><button type="submit" class="btn btn-primary">Save Changes</button></div>
                    </form>


                </div>
                <div role="tabpanel" id="tab3box" class="tabbox tab-pane">
                

                    <table class="table" width="75%" align="center">
                        <tbody>
                            <tr>
                                <td width="50%" align="center"><b>Add Credit to Invoice</b></td>
                                <td align="center"><b>Remove Credit from Invoice</b></td>
                            </tr>
                            <tr>
                                <td align="center"><font color="#377D0D">$0.00 NZD Available</font></td>
                                <td align="center"><font color="#cc0000">$0.00 NZD Available</font></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <form method="post" action="/admin/invoices.php">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="{$invoice.id}">
                                        <input type="text" class="form-control" name="addcredit" value="0.00" size="8" disabled=""> 
                                        <input type="submit" value="Go" class="btn disabled" disabled="">
                                    </form>
                                </td>
                                <td align="center">
                                    <form method="post" action="/admin/invoices.php">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="{$invoice.id}">
                                        <input type="text" class="form-control" name="removecredit" value="0.00" size="8" disabled="">
                                        <input type="submit" value="Go" class="btn disabled" disabled="">
                                    </form>
                                </td></tr>
                        </tbody>
                    </table>



                </div>
                <div  role="tabpanel" id="tab4box" class="tabbox tab-pane">

                    <form method="post" action="/admin/invoices.php">
                        <input type="hidden" name="token" value="{$token}">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="{$invoice.id}">
                        <input type="hidden" name="sub" value="refund">

                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td width="20%" >Transactions</td><td ><select class="form-control" name="transid"><option value="">No Transactions Applied To This Invoice Yet</option></select></td></tr>
                                <tr><td >Amount</td><td ><input class="form-control" type="text" name="amount" size="15"> Leave blank for full refund</td></tr>
                                <tr><td >Refund Type</td><td ><select class="form-control" name="refundtype" id="refundtype" onchange="showrefundtransid();
                                        return false"><option value="sendtogateway">Refund through Gateway (If supported by module)</option><option value="" type="">Manual Refund Processed Externally</option><option value="addascredit">Add to Client's Credit Balance</option></select></td></tr>
                                <tr id="refundtransid" style="display:none;"><td >Transaction ID</td><td ><input type="text" name="refundtransid" size="25"></td></tr>
                                <tr><td >Send Email</td><td ><input type="checkbox" name="sendemail" checked=""> Tick to Send Confirmation Email</td></tr>
                            </tbody></table>
                        <img src="images/spacer.gif" width="1" height="10"><br>
                        <div align="center"><input type="submit" value="Refund" class="btn" disabled=""></div>
                    </form>


                </div>
                <div  role="tabpanel" id="tab5box" class="tabbox tab-pane">


                    <form method="post" action="/admin/invoices.php?save=notes">
                        <input type="hidden" name="token" value="{$token}">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="{$invoice.id}">
                        <textarea class="form-control" rows="4" style="width:100%" name="notes"></textarea><br>
                        <img src="images/spacer.gif" width="1" height="5"><br>
                        <div align="center"><input type="submit" value="Save Changes" class="button"></div>
                    </form>


                </div>
            </div>
        </div>


        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Invoice Items</h3>
                <div class="box-tools"></div>
            </div>
            <div class="box-body table-responsive no-padding">
                <form method="post" action="/admin/invoices.php">
                    <input type="hidden" name="token" value="{$token}">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="{$invoice.id}">
                    <input type="hidden" name="userid" value="12437">
                    <input type="hidden" name="sub" value="save">

                    <div class="tablebg">
                        <table class="table table-bordered" width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tbody>
                                <tr>
                                    <th width="50"></th>
                                    <th>Description</th>
                                    <th width="150">Amount</th>
                                    <th width="50">Taxed</th>
                                    <th width="50"></th>
                                </tr>

                                {foreach from=$details item=detail key=id}
                                    <tr>
                                        <td width="20" align="center">
                                            <input type="checkbox" class="flat-red" name="itemids[]" value="{$id}">
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="description[{$id}]" style="width:98%" rows="{$detail.linecount}">{$detail.description}</textarea>
                                        </td>
                                        <td align="center" nowrap="">
                                            <input class="form-control" type="text" name="amount[{$id}]" value="{$detail.amount}" size="10" style="text-align:center">
                                        </td>
                                        <td align="center"><input type="checkbox" class="flat-red" name="taxed[{$id}]" value="1" {if $detail.taxed}checked{/if}></td>
                                        <td width="20" align="center">
                                            <a href="#" onclick="doDelete('{$id}')">
                                                <img src="images/delete.gif" border="0">
                                            </a>
                                        </td>
                                    </tr>
                                {/foreach}

                                <tr>
                                    <td width="20"></td>
                                    <td><textarea class="form-control" name="adddescription" style="width:98%" rows="1"></textarea></td>
                                    <td align="center"><input class="form-control" type="text" name="addamount" size="10" style="text-align:center"></td>
                                    <td align="center"><input type="checkbox" name="addtaxed" value="1"></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right;background-color:#efefef;">
                                        <div align="left" style="width:60%;float:left;">
                                            <select class="form-control" name="selaction" onchange="this.form.submit()">
                                                <option>- With Selected -</option>
                                                <option value="split">Split to New Invoice</option>
                                                <option value="delete">Delete</option>
                                            </select>
                                        </div>
                                        <div style="width:25%;float:right;line-height:22px;"><strong>Sub Total:</strong>&nbsp;</div>
                                    </td>
                                    <td width="90" style="background-color:#efefef;"><strong>{$invoice.subtotalcurrency}</strong></td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right;background-color:#efefef;">Credit:&nbsp;</td>
                                    <td width="90" style="background-color:#efefef;">{$invoice.creditcurrency}</td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:right;">Total Due:&nbsp;</th>
                                    <th width="90">{$invoice.totalcurrency}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p align="center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="reset" class="btn btn-danger">Cancel Changes</button>
                    </p>
                </form>

            </div>

        </div>






        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Transactions</h3>
                <div class="box-tools"></div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Transaction Fees</th>
                            <th width="20"></th>
                        </tr>
                        {if !$transactions}
                            <tr><td colspan="6">No Records Found</td></tr>
                        {else}
                            {foreach from=$transactions item=transaction}
                            <td>{$transaction.date}</td>
                            <td>{$transaction.gateway}</td>
                            <td>{$transaction.id}</td>
                            <td>{$transaction.amountin}</td>
                            <td>{$transaction.fees}</td>
                            <a href="#" onclick="doDeleteTransaction({$transaction.id});"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

{literal}
    <script type="text/javascript">


        function printVersion()
        {
            window.open('../viewinvoice.php?id={/literal}{$invoice.id}{literal}', 'windowfrm', 'menubar=yes,toolbar=yes,scrollbars=yes,resizable=yes,width=750,height=600')
        }
        function viewPDF()
        {
            window.open('../dl.php?type=i&id={/literal}{$invoice.id}{literal}&viewpdf=1', 'pdfinv', '')
        }
        function downloadPDF()
        {
            window.location = "../dl.php?type=i&id={/literal}{$invoice.id}{literal}";

        }
        function markCancelled()
        {
            if (confirm("Are you sure you want to cancel this invoice?"))
            {
                window.location = "/admin/invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=statuscancelled&token={/literal}{$tokens}{literal}";
            }
        }
        function markUnpaid()
        {
            if (confirm("Are you sure you want to mark unpaid this invoice?"))
            {
                window.location = "/admin/invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=statusunpaid&token={/literal}{$tokens}{literal}";
            }

        }
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this invoice item?")) {
                window.location = '/admin/invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=delete&token={/literal}{$tokens}{literal}&iid=' + id;
            }
        }
        function doDeleteTransaction(id) {
            if (confirm("Are you sure you want to delete this transaction?")) {
                window.location = '/admin/invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=deletetrans&token={/literal}{$tokens}{literal}&ide=' + id;
            }
        }
        function attemptpayment() {
            if (confirm("Are you sure you want to attempt payment for this invoice?")) {
                window.location = '/admin/invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=attemptpayment&token={/literal}{$tokens}{literal}';
            }
        }
        function showrefundtransid() {
            var refundtype = $("#refundtype").val();
            if (refundtype != "") {
                $("#refundtransid").slideUp();
            } else {
                $("#refundtransid").slideDown();
            }
        }
    </script>
{/literal}