<!-- BEGIN WHMCS -->
{if $maintenancemode}
    <div class="errorbox" style="font-size:14px;"> {$_ADMINLANG.home.maintenancemode} </div>
    <br />
{/if}
{$infobox}
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{if $sidebarstats.orders.pending}{$sidebarstats.orders.pending}{else}0{/if}</h3>
                <p>New Orders</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="orders.php?status=Pending" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{if $sidebarstats.orders.cancelled}{$sidebarstats.orders.cancelled}{else}0{/if}</h3>
                <p>Cancel Orders</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="orders.php?status=Cancelled" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{if $sidebarstats.orders.active}{$sidebarstats.orders.active}{else}0{/if}</h3>
                <p>Active Clients</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{if $sidebarstats.orders.unpaid}{$sidebarstats.orders.unpaid}{else}0{/if}</h3>
                <p>Unpaid Invoice</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">
    {foreach from=$notes item=data}
        {if $data.flag && $adminid = $data.assignto && $data.sticky eq '0'}
            <div class="col-lg-3 col-xs-6">
                <div class="alert alert-{$data.color} alert-dismissible">
                    <form class="notesupdate{$data.id}" method="post" action="">
                        <input type="hidden" name="noteid" value="{$data.id}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Notes {$data.modified}</h4>
                        <table class="table">
                            <tr>
                                <td colspa="2">{$data.name}: <input type="hidden" name='assign' value="{$data.assignto}"></td>
                            </tr> 
                            <tr>
                                <td colspa="2"> 
                                    <textarea name="notesdata" class="form-control" style="color:black">{$data.note}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspa="2">              
                                    <input class="datepick form-control" name="updatetime" style="color:black;width: 100px;display: inline-block" type="text" value="{$data.duedate}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="finishtask" value='0'>
                                            done
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspa="2"> 
                                    <div class="notesdone btn btn-default">Update</div>
                                    <a style='color:black;margin-left:10px' class="btn btn-default" href ="{$data.type}">View</a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        {/if}
    {/foreach}
</div>
<!-- END WHMCS -->
<div class="row">
    <section class="col-lg-7 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->

        {foreach from=$widgets.left item=data}
            <div class="box box-success" id="{$data.name}">
                <div class="box-header">
                    <i class="fa fa-inbox"></i>
                    <h3 class="box-title">{$data.title}</h3>
                </div>
                <div class="box-body">
                    {$data.content}
                </div>
            </div>
        {/foreach}
        <!-- /.nav-tabs-custom -->
    </section>
    <section class="col-lg-5 connectedSortable">
        {foreach from=$widgets.right item=data}
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">
                        {$data.title}
                    </h3>
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    {$data.content}
                </div>
            </div>
        {/foreach}
    </section>
</div>
