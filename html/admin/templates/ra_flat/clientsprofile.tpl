{strip}
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="clientsprofile.php?save=true&amp;userid={$clientsdetails.userid}">
                    {$infobox}
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">First Name</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="30" name="firstname" value="{$clientsdetails.firstname}" tabindex="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Last Name</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="30" name="lastname" value="{$clientsdetails.lastname}" tabindex="2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Company Name</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="30" name="companyname" value="{$clientsdetails.companyname}" tabindex="3">
                                            <font color="#cccccc"><small>(Optional)</small></font>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email Address</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="35" name="email" value="{$clientsdetails.email}" tabindex="4">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Password</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="20" name="password" value="Enter to Change" onfocus="if (this.value == 'Enter to Change')
                                                        this.value = ''" tabindex="5"> <a href="clientsprofile.php?userid=12437&amp;resetpw=true&amp;token=08d71233b8e79ff6a3f1b010d65562c4f1e12fa4"><img src="images/icons/resetpw.png" border="0" align="absmiddle"> Reset &amp; Send Password</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Date Of Birth</label>
                                        <div class="col-md-9">
                                            <input class="datepick form-control" type="text" size="20" name="dateofbirth" value="{$clientsdetails.dateofbirth}" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Status</label>
                                        <div class="col-md-9">
                                            <select class="form-control"  name="status" tabindex="24">
                                                {foreach from=$status item=row}
                                                    <option value="{$row}" {if $row eq $clientsdetails.status}selected{/if}>{$row}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Late Fees</label>
                                        <div class="col-md-9">
                                            <label class="checkbox">
                                                <input data-toggle="checkbox" type="checkbox" name="latefeeoveride" tabindex="15" {if $clientsdetails.latefeeoveride}checked{/if}> Don't Apply Late Fees
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Overdue Notices</label>
                                        <div class="col-md-9">
                                            <label class="checkbox">
                                                <input data-toggle="checkbox" type="checkbox" name="overideduenotices" tabindex="16" {if $clientsdetails.overideduenotices}checked{/if}> Don't Send Overdue Emails
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Currency</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="currency" tabindex="25">{$currencyoption}</select>
                                        </div>
                                    </div>

                                    {foreach from=$clientfields.left item=clientfielddata}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">{$clientfielddata.name}</label>
                                            <div class="col-md-9">
                                                {$clientfielddata.input}
                                            </div>
                                        </div>
                                    {/foreach}

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Address</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="30" name="address1" value="{$clientsdetails.address1}" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">City</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="25" name="city" value="{$clientsdetails.city}" tabindex="10">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Region</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="25" name="state" value="{$clientsdetails.state}" tabindex="11">
                                            <font color="#cccccc"><small>*</small></font>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Postcode</label>
                                        <div class="col-md-9">
                                            <input class="form-control"  type="text" size="14" name="postcode" value="{$clientsdetails.postcode}" tabindex="12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-9">
                                            <select class="form-control country" name="country" tabindex="13">
                                                {foreach from=$coutries item=country key=code}
                                                    <option value="{$country}" {if $clientsdetails.country eq $country}selected{/if}>{$country}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mobile Number</label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" size="20" name="mobilenumber" value="{$clientsdetails.mobilenumber}" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Phone Number</label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" size="20" name="phonenumber" value="{$clientsdetails.phonenumber}" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Payment Method</label>
                                        <div class="col-md-9">
                                            {$paymmentmethod}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Billing Contact</label>
                                        <div class="col-md-9">
                                            <select class="form-control"  name="billingcid" tabindex="22">
                                                <option value="0">Default</option>
                                                {if $billingcid}
                                                    {foreach from=$billingcid item=row key=id}
                                                        <option value="{$id}" {if $id eq $clientsdetail.billingid}selected{/if}>{$row.firstname} {$row.lastname}</option>
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Client Group</label>
                                        <div class="col-md-9">
                                            <select class="form-control"  name="groupid" tabindex="27">
                                                <option value="0">None</option>
                                                {if isset($groupid)}
                                                    {foreach from=$groupid item=row key=id}
                                                        <option value="{$id}" {if $id eq $clientsdetails.groupid}selected{/if} style="background-color:{$row.groupcolour}}">{$row.groupname}</option>
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                    {foreach from=$clientfields.right item=clientfielddata}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">{$clientfielddata.name}</label>
                                            <div class="col-md-9">
                                                {$clientfielddata.input}
                                            </div>
                                        </div>
                                    {/foreach}

                                </div>
                            </div>
                        </div>  
                    </div>
                    <div align="center"><input type="submit" value="Save Changes" class="btn btn-primary" tabindex="1"> <input type="reset" value="Cancel Changes" class="btn btn-danger" tabindex="2"></div>
                    <img src="images/spacer.gif" height="10" width="1"><br>
                </form>
            </div>
        </div>
    </div>
{/strip}

{literal}

{/literal}