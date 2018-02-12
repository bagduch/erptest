{include file='orderforms/ajaxcart/ajaxcartheader.tpl'}

{if $pid}<script type="text/javascript">
    selectproduct('{$pid}');
    $(document).ready(function (){ldelim}
            jQuery("#pid{$pid}").attr("checked", "checked");



        {rdelim});
    </script>{/if}
    <script type="text/javascript">
        jQuery(function () {ldelim}
                jQuery('#myTab').tab()
        {rdelim});
    </script>



    <div id="prodcontainer"{if $smarty.get.skip && $pid} style="display:none;"{/if}>


        <div id="myTab">

            <!-- Nav tabs -->
            heelo{$type}
            <ul  class="nav nav-tabs" role="tablist">
                <li role="presentation" class="{if $type=='2'}active{/if}"><a href="#products" aria-controls="products" role="tab" data-toggle="tab">Products</a></li>
                <li role="presentation" class="{if $type=='1'}active{/if}"><a href="#services" aria-controls="services" role="tab" data-toggle="tab">Services</a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane {if $type=='2'}active{/if}" id="products">          
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr class="rowcolor1">
                            <td>
                                {foreach from=$productgroups item=group}<label><input type="radio" name="gid" value="{$group.gid}" onclick="window.location = 'cart.php?gid={$group.gid}'"{if $group.gid eq $gid} checked{/if} /> {$group.name}</label> {/foreach}
                                {if $loggedin}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?gid=addons'" /> {$LANG.cartproductaddons}</label>
                                    <label><input type="radio" name="gid" onclick="window.location = 'cart.php?gid=renewals'" /> {$LANG.domainrenewals}</label> {/if}
                                    {if $registerdomainenabled}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?a=add&domain=register'" /> {$LANG.registerdomain}</label> {/if}
                                    {if $transferdomainenabled}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?a=add&domain=transfer'" /> {$LANG.transferdomain}</label>{/if}
                                </td>
                            </tr>
                        </table>
                        <table width="90%" cellspacing="0" cellpadding="0" align="center">
                            {foreach from=$products item=product key=num}
                                {if $product.pid}
                                    <tr><td width="25"><input type="radio" name="pid" value="{$product.pid}" id="pid{$product.pid}" {if $product.qty!="" && $product.qty<=0}disabled{/if} onclick="selectproduct('{$product.pid}')"></td><td><label for="pid{$product.pid}"><strong>{$product.name}</strong>{if $product.qty!="" && $product.qty<=0} ({$LANG.outofstock}){/if}</label>{if $product.description} - {$product.description}{/if}</td></tr>
                                        {/if}
                                    {/foreach}
                        </table></div>
                    <div role="tabpanel" class="tab-pane {if $type=='1'}active{/if}" id="services">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tr class="rowcolor1">
                                <td>
                                    {foreach from=$productservices item=group}<label><input type="radio" name="gid" value="{$group.gid}" onclick="window.location = 'cart.php?gid={$group.gid}'"{if $group.gid eq $gid} checked{/if} /> {$group.name}</label> {/foreach}
                                    {if $loggedin}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?gid=addons'" /> {$LANG.cartproductaddons}</label>
                                        <label><input type="radio" name="gid" onclick="window.location = 'cart.php?gid=renewals'" /> {$LANG.domainrenewals}</label> {/if}
                                        {if $registerdomainenabled}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?a=add&domain=register'" /> {$LANG.registerdomain}</label> {/if}
                                        {if $transferdomainenabled}<label><input type="radio" name="gid" onclick="window.location = 'cart.php?a=add&domain=transfer'" /> {$LANG.transferdomain}</label>{/if}
                                    </td>
                                </tr>
                            </table>
                            <table width="90%" cellspacing="0" cellpadding="0" align="center">
                                {foreach from=$products item=product key=num}
                                    {if $product.pid}
                                        <tr><td width="25"><input type="radio" name="pid" value="{$product.pid}" id="pid{$product.pid}" {if $product.qty!="" && $product.qty<=0}disabled{/if} onclick="selectproduct('{$product.pid}')"></td><td><label for="pid{$product.pid}"><strong>{$product.name}</strong>{if $product.qty!="" && $product.qty<=0} ({$LANG.outofstock}){/if}</label>{if $product.description} - {$product.description}{/if}</td></tr>
                                            {/if}
                                        {/foreach}
                            </table>
                        </div>
                    </div>

                </div>

                {if $numitemsincart}<div id="checkoutbtn"><input type="button" value="{$LANG.ajaxcartcheckout}" onclick="checkout()" /></div>{/if}

                {if $hiddenproduct}<input type="hidden" name="pid" value="{$pid}" />{/if}

                <div id="loading1" class="loading"><img src="images/loading.gif" border="0" alt="{$LANG.loading}" /></div>

                <div id="configcontainer1"></div>

                <div id="configcontainer2"></div>

                <div id="configcontainer3"></div>

                <div id="signupcontainer"></div>

                {include file='orderforms/ajaxcart/ajaxcartfooter.tpl'}