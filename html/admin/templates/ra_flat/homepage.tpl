<!-- BEGIN WHMCS -->
{if isset($maintenancemode) && $maintenancemode}
    <div class="errorbox" style="font-size:14px;"> {$_ADMINLANG.home.maintenancemode} </div>
    <br />
{/if}
{$infobox}


<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card">
            <div class="content">
                <div class="row">
                    <div class="col-xs-7">
                        <h3>Total Orders<i class="ti-stats-up"></i></h3>
                        <dl>
                        {foreach from=$sidebarstats.orders key=status item=count}
                          <dt>{$status}</dt><dd>{$count}</dd>
                        {/foreach}
                        </dl>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="stats">
                <a href="orders.php?status=Pending">
                    <i class="ti-reload"></i> >More info
                </a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card">
        <div class="content">
            <div class="row">
                <div class="col-xs-5">
                    <div class="icon-big icon-danger text-center">
                        <i class="ti-face-sad"></i>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="numbers">
                        <p>Cancel Orders</p>
                    {if isset($sidebarstats.orders.cancelled)}{$sidebarstats.orders.cancelled}{else}0{/if}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="stats">
            <a href="orders.php?status=Cancelled">
                <i class="ti-reload"></i> >More info
            </a>
        </div>
    </div>
</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card">
        <div class="content">
            <div class="row">
                <div class="col-xs-5">
                    <div class="icon-big icon-success text-center">
                        <i class="ti-user"></i>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="numbers">
                        <p>Active Clients</p>
                    {if $sidebarstats.clients.active}{$sidebarstats.clients.active}{else}0{/if}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="stats">
            <a href="clients.php?status=active">
                <i class="ti-reload"></i> >More info
            </a>
        </div>
    </div>
</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card">
        <div class="content">
            <div class="row">
                <div class="col-xs-5">
                    <div class="icon-big icon-info text-center">
                        <i class="ti-money"></i>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="numbers">
                        <p>Unpaid Invoice</p>
                    {if $sidebarstats.invoices.unpaid}{$sidebarstats.invoices.unpaid}{else}0{/if}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="stats">
            <a href="invoices.php?status=unpaid" class="small-box-footer">
                <i class="ti-reload"></i> >More info
            </a>
        </div>
    </div>
</div>
</div>
</div>

<div class="row">
    {foreach from=$notes item=data}
        {if $data.flag eq '1' && $adminid eq $data.assignto && $data.sticky eq '0'}
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
                                            <input type="checkbox" name="done" value='0'>
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
    <div class="col-md-6">
        <!-- Custom tabs (Charts with tabs)-->

        {foreach from=$widgets.left item=data}
            <div class="card">

                <div class="header card-header-icon">

                    <h4 class="title"><i class="ti-pulse"></i> {$data.title}
                    </h4>
                </div>
                <div class="content">
                    {$data.content}
                </div>
            </div>

        {/foreach}
        <!-- /.nav-tabs-custom -->
    </div>
    <section class="col-md-6">
        {foreach from=$widgets.right item=data}
            <div class="card">
                <div class="card">

                    <div class="header card-header-icon">

                        <h4 class="title"><i class="ti-pulse"></i> {$data.title}
                        </h4>
                    </div>
                    <div class="content">
                        {$data.content}
                    </div>
                </div>
            </div>
        {/foreach}
    </section>
</div>
{literal}
    <script type="text/javascript">
        $(document).ready(function () {
            $(".notesdone").click(function (e) {
                $("input[name='done']").val(1);
                $(this).closest('form').submit();
            });
        });
    </script>
{/literal}