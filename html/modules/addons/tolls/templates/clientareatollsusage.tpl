{include file="$template/pageheader.tpl" title=$pagetitle}

{literal}

<style type="text/css">
div#content {
	font-size: 13px;
}
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
}
table {
	font-size: 11px;
}
table tr.table-header {
	background: #ddd;
	color: #333;
}
table tr.table-header td {
	text-align: right;
	border-top: 1px solid gray;
	border-bottom: 1px solid gray;
	font-weight: bold;
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
table tr td {
	vertical-align: middle;
	padding: 5px 10px;
}
</style>
{/literal}

{if $smarty.get.ddi}
	<div id="content">
		{if $smarty.get.viewby == "day"}
			{foreach from=$usage key=year item=months}
				<h2 style="margin-left: 20px;">{if $smarty.get.period}Single-month{else}FULL{/if} call transcript for DDI {$client.domain_formatted}</h2>
				<div style="margin-left: 20px;padding-bottom: 20px;padding-top: 20px;">
					View by:
					<select onchange="window.location=window.location+'&viewby='+this.value">
						<option value="day" {if $smarty.get.viewby == "day"}selected="selected"{/if}>Day</option>
						<option value="cat" {if $smarty.get.viewby == "cat"}selected="selected"{/if}>Category</option>
						<option value="sum" {if $smarty.get.viewby == "sum"}selected="selected"{/if}>Sum</option>
					</select>
				</div>
				{foreach from=$months key=month item=days}
					<div style="padding-left: 20px;padding-bottom: 14px;">
						<div style="padding-top: 10px;">
							<strong>{$year} {$month}</strong>
						</div>
						<div class="sip-account">
							{foreach from=$days key=day item=calls}
								<div class="num-ddi"><strong>{$day}</strong></div>
								<div style="padding-bottom: 14px;">
									<table cellpadding="5" cellspacing="0" style="width: 100%;">
										<tr class="table-header">
											<td style="width: 10%;text-align: left;">Number</td>
											<td style="width: 10%;">Category</td>
											<td style="width: 10%;">Call ringing</td>
											<td style="width: 10%;">Call answered</td>
											<td style="width: 10%;">Call ended</td>
											<td style="width: 10%;">Free talktime</td>
											<td style="width: 10%;">Billed talktime</td>
											<td style="width: 10%;">Minute rate</td>
											<td style="width: 10%;">Billed amount</td>
										</tr>
										{foreach from=$calls key=id item=call}
											<tr class="table-data {cycle values="odd,even"}" {if !$call.is_outbound && !$call.is_forwarded}style="opacity: 0.4;"{/if}>
												<td style="text-align: left;">{if $call.is_outbound}<span style="color: red;">&larr;</span></a>{else}<span style="color: green;">&rarr;</span></a>{/if} {$call.noi_formatted}{if $call.is_forwarded} <span style="color: red;">&rarr;</span> {$call.forward_to_formatted}{/if}</td>
												<td>{if $call.category == "international"}{$call.subcategory|ucwords}{else}{$call.category|ucwords}{/if}{if $call.is_forwarded} <span style="color: red;">&rarr;</span> {$call.forward_category|ucwords}{/if}</td>
												<td>{$call.start}</td>
												<td>{$call.answer}</td>
												<td>{$call.end}</td>
												<td>{$call.used_included_secs_formatted}</td>
												<td>{$call.billsec_formatted}</td>
												<td>${$call.bill_rate|number_format:"3"}</td>
												<td>${$call.bill_amount|number_format:"2"}</td>
											</tr>
										{/foreach}
										<tr class="table-totals">
											<td style="text-align: left;">{$totals.$year.$month.$day.total_calls} call{if $totals.$year.$month.$day.total_calls != 1}s{/if} logged</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>{$totals.$year.$month.$day.total_used_included_secs_formatted}</td>
											<td>{$totals.$year.$month.$day.billsec_formatted}</td>
											<td>&times; applicable rate</td>
											<td><div style="float: left;">=</div><div style="float: right;">${$totals.$year.$month.$day.total_bill|number_format:"2"}</div></td>
										</tr>
									</table>
								</div>
							{/foreach}
						</div>
					</div>
				{/foreach}
			{/foreach}
		{elseif $smarty.get.viewby == "cat"}
			<h2 style="margin-left: 20px;">{if $smarty.get.period}Single-month{else}FULL{/if} call transcript for DDI {$client.domain_formatted}</h2>
			<div style="margin-left: 20px;padding-bottom: 20px;padding-top: 20px;">
				View by:
				<select onchange="window.location=window.location+'&viewby='+this.value">
					<option value="day" {if $smarty.get.viewby == "day"}selected="selected"{/if}>Day</option>
					<option value="cat" {if $smarty.get.viewby == "cat"}selected="selected"{/if}>Category</option>
					<option value="sum" {if $smarty.get.viewby == "sum"}selected="selected"{/if}>Sum</option>
				</select>
			</div>
			{foreach from=$usage key=category item=arr}
				<h2 style="margin-left: 20px;">{$category|ucwords}</h2>
				{foreach from=$arr key=year item=months}
					{foreach from=$months key=month item=days}
						<div style="padding-left: 20px;padding-bottom: 14px;">
							<div style="padding-top: 10px;">
								<strong>{$year} {$month}</strong>
							</div>
							<div class="sip-account">
								{foreach from=$days key=day item=calls}
									<div class="num-ddi"><strong>{$day}</strong></div>
									<div style="padding-bottom: 14px;">
										<table cellpadding="5" cellspacing="0" style="width: 100%;">
											<tr class="table-header">
												<td style="width: 10%;text-align: left;">Number</td>
												<td style="width: 10%;">Category</td>
												<td style="width: 10%;">Call ringing</td>
												<td style="width: 10%;">Call answered</td>
												<td style="width: 10%;">Call ended</td>
												<td style="width: 10%;">Free talktime</td>
												<td style="width: 10%;">Billed talktime</td>
												<td style="width: 10%;">Minute rate</td>
												<td style="width: 10%;">Billed amount</td>
											</tr>
											{foreach from=$calls key=id item=call}
												<tr class="table-data {cycle values="odd,even"}" {if !$call.is_outbound}style="opacity: 0.4;"{/if}>
													<td style="text-align: left;">{if $call.is_outbound}<span style="color: red;">&larr;</span></a>{else}<span style="color: green;">&rarr;</span></a>{/if} {$call.noi_formatted}{if $call.is_forwarded} <span style="color: red;">&rarr;</span> {$call.forward_to_formatted}{/if}</td>
													<td>{if $call.category == "international"}{$call.subcategory|ucwords}{else}{$call.category|ucwords}{/if}{if $call.is_forwarded} <span style="color: red;">&rarr;</span> {$call.forward_category|ucwords}{/if}</td>
													<td>{$call.start}</td>
													<td>{$call.answer}</td>
													<td>{$call.end}</td>
													<td>{$call.used_included_secs_formatted}</td>
													<td>{$call.billsec_formatted}</td>
													<td>${$call.bill_rate|number_format:"3"}</td>
													<td>${$call.bill_amount|number_format:"2"}</td>
												</tr>
											{/foreach}
											<tr class="table-totals">
												<td style="text-align: left;">{$totals.$category.$year.$month.$day.total_calls} call{if $totals.$category.$year.$month.$day.total_calls != 1}s{/if} made</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>{$totals.$category.$year.$month.$day.total_used_included_secs_formatted}</td>
												<td>{$totals.$category.$year.$month.$day.billsec_formatted}</td>
												<td>&times; each rate</td>
												<td><div style="float: left;">=</div><div style="float: right;">${$totals.$category.$year.$month.$day.total_bill|number_format:"2"}</div></td>
											</tr>
										</table>
									</div>
								{/foreach}
							</div>
						</div>
					{/foreach}
				{/foreach}
			{/foreach}
		{elseif $smarty.get.viewby == "sum"}
			<h2 style="margin-left: 20px;">{if $smarty.get.period}Single-month{else}FULL{/if} call transcript sum for DDI {$client.domain_formatted}</h2>
			<div style="margin-left: 20px;padding-bottom: 20px;padding-top: 20px;">
				View by:
				<select onchange="window.location=window.location+'&viewby='+this.value">
					<option value="day" {if $smarty.get.viewby == "day"}selected="selected"{/if}>Day</option>
					<option value="cat" {if $smarty.get.viewby == "cat"}selected="selected"{/if}>Category</option>
					<option value="sum" {if $smarty.get.viewby == "sum"}selected="selected"{/if}>Sum</option>
				</select>
			</div>
			{foreach from=$usage key=month item=categories}
				<h2 style="margin-left: 20px;">{$month}</h2>
				<div style="padding-left: 20px;padding-bottom: 14px;">
					<div class="sip-account">
						<div class="num-ddi"><strong>{$day}</strong></div>
						<div style="padding-bottom: 14px;">
							<table cellpadding="5" cellspacing="0" style="width: 100%;">
								<tr class="table-header">
									<td style="width: 5%;text-align: left;">Category</td>
									<td style="width: 20%;">Total calls</td>
									<td style="width: 20%;">Free talktime</td>
									<td style="width: 20%;">Billed talktime</td>
									<td style="width: 20%;">Billed amount</td>
								</tr>
								{foreach from=$categories key=category item=total}
									<tr class="table-data {cycle values="odd,even"}">
										<td style="text-align: left;">{$total.category|ucwords}</td>
										<td>{$total.total_calls}</td>
										<td>{$total.freesec_formatted}</td>
										<td>{$total.billsec_formatted}</td>
										<td>${$total.total_bill|number_format:"2"}</td>
									</tr>
								{/foreach}
								<tr class="table-totals">
									<td></td>
									<td>{$totals.$month.total_calls}</td>
									<td>{$totals.$month.freesec_formatted}</td>
									<td>{$totals.$month.billsec_formatted}</td>
									<td><div style="float: right;">${$totals.$month.total_bill|number_format:"2"}</div></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			{/foreach}
		{/if}
	</div>
{else}
	<div id="content">
		{foreach from=$usage key=month item=categories}
			<h2 style="margin-left: 20px;">Combined recent usage summary</h2>
			<div style="padding-left: 20px;padding-bottom: 14px;">
				<div class="sip-account">
					<div class="num-ddi"><strong>{$day}</strong></div>
					<div style="padding-bottom: 14px;">
						<table cellpadding="5" cellspacing="0" style="width: 100%;">
							<tr class="table-header">
								<td style="width: 5%;text-align: left;">Category</td>
								<td style="width: 20%;">Total calls since {$month}-01</td>
								<td style="width: 20%;">Free talktime since {$month}-01</td>
								<td style="width: 20%;">Paid talktime since {$month}-01</td>
								<td style="width: 20%;">Charges incurred since {$month}-01</td>
							</tr>
							{foreach from=$categories key=category item=total}
								<tr class="table-data {cycle values="odd,even"}">
									<td style="text-align: left;">{$total.category|ucwords}</td>
									<td>{$total.total_calls}</td>
									<td>{$total.freesec_formatted}</td>
									<td>{$total.billsec_formatted}</td>
									<td>${$total.total_bill|number_format:"2"}</td>
								</tr>
							{/foreach}
							<tr class="table-totals">
								<td></td>
								<td>{$totals.sum.total_calls}</td>
								<td>{$totals.sum.freesec_formatted}</td>
								<td>{$totals.sum.billsec_formatted}</td>
								<td><div style="float: right;">${$totals.sum.total_bill|number_format:"2"}</div></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		{/foreach}
		{foreach from=$lines key=username item=mlines}
			<h2 style="margin-left: 20px;">SIP Account: {$username}</h2>
			<div style="padding-left: 20px;padding-bottom: 14px;">
				<div class="sip-account" style="overflow: hidden;">
					{foreach from=$mlines key=index item=line}
						<div style="overflow: auto;">
							<div class="num-ddi" style="float: left;">
								<strong><a href="index.php?m=hdtolls&amp;ddi={$line.hostingid}&amp;viewby=sum" onclick="this.innerHTML='Please wait...';">{$line.domain_formatted}</a></strong>
							</div>
							<div class="num-ddi" style="float: right;padding-right: 0;">
								Last call occurred <strong>{if $line.last_call_formatted}{$line.last_call_formatted}{else}never{/if}</strong>
							</div>
						</div>
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
										<td style="text-align: left;">{if $month != "No calls on record"}<a href="index.php?m=hdtolls&amp;ddi={$line.hostingid}&amp;period={$month}&amp;viewby=day" onclick="this.innerHTML='Please wait...';">{$month}</a>{else}{$month}{/if}</td>
										<td>{$usage.total_calls}</td>
										<td>{$usage.total_used_included_secs_formatted}</td>
										<td>{$usage.billsec_formatted}</td>
										<td>${$usage.month_bill|number_format:"2"}</td>
										<td>
											{if $usage.invoiced}
												{if is_bool($usage.invoiced)}
													IGNORED
												{else}
													#<a href="viewinvoice.php?id={$usage.invoiced}">{$usage.invoiced}</a>
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
{/if}