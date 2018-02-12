<div>
    <ul class="nav nav-tabs account">

        <li {if $clientareaaction eq "invoices"}class="active"{/if}><a href="clientarea.php?action=invoices"><i class="fa fa-inbox"></i>{$LANG.invoices}</a></li>

        {if $condlinks.updatecc}<li {if $clientareaaction eq "creditcard"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=creditcard">{$LANG.clientareanavccdetails}</a></li>{/if}

        <li {if $clientareaaction eq "transection"}class="active"{/if}><a href="clientarea.php?action=transection"><i class="fa fa-lock"></i>{$LANG.transectiontitle}</a></li>

        <li {if $clientareaaction eq "creditus"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=creditus"><i class="fa fa-lock"></i>{$LANG.credit}</a></li>

        <li {if $clientareaaction eq "creditcard"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=creditcard"><i class="fa fa-credit-card"></i>{$LANG.creditcard}</a></li>

        {if $condlinks.security}<li {if $clientareaaction eq "security"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=security">{$LANG.clientareanavsecurity}</a></li>{/if}

    </ul>
</div>
<div class="clear"></div>