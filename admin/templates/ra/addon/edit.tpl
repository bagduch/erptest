

<form method="post" action="/admin/configaddons.php?action=save&amp;id=1">
    <input type="hidden" name="token" value="526b26301bb68fce470779db889e9f2dd30aede6">
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody>
            <tr>
                <td class="fieldlabel" width="20%">Name</td>
                <td class="fieldarea">
                    <div class="col-lg-4">
                        <input class="form-control" type="text" name="name" size="40" value="{$addon.name}">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">Description</td>
                <td class="fieldarea">
                     <div class="col-lg-4">
                    <textarea class="form-control" name="description" cols="60" rows="3">{$addon.description}</textarea>
                     </div>
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">Billing Cycle</td>
                <td class="fieldarea">
                    <label><input type="radio" name="paytype" {if $addon.billingcycle eq 'free'}checked{/if} value="free"> Free</label>
                    <label><input type="radio" name="paytype" {if $addon.billingcycle eq 'onetime'}checked{/if} value="onetime"> One Time</label>
                    <label><input type="radio" name="paytype" {if $addon.billingcycle eq 'recurring'}checked{/if} value="recurring"> Recurring</label>
                </td>
            </tr>
            <tr class="tableprice"><td class="fieldlabel">Pricing</td><td class="fieldarea"><br>
                    {*<table cellspacing="1" bgcolor="#cccccc">
                    <tbody>
                    <tr bgcolor="#efefef" style="text-align:center;font-weight:bold">
                    <td width="100"></td>
                    <td width="100">NZD</td>
                    </tr>
                    <tr bgcolor="#ffffff" style="text-align:center">
                    <td bgcolor="#efefef"><b>Setup Fee</b></td>
                    <td><input type="text" name="currency[1][msetupfee]" size="10" value="0.00"></td>
                    </tr>
                    <tr bgcolor="#ffffff" style="text-align:center">
                    <td bgcolor="#efefef"><b>Recurring</b></td>
                    <td><input type="text" name="currency[1][monthly]" size="10" value="45.00"></td>
                    </tr>
                    </tbody>
                    </table>*}
            <tr class="tableprice">
                <td colspan="2" align="left"><br>
                    <table cellspacing="1" bgcolor="#cccccc">
                        <tbody>
                            <tr bgcolor="#efefef" style="text-align:center;font-weight:bold">
                                <td class="onetime" width="80">Currency</td>
                                <td class="onetime" width="80"></td>
                                <td class="onetime" width="120">One Time/Monthly</td>
                                <td width="90">Quarterly</td>
                                <td width="100">Semi-Annually</td>
                                <td width="90">Annually</td>
                                <td width="90">Biennially</td>
                                <td width="90">Triennially</td>
                            </tr>
                            {foreach from=$price item=tdata key=currency_id}
                                <tr bgcolor="#ffffff" style="text-align:center">
                                    <td class="onetime" rowspan="2" bgcolor="#efefef"><b>{$tdata.code}</b></td>
                                    <td class="onetime">Setup Fee</td>
                                    <td class="onetime">
                                        <input class="form-control" type="text" name="currency[{$currency_id}][msetupfee]" size="10" value="{$tdata.msetupfee}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][qsetupfee]" size="10" value="{$tdata.qsetupfee}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][ssetupfee]" size="10" value="{$tdata.ssetupfee}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][asetupfee]" size="10" value="{$tdata.asetupfee}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][bsetupfee]" size="10" value="{$tdata.bsetupfee}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][tsetupfee]" size="10" value="{$tdata.tsetupfee}"></td>
                                </tr>
                                <tr bgcolor="#ffffff" style="text-align:center">
                                    <td class="onetime">Price</td>
                                    <td class="onetime"><input class="form-control" type="text" name="currency[{$currency_id}][monthly]" size="10" value="{$tdata.monthly}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][quarterly]" size="10" value="{$tdata.quarterly}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][semiannually]" size="10" value="{$tdata.semiannually}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][annually]" size="10" value="{$tdata.annually}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][biennially]" size="10" value="{$tdata.biennially}"></td>
                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][triennially]" size="10" value="{$tdata.triennially}"></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                    <br>
                    (Set Price to 0.00 to disable any of the payment term options - leave Setup Fee at zero)<br><br>
                </td>
            </tr>
        <br>
        </td>
        </tr>
        <tr>
            <td class="fieldlabel">Tax Addon</td>
            <td class="fieldarea"><input type="checkbox" name="tax" id="tax" {if $addon.tax eq "on"}checked{/if}> <label for="tax">Charge tax on this addon</label></td>
        </tr>
        <tr>
            <td class="fieldlabel">Show on Order</td>
            <td class="fieldarea"><input type="checkbox" name="showorder" checked="" {if $addon.showorder eq "on"}checked{/if} id="showorder"> <label for="showorder">Show addon during initial product order process</label></td>
        </tr>
        <tr><td class="fieldlabel">Auto Activate on Payment</td><td class="fieldarea"><input type="checkbox" name="autoactivate" id="autoactivate"  {if $addon.autoactivate eq "on"}checked{/if}> <label for="autoactivate">Auto Activate and send Welcome Email on payment</label></td></tr>
        <tr><td class="fieldlabel">Suspend Parent Product</td><td class="fieldarea"><input type="checkbox" name="suspendproduct" id="suspendproduct" {if $addon.suspendproduct eq "on"}checked{/if}> <label for="suspendproduct">Tick to suspend the parent product as well when instances of this addon are overdue</label></td></tr>

        <tr><td  class="fieldlabel">Welcome Email</td><td class="fieldarea">
                <select class="form-control" name="welcomeemail"><option value="0">None</option>
                    <option value="35">Cancellation Request Confirmation</option>
                    <option value="17">Dedicated/VPS Server Welcome Email</option>
                    <option value="1">Hosting Account Welcome Email</option>
                    <option value="18">Other Product/Service Welcome Email</option>
                    <option value="4">Reseller Account Welcome Email</option>
                    <option value="10">Service Suspension Notification</option>
                    <option value="25">SHOUTcast Welcome Email</option>
                </select>
            </td>
        </tr>
        <tr><td class="fieldlabel">Addon Weighting</td><td class="fieldarea"><input class="form-control" type="text" name="weight" size="10" value="{$addon.weight}"> Enter a number here to override the default alphabetical display order</td></tr>
        </tbody></table>

    {if $service}
        <p><b>Applicable Products</b></p>

        <table width="100%">
            <tbody>
                <tr>
                    {foreach from=$service item=ser}
                        <td width="33%">
                            <input type="checkbox" name="packages[]" value="{$ser.id}" {$ser.check} id="a{$ser.id}}"> <label for="a{$ser.id}">{$ser.groupname} - {$ser.name}</label>
                        </td>
                    {/foreach}
                </tr>
            </tbody>
        </table>
    {/if}

    <p align="center"><input type="submit" value="Save Changes" class="button"></p>

</form>

{literal}
    <script type="text/javascript">
        $(document).ready(function () {
            function pricetable(value)
            {

                if (value == 'onetime')
                {
                    $(".tableprice").show();
                    $(".tableprice").find("table tr td").hide();
                    $(".tableprice").find(".onetime").show();
                }
                else if (value == 'free')
                {
                    $(".tableprice").hide();
                }
                else
                {
                    $(".tableprice").find("table tr td").show();
                    $(".tableprice").show();
                }
            }
            pricetable("{/literal}{$addon.billingcycle}{literal}");
            $("input[name='paytype']").change(function () {
                pricetable($(this).val());
            });
        });
    </script>
{/literal}