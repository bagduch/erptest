<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header card-header">
                <h3 class="title">Edit Product</h3>
            </div>
            <div class="content">
                <form method="post" action="configservices.php?action=save&amp;id={$services.id}" name="packagefrm">
                    <input type="hidden" name="mytab">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" id="myTab">
                            <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                            <li role="presentation"><a href="#pricing" aria-controls="pricing" role="tab" data-toggle="tab">Pricing</a></li>
                            <li role="presentation" class=""><a href="#module" aria-controls="module" role="tab" data-toggle="tab">Module Settings</a></li>
                            <li role="presentation" class=""><a href="#configurable" aria-controls="configurable" role="tab" data-toggle="tab">Configurable Options</a></li>
                            <li role="presentation" class=""><a href="#addons" aria-controls="addons" role="tab" data-toggle="tab">Addons</a></li>
                            <li role="presentation" class=""><a href="#other" aria-controls="other" role="tab" data-toggle="tab">Other</a></li>
                            <li role="presentation" class=""><a href="#links" aria-controls="links" role="tab" data-toggle="tab">Links</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="details">
                                <div class="panel-body">
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                        <tbody>
                                            <tr>
                                                <td class="fieldlabel">{$langs.fields}</td>
                                                <td class="fieldarea">
                                                    <select class="form-control" name="type">
                                                        <option value="residential" {if $services.type eq 'residential'}selected{/if}>Residential</option>
                                                        <option value="business" {if $services.type eq 'business'}selected{/if}>Business</option>
                                                    </select>
                                                    <input type="hidden" name="gid" value="{$groupsid}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Revenue Codes</td>
                                                <td class="fieldarea">
                                                    <input class="form-control" type="text" size="40" name="rcode" value="{$services.revenuecode}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Supplier Codes</td>
                                                <td class="fieldarea">
                                                    <input class="form-control" type="text" size="40" name="scode" value="{$services.supplycode}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Supplier Revenue</td>
                                                <td class="fieldarea">
                                                    <input class="form-control" type="text" size="40" name="srev" value="{$services.supplyreve}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Individual Sale</td>
                                                <td class="fieldarea">
                                                    <input type="checkbox" name="isale" {if $services.individual}checked{/if}/> (Can be sale as an individual product)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="fieldlabel">{$langs.servicename}</td>
                                                <td class="fieldarea">
                                                    <input class="form-control" type="text" size="40" name="name" value="{$services.name}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">{$langs.servicedesc}</td>
                                                <td class="fieldarea">
                                                    <table cellsapcing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <textarea class="form-control" name="description" cols="60" rows="5">{$services.description}</textarea>
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
                                                <td class="fieldarea"><label><input type="checkbox" name="tax" {if $services.tax}checked{/if}> {$lang.applytaxdesc}</label></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">{$langs.hidden}</td><td class="fieldarea"><label><input type="checkbox" name="hidden" {if $services.hidden}checked{/if}> {$langs.hiddendesc}</label></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">{$langs.retired}</td>
                                                <td class="fieldarea"><label><input type="checkbox" name="retired" {if $services.retired}checked{/if}> {$langs.retireddesc}</label></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">{$langs.contract}</td>
                                                <td class="fieldarea"><label><input type="checkbox" name="contract" {if $services.etf}checked{/if}>{$langs.contractdes}</label></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td class="fieldarea">
                                                    <div style="display: none" id="contractop" class="form-inline">
                                                        <input style="width:40px" class="form-control" type="text" name="etf" value="{$services.etf}"> <label>{$langs.etf}</label>
                                                        <input style="width:40px" class="form-control" type="text" name="term" value="{$services.term}"> <label>{$langs.term}</label>
                                                    </div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                            <div role="tabpanel" class="tab-pane" id="pricing">
                                <div class="panel-body">
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                        <tbody>
                                            <tr>
                                                <td class="fieldlabel">Payment Type</td>
                                                <td class="fieldarea">
                                                    <label><input type="radio" name="paytype" {if $services.paytype eq 'free'}checked{/if} value="free"> Free</label>
                                                    <label><input type="radio" name="paytype" {if $services.paytype eq 'onetime'}checked{/if} value="onetime"> One Time</label>
                                                    <label><input type="radio" name="paytype" {if $services.paytype eq 'recurring'}checked{/if} value="recurring"> Recurring</label>
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
                                                </td>
                                            </tr>
                                            <tr><td class="fieldlabel">Allow Multiple Quantities</td><td class="fieldarea"><input type="checkbox" name="allowqty" {if $services.allowqty}checked{/if}> Tick this box to allow customers to specify if they want more than 1 of this item when ordering (must not require separate config)</td></tr>
                                            <tr><td class="fieldlabel">Recurring Cycles Limit</td>
                                                <td class="fieldarea">
                                                    <div class="form-inline">
                                                        <input style="width:50px" class="form-control" type="text" name="recurringcycles" value="{$services.recurringcycles}" size="7">
                                                        To limit this service to only recur a fixed number of times, enter the total number of times to invoice (0 = Unlimited)
                                                    </div>
                                                </td></tr>
                                            <tr><td class="fieldlabel">Auto Terminate/Fixed Term</td><td class="fieldarea">
                                                    <div class="form-inline">
                                                        <input style="width:50px" class="form-control" type="text" name="autoterminatedays" value="{$services.autoterminatedays}" size="7"> Enter the number of days after activation to automatically terminate (eg. free trials, time limited services, etc...)</div></td></tr>
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
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                        <tbody><tr><td class="fieldlabel" width="150">Module Name</td><td class="fieldarea">
                                                    <select class="form-control" name="servertype">
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
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
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

                            <div role="tabpanel" class="tab-pane" id="other">
                                <div class="panel-body">
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                        <tbody>
                                            <tr>
                                                <td class="fieldlabel">Custom Affiliate Payout</td>
                                                <td class="fieldarea">
                                                    <input type="radio" name="affiliatepaytype" {if $services.affiliatepaytype eq ''}checked{/if} value=""> Use Default 
                                                    <input type="radio" name="affiliatepaytype" {if $services.affiliatepaytype eq 'percentage'}checked{/if} value="percentage"> Percentage 
                                                    <input type="radio" name="affiliatepaytype" {if $services.affiliatepaytype eq 'fixed'}checked{/if} value="fixed"> Fixed Amount 
                                                    <input type="radio" name="affiliatepaytype" {if $services.affiliatepaytype eq 'none'}checked{/if} value="none"> No Commission
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Affiliate Pay Amount</td>
                                                <td class="fieldarea">
                                                    <input style="width:60px" class="form-control" type="text" name="affiliatepayamount" value="{$services.affiliatepayamount}" size="10"> 
                                                    <input type="checkbox" name="affiliateonetime" {if $services.affiliateonetime eq 'on'}checked{/if}> One Time Payout (Default is Recurring)
                                                </td>
                                            </tr>


                                        </tbody></table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="links">
                                <div class="panel-body">
                                    <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                                        <tbody>
                                            <tr>
                                                <td class="fieldlabel">Direct Shopping Cart Link</td>
                                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="/cart.php?a=add&amp;pid={$services.id}" readonly=""></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Direct Shopping Cart Link Specifying Template</td>
                                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="/cart.php?a=add&amp;pid={$services.id}&amp;carttpl=cart" readonly=""></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Direct Shopping Cart Link Including Domain</td>
                                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="/cart.php?a=add&amp;pid={$services.id}&amp;sld=ra&amp;tld=.com" readonly=""></td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">Service Group Cart Link</td>
                                                <td class="fieldarea"><input class="form-control" type="text" size="100" value="/cart.php?gid={$services.gid}" readonly=""></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>


                    <p align="center">
                        <input type="submit" value="Save Changes" class="btn btn-default"> 
                        <input type="button" value="Back to Service List" onclick="window.location = 'configservices.php'" class="btn btn-default">
                    </p>

                    <input type="hidden" name="tab" id="tab" value="">

                </form>
            </div>
        </div>
    </div>
</div>

{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        $(document).ready(function () {

            $("#myTab a").click(function () {
                $("input[name='mytab']").val($(this).attr('href').slice(1));
            });

            var mytab = "{/literal}{$mytab}{literal}";
            $("#myTab a[href$='#"+mytab+"']").tab('show');
            function checkgroup()
            {
                all_checkbox = $(".list-group").find("input[type='checkbox']");
                if (all_checkbox.filter(":checked").length === all_checkbox.length)
                {
                    all_checkbox.closest(".panel").find(".groupcheck").prop("checked", true);
                } else {
                    all_checkbox.closest(".panel").find(".groupcheck").prop("checked", false);
                }
            }

            checkgroup();
            $(".childrenserivce").change(checkgroup);
            $(".groupcheck").change(function () {
                childrenchecks = $(this).closest(".panel-body").find(".childrenserivce");
                if ($(this).is(":checked"))
                {
                    childrenchecks.prop('checked', true);
                } else {
                    childrenchecks.attr('checked', false);
                }

            });


            function pricetag()
            {
                value = $("input[name='paytype']:checked").val();
                if (value == 'onetime')
                {
                    $(".tableprice").find("table tr td").hide();
                    $(".tableprice").find(".onetime").show();
                } else if (value == 'free')
                {
                    $(".tableprice").hide();
                } else
                {
                    $(".tableprice").find("table tr td").show();
                    $(".tableprice").show();
                }
            }
            pricetag();
            $("input[name='paytype']").change(function () {
                pricetag();
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
                window.location = 'configservices.php?action=edit&id=3&tab=3&sub=deletecustomfield&fid=' + id + '&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502';
            }
        }
        function deleteoption(id) {
            if (confirm("Are you sure you want to delete this product configuration?")) {
                window.location = 'configservices.php?action=edit&id=3&tab=4&sub=deleteoption&confid=' + id + '&token=6ca3bb31e4fdac841bf9a61cb01482dce4be5502';
            }
        }
        function showDialog(name) {
            $("#" + name).dialog('open');
        }
    </script>


{/literal}
