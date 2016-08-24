

<div class="panel-heading" role="tab" id="addon{$addons.id}">
    <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#addoncollapse{$addons.id}" aria-expanded="true" aria-controls="addoncollapse{$addons.id}" class="collapsed">
            <i class="fa fa-caret-right" aria-hidden="true"></i>
            {$addons.name}
        </a>
    </h4>
</div>
<div id="addoncollapse{$addons.id}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="addon{$addons.id}" style="height: 0px;">
    <div class="panel-body">
        {$addons.billingcycle} ${$addons.total} NZD
        <input type="hidden" class="addonsprice" data-type="{$addons.billingcycle}" value="{$addons.total}">
    </div>
</div>


