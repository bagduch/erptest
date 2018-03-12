<div class="card">
    <div class="content">
        <h2>Edit Client Group</h2>
        <form method="post" action="{$url}">
            <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr><td width="25%" class="fieldlabel">Group Name</td>
                        <td class="fieldarea"><input type="text" name="groupname" size="40" value="{$editdata.groupname}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Group Colour</td>
                        <td class="fieldarea"><input type="color" name="groupcolour" size="10" value="{$editdata.groupcolour}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Group Discount %</td>
                        <td class="fieldarea"><input type="text" name="discountpercent" size="10" value="{$editdata.discountpercent}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Exempt from Suspend &amp; Terminate</td>
                        <td class="fieldarea"><input type="checkbox" name="susptermexempt" {if $editdata.susptermexempt eq 'on'}checked{/if}></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Separate Invoices for Services</td>
                        <td class="fieldarea"><input type="checkbox" name="separateinvoices" {if $editdata.separateinvoices eq 'on'}checked{/if}></td>
                    </tr>
                <input type="hidden" name="groupid" value="{$groupid}">
                </tbody>
            </table>
            <p align="center"><input type="submit" value="Save Changes" class="button"></p>
        </form>
    </div>
</div>