<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
{debug}
=======

>>>>>>> ben
=======

=======
{debug}
>>>>>>> staging/master
=======
{debug}
=======

>>>>>>> ben
>>>>>>> ben
<table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
    <tbody>
        <tr>
            <td class="fieldlabel">Date</td>
            <td class="fieldarea">{$orderdata.date}</td>
            <td width="15%" class="fieldlabel">Payment Method</td>
            <td class="fieldarea">{$orderdata.paymentmethod}</td>
        </tr>
        <tr>
            <td width="15%" class="fieldlabel">Order #</td>
            <td class="fieldarea">{$orderdata.ordernum} (ID: {$orderdata.id})</td>
            <td class="fieldlabel">Amount</td>
            <td class="fieldarea">{$orderdata.amount}</td>
        </tr>
        <tr>
            <td class="fieldlabel" rowspan="3" valign="top">Client</td>
            <td class="fieldarea" rowspan="3" valign="top">
                <a href="clientssummary.php?userid={$orderdata.userid}"></a>
                <a href="clientssummary.php?userid={$orderdata.userid}">{$orderdata.firstname} {$orderdata.lastname}</a>
                <br>{$orderdata.address1}{if $orderdata.address2 != ""}<br>{$orderdata.address2}{/if}<br>{$orderdata.city}, {$orderdata.state}, {$orderdata.postcode}<br>{$orderdata.country}</td>
            <td class="fieldlabel">Invoice #</td>
            <td class="fieldarea"><a href="invoices.php?action=edit&amp;id={$orderdata.invoiceid}">{$orderdata.invoiceid}</a></td>
        </tr>
        <tr>
            <td class="fieldlabel">Status</td>
            <td class="fieldarea">
                {$statusoptions}
            </td></tr>
        <tr><td class="fieldlabel">IP Address</td>
            <td class="fieldarea">{$orderdata.ipaddress} - <a href="http://www.geoiptool.com/en/?IP={$orderdata.ipaddress}" target="_blank">Lookup</a> 
                | <a href="orders.php?orderip={$orderdata.ipaddress}">Filter</a> 
                | <a href="configbannedips.php?ip={$orderdata.ipaddress}&amp;reason=Banned due to Orders&amp;year=2020&amp;month=12&amp;day=31&amp;hour=23&amp;minutes=59&amp;token={$token}">Ban</a>
            </td>
        </tr>
        <tr>
            <td class="fieldlabel">Promotion Code</td>
            <td class="fieldarea">{$promocodetext}</td>
            <td class="fieldlabel">Affiliate</td>
            <td class="fieldarea" id="affiliatefield">None - <a href="#" id="showaffassign">Manual Assign</a></td>
        </tr>
    </tbody></table>

<div id="togglenotesbtnholder" style="float:right;margin:10px;"><input type="button" value="Add Notes" id="togglenotesbtn"></div>

<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title"> 
            <input type="button" value="Add Notes" class="btn btn-default" data-widget="collapse">
            </button>
        </h3>
        <!-- /.box-tools -->
    </div>
    <div class="box-body">
        <p>
            <b>Notes / Additional Information</b>
        </p>
        <textarea  class="form-control" id="notes"></textarea>
        <br>
        <input type="button" value="Update/Save" id="savenotesbtn">
    </div>
</div>

{$notes}
<p><b>Order Items</b></p>

<form method="post" action="whois.php" target="_blank" id="frmWhois">
    <input type="hidden" name="domain" value="" id="frmWhoisDomain">
</form>

<form method="post" action="/admin/orders.php?action=view&amp;id=154&amp;activate=true">
    <div class="tablebg">
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
=======
        <table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
>>>>>>> ben
=======
        <table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
=======
        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
>>>>>>> staging/master
=======
        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
=======
        <table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
>>>>>>> ben
>>>>>>> ben
            <tbody>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Billing Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                </tr>
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
                <tr>
                    <td align="center">
                        <a href="clientsservices.php?userid=12437&amp;id=135"><b></b></a>
                    </td>
                    <td>Broadband - ADSL2<br></td>
                    <td>Monthly</td>
                    <td>$118.00 NZD</td>
                    <td>Pending</td>
                    <td><b>
                            {$paymentstatus}</b></td></tr><tr><td style="background-color:#EFF2F9;text-align:center;" colspan="6"><label><input type="checkbox" name="vars[products][135][sendwelcome]" checked=""> Send Welcome Email</label></td></tr><tr><th colspan="3" style="text-align:right;">Total Due:&nbsp;</th><th>$0.00 NZD</th><th colspan="2"></th></tr>
