<div style="float:left;width:100%;">
    <h1>Services</h1>
    <script type="text/javascript" src="../includes/jscript/jquerylq.js"></script>
    <script type="text/javascript" src="../includes/jscript/jqueryFileTree.js"></script>
    <link href="../includes/jscript/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen">

    <h2>Edit Product</h2>

    <form method="post" action="/admin/configservices.php?action=save&amp;id={$data.id}" name="packagefrm">
        <div id="myTabs">
            {$infobox}
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                <li role="presentation" class=""><a href="#pricing" aria-controls="pricing" role="tab" data-toggle="tab">Pricing</a></li>
                <li role="presentation" class=""><a href="#module" aria-controls="module" role="tab" data-toggle="tab">Module Settings</a></li>
                <li role="presentation" class=""><a href="#configurable" aria-controls="configurable" role="tab" data-toggle="tab">Configurable Options</a></li>
                <li role="presentation" class=""><a href="#addons" aria-controls="addons" role="tab" data-toggle="tab">Addons</a></li>
                <li role="presentation" class=""><a href="#other" aria-controls="other" role="tab" data-toggle="tab">Other</a></li>
                <li role="presentation" class=""><a href="#links" aria-controls="links" role="tab" data-toggle="tab">Links</a></li>
            </ul>
        </div>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="details">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">{$langs.fields}</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="type" onchange="doFieldUpdate()">
                                        <option value="hostingaccount" {if $data.type eq 'hostingaccount'}selected{/if}>{$langs.hostingaccount}</option>
                                        <option value="reselleraccount" {if $data.type eq 'reselleraccount'}selected{/if}>{$langs.reselleraccount}</option>
                                        <option value="server" {if $data.type eq 'server'}selected{/if}>{$langs.server}</option>
                                        <option value="other" {if $data.type eq 'other'}selected{/if}>{$langs.other}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.servicegroup}</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="gid">
                                        {foreach from=$servicegroups item=row key=gid}
                                            <option value="{$gid}" {if $gid==$data.gid}selected{/if}>{$row}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Revenue Codes</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" size="40" name="rcode" value="{$data.revenuecode}">
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.servicename}</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" size="40" name="name" value="{$data.name}">
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.servicedesc}</td>
                                <td class="fieldarea">
                                    <table cellsapcing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <textarea class="form-control" name="description" cols="60" rows="5">{$data.description}</textarea>
                                                </td>
                                                <td>
                                                    {$langs.htmlallowed}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.welcomeemail}</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="welcomeemail">
                                        {foreach from = $autoemail item=row key=id}
                                            <option value="{$id}" {$row.select}>{$row.name}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.applytax}</td>
                                <td class="fieldarea"><label><input type="checkbox" name="tax" {if $data.tax}checked{/if}> {$lang.applytaxdesc}</label></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.hidden}</td><td class="fieldarea"><label><input type="checkbox" name="hidden" {if $data.hidden}checked{/if}> {$langs.hiddendesc}</label></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.retired}</td>
                                <td class="fieldarea"><label><input type="checkbox" name="retired" {if $data.retired}checked{/if}> {$langs.retireddesc}</label></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">{$langs.contract}</td>
                                <td class="fieldarea"><label><input type="checkbox" name="contract" {if $data.etf}checked{/if}>{$langs.contractdes}</label></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="fieldarea">
                                    <div style="display: none" id="contractop" class="form-inline">
                                        <input style="width:40px" class="form-control" type="text" name="etf" value="{$data.etf}"> <label>{$langs.etf}</label>
                                        <input style="width:40px" class="form-control" type="text" name="term" value="{$data.term}"> <label>{$langs.term}</label>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <div role="tabpanel" class="tab-pane" id="pricing">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Payment Type</td>
                                <td class="fieldarea">
                                    <label><input type="radio" name="paytype" {if $data.paytype eq 'free'}checked{/if} value="free"> Free</label>
                                    <label><input type="radio" name="paytype" {if $data.paytype eq 'onetime'}checked{/if} value="onetime"> One Time</label>
                                    <label><input type="radio" name="paytype" {if $data.paytype eq 'recurring'}checked{/if} value="recurring" checked=""> Recurring</label>
                                </td>
                            </tr>
                            <tr class="tableprice">
                                <td colspan="2" align="center"><br>
                                    <table cellspacing="1" bgcolor="#cccccc">
                                        <tbody>
                                            <tr bgcolor="#efefef" style="text-align:center;font-weight:bold">
                                                <td class="onetime" width="80">Currency</td>
                                                <td class="onetime" width="80"></td>
                                                <td class="onetime" width="120">One Time/Monthly</td>
                                                <td width="90">Quarterly</td>
                                                <td width="100">Semi-Annually</td>
                                                <td width="90">Annually</td>
                                                <td width="90">Biennially</td>
                                                <td width="90">Triennially</td>
                                            </tr>
                                            {foreach from=$tabledata item=tdata key=currency_id}
                                                <tr bgcolor="#ffffff" style="text-align:center">
                                                    <td class="onetime" rowspan="2" bgcolor="#efefef"><b>{$tdata.code}</b></td>
                                                    <td class="onetime">{$langs.setupfee}</td>
                                                    <td class="onetime"><input class="form-control" type="text" name="currency[{$currency_id}][msetupfee]" size="10" value="{$tdata.msetupfee}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][qsetupfee]" size="10" value="{$tdata.qsetupfee}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][ssetupfee]" size="10" value="{$tdata.ssetupfee}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][asetupfee]" size="10" value="{$tdata.asetupfee}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][bsetupfee]" size="10" value="{$tdata.bsetupfee}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][tsetupfee]" size="10" value="{$tdata.tsetupfee}"></td>
                                                </tr>
                                                <tr bgcolor="#ffffff" style="text-align:center">
                                                    <td class="onetime">Price</td>
                                                    <td class="onetime"><input class="form-control" type="text" name="currency[{$currency_id}][monthly]" size="10" value="{$tdata.monthly}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][quarterly]" size="10" value="{$tdata.quarterly}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][semiannually]" size="10" value="{$tdata.semiannually}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][annually]" size="10" value="{$tdata.annually}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][biennially]" size="10" value="{$tdata.biennially}"></td>
                                                    <td><input class="form-control" type="text" name="currency[{$currency_id}][triennially]" size="10" value="{$tdata.triennially}"></td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                    <br>
                                    (Set Price to -1.00 to disable any of the payment term options - leave Setup Fee at zero)<br><br>
                                </td></tr>
                            <tr><td class="fieldlabel">Allow Multiple Quantities</td><td class="fieldarea"><input type="checkbox" name="allowqty" {if $data.allowqty}checked{/if}> Tick this box to allow customers to specify if they want more than 1 of this item when ordering (must not require separate config)</td></tr>
                            <tr><td class="fieldlabel">Recurring Cycles Limit</td>
                                <td class="fieldarea">
                                    <div class="form-inline">
                                        <input style="width:50px" class="form-control" type="text" name="recurringcycles" value="{$data.recurringcycles}" size="7">
                                        To limit this service to only recur a fixed number of times, enter the total number of times to invoice (0 = Unlimited)
                                    </div>
                                </td></tr>
                            <tr><td class="fieldlabel">Auto Terminate/Fixed Term</td><td class="fieldarea">
                                    <div class="form-inline">
                                        <input style="width:50px"  class="form-control" type="text" name="autoterminatedays" value="{$data.autoterminatedays}" size="7"> Enter the number of days after activation to automatically terminate (eg. free trials, time limited services, etc...)</div></td></tr>
                            <tr><td class="fieldlabel">Termination Email</td><td class="fieldarea">
                                    <div class="form-inline">
                                        <select class="form-control" name="autoterminateemail">
                                            <option value="0">None</option>
                                            {foreach from = $autoemail item=evalue key=eid}
                                                <option {$evalue.termninate} value="{$eid}">{$evalue.name}</option>
                                            {/foreach}

                                        </select> Choose the email template to send when the fixed term comes to an end</div></td></tr>
                        </tbody></table>
                </div>

            </div>
            <div role="tabpanel" class="tab-pane" id="module">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody><tr><td class="fieldlabel" width="150">Module Name</td><td class="fieldarea">
                                    <select class="form-control" name="servertype" onchange="submit()">
                                        <option value="">None</option>
                                        {foreach from=$modulesarray item=module}
                                            <option value="{$module.name}" {$module.select}>{$module.name}</option>
                                        {/foreach}
                                    </select></td></tr>
                        </tbody></table>
                </div>

                <br>


            </div>
            <div role="tabpanel" class="tab-pane" id="configurable">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="150" class="fieldlabel">Assigned Option Groups</td>
                                <td class="fieldarea">
                                    <select class="form-control" name="configoptionlinks[]" size="8" style="width:90%" multiple="">
                                        {foreach from=$configservice item=servicegroup}
                                            <option {$servicegroup.current} value="{$servicegroup.cfgid}">{$servicegroup.name}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="addons">
                <div class="panel-body">
                    {if $addons}
                        <select class="form-control" name="addons[]" multiple="">
                            {foreach from=$addons item=addon}
                                <option {$addon.check} value="{$addon.id}">{$addon.name}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="other">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Custom Affiliate Payout</td>
                                <td class="fieldarea">
                                    <input type="radio" name="affiliatepaytype" {if $data.affiliatepaytype eq ''}checked{/if} value=""> Use Default 
                                    <input type="radio" name="affiliatepaytype" {if $data.affiliatepaytype eq 'percentage'}checked{/if} value="percentage"> Percentage 
                                    <input type="radio" name="affiliatepaytype" {if $data.affiliatepaytype eq 'fixed'}checked{/if} value="fixed"> Fixed Amount 
                                    <input type="radio" name="affiliatepaytype" {if $data.affiliatepaytype eq 'none'}checked{/if} value="none"> No Commission
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Affiliate Pay Amount</td>
                                <td class="fieldarea">
                                    <input style="width:60px" class="form-control" type="text" name="affiliatepayamount" value="{$data.affiliatepayamount}" size="10"> 
                                    <input type="checkbox" name="affiliateonetime" {if $data.affiliateonetime eq 'on'}checked{/if}> One Time Payout (Default is Recurring)
                                </td>
                            </tr>


                        </tbody></table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="links">
                <div class="panel-body">
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td class="fieldlabel">Direct Shopping Cart Link</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}" readonly=""></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Direct Shopping Cart Link Specifying Template</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}&amp;carttpl=cart" readonly=""></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Direct Shopping Cart Link Including Domain</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}&amp;sld=ra&amp;tld=.com" readonly=""></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Service Group Cart Link</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?gid={$data.gid}" readonly=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p align="center">
            <input type="submit" value="Save Changes" class="button"> 
            <input type="button" value="Back to Service List" onclick="window.location = 'configservices.php'" class="button">
        </p>

        <input type="hidden" name="tab" id="tab" value="">

    </form>
