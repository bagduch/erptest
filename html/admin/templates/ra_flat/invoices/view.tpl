
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header-text">
                <h4 class="title">     {$topwrap}</h4>
            </div>


            <div class="content">

                <!-- Filter -->
                <button class="btn btn-default btn-box-tool" data-toggle="collapse" data-target="#search">Search/Filter</button>
                <div class="collapse" id ="search">
                    <form action="{$PHP_SELF}" method="post">

                        <table class="form table">
                            <tbody>
                                <tr>
                                    <td width="15%" class="fieldlabel">Client Name</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="clientname" size="25" value=""></td>
                                    <td width="15%" class="fieldlabel">Invoice Date</td>
                                    <td class="fieldarea"><input type="text"  name="invoicedate" size="15" value="" class="form-control datepick"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Line Item Description</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="lineitem" size="40" value=""></td>
                                    <td width="15%" class="fieldlabel">Due Date</td>
                                    <td class="fieldarea"><input type="text"  name="duedate" size="15" value="" class="form-control datepick"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Payment Method</td>
                                    <td class="fieldarea">
                                        {$paymentmethod}
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
                        <div class="card-footer text-center"><input type="submit" value="Search" class="btn btn-default"></div>
                    </form>
                </div>
            </div>
                <!-- Filter -->
                {$table}
        
        </div>
    </div>
</div>