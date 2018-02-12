{if $customefield}
    {foreach from=$customefield item=fields}
        {if !$fields.adminonly}
            {if $fields.fieldtype eq "text"}
                {if $fields.fieldname eq "address"}
                    <input name="customfield[{$fields.cfid}]" type="hidden" class="form-control input-lg" id="{$fields.fieldname}{$fields.cfid}"  value="{$address}"/>
                {else}
                    <div class="clearfix"></div>

                    <div class="col-md-6">
                        <label for="#{$fields.fieldname}{$fields.cfid}">{$fields.fieldname}{if $fields.required}<span>*</span>{/if}</label>
                    </div>
                    <div class="col-md-6">
                        <input name="customfield[{$fields.cfid}]" type="text" class="form-control" id="{$fields.fieldname}{$fields.cfid}" placeholder="{$fields.description }"/>
                    </div>
                {/if}
            {elseif $fields.fieldtype eq "password"}
            {elseif $fields.fieldtype eq "date"}
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <label class="datalabel">{$fields.fieldname}:</label>
                </div>
                <div class="col-md-6">
                    <div id="sandbox-container">
                        <input type="text" name="customfield[{$fields.cfid}]" class="form-control">
                    </div>
                </div>
            {elseif $fields.fieldtype eq "dropdown"}
            {elseif $fields.fieldtype eq "tickbox"}
            {elseif $fields.fieldtype eq "more"}

                <div class="clearfix"></div>
                <button class="btn btn-default btn-circle hidden-button" id="a1" onclick=""> <i class=""></i></button>
                <label>{$fields.fieldname}:</label>
                {if $fields.children}
                    <div class="hidden-option">
                        {foreach from=$fields.children item=childrendata}

                            {if $childrendata.fieldtype eq "text"}
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <label>{$childrendata.fieldname}:</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="customfield[{$childrendata.cfid}]" type="text"  class="form-control">
                                </div>
                            {elseif $childrendata.fieldtype eq "text"}
                            {/if}





                        {/foreach}
                        <div class="clearfix"></div>
                    </div>
                {/if}

            {else}
            {/if}
        {/if}
    {/foreach}
{/if}