
<div class="col-sm-8">
    <div class="card">
        <div class="content">
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
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="header card-header-text">
            <h4 class="title">Order Items</h4>
        </div>

        <div class="content">
            <form method="post" action="{$PHP_SELF}?action=view&amp;id={$orderdata.id}&amp;activate=true">
                <div class="tablebg">
                    <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                        <tbody>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Billing Cycle</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                            </tr>
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
                                <th colspan="3" style="text-align:right;">Total Due:&nbsp;</th>
                                <th>{$orderdata.amount}</th>
                                <th colspan="2"></th>
                            </tr>
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
                        </div>

</div>

                                    
                                    
</div>
                                    
                                    <div class="col-sm-4">
                                         
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <form action="?action=addnotes" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Notes / Additional Information</h4>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" name="account" value="{$orderdata.id}">
                                <input type="hidden" name="rel_type" value="order">

                                <textarea class="form-control" name="notes" id="note"></textarea>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-default" value="Update/Save" id="savenotesbtn">
                            </div>
                    </form>
                </div>
            </div>
        </div>
                                        <ul class="timeline timeline-simple">
                                           <li class="timeline-inverted">
                                                 <div class="timeline-body">
                                                <button style="display: block;float: right" type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
                                                             Add Notes
                                                </button>
                                                 </div>
                                            </li>
    {foreach from=$tabledata item=data}
                                <li class="timeline-inverted">
                                  
                                    <div class="timeline-panel">
                                   
                                        <div class="timeline-body">
                                              <span class="label label-danger">{$data[3]}</span>
                                            <p>{$data[2]}</p>
                                        </div>
                                        <h6>
                                            <i class="ti-time"></i> {$data[1]}
                                        </h6>
                                    </div>
                                </li>
    {/foreach}
                              
                              
                            </ul>
                        </div>
                                        
                             
{literal}
    <script type="text/javascript">
        function cancelOrder() {
            if (confirm("Are you sure you want to cancel this order? This will also run module termination for any active products/services."))
                window.location = "{/literal}{$PHP_SELF}{literal}?action=view&id={/literal}{$orderdata.id}{literal}&cancel=true&token={/literal}{$token}{literal}";
                    }
                    function cancelRefundOrder() {
                        if (confirm("Are you sure you want to cancel & refund this order? This will also run module termination for any active products/services."))
                            window.location = "{/literal}{$PHP_SELF}{literal}?action=view&id={/literal}{$orderdata.id}{literal}&cancelrefund=true&token={/literal}{$token}{literal}";
                                }
                                function fraudOrder() {
                                    if (confirm("Are you sure you want to cancel this order? This will also run module termination for any active products/services."))
                                        window.location = "{/literal}{$PHP_SELF}{literal}?action=view&id={/literal}{$orderdata.id}{literal}&fraud=true&token={/literal}{$token}{literal}";
                                            }
                                            function pendingOrder() {
                                                if (confirm("Are you sure you want to set this order back to Pending?"))
                                                    window.location = "{/literal}{$PHP_SELF}{literal}?action=view&id={/literal}{$orderdata.id}{literal}&pending=true&token={/literal}{$token}{literal}";
                                                        }
                                                        function deleteOrder() {
                                                            if (confirm("Are you sure you want to delete this order? This will delete all related products/services & invoice."))
                                                                window.location = "{/literal}{$PHP_SELF}{literal}?action=delete&id={/literal}{$orderdata.id}{literal}&token={/literal}{$token}{literal}";
                                                                    }
                                                                    function showDialog(name) {
                                                                        $("#" + name).dialog('open');
                                                                    }




                                                                    $("#savenotesbtn").click(function () {
                                                                        console.log("ok");
                                                                        $.ajax({
                                                                            url: "clientsnotes.php?sub=add",
                                                                            method: "post",
                                                                            data: {
                                                                                account:{/literal}{$orderdata.id}{literal},
                                                                                token: "{/literal}{$token}{literal}",
                                                                                note: $("#notes").val(),
                                                                                type: "order",
                                                                            }

                                                                        }).done(function () {
                                                                            location.reload();
                                                                        });

                                                                    });

</script>
{/literal}
