<div class="card">
    <div class="header">
        <h3 class="title">Invoice Payment Monitor</h3>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="numbers">
                    <p>Monitoring Invoices</p>
                    2        
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="numbers">
                    <p>Paid Off</p>
                    2        
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="numbers">
                    <p>Suspended</p>
                    2        
                </div>
            </div>
        </div>
        <br>
        <div class="tablebg">
            <table id="sortabletbl0" class="datatable table table-condensed table-sm" width="100%" border="0" cellspacing="1" cellpadding="3">
                <tbody>
                    <tr>
                        <th>Customer</th>
                        <th>Company</th>
                        <th>Invoice ID</th>
                        <th>Balance</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>List of Payment Transactions</th>
                        <th>The Agreement</th>
                        <th>Suspend</th>
                        <th></th>
                    </tr>

                    {foreach from=$paymentplan item=data}
                        <tr>
                            <td>{$data.firstname} {$data.lastname}</td>
                            <td>{$data.companyname}</td>
                            <td>{$data.invoice_id}</td>
                            <td>{$data.balance}</td>
                            <td>{$data.email}</td>
                            <td>{$data.mobilenumber}</td>
                            <td> {foreach from=$data.transections item=trans}<li>{$trans.date} <span style="color:green">${$trans.amount}</span></li>{/foreach}</td>
                    <td>{$data.days}d to pay (amount {$data.amount})<br></td>
                    <td>{$data.suspension}hs to Suspend</td>
                    <td>
                        <a href="invoices.php?action=edit&id={$data.invoice_id}"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    </td>
                    </tr>
                {/foreach}

                </tbody>
            </table>
        </div>
    </div>
</div>
