{*
Variables passed:
userid int
contactid int or "addnew"
token "<option value="addnew" selected>Add N..."
countrydrop "<select class=form-control country ...>"
infobox
contactlist
content
jquerycode
cdata - associative array of row from tblcontacts;
  only exists when contactid is int (and not addnew)
*}
<div class="card">
    <div class="row">
        <div class="col-md-12">
            <div class="header card-header-text">
                <div class="row">
                    <form action="{$PHP_SELF}" method="get">
                        <div class="col-md-6">
                            <input type="hidden" name="userid" value="{$userid}">
                            <label class="col-md-2">Contacts:</label>
                            <div class="col-md-4">
                                <select class="selectpicker" name="contactid" onchange="submit();">
                                    {$contactlist}
                                </select>
                            </div>
                            <input class="btn btn-success" type="submit" value="Go">
                        </div>
                    </form>
                </div>
            </div>
            <div class="content">
                {$infobox}
                <form method="post" action="{$PHP_SELF}?action=save&amp;userid={$userid}">
                    <input type="hidden" value="{$contactid}" name="contactid">
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">First Name</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="30" name="firstname" tabindex="1" value="{$cdata.firstname|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last Name</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="30" name="lastname" tabindex="2" value="{$cdata.lastname|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Company Name</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="30" name="companyname" tabindex="3" value="{$cdata.companyname|default:""}"><small>(Optional)</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email Address</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="35" name="email" tabindex="4" value="{$cdata.email|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Activate Sub-Account</label>
                                <div class="col-md-9">
                                    <label class="checkbox"><input data-toggle="checkbox"  type="checkbox" tabindex="5" name="subaccount" {if isset($cdata.subaccount) && $cdata.subaccount}checked{/if} id="subaccount">Tick to Enable</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="20" name="password" tabindex="6" placeholder="Enter to Change">
                                    <a href="clientscontacts.php?userid={$userid}&amp;contactid={$contactid}&amp;resetpw=true&amp;token={$token}"><img src="images/icons/resetpw.png" border="0" align="absmiddle"> Reset &amp; Send Password</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email Notifications</label>
                                <div class="col-md-9">
                                    <label class="checkbox checkbox-inline"><input data-toggle="checkbox" type="checkbox" name="generalemails" {if $cdata.generalemails|default:0}checked{/if} tabindex="14"> General</label>
                                    <label class="checkbox checkbox-inline"><input data-toggle="checkbox" type="checkbox" name="invoiceemails" {if $cdata.invoiceemails|default:0}checked{/if} tabindex="15"> Invoice</label>
                                    <label class="checkbox checkbox-inline"><input data-toggle="checkbox" type="checkbox" name="supportemails" {if $cdata.supportemails|default:0}checked{/if} tabindex="16"> Support</label><br>
                                    <label class="checkbox checkbox-inline"><input data-toggle="checkbox" type="checkbox" name="productemails" {if $cdata.productemails|default:0}checked{/if} tabindex="17"> Product</label>
                                    <label class="checkbox checkbox-inline"><input data-toggle="checkbox" type="checkbox" name="affiliateemails" {if $cdata.affiliateemails|default:0}checked{/if} tabindex="19"> Affiliate</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Permissions</label>
                                <div class="col-md-9">
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="21" {if 'profile'|in_array:$cdata.permissions|default:0}checked{/if} value="profile"> Modify Master Account Profile</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="22" {if 'contacts'|in_array:$cdata.permissions|default:0}checked{/if}  value="contacts"> View &amp; Manage Contacts</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="23" {if 'products'|in_array:$cdata.permissions|default:0}checked{/if}  value="products"> View Products &amp; Services</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="24" {if 'manageproducts'|in_array:$cdata.permissions|default:0}checked{/if}  value="manageproducts"> View &amp; Modify Product Passwords</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="30" name="address1" tabindex="7" value="{$cdata.address1|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Address 2</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="30" name="address2" tabindex="8" value="{$cdata.address2|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" tabindex="9" size="25" name="city" value="{$cdata.city|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Postcode</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" tabindex="10" size="14" name="postcode" value="{$cdata.postcode|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Region</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" tabindex="11" size="14" name="state" value="{$cdata.state|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Country</label>
                                <div class="col-md-9">
                                    {$countrydrop}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Phone Number</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="20" name="phonenumber" tabindex="12" value="{$cdata.phonenumber|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Mobile Number</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" size="20" name="mobilenumber" tabindex="13" value="{$cdata.mobilenumber|default:""}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                  <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="28" {if 'tickets'|in_array:$cdata.permissions|default:0}checked{/if} value="tickets"> View &amp; Open Support Tickets</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="27" {if 'invoices'|in_array:$cdata.permissions|default:0}checked{/if} value="invoices"> View &amp; Pay Invoices</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="29" {if 'affiliates'|in_array:$cdata.permissions|default:0}checked{/if} value="affiliates"> View &amp; Manage Affiliate Account</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="30" {if 'emails'|in_array:$cdata.permissions|default:0}checked{/if} value="emails"> View Emails</label>
                                    <label class="checkbox"><input data-toggle="checkbox" type="checkbox" name="permissions[]" tabindex="31" {if 'orders'|in_array:$cdata.permissions|default:0}checked{/if} value="orders"> Place New Orders/Upgrades/Cancellations</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" align="center">
                      <input type="submit" value="Save Changes" class="btn btn-primary" tabindex="31">
                      <input type="reset" value="Cancel Changes" class="btn btn-warning" tabindex="32">
                      {if $contactid != "addnew"}
                        <a class="btn btn-danger" href="#" onclick="deleteContact('{$contactid}');
                                              return false" style="color:#cc0000"><b>Delete</b></a>
                      {/if}
                    </p>
                </form>
            </div>
        </div>
    </div>

</div>