</div>

{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        $(document).ready(function () {

            $('#productdownloadsbrowser').fileTree({root: '0', script: 'configservices.php?action=getdownloads&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502', folderEvent: 'click', expandSpeed: 1, collapseSpeed: 1}, function (file) {
                $.post("configservices.php?action=managedownloads&id=3&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502&adddl=" + file, function (data) {
                    $("#productdownloadslist").html(data);
                });
            });


            $("input[name='paytype']").change(function () {
                value = $(this).val();
                if (value == 'onetime')
                {
                    $(".tableprice").find("table tr td").hide();
                    $(".tableprice").find(".onetime").show();
                }
                else if (value == 'free')
                {
                    $(".tableprice").hide();
                }
                else
                {
                    $(".tableprice").find("table tr td").show();
                    $(".tableprice").show();
                }

            });

            if ($("input[name='contract']").is(':checked'))
            {
                $("#contractop").show();
            }
            $("input[name='contract']").click(function () {
                if ($(this).is(":checked"))
                {
                    $("#contractop").show();
                } else {
                    $("#contractop").hide();
                }
            });
            $(".removedownload").livequery("click", function (event) {
                var dlid = $(this).attr("rel");
                $.post("configservices.php?action=managedownloads&id=3&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502&remdl=" + dlid, function (data) {
                    $("#productdownloadslist").html(data);
                });
            });
            $("#showquickupload").click(
                    function () {
                        $("#quickupload").dialog("open");
                        $("#quickupload").load("configservices.php?action=quickupload&id=3&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502");
                        return false;
                    }
            );
            $("#showadddownloadcat").click(
                    function () {
                        $("#adddownloadcat").dialog("open");
                        $("#adddownloadcat").load("configservices.php?action=adddownloadcat&id=3&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502");
                        return false;
                    }
            );

            $(".tabbox").css("display", "none");

        });

        function showDialog(name) {
            $("#" + name).dialog('open');
        }

        function deletecustomfield(id) {
            if (confirm("Are you sure you want to delete this field and ALL DATA associated with it?")) {
                window.location = '/admin/configservices.php?action=edit&id=3&tab=3&sub=deletecustomfield&fid=' + id + '&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502';
            }
        }
        function deleteoption(id) {
            if (confirm("Are you sure you want to delete this product configuration?")) {
                window.location = '/admin/configservices.php?action=edit&id=3&tab=4&sub=deleteoption&confid=' + id + '&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502';
            }
        }
        function showDialog(name) {
            $("#" + name).dialog('open');
        }
    </script>


{/literal}