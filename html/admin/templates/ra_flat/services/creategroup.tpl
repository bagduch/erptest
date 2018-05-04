<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="content">
                <form method="post" action="configservices.php?sub=savegroup&amp;ids={$groupdata.id}">
                    <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="25%" class="fieldlabel">Service Group Name</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" name="name" size="40" value="{$groupdata.name}">
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Service Group Type</td>
                                <td>
                                    <select name="type" class="form-control">
                                        <option {if $groupdata.type eq "service"}Selected{/if}>Service</option>
                                        <option {if $groupdata.type eq "product"}Selected{/if}>Product</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">
                                    <label>Custom Fields Group<br></label>
                                </td>
                                <td class="fieldarea">
                                    <select class="form-control" name="customfield[]" multiple="">
                                        {foreach from = $cdata item=customerf}
                                            <option {if $customerf.check}Selected{/if} value="{$customerf.cfgid}">{$customerf.name}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Available Payment Gateways</td>
                                <td class="fieldarea">

                                    {foreach from=$avaiablegateway item=gatewayvalue}
                                        <label><input type="checkbox" name="gateways[{$gatewayvalue.value}]" {$gatewayvalue.check}> {$gatewayvalue.name}</label>
                                        <br>
                                    {/foreach}
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel"><br></td>
                                <td class="fieldarea"></td></tr>
                            <tr>
                                <td class="fieldlabel">Hidden</td>
                                <td class="fieldarea"><label><input type="checkbox" name="hidden"> Check this box if this is a hidden group</label></td></tr>
                        </tbody></table>
                    <p align="center"><input type="submit" value="Save Changes" class="btn btn-primary"> 
                        <input type="button" value="Cancel Changes" onclick="window.location = 'configservices.php'" class="btn">
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>
