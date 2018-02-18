
{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        function manageconfigoptions(id) {
            window.open('configcustomfieldsgroup.php?manageoptions=true&cid=' + id, 'configoptions', 'width=900,height=500,scrollbars=yes');
        }
        function addconfigoption() {
            window.open('configcustomfieldsgroup.php?manageoptions=true&gid=', 'configoptions', 'width=800,height=500,scrollbars=yes');
        }
        function doDelete(id, opid) {
            if (confirm("Are you sure you want to delete this configurable option?")) {
                window.location = 'configcustomfieldsgroup.php?action=deleteoption&id=' + id + '&opid=' + opid + '&token=1ba6aebbf4014e5e7b53c3611ecb1c9d209df956';
            }
        }
    </script>
{/literal}
{debug}
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Create a New Group</h3>

    </div>
    <div class="box-body">
        <form method="post" action="configcustomfieldsgroup.php?action=savegroup" name="managefrm">

            <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr>
                        <td width="15%" class="fieldlabel">Name</td>
                        <td class="fieldarea">
                            <input class="form-control" type="text" name="name" size="40" value="">
                        </td>
                    </tr>

                    <tr>
                        <td class="fieldlabel">Assigned Products</td>
                        <td class="fieldarea">
                            {foreach from=$productlinks item=row}
                                <label name="productlinks[{$row.id}]"><input class="flat-red" type="checkbox">{$row.data.name}</label>
                                {/foreach}
                        </td>
                    </tr>
                </tbody>
            </table>



        </form>
    </div>
</div>
