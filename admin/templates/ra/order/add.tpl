
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> ben
=======
>>>>>>> staging/master
>>>>>>> ben
<<<<<<< HEAD
=======
        <form method="post" action="/admin/ordersadd.php" id="orderfrm">
            <input type="hidden" name="submitorder" value="true">
            <div class="box">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr>
                                    <td width="130" class="fieldlabel">Client</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="userid">
                                            <option value="2" style="background-color:#ffffff">Ben Customer (Simpsoon)</option>
                                            <option value="12442">Ben Simpson2</option>
                                            <option value="3">Guy Lowe (Lowe)</option>
                                            <option value="1" style="background-color:#ffffff">Test1 Customer (Company 1)</option>
                                            <option value="12437">yue zhang</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Payment Method</td><td class="fieldarea"><select class="form-control" name="paymentmethod"><option value="banktransfer">Bank Transfer</option><option value="mailin">Mail In Payment</option><option value="paystation">Paystation</option></select></td></tr>
                                <tr><td class="fieldlabel">Promotion Code</td><td class="fieldarea"><select name="promocode" id="promodd" onchange="updatesummary()"><option value="">None</option><optgroup label="Active Promotions"></optgroup><optgroup label="Expired Promotions"></optgroup></select> <a href="#" onclick="showDialog('createpromo');
                                        return false"><img src="images/icons/add.png" border="0" align="absmiddle"> Create Custom Promo</a></td></tr>
                                <tr><td class="fieldlabel">Order Status</td><td class="fieldarea"><select name="orderstatus">
                                            <option value="Pending">Pending</option>
                                            <option value="Active">Active</option>
                                        </select></td></tr>
                                <tr>
                                    <td width="130" class="fieldlabel"></td>
                                    <td class="fieldarea">
                                        <input type="checkbox" name="adminorderconf" id="adminorderconf" checked=""> 
                                        <label for="adminorderconf">Order Confirmation</label> 
                                        <input type="checkbox" name="admingenerateinvoice" id="admingenerateinvoice" checked="">
                                        <label for="admingenerateinvoice">Generate Invoice</label>
                                        <input type="checkbox" name="adminsendinvoice" id="adminsendinvoice" checked=""> 
                                        <label for="adminsendinvoice">Send Email</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="products">
                            <div id="ord0" class="product">

                                <p><b>Product/Service</b></p>

                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody><tr><td width="130" class="fieldlabel">Product/Service</td><td class="fieldarea"><select name="pid[]" id="pid0" onchange="loadproductoptions(this)"><option value="">None</option><optgroup label="Broadband"><option value="3">ADSL2</option><option value="6">VDSL2</option></optgroup><optgroup label="Products"><option value="7">Public Static IP</option></optgroup></select></td></tr>
                                        <tr><td class="fieldlabel"></td><td class="fieldarea"><input type="text" name="domain[]" size="40" id="domain0" onkeyup="updatesummary()"> <span id="whoisresult0"></span></td></tr>
                                        <tr><td class="fieldlabel">Billing Cycle</td><td class="fieldarea"><select class="form-control" name="billingcycle[]" onchange="updatesummary()"><option value="Free Account">Free</option><option value="One Time">One Time</option><option value="Monthly" selected="">Monthly</option><option value="Quarterly">Quarterly</option><option value="Semi-Annually">Semi-Annually</option><option value="Annually">Annually</option><option value="Biennially">Biennially</option><option value="Triennially">Triennially</option></select></td></tr>
                                        <tr id="addonsrow0" style="display:none;"><td class="fieldlabel">Addons</td><td class="fieldarea" id="addonscont0"></td></tr>
                                        <tr><td class="fieldlabel">Quantity</td><td class="fieldarea"><input type="text" name="qty[]" value="1" size="5" onkeyup="updatesummary()"></td></tr>
                                        <tr><td class="fieldlabel">Price Override</td><td class="fieldarea"><input type="text" name="priceoverride[]" size="10" onkeyup="updatesummary()"> (Only enter to manually override default product pricing)</td></tr>
                                    </tbody></table>

                                <div id="productconfigoptions0"></div>

                            </div>
                        </div>
                        <p style="padding-left:20px;"><a href="#" class="addproduct"><img src="images/icons/add.png" border="0" align="absmiddle"> Add Another Product</a></p>

                    </div>
                    <div class="col-md-4">
                        <table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td valign="top" class="ordersummaryleftcol table">

                                    </td><td valign="top">

                                        <div id="ordersumm"><div class="ordersummarytitle">Order Summary</div>
                                            <div id="ordersummary">
                                                <table>
                                                    <tbody><tr class="item"><td colspan="2"><div class="itemtitle" align="center">No Items Selected</div></td></tr><tr class="subtotal"><td>Subtotal</td><td class="alnright">$0.00 NZD</td></tr><tr class="tax"><td>GST @ 15.00%</td><td class="alnright">$0.00 NZD</td></tr><tr class="total"><td width="140">Total</td><td class="alnright">$0.00 NZD</td></tr></tbody></table>
                                            </div></div>

                                        <div class="ordersummarytitle"><input type="submit" value="Submit Order »" class="btn-primary" style="font-size:20px;padding:12px 30px ;"></div>

                                    </td></tr></tbody></table>
                    </div>
                </div>
            </div>
        </form>

        <script> updatesummary();</script>

        <div id="createpromo" title="Create Custom Promo" style="display:none;">
            <p></p><form id="createpromofrm">
                <input type="hidden" name="token" value="14cb1db9b2dd40e27b77721390a501edb02b7f14">
                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td class="fieldlabel" width="110">Promotion Code</td><td class="fieldarea"><input type="text" name="code" id="promocode"></td></tr>
                        <tr><td class="fieldlabel">Type</td><td class="fieldarea"><select name="type">
                                    <option value="Percentage">Percentage</option>
                                    <option value="Fixed Amount">Fixed Amount</option>
                                    <option value="Price Override">Price Override</option>
                                    <option value="Free Setup">Free Setup</option>
                                </select></td></tr>
                        <tr><td class="fieldlabel">Value</td><td class="fieldarea"><input type="text" name="pvalue" size="10"></td></tr>
                        <tr><td class="fieldlabel">Recurring</td><td class="fieldarea"><input type="checkbox" name="recurring" id="recurring" value="1"> <label for="recurring">Enable - Recur For</label> <input type="text" name="recurfor" size="3" value="0"> Times (0 = Unlimited)</td></tr>
                    </tbody></table>
                <p>* Promotional Discounts created "on the fly" here apply to all items in an order</p>
            </form><p></p>
        </div>

