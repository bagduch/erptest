
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Edit Transaction</h3>
    </div>
    <div class="box-body">
        <form method="post" action="{$PHP_SELF}?action=save&amp;id=14" name="calendarfrm">

            <div class="col-lg-6">
                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">Related Client</td>
                            <td class="fieldarea">
                                {$client}
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Date</td>
                            <td class="fieldarea"><input type="text" size="12" name="date" value="{$date}" class="datepick form-control"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Payment Method</td>
                            <td class="fieldarea">
                                {$payment}
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Description</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="description" size="40" value="{$description}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Invoice ID</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="invoiceid" size="8" value="{$invoiceid}"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">Transaction ID</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="transid" size="20" value="{$transid}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Amount In</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="amountin" size="10" value="{$amountin}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Fees</td>
                            <td class="fieldarea">
                                <input class="form-control" type="text" name="fees" size="10" value="{$fees}">
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Amount Out</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="amountout" size="10" value="{$amountout}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel"><br></td>
                            <td class="fieldarea"></td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <p align="center"><input type="submit" value="Save Changes" class="button"></p>

        </form>
    </div>
</div>
