{include file="$template/pageheader.tpl" title=$LANG.transectiontitle}
<script>
    {literal}
        $(document).ready(function () {
            $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' files selected' : label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log)
                        alert(log);
                }

            });
        });
    {/literal}
</script>
    <table class="table table-striped table-framed table-hover">
        <thead>
            <tr>
                <th{if $orderby eq "id"} class="headerSort{$sort}"{/if}><a href="clientarea.php?action=invoices&orderby=id">{$LANG.transectiondate}</a></th>
                <th{if $orderby eq "date"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=date">{$LANG.transectiontype}</a></th>
                <th{if $orderby eq "duedate"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=duedate">{$LANG.transectiondes}</a></th>
                <th{if $orderby eq "total"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=invoices&orderby=total">{$LANG.transectionamount}</a></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$invoices item=invoice}
                <tr>
                    <td><a href="viewinvoice.php?id={$invoice.id}" target="_blank"><strong>{$invoice.invoicenum}</strong></a>
                        <ul class="cell-inner-list visible-xs">
                            <li><span class="label {$invoice.rawstatus}">{$invoice.statustext}</span></li>
                            <li><span class="item-title">{$LANG.invoicestotal} : </span>{$invoice.total}</li>
                            <li><span class="item-title">{$LANG.invoicesdatecreated} : </span>{$invoice.datecreated}</li>                                       
                            <li><span class="item-title">{$LANG.invoicesdatedue} : </span>{$invoice.datedue}</li>
                        </ul>
                    </td>
                    <td class="hidden-xs">{$invoice.datecreated}</td>
                    <td class="hidden-xs">{$invoice.datedue}</td>
                    <td class="hidden-xs">{$invoice.total}</td>
                    <td><a href="viewinvoice.php?id={$invoice.id}" target="_blank" class="btn btn-default btn-xs pull-right">{$LANG.invoicesview}</a></td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="6" class="textcenter">{$LANG.norecordsfound}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>


{include file="$template/clientarearecordslimit.tpl" clientareaaction=$clientareaaction}

<ul class="pagination">
    <li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}clientarea.php?action=invoices{if $q}&q={$q}{/if}&amp;page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
    <li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}clientarea.php?action=invoices{if $q}&q={$q}{/if}&amp;page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
</ul>
