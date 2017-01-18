

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_all" data-toggle="tab">All Products ({$totalservice})</a></li>
            {foreach from=$userarray key=gname item=row}
            <li><a href="#tab_{$gname}" data-toggle="tab">{$gname} ({$row|@count})</a></li>
            {/foreach}
        <li class="pull-right">
            <input type="button" value="Upgrade/Downgrade" class="btn" onclick="window.open('clientsupgrade.php?id=29', '', 'width=750,height=350,scrollbars=yes')">
            <input type="button" value="Move Product/Service" class="btn" onclick="window.open('clientsmove.php?type=hosting&amp;id=29', 'movewindow', 'width=500,height=300,top=100,left=100,scrollbars=yes')"> &nbsp;&nbsp;&nbsp;
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_all">
            {foreach from=$userarray key=gname item=row}
                <div class="tablebg">
                    <table id="sortabletbl1" class="datatable table" width="100%" border="0" cellspacing="1" cellpadding="3">
                        <tbody>
                            <tr>
                                <th>Acccount ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            {foreach from=$row item=item}
                                <tr>
                                    <td><a href="/admin/clientsservices.php?userid={$userid}&id={$item.id}">{$item.id}</a></td>
                                    <td>{$item.name}</td>
                                    <td>{$item.description}</td>
                                    <td>{$item.status}</td>
                                    <td></td>
                                    <td></td>
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
                            <tr>
                                <th>Acccount ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            {foreach from=$row item=item}
                                <tr>
                                    <td>{$item.id}</td>
                                    <td>{$item.name}</td>
                                    <td>{$item.description}</td>
                                    <td>{$item.status}</td>
                                    <td></td>
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


