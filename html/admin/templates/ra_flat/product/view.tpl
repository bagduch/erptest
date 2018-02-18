
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header card-header">
                <p><strong>Options:</strong> <a class="btn btn-default" href="configproducts.php?action=creategroup">Create Group</a>
                    <a class="btn btn-default" href="configproducts.php?action=create">Create Product</a></p>
            </div>
            <div class="box-body">
                <form method="post" action="configservices.php?action=updatesort">
                    <div class="tablebg">
                        <table class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tbody>
                                <tr>
                                    <th style="text-align: center">{$langs.servicename}</th>
                                      <th style="text-align: center">Cost</th>
                                    <th style="text-align: center">{$langs.price}</th>
                                    <th style="text-align: center">Quantity</th>
                                    <th style="text-align: center" width="20"></th>
                                    <th style="text-align: center" width="20"></th>
                                </tr>
                                {foreach from=$servicegroup item=servicesg}
                                    <tr>
                                        <td colspan="4" style="background-color:#ffffdd;">
                                            <div align="left"><b>{$langs.groupname}:</b> {$servicesg.group.name} </div>
                                        </td>
                                        <td style="background-color:#ffffdd;" align="center">
                                            <a href="configservices.php?action=editgroup&amp;ids={$servicesg.group.id}"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>
                                        </td>
                                        <td style="background-color:#ffffdd;" align="center">
                                            <a href="#" onclick="{$servicesg.group.deletelink}">
                                                <img src="images/delete.gif" width="16" height="16" border="0" alt="Delete">
                                            </a>
                                        </td 
                                    </tr>
                                    {foreach from=$servicesg.service item=servicedata key=sid}
                                        <tr style="text-align:center;">
                                            <td>{$servicedata.name}</td>
                                            <td>{$servicedata.cost}</td>
                                            <td>{$servicedata.price}</td>
                                            <td>{$servicedata.stock}</td>
                                            <td><a href="configproducts.php?action=edit&amp;id={$sid}"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
                                            <td><a href="#" onclick="{$servicedata.deletelink}"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a></td>
                                        </tr>
                                    {/foreach}
                                {/foreach}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><div align="center"><input type="submit" value="Update Sorting" style="font-size:10px;"></div></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <script type="text/javascript">
                    {literal}
                        function doDelete(id) {
                            if (confirm("Are you sure you want to delete this product?")) {
                                window.location = '?sub=delete&id=' + id + '&token= {/literal}{$csrfToken}{literal}';
                            }
                        }
                        function doGroupDelete(id) {
                            if (confirm("Are you sure you want to delete this product group?")) {
                                window.location = '?sub=deletegroup&id=' + id + '&token={$csrfToken}';
                            }
                        }



                    {/literal}
                </script>
            </div>
        </div>
    </div>
</div>