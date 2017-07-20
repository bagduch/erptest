<div>
    <ul class="nav nav-tabs account">

        <li {if $filename eq "submitticket"}class="active"{/if}><a href="submitticket.php">{$LANG.opennewticket}</a></li>

        <li {if $filename eq "supporttickets"}class="active"{/if}><a href="supporttickets.php">{$LANG.supportticketspagetitle}</a></li>

        <li {if $filename eq "downloads"}class="active"{/if}><a href="downloads.php">{$LANG.downloadstitle}</a></li>

        <li {if $filename eq "knowledgebase"}class="active"{/if}><a href="knowledgebase.php">{$LANG.knowledgebasetitle}</a></li>

    </ul>
</div>
<div class="clear"></div>