<p><a href="{$modulelink}">&laquo; home</a></p>
{if isset($row)}
<table class="form" border="0" cellspacing="2" cellpadding="3">
	<tbody>
		<tr>
			<td width="30%" class="fieldlabel">Log</td>
			<td class="fieldarea">{$row.date_index}</td>
		</tr>
		<tr>
			<td width="30%" class="fieldlabel">Last Processed</td>
			<td class="fieldarea">{$row.datetime_lastproccessed}</td>
		</tr>
		<tr>
			<td width="30%" class="fieldlabel">Log Rows</td>
			<td class="fieldarea">{$row.logrows|number_format}</td>
		</tr>
		<tr>
			<td class="fieldlabel">Accounts Processed</td>
			<td class="fieldarea">{$row.accounts_found|number_format}/{$row.row_found|number_format}</td>
		</tr>
		<tr>
			<td class="fieldlabel">Errors</td>
			<td class="fieldarea">{$sync.statistics.error_count|number_format} error{if $row.statistics.error_count!=1}s{/if}
				{if isset($row.errors) && count($row.errors)>0 && $row.errors!=''}<div class="errorbox">{$row.errors}</div>{/if}
			</td>
		</tr>
	</tbody>
</table>
<br />
<h2>Log</h2>
<table class="datatable" border="0" cellspacing="1" cellpadding="3">
	<thead>
		<tr>
			<th>#</th>
{foreach item=r key=kname from=$row.log.0}
			<th>{$kname}</th>
{/foreach}
		</tr>
	</thead>
	<tbody>
{foreach item=r key=k from=$row.log}
		<tr>
			<td>{$k}</td>
	{foreach item=element key=fieldname from=$r}
			<td>{$element}</td>
	{/foreach}
		</tr>
{/foreach}
	</tbody>
</table>

<h2>Raw Log</h2>
<pre>{$row.rawlog}</pre>
{/if}
