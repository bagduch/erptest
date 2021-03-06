<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="content">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab0box" aria-controls="tab0box" role="tab" data-toggle="tab">Summary</a></li>
                        <li role="presentation" class=""><a href="#tab5box" aria-controls="tab5box" role="tab" data-toggle="tab">Add Payment</a></li>
                        <li role="presentation" class=""><a href="#tab2box" aria-controls="tab2box" role="tab" data-toggle="tab">Options</a></li>
                        <li role="presentation" class=""><a href="#tab3box" aria-controls="tab3box" role="tab" data-toggle="tab">Credit</a></li>
                        <li role="presentation" class=""><a href="#tab4box" aria-controls="tab4box" role="tab" data-toggle="tab">Refund</a></li>
                        <li role="presentation" class=""><a href="#tab6box" aria-controls="tab5box" role="tab" data-toggle="tab">Payment Plan</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab0box" class="tab-pane active">
                            <table width="100%">
                                <tbody><tr><td width="50%">
                                            <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                                <tbody><tr><td width="35%" class="fieldlabel">Client Name</td>
                                                        <td class="fieldarea"><a href="clientssummary.php?userid={$invoice.userid}">{$invoice.firstname} {$invoice.lastname}</a> 
                                                            (<a href="clientsinvoices.php?userid={$invoice.userid}">View Invoices</a>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fieldlabel">Invoice Date</td>
                                                        <td class="fieldarea">{$invoice.date}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fieldlabel">Due Date</td>
                                                        <td class="fieldarea">{$invoice.duedate}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fieldlabel">Total Due</td>
                                                        <td class="fieldarea">{$invoice.totalcurrency}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fieldlabel">Balance</td>
                                                        <td class="fieldarea"><b><font color="#cc0000">{$balancecurrency}</font></b></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td><td align="center" width="50%">
                                            <span class="invoicestatus Unpaid">{$invoice.status}</span>
                                            <br>Payment Method: <b>{$invoice.paymentmethod}</b>
                                            <br><img src="images/spacer.gif" width="1" height="10"><br>
                                            <form method="post" action="invoices.php?action=edit&id={$invoice.id}">
                                                <select style="width: 80%" class="form-control" name="tplname">
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
                                                <img src="images/spacer.gif" width="1" height="5"><br>
                                                <input class="btn btn-default" type="submit" value="Send Email">
                                            </form>
                                            <img src="images/spacer.gif" width="1" height="5"><br>
                                            <input type="button" value="Attempt Capture" onclick="attemptpayment()" class="btn btn-default">
                                            <input type="button" value="Mark Cancelled" class="btn btn-default" onclick="markCancelled()" > 
                                            <input type="button" value="Mark Unpaid" class="btn btn-default" onclick="markUnpaid()" >
                                            <br><img src="images/spacer.gif" width="1" height="5"><br>
                                            <input type="button" value="Download PDF" class="btn btn-default" onclick="downloadPDF()">
                                            <input type="button" value="Download Late Fee" class="btn btn-default" onclick="downloadLate()">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" id="tab1box" class="tabbox tab-pane">
                            <form method="post" action="invoices.php">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="{$invoice.id}">
                                <input type="hidden" name="sub" value="markpaid">
                                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody>
                                        <tr>
                                            <td width="20%" class="fieldlabel">Date</td>
                                            <td class="fieldarea"><input type="text" name="date" value="{$invoice.date}" class="datepick form-control"></td>
                                            <td width="20%" class="fieldlabel">Amount</td>
                                            <td class="fieldarea"><input type="text" form-control name="amount" value="{$balance}" size="10"></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">Payment Method</td>
                                            <td class="fieldarea">
                                                {$paymentmethod}
                                            </td>
                                            <td class="fieldlabel">Transaction Fees</td>
                                            <td class="fieldarea"><input type="text" class="form-control" name="fees" value="{$invoice.fees}" size="10"></td></tr>
                                        <tr>
                                            <td class="fieldlabel">Transaction ID</td>
                                            <td class="fieldarea"><input type="text" class="form-control" name="transid" size="25"></td>
                                            <td class="fieldlabel">Send Email</td>
                                            <td class="fieldarea"><input type="checkbox" name="sendconfirmation" checked=""> Tick to Send Confirmation Email</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <img src="images/spacer.gif" width="1" height="10"><br>
                                <div align="center"><input type="submit" value="Add Payment" class="btn"></div>
                            </form>
                        </div>
                        <div  role="tabpanel" id="tab2box" class="tabbox tab-pane">
                            <form method="post" action="invoices.php">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="saveoptions" value="true">
                                <input type="hidden" name="id" value="{$invoice.id}">

                                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody>
                                        <tr>
                                            <td width="20%" class="fieldlabel">Invoice Date</td>
                                            <td class="fieldarea"><input type="text" name="invoicedate" value="{$invoice.date}" class="datepick form-control"></td>
                                            <td width="20%" class="fieldlabel">Due Date</td>
                                            <td class="fieldarea"><input type="text" name="datedue" value="{$invoice.duedate}" class="datepick form-control"></td>
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">Payment Method</td>
                                            <td class="fieldarea">
                                                {$paymentmethod}
                                            </td>
                                            <td class="fieldlabel">Tax Rate</td>
                                            <td class="fieldarea">
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
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">Invoice #</td>
                                            <td class="fieldarea"><input class="form-control" type="text" name="invoicenum" value="" size="12"></td>
                                            <td class="fieldlabel">Status</td>
                                            <td class="fieldarea">
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
                                <div align="center"><input type="submit" value="Save Changes" class="btn btn-default"></div>
                            </form>
                        </div>
                        <div  role="tabpanel" id="tab5box" class="tabbox tab-pane">
                            <form method="post" action="invoices.php">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="{$invoice.id}">
                                <input type="hidden" name="sub" value="markpaid">
                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody><tr>
                                            <td width="20%" class="fieldlabel">
                                                Date
                                            </td>
                                            <td class="fieldarea">
                                                <input type="text" name="date" value="" class="datepick form-control">
                                            </td>
                                            <td width="20%" class="fieldlabel">
                                                Amount
                                            </td>
                                            <td class="fieldarea">
                                                <div class="row">
                                                    <div class="col-xs-9 col-md-5">
                                                        <input type="text" name="amount" value="{$balance}" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">
                                                Payment Method
                                            </td>
                                            <td class="fieldarea">
                                                {$paymentmethod}
                                            </td>
                                            <td class="fieldlabel">
                                                Transaction Fees
                                            </td>
                                            <td class="fieldarea">
                                                <div class="row">
                                                    <div class="col-xs-9 col-md-5">
                                                        <input type="text" name="fees" value="0.00" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fieldlabel">
                                                Transaction ID
                                            </td>
                                            <td class="fieldarea">
                                                <div class="row">
                                                    <div class="col-xs-9">
                                                        <input type="text" name="transid" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fieldlabel">
                                                Send Email
                                            </td>
                                            <td class="fieldarea">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="sendconfirmation" checked="">
                                                    Tick to Send Confirmation Email
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                <img src="images/spacer.gif" width="1" height="10"><br>
                                <div align="center"><input type="submit" value="Save Changes" class="btn btn-default"></div>
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
                                            <form method="post" action="invoices.php">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="{$invoice.id}">
                                                <input type="text" class="form-control" name="addcredit" value="0.00" size="8" disabled=""> 
                                                <input type="submit" value="Go" class="btn disabled" disabled="">
                                            </form>
                                        </td>
                                        <td align="center">
                                            <form method="post" action="invoices.php">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="{$invoice.id}">
                                                <input type="text" class="form-control" name="removecredit" value="0.00" size="8" disabled="">
                                                <input type="submit" value="Go" class="btn disabled" disabled="">
                                            </form>
                                        </td></tr>
                                </tbody></table>



                        </div>
                        <div role="tabpanel" id="tab4box" class="tabbox tab-pane">
                            <form method="post" action="invoices.php">
                                <input type="hidden" name="token" value="{$token}">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="{$invoice.id}">
                                <input type="hidden" name="sub" value="refund">
                                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody><tr><td width="20%" class="fieldlabel">Transactions</td><td class="fieldarea"><select class="form-control" name="transid"><option value="">No Transactions Applied To This Invoice Yet</option></select></td></tr>
                                        <tr><td class="fieldlabel">Amount</td><td class="fieldarea"><input class="form-control" type="text" name="amount" size="15"> Leave blank for full refund</td></tr>
                                        <tr><td class="fieldlabel">Refund Type</td><td class="fieldarea"><select class="form-control" name="refundtype" id="refundtype" onchange="showrefundtransid();
                                                return false"><option value="sendtogateway">Refund through Gateway (If supported by module)</option><option value="" type="">Manual Refund Processed Externally</option><option value="addascredit">Add to Client's Credit Balance</option></select></td></tr>
                                        <tr id="refundtransid" style="display:none;"><td class="fieldlabel">Transaction ID</td><td class="fieldarea"><input type="text" name="refundtransid" size="25"></td></tr>
                                        <tr><td class="fieldlabel">Send Email</td><td class="fieldarea"><input type="checkbox" name="sendemail" checked=""> Tick to Send Confirmation Email</td></tr>
                                    </tbody></table>
                                <img src="images/spacer.gif" width="1" height="10"><br>
                                <div align="center"><input type="submit" value="Refund" class="btn" disabled=""></div>
                            </form>
                        </div>
                        <div role="tabpanel" id="tab5box" class="tabbox tab-pane">
                            <form method="post" action="invoices.php?save=notes">
                                <input type="hidden" name="token" value="{$token}">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="{$invoice.id}">
                                <textarea class="form-control" rows="4" style="width:100%" name="notes"></textarea><br>
                                <img src="images/spacer.gif" width="1" height="5"><br>
                                <div align="center"><input type="submit" value="Save Changes" class="button"></div>
                            </form>
                        </div>
                        <div role="tabpanel" id="tab6box" class="tabbox tab-pane">
                            <form method="post" action="invoicepaymentmonitor.php">
                                <input type="hidden" name="token" value="{$token}">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="newpaymentplan" value="{$newpaymentplan}">
                                <input type="hidden" name="id" value="{$invoice.id}">
                                <input type="hidden" name="date" value="{$invoice.date}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Days to Pay</label>
                                        <input id="daystopay" name="daystopay" type="text" class="form-control" value="{$paymentplan.days}">
                                        <label>Payment Type</label>
                                        <select id="paymenttype" name="paymenttype" class="form-control">
                                            <option {if $paymentplan.period eq 30}SELECTED{/if} value="monthly">Monthly</option>
                                            <option {if $paymentplan.period eq 7}SELECTED{/if}  value="weekly">Weekly</option>
                                            <option {if $paymentplan.period eq 14}SELECTED{/if}  value="fornightly">Fornightly</option>
                                        </select>
                                        <label>Suspension</label>
                                        <select name="suspension" class="form-control">
                                            <option {if $paymentplan.suspension eq 24}SELECTED{/if} value="24">24 Hour Suspend</option>
                                            <option {if $paymentplan.suspension eq 72}SELECTED{/if} value="72">72 Hour Suspend</option>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Amount to Pay</label>
                                        <input type="text" class="form-control" id="amounttopay" disabled>
                                        <label>First Payment Date</label>
                                        <input type="text" class="form-control" id="fday" disabled>
                                    </div>
                                </div>
                                <div align="center"><input type="submit" value="Apply" class="btn btn-default"></div>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="header card-header-text">
                <h4 class="title">Invoice Items</h4>
            </div>
            <div class="content">
                <form method="post" action="invoices.php">
                    <input type="hidden" name="token" value="{$token}">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="{$invoice.id}">
                    <input type="hidden" name="userid" value="{$invoice.userid}">
                    <input type="hidden" name="sub" value="save">
                    <div class="tablebg">
                        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tbody>
                                <tr>
                                    <th width="20"></th>
                                    <th>Description</th>
                                    <th width="100">Amount</th>
                                    <th width="50">Taxed</th>
                                    <th width="20"></th>
                                </tr>
                                {foreach from=$details item=detail key=id}
                                    <tr>
                                        <td width="20" align="center">
                                            <input type="checkbox" name="itemids[]" value="{$id}">
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="description[{$id}]" style="width:98%" rows="{$detail.linecount}">{$detail.description}</textarea>
                                        </td>
                                        <td align="center" nowrap="">
                                            <input class="form-control" type="text" name="amount[{$id}]" value="{$detail.amount}" size="10" style="text-align:center">
                                        </td>
                                        <td align="center"><input type="checkbox" name="taxed[{$id}]" value="1" {if $detail.taxed}checked{/if}></td>
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
                                        <div style="width:25%;float:right;line-height:35px;"><strong>Sub Total:</strong>&nbsp;</div>
                                    </td>
                                    <td width="90" style="background-color:#efefef;"><strong>{$invoice.subtotalcurrency}</strong></td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                    <td style="background-color:#efefef;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align:right;background-color:#efefef;">GST:&nbsp;</td>
                                    <td width="90" style="background-color:#efefef;">${$invoice.tax}</td>
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
                            </tbody></table>
                    </div>
                    <br>
                    <p align="center"><input type="submit" value="Save Changes" class="btn btn-default"> <input type="reset" value="Cancel Changes" class="btn btn-default"></p>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="header card-header-text">
                <h4 class="title">Transactions</h4>
            </div>
            <div class="content">
                <div class="tablebg">
                    <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
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
                                    <tr>
                                        <td>{$transaction.date}</td>
                                        <td>{$transaction.gateway}</td>
                                        <td>{$transaction.id}</td>
                                        <td>{$transaction.amountin}</td>
                                        <td>{$transaction.fees}</td>
                                        <td>
                                            <a href="#" onclick="doDeleteTransaction({$transaction.id});"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a>
                                        </td>
                                    </tr>
                                {/foreach}
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
{literal}
    <script type="text/javascript">

        $("#daystopay").keyup(caculatePayment);
        $("#paymenttype").change(caculatePayment);
        function caculatePayment()
        {
            paymenttype = $("#paymenttype").val();
            days = $("#daystopay").val();
            $.ajax({
                url: "invoicepaymentmonitor.php",
                method: "post",
                data: {
                    "action": "getpaymentplan",
                    "days": days,
                    "start": "{/literal}{$invoice.date}{literal}",
                    "balance":{/literal}{$balance}{literal},
                    "paymenttype": paymenttype
                }
            }).done(function (data) {
                $("#amounttopay").val(data.amount);
                $("#fday").val(data.start);
            });

        }
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
        function downloadLate()
        {
            window.location = "../dl.php?type=l&id={/literal}{$invoice.latefeeid}{literal}";

        }
        function markCancelled()
        {
            if (confirm("Are you sure you want to cancel this invoice?"))
            {
                window.location = "invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=statuscancelled&token={/literal}{$tokens}{literal}";
            }
        }
        function markUnpaid()
        {
            if (confirm("Are you sure you want to mark unpaid this invoice?"))
            {
                window.location = "invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=statusunpaid&token={/literal}{$tokens}{literal}";
            }

        }
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this invoice item?")) {
                window.location = 'invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=delete&token={/literal}{$tokens}{literal}&iid=' + id;
            }
        }
        function doDeleteTransaction(id) {
            if (confirm("Are you sure you want to delete this transaction?")) {
                window.location = 'invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=deletetrans&token={/literal}{$tokens}{literal}&ide=' + id;
            }
        }
        function attemptpayment() {
            if (confirm("Are you sure you want to attempt payment for this invoice?")) {
                window.location = 'invoices.php?action=edit&id={/literal}{$invoice.id}{literal}&sub=attemptpayment&token={/literal}{$tokens}{literal}';
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
