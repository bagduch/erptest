<h2>Billing Period: {$period_unix|date_format:"%Y-%b"}</h2>
{if isset($message_error)}<div class="errorbox">{$message_error}</div>{/if}
{if isset($message_success)}<div class="successbox">{$message_success}</div>{/if}
<div style="max-width: 1024px;">
<div style="width:100%;">
<div style="float:left;width:31%;height:1em;text-align:left;padding: 1%;">{if $period_unix>$period_epoc}<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;period={$period_last|date_format:"%Y-%m"}">&laquo;{$period_last|date_format:"%Y %b"}</a>{/if}</div>
<div style="float:left;width:31%;height:1em;text-align:center;padding: 1%;"><strong>{$period_unix|date_format:"%Y %b"}</strong></div>
<div style="float:left;width:31%;height:1em;text-align:right;padding: 1%;">{if $period_unix<=$smarty.now}<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;period={$period_next|date_format:"%Y-%m"}">{$period_next|date_format:"%Y %b"}&raquo;</a>{/if}</div>
<div style="clear:both;"><!-- --></div>
</div>
<div class="tablebg">
<table class="datatable" border="0" cellspacing="1" cellpadding="3" style="width:100%;">
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Company Name</th>
			<th>Domain</th>
			<th>Usernames</th>

			<th>Bill From</th>
			<th>Bill Until</th>
			
			<th>Charge</th>
			<th>Invoice</th>
		</tr>
	</thead>
	<tbody>
{foreach item=row from=$rows}
		<tr>
			<td>{$row.firstname}</td>
			<td>{$row.lastname}</td>
			<td>{$row.companyname}</td>
			<td><a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v=usage_user&amp;c={$row.client_id}#acc_{$row.domain}">{$row.domain}</a></td>
			<td><a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v=usage_user&amp;c={$row.client_id}#acc_{$row.domain}">{$row.username}</a></td>
			<td>{if isset($row.date_from)}{$row.date_from|date_format:"%Y-%b-%d"}{else}&nbsp;{/if}</td>
			<td>{if isset($row.date_stop)}{$row.date_stop|date_format:"%Y-%b-%d"}{else}&nbsp;{/if}</td>
			<td>{if isset($row.invoice_id) && $row.invoice_id>0}${$row.invoice_item_amount|number_format:2}{else}&nbsp;{/if}</td>
			<td style="text-align:right;">{if isset($row.invoice_id) && $row.invoice_id>0}<a href="invoices.php?action=edit&id={$row.invoice_id}" target="_blank">#{$row.invoice_id}</a>{elseif isset($canbill) && $canbill===true}<a href="{$smarty.server.PHP_SELF}?module={$module}&amp;v={$v}&amp;user={$row.userid}&amp;hid={$row.hosting_id}&amp;createinvoice={$period}&amp;period={$period}">+invoice</a>{else}&nbsp;{/if}</td>
		</tr>
{/foreach}
	</tbody>
</table>
</div>
</div>
