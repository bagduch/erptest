

<div class="box-header with-border">
    {include file="$template/clientproducts/productview.tpl"}
</div>

<form method="post" action="?userid={$userid}&amp;id={$id}{if $aid}&aid={$aid}{/if}" id="frm1">

    <input type="hidden" name="frm1" value="1">
    <div class="box-header with-border">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_account" data-toggle="tab">Account Details</a></li>
                    {if $servicefield}
                    <li><a href="#tab_customer" data-toggle="tab">Customer Field</a></li>
                    {/if}
                <li><a href="#tab_invoice" data-toggle="tab">Account Invoices</a></li>
                <li><a href="#tab_log" data-toggle="tab">Account Log</a></li>
                <li class="pull-right">
                    <a style="float:right" href="#" class="btn btn-danger" onclick="deleteaccount({$id});"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                    <a style="float:right;margin-right:10px" class="btn btn-primary" href="ordersadd.php?userid={$userid}"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_account">
                    <div class="row">
                        <div class="col-xs-6">
                            <table class="datatable table">
                                <tr>
                                    <td width="50%"><label for="orderid">Order #</label></td>
                                    <td>{$products[$id].orderid}- <a href="orders.php?action=view&id={$products[$id].orderid}" class="btn btn-primary">View Order</a></td>
                                </tr>
                                <tr>
                                    <td><label for="orderid">Service</label></td>
                                    <td>
                                        <select name="packageid" class="form-control">
                                            {$servicedrop}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#description">Description (Address)</label></td>
                                    <td><input id="description" name="description" type="text" class="form-control" value="{$products[$id].description}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#status">Order Status</label></td>
                                    <td>{$status}</td>
                                </tr>
                                <tr>
                                    <td><label for="#promocode">Promotion Code</label></td>
                                    <td>
                                        <select class="form-control" id="promocode" name="promocode">

                                            <option value="">None</option>
                                            {if $promo}
                                                {foreach from=$promo key=promoid item=row}
                                                    <option value="{$promoid}">{$row}</option>
                                                {/foreach}
                                            {/if}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Subscription ID</label></td>
                                    <td><input class="form-control"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-6">
                            <table class="datatable table">
                                <tr>
                                    <td width="50%"><label for="#regdate">Registration Date</label></td>
                                    <td><input id="regdate" name="regdate" type="text" class="form-control" value="{$products[$id].regdate}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">First Payment Amount</label></td>
                                    <td><input id="firstpaymentamount" name="firstpaymentamount" type="text" class="form-control" value="{$products[$id].firstpaymentamount}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Recurring Amount</label></td>
                                    <td><input id="amount" name="amount" type="text" class="form-control" value="{$products[$id].amount}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#nextduedate">Next Due Date</label></td>
                                    <td><input id="nextduedate" name="nextduedate" type="text" class="form-control nextduedate" value="{$products[$id].nextduedate}"></td>

                                </tr>
                                <tr>
                                    <td><label for="#amount">Billing Cycle</label></td>
                                    <td>{$billingcycle}</td>
                                </tr>
                                <tr>
                                    <td><label for="#paymentmethod">Payment Method</label></td>
                                    <td>{$paymentmethod}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                {if $servicefield}
                    <div class="tab-pane" id="tab_customer">
                        <div class="row">
                            <div class="col-xs-6">
                                <table class="table">
                                    {foreach from=$servicefieldnd key=fieldidnd item=fieldsnd}
                                        {if $fieldsnd.fieldtype eq "text"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldidnd}">{$fieldsnd.fieldname}</label></td>
                                                <td><input class="form-control" id="custome{$fieldid}"  name="customefield[{$fieldsnd.cfid}]" value="{$fieldsnd.value}"></td>
                                            </tr>
                                        {elseif $fieldsnd.fieldtype eq "date"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldidnd}">{$fieldsnd.fieldname}</label></td>
                                                <td><input class="form-control datecontroller" id="custome{$fieldidnd}" name="customefield[{$fieldsnd.cfid}]" value="{$fieldsnd.value}"></td>
                                            </tr>
                                        {elseif $fieldsnd.fieldtype eq "more"}
                                            {foreach from=$fieldsnd.children item=childrenfield}
                                                <tr>
                                                    <td width="50%"><label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label></td>
                                                    <td><input class="form-control" id="custome{$childrenfield.cfid}" name="customefield[{$childrenfield.cfid}]" value="{$childrenfield.value}"></td>
                                                </tr>
                                            {/foreach}
                                        {else}
                                        {/if}
                                    {/foreach}
                                </table>  
                            </div>
                            <div class="col-xs-6">
                                <table class="table">

                                    {foreach from=$servicefield key=fieldid item=fields}
                                        {if $fields.fieldtype eq "text"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldid}">{$fields.fieldname}</label></td>
                                                <td><input class="form-control" id="custome{$fieldid}" name="customefield[{$fields.cfid}]" value="{$fields.value}"></td>
                                            </tr>
                                        {elseif $fields.fieldtype eq "date"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldid}">{$fields.fieldname}</label></td>
                                                <td><input class="form-control datecontroller" id="custome{$fieldid}" name="customefield[{$fields.cfid}]" value="{$fields.value}"></td>
                                            </tr>
                                        {elseif $fields.fieldtype eq "more"}
                                            {foreach from=$fields.children item=childrenfield}
                                                <tr>
                                                    <td width="50%"><label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label></td>
                                                    <td><input class="form-control" id="custome{$childrenfield.cfid}" name="customefield[{$childrenfield.cfid}]" value="{$childrenfield.value}"></td>
                                                </tr>
                                            {/foreach}
                                        {else}
                                        {/if}
                                    {/foreach}
                                </table>  
                            </div>
                        </div>
                    </div>
                {/if}
            
                <div class="tab-pane" id ="tab_invoice">

                </div>
                <div class="tab-pane" id ="tab_log">
                    {if $accountlog}
                        <div class="tablebg">
                            <table class="datatable table"> 
                                <tbody>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                    </tr>

                                    {foreach from=$accountlog item=data}
                                        <tr>
                                            <td>{$data.date}</td>
                                            <td>{$data.description}</td>
                                            <td>{$data.user}</td>
                                            <td>{$data.ipaddr}</td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>   
                        </div>
                    {/if}

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Addons</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="#addonname">Addon Names</label>
                        <select class='form-control' id='addonname' name='addonid'>
                            <option value="0">Please Choose Addons</option>
                            {foreach from=$alladdons item=item}
                                <option value="{$item.id}">{$item.name}</option>
                            {/foreach}
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="payment">Payment Method</label>
                        {$paymentmethod}
                    </div>
                    <input type="checkbox">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary addonaddbutton">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div align="center"><input type="submit" value="Save Changes" class="btn btn-primary"> <input type="reset" value="Cancel Changes" class="btn">
    </div>

</form>