<script type="text/javascript>">
    {foreach from=$deletejs item=js}
        {$js}
    {/foreach}
</script>
=======
>>>>>>> ben
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ben
=======
>>>>>>> staging/master
>>>>>>> ben
=======
>>>>>>> staging/master
=======
=======
>>>>>>> ben
>>>>>>> staging/master
>>>>>>> ben
<form method="post" action="{$PHP_SELF}" id="orderfrm">
    <input type="hidden" name="submitorder" value="true">
    <div class="box">
        <div class="row">
            <div class="col-md-8">
                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr>
                            <td width="130" class="fieldlabel">Client</td>
                            <td class="fieldarea">
                                {$clientdrop}
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">Payment Method</td>
                            <td class="fieldarea">
                                {$paymentdrop}
                            </td>
                        </tr>
                        <tr><td class="fieldlabel">Promotion Code</td>
                            <td class="fieldarea">

                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" name="promocode" id="promodd" onchange="updatesummary()">
                                            <option value="">None</option>
                                            {if $activepromotion}
                                                <optgroup label="Active Promotions">
                                                    {$activepromotion}
                                                </optgroup>
                                            {/if}
                                            {if $expireprmotion}
                                                <optgroup label="Expired Promotions">
                                                    {$expireprmotion}
                                                </optgroup>
                                            {/if}
                                        </select> 
                                    </div>
                                    <div class="col-md-6">
                                        <a style="display: block;margin-top: 7px;" href="#" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Promotion Code
                                        </a>
                                    </div>
                                </div>

                            </td></tr>
                        <tr>
                            <td class="fieldlabel">Order Status</td><td class="fieldarea">
                                <select class="form-control" name="orderstatus">
                                    <option value="Pending">Pending</option>
                                    <option value="Active">Active</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="130" class="fieldlabel"></td>
                            <td class="fieldarea">
                                <input class="minimal" type="checkbox" name="adminorderconf" id="adminorderconf" checked=""> 
                                <label for="adminorderconf">Order Confirmation</label> 
                                <input class="minimal" type="checkbox" name="admingenerateinvoice" id="admingenerateinvoice" checked="">
                                <label for="admingenerateinvoice">Generate Invoice</label>
                                <input class="minimal" type="checkbox" name="adminsendinvoice" id="adminsendinvoice" checked=""> 
                                <label for="adminsendinvoice">Send Email</label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div id="products">
                    <div id="ord0" class="product">

                        <h2>Product/Service</h2>
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td width="130" class="fieldlabel">Product/Service</td><td class="fieldarea">
                                        <select class="form-control" name="pid[]" id="pid0" onchange="loadproductoptions(this)">
                                            {$productdrop}
                                        </select>
                                    </td></tr>
                                <tr><td class="fieldlabel">Description</td><td class="fieldarea"><input class="form-control" type="text" name="description[]" size="40" id="domain0" onkeyup="updatesummary()"> <span id="whoisresult0"></span></td></tr>
                                <tr>
                                    <td class="fieldlabel">Billing Cycle</td>
                                    <td class="fieldarea">
                                        {$cyclesDropDown}
                                    </td>
                                </tr>
                                <tr id="addonsrow0" style="display:none;"><td class="fieldlabel">Addons</td><td class="fieldarea" id="addonscont0"></td></tr>
                                <tr><td class="fieldlabel">Quantity</td>
                                    <td class="fieldarea">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input class="form-control" type="text" name="qty[]" value="1" size="5" onkeyup="updatesummary()">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Price Override</td><td class="fieldarea">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input class="form-control" type="text" name="priceoverride[]" size="10" onkeyup="updatesummary()"> 
                                            </div>
                                            <label style="margin-top: 5px">
                                                (Only enter to manually override default product pricing)
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody></table>

                        <div id="productconfigoptions0"></div>

                    </div>
                </div>
                <p style="padding-left:20px;"><a href="#" class="addproduct"><img src="images/icons/add.png" border="0" align="absmiddle"> Add Another Product</a></p>

            </div>
            <div class="col-md-4">
                <table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td valign="top" class="ordersummaryleftcol table">

                            </td><td valign="top">

                                <div id="ordersumm"><div class="ordersummarytitle">Order Summary</div>
                                    <div id="ordersummary">
                                        <table>
                                            <tbody><tr class="item"><td colspan="2"><div class="itemtitle" align="center">No Items Selected</div></td></tr><tr class="subtotal"><td>Subtotal</td><td class="alnright">$0.00 NZD</td></tr><tr class="tax"><td>GST @ 15.00%</td><td class="alnright">$0.00 NZD</td></tr><tr class="total"><td width="140">Total</td><td class="alnright">$0.00 NZD</td></tr></tbody></table>
                                    </div></div>

                                <div class="ordersummarytitle"><input type="submit" value="Submit Order »" class="btn-primary" style="font-size:20px;padding:12px 30px ;"></div>

                            </td></tr></tbody></table>
            </div>
        </div>
    </div>
