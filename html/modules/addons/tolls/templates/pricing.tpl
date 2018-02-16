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
div#content input[type=text] {
	margin: 0;
	padding: 0;
	text-align: right;
	background: none;
	border: none;
	outline: none;
	font-weight: bold;
	color: #44f;
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
<script type="text/javascript">
function validateNumber(e) {
	var theEvent = e || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if(!regex.test(key)) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault)
			theEvent.preventDefault();
	}
}
function createForm($el, hostingid, zone, rate_or_talktime) {
	$el.html('<input type="text" id="change_form" value="' + $el.text().replace('$', '').replace('minutes', '').trim() + '" onkeypress="validateNumber(event)" onblur="submitForm(this, \'' + hostingid + '\', \'' + zone + '\', \'' + rate_or_talktime + '\');" />');
	$('#change_form').focus();
}
function submitForm(el, hostingid, zone, rate_or_talktime) {
	var value = el.value.replace('$', '').replace('minutes', '').trim();
	$.ajax({
		url: '/modules/addons/hdtolls/ajax.php',
		type: 'post',
		data: {hostingid: hostingid, zone: zone, type: rate_or_talktime, value: value},
		async: true,
		complete: function(response) {
			var prefix = value && rate_or_talktime == "rate" ? "$" : '';
			var suffix = value && rate_or_talktime == "talktime" ? " minutes" : '';
			$('#change_form').parent().html(prefix + response.responseText + suffix);
		}
	});
}
</script>
{/literal}

<div id="content">
	<h2>Change client pricing for
		<select onchange="window.location=window.location+'&zone='+this.value">
			{foreach from=$zones item=zone}
				<option value="{$zone}" {if $smarty.get.zone == $zone}selected="selected"{/if}>{$zone|ucwords}</option>
			{/foreach}
		</select>
	calls</h2>
	<div style="padding-left: 20px;">
		{foreach from=$customers key=companyid item=accounts}
			<h2>Client #{$companyid}</h2>
			<div style="padding-left: 20px;padding-bottom: 14px;">
				<strong>has {$accounts|@count} SIP trunk{if $accounts|@count != 1}s{/if}</strong>
				<div class="sip-account">
					{foreach from=$accounts item=account}{assign var="username" value=$account.0.username}{assign var="zone" value=$smarty.get.zone}
						<div class="num-ddi"><strong>{$account.0.username} &mdash; {$account|@count} DDI{if $account|@count != 1}s{/if}</strong></div>
						<div style="padding-bottom: 14px;">
							<table cellpadding="5" cellspacing="0" style="width: 100%;">
								<tr class="table-header">
									<td style="width: 14%;text-align: left;">Client DDI</td>
									<td style="width: 33%;">Price per {$smarty.get.zone} minute</td>
									<td style="width: 33%;">Free talktime per month</td>
								</tr>
								{foreach from=$account item=line}
									<tr class="table-data {cycle values="odd,even"}">
										<td style="text-align: left;">{$line.domain_formatted}</a></td>
										<td ondblclick="createForm($(this), '{$line.hostingid}', '{$zone}', 'rate');">{if $line.zones.$zone.rate}${$line.zones.$zone.rate}{else}{/if}</td>
										<td ondblclick="createForm($(this), '{$line.hostingid}', '{$zone}', 'talktime');">{if $line.zones.$zone.freemins}{$line.zones.$zone.freemins} minutes{else}{/if}</td>
									</tr>
								{/foreach}
							</table>
						</div>
					{/foreach}
				</div>
			</div>
		{/foreach}
	</div>
</div>