{include file='orderforms/ajaxcart/ajaxcartheader.tpl'}

{if $pid}<script type="text/javascript">
    selectproduct('{$pid}');
    $(document).ready(function (){ldelim}
            jQuery("#pid{$pid}").attr("checked", "checked");
        {rdelim});
    </script>{/if}
    <h3 class="page-header"><span aria-hidden="true" class="icon icon-user"></span> {$LANG.cartserver}</h3>

    <div id="prodcontainer"{if $smarty.get.skip && $pid} style="display:none;"{/if}>

        {if $productgroups}
            <div id="myTab">
                <!-- Nav tabs -->
                <ul  class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="{if $type=='2'}active{/if}"><a href="#products" aria-controls="products" role="tab" data-toggle="tab">Products</a></li>
                    <li role="presentation" class="{if $type=='1'}active{/if}"><a href="#services" aria-controls="services" role="tab" data-toggle="tab">Services</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane {if $type=='2'}active{/if}" id="products">          
                    </div>
                    <div role="tabpanel" class="tab-pane {if $type=='1'}active{/if}" id="services">
                        {foreach from=$productservices item=group}
                            <label><input type="radio" name="gid" value="{$group.gid}" onclick="window.location = 'cart.php?gid={$group.gid}'"{if $group.gid eq $gid} checked{/if} /> {$group.name}</label> 
                        {/foreach}
                    </div>
                </div>

            </div>
        {else}
            {foreach from=$productservices item=group}
                <label>
                    <input type="radio" name="gid" value="{$group.gid}" onclick="window.location = 'cart.php?gid={$group.gid}'"{if $group.gid eq $gid} checked{/if} /> {$group.name}
                </label> 
                {/foreach}
        {/if}

{*        {if $numitemsincart}<div id="checkoutbtn"><input class="btn btn-default" type="button" value="{$LANG.ajaxcartcheckout}" onclick="checkout()" /></div>{/if}

        {if $hiddenproduct}<input type="hidden" name="pid" value="{$pid}" />{/if}

        <div id="loading1" class="loading"><img src="images/loading.gif" border="0" alt="{$LANG.loading}" /></div>

        <div id="configcontainer1"></div>

        <div id="configcontainer2"></div>

        <div id="configcontainer3"></div>

        <div id="signupcontainer"></div>*}

        {include file='orderforms/ajaxcart/ajaxcartfooter.tpl'}