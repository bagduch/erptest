
<div class="row">
    <div class="col-lg-12">
        <button style="margin-bottom: 15px;" class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Search
        </button>
    </div>
</div>
<div class="collapse" id="collapseExample">
    <form action="/admin/clientsinvoices.php?userid={$userid}" method="post">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody>
                <tr>
                    <td width="15%" class="fieldlabel">Invoice #</td>
                    <td class="fieldarea"><input class="form-control" type="text" name="invoicenum" size="25" value=""></td>
                    <td width="15%" class="fieldlabel">Invoice Date</td>
                    <td class="fieldarea"><input class="form-control" type="text" name="invoicedate" size="15" value="" class="datepick"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Line Item Description</td>
                    <td class="fieldarea"><input class="form-control" type="text" name="lineitem" size="40" value=""></td>
                    <td width="15%" class="fieldlabel">Due Date</td>
                    <td class="fieldarea"><input class="form-control" type="text" name="duedate" size="15" value="" class="datepick"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Payment Method</td>
                    <td class="fieldarea">
                        {$paymentdropdown}
                    </td>
                    <td width="15%" class="fieldlabel">Date Paid</td>
                    <td class="fieldarea"><input class="form-control" type="text" name="datepaid" size="15" value="" class="datepick"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Status</td>
                    <td class="fieldarea">
                        <select class="form-control" name="status">
                            <option value="">- Any -</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Overdue">Overdue</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Refunded">Refunded</option>
                            <option value="Collections">Collections</option>
                        </select>
                    </td>
                    <td class="fieldlabel">Total Due</td>
                    <td class="fieldarea">
                        <div class="form-inline">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    From
                                </div>
                                <input class="form-control" type="text" name="totalfrom" size="10" value="">
                            </div>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    To
                                </div>
                                <input class="form-control pull-right" type="text" name="totalto" size="10" value="">
                            </div>
                        </div>

                    </td>
                </tr>
                <tr></tr>
            </tbody></table>
        <img src="images/spacer.gif" height="5" width="1"><br>
        <div align="center">
            <input type="submit" value="Search" class="button">
        </div>
    </form>
</div>
{$intable}
{foreach from=$invoicedata item=data}
    <div id="paymentadd{$data.id}" class="box collapse">
        <form method="post" action="/admin/clientsinvoices.php?userid={$userid}">
            <div class="box-header">
                <h3 class="box-title">Add Payment to Invoice #{$data.id}</h3>
            </div>
            <input type="hidden" name="id" value="{$data.id}">
            <input type="hidden" name="addpayment" value="1">
            <div class="box-body">
                <table class="form table">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">Date</td>
                            <td class="fieldarea"><input type="text" name="date" value="{$data.date}" class="datepick form-control"></td>
                            <td class="fieldlabel">Amount</td>
                            <td class="fieldarea"><input type="text" form-control="" name="amount" value="{$data.total}" size="10"></td>
                            <td class="fieldlabel">Payment Method</td>
                            <td class="fieldarea">
                                {$paymentdropdown}
                            </td>
                            <td class="fieldlabel">Transaction Fees</td>
                            <td class="fieldarea"><input type="text" class="form-control" name="fees" value="" size="10"></td>
                            <td class="fieldlabel">Transaction ID</td>
                            <td class="fieldarea"><input type="text" class="form-control" name="transid" size="25"></td>
                            <td class="fieldlabel">Send Email</td>
                            <td class="fieldarea"><input type="checkbox" name="sendconfirmation" checked=""> Tick to Send Confirmation Email</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <p align="center">
                    <button class="btn btn-danger" data-toggle="collapse" data-target="#paymentadd{$data.id}">Cancel</button>
                    <button onclick="submit();" id="paymentadd" class="btn btn-success">Pay</button>
                </p
            </div>

    </div>
</form>
</div>
{/foreach}
{literal}
    <script type="text/javascript">

    </script>
{/literal}