=======
>>>>>>> ben
=======
>>>>>>> ben
                {foreach from=$tblcustomerservices item=data}
                    <tr>
                        <td align="center">
                            <a href="clientsservices.php?userid={$data.userid}&amp;id={$data.id}"><b>{$data.id}</b></a>
                        </td>
                        <td>{$data.services.groupname} - {$data.services.name}<br>{$data.description}</td>
                        <td>{$data.billingcycle}</td>
                        <td>{$data.firstpaymentamount}</td>
                        <td>{$data.servicestatus}</td>
                        <td><b> {$paymentstatus}</b></td>
                    </tr>
                {/foreach}
                <tr>
                    <td style="background-color:#EFF2F9;text-align:center;" colspan="6">
                        <label><input type="checkbox" name="vars[products][{$data.id}][sendwelcome]" checked=""> Send Welcome Email</label></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align:right;">Total Due:&nbsp;</th>
                    <th>$0.00 NZD</th>
                    <th colspan="2"></th>
                </tr>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ben
=======
                <tr>
                    <td align="center">
                        <a href="clientsservices.php?userid=12437&amp;id=135"><b></b></a>
                    </td>
                    <td>Broadband - ADSL2<br></td>
                    <td>Monthly</td>
                    <td>$118.00 NZD</td>
                    <td>Pending</td>
                    <td><b>
                            {$paymentstatus}</b></td></tr><tr><td style="background-color:#EFF2F9;text-align:center;" colspan="6"><label><input type="checkbox" name="vars[products][135][sendwelcome]" checked=""> Send Welcome Email</label></td></tr><tr><th colspan="3" style="text-align:right;">Total Due:&nbsp;</th><th>$0.00 NZD</th><th colspan="2"></th></tr>
>>>>>>> ben
<<<<<<< HEAD
=======
>>>>>>> staging/master
>>>>>>> ben
=======
>>>>>>> ben
            </tbody></table>
    </div>

    <br>

    <table align="center">
        <tbody>
            <tr>
                <td><input type="submit" value="Accept Order" class="btn btn-success"></td>
                <td><input type="button" value="Cancel Order" onclick="cancelOrder()" class="btn"></td>
                <td><input type="button" value="Cancel &amp; Refund" onclick="cancelRefundOrder()" class="btn"></td>
                <td><input type="button" value="Set as Fraud" onclick="fraudOrder()" class="btn"></td>
                <td><input type="button" value="Set Back to Pending" onclick="pendingOrder()" class="btn"></td>
                <td><input type="button" value="Delete Order" onclick="deleteOrder()" class="btn" style="color:#cc0000;"></td>
            </tr>
        </tbody>
    </table>

    <div id="notesholder" style="display:none"><p><b>Notes / Additional Information</b></p><p align="center"></p><table align="center" cellspacing="0" cellpadding="0"><tbody><tr><td><textarea rows="4" cols="100" id="notes"></textarea></td><td>&nbsp;&nbsp; <input type="button" value="Update/Save" id="savenotesbtn"></td></tr></tbody></table><p></p></div>
</form>

<div id="affassign" title="Assign to Affiliate" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>Loading...</p>
</div>

{literal}

    <script type="text/javascript">
        function cancelOrder() {
            if (confirm("Are you sure you want to cancel this order? This will also run module termination for any active products/services."))
                window.location = "/admin/orders.php?action=view&id=154&cancel=true&token=8bd8b7c27bcec896877c8df715e34fa5e97e2ad5";
        }
        function cancelRefundOrder() {
            if (confirm("Are you sure you want to cancel & refund this order? This will also run module termination for any active products/services."))
                window.location = "/admin/orders.php?action=view&id=154&cancelrefund=true&token=8bd8b7c27bcec896877c8df715e34fa5e97e2ad5";
        }
        function fraudOrder() {
            if (confirm("Are you sure you want to cancel this order? This will also run module termination for any active products/services."))
                window.location = "/admin/orders.php?action=view&id=154&fraud=true&token=8bd8b7c27bcec896877c8df715e34fa5e97e2ad5";
        }
        function pendingOrder() {
            if (confirm("Are you sure you want to set this order back to Pending?"))
                window.location = "/admin/orders.php?action=view&id=154&pending=true&token=8bd8b7c27bcec896877c8df715e34fa5e97e2ad5";
        }
        function deleteOrder() {
            if (confirm("Are you sure you want to delete this order? This will delete all related products/services & invoice."))
                window.location = "/admin/orders.php?action=delete&id=154&token=8bd8b7c27bcec896877c8df715e34fa5e97e2ad5";
        }
        function showDialog(name) {
            $("#" + name).dialog('open');
        }

        $("#savenotesbtn").click(function () {
            $.post("?actionn=view&id={/literal}{$orderid}{literal}", {updatenotes:true, notes:$("#notes".val(), token:"")});
        });
    </script>
{/literal}
