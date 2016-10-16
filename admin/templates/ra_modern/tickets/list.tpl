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
              <option value="{$ticketstatus.title}"{if $ticketfilterdata.view eq $ticketstatus.title } selected{/if}>
                {$ticketstatus.title}
              </option>
            {/foreach}
            </select>
          </td>
          <td width="15%" class="fieldlabel">
            {$_ADMINLANG.fields.client}
          </td>
          <td class="fieldarea">
                <input type="text" name="client" value="{$ticketfilterdata.client}" size="10" />
          </td>
        </tr>

        <tr>
        <td class="fieldlabel">
          {$_ADMINLANG.support.department}
        </td>
        <td>
          <select name="deptid">
            <option value="">
              {$_ADMINLANG.global.any}
            </option> 
          {foreach from=$ticketdepts item=ticketdepartment}

            <option value="{$ticketdepartment.did}"{if $ticketdepartment.id eq $ticketfilterdata.deptid} selected{/if}> 
              {$ticketdepartment.name}
        {/foreach}
            </option>
          </select>
        </td>

        <td class="fieldlabel">
          {$_ADMINLANG.support.ticketid}
        </td>
        <td class="fieldarea">
          <input type="text" name="ticketid" size="15">
        </td>
        <td class="fieldlabel">
          {$_ADMINLANG.support.subjectmessages}
        </td>
        <td class="fieldarea">
          <input type="text" name="subject" size="40" value="{$ticketfilterdata.subject}" />
        </td>
        <td class="fieldlabel">
          {$_ADMINLANG.fields.email}
        </td>
        <td>
          <input type="text" name="email" size="40" value="{$ticketfilterdata.email}" />
        </td>
      </tr>
    </table>
   
    <img src="images/spacer.gif" height="10" width="1"><br>
    <div align="center">
      <input type="submit" value={$_ADMINLANG.global.searchfilter}" class="button">
    </div>

  </form>

</div>
</div>
<div id="tab1box" class="tabbox">
  <div id="tab_content">
  </div>
</div>
<div id="tab2box" class="tabbox">
  <div id="tab_content">
</div>
</div>
<br />
{if $ticketfilterdata.tag|strlen > 0}<h2>Filtering Tickets for Tag {$ticketfilterdata.tag}</h2>{/if}

            
 
            
        
{$content}
<hr />
