<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h3 class='box-title'>Add New Client</h3>
            </div>
            <div class="box-body">
                <br>
                <form method="post" action="{$PHP_SELF}?action=add&amp;userid={$data.userid}">
                    {$infobox}
                    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody><tr><td width="15%" class="fieldlabel">First Name</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="30" name="firstname" tabindex="1"></td>
                                <td class="fieldlabel" width="15%">Address</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="30" name="address1" tabindex="8"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Last Name</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="30" name="lastname" tabindex="2"></td>
                                <td class="fieldlabel">City</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="25" name="city"  tabindex="10"></td>

                            </tr>
                            <tr>
                                <td class="fieldlabel">Company Name</td>
                                <td class="fieldarea">
                                    <input class="form-control"  type="text" size="30" name="companyname" tabindex="3">
                                    <font color="#cccccc"><small>(Optional)</small></font>
                                </td>
                                <td class="fieldlabel">Region</td>
                                <td class="fieldarea region"><input class="form-control"  type="text" size="25" name="state"  tabindex="11"></td>

                            </tr>
                            <tr>
                                <td class="fieldlabel">Email Address</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="35" name="email"  tabindex="4"></td>
                                <td class="fieldlabel">Postcode</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="14" name="postcode" tabindex="12"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Password</td>
                                <td class="fieldarea"><input class="form-control"  type="text" size="20" name="password"  tabindex="5"></td>
                                <td class="fieldlabel">Country</td>
                                <td class="fieldarea">
                                        {$countrydrop}
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Date Of Birth</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="20" name="dateofbirth" value="{$data.dateofbirth}" tabindex="14"></td>
                                <td class="fieldlabel">Language</td>
                                <td class="fieldarea"><select  class="form-control"  name="language" tabindex="23"><option value="">Default</option><option value="en">En</option></select></td>
                            </tr>
                            <tr>    
                                <td class="fieldlabel">Status</td>
                                <td class="fieldarea">
                                    <select class="form-control"  name="status" tabindex="24">
                                        {foreach from=$status item=row}
                                            <option value="{$row}" {if $row eq $data.status}selected{/if}>{$row}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td class="fieldlabel">Phone Number</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="20" name="phonenumber" value="{$data.phonenumber}" tabindex="14"></td>
                            </tr>
                          
                            <tr>
                                <td class="fieldlabel">Late Fees</td>
                                <td class="fieldarea"><input type="checkbox" name="latefeeoveride" tabindex="15" {if $data.latefeeoveride}checked{/if}> Don't Apply Late Fees</td>
                                <td class="fieldlabel">Payment Method</td>
                                <td class="fieldarea">
                                    {$paymentmethoddrop}
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Overdue Notices</td>
                                <td class="fieldarea"><input type="checkbox" name="overideduenotices" tabindex="16" {if $data.overideduenotices}checked{/if}> Don't Send Overdue Emails</td>
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
                                            {foreach from=$$groupoption item=row key=id}
                                                <option value="{$id}" {if $id eq $data.groupid}selected{/if} style="background-color:{$row.groupcolour}}">{$row.groupname}</option>
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
                    <p align="center"><input type="submit" value="Add Client" class="btn btn-primary" tabindex="31"> <br>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>