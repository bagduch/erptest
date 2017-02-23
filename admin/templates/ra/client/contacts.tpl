<div id="tab0box" class="tabbox">
    <div id="tab_content" style="text-align:left;">
        <form action="{$PHP_SELF}" method="get">
            <input type="hidden" name="userid" value="{$userid}">
            Contacts: <select name="contactid" onchange="submit();">
                {$contactlist}
            </select> <input type="submit" value="Go">
        </form>
        <br>
        <form method="post" action="{$PHP_SELF}?action=save&amp;userid={$userid}">
            <input type="hidden" value="{$contactid}" name="contactid">
            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr>
                        <td width="15%" class="fieldlabel">First Name</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="30" name="firstname" tabindex="1" value="{$cdata.firstname}"></td>
                        <td width="15%" class="fieldlabel">Address 1</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="30" name="address1" tabindex="7" value="{$cdata.address1}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Last Name</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="30" name="lastname" tabindex="2" value="{$cdata.lastname}"></td>
                        <td class="fieldlabel">Address 2</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="30" name="address2" tabindex="7" value="{$cdata.address2}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Company Name</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="30" name="companyname" tabindex="3" value="{$cdata.companyname}"><small>(Optional)</small></td>
                        <td class="fieldlabel">City</td>
                        <td class="fieldarea"><input class="form-control" type="text" tabindex="9" size="25" name="city" value="{$cdata.city}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Email Address</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="35" name="email" tabindex="4" value="{$cdata.email}"></td>
                        <td class="fieldlabel">Postcode</td>
                        <td class="fieldarea"><input class="form-control" type="text" tabindex="11" size="14" name="postcode" value="{$cdata.postcode}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Activate Sub-Account</td>
                        <td class="fieldarea"><input type="checkbox" tabindex="5" name="subaccount" {if $cdata.subaccount}checked{/if} id="subaccount"> <label for="subaccount">Tick to Enable</label></td>
                        <td class="fieldlabel">Country</td>
                        <td class="fieldarea">
                            {$countrydrop}     
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Password</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="20" name="password" tabindex="6" value="Enter to Change" onfocus="if (this.value == 'Enter to Change')
                                                     {this.value=''}">
                            <a href="clientscontacts.php?userid={$userid}&amp;contactid={$contactid}&amp;resetpw=true&amp;token={$token}"><img src="images/icons/resetpw.png" border="0" align="absmiddle"> Reset &amp; Send Password</a>
                        </td>
                        <td class="fieldlabel">Phone Number</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="20" name="phonenumber" tabindex="13" value="{$cdata.phonenumber}"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Email Notifications</td>
                        <td class="fieldarea">
                            <label><input type="checkbox" name="generalemails" {if $cdata.generalemails}checked{/if} tabindex="14"> General</label>
                            <label><input type="checkbox" name="invoiceemails" {if $cdata.invoiceemails}checked{/if} tabindex="15"> Invoice</label>
                            <label><input type="checkbox" name="supportemails" {if $cdata.supportemails}checked{/if} tabindex="16"> Support</label><br>
                            <label><input type="checkbox" name="productemails" {if $cdata.productemails}checked{/if} tabindex="17"> Product</label>
                            <label><input type="checkbox" name="affiliateemails" {if $cdata.affiliateemails}checked{/if} tabindex="19"> Affiliate</label>
                        </td>
                        <td class="fieldlabel">Mobile Number</td>
                        <td class="fieldarea"><input class="form-control" type="text" size="20" name="mobilenumber" tabindex="13" value="{$cdata.mobilenumber}"></td>
                    </tr>

                    <tr>
                        <td class="fieldlabel">Permissions</td>
                        <td class="fieldarea" colspan="3">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td width="50%" valign="top">
                                            <label><input type="checkbox" name="permissions[]" tabindex="21" {if 'profile'|in_array:$cdata.permissions}checked{/if} value="profile"> Modify Master Account Profile</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="22" {if 'contacts'|in_array:$cdata.permissions}checked{/if}  value="contacts"> View &amp; Manage Contacts</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="23" {if 'products'|in_array:$cdata.permissions}checked{/if}  value="products"> View Products &amp; Services</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="24" {if 'manageproducts'|in_array:$cdata.permissions}checked{/if}  value="manageproducts"> View &amp; Modify Product Passwords</label>
                                            <br>
                                        </td>
                                        <td width="50%" valign="top">
                                            <label><input type="checkbox" name="permissions[]" tabindex="27" {if 'invoices'|in_array:$cdata.permissions}checked{/if} value="invoices"> View &amp; Pay Invoices</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="28" {if 'tickets'|in_array:$cdata.permissions}checked{/if} value="tickets"> View &amp; Open Support Tickets</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="29" {if 'affiliates'|in_array:$cdata.permissions}checked{/if} value="affiliates"> View &amp; Manage Affiliate Account</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="30" {if 'emails'|in_array:$cdata.permissions}checked{/if} value="emails"> View Emails</label>
                                            <br>
                                            <label><input type="checkbox" name="permissions[]" tabindex="31" {if 'orders'|in_array:$cdata.permissions}checked{/if} value="orders"> Place New Orders/Upgrades/Cancellations</label>
                                            <br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody></table>

            <p align="center"><input type="submit" value="Save Changes" class="btn btn-primary" tabindex="31"> <input type="reset" value="Cancel Changes" class="button" tabindex="32"><br>
                <a href="#" onclick="deleteContact('2');
                                return false" style="color:#cc0000"><b>Delete</b></a></p>
        </form>
    </div>
</div>