</form>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createpromofrm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Create Custom Promo</h4>
                </div>
                <div class="modal-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel" width="110">Promotion Code</td>
                                <td class="fieldarea"><input class="form-control" type="text" name="code" id="promocode"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type">
                                        <option value="Percentage">Percentage</option>
                                        <option value="Fixed Amount">Fixed Amount</option>
                                        <option value="Price Override">Price Override</option>
                                        <option value="Free Setup">Free Setup</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Value</td>
                                <td class="fieldarea"><input class="form-control" type="text" name="pvalue"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Recurring</td>
                                <td class="fieldarea">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <input class="minimal" type="checkbox" name="recurring" id="recurring" value="1"> 
                                            <label for="recurring">Enable - Recur For</label> 
                                            <input class="form-control" type="text" name="recurfor" size="3" value="0"> Times (0 = Unlimited)
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody></table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div id="createpromo" title="Create Custom Promo" style="display:none;">
    <p></p><form id="createpromofrm">
        <input type="hidden" name="token" value="14cb1db9b2dd40e27b77721390a501edb02b7f14">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody><tr><td class="fieldlabel" width="110">Promotion Code</td><td class="fieldarea"><input class="form-control" type="text" name="code" id="promocode"></td></tr>
                <tr><td class="fieldlabel">Type</td><td class="fieldarea"><select name="type">
                            <option value="Percentage">Percentage</option>
                            <option value="Fixed Amount">Fixed Amount</option>
                            <option value="Price Override">Price Override</option>
                            <option value="Free Setup">Free Setup</option>
                        </select></td></tr>
                <tr><td class="fieldlabel">Value</td><td class="fieldarea"><input type="text" name="pvalue" size="10"></td></tr>
                <tr><td class="fieldlabel">Recurring</td><td class="fieldarea"><input type="checkbox" name="recurring" id="recurring" value="1"> <label for="recurring">Enable - Recur For</label> <input type="text" name="recurfor" size="3" value="0"> Times (0 = Unlimited)</td></tr>
            </tbody></table>
        <p>* Promotional Discounts created "on the fly" here apply to all items in an order</p>
    </form><p></p>
