

<div style="float:left;width:100%;">
    <div id="tabs">
        <ul>
            <li id="tab0" class="tab">
                <a href="javascript:;">{$lang.searchfilterlang}</a>
            </li>
        </ul>
    </div>
    <div id="tab0box" class="tabbox" style="display: none;">
        <div id="tab_content">

            <form action="clients.php" method="post">
                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td width="15%" class="fieldlabel">{$lang.clientnamelang}</td>
                            <td class="fieldarea"><input type="text" name="clientname" size="30" value="{$filterdata.clientname}"></td>
                            <td width="15%" class="fieldlabel">{$lang.companynamelang}</td>
                            <td class="fieldarea"><input type="text" name="companyname" size="30" value="{$filterdata.companyname}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">{$lang.emaillang}</td>
                            <td class="fieldarea">
                                <input type="text" name="email" size="40" value="{$filterdata.email}">
                            </td>
                            <td class="fieldlabel">{$lang.addresslang}</td>
                            <td class="fieldarea">
                                <input type="text" name="address" size="30" value="{$filterdata.address}">
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">{$lang.statuslang}</td>
                            <td class="fieldarea">
                                <select name="status">
                                    <option  value="">{$lang.anylang}</option>
                                    <option {if $filterdata.status eq 'Active'}selected{/if} value="{$lang.activelang}">{$lang.activelang}</option>
                                    <option {if $filterdata.status eq 'Inactive'}selected{/if} value="{$lang.inactivelang}">{$lang.inactivelang}</option>
                                    <option {if $filterdata.status eq 'Closed'}selected{/if} value="{$lang.closelang}">{$lang.closelang}</option>
                                </select>
                            </td>
                            <td class="fieldlabel">{$lang.statelang}</td>
                            <td class="fieldarea"><input type="text" name="state" size="30" value="{$filterdata.state}"></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">{$lang.clientgrouplang}</td>
                            <td class="fieldarea">
                                <select name="clientgroup">
                                    <option value="">- Any -</option>
                                    {foreach item=clientgroup num=id from=$clientgroups}
                                        <option {if $filterdata.clientgroup eq $id}selected{/if} value="{$id}">{$clientgroup}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="fieldlabel">{$lang.phonenumberlang}</td>
                            <td class="fieldarea">
                                <input type="text" name="phonenumber" size="30" value="{$filterdata.phonenumber}">
                            </td>
                        </tr>
                        <tr>
                            <td width="15%" class="fieldlabel">{$lang.currencylang}</td>
                            <td class="fieldarea">
                                <select name="currency">
                                    <option value="">- Any -</option>
                                    {foreach item=currency num=id from=$currencys}
                                        <option {if $filterdata.currency eq $id}selected{/if} value="{$id}">{$currency}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="fieldlabel">{$lang.cardlast4lang}</td>
                            <td class="fieldarea">
                                <input type="text" name="cardlastfour" size="15" value="{$filterdata.cardlastfour}"></td>
                        </tr>
                    </tbody>
                </table>
                <p align="center"><input type="submit" value="Search" class="button"></p>
            </form>
        </div>
    </div>

    <br>


    <form method="post" action="/admin/clients.php?filter=1">
        <div class="tablebg">
            {if $table}
                {$table}
            {/if}
        </div>
    </form></div>
<div class="clear"></div>

