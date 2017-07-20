<div class="account-wrap">

    {include file="$template/clientareabillinglinks.tpl"}
    <div class="row details">
        {include file="$template/pageheader.tpl" title=$LANG.invoices }

    
        <div class="table-wrap">
            <div class="resultsbox">
                <p class="invdesc">{$LANG.invoicesintro}</p>
                <p class="invnum">{$numitems} {$LANG.recordsfound}, {$LANG.page} {$pagenumber} {$LANG.pageof} {$totalpages}</p>
            </div>

            <div class="clear"></div>

            <form method="post" action="clientarea.php?action=masspay">
            
                <table class="table table-striped table-framed table-hover">
                    <thead>
                        <tr>
                            <th{if $orderby eq "id"} class="headerSort{$sort}"{/if}><a href="clientarea.php?action=invoices&orderby=id">{$LANG.invoicestitle}</a></th>
                            <th{if $orderby eq "date"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=date">{$LANG.invoicesdatecreated}</a></th>
                            <th{if $orderby eq "duedate"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=duedate">{$LANG.invoicesdatedue}</a></th>
                            <th{if $orderby eq "total"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=total">{$LANG.invoicestotal}</a></th>
                            <th{if $orderby eq "status"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=status">{$LANG.invoicesstatus}</a></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$invoices item=invoice}
                        <tr>
                            <td  data-label="{$LANG.invoicestitle}"><a href="viewinvoice.php?id={$invoice.id}" target="_blank"><strong>{$invoice.invoicenum}</strong></a>
                            <!--<ul class="cell-inner-list visible-xs">
                                <li><span class="label {$invoice.rawstatus}">{$invoice.statustext}</span></li>
                                <li><span class="item-title">{$LANG.invoicestotal} : </span>{$invoice.total}</li>
                                <li><span class="item-title">{$LANG.invoicesdatecreated} : </span>{$invoice.datecreated}</li>                                       
                                <li><span class="item-title">{$LANG.invoicesdatedue} : </span>{$invoice.datedue}</li>
                            </ul>-->
                        </td>
                        <td data-label="{$LANG.invoicesdatecreated}">{$invoice.datecreated}</td>
                        <td data-label="{$LANG.invoicesdatedue}">{$invoice.datedue}</td>
                        <td data-label="{$LANG.invoicestotal}">{$invoice.total}</td>
                        <td data-label="{$LANG.invoicesstatus}"><span class="label {$invoice.rawstatus}">{$invoice.statustext}</span></td>
                        <td class="inv-vw"><a href="viewinvoice.php?id={$invoice.id}" target="_blank" class="btn btn-default btn-xs">{$LANG.invoicesview}</a></td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="6" class="textcenter">{$LANG.norecordsfound}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            </form>

            {include file="$template/clientarearecordslimit.tpl" clientareaaction=$clientareaaction}

            <ul class="pagination">
                <li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}clientarea.php?action=invoices{if $q}&q={$q}{/if}&amp;page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
                <li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}clientarea.php?action=invoices{if $q}&q={$q}{/if}&amp;page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
            </ul>
        </div> 
    </div>


    <div class="searchbox">
        <span class="invoicetotal">{$LANG.invoicesoutstandingbalance}: <span class="text{if $nobalance} label label-success{else} label label-warning{/if}">{$totalbalance}</span></span>{if $masspay}&nbsp; <a href="clientarea.php?action=masspay&all=true" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-ok-circle"></span> {$LANG.masspayall}</a>{/if}
    </div>
 
</div>  