</div>
{literal}
    <script type="text/javascript">

        function loadproductoptions(piddd) {
            var ord = piddd.id.substring(3);
            var pid = piddd.value;
            var billingcycle = $("#billingcycle option:selected").val();
            if (pid == 0) {
                $("#productconfigoptions" + ord).html("");
                $("#addonsrow" + ord).hide();
                updatesummary();
            } else {
                $("#productconfigoptions" + ord).html("<p align=\"center\">Loading...<br><img src=\"../images/loading.gif\"></p>");
                $.ajax({
                    type: "POST",
                    url: "ordersadd.php",
                    dataType: "json",
                    data: {action: "getconfigoptions", pid: pid, cycle: billingcycle, orderid: ord, token: "{/literal}{$token}{literal}"},
                }).done(function (data) {
                    console.log(data);
                    if (data.addons) {
                        $("#addonsrow" + ord).show();
                        $("#addonscont" + ord).html(data.addons);
                    } else {
                        $("#addonsrow" + ord).hide();
                    }
                    $("#productconfigoptions" + ord).html(data.options);
                    updatesummary();

                });
            }
        }
        function loaddomainoptions(domrd, type) {
            var ord = domrd.id.substring(6);
            if (type == 1) {
                $("#domrowdn" + ord).css("display", "");
                $("#domrowrp" + ord).css("display", "");
                $("#domrowep" + ord).css("display", "none");
                $("#domrowad" + ord).css("display", "");
            } else if (type == 2) {
                $("#domrowdn" + ord).css("display", "");
                $("#domrowrp" + ord).css("display", "");
                $("#domrowep" + ord).css("display", "");
                $("#domrowad" + ord).css("display", "");
            } else {
                $("#domrowdn" + ord).css("display", "none");
                $("#domrowrp" + ord).css("display", "none");
                $("#domrowep" + ord).css("display", "none");
                $("#domrowad" + ord).css("display", "none");
            }
        }
        function updatesummary() {
            jQuery.post("ordersadd.php", "submitorder=1&calconly=1&" + jQuery("#orderfrm").serialize(),
                    function (data) {
                        jQuery("#ordersumm").html(data);
                    });
        }
        function savePromo() {
            jQuery.post("ordersadd.php", "action=createpromo&" + jQuery("#createpromofrm").serialize(),
                    function (data) {

                        if (data.substr(0, 1) == "<") {
                            $("#promodd").append(data);
                            $("#promodd").val($("#promocode").val());
                            $("#createpromo").dialog("close");
                        } else {
                            alert(data);
                        }
                    });
        }




    </script>
{/literal}
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ben
=======
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> staging/master
>>>>>>> ben
=======
<<<<<<< HEAD
=======
>>>>>>> ben
>>>>>>> staging/master
<<<<<<< HEAD
>>>>>>> ben
=======
>>>>>>> ben
=======
        <form method="post" action="/admin/ordersadd.php" id="orderfrm">
            <input type="hidden" name="submitorder" value="true">
            <div class="box">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr>
                                    <td width="130" class="fieldlabel">Client</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="userid">
                                            <option value="2" style="background-color:#ffffff">Ben Customer (Simpsoon)</option>
                                            <option value="12442">Ben Simpson2</option>
                                            <option value="3">Guy Lowe (Lowe)</option>
                                            <option value="1" style="background-color:#ffffff">Test1 Customer (Company 1)</option>
                                            <option value="12437">yue zhang</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Payment Method</td><td class="fieldarea"><select class="form-control" name="paymentmethod"><option value="banktransfer">Bank Transfer</option><option value="mailin">Mail In Payment</option><option value="paystation">Paystation</option></select></td></tr>
                                <tr><td class="fieldlabel">Promotion Code</td><td class="fieldarea"><select name="promocode" id="promodd" onchange="updatesummary()"><option value="">None</option><optgroup label="Active Promotions"></optgroup><optgroup label="Expired Promotions"></optgroup></select> <a href="#" onclick="showDialog('createpromo');
                                        return false"><img src="images/icons/add.png" border="0" align="absmiddle"> Create Custom Promo</a></td></tr>
                                <tr><td class="fieldlabel">Order Status</td><td class="fieldarea"><select name="orderstatus">
                                            <option value="Pending">Pending</option>
                                            <option value="Active">Active</option>
                                        </select></td></tr>
                                <tr>
                                    <td width="130" class="fieldlabel"></td>
                                    <td class="fieldarea">
                                        <input type="checkbox" name="adminorderconf" id="adminorderconf" checked=""> 
                                        <label for="adminorderconf">Order Confirmation</label> 
                                        <input type="checkbox" name="admingenerateinvoice" id="admingenerateinvoice" checked="">
                                        <label for="admingenerateinvoice">Generate Invoice</label>
                                        <input type="checkbox" name="adminsendinvoice" id="adminsendinvoice" checked=""> 
                                        <label for="adminsendinvoice">Send Email</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="products">
                            <div id="ord0" class="product">

                                <p><b>Product/Service</b></p>

                                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                    <tbody><tr><td width="130" class="fieldlabel">Product/Service</td><td class="fieldarea"><select name="pid[]" id="pid0" onchange="loadproductoptions(this)"><option value="">None</option><optgroup label="Broadband"><option value="3">ADSL2</option><option value="6">VDSL2</option></optgroup><optgroup label="Products"><option value="7">Public Static IP</option></optgroup></select></td></tr>
                                        <tr><td class="fieldlabel"></td><td class="fieldarea"><input type="text" name="domain[]" size="40" id="domain0" onkeyup="updatesummary()"> <span id="whoisresult0"></span></td></tr>
                                        <tr><td class="fieldlabel">Billing Cycle</td><td class="fieldarea"><select class="form-control" name="billingcycle[]" onchange="updatesummary()"><option value="Free Account">Free</option><option value="One Time">One Time</option><option value="Monthly" selected="">Monthly</option><option value="Quarterly">Quarterly</option><option value="Semi-Annually">Semi-Annually</option><option value="Annually">Annually</option><option value="Biennially">Biennially</option><option value="Triennially">Triennially</option></select></td></tr>
                                        <tr id="addonsrow0" style="display:none;"><td class="fieldlabel">Addons</td><td class="fieldarea" id="addonscont0"></td></tr>
                                        <tr><td class="fieldlabel">Quantity</td><td class="fieldarea"><input type="text" name="qty[]" value="1" size="5" onkeyup="updatesummary()"></td></tr>
                                        <tr><td class="fieldlabel">Price Override</td><td class="fieldarea"><input type="text" name="priceoverride[]" size="10" onkeyup="updatesummary()"> (Only enter to manually override default product pricing)</td></tr>
                                    </tbody></table>

                                <div id="productconfigoptions0"></div>

                            </div>
                        </div>
                        <p style="padding-left:20px;"><a href="#" class="addproduct"><img src="images/icons/add.png" border="0" align="absmiddle"> Add Another Product</a></p>

                    </div>
                    <div class="col-md-4">
                        <table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td valign="top" class="ordersummaryleftcol table">

                                    </td><td valign="top">

                                        <div id="ordersumm"><div class="ordersummarytitle">Order Summary</div>
                                            <div id="ordersummary">
                                                <table>
                                                    <tbody><tr class="item"><td colspan="2"><div class="itemtitle" align="center">No Items Selected</div></td></tr><tr class="subtotal"><td>Subtotal</td><td class="alnright">$0.00 NZD</td></tr><tr class="tax"><td>GST @ 15.00%</td><td class="alnright">$0.00 NZD</td></tr><tr class="total"><td width="140">Total</td><td class="alnright">$0.00 NZD</td></tr></tbody></table>
                                            </div></div>

                                        <div class="ordersummarytitle"><input type="submit" value="Submit Order »" class="btn-primary" style="font-size:20px;padding:12px 30px ;"></div>

                                    </td></tr></tbody></table>
                    </div>
                </div>
            </div>
        </form>

        <script> updatesummary();</script>

        <div id="createpromo" title="Create Custom Promo" style="display:none;">
            <p></p><form id="createpromofrm">
                <input type="hidden" name="token" value="14cb1db9b2dd40e27b77721390a501edb02b7f14">
                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td class="fieldlabel" width="110">Promotion Code</td><td class="fieldarea"><input type="text" name="code" id="promocode"></td></tr>
                        <tr><td class="fieldlabel">Type</td><td class="fieldarea"><select name="type">
                                    <option value="Percentage">Percentage</option>
                                    <option value="Fixed Amount">Fixed Amount</option>
                                    <option value="Price Override">Price Override</option>
                                    <option value="Free Setup">Free Setup</option>
                                </select></td></tr>
                        <tr><td class="fieldlabel">Value</td><td class="fieldarea"><input type="text" name="pvalue" size="10"></td></tr>
                        <tr><td class="fieldlabel">Recurring</td><td class="fieldarea"><input type="checkbox" name="recurring" id="recurring" value="1"> <label for="recurring">Enable - Recur For</label> <input type="text" name="recurfor" size="3" value="0"> Times (0 = Unlimited)</td></tr>
                    </tbody></table>
                <p>* Promotional Discounts created "on the fly" here apply to all items in an order</p>
            </form><p></p>
        </div>

<script type="text/javascript>">
    {foreach from=$deletejs item=js}
        {$js}
    {/foreach}
</script>
>>>>>>> ben
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> staging/master
>>>>>>> ben
<<<<<<< HEAD
=======
=======
>>>>>>> staging/master
>>>>>>> ben
=======
=======
>>>>>>> staging/master
>>>>>>> ben
=======
>>>>>>> ben
>>>>>>> staging/master
<<<<<<< HEAD
>>>>>>> ben
=======
>>>>>>> ben
