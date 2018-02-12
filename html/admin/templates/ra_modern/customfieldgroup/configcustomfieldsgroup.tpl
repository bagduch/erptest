
<p>Product/Service Custom Fields options allow you to customize the product/service field to enter customer details</p>

<p><b>Options:</b> <a href="/admin/configcustomfieldsgroup.php?action=managegroup">Create a Customer Field Group</a> | <a href="/admin/configcustomfieldsgroup.php?action=duplicategroup">Duplicate a Group</a></p>


<div class="tablebg">
    <table id="sortabletbl1" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
        <tbody>
            <tr>
                <th>Name</th>
                <th width="20"></th>
                <th width="20"></th>
            </tr>
            {if $tabledatas}
                {foreach key=num item=tabledata from=$tabledatas}
                    <tr>
                        <td>{$tabledata[0]}</td>
                        <td>{$tabledata[1]}</td>
                        <td>{$tabledata[2]}</td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="3">No Records Found</td>
                </tr>
            {/if}
        </tbody>
    </table>
</div>

</div>
<div class="clear"></div>

