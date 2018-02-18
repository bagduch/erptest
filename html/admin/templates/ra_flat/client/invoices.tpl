
<div class="card">
    <div class="row">
        <div class="col-lg-12">
            <div class="header card-header-icon">
                <div class="row">
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Search
                    </button>


                    <div class="collapse" id="collapseExample">
                        <form action="clientsinvoices.php?userid={$userid}" method="post">
                            <table class="table borderless" width="100%" border="0" cellspacing="2" cellpadding="3">
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
                           
                            <div align="center">
                                <input type="submit" value="Search" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content">

                {$intable}


                {foreach from=$invoicedata item=data}
                    <div id="paymentadd{$data.id}" role="dialog" class="modal fade">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="post" action="clientsinvoices.php?userid={$userid}">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Add Payment to Invoice #{$data.id}</h4>
                                    </div>
                                    <input type="hidden" name="id" value="{$data.id}">
                                    <input type="hidden" name="addpayment" value="1">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label>Date</label>
                                            <input class="form-control datepick" type="text" value="{$data.date}">
                                        </div>
                                        <div class="form-group">
                                            <label>Transaction ID</label>
                                            <input type="text" class="form-control" name="transid" size="25">
                                        </div>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="form-control" name="amount" value="{$data.total}" size="10">
                                        </div>
                                        <div class="form-group">
                                            <label>Payment Method</label>
                                            {$paymentdropdown}
                                        </div>
                                        <div class="form-group">
                                            <label>Send Email</label>
                                            <input type="checkbox" name="sendconfirmation" checked=""> (Tick to Send Confirmation Email)
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button onclick="submit();" id="paymentadd" class="btn btn-success">Pay</button>
                                    </div>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>

</div>