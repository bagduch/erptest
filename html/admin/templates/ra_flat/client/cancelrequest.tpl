
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h3 class="box-title"> 
                    <button style="font-size: 20px;color:blue" type="button" class="btn btn-box-tool" data-widget="collapse">Search/Filter
                    </button>
                </h3>
                <!-- /.box-tools -->
            </div>
            <div class="content">
                <form action="{$PHP_SELF}" method="get">
                    <input type="hidden" name="filter" value="true">
                    <table  class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Reason</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" name="reason" value="">
                                </td>
                                <td class="fieldlabel">Client</td>
                                <td class="fieldarea">
                                    {$clientdropdown}
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Description</td>
                                <td class="fieldarea"><input  class="form-control" type="text" name="description" value=""></td>
                                <td class="fieldlabel">Type</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type">
                                        <option value="">- Any -</option>
                                        <option value="Immediate">Immediate</option>
                                        <option value="End of Billing Period">End of Billing Period</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Service ID</td>
                                <td class="fieldarea"><input  class="form-control" type="text" name="serviceid"  value=""></td>
                                <td class="fieldlabel">&nbsp;</td>
                                <td class="fieldarea">&nbsp;</td>
                            </tr>
                        </tbody></table>

                    <img src="images/spacer.gif" height="10" width="1"><br>
                    <div align="center"><input type="submit" value="Filter" class="btn btn-default"></div>

                </form>

            </div>
        </div>
        <br>
       
        <div class="card">
            <div class="header">
                 <p align="center"><a class="btn btn-default {if !$completed}active{/if}" href="{$PHP_SELF}">Show Open Requests</a><a class="btn btn-default {if $completed}active{/if}" href="{$PHP_SELF}?completed=true">Show Completed Requests</a></p>
            </div>
            <div class="content">
                {$table}
            </div>
        </div>
    </div>

</div>
{literal}
    <script type="text/javascript">
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this cancellation request?")) {
                window.location = '?action=delete&id=' + id + '&token=858df84bc307419a8c061ec9cbe1d3acfa6554b2';
            }
        }


    </script>
{/literal}


