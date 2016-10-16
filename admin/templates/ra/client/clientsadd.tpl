<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <form action="{$PHP_SELF}" method="get">


                    <input type="hidden" name="userid" value="{$data.userid}">
                    <div class="form-inline">
                        Contacts: <select class="form-control" name="contactid" onchange="submit();">
                            {$contactlist}
                        </select>
                        <input type="submit" value="Go">
                    </div>

                </form>
            </div>
            <div class="box-body">
                <br>
                <form method="post" action="{$PHP_SELF}?action=save&amp;userid={$data.userid}&amp;contactid={$data.id}">
                    {$infobox}
                    <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                        <tbody>
                            <tr>
                                <td width="15%" class="fieldlabel">First Name</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="30" name="firstname" tabindex="1" value="{$data.firstname}"></td>
                                <td width="15%" class="fieldlabel">Address 1</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="30" name="address1" tabindex="7" value="{$data.address1 }"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Last Name</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="30" name="lastname" tabindex="2" value="{$data.lastname}"></td>
                                <td class="fieldlabel">City</td>
                                <td class="fieldarea"><input class="form-control" type="text" tabindex="9" size="25" name="city" value="{$data.city}"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Company Name</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="30" name="companyname" tabindex="3" value="{$data.companyname}"><small>(Optional)</small></td>
                                <td class="fieldlabel">Region</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="25" name="state" tabindex="10" value="{$data.state}"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Email Address</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="35" name="email" tabindex="4" value="{$data.email}"</td>
                                <td class="fieldlabel">Country</td><td class="fieldarea">
                                    {$countrydrop}
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Password</td>
                                <td class="fieldarea">
                                    <input class="form-control" type="text" size="20" name="password" tabindex="6" value="Enter to Change" onfocus="if (this.value == 'Enter to Change')
                                           {this.value=''}"> <a href="clientscontacts.php?userid={$data.userid}&amp;contactid={$data.id}&amp;resetpw=true&amp;token={$token}"><img src="images/icons/resetpw.png" border="0" align="absmiddle"> Reset &amp; Send Password</a></td>
                                <td class="fieldlabel">Postcode</td>
                                <td class="fieldarea"><input class="form-control" type="text" tabindex="11" size="14" name="postcode" value="{$data.postcode}"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel">Email Notifications</td><td class="fieldarea">
                                    <label><input type="checkbox" name="generalemails" {if $data.generalemails}checked{/if} tabindex="14"> General</label>
                                    <label><input type="checkbox" name="invoiceemails" {if $data.invoiceemails}checked{/if} tabindex="15"> Invoice</label>
                                    <label><input type="checkbox" name="supportemails" {if $data.supportemails}checked{/if} tabindex="16"> Support</label><br>
                                    <label><input type="checkbox" name="productemails" {if $data.productemails}checked{/if} tabindex="17"> Product</label>
                                    <label><input type="checkbox" name="affiliateemails" {if $data.affiliateemails}checked{/if} tabindex="19"> Affiliate</label>
                                </td>
                                <td class="fieldlabel">Phone Number</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="20" name="phonenumber" tabindex="13" value="{$data.phonenumber}"></td>
                            </tr>
                            <tr>
                                <td class="fieldlabel"></td>
                                <td class="fieldarea"></td>
                                <td class="fieldlabel">Mobile Number</td>
                                <td class="fieldarea"><input class="form-control" type="text" size="20" name="mobilenumber" tabindex="13" value="{$data.mobilenumber}"></td>
                            </tr>
                            <tr><td class="fieldlabel">Permissions</td>
                                <td class="fieldarea" colspan="3">
                                    <table width="100%" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td width="50%" valign="top">
                                                    <br>
                                                    <label>
                                                        <input type="checkbox" name="permissions[]" tabindex="22" value="contacts" {if $data.productemails}checked{/if}> View &amp; Manage Contacts</label>
                                                    <br>
                                                    <label>
                                                        <input type="checkbox" name="permissions[]" tabindex="23" value="products" {if $data.productemails}checked{/if}> View Products &amp; Services</label>
                                                    <br><label>
                                                        <input type="checkbox" name="permissions[]" tabindex="24" value="manageproducts" {if $data.productemails}checked{/if}> View &amp; Modify Product Passwords</label>
                                                    <br>
                                                </td>
                                                <td width="50%" valign="top">
                                                    <label><input type="checkbox" name="permissions[]" tabindex="27" {if $data.permissions.invoices}checked{/if} value="invoices"> View &amp; Pay Invoices</label><br>
                                                    <label><input type="checkbox" name="permissions[]" tabindex="28" {if $data.permissions.tickets}checked{/if} value="tickets"> View &amp; Open Support Tickets</label><br>
                                                    <label><input type="checkbox" name="permissions[]" tabindex="29" {if $data.permissions.affiliates}checked{/if} value="affiliates"> View &amp; Manage Affiliate Account</label><br>
                                                    <label><input type="checkbox" name="permissions[]" tabindex="30" {if $data.permissions.emails}checked{/if} value="emails"> View Emails</label><br>
                                                    <label><input type="checkbox" name="permissions[]" tabindex="31" {if $data.permissions.orders}checked{/if} value="orders"> Place New Orders/Upgrades/Cancellations</label>
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
    </div>
</div>