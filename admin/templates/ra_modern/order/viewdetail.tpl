
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
        <div class="box box-warning">
            <!-- /.box-header -->
            <div class="box-body">
                <div clas="row">
                    <div class="col-lg-6">
                        <table class="table" width="100%">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">Date</td>
                                    <td class="fieldarea">{$orderdata.date}</td>
                                </tr>
                                <tr>
                                    <td width="15%" class="fieldlabel">Order #</td>
                                    <td class="fieldarea">{$orderdata.ordernum} (ID: {$orderdata.id})</td>
                                </tr>
                                <tr>  
                                    <td class="fieldlabel">Client</td>
                                    <td class="fieldarea">
                                        <a href="clientssummary.php?userid={$orderdata.userid}"></a>
                                        <a href="clientssummary.php?userid={$orderdata.userid}">{$orderdata.firstname} {$orderdata.lastname}</a>
                                        <br>{$orderdata.address1} {$orderdata.address2}<br> {$orderdata.city}, {$orderdata.state}, {$orderdata.postcode}<br> {$orderdata.country}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Promotion Code</td>
                                    <td class="fieldarea">{$orderdata.promocode}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div  class="col-lg-6">
                        <table class="table" width="100%">
                            <tbody>
                                <tr>
                                    <td width="15%" class="fieldlabel">Payment Method</td>
                                    <td class="fieldarea">{$orderdata.paymentmethod}</td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Amount</td>
                                    <td class="fieldarea">${$orderdata.amount} NZD</td>
                                </tr>
                                <tr>

                                    <td class="fieldlabel">Invoice #</td>
                                    <td class="fieldarea"><a href="invoices.php?action=edit&amp;id={$orderdata.invoiceid}">{$orderdata.invoiceid}</a></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Status</td>
                                    <td class="fieldarea">
                                        <select class="form-control" id="ajaxchangeorderstatus" style="font-size:14px;">
                                            <option style="color:#cc0000" {if $orderdata.status eq "Pending"}Selected{/if}>Pending</option>
                                            <option style="color:#779500" {if $orderdata.status eq "Active"}Selected{/if}>Active</option>
                                            <option style="color:#888888" {if $orderdata.status eq "Cancelled"}Selected{/if}>Cancelled</option>
                                            <option style="color:#000000" {if $orderdata.status eq "Fraud"}Selected{/if}>Fraud</option>
                                            <option style="color:#f2d342" {if $orderdata.status eq "Draft"}Selected{/if}>Draft</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">IP Address</td>
                                    <td class="fieldarea">{$orderdata.ipaddress}- <a href="http://www.geoiptool.com/en/?IP={$orderdata.ipaddress}" target="_blank">Lookup</a> | <a href="orders.php?orderip={$orderdata.ipaddress}">Filter</a> | <a href="configbannedips.php?ip={$orderdata.ipaddress}&amp;reason=Banned due to Orders&amp;year=2020&amp;month=12&amp;day=31&amp;hour=23&amp;minutes=59&amp;token=14cb1db9b2dd40e27b77721390a501edb02b7f14">Ban</a></td></tr>
                                <tr>

                                    <td class="fieldlabel">Affiliate</td>
                                    <td class="fieldarea" id="affiliatefield">
                                        {if $affid}
                                            <a href=\"affiliates.php?action=edit&id={$affid.id}>
                                                {$affid.firstname} {$affid.lastname}</a>
                                            {else}
                                            None - <a href="#" id="showaffassign">Manual Assign</a>
                                        {/if}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {if $notes}
                        {$notes}
                    {/if}
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="box box-warning">
            <div class="box-header with-border"></div>

        </div>

    </section>
</div>
<script type="text/javascript>">
    {foreach from=$deletejs item=js}
        {$js}
    {/foreach}
</script>