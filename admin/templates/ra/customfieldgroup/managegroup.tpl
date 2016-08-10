
{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        function manageconfigoptions(id) {
            window.open('/admin/configcustomfieldsgroup.php?manageoptions=true&cid=' + id, 'configoptions', 'width=900,height=500,scrollbars=yes');
        }
        function addconfigoption() {
            window.open('/admin/configcustomfieldsgroup.php?manageoptions=true&gid=', 'configoptions', 'width=800,height=500,scrollbars=yes');
        }
        function doDelete(id, opid) {
            if (confirm("Are you sure you want to delete this configurable option?")) {
                window.location = '/admin/configcustomfieldsgroup.php?action=deleteoption&id=' + id + '&opid=' + opid + '&token=1ba6aebbf4014e5e7b53c3611ecb1c9d209df956';
            }
        }
    </script>
{/literal}

<form method="post" action="/admin/configcustomfieldsgroup.php?action=save&amp;id={$id}" name="managefrm">
    <p><b>Create a New Group</b></p>
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody>
            <tr>
                <td width="15%" class="fieldlabel">Name</td>
                <td class="fieldarea">
                    <input type="text" name="name" size="40" value="{$name}">
                </td>
            </tr>

            <tr>
                <td class="fieldlabel">Assigned Products</td>
                <td class="fieldarea">
                    <select name="productlinks[]" size="8" style="width:90%" multiple="">
                        {foreach from=$productlinks item=row key=productid}
                            <option value="{$productid}"{$row.check}>{$row.data.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
        </tbody>
    </table>

    {foreach key=num item=data from=$datas}
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody>
                <tr>
                    <td width="100" class="fieldlabel">Field Name</td>
                    <td class="fieldarea">
                        <table width="98%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td><input type="text" name="fieldname[{$num}]" size="30" value="{$data.fieldname}"></td>
                                    <td align="right">Display Order <input type="text" name="sortorder[{$num}]" size="5" value="{$data.showorder}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Field Type</td>
                    <td class="fieldarea">
                        <select name="fieldtype[{$num}]">
                            <option {if $data.fieldtype eq 'text'}checked{/if} value="text">Text Box</option>
                            <option {if $data.fieldtype eq 'link'}checked{/if} value="link">Link/URL</option>
                            <option {if $data.fieldtype eq 'password'}checked{/if} value="password">Password</option>
                            <option {if $data.fieldtype eq 'dropdown'}checked{/if} value="dropdown">Drop Down</option>
                            <option {if $data.fieldtype eq 'tickbox'}checked{/if} value="tickbox">Tick Box</option>
                            <option {if $data.fieldtype eq 'textarea'}checked{/if} value="textarea">Text Area</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Description</td>
                    <td class="fieldarea">
                        <input type="text" name="description[{$num}]" size="60" value='{$data.description}'> The explanation to show users
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Validation</td>
                    <td class="fieldarea">
                        <input type="text" name="regexpr[{$num}]" size="60" value='{$data.description}'> Regular Expression Validation String
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Select Options</td>
                    <td class="fieldarea">
                        <input type="text" name="fieldoptions[{$num}]" size="60" value='{$data.fieldoptions}'> For Dropdowns Only - Comma Seperated List</td>
                </tr>
                <tr>
                    <td class="fieldlabel"></td>
                    <td class="fieldarea">
                        <input type="checkbox" name="adminonly[{$num}]" {if $data.adminonly}checked{/if}> Admin Only 
                        <input type="checkbox" name="required[{$num}]" {if $data.required}checked{/if}> Required Field 
                        <input type="checkbox" name="showorder[{$num}]" {if $data.showorder}checked{/if}> Show on Order Form 
                        <input type="checkbox" name="showinvoice[{$num}]" {if $data.showinvoice}checked{/if}> Show on Invoice
                    </td>
                </tr>
            </tbody>
        </table>
    {/foreach}
    <b>Add New Custom Field</b><br><br>
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody><tr><td width="100" class="fieldlabel">Field Name</td><td class="fieldarea"><table width="98%" cellspacing="0" cellpadding="0"><tbody><tr><td><input type="text" name="addfieldname" size="30"></td><td align="right">Display Order <input type="text" name="addsortorder" size="5" value="0"></td></tr></tbody></table></td></tr>
            <tr><td class="fieldlabel">Field Type</td><td class="fieldarea"><select name="addfieldtype">
                        <option value="text">Text Box</option>
                        <option value="link">Link/URL</option>
                        <option value="password">Password</option>
                        <option value="dropdown">Drop Down</option>
                        <option value="tickbox">Tick Box</option>
                        <option value="textarea">Text Area</option>
                    </select></td></tr>
            <tr><td class="fieldlabel">Description</td><td class="fieldarea"><input type="text" name="adddescription" size="60"> The explanation to show users</td></tr>
            <tr><td class="fieldlabel">Validation</td><td class="fieldarea"><input type="text" name="addregexpr" size="60"> Regular Expression Validation String</td></tr>
            <tr><td class="fieldlabel">Select Options</td><td class="fieldarea"><input type="text" name="addfieldoptions" size="60"> For Dropdowns Only - Comma Seperated List</td></tr>
            <tr><td class="fieldlabel"></td><td class="fieldarea"><input type="checkbox" name="addadminonly"> Admin Only <input type="checkbox" name="addrequired"> Required Field <input type="checkbox" name="addshoworder"> Show on Order Form <input type="checkbox" name="addshowinvoice"> Show on Invoice</td></tr>
        </tbody>
    </table>
    <p style="clear:both" align="center"><input type="submit" value="Save Changes" class="button"> 
        <input type="button" value="Back to Groups List" onclick="window.location = 'configproductoptions.php'" class="button">
    </p>

</form>
