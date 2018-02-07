<div class="row">
    <div class="col-xs-12">
        <div class="box box-warning collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"> 
                    <button style="font-size: 20px;color:blue" type="button" class="btn btn-box-tool" data-widget="collapse">Search/Filter
                    </button>
                </h3>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">
                <form action="/admin/invoices.php" method="post">
                    <table class="form table">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Client Name</td>
                                <td class="fieldarea"><input class="form-control" type="text" name="clientname" size="25" value=""></td>
                                <td width="15%" class="fieldlabel">Invoice Date</td>
                                <td class="fieldarea"><input type="text" name="invoicedate" size="15" value="" class="form-control datepick"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Line Item Description</td>
                                <td class="fieldarea"><input type="text" class="form-control" name="lineitem" size="40" value=""></td>
                                <td width="15%" class="fieldlabel">Due Date</td>
                                <td class="fieldarea"><input type="text" name="duedate" size="15" value="" class="form-control datepick"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Payment Method</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="paymentmethod"><option value="banktransfer">Bank Transfer</option><option value="mailin">Mail In Payment</option><option value="paystation">Credit Card (Visa / Master Card)</option></select>
                                </td>
                                <td width="15%" class="fieldlabel">Date Paid</td>
                                <td class="fieldarea"><input type="text" name="datepaid" size="15" value="" class="form-control datepick"></td>
                            </tr>
                            <tr><td class="fieldlabel">Status</td><td class="fieldarea">
                                    <select class="form-control" name="status">
                                        <option value="">- Any -</option>
                                        <option value="Draft">Draft</option>
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
                                    <div class="form-line">
                                        <div class="input-group">
                                            <div class="input-group-addon">From</div>
                                            <input class="form-control" type="text" name="totalfrom" size="10" value="">
                                            <div class="input-group-addon">To</div>
                                            <input class="form-control" type="text" name="totalto" size="10" value="">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr></tr>
                        </tbody>
                    </table>

                    <img src="images/spacer.gif" height="5" width="1"><br>
                    <div align="center"><input type="submit" value="Search" class="button"></div>

                </form>


            </div>
        </div>


    </div>
</div>