{debug}
TEMPLATE START
<div id="tab0box" class="tabbox">
  <div id="tab_content">
    <form action="{$SCRIPT_NAME}" method="post">
      <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tr>
          <td width="15%" class="fieldlabel">{$_ADMINLANG.fields.status}</td>
          <td class="fieldarea">
            <select name="view" />
              <option value="any"{if $ticketfilterdata.view eq "any"} selected{/if}>
                {$_ADMINLANG.global.any}
              </option>
              <option value=""{if $ticketfilterdata.view eq ""} selected{/if}>
                {$_ADMINLANG.support.awaitingreply}
              </option>
              <option value="flagged"{if $ticketfilterdata.view eq "flagged"} selected{/if}>
                {$_ADMINLANG.support.flagged}
              </option>
              <option value="active"{if $ticketfilterdata.view eq "active"} selected{/if}>
                {$_ADMINLANG.support.allactive}
              </option>
            {foreach from=$ticketstatuses item=ticketstatus}
              <option value="{$ticketstatus.title}"{if $ticketfilterdata.view eq {$ticketstatus.title} selected{/if}>
                {$ticketstatus.title}
              </option>
            {/foreach}
            </select>
          </td>
          <td width="15%" class="fieldlabel">
            {$_ADMINLANG.fields.client}
        
CONTENT START
{$content}
<hr />


NEW STUFF HERE<br />
