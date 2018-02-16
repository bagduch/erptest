{literal}
<style type="text/css">
div#content a {
	text-decoration: none;
}
div#content a:hover {
	text-decoration: underline;
}
div#content h2 {
	padding-bottom: 10px;
	border-bottom: 2px solid gray;
	font-weight: bold;
}
div#content div.sip-account {
	padding-left: 20px;
	margin-top: 10px;
	border-left: 1px dotted #aaa;
}
div#content div.num-ddi {
	padding: 10px 10px 10px 0;
	border-bottom: 1px solid gray;
}
table tr.table-data td {
	font-size: 11px !important;
}
table tr.table-header {
	background: #ddd;
	font-weight: bold;
	color: #333;
}
table tr.table-header td {
	text-align: right;
	border-bottom: 1px solid gray;
}
table tr.table-data.odd {
	background: #eee;
}
table tr.table-data.even {
	background: none;
}
table tr.table-data td {
	text-align: right;
}
table tr.table-totals td {
	text-align: right;
	font-weight: bold;
	border-top: 1px solid black;
	border-bottom: 1px solid black;
}
table tr.table-data:hover {
	background: #ecf6ff;
}
</style>
{/literal}

<div id="content">
	{foreach from=$phonelines key=username item=lines}
		<h2>{$username}</h2>
		<div style="padding-left: 20px;padding-bottom: 14px;">
			<strong>All calls on record</strong>
			<div class="sip-account">
				{foreach from=$lines key=index item=line}
					<div class="num-ddi"><strong><a href="?module=hdtolls&amp;v=usage_detail&amp;c={$line.clientid}&amp;h={$line.hostingid}&amp;viewby=sum" onclick="this.innerHTML='Please wait...';">{$line.domain_formatted}</a></strong></div>
					<div style="padding-bottom: 14px;">
						<table cellpadding="5" cellspacing="0" style="width: 100%;">
							<tr class="table-header">
								<td style="width: 20%;text-align: left;">Month</td>
								<td style="width: 10%;">Calls</td>
								<td style="width: 17%;">Free talktime</td>
								<td style="width: 17%;">Billed talktime</td>
								<td style="width: 17%;">Billed amount</td>
								<td style="width: 17%;">Invoice</td>
							</tr>
							{foreach from=$line.usage key=month item=usage}
								<tr class="table-data {cycle values="odd,even"}">
									<td style="text-align: left;">{if $month != "No calls on record"}<a href="?module=hdtolls&amp;v=usage_detail&amp;c={$line.clientid}&amp;h={$line.hostingid}&amp;period={$month}&amp;viewby=day" onclick="this.innerHTML='Please wait...';">{$month}</a>{else}{$month}{/if}</td>
									<td>{$usage.total_calls}</td>
									<td>{$usage.total_used_included_secs_formatted}</td>
									<td>{$usage.billsec_formatted}</td>
									<td>${$usage.month_bill|number_format:"2"}</td>
									<td>
										{if $usage.can_invoice}
											<a href="?module=hdtolls&amp;v=usage_detail&amp;c={$line.clientid}&amp;h={$line.hostingid}&amp;period={$month}&amp;viewby=day&amp;invoice=true" onclick="this.innerHTML='Generating...';">[send invoice]</a>
										{elseif $usage.invoiced}
											{if is_bool($usage.invoiced)}
												IGNORED
											{else}
												#<a href="invoices.php?action=edit&amp;id={$usage.invoiced}">{$usage.invoiced}</a>
											{/if}
										{else}
											&mdash;
										{/if}
									</td>
								</tr>
							{/foreach}
							<tr class="table-totals">
								<td></td>
								<td>{$totals.$username.$index.calls}</td>
								<td>{$totals.$username.$index.free_time_formatted}</td>
								<td>{$totals.$username.$index.billed_time_formatted}</td>
								<td>${$totals.$username.$index.bill|number_format:"2"}</td>
								<td></td>
							</tr>
						</table>
					</div>
				{/foreach}
			</div>
		</div>
	{/foreach}
</div>