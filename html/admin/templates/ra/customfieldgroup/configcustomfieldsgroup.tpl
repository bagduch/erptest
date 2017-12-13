


<div class="box">

    <div class="box-header">
        <h3 class="box-title">Product/Service Custom Fields</h3>
        <p>Product/Service Custom Fields options allow you to customize the product/service field to enter customer details</p>
        <b>Options:</b> <a href="/admin/configcustomfieldsgroup.php?action=managegroup">Create a Customer Field Group</a> | <a href="/admin/configcustomfieldsgroup.php?action=duplicategroup">Duplicate a Group</a>
    </div>

    <div class="box-body">
        <div class="tablebg">
            <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
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

</div>





