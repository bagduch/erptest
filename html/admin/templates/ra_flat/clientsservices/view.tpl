


{include file="$template/clientsservices/serviceview.tpl"}

<div class="card">
    <div class="content">
        <form method="post" class="form-horizontal" action="?userid={$userid}&amp;id={$id}{if $aid}&aid={$aid}{/if}" id="frm1">

            <input type="hidden" name="frm1" value="1">
            <div class="box-header with-border">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_account" data-toggle="tab">Account Details</a></li>
                        <li><a href="#tab_customfields" data-toggle="tab">Custom Fields</a></li>
                        <li><a href="#tab_addon" data-toggle="tab">Addons</a></li>
                        <li><a href="#tab_invoice" data-toggle="tab">Account Invoices</a></li>
                        <li><a href="#tab_log" data-toggle="tab">Account Log</a></li>
                        <li><a href="#tab_notes" data-toggle="tab">Notes</a></li>
                        <li class="pull-right">
                            <a style="float:right" href="#" class="btn btn-danger" onclick="deleteaccount({$id});" data-toggle="tooltip" data-placement="top" title="Delete this Account"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                            <a style="float:right;margin-right:10px" class="btn btn-default" href="ordersadd.php?userid={$userid}" data-toggle="tooltip" data-placement="top" title="Add an Account"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_account">

                            {* Account Details *}
                            <div class="row">
                                <h3>Account Details</h3>
                            </div>
                            <div class="row"> {*Order/Account stuff*}

                                {* Order Number *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="orderid">Order #</label>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="orders.php?action=view&id={$services.orderid}">
                                                <input 
                                                    id="orderid" name="orderid" type="text" readonly 
                                                    class="form-control" value="{$services.orderid}" 
                                                />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {* Service *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="packageid">Service</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="packageid" id="packageid" class="form-control">
                                                {$servicedrop}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {* Description *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="description">Description (Address)</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="description" id="description" type="text"  class="form-control" value={$services.description} />
                                        </div>
                                    </div>
                                </div>


                                {* Order Status *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="status">Order Status</label>
                                        </div>
                                        <div class="col-md-6">
                                            {$status}
                                        </div>
                                    </div>
                                </div>

                                {* Promo Code *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="promocode">Promo Code</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control" id="promocode" name="promocode">
                                                <option value="">None</option>
                                                {if $promo}
                                                    {foreach from=$promo key=promoid item=row}
                                                        <option value="{$promoid}">{$row}</option>
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {* Registration Date *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="regdate">Registration Date</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="regdate" name="regdate" type="text" readonly class="form-control" value="{$services.regdate}" />
                                        </div>
                                    </div>
                                </div>

                                {* First Payment Amount*}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="firstpaymentamount">First Payment Amount</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="firstpaymentamount" name="firstpaymentamount" 
                                                type="text" class="form-control" value="{$services.firstpaymentamount}" 
                                            />
                                        </div>
                                    </div>
                                </div>

                                {* Recurring Amount*}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="recurringamount">Recurring Amount</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="recurringamount" name="recurringamount" 
                                                type="text" class="form-control" value="{$services.amount}" 
                                            />
                                        </div>
                                    </div>
                                </div>

                                {* Next Due Date*}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="nextduedate">Next Due Date</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="nextduedate" name="nextduedate" 
                                                type="text" class="form-control datepick" value="{$services.nextduedate}"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {* Billing Cycle *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="billingcycle">Billing Cycle</label>
                                        </div>
                                        <div class="col-md-6">
                                            {$billingcycle}
                                        </div>
                                    </div>
                                </div>

                                {* Payment Method *}
                                <div class="col-md-6">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label for="paymentmethod">Payment Method</label>
                                        </div>
                                        <div class="col-md-6">
                                            {$paymentmethod}
                                        </div>
                                    </div>
                                </div>
                            </div> {* end overflowed row *}

                            {* Buttons *}
                            <div class="row">
                                <div class="col-md-12 col-md-offset-3">
                                    <input type="submit" value="Save Changes" class="btn btn-primary" />
                                    <input type="reset" value="Cancel Changes" class="btn" />
                                </div>
                            </div>


                            
                        </div> {* End tab_account div *}


                        {* customfields *}
                        <div class="tab-pane" id="tab_customfields">
                            {* Account Details *}
                            <div class="row">
                                <h3>Custom Fields</h3>
                            </div>
                            <div class="row">
                                {* loop through servicefieldnd *}
                                {foreach from=$servicefieldnd key=fieldidnd item=fieldsnd}
                                    {if $fieldsnd.fieldtype eq "text"}
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label for="#custome{$fieldidnd}">
                                                    {$fieldsnd.fieldname}
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" 
                                                    id="custome{$fieldid}"  name="customfield[{$fieldsnd.cfid}]" value="{$fieldsnd.value}" 
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    {elseif $fieldsnd.fieldtype eq "date"}
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label for="#custome{$fieldidnd}">
                                                    {$fieldsnd.fieldname}
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control datepick" id="custome{$fieldidnd}" 
                                                    name="customfield[{$fieldsnd.cfid}]" value="{$fieldsnd.value}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    {elseif $fieldsnd.fieldtype eq "more"}
                                        {foreach from=$fieldsnd.children item=childrenfield}
                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form-control" id="custome{$childrenfield.cfid}" 
                                                        name="customfield[{$childrenfield.cfid}]" value="{$childrenfield.value}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        {/foreach}
                                    {/if}
                                {/foreach} {* end servicefieldnd loop*}
                                {foreach from=$servicefield key=fieldid item=fields}
                                    {if $fields.fieldtype eq "text"}
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label for="#custome{$fieldid}">{$fields.fieldname}</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" 
                                                    id="custome{$fieldid}" name="customfield[{$fields.cfid}]" value="{$fields.value}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    {elseif $fields.fieldtype eq "date"}
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label for="#custome{$fieldid}">{$fields.fieldname}</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control datepick" 
                                                    id="custome{$fieldid}" name="customfield[{$fields.cfid}]" value="{$fields.value}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    {elseif $fields.fieldtype eq "more"}
                                        {foreach from=$fields.children item=childrenfield}
                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form-control" id="custome{$childrenfield.cfid}" 
                                                        name="customfield[{$childrenfield.cfid}]" value="{$childrenfield.value}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        {/foreach}
                                    {/if}
                                {/foreach} {* end servicefield loop *}
                            </div> {*end overflowed CF row *}
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <input type="submit" value="Save Changes" class="btn btn-primary" />
                                    <input type="reset" value="Cancel Changes" class="btn" />
                                </div>
                            </div>
                        </div> {* end customfields handling *}

                        <div class="tab-pane" id="tab_addon">
                            {if isset($services.addon)}
                                <table class="datatable table" style="width:100%">
                                    <tr>
                                        <th>Reg Date</th>
                                        <th>Name</th>
                                        <th>First Payment</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Billing Cycle</th>
                                        <th>Action</th>
                                    </tr>
                                    {foreach from=$services.addon item=itema}
                                        <tr>
                                            <td>{$itema.regdate}</td>
                                            <td>{$itema.name}</td>
                                            <td>{$itema.firstpaymentamount}</td>
                                            <td>{$itema.amount}</td>
                                            <td>{$itema.servicestatus}</td>
                                            <td>{$itema.billingcycle}</td>
                                            <td>
                                                <a href="#" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="#" onclick="doDeleteAddon({$itema.id})" class="btn btn-danger">
                                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            {else}
                                <div class="alert alert-danger" role="alert">
                                    This addon doen't have any addon   <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-fw fa-plus-circle"></i>
                                    </button>
                                </div>
                            {/if}
                        </div>
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
                        <div class="tab-pane" id="tab_notes">


                            <table class="datatable table">
                                <tr>
                                    <th>Create Date</th>
                                    <th>Notes</th>
                                    <th>Create Admin</th>
                                    <th>Assign To</th>
                                    <th>Due Date</th>
                                    <th>Update Time</th>
                                    <th>Status</th>
                                </tr>
                                {foreach from=$tabledata item=row}
                                    {if $row[0] eq 'account'}
                                        <tr class="itemrow_{$row[10]}">
                                            <td>{$row[1]}</td>
                                            <td>{$row[2]}</td>
                                            <td>{$row[3]}</td>
                                            <td>{$row[4]}</td>
                                            <td>{$row[5]}</td>
                                            <td>{$row[6]}</td>
                                            <td>{$row[7]}</td>
                                        </tr>
                                    {/if}
                                {/foreach}
                            </table>
                            <input type="hidden" name="userid" value="{$userid}">
                            <input type="hidden" name="account" value="{$id}">
                            <input type="hidden" name="rel_type" value="account">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Notes</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Assign To</label>
                                    <select class="form-control" name="assign">
                                        {foreach from=$adminlist item=row}
                                            <option value="{$row.id}">{$row.firstname} {$row.lastname}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input class="datepick form-control" type="text" name="duedate">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="import" value="1">  <label>Important</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary addnotes">Add Notes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </form>
    </div>
</div>

<div class="card">
    <div class="content">
        <div class="row">
            <form method="post" action="clientsemails.php?userid={$userid}" name="frm3" id="frm3">

                <input type="hidden" name="__fpfrm3" value="1">
                <input type="hidden" name="action" value="send">
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="id" value="{$id}">

                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Send Message</label>
                        <div class="col-sm-10">
                            {if $emaildropdown}
                                <select class="form-control" name="messagename">
                                    {foreach item=row from=$emaildropdown}
                                        <option value="{$row}">{$row}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="form-group">
                        <input type="submit" value="Send Message" class="btn">
                    </div>
                </fieldset>
            </form>
            <form method="post" action="clientsemails.php?userid=2" name="frm4" id="frm4">
                <input type="hidden" name="__fpfrm4" value="1">
                <input type="hidden" name="action" value="send">
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="id" value="{$id}">
                <input type="hidden" name="messagename" value="defaultnewacc">
                <input type="submit" value="Resend Product Welcome Email" class="btn">
            </form>

        </div>
    </div>
</div>

{foreach from=$test item=row}
    {$row}
{/foreach}

</div>

</div>
</div>

<div class="clear"></div>
{literal}
    <script type="text/javascript">
        $(document).ready(function () {

            $(".addonaddbutton").click(function () {
                $("#frm1").submit();
            });
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var target = $(e.target).attr("href") // activated tab
                if (target == "#tab_invoice")
                {
                    $.ajax({
                        method: "POST",
                        url: "clientsinvoices.php?userid={/literal}{$userid}{literal}&serviceid={/literal}{$id}{literal}",
                        data: {ajax: 1},
                    }).done(function (data) {
                        $("#tab_invoice").html(data);
                    });
                }
            });


            $(".addnotes").click(function () {
                var token = $("input[name='token']").val();
                var notes = $("textarea[name='notes']").val();
                var assign = $("select[name='assign']").val();
                var duedate = $("input[name='duedate']").val();
                var imports = $("input[name='import']").val();
                var type = $("input[name='rel_type']").val();
                var account = $("input[name='account']").val();
                $.ajax({
                    url: "clientsnotes.php?sub=add",
                    method: "post",
                    data: {
                        "userid":{/literal}{$userid}{literal},
                        "token": token,
                        "notes": notes,
                        "assign": assign,
                        "duedate": duedate,
                        "rel_type": type,
                        "account": account,
                        "imports": imports
                    }}).done(function () {
                    location.reload();
                });
            });
        });

        function doDeleteAddon(id) {
            if (confirm("Are you sure you want to delete this addon?")) {
                window.location = 'clientsservices.php?userid={/literal}{$userid}{literal}&action=deladdon&aid=' + id + '&token={/literal}{$token}{literal}';
            }
        }
        function deleteaccount(id) {
            if (confirm("Are you sure you want to delete this account?")) {
                window.location = 'clientsservices.php?userid={/literal}{$userid}{literal}&action=delete&id=' + id + '&token={/literal}{$token}{literal}';
            }
        }

        function runModuleCommand(cmd, custom) {
            $("#mod" + cmd).dialog("close");
            $("#modcmdbtns").css("filter", "alpha(opacity=20)");
            $("#modcmdbtns").css("-moz-opacity", "0.2");
            $("#modcmdbtns").css("-khtml-opacity", "0.2");
            $("#modcmdbtns").css("opacity", "0.2");
            var position = $("#modcmdbtns").position();
            $("#modcmdworking").css("position", "absolute");
            $("#modcmdworking").css("top", position.top);
            $("#modcmdworking").css("left", position.left);
            $("#modcmdworking").css("padding", "9px 50px 0");
            $("#modcmdworking").fadeIn();
            var reqstr = "userid=1&id=35&modop=" + cmd + "&token=0951db7664024f53758d62b7cb94336b96566473";
            if (custom)
                reqstr += "&ac=" + custom;
            else if (cmd == "suspend")
                reqstr += "&suspreason=" + encodeURIComponent($("#suspreason").val()) + "&suspemail=" + $("#suspemail").is(":checked");
            $.post("clientsservices.php", reqstr,
                    function (data) {
                        if (data.substr(0, 9) == "redirect|") {
                            window.location = data.substr(9);
                        } else {
                            $("#servicecontent").html(data);
                        }
                    });
        }
    </script>
{/literal}


