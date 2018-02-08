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

                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">Client Name</td>
                                <td class="fieldarea"><input type="text" name="client" size="25" value=""></td>
                                <td width="10%" class="fieldlabel">Balance</td>
                                <td class="fieldarea">
                                    <select name="balancetype"><option value="greater">Greater Than</option><option>Less Than</option></select>
                                    <input type="text" name="balance" size="5" value="">
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Visitors Referred</td>
                                <td class="fieldarea">
                                    <select name="visitorstype"><option value="greater">Greater Than</option><option>Less Than</option></select> 
                                    <input type="text" name="visitors" size="5" value=""></td>
                                <td class="fieldlabel">Withdrawn</td>
                                <td class="fieldarea">
                                    <select name="withdrawntype"><option value="greater">Greater Than</option><option>Less Than</option></select>
                                    <input type="text" name="withdrawn" size="5" value="">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <img src="images/spacer.gif" height="10" width="1"><br>
                    <div align="center"><input type="submit" value="Search" class="button"></div>

                </form>

            </div>
        </div>

        <div class="card">
            <div class="content">
                {$table}
            </div>
        </div>
    </div>
</div>



{literal}

    <script type="text/javascript">
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this affiliate?")) {
                window.location = 'affiliates.php?sub=delete&ide=' + id + '&token=773136f385acef9d62f7cc92904c025310b11e85';
            }
        }


    </script>


{/literal}

