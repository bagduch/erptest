

<div class="row">
    <div class="col-md-12">

        <div class="header card-header-text">
            <h4 class="title">
                <p>Client: <b>{$name}</b> (Balance: {$creditbalance})</p>
                <p>You can manage a clients credit balance from here. Every credit adjustment, either addition or removal, requires a log entry and the descriptions you enter here are not visible to clients.</p>
            </h4>
            <div style="float:right">
                <input type="button" class="btn btn-success" value="Add Credit" onclick="window.location = '/admin/clientscredits.php?userid={$userid}&amp;action=add'"> 
                <input type="button" value="Remove Credit" onclick="window.location = '/admin/clientscredits.php?userid={$userid}&amp;action=remove'" class="btn btn-inverse">
            </div>
            <div class="clearfix"></div>

        </div>

        <div class="content">


            {$table}
        </div>
    </div>
</div>
{literal}
    <script language="text/javascript">
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this credit and remove it from the clients balance?")) {
                window.location = '/admin/clientscredits.php?userid=2&sub=delete&ide=' + id + '&token={/literal}{$token}{literal}';
            }
        }
    </script>
{/literal}