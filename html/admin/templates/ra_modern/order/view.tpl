<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manage Orders
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Orders</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-warning collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"> 
                    <button style="font-size: 20px;color:blue" type="button" class="btn btn-box-tool" data-widget="collapse">Search/Filter
                    </button>
                </h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div clas="row">
                    <form action="/admin/orders.php" method="post">
                        <div class="col-lg-6">
                            <table class="table" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%" class="fieldlabel">Order ID</td>
                                        <td class="fieldarea"><input class="form-control" type="text" name="orderid" size="8" value="{$filterdata.orders.orderid}"></td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Order #</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="ordernum" size="20" value="{$filterdata.orders.ordernum}">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Date</td>
                                        <td class="fieldarea"><input type="text" name="orderdate" value="{$filterdata.orders.orderdate}" class="form-control datepick"></td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Amount</td>
                                        <td class="fieldarea"><input class="form-control" type="text" name="amount" value="{$filterdata.orders.amount}" size="10"></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%" class="fieldlabel">Client</td>
                                        <td class="fieldarea">
                                            {$clientdropdown}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Payment Status</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="paymentstatus">
                                                <option {if $filterdata.orders.paymentstatus eq ""}selected{/if} value="">- Any -</option>
                                                <option {if $filterdata.orders.paymentstatus eq "Paid"}selected{/if} value="Paid">Paid</option>
                                                <option {if $filterdata.orders.paymentstatus eq "Unpaid"}selected{/if} value="Unpaid">Unpaid</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Status</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="status">
                                                <option {if $filterdata.orders.status eq ""}selected{/if} value="">- Any -</option>
                                                <option {if $filterdata.orders.status eq "Pending"}selected{/if} value="Pending" style="color:#cc0000">Pending</option>
                                                <option {if $filterdata.orders.status eq "Active"}selected{/if} value="Active" style="color:#779500">Active</option>
                                                <option {if $filterdata.orders.status eq "Cancelled"}selected{/if} value="Cancelled" style="color:#888888">Cancelled</option>
                                                <option {if $filterdata.orders.status eq "Fraud"}selected{/if} value="Fraud" style="color:#000000">Fraud</option>
                                                <option {if $filterdata.orders.status eq "Draft"}selected{/if} value="Draft" style="color:#f2d342">Draft</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">IP Address</td>
                                        <td class="fieldarea">
                                            <input class="form-control" type="text" name="orderip" value="{$filterdata.orders.orderip}" data-inputmask="'alias': 'ip'" data-mask>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <img src="images/spacer.gif" height="8" width="1"><br>
                        <div align="center"><input type="submit" value="Search" class="btn btn-default"></div>
                    </form>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        {$table}
    </section>
</div>
<script type="text/javascript>">
    {foreach from=$deletejs item=js}
            {$js}
    {/foreach}
</script>