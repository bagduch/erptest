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
            <th{if $orderby eq "id"} class="headerSort{$sort}"{/if}><a href="clientarea.php?action=transection&orderby=id">{$LANG.transectiondate}</a></th>
            <th{if $orderby eq "date"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=transection&orderby=date">{$LANG.transectiontype}</a></th>
            <th{if $orderby eq "duedate"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=transection&orderby=duedate">{$LANG.transectiondes}</a></th>
            <th{if $orderby eq "total"} class="headerSort{$sort} hidden-xs" {else} class="hidden-xs"{/if}><a href="clientarea.php?action=transection&orderby=total">{$LANG.transectionamount}</a></th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$transection item=row}
            <tr>
                <td class="hidden-xs">{$row.date}</td>
                <td class="hidden-xs">{$row.gateway}</td>
                <td class="hidden-xs">{$row.description}</td>
                <td class="hidden-xs">${$row.amount|number_format:2}</td>
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
    <li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}clientarea.php?action=transection{if $q}&q={$q}{/if}&amp;page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
    <li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}clientarea.php?action=transection{if $q}&q={$q}{/if}&amp;page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
</ul>
