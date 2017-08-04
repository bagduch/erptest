<div>
    <ul class="nav nav-tabs account">

        <li {if $clientareaaction eq "details"}class="active"{/if}><a href="clientarea.php?action=details"><i class="fa fa-id-card-o"></i>{$LANG.clientareanavdetails}</a></li>

        {if $condlinks.updatecc}<li {if $clientareaaction eq "creditcard"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=creditcard">{$LANG.clientareanavccdetails}</a></li>{/if}

        <!--<li {if $clientareaaction eq "contacts" ||  $clientareaaction eq "addcontact"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=contacts">{$LANG.clientareanavcontacts}</a></li>-->

        <li {if $clientareaaction eq "changepw"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=changepw"><i class="fa fa-lock"></i>{$LANG.clientareanavchangepw}</a></li>

        {if $condlinks.security}<li {if $clientareaaction eq "security"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=security">{$LANG.clientareanavsecurity}</a></li>{/if}

        <li {if $clientareaaction eq "emails"}class="active"{/if}><a href="{$smarty.server.PHP_SELF}?action=emails"><i class="fa fa-envelope-o"></i>{$LANG.clientareaemails}</a></li>
    </ul>
</div>
<div class="clear"></div>