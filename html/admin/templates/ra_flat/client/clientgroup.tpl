<div class="card">
    <section class="content">
        <p>Client Groups can be used to differentiate between your customers more easily and apply overides to certain functions.</p>
        {$table}
        <h2>Add Client Group</h2>
        <form method="post" action="{$url}">
            <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr><td width="25%" class="fieldlabel">Group Name</td>
                        <td class="fieldarea"><input type="text" name="groupname" size="40" value=""></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Group Colour</td>
                        <td class="fieldarea"><input type="color" name="groupcolour" size="10"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Group Discount %</td>
                        <td class="fieldarea"><input type="text" name="discountpercent" size="10" value=""></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Exempt from Suspend &amp; Terminate</td>
                        <td class="fieldarea"><input type="checkbox" name="susptermexempt"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Separate Invoices for Services</td>
                        <td class="fieldarea"><input type="checkbox" name="separateinvoices"></td>
                    </tr>
                <input type="hidden" name="groupid" value="">
                </tbody>
            </table>
            <p align="center"><input type="submit" value="Save Changes" class="button"></p>
        </form>
        {literal}
            <script type="text/javascript">
                function doDelete(id) {
                    if (confirm("Click OK if you are sure you want to delete this Client Group?")) {
                        window.location = '/admin/configclientgroups.php?action=delete&id=' + id + '&token={/literal}{$token}{literal}';
                    }
                }
            </script>
        {/literal}
    </section>
</div>