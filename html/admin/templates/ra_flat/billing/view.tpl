
<div class="card">
    <div class="header">
        <h3 class="title">Transaction Summary</h3>
    </div>
    <div class="content">
        {$sumtable}
    </div>


    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active" >
                <a href="#search" aria-controls="search" role="tab" data-toggle="tab">Search/Filter</a>
            </li>
            <li role="presentation">
                <a href="#addtransaction" aria-controls="addtransaction" role="tab" data-toggle="tab">Add Transaction</a>
            </li>
        </ul>


        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="search">
                <form method="post" action="{$PHP_SELF}">
                    <input type="hidden" name="filter" value="true">
                    <div class="col-lg-6">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td width="15%" class="fieldlabel">Show</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="show">
                                            {$showoption}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel" width="15%">Description</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="filterdescription" size="50" value="{$filterdescription}"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Transaction ID</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="filtertransid" size="30" value="{$filtertransid}"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Payment Method</td>
                                    <td class="fieldarea">
                                        {$payment}    
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-lg-6">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>

                                    <td width="15%" class="fieldlabel">Within</td>
                                    <td class="fieldarea"><select class="form-control" name="within">
                                            {$withinoption}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Start Date</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="startdate" size="20" value="{$startdate}" class="datepick"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">To Date</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="enddate" size="20" value="{$enddate}" class="datepick"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Amount</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="amount" size="15" value="{$amount}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <img src="images/spacer.gif" height="10" width="1"><br>
                    <div align="center"><input type="submit" value="Search/Filter" class="btn btn-default"></div>

                </form>

            </div>

            <div role="tabpanel" class="tab-pane" id="addtransaction">

                <form method="post" action="{$PHP_SELF}?action=add" name="calendarfrm">
                    <div class="col-lg-6">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td width="15%" class="fieldlabel">Date</td>
                                    <td class="fieldarea"><input class="form-control" type="text" size="20" name="date" value="16/11/2016" class="datepick form-control"></td>

                                </tr>
                                <tr>
                                    <td width="15%" class="fieldlabel">Related Client</td>
                                    <td class="fieldarea">
                                        {$client}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">Description</td>
                                    <td class="fieldarea"><input class="form-control" value="{$description}" type="text" name="description" size="50"></td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">Transaction ID</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="transid" size="30" value="{$transid}"></td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">Invoice ID(s)</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="invoiceids" size="20"> Comma Separated</td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">Payment Method</td>
                                    <td class="fieldarea">{$payment}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>

                                    <td width="15%" class="fieldlabel">Currency</td>
                                    <td class="fieldarea"><select class="form-control" name="currency">{$currencyoption}</select> (Non Client Only)</td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Amount In</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="amountin" size="10" value="0.00"></td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Fees</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="fees" size="10" value="0.00"></td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Amount Out</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="amountout" size="10" value="0.00"></td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Credit</td>
                                    <td class="fieldarea"><input type="checkbox" name="addcredit"> Add to Client's Credit Balance</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"></td>
                                    <td class="fieldarea"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
               
                    <div align="center"><input type="submit" value="Add Transaction" class="btn btn-default"></div>
                </form>

            </div>
        </div>

        <br>

        <div class="col-lg-12">
            {$table}
        </div>


        <div class="clearfix"></div>
    </div>
</div>