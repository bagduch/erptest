
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header">
                <p>This is where you configure the users which you want to allow to access the admin area.</p>
                <p><b>Options:</b> <a class="btn btn-success" href="configadmins.php?action=manage">Add New Administrator</a></p>
            </div>
            <div class="content">

                <h4>Active Administrators </h4>
                {$tableactive}
                <h4>Inactive Administrators </h4>
                {$tableinactive}
            </div>
        </div>
    </div>
</div>