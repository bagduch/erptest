


<div class="card">
    <div class="header card-header">
        <h4 class="title">Product/Service Custom Fields</h4>
    </div>
    <div class="content">
        <p>Product/Service Custom Fields options allow you to customize the product/service field to enter customer details</p>
        <p> <b>Options:</b> <a class="btn btn-default" href="configcustomfieldsgroup.php?action=managegroup">Create a Custom Field Group</a> | <a class="btn btn-default" href="configcustomfieldsgroup.php?action=duplicategroup">Duplicate a Group</a>
        </p>
        <div class="tablebg">
            <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <th width="20"></th>
                        <th width="20">Action</th>
                    </tr>
                    {if $tabledatas}
                        {foreach key=num item=tabledata from=$tabledatas}
                            <tr>
                                <td>{$tabledata[1]}</td>
                                <td><a class="btn btn-default" href="?action=managegroup&id={$tabledata[0]}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                <td> <a class="btn btn-danger" href="doDelete({$tabledata[0]})"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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





