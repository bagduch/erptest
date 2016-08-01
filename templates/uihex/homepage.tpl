<div class="row">
    <div class="col-md-12">
        <h3 class="page-header"><span aria-hidden="true" class="icon icon-home"></span> {$LANG.clientareanavhome} <i class="fa fa-info-circle animated bounce show-info"></i>
            {if $showqsl} 
                <span class="pull-right qsl"><a href="#" data-original-title="Quick Server Logins"><span aria-hidden="true" class="icon icon-settings settings-toggle"></span></a></span>
                    {/if}
        </h3>  
        <h2>Welcome Back {$name}</h2>	
        <br />   
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <a title="{$LANG.registerdomain}" href="clientarea.php?action=services">
            <div class="info-box  bg-info  text-white" id="initial-tour">
                <div class="info-icon bg-info-dark leftbox">
                    <span aria-hidden="true" class="icon icon-layers"></span>
                </div>
                <div class="info-details">
                    <h4>{$LANG.ordertitle}</h4>
                    <h4>{$LANG.navservicesorder}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a title="{$LANG.registerdomain}" href="clientarea.php?action=products">
            <div class="info-box  bg-info  text-white" id="initial-tour">
                <div class="info-icon bg-info-dark leftbox">
                    <span aria-hidden="true" class="icon icon-layers"></span>
                </div>
                <div class="info-details">
                    <h4>{$LANG.ordertitle}</h4>
                    <h4>{$LANG.navsproductsorder}</h4>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a title="{$LANG.cartproductdomain}" href="submitticket.php">
            <div class="info-box  bg-info  text-white">
                <div class="info-icon bg-info-dark leftbox">
                    <span aria-hidden="true" class="icon icon-drawer"></span>
                </div>
                <div class="info-details">
                    <h4>{$LANG.opennewticket}</h4>
                    <h4>{$LANG.navsupport}</h4>
                </div>
        </a>
    </div>
</div>
</div>
<div class="styled_title">
    <h2>{$LANG.latestannouncements}</h2>
</div>
{foreach from=$announcements item=announcement}
    <p>{$announcement.date} - <a href="{if $seofriendlyurls}announcements/{$announcement.id}/{$announcement.urlfriendlytitle}.html{else}announcements.php?id={$announcement.id}{/if}"><b>{$announcement.title}</b></a><br />{$announcement.text|strip_tags|truncate:100:"..."}</p>
            {/foreach}

</div>