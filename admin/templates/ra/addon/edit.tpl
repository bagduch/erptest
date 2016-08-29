
<form method="post" action="/admin/configaddons.php?action=save&amp;id=1">
    <input type="hidden" name="token" value="526b26301bb68fce470779db889e9f2dd30aede6">
    {debug}
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody>
            <tr>
                <td class="fieldlabel" width="20%">Name</td>
                <td class="fieldarea"><input type="text" name="name" size="40" value="{$addon.name}"></td>
            </tr>
            <tr>
                <td class="fieldlabel">Description</td>
                <td class="fieldarea">
                    <textarea name="description" cols="60" rows="3">{$addon.description}</textarea>
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">Billing Cycle</td>
                <td class="fieldarea">
                    {$paymentoption}
                </td>
            </tr>
            <tr><td class="fieldlabel">Pricing</td><td class="fieldarea"><br>
                    <table cellspacing="1" bgcolor="#cccccc">
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
                    </table>
                    <br>
                </td></tr>
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

            <tr><td class="fieldlabel">Welcome Email</td><td class="fieldarea">
                    <select name="welcomeemail"><option value="0">None</option>
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
            <tr><td class="fieldlabel">Addon Weighting</td><td class="fieldarea"><input type="text" name="weight" size="10" value="{$addon.weight}"> Enter a number here to override the default alphabetical display order</td></tr>
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
