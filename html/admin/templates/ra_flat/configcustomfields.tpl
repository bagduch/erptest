
<p>This is where you configure custom fields which appear in the clients profile.</p>
<form method="post" action="/admin/configcustomfields.php?action=save">
  <b>Add New Custom Field</b><br>
  <br>
  {if $infobox}
  {$infobox}
  {/if}
  {if $cfids}
  
  {foreach key=num item=cfid from=$cfids}
  <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
    <tbody>
      <tr>
        <td width="100" class="fieldlabel">Field Name</td>
        <td class="fieldarea"><table width="98%" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td><input type="text" name="addfieldname" size="30"></td>
                <td align="right">Display Order
                  <input type="text" name="addsortorder" size="5" value="0"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td class="fieldlabel">Field Type</td>
        <td class="fieldarea"><select name="addfieldtype">
            <option value="text">Text Box</option>
            <option value="link">Link/URL</option>
            <option value="password">Password</option>
            <option value="dropdown">Drop Down</option>
            <option value="tickbox">Tick Box</option>
            <option value="date">Date</option> 
            <option value="textarea">Text Area</option>
          </select></td>
      </tr>
      <tr>
        <td class="fieldlabel">Description</td>
        <td class="fieldarea"><input type="text" name="adddescription" size="60">
          The explanation to show users </td>
      </tr>
      <tr>
        <td class="fieldlabel">Validation</td>
        <td class="fieldarea"><input type="text" name="addregexpr" size="60">
          Regular Expression Validation String </td>
      </tr>
      <tr>
        <td class="fieldlabel">Select Options</td>
        <td class="fieldarea"><input type="text" name="addfieldoptions" size="60">
          For Dropdowns Only - Comma Seperated List</td>
      </tr>
      <tr>
        <td class="fieldlabel"></td>
        <td class="fieldarea"><input type="checkbox" name="addadminonly">
          Admin Only
          <input type="checkbox" name="addrequired">
          Required Field
          <input type="checkbox" name="addshoworder">
          Show on Order Form
          <input type="checkbox" name="addshowinvoice">
          Show on Invoice </td>
      </tr>
    </tbody>
  </table>
  {/foreach}
  {/if}
  <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
    <tbody>
      <tr>
        <td width="100" class="fieldlabel">Field Name</td>
        <td class="fieldarea"><table width="98%" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td><input type="text" name="addfieldname" size="30"></td>
                <td align="right">Display Order
                  <input type="text" name="addsortorder" size="5" value="0"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td class="fieldlabel">Field Type</td>
        <td class="fieldarea"><select name="addfieldtype">
            <option value="text">Text Box</option>
            <option value="link">Link/URL</option>
            <option value="password">Password</option>
            <option value="dropdown">Drop Down</option>
            <option value="tickbox">Tick Box</option>
            <option value="textarea">Text Area</option>
          </select></td>
      </tr>
      <tr>
        <td class="fieldlabel">Description</td>
        <td class="fieldarea"><input type="text" name="adddescription" size="60">
          The explanation to show users</td>
      </tr>
      <tr>
        <td class="fieldlabel">Validation</td>
        <td class="fieldarea"><input type="text" name="addregexpr" size="60">
          Regular Expression Validation String</td>
      </tr>
      <tr>
        <td class="fieldlabel">Select Options</td>
        <td class="fieldarea"><input type="text" name="addfieldoptions" size="60">
          For Dropdowns Only - Comma Seperated List</td>
      </tr>
      <tr>
        <td class="fieldlabel"></td>
        <td class="fieldarea"><input type="checkbox" name="addadminonly">
          Admin Only
          <input type="checkbox" name="addrequired">
          Required Field
          <input type="checkbox" name="addshoworder">
          Show on Order Form
          <input type="checkbox" name="addshowinvoice">
          Show on Invoice</td>
      </tr>
    </tbody>
  </table>
  <br>
  <div align="center">
    <input type="submit" value="Save Changes" class="button">
  </div>
</form>
