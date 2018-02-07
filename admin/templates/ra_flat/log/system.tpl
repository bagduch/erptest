
<div class="row">
    <div class="card">

        <div class="header card-header-icon">
            <button style="margin-bottom: 20px" class="btn btn-box-tool" data-toggle="collapse" data-target="#search">Search/Filter</button>
        </div>
        <div id="search" class="collapse">
            <form method="post" action="systemactivitylog.php">
      
                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">Date</td>
                            <td class="fieldarea"><input type="text" name="date" value="{$date}" class="form-control datepicker"></td>
                            <td class="fieldlabel">Username</td>
                            <td class="fieldarea"><select name="username" class="form-control"><option value="">- Any -</option>{$option}</select>
                            </td>
                        </tr>
                        <tr><td class="fieldlabel">Description</td>
                            <td class="fieldarea">
                                <input type="text" name="description" class="form-control" value="{$description}" size="80"></td>
                            <td class="fieldlabel">IP Address</td>
                            <td class="fieldarea"><input class="form-control" type="text" name="ipaddress" value="{$ipaddress}" size="20"></td>
                        </tr>
                    </tbody>
                </table>
                <div align="center"><input type="submit" value="Filter" class="btn btn-default"></div>
                <br>
            </form>
        </div>

    </div>
    <div class="card">
        <div class="content">
            {$table}
        </div>
    </div>
</div>

