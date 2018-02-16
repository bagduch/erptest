<p><a href="{$modulelink}">&laquo; home</a></p>
<h2>Import Logs Index</h2>
<p>{$numitems} {$_ADMINLANG.global.recordsfound}, {$_ADMINLANG.global.page} {$pagenumber} {$_ADMINLANG.global.of} {$totalpages}</p>
<br />
<div style="max-width: 1024px;">
{if isset($sync.errors) && $sync.errors!='' && count($sync.errors)>0}<div class="errorbox">{$sync.errors}</div>{/if}
{if isset($sync.success) && $sync.success!=''}<div class="successbox">{$sync.success}</div>{/if}


<div class="tablebg">
<table class="datatable" border="0" cellspacing="1" cellpadding="3" style="width:100%;">
	<thead>
		<tr>
			<th>Date</th>
			<th>Last Processed</th>
			<th>Accounts</th>
			<th>Rows</th>
			<th>Errors</th>
			<th style="width:50%;">Error Log</th>
		</tr>
	</thead>
	<tbody>
{foreach item=row from=$rows}
		<tr>
			<td style="text-align:center;"><a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v=log_view&amp;i={$row.date_index}">{$row.date_index}</a></td>
			<td style="text-align:center;">{$row.datetime_lastproccessed}</td>
			<td style="text-align:center;">{$row.accounts_found}/{$row.activity_found}</td>
			<td style="text-align:center;">{$row.logrows|number_format}</td>
			<td style="text-align:center;">{$row.error_count|number_format}</td>
			<td>{if $row.error_count>0}[<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;page={$pagenumber}&amp;itemlimit={$itemlimit}&sync={$row.date_index}">re-sync</a>] {/if}{$row.errors}</td>
		</tr>
{/foreach}
	</tbody>
</table>
</div>
<div class="recordslimit">
    <form method="post" action="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;user={$uid}&amp;hid={$hid}" />
    <select name="itemlimit" onchange="submit()">
        <option>{$_ADMINLANG.global.resultsperpage}</option>
        <option value="10"{if $itemlimit==10} selected{/if}>10</option>
        <option value="25"{if $itemlimit==25} selected{/if}>25</option>
        <option value="50"{if $itemlimit==50} selected{/if}>50</option>
        <option value="100"{if $itemlimit==100} selected{/if}>100</option>
        <option value="all"{if $itemlimit==99999999} selected{/if}>{$_ADMINLANG.global.clientareaunlimited}</option>
    </select>
    </form>
</div>
<p align="center">
	{if $prevpage}<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;page={$prevpage}&amp;itemlimit={$itemlimit}">{/if}{$_ADMINLANG.global.previouspage}{if $prevpage}</a>{/if}
	&nbsp;
	{if $nextpage}<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;page={$nextpage}&amp;itemlimit={$itemlimit}">{/if}{$_ADMINLANG.global.nextpage}{if $nextpage}</a>{/if}
</p>
</div>
