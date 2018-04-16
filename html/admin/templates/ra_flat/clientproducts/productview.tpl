
<div class="card">
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_all" data-toggle="tab">All Product ({$totalservice})</a></li>
                    {foreach from=$userarray key=gname item=row}
                    <li><a href="#tab_{$gname}" data-toggle="tab">{$gname} ({$row|@count})</a></li>
                    {/foreach}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_all">
                    {foreach from=$userarray key=gname item=row}
                        <div class="tablebg">
                            <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                                <tbody>
                                    <tr style="background: lightgray;">
                                        <th style="width:10px"></th>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    {foreach from=$row key=accountid item=item}
                                        <tr>
                                            <td>{if $id eq $item.id}<i class="fa fa-fw fa-arrow-right"></i>{/if}</td>
                                            <td><a href="clientproduct.php?userid={$userid}&id={$item.id}">{$item.id}</a></td>
                                            <td>{$item.name}</td>
                                            <td>{$item.description}</td>
                                            <td>
                                                 {$item.servicestatus}
                                            </td>
                                            <td>
                                                <a href="clientproduct.php?userid={$userid}&id={$item.id}" class="btn btn-success tableitem"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    {/foreach}
                </div>
                {foreach from=$userarray key=gname item=row}
                    <div class="tab-pane" id="tab_{$gname}">
                        <div class="tablebg">
                            <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                                <tbody>
                                    <tr style="background: lightgray;">
                                        <th style="width:10px"></th>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    {foreach from=$row item=item}
                                        <tr>
                                            <td>{if $id eq $item.id}<i class="fa fa-fw fa-arrow-right"></i>{/if}</td>
                                            <td><a href="clientproduct.php?userid={$userid}&id={$item.id}">{$item.id}</a></td>
                                            <td>{$item.name}</td>
                                            <td>{$item.description}</td>
                                            <td>
                                                {$item.servicestatus}
                                            </td>
                                            <td>
                                                <a href="clientproduct.php?userid={$userid}&id={$item.id}" class="btn btn-success tableitem"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                {/foreach}
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>

