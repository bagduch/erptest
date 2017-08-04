<div>
    <ul class="nav nav-tabs account">

        <li {if $filename eq "submitticket"}class="active"{/if}><a href="submitticket.php"><i class="fa fa-plus"></i>{$LANG.opennewticket}</a></li>

        <li {if $filename eq "supporttickets"}class="active"{/if}><a href="supporttickets.php"><i class="fa fa-inbox"></i>{$LANG.supportticketspagetitle}</a></li>

        <li {if $filename eq "downloads"}class="active"{/if}><a href="downloads.php"><i class="fa fa-download"></i>{$LANG.downloadstitle}</a></li>

        <li {if $filename eq "knowledgebase"}class="active"{/if}><a href="knowledgebase.php"><i class="fa fa-life-ring"></i>{$LANG.knowledgebasetitle}</a></li>

    </ul>
</div>
<div class="clear"></div>