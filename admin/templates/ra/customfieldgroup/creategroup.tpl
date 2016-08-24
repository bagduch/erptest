
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

<form method="post" action="/admin/configcustomfieldsgroup.php?action=savegroup&amp;id={$id}" name="managefrm">
    <p><b>Create a New Group</b></p>
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody>
            <tr>
                <td width="15%" class="fieldlabel">Name</td>
                <td class="fieldarea">
                    <input type="text" name="name" size="40" value="">
                </td>
            </tr>

            <tr>
                <td class="fieldlabel">Assigned Products</td>
                <td class="fieldarea">
                    <select name="productlinks[]" size="8" style="width:90%" multiple="">
                    </select>
                </td>
            </tr>
        </tbody>
    </table>



    {if $infobox}
        {$infobox}
    {/if}
    {if $cfids}

        {foreach key=num item=cfid from=$cfids}
            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr>
                        <td width="100" class="fieldlabel">Field Name</td>
                        <td class="fieldarea">
                            <table width="98%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="addfieldname" size="30"></td>
                                        <td align="right">Display Order <input type="text" name="addsortorder" size="5" value="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Field Type</td>
                        <td class="fieldarea">
                            <select name="addfieldtype">
                                <option value="text">Text Box</option>
                                <option value="link">Link/URL</option>
                                <option value="password">Password</option>
                                <option value="dropdown">Drop Down</option>
                                <option value="tickbox">Tick Box</option>
                                <option value="textarea">Text Area</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Description</td>
                        <td class="fieldarea">
                            <input type="text" name="adddescription" size="60"> The explanation to show users
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Validation</td>
                        <td class="fieldarea">
                            <input type="text" name="addregexpr" size="60"> Regular Expression Validation String
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Select Options</td>
                        <td class="fieldarea">
                            <input type="text" name="addfieldoptions" size="60"> For Dropdowns Only - Comma Seperated List</td>
                    </tr>
                    <tr>
                        <td class="fieldlabel"></td>
                        <td class="fieldarea">
                            <input type="checkbox" name="addadminonly"> Admin Only 
                            <input type="checkbox" name="addrequired"> Required Field 
                            <input type="checkbox" name="addshoworder"> Show on Order Form 
                            <input type="checkbox" name="addshowinvoice"> Show on Invoice
                        </td>
                    </tr>
                </tbody>
            </table>

        {/foreach}
    {/if}
    <b>Add New Custom Field</b><br><br>
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tbody><tr><td width="100" class="fieldlabel">Field Name</td><td class="fieldarea"><table width="98%" cellspacing="0" cellpadding="0"><tbody><tr><td><input type="text" name="addfieldname" size="30"></td><td align="right">Display Order <input type="text" name="addsortorder" size="5" value="0"></td></tr></tbody></table></td></tr>
            <tr><td class="fieldlabel">Field Type</td><td class="fieldarea"><select name="addfieldtype">
                        <option value="text">Text Box</option>
                        <option value="link">Link/URL</option>
                        <option value="password">Password</option>
                        <option value="dropdown">Drop Down</option>
                        <option value="date">Date</option>
                        <option value="tickbox">Tick Box</option>
                        <option value="textarea">Text Area</option>
                    </select></td></tr>
            <tr><td class="fieldlabel">Description</td><td class="fieldarea"><input type="text" name="adddescription" size="60"> The explanation to show users</td></tr>
            <tr><td class="fieldlabel">Validation</td><td class="fieldarea"><input type="text" name="addregexpr" size="60"> Regular Expression Validation String</td></tr>
            <tr><td class="fieldlabel">Select Options</td><td class="fieldarea"><input type="text" name="addfieldoptions" size="60"> For Dropdowns Only - Comma Seperated List</td></tr>
            <tr><td class="fieldlabel"></td><td class="fieldarea"><input type="checkbox" name="addadminonly"> Admin Only <input type="checkbox" name="addrequired"> Required Field <input type="checkbox" name="addshoworder"> Show on Order Form <input type="checkbox" name="addshowinvoice"> Show on Invoice</td></tr>
        </tbody>
    </table>
    <br>
    <div align="center">
        <input type="submit" value="Save Changes" class="button">
    </div>
</form>
