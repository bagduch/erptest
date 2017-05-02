{strip}

    <form method="post" action="/admin/clientsprofile.php?save=true&amp;userid={$clientsdetails.userid}">
        {$infobox}
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tbody><tr><td width="15%" class="fieldlabel">First Name</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="30" name="firstname" value="{$clientsdetails.firstname}" tabindex="1"></td>
                    <td class="fieldlabel" width="15%">Address</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="30" name="address1" value="{$clientsdetails.address1}" tabindex="8"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Last Name</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="30" name="lastname" value="{$clientsdetails.lastname}" tabindex="2"></td>
                    <td class="fieldlabel">City</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="25" name="city" value="{$clientsdetails.city}" tabindex="10"></td>

                </tr>
                <tr>
                    <td class="fieldlabel">Company Name</td>
                    <td class="fieldarea">
                        <input class="form-control"  type="text" size="30" name="companyname" value="{$clientsdetails.companyname}" tabindex="3">
                        <font color="#cccccc"><small>(Optional)</small></font>
                    </td>
                    <td class="fieldlabel">Region</td>
                    <td class="fieldarea region">
                        <input class="form-control"  type="text" size="25" name="state" value="{$clientsdetails.state}" tabindex="11">
                    </td>

                </tr>
                <tr>
                    <td class="fieldlabel">Email Address</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="35" name="email" value="{$clientsdetails.email}" tabindex="4"></td>
                    <td class="fieldlabel">Postcode</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="14" name="postcode" value="{$clientsdetails.postcode}" tabindex="12"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Password</td>
                    <td class="fieldarea"><input class="form-control"  type="text" size="20" name="password" value="Enter to Change" onfocus="if (this.value == 'Enter to Change')
                                this.value = ''" tabindex="5"> <a href="clientsprofile.php?userid=12437&amp;resetpw=true&amp;token=08d71233b8e79ff6a3f1b010d65562c4f1e12fa4"><img src="images/icons/resetpw.png" border="0" align="absmiddle"> Reset &amp; Send Password</a></td>
                    <td class="fieldlabel">Country</td>
                    <td class="fieldarea">
                        <select class="form-control country" name="country" tabindex="13">
                            {foreach from=$coutries item=country key=code}
                                <option value="{$country}" {if $clientsdetails.country eq $country}selected{/if}>{$country}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Date Of Birth</td>
                    <td class="fieldarea"><input class="form-control" type="text" size="20" name="dateofbirth" value="{$clientsdetails.dateofbirth}" tabindex="14"></td>
                    <td class="fieldlabel">Language</td>
                    <td class="fieldarea"><select  class="form-control"  name="language" tabindex="23"><option value="">Default</option><option value="en">En</option></select></td>
                </tr>
                <tr>    
                    <td class="fieldlabel">Status</td>
                    <td class="fieldarea">
                        <select class="form-control"  name="status" tabindex="24">
                            {foreach from=$status item=row}
                                <option value="{$row}" {if $row eq $clientsdetails.status}selected{/if}>{$row}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td class="fieldlabel">Phone Number</td>
                    <td class="fieldarea"><input class="form-control" type="text" size="20" name="phonenumber" value="{$clientsdetails.phonenumber}" tabindex="14"></td>
                </tr>
                <tr>
                    <td class="fieldlabel"><br></td>
                    <td class="fieldarea"></td>
                    <td class="fieldlabel"></td>
                    <td class="fieldarea"></td>
                </tr>
                <tr>
                    <td class="fieldlabel">Late Fees</td>
                    <td class="fieldarea"><input type="checkbox" name="latefeeoveride" tabindex="15" {if $clientsdetails.latefeeoveride}checked{/if}> Don't Apply Late Fees</td>
                    <td class="fieldlabel">Payment Method</td>
                    <td class="fieldarea">
                        {$paymmentmethod}
                    </td>
                </tr>
                <tr>
                    <td class="fieldlabel">Overdue Notices</td>
                    <td class="fieldarea"><input type="checkbox" name="overideduenotices" tabindex="16" {if $clientsdetails.overideduenotices}checked{/if}> Don't Send Overdue Emails</td>
                    <td class="fieldlabel">Billing Contact</td>
                    <td class="fieldarea">
                        <select class="form-control"  name="billingcid" tabindex="22">
                            <option value="0">Default</option>
                            {if $billingcid}
                                {foreach from=$billingcid item=row key=id}
                                    <option value="{$id}" {if $id eq $clientsdetail.billingid}selected{/if}>{$row.firstname} {$row.lastname}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </td>
                </tr>

                <tr>

                    <td class="fieldlabel">Currency</td>
                    <td class="fieldarea"><select class="form-control" name="currency" tabindex="25">{$currencyoption}</select></td>
                    <td class="fieldlabel">Client Group</td>
                    <td class="fieldarea">
                        <select class="form-control"  name="groupid" tabindex="27">
                            <option value="0">None</option>
                            {if isset($groupid)}
                                {foreach from=$groupid item=row key=id}
                                    <option value="{$id}" {if $id eq $clientsdetails.groupid}selected{/if} style="background-color:{$row.groupcolour}}">{$row.groupname}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </td>
                </tr>
                {foreach from=$clientfields item=clientfielddata}
                    <tr>
                        {foreach from=$clientfielddata item=row}
                            <td class="fieldlabel">{$row.name}</td>
                            <td class="fieldarea">{$row.input}</td>
                        {/foreach}
                    </tr>
                {/foreach}

            </tbody>
        </table>
        <img src="images/spacer.gif" height="10" width="1"><br>
        <div align="center"><input type="submit" value="Save Changes" class="btn btn-primary" tabindex="1"> <input type="reset" value="Cancel Changes" class="button" tabindex="2"></div>
    </form>
{/strip}

{literal}
    
{/literal}