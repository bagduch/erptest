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
table tr.table-data {
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
	<h2>HD Tolls billing report for {$smarty.get.period}</h2>
	<div style="padding-bottom: 14px;">
		<table cellpadding="5" cellspacing="0" style="width: 100%;">
			<tr class="table-header">
				<td style="width: 10%;text-align: left;">Client ID</td>
				<td style="width: 15%;">Company name</td>
				<td style="width: 15%;">Client name</td>
				<td style="width: 15%;">Hosting ID</td>
				<td style="width: 15%;">Period</td>
				<td style="width: 15%;">Amount</td>
				<td style="width: 15%;">Generated</td>
				<td style="width: 15%;">Invoice ID</td>
			</tr>
			{foreach from=$invoices item=invoice}{assign var="client" value=$invoice.clientid}
				<tr class="table-data {cycle values="odd,even"}">
					<td style="text-align: left;"><a href="clientssummary.php?userid={$invoice.clientid}">{$invoice.clientid}</a></td>
					<td>{if $clients.$client.companyname}{$clients.$client.companyname}{else}&mdash;{/if}</td>
					<td>{$clients.$client.firstname} {$clients.$client.lastname}</td>
					<td>{$invoice.hostingid}</td>
					<td>{$invoice.period}</td>
					<td>${$invoice.amount}</td>
					<td>{$invoice.period}-01</td>
					<td>
						{if is_null($invoice.invoiceid)}
							IGNORED
						{else}
							#<a href="invoices.php?action=edit&amp;id={$invoice.invoiceid}">{$invoice.invoiceid}</a>
						{/if}
					</td>
				</tr>
			{/foreach}
			<tr class="table-totals">
				<td style="text-align: left;"><a href="{$smarty.server.REQUEST_URI}&period={$back}">&laquo; back</a></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>${$entire_invoice_total}</td>
				<td></td>
				<td><a href="{$smarty.server.REQUEST_URI}&period={$next}">&raquo; next</a></td>
			</tr>
		</table>
	</div>
</div>		