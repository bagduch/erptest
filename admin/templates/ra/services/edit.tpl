<div style="float:left;width:100%;">
    <h1>Services</h1>
    <script type="text/javascript" src="../includes/jscript/jquerylq.js"></script>
    <script type="text/javascript" src="../includes/jscript/jqueryFileTree.js"></script>
    <link href="../includes/jscript/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen">

    <h2>Edit Product</h2>
    <form method="post" action="/admin/configservices.php?action=save&amp;id=3" name="packagefrm">
        <input type="hidden" name="token" value="a77ffd97d8a752f1165226d85ae274ae6f19f719"><div id="tabs">
            <ul>
                <li id="tab0" class="tab tabselected"><a href="javascript:;">Details</a></li>
                <li id="tab1" class="tab"><a href="javascript:;">Pricing</a></li>
                <li id="tab2" class="tab"><a href="javascript:;">Module Settings</a></li>
                <li id="tab3" class="tab"><a href="javascript:;">Configurable Options</a></li>
                <li id="tab4" class="tab"><a href="javascript:;">Other</a></li>
                <li id="tab5" class="tab"><a href="javascript:;">Links</a></li>
            </ul>
        </div>
        {$infobox}
        <div id="tab0box" class="tabbox">
            <div id="tab_content">
                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">{$langs.fields}</td>
                            <td class="fieldarea">
                                <select name="type" onchange="doFieldUpdate()">
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
                                <select name="gid">
                                    {foreach from=$servicegroups item=row  key=gid}
                                        <option value="{$gid}" {if $gid==$data.gid}selected{/if}>{$row}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">{$langs.servicename}</td>
                            <td class="fieldarea">
                                <input type="text" size="40" name="name" value="{$data.name}">
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">{$langs.servicedesc}</td>
                            <td class="fieldarea">
                                <table cellsapcing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <textarea name="description" cols="60" rows="5">{$data.description}</textarea>
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
                                <select name="welcomeemail">
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
                            <td class="fieldlabel">{$langs.hidden}</td><td class="fieldarea"><label><input type="checkbox" name="hidden"  {if $data.hidden}checked{/if}> {$langs.hiddendesc}</label></td>
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
                                <div style="display: none" id="contractop">
                                    <input style="width:40px" type="text" name="etf" value="{$data.etf}"> <label>{$langs.etf}</label>
                                    <input style="width:40px" type="text" name="term" value="{$data.term}"> <label>{$langs.term}</label>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div id="tab1box" class="tabbox" style="display: none;">
            <div id="tab_content">

                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel">Payment Type</td>
                            <td class="fieldarea">
                                <label><input type="radio" name="paytype" {if $data.paytype eq 'free'}checked{/if} value="free"> Free</label>
                                <label><input type="radio" name="paytype" {if $data.paytype eq 'onetime'}checked{/if}  value="onetime"> One Time</label>
                                <label><input type="radio" name="paytype" {if $data.paytype eq 'recurring'}checked{/if}  value="recurring" checked=""> Recurring</label>
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
                                                <td class="onetime"><input type="text" name="currency[{$currency_id}][msetupfee]" size="10" value="{$tdata.msetupfee}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][qsetupfee]" size="10" value="{$tdata.qsetupfee}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][ssetupfee]" size="10" value="{$tdata.ssetupfee}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][asetupfee]" size="10" value="{$tdata.asetupfee}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][bsetupfee]" size="10" value="{$tdata.bsetupfee}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][tsetupfee]" size="10" value="{$tdata.tsetupfee}"></td>
                                            </tr>
                                            <tr bgcolor="#ffffff" style="text-align:center">
                                                <td class="onetime">Price</td>
                                                <td class="onetime"><input type="text" name="currency[{$currency_id}][monthly]" size="10" value="{$tdata.monthly}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][quarterly]" size="10" value="{$tdata.quarterly}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][semiannually]" size="10" value="{$tdata.semiannually}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][annually]" size="10" value="{$tdata.annually}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][biennially]" size="10" value="{$tdata.biennially}"></td>
                                                <td><input type="text" name="currency[{$currency_id}][triennially]" size="10" value="{$tdata.triennially}"></td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                                <br>
                                (Set Price to -1.00 to disable any of the payment term options - leave Setup Fee at zero)<br><br>
                            </td></tr>
                        <tr><td class="fieldlabel">Allow Multiple Quantities</td><td class="fieldarea"><input type="checkbox" name="allowqty" {if $data.allowqty}checked{/if}> Tick this box to allow customers to specify if they want more than 1 of this item when ordering (must not require separate config)</td></tr>
                        <tr><td class="fieldlabel">Recurring Cycles Limit</td><td class="fieldarea"><input type="text" name="recurringcycles" value="{$data.recurringcycles}" size="7"> To limit this service to only recur a fixed number of times, enter the total number of times to invoice (0 = Unlimited)</td></tr>
                        <tr><td class="fieldlabel">Auto Terminate/Fixed Term</td><td class="fieldarea"><input type="text" name="autoterminatedays" value="{$data.autoterminatedays}" size="7"> Enter the number of days after activation to automatically terminate (eg. free trials, time limited services, etc...)</td></tr>
                        <tr><td class="fieldlabel">Termination Email</td><td class="fieldarea">
                                <select name="autoterminateemail">
                                    <option value="0">None</option>
                                    {foreach from = $autoemail item=evalue key=eid}
                                        <option {$evalue.termninate} value="{$eid}">{$evalue.name}</option>
                                    {/foreach}

                                </select> Choose the email template to send when the fixed term comes to an end</td></tr>
                    </tbody></table>

            </div>
        </div>
        <div id="tab2box" class="tabbox" style="display: none;">
            <div id="tab_content">

                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td class="fieldlabel" width="150">Module Name</td><td class="fieldarea">
                                <select name="servertype" onchange="submit()">
                                    <option value="">None</option>
                                    {foreach from=$modulesarray item=module}
                                        <option value="{$module.name}" {$module.select}>{$module.name}</option>
                                    {/foreach}
                                </select></td></tr>
                    </tbody></table>

                <br>


            </div>
        </div>

        <div id="tab3box" class="tabbox" style="display: none;">
            <div id="tab_content">

                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td width="150" class="fieldlabel">Assigned Option Groups</td><td class="fieldarea"><select name="configoptionlinks[]" size="8" style="width:90%" multiple="">
                                </select></td></tr>
                    </tbody></table>

            </div>
        </div>


        <div id="tab4box" class="tabbox" style="display: none;">
            <div id="tab_content">

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
                                <input type="text" name="affiliatepayamount" value="{$data.affiliatepayamount}" size="10"> 
                                <input type="checkbox" name="affiliateonetime" {if $data.affiliateonetime eq 'on'}checked{/if}> One Time Payout (Default is Recurring)
                            </td>
                        </tr>

                        <tr><td class="fieldlabel">Associated Downloads</td><td class="fieldarea">This is where you can specify files that are granted access to by purchasing this service.<br>
                                <table align="center"><tbody><tr><td valign="top">
                                                <div align="center"><strong>Available Files</strong></div>
                                                <div id="productdownloadsbrowser" style="width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;" class=""><ul class="jqueryFileTree" style=""></ul></div>
                                            </td><td>&lt;&gt;</td><td valign="top">
                                                <div align="center"><strong>Selected Files</strong></div>
                                                <div id="productdownloadslist" style="width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;"><ul class="jqueryFileTree"></ul></div>
                                            </td></tr></tbody></table>
                                <div align="center"><input type="button" value="Add Category" class="button" id="showadddownloadcat"> <input type="button" value="Quick Upload" class="button" id="showquickupload"></div>
                            </td></tr>

                    </tbody></table>

            </div>
        </div>
        <div id="tab5box" class="tabbox" style="display: none;">
            <div id="tab_content">

                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td class="fieldlabel">Direct Shopping Cart Link</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}" readonly=""></td></tr>
                        <tr><td class="fieldlabel">Direct Shopping Cart Link Specifying Template</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}&amp;carttpl=cart" readonly=""></td></tr>
                        <tr><td class="fieldlabel">Direct Shopping Cart Link Including Domain</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid={$data.id}&amp;sld=ra&amp;tld=.com" readonly=""></td></tr>
                        <tr><td class="fieldlabel">Service Group Cart Link</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?gid={$data.gid}" readonly=""></td></tr>
                    </tbody></table>

            </div>
        </div>

        <p align="center"><input type="submit" value="Save Changes" class="button"> <input type="button" value="Back to Service List" onclick="window.location = 'configservices.php'" class="button"></p>

        <input type="hidden" name="tab" id="tab" value="">

    </form>
</div>

{literal}
    <script type="text/javascript">
        var datepickerformat = "dd/mm/yy";
        $(document).ready(function () {
            $("#quickupload").dialog({
                autoOpen: false,
                resizable: false,
                modal: true,
                buttons: {'Save': function () {
                        $('#quickuploadfrm').submit();
                    }, 'Cancel': function () {
                        $(this).dialog('close');
                    }}
            });
            $("#adddownloadcat").dialog({
                autoOpen: false,
                resizable: false,
                modal: true,
                buttons: {'Save': function () {
                        $('#adddownloadcatfrm').submit();
                    }, 'Cancel': function () {
                        $(this).dialog('close');
                    }}
            });
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
            var selectedTab;
            $(".tab").click(function () {
                var elid = $(this).attr("id");
                $(".tab").removeClass("tabselected");
                $("#" + elid).addClass("tabselected");
                if (elid != selectedTab) {
                    $(".tabbox").slideUp();
                    $("#" + elid + "box").slideDown();
                    selectedTab = elid;
                }
                $("#tab").val(elid.substr(3));
            });
            selectedTab = "tab0";
            $("#tab0").addClass("tabselected");
            $("#tab0box").css("display", "");
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