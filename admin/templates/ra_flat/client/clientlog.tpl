<div class="card">
    <div class="content">
        <form class="form-inline" method="post" action="clientslog.php?userid=">
            <div class="form-group">
                <label>Date</label>
                <input class="form-control" type="text" name="date" value="" class="form-control datepick">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input class="form-control" type="text" name="description" value="" size="30">
            </div>
            <div class="form-group">
                <label>Username</label>
                <select class="form-control" name="username">
                    {$useroption}
                </select>
            </div>
            <div class="form-group">
                <label>IP Address:</label>
                <input class="form-control" type="text" name="ipaddress" value="" size="20">
            </div>
            <input class="btn btn-default" type="submit" value="Filter Log">
        </form>
                
                {$table}
    </div>
</div>