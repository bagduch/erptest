<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="header card-header-icon">
            <button style="margin-bottom: 20px" class="btn btn-box-tool" data-toggle="collapse" data-target="#search">Search/Filter</button>
            </div>
            <div id="search" class="collapse">
                <form action="clients.php" method="post">
                    <div class="content">
                        <div class="table-responsive">
                            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                <tbody>
                                    <tr>
                                        <td class="fieldlabel">{$lang.clientnamelang}</td>
                                        <td class="fieldarea"><input class="form-control" type="text" name="clientname" value="{$filterdata.clientname}"></td>
                                        <td class="fieldlabel">{$lang.companynamelang}</td>
                                        <td class="fieldarea"><input type="text" class="form-control" name="companyname" value="{$filterdata.companyname}"></td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">{$lang.emaillang}</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="email" value="{$filterdata.email}">
                                        </td>
                                        <td class="fieldlabel">{$lang.addresslang}</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="address" value="{$filterdata.address}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">{$lang.statuslang}</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="status">
                                                <option  value="">{$lang.anylang}</option>
                                                <option {if $filterdata.status eq 'Active'}selected{/if} value="{$lang.activelang}">{$lang.activelang}</option>
                                                <option {if $filterdata.status eq 'Inactive'}selected{/if} value="{$lang.inactivelang}">{$lang.inactivelang}</option>
                                                <option {if $filterdata.status eq 'Closed'}selected{/if} value="{$lang.closelang}">{$lang.closelang}</option>
                                            </select>
                                        </td>
                                        <td class="fieldlabel">{$lang.statelang}</td>
                                        <td class="fieldarea"><input type="text" class="form-control" name="state" value="{$filterdata.state}"></td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">{$lang.clientgrouplang}</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="clientgroup">
                                                <option value="">- Any -</option>
                                                  {if isset($id)}
                                                    {foreach item=clientgroup from=$clientgroups}
                                                        <option {if $filterdata.clientgroup eq $id}selected{/if} value="{$id}">{$clientgroup}</option>
                                                    {/foreach}
                                                  {/if}
                                            </select>
                                        </td>
                                        <td class="fieldlabel">{$lang.phonenumberlang}</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="phonenumber" value="{$filterdata.phonenumber}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">{$lang.currencylang}</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="currency">
                                                <option value="">- Any -</option>
                                                {foreach item=currency from=$currencys}
                                                  {if isset($id)}
                                                    <option {if $filterdata.currency eq $id}selected{/if} value="{$id}">{$currency}</option>
                                                  {/if}
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td class="fieldlabel">{$lang.cardlast4lang}</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="cardlastfour" value="{$filterdata.cardlastfour}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit"  class="btn btn-default">Search</button>
                    </div>
                </form>
            </div>

        </div>




        <form method="post" action="clients.php?filter=1">
            <div class="content">
                {if isset($table)}
                    {$table}
                {/if}
            </div>
        </form>

    </div>
</div>
<div class="clear"></div>

