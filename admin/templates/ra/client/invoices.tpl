
<form action="/admin/clientsinvoices.php?userid=12437" method="post">
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
        <input type="button" value="Create Invoice" class="button" onclick="window.location = 'invoices.php?action=createinvoice&amp;userid=12437&amp;token=cf9d4a51d2e2a6743cc16e0bbd838895c410067c'">
    </div>
</form>
{$intable}
