<form method="post" action="{$PHP_SELF}?action=save" name="configfrm">
    <div class="row">
        <div class="col-lg-12">
            <div id="addwhitelistip" title="Add Whitelisted IP" style="display:none;">
                <p></p><table><tbody><tr><td>IP Address:</td><td><input type="text" class="form-control" id="ipaddress" size="20"></td></tr><tr><td>Reason:</td><td><input type="text" class="form-control" id="notes" size="40"></td></tr></tbody></table><p></p>
            </div>
            <div id="addapiip" title="Add Whitelisted IP" style="display:none;">
                <p></p><table><tbody><tr><td>IP Address:</td><td><input type="text" class="form-control" id="ipaddress2" size="20"></td></tr><tr><td>Notes:</td><td><input type="text" class="form-control" id="notes2" size="40"></td></tr></tbody></table><p></p>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab0" aria-controls="tab0" role="tab" data-toggle="tab">General</a></li>
                    <li role="presentation"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">Localisation</a></li>
                    <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">Ordering</a></li>
                    <li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">Mail</a></li>
                    <li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">Support</a></li>
                    <li role="presentation"><a href="#tab6" aria-controls="tab6" role="tab" data-toggle="tab">Invoices</a></li>
                    <li role="presentation"><a href="#tab12" aria-controls="tab12" role="tab" data-toggle="tab">Invoices Details</a></li>
                    <li role="presentation"><a href="#tab7" aria-controls="tab7" role="tab" data-toggle="tab">Credit</a></li>
                    <li role="presentation"><a href="#tab8" aria-controls="tab8" role="tab" data-toggle="tab">Affiliates</a></li>
                    <li role="presentation"><a href="#tab9" aria-controls="tab9" role="tab" data-toggle="tab">Security</a></li>
                    <li role="presentation"><a href="#tab10" aria-controls="tab10" role="tab" data-toggle="tab">Social</a></li>
                    <li role="presentation"><a href="#tab11" aria-controls="tab11" role="tab" data-toggle="tab">Other</a></li>
                </ul>
                <!-- General -->
                <div class="tab-content">
                    <div role="tabpanel" id="tab0" class="tab-pane active">

                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">Company Name</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="companyname" value="{$CONFIG.CompanyName}" size="35"> Your Company Name as you want it to appear throughout the system</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Email Address</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="email" value="{$CONFIG.Email}" size="35"> The default sender address used for emails sent by ra</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"></td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="domain" value="{$CONFIG.Domain}" size="50"> The URL to your website homepage</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Logo URL</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="logourl" value="{$CONFIG.LogoURL}" size="70"><br>Enter your logo URL to display in email messages or leave blank for none</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Pay To Text</td>
                                    <td class="fieldarea">
                                        <textarea class="form-control" cols="50" rows="5" name="invoicepayto">{$CONFIG.InvoicePayTo}</textarea>
                                        <br>This text is displayed on the invoice as the Pay To details
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">ra System URL</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="systemurl" value="{$CONFIG.SystemURL}" size="50"><br>URL of the ra installation, eg. http://www.yourdomain.com/members/
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">ra SSL System URL</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="systemsslurl" value="{$CONFIG.SystemSSLURL}" size="50"><br>URL of the ra installation for secure access, eg. https://www.yourdomain.com/members/ (leave blank for no SSL)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Template</td>
                                    <td class="fieldarea">
                                        <select class="form-control"name="template">
                                            {$templatearray}
                                        </select> 
                                        The template you want ra to use</td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Limit Activity Log</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="activitylimit" size="6" value="{$CONFIG.ActivityLimit}"> The Number of Activity Log Entries you wish to keep
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Records to Display per Page</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="numrecords">
                                            <option {if $CONFIG.ActivityLimit eq "25"}selected{/if}>25</option>
                                            <option {if $CONFIG.ActivityLimit eq "50"}selected{/if}>50</option>
                                            <option {if $CONFIG.ActivityLimit eq "100"}selected{/if}>100</option>
                                            <option {if $CONFIG.ActivityLimit eq "200"}selected{/if}>200</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Maintenance Mode</td>
                                    <td class="fieldarea"><label><input type="checkbox" class="flat-red" name="maintenancemode" {if $CONFIG.MaintenanceMode eq "on"}checked{/if}> Tick to enable - prevents client area access when enabled</label></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Maintenance Mode Message</td>
                                    <td class="fieldarea">
                                        <textarea class="form-control" cols="75" rows="3" name="maintenancemodemessage">{$CONFIG.MaintenanceModeMessage}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Maintenance Mode Redirect URL</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="maintenancemodeurl" value="{$CONFIG.MaintenanceModeURL}" size="75"><br>If specified, redirects client area visitors to this URL when Maintenance Mode is enabled
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Localisation -->
                    <div role="tabpanel" id="tab1" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">System Charset</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="charset" value="{$CONFIG.Charset}" size="20"> Default: utf-8</td>
                                </tr>
                                <tr><td class="fieldlabel">Date Format</td><td class="fieldarea">
                                        <select class="form-control" name="dateformat">
                                            <option value="DD/MM/YYYY" {if $CONFIG.DateFormat eq 'DD/MM/YYYY'}selected{/if}>DD/MM/YYYY</option>
                                            <option value="DD.MM.YYYY" {if $CONFIG.DateFormat eq 'DD.MM.YYYY'}selected{/if}>DD.MM.YYYY</option>
                                            <option value="DD-MM-YYYY" {if $CONFIG.DateFormat eq 'DD-MM-YYYY'}selected{/if}>DD-MM-YYYY</option>
                                            <option value="MM/DD/YYYY" {if $CONFIG.DateFormat eq 'MM/DD/YYYY'}selected{/if}>MM/DD/YYYY</option>
                                            <option value="YYYY/MM/DD" {if $CONFIG.DateFormat eq 'YYYY/MM/DD'}selected{/if}>YYYY/MM/DD</option>
                                            <option value="YYYY-MM-DD" {if $CONFIG.DateFormat eq 'YYYY-MM-DD'}selected{/if}>YYYY-MM-DD</option>
                                        </select> Choose Display Style for Admins &amp; Staff (must be numeric to allow for date input)</td></tr>
                                <tr><td class="fieldlabel">Client Date Format</td>
                                    <td class="fieldarea">
                                        <select class="form-control"name="clientdateformat">
                                            <option value="" {if $CONFIG.clientdateformat eq ''}selected{/if}>Same as Admin (Above)</option>
                                            <option value="full" {if $CONFIG.clientdateformat eq 'full'}selected{/if}>1st January 2000</option>
                                            <option value="shortmonth" {if $CONFIG.clientdateformat eq 'shortmonth'}selected{/if}>1st Jan 2000</option>
                                            <option value="fullday" {if $CONFIG.clientdateformat eq 'fullday'}selected{/if}>Monday, January 1st, 2000</option>
                                        </select> Choose Display Style you want to use for clients</td></tr>
                                <tr><td class="fieldlabel">Default Country</td>
                                    <td class="fieldarea">
                                        {$countryarray}
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Default Language</td>
                                    <td class="fieldarea">
                                        <select class="form-control"name="language">
                                            {$languagearray}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Enable Language Menu</td>
                                    <td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="allowuserlanguage" {if $CONFIG.AllowLanguageChange eq 'on'}checked{/if}> Allow users to change the language of the system</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Ordering -->
                    <div role="tabpanel" id="tab2" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">Order Days Grace</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="orderdaysgrace" size="5" value="{$CONFIG.OrderDaysGrace}">
                                        The number of days to allow for payment of an order before being overdue
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Default Order Form Template</td>
                                    <td class="fieldarea">
                                        {$ordertemplatearray}
                                        <table width="100%">
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Enable TOS Acceptance</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="enabletos" {if $CONFIG.EnableTOSAccept eq "on"}checked{/if}> If ticked, clients must agree to your Terms of Service</label></td></tr>
                                <tr><td class="fieldlabel">Terms of Service URL</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="tos" style="width:80%" value="" {if $CONFIG.TermsOfService eq "on"}checked{/if}><br>The URL to your Terms of Service page on your site (eg. http://www.yourdomain.com/tos.html)</td></tr>
                                <tr><td class="fieldlabel">Auto Redirect on Checkout</td><td class="fieldarea">
                                        <label><input type="radio" class="flat-red" name="autoredirecttoinvoice" value="" {if $CONFIG.AutoRedirectoInvoice eq "on"}checked{/if}> Just show the order completed page (no payment redirect)</label><br><label><input type="radio" class="flat-red" name="autoredirecttoinvoice" value="on"> Automatically take the user to the invoice</label><br><label><input type="radio" class="flat-red" name="autoredirecttoinvoice" value="gateway" checked=""> Automatically forward the user to the payment gateway</label></td></tr>
                                <tr><td class="fieldlabel">Allow Notes on Checkout</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="shownotesfieldoncheckout" {if $CONFIG.ShowNotesFieldonCheckout eq "on"}checked{/if}> Tick this box to show a field on the order form where the customer can enter additional info for staff</label></td></tr>
                                <tr><td class="fieldlabel">Monthly Pricing Breakdown</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="productmonthlypricingbreakdown" {if $CONFIG.ProductMonthlyPricingBreakdown eq "on"}checked{/if}> Tick this box to enable monthly pricing breakdown for recurring terms on the order form</label></td></tr>
                                <tr><td class="fieldlabel">Block Existing Domains</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="allowdomainstwice" {if $CONFIG.AllowDomainsTwice eq "on"}checked{/if}> Tick this box to prevent orders being placed for domains already in your system</label></td></tr>
                                <tr><td class="fieldlabel">No Invoice Email on Order</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="noinvoicemeailonorder" {if $CONFIG.NoInvoiceEmailOnOrder eq "on"}checked{/if}> Tick this box to not send an invoice due notice when new orders are placed</label></td></tr>
                                <tr><td class="fieldlabel">Skip Fraud Check for Existing</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="skipfraudforexisting" {if $CONFIG.SkipFraudForExisting eq "on"}checked{/if}> Tick this box to skip the fraud check for existing clients who already have an active order</label></td></tr>
                                <tr><td class="fieldlabel">Only Auto Provision for Existing</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="autoprovisionexistingonly" {if $CONFIG.AutoProvisionExistingOnly eq "on"}checked{/if}> Tick this box to always leave orders by new clients pending for manual review (no auto setup/registration)</label></td></tr>
                                <tr><td class="fieldlabel">Enable Random Usernames</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="generaterandomusername" {if $CONFIG.GenerateRandomUsername eq "on"}checked{/if}> Tick this box to generate random usernames for services rather than use the first 8 letters of the domain</label></td></tr>
                                <tr><td class="fieldlabel">Signup Anniversary Prorata</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="prorataclientsanniversarydate" {if $CONFIG.ProrataClientsAnniversaryDate eq "on"}checked{/if}> Prorata products to the clients signup anniversary date if prorata is enabled (ie. all items due on the same date per client)</label></td></tr>
                            </tbody></table>


                    </div>
                    <!-- Domains -->
                    <div role="tabpanel" id="tab3" class="tab-pane">


                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Domain Registration Options</td><td class="fieldarea">
                                        <label><input type="checkbox" class="flat-red" name="allowregister" checked=""> Allow clients to register domains with you</label><br>
                                        <label><input type="checkbox" class="flat-red" name="allowtransfer" checked=""> Allow clients to transfer a domain to you</label><br>
                                        <label><input type="checkbox" class="flat-red" name="allowowndomain" checked=""> Allow clients to use their own domain</label>
                                    </td></tr>
                                <tr><td class="fieldlabel">Enable Renewal Orders</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="enabledomainrenewalorders" checked=""> Tick this box to show the Domain Renewals cart category allowing clients to place renewal orders early if they wish</label></td></tr>
                                <tr><td class="fieldlabel">Auto Renew on Payment</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="autorenewdomainsonpayment" checked=""> Automatically renew domains which are set to a supported registrar when they are paid for</label></td></tr>
                                <tr><td class="fieldlabel">Auto Renew Requires Product</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="freedomainautorenewrequiresproduct" checked=""> Only auto renew free domains that have a corresponding active product/service for the same domain</label></td></tr>
                                <tr><td class="fieldlabel">Default Auto Renewal Setting</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domainautorenewdefault" checked=""> This can be changed per domain, but sets the default of whether invoices should auto generate for expiring domains</label></td></tr>
                                <tr><td class="fieldlabel">Create To-Do List Entries</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domaintodolistentries" checked=""> Tick this box to create To-Do list entries for new or failed domain actions that require manual intervention</label></td></tr>
                                <tr><td class="fieldlabel">Domain Sync Enabled</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domainsyncenabled" checked=""> Tick this box to enable automated domain syncing with supported registrars via cron</label></td></tr>
                                <tr><td class="fieldlabel">Sync Next Due Date</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domainsyncnextduedate"> Enable - Number of Days to Set Due Date in Advance of Expiry:</label> <input type="text" class="form-control" name="domainsyncnextduedatedays" size="5" value="0"></td></tr>
                                <tr><td class="fieldlabel">Domain Sync Notify Only</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domainsyncnotifyonly"> Tick this box to not auto update any domain dates - just send email notification to admins</label></td></tr>
                                <tr><td class="fieldlabel">Allow IDN Domains</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="allowidndomains"> Tick this box to not enforce A-Z 0-9 character validation for domains entered via the client area</label></td></tr>
                                <tr><td class="fieldlabel">Bulk Domain Search</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="bulkdomainsearchenabled" checked=""> Tick to enable for domain searches through the order form</label></td></tr>
                                <tr><td class="fieldlabel">Bulk Check TLDs</td><td class="fieldarea"><select class="form-control"name="bulkchecktlds[]" size="6" multiple=""></select><br>Select the TLDs here that you want to check in addition to the TLD the client selects when placing an order<br>(NOTE: The more you select, the longer the order form check will take to complete)</td></tr>
                                <tr><td class="fieldlabel">Default Nameserver 1</td><td class="fieldarea"><input type="text" class="form-control" name="ns1" size="40" value="ns1.yourdomain.com"></td></tr>
                                <tr><td class="fieldlabel">Default Nameserver 2</td><td class="fieldarea"><input type="text" class="form-control" name="ns2" size="40" value="ns2.yourdomain.com"></td></tr>
                                <tr><td class="fieldlabel">Default Nameserver 3</td><td class="fieldarea"><input type="text" class="form-control" name="ns3" size="40" value=""></td></tr>
                                <tr><td class="fieldlabel">Default Nameserver 4</td><td class="fieldarea"><input type="text" class="form-control" name="ns4" size="40" value=""></td></tr>
                                <tr><td class="fieldlabel">Default Nameserver 5</td><td class="fieldarea"><input type="text" class="form-control" name="ns5" size="40" value=""></td></tr>
                                <tr><td class="fieldlabel">Use Clients Details</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="domuseclientsdetails" checked=""> Tick this box to use clients details for the Billing/Admin/Tech contacts</label></td></tr>
                                <tr><td class="fieldlabel">First Name</td><td class="fieldarea"><input type="text" class="form-control" name="domfirstname" size="30" value=""> Default Billing/Admin/Tech Contact Details</td></tr>
                                <tr><td class="fieldlabel">Last Name</td><td class="fieldarea"><input type="text" class="form-control" name="domlastname" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Company Name</td><td class="fieldarea"><input type="text" class="form-control" name="domcompanyname" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Email Address</td><td class="fieldarea"><input type="text" class="form-control" name="domemail" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Address 1</td><td class="fieldarea"><input type="text" class="form-control" name="domaddress1" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Address 2</td><td class="fieldarea"><input type="text" class="form-control" name="domaddress2" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">City</td><td class="fieldarea"><input type="text" class="form-control" name="domcity" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Region</td><td class="fieldarea"><input type="text" class="form-control" name="domstate" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Postcode</td><td class="fieldarea"><input type="text" class="form-control" name="dompostcode" size="30" value=""></td></tr>
                                <tr><td class="fieldlabel">Country</td><td class="fieldarea"><select class="form-control"class="form-control" name="domcountry" id="domcountry"><option value="AF">Afghanistan</option><option value="AX">Aland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua And Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia And Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN" selected="selected">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, Democratic Republic</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Cote D'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curacao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island &amp; Mcdonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic Of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle Of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KR">Korea</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States Of</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territory, Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="BL">Saint Barthelemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts And Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin</option><option value="PM">Saint Pierre And Miquelon</option><option value="VC">Saint Vincent And Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome And Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia And Sandwich Isl.</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard And Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad And Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks And Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.S.</option><option value="WF">Wallis And Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select></td></tr>
                                <tr><td class="fieldlabel">Phone Number</td><td class="fieldarea"><input type="text" class="form-control" name="domphone" size="30" value=""></td></tr>
                            </tbody></table>

                    </div>
                    <!-- Mail -->
                    <div role="tabpanel" id="tab4" class="tab-pane">


                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Mail Type</td><td class="fieldarea">
                                        <select class="form-control"name="mailtype">
                                            <option value="mail" {if $CONFIG['MailType'] eq "mail"}selected{/if}>PHP Mail()</option>
                                            <option value="smtp" {if $CONFIG['MailType'] eq "smtp"}selected{/if}>SMTP</option>
                                        </select></td></tr>
                                <tr><td class="fieldlabel">SMTP Port</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="smtpport" size="5" value="{$CONFIG.SMTPPort}"> The port your mail server uses</td></tr>
                                <tr><td class="fieldlabel">SMTP Host</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="smtphost" size="40" value="{$CONFIG.smtphost}"></td></tr>
                                <tr><td class="fieldlabel">SMTP Username</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="smtpusername" size="35" value="{$CONFIG.SMTPUsername}"></td></tr>
                                <tr><td class="fieldlabel">SMTP Password</td>
                                    <td class="fieldarea"><input type="password" name="smtppassword" size="20" value="{$CONFIG.smtppassword}"></td></tr>
                                <tr><td class="fieldlabel">SMTP SSL Type</td>
                                    <td class="fieldarea">
                                        <label><input type="radio" class="flat-red" name="smtpssl" value="" {if $CONFIG.SMTPSSL eq ""}checked{/if}> None</label>
                                        <label><input type="radio" class="flat-red" name="smtpssl" value="ssl" {if $CONFIG.SMTPSSL eq "ssl"}checked{/if}> SSL</label>
                                        <label><input type="radio" class="flat-red" name="smtpssl" value="tls" {if $CONFIG.SMTPSSL eq "tls"}checked{/if}> TLS</label>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Global Email Signature</td><td class="fieldarea"><textarea class="form-control" name="signature" rows="4" cols="60">{$CONFIG.signature}</textarea></td></tr>
                                <tr><td class="fieldlabel">Global Email CSS Styling</td><td class="fieldarea"><textarea class="form-control" name="emailcss" rows="4" cols="100">{$CONFIG.EmailCSS}</textarea></td></tr>
                                <tr><td class="fieldlabel">Global Email Header Content</td><td class="fieldarea"><textarea class="form-control" name="emailglobalheader" rows="5" cols="100">{$CONFIG.EmailGlobalHeader}</textarea><br>Any text you enter here will be prefixed to the top of all email templates sent out by the system. HTML is accepted.</td></tr>
                                <tr><td class="fieldlabel">Global Email Footer Content</td><td class="fieldarea"><textarea class="form-control" name="emailglobalfooter" rows="5" cols="100">{$CONFIG.EmailGlobalFooter}</textarea><br>Any text you enter here will be added to the bottom of all email templates sent out by the system. HTML is accepted.</td></tr>
                                <tr><td class="fieldlabel">System Emails From Name</td><td class="fieldarea"><input type="text" class="form-control" name="systememailsfromname" size="35" value="{$CONFIG.SystemEmailsFromName}"></td></tr>
                                <tr><td class="fieldlabel">System Emails From Email</td><td class="fieldarea"><input type="text" class="form-control" name="systememailsfromemail" size="50" value="{$CONFIG.SystemEmailsFromEmail}"></td></tr>
                                <tr><td class="fieldlabel">BCC Messages</td><td class="fieldarea"><input type="text" class="form-control" name="bccmessages" size="60" value="{$CONFIG.bccmessages}"><br>If you want copies of all emails sent by the system sent to an address of yours enter the address here.  You may enter multiple addresses seperated by a comma (,)</td></tr>
                                <tr><td class="fieldlabel">Presales Form Destination</td><td class="fieldarea">
                                        <select class="form-control"name="contactformdept">
                                            <option value="">Choose a Department - OR - Send to email address below</option>
                                            {$deptarray}
                                        </select></td></tr>
                                <tr><td class="fieldlabel">Presales Contact Form Email</td><td class="fieldarea"><input type="text" class="form-control" name="contactformto" size="35" value="{$CONFIG.ContactFormTo}"></td></tr>
                            </tbody></table>

                    </div>
                    <!-- Support -->
                    <div role="tabpanel" id="tab5" class="tab-pane">


                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr><td class="fieldlabel">Support Module</td><td class="fieldarea">
                                        <select class="form-control"name="supportmodule">
                                            <option value="">ra Built-in System</option>
                                            {$supportarray}
                                        </select>
                                    </td></tr>
                                <tr><td class="fieldlabel">Support Ticket Mask Format</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="ticketmask" value="{$CONFIG.TicketMask}" size="40"><br>Key: %A - Uppercase letter | %a - Lowercase letter | %n - Number | %y - Year | %m - Month | %d - Day | %i - Ticket ID</td></tr>
                                <tr>
                                    <td class="fieldlabel">Ticket Reply List Order</td>
                                    <td class="fieldarea">
                                        <select class="form-control"name="supportticketorder">
                                            <option value="ASC" selected="">Ascending (Oldest to Newest)</option>
                                            <option value="DESC">Descending (Newest to Oldest)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class="fieldlabel">Show Client Only Departments</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="showclientonlydepts" {if $CONFIG.ShowClientOnlyDepts eq "on"}checked{/if}> Tick to show client only departments to guests (not logged in visitors)</td></tr>
                                <tr><td class="fieldlabel">Client Tickets Require Login</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="requireloginforclienttickets" {if $CONFIG.RequireLoginforClientTickets eq "on"}checked{/if}> Require login by the owning client for viewing tickets assigned to a client</td></tr>
                                <tr><td class="fieldlabel">Knowledgebase Suggestions</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="supportticketkbsuggestions" {if $CONFIG.SupportTicketKBSuggestions eq "on"}checked{/if}> Show suggested KB articles to a user as they enter a support ticket message</td></tr>
                                <tr><td class="fieldlabel">Attachment Thumbnail Previews</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="attachmentthumbnails" {if $CONFIG.AttachmentThumbnails eq "on"}checked{/if}> Tick to enable thumbnail previews of image attachments (requires GD)</td></tr>
                                <tr><td class="fieldlabel">Support Ticket Rating</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="ticketratingenabled" {if $CONFIG.TicketRatingEnabled eq "on"}checked{/if}> Allow users to rate support ticket replies from staff</td></tr>
                                <tr><td class="fieldlabel">Ticket Closure Feedback Request</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="ticketfeedback" {if $CONFIG.TicketFeedback eq "on"}checked{/if}> Tick to enable sending of Ticket Feedback Requests upon closure of tickets</td></tr>
                                <tr><td class="fieldlabel">Disable Reply Email Logging</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="disablesupportticketreplyemailslogging" {if $CONFIG.DisableSupportTicketReplyEmailsLogging eq "on"}checked{/if}> Do not create email log entry for ticket replies (text is already logged in ticket so saves disk space)</td></tr>
                                <tr><td class="fieldlabel">KB SEO Friendly URLs</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="seofriendlyurls" {if $CONFIG.SEOFriendlyUrls eq "on"}checked{/if}> Tick to enable SEO friendly urls (Requires renaming the htaccess.txt file to .htaccess in the root directory)</td></tr>
                                <tr><td class="fieldlabel">Allowed File Attachment Types</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="allowedfiletypes" value="{$CONFIG.TicketAllowedFileTypes }" size="50"> Seperate multiple extensions with a comma</td></tr>
                                <tr><td class="fieldlabel">Service Status Require Login</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="networkissuesrequirelogin" {if $CONFIG.NetworkIssuesRequireLogin eq "on"}checked{/if}> Require a login to view the server status &amp; network issues pages</td></tr>
                                <tr><td class="fieldlabel">Include Product Downloads</td><td class="fieldarea">
                                        <input type="checkbox" class="flat-red" name="dlinclproductdl" {if $CONFIG.DownloadsIncludeProductLinked eq "on"}checked{/if}> Tick to include Product Associated Downloads in the Downloads Directory</td></tr>
                            </tbody></table>

                    </div>
                    <!-- Invoices -->
                    <div role="tabpanel" id="tab6" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Continuous Invoice Generation</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="continuousinvoicegeneration" {if $CONFIG.ContinuousInvoiceGeneration eq "on"}checked{/if}> If enabled, invoices will be generated for each cycle even if the previous invoice remains unpaid</label></td></tr>
                                <tr><td class="fieldlabel">Enable PDF Invoices</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="enablepdfinvoices" {if $CONFIG.EnablePDFInvoices eq "on"}checked{/if}> Tick to send PDF versions of invoices along with invoice emails</label></td></tr>
                                <tr><td class="fieldlabel">Enable Mass Payment</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="enablemasspay" {if $CONFIG.EnableMassPay eq "on"}checked{/if}> Tick to enable the multiple invoice payment options on the client area homepage</label></td></tr>
                                <tr><td class="fieldlabel">Clients Choose Gateway</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="allowcustomerchangeinvoicegateway" {if $CONFIG.AllowCustomerChangeInvoiceGateway eq "on"}checked{/if}> Tick to allow clients to choose the gateway they pay with</label></td></tr>
                                <tr><td class="fieldlabel">Group Similar Line Items</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="groupsimilarlineitems" {if $CONFIG.GroupSimilarLineItems eq "on"}checked{/if}> Tick to enable automatically grouping identical line items into a quantity x description format</label></td></tr>
                                <tr><td class="fieldlabel">Disable Auto Credit Applying</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="noautoapplycredit" {if $CONFIG.NoAutoApplyCredit eq "on"}checked{/if}> Tick to disable automatically applying credit from a users available credit balance when generating invoices</label></td></tr>
                                <tr><td class="fieldlabel">Cancellation Request Handling</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="cancelinvoiceoncancel" {if $CONFIG.CancelInvoiceOnCancellation eq "on"}checked{/if}> Tick to automatically cancel outstanding unpaid invoices when a cancellation request is submitted</label></td></tr>
                                <tr><td class="fieldlabel">Sequential Paid Invoice Numbering</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="sequentialinvoicenumbering" {if $CONFIG.SequentialInvoiceNumbering eq "on"}checked{/if}> Tick this box to enable automatic sequential numbering of paid invoices</label></td></tr>
                                <tr><td class="fieldlabel">Sequential Invoice Number Format</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="sequentialinvoicenumberformat" value="{$SequentialInvoiceNumberFormat}" size="25"> Enter the format for the paid invoice numbers eg. RA2007-{NUMBER}</td></tr>
                                <tr><td class="fieldlabel">Next Paid Invoice Number</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="sequentialinvoicenumbervalue" value="{$SequentialInvoiceNumberValue}" size="5"> Only change this if you need to regenerate an invoice number</td></tr>
                                <tr><td class="fieldlabel">Late Fee Type</td><td class="fieldarea"><label>
                                            <input type="radio" class="flat-red" name="latefeetype" value="Percentage" {if $CONFIG.LateFeeType eq "Percentage"}checked{/if}> Percentage</label> <label>
                                            <input type="radio" class="flat-red" name="latefeetype" value="Fixed Amount" {if $CONFIG.fixedamount eq "Fixed Amount"}checked{/if}> Fixed Amount</label></td></tr>
                                <tr><td class="fieldlabel">Late Fee Amount</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="invoicelatefeeamount" value="{$CONFIG.InvoiceLateFeeAmount}" size="8"> Enter the amount (percentage or monetary value) to apply to late invoices (set to 0 to disable)</td></tr>
                                <tr><td class="fieldlabel">Late Fee Minimum</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="latefeeminimum" value="{$CONFIG.LateFeeMinimum}" size="8"> Enter the minimum amount to charge in cases where the calculated late fee falls below this figure</td></tr>
                                <tr><td class="fieldlabel">Accepted Credit Card Types</td><td class="fieldarea"><table cellspacing="0" cellpadding="0"><tbody><tr><td>
                                                        <select class="form-control"name="acceptedcctypes[]" size="5" multiple="">
                                                            <option {if "Visa"|in_array:$acceptedcctypes}selected{/if}>Visa</option>
                                                            <option {if "MasterCard"|in_array:$acceptedcctypes}selected{/if}>MasterCard</option>
                                                            <option {if "Discover"|in_array:$acceptedcctypes}selected{/if}>Discover</option>
                                                            <option {if "American Express"|in_array:$acceptedcctypes}selected{/if}>American Express</option>
                                                            <option {if "JCB"|in_array:$acceptedcctypes}selected{/if}>JCB</option>
                                                            <option {if "EnRoute"|in_array:$acceptedcctypes}selected{/if}>EnRoute</option>
                                                            <option {if "Diners Club"|in_array:$acceptedcctypes}selected{/if}>Diners Club</option>
                                                            <option {if "Solo"|in_array:$acceptedcctypes}selected{/if}>Solo</option>
                                                            <option {if "Switch"|in_array:$acceptedcctypes}selected{/if}>Switch</option>
                                                            <option {if "Maestro"|in_array:$acceptedcctypes}selected{/if}>Maestro</option>
                                                            <option {if "Visa Debit"|in_array:$acceptedcctypes}selected{/if}>Visa Debit</option>
                                                            <option {if "Visa Electron"|in_array:$acceptedcctypes}selected{/if}>Visa Electron</option>
                                                            <option {if "Laser"|in_array:$acceptedcctypes}selected{/if}>Laser</option>
                                                        </select></td><td style="padding-left:15px;">Use Ctrl+Click to select Multiple Card Types</td></tr></tbody></table></td></tr>
                                <tr><td class="fieldlabel">Issue Number/Start Date</td><td class="fieldarea"><label>
                                            <input type="checkbox" class="flat-red" name="showccissuestart"> Tick to show these fields for credit card payments</label></td></tr>
                                <tr><td class="fieldlabel">TCPDF Font Family</td><td class="fieldarea"><label>
                                            <input type="radio" class="flat-red" name="tcpdffont" value="helvetica" checked=""> Helvetica </label><label><input type="radio" class="flat-red" name="tcpdffont" value="freesans"> Freesans </label><label> <input type="radio" class="flat-red" name="tcpdffont" value="custom"> Custom</label> <input type="text" class="form-control" name="tcpdffontcustom" size="15" value=""></td></tr>
                                <tr><td class="fieldlabel">Invoice # Incrementation</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="invoiceincrement" value="1" size="5"> Enter the difference you want between invoice numbers generated by the system (Default: 1)</td></tr>
                                <tr><td class="fieldlabel">Invoice Starting #</td><td class="fieldarea">
                                        <input type="text" class="form-control" name="invoicestartnumber" value="" size="10"> Enter to set the next invoice number, must be greater than last #152031 (Blank for no change)</td></tr>
                            </tbody></table>
                    </div>
                    <div role="tabpanel" id="tab12" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel">GST Number:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="gst" value="{$CONFIG.gst}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Phone Number:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invphone" value="{$CONFIG.invphone}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Fax Number:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invfax" value="{$CONFIG.invfax}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Website:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invwebsite" value="{$CONFIG.invwebsite}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Email:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invemail" value="{$CONFIG.invemail}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Bank Account:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invaccount" value="{$CONFIG.invaccount}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Bank Name:</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="invname" value="{$CONFIG.invname}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Company Name:</td>
                                    <td class="fieldarea"><input class="form-control" name="invcompany" value="{$CONFIG.invcompany}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">PO Box:</td>
                                    <td class="fieldarea"><input class="form-control" name="invpoxbox" value="{$CONFIG.invpoxbox}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">City:</td>
                                    <td class="fieldarea"><input class="form-control" name="invcity" value="{$CONFIG.invcity}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Post Code:</td>
                                    <td class="fieldarea"><input class="form-control" name="invpostcode" value="{$CONFIG.invpostcode}" size="35"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Country:</td>
                                    <td class="fieldarea"><input class="form-control" name="invcountry" value="{$CONFIG.invcountry}" size="35"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- Credit -->
                    <div role="tabpanel" id="tab7" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Enable/Disable</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="addfundsenabled" {if $CONFIG.AddFundsEnabled eq 'on'}checked{/if}> Tick this box to enable adding of funds by clients from the client area</label></td></tr>
                                <tr><td class="fieldlabel">Minimum Deposit</td><td class="fieldarea"><input type="text" class="form-control" name="addfundsminimum" size="10" value="10.00"> Enter the minimum amount a client can add in a single transaction</td></tr>
                                <tr><td class="fieldlabel">Maximum Deposit</td><td class="fieldarea"><input type="text" class="form-control" name="addfundsmaximum" size="10" value="100.00"> Enter the maximum amount a client can add in a single transaction</td></tr>
                                <tr><td class="fieldlabel">Maximum Balance</td><td class="fieldarea"><input type="text" class="form-control" name="addfundsmaximumbalance" size="10" value="300.00"> Enter the maximum balance that a client can add in credit</td></tr>
                                <tr><td class="fieldlabel">Require Active Order</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="addfundsrequireorder" checked=""> Require an active order before allowing Add Funds use (used to protect against fraud, means an admin must have manually reviewed the client &amp; approved an order before allowing credit to be added)</label></td></tr>
                            </tbody></table>
                    </div>
                    <!-- Affiliates -->
                    <div role="tabpanel" id="tab8" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Enable/Disable</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="affiliateenabled"> Tick this box to enable the affiliate system</label></td></tr>
                                <tr><td class="fieldlabel">Affiliate Earning Percentage</td><td class="fieldarea"><input type="text" class="form-control" name="affiliateearningpercent" size="10" value="0"> Enter the percentage of each payment you want affiliates to receive</td></tr>
                                <tr><td class="fieldlabel">Affiliate Bonus Deposit</td><td class="fieldarea"><input type="text" class="form-control" name="affiliatebonusdeposit" size="10" value="0.00"> Enter the amount you want affiliates to receive in their account after signing up</td></tr>
                                <tr><td class="fieldlabel">Affiliate Payout Amount</td><td class="fieldarea"><input type="text" class="form-control" name="affiliatepayout" size="10" value="0.00"> Enter the minimum amount affiliates have to reach before making a withdrawal</td></tr>
                                <tr><td class="fieldlabel">Affiliate Commission Delay</td><td class="fieldarea"><input type="text" class="form-control" name="affiliatesdelaycommission" size="10" value="0"> Enter the number of days to delay commission payments - then only pays if account is still active</td></tr>
                                <tr><td class="fieldlabel">Payout Request Department</td><td class="fieldarea"><select class="form-control"name="affiliatedepartment"><option value="1" selected="">Provisioning</option></select> Select the support department to use for affiliate withdrawal requests</td></tr>
                                <tr><td class="fieldlabel">Affiliate Links</td><td class="fieldarea"><textarea class="form-control" name="affiliatelinks" rows="10" style="width:100%"></textarea class="form-control"><br>Enter [AffiliateLinkCode] where the affiliate's customised link code should be inserted<br>Use <b>&lt;(</b> for open brackets and <b>)&gt;</b> for close brackets in HTML or else the HTML will be executed on the page</td></tr>
                            </tbody></table>
                    </div>
                    <!-- Security -->
                    <div role="tabpanel" id="tab9" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Captcha Form Protection</td><td class="fieldarea"><label><input type="radio" class="flat-red" name="captchasetting" value="on"> Always On (code shown to ensure human submission)</label><br><label><input type="radio" class="flat-red" name="captchasetting" value="offloggedin" checked=""> Off when logged in</label><br><label><input type="radio" class="flat-red" name="captchasetting" value=""> Always Off</label></td></tr>
                                <tr><td class="fieldlabel">Captcha Type</td><td class="fieldarea"><label><input type="radio" class="flat-red" name="captchatype" value="" onclick="$('.recaptchasetts').hide();"> Default (5 Character Verification Code)</label><br><label><input type="radio" class="flat-red" name="captchatype" value="recaptcha" onclick="$('.recaptchasetts').show();" checked=""> reCAPTCHA (<a href="http://www.google.com/recaptcha" target="_blank">Google's reCAPTCHA system</a>)</label></td></tr>
                                <tr class="recaptchasetts"><td class="fieldlabel">reCAPTCHA Private Key</td><td class="fieldarea"><input type="text" class="form-control" name="recaptchaprivatekey" size="25" value="6LdYrSMTAAAAAN_xfTd3B6odMsiVxsXCpcWEtr6C"> You need to register for reCAPTCHA @ <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a></td></tr>
                                <tr class="recaptchasetts"><td class="fieldlabel">reCAPTCHA Public Key</td><td class="fieldarea"><input type="text" class="form-control" name="recaptchapublickey" size="25" value="6LdYrSMTAAAAAKabOMjEY4Y2e9wIgJNdFY_ed9Yo"></td></tr>
                                <tr><td class="fieldlabel">Required Password Strength</td><td class="fieldarea"><input type="text" class="form-control" name="requiredpwstrength" size="5" value="50"> Enter the required password strength from 1 to 100 - Enter 0 to Disable</td></tr>
                                <tr><td class="fieldlabel">Failed Admin Login Ban Time</td><td class="fieldarea"><input type="text" class="form-control" name="invalidloginsbanlength" value="15" size="5"> Enter the time in minutes for the ban after exceeding 3 invalid login attempts</td></tr>
                                <tr><td class="fieldlabel">Whitelisted IPs</td><td class="fieldarea"><select class="form-control"name="whitelistedips[]" id="whitelistedips" size="3" style="min-width:200px;" multiple=""><option value=""> - </option><option value=""> - </option><option value=""> - </option><option value=""> - </option></select> IP Addresses exempt from being banned for invalid login attempts<br><a href="javascript:;" onclick="showDialog('addwhitelistip')"><img src="images/icons/add.png" align="absmiddle" border="0"> Add IP</a> <a href="#" id="removewhitelistedip"><img src="images/icons/delete.png" align="absmiddle" border="0"> Remove Selected</a></td></tr>
                                <tr><td class="fieldlabel">Admin Force SSL Access</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="adminforcessl" checked=""> Tick this box to force SSL Access for all admin area requests</label></td></tr>
                                <tr><td class="fieldlabel">Disable Admin Password Reset</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="disableadminpwreset"> Tick this box to disable the forgotten password feature on the admin login page</label></td></tr>
                                <tr><td class="fieldlabel">Disable Credit Card Storage</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="ccneverstore"> Tick this box to not store customers credit cards in the database (Warning: This will delete any existing stored credit card data)</label></td></tr>
                                <tr><td class="fieldlabel">Allow Client CC Removal</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="ccallowcustomerdelete"> Tick this box to allow customers to delete the credit card details stored on their account</label></td></tr>
                                <tr><td class="fieldlabel">Disable Session IP Check</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="disablesessionipcheck" checked=""> This is used to protect against cookie/session hijacking but can cause problems for users with dynamic IPs</label></td></tr>
                                <tr><td class="fieldlabel">API IP Access Restriction</td><td class="fieldarea"><select class="form-control"name="apiallowedips[]" id="apiallowedips" size="3" style="min-width:200px;" multiple=""><option value=""> - </option></select> IP Addresses allowed to connect to the ra API<br><a href="javascript:;" onclick="showDialog('addapiip')"><img src="images/icons/add.png" align="absmiddle" border="0"> Add IP</a> <a href="#" id="removeapiip"><img src="images/icons/delete.png" align="absmiddle" border="0"> Remove Selected</a></td></tr>
                                <tr><td class="fieldlabel">ra.default</td><td class="fieldarea"><span>Tick to enable general use of CSRF tokens for all public and clientarea forms (Highly Recommended)</span><br><label><input type="radio" class="flat-red" name="csrftoken_ns_ra_ns_default" value="on" onclick="$('.csrftoken').show();"> Enabled</label><br><label><input type="radio" class="flat-red" name="csrftoken_ns_ra_ns_default" value="off" onclick="$('.csrftoken').hide();" checked=""> Disabled</label></td></tr>
                                <tr class="csrftoken" style="display:none"><td class="fieldlabel">CSRF Tokens: General</td><td class="fieldarea"><span>RA.default</span><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_default" value="on" checked=""> Enabled (Default)</label><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_default" value="off"> Disabled</label></td></tr>
                                <tr class="csrftoken" style="display:none"><td class="fieldlabel">RA.admin.default</td><td class="fieldarea"><span>RA.admin.default</span><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_admin_ns_default" value="on" checked=""> Enabled (Default)</label><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_admin_ns_default" value="off"> Disabled</label></td></tr>
                                <tr class="csrftoken" style="display:none"><td class="fieldlabel">RA.domainchecker</td><td class="fieldarea"><span>RA.domainchecker</span><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_domainchecker" value="on"> Enabled</label><br><label><input type="radio" class="flat-red" name="csrftoken_ns_RA_ns_domainchecker" value="off" checked=""> Disabled (Default)</label></td></tr>
                            </tbody></table>

                    </div>
                    <!-- Social -->
                    <div role="tabpanel" id="tab10" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Twitter Username</td><td class="fieldarea"><input type="text" class="form-control" name="twitterusername" size="20" value=""> Enter your Twitter Username here to Enable Integration</td></tr>
                                <tr><td class="fieldlabel">Announcements Tweet</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="announcementstweet"> Enable Tweet Button on Announcements</label></td></tr>
                                <tr><td class="fieldlabel">Facebook Recommend</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="announcementsfbrecommend"> Enable Facebook Recommend/Send on Announcements</label></td></tr>
                                <tr><td class="fieldlabel">Facebook Comments</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="announcementsfbcomments"> Enable Facebook Comments on Announcements</label></td></tr>
                                <tr><td class="fieldlabel">Google +1</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="googleplus1"> Enable Recommend &amp; Share with Google+</label></td></tr>
                            </tbody></table>

                    </div>
                    <!-- Other -->
                    <div role="tabpanel" id="tab11" class="tab-pane">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody><tr><td class="fieldlabel">Admin Client Display Format</td><td class="fieldarea"><label><input type="radio" class="flat-red" name="clientdisplayformat" value="1" checked=""> Show first name/last name only</label><br><label><input type="radio" class="flat-red" name="clientdisplayformat" value="2"> Show company name if set, otherwise first name/last name</label><br><label><input type="radio" class="flat-red" name="clientdisplayformat" value="3"> Show full name &amp; company if set</label></td></tr>
                                <tr><td class="fieldlabel">Client Dropdown Format</td><td class="fieldarea"><label><input type="radio" class="flat-red" name="clientdropdownformat" value="1" checked=""> [First Name] [Last Name] ([Company Name])</label><br><label><input type="radio" class="flat-red" name="clientdropdownformat" value="2"> [Company Name] - [First Name] [Last Name]</label><br><label><input type="radio" class="flat-red" name="clientdropdownformat" value="3"> #[Client ID] - [First Name] [Last Name] - [Company Name]</label></td></tr>
                                <tr><td class="fieldlabel">Tick this box to disable the list of clients at the top of the profile pages (recommended for performance on large databases)</td></tr>
                                <tr><td class="fieldlabel">Default to Client Area</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="defaulttoclientarea"> Tick this box to skip the homepage and forward users directly to the client area/login form upon first visiting ra</label></td></tr>
                                <tr><td class="fieldlabel">Allow Client Registration</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="allowclientregister" checked=""> Tick this box to allow registration without ordering any products/services</label></td></tr>
                                <tr><td class="fieldlabel">Optional Client Profile Fields</td><td class="fieldarea">Tick any of the fields below to make them optional at signup time:<br>
                                        <table width="100%"><tbody><tr>
                                                    <td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="firstname"> First Name</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="lastname"> Last Name</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="address1"> Address 1</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="city"> City</label></td></tr><tr><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="state"> Region</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="postcode"> Postcode</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofoptional[]" value="phonenumber"> Phone Number</label></td></tr></tbody></table></td></tr>
                                <tr><td class="fieldlabel">Locked Client Profile Fields</td><td class="fieldarea">Select any fields below that you want to prevent clients being able to edit from the client area:<br>
                                        <table width="100%"><tbody><tr>
                                                    <td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="firstname"> First Name</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="lastname"> Last Name</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="companyname"> Company Name</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="email"> Email Address</label></td></tr><tr><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="address1"> Address 1</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="address2"> Address 2</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="city"> City</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="state"> Region</label></td></tr><tr><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="postcode"> Postcode</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="country"> Country</label></td><td width="25%"><label><input type="checkbox" class="flat-red" name="clientsprofuneditable[]" value="phonenumber"> Phone Number</label></td></tr></tbody></table></td></tr>
                                <tr><td class="fieldlabel">Client Details Change Notify</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="sendemailnotificationonuserdetailschange" checked=""> Tick this box to send an email notification to admins on user details change</label></td></tr>
                                <tr><td class="fieldlabel">Marketing Opt-out</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="allowclientsemailoptout"> Tick to show newsletter opt-out option in client area</label></td></tr>
                                <tr><td class="fieldlabel">Show Cancellation Link</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="showcancel" checked=""> Tick this box to show the cancellation request option in the client area for products</label></td></tr>
                                <tr><td class="fieldlabel">Credit On Downgrade</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="creditondowngrade" checked=""> Tick this box to provide a prorata refund to clients when downgrading for unused time</label></td></tr>
                                <tr><td class="fieldlabel">Monthly Affiliate Reports</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="affreport" checked=""> Tick this box to send Monthly Referrals Reports to Affiliates on the 1st of each month</label></td></tr>
                                <tr><td class="fieldlabel">Banned Subdomain Prefixes</td><td class="fieldarea"><textarea class="form-control" name="bannedsubdomainprefixes" cols="100" rows="2">mail,mx,gapps,gmail,webmail,cpanel,whm,ftp,clients,billing,members,login,accounts,access</textarea class="form-control"></td></tr>
                                <tr><td class="fieldlabel">Display Errors</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="displayerrors" checked=""> Tick to enable PHP Error Reporting (Do not leave enabled in live use)</label></td></tr>
                                <tr><td class="fieldlabel">SQL Debug Mode</td><td class="fieldarea"><label><input type="checkbox" class="flat-red" name="sqlerrorreporting"> Tick to enable logging of SQL Errors (Enable only if instructed)</label></td></tr>

                            </tbody></table>
                    </div>
                </div>
            </div>
            <p align="center"><input type="submit" value="Save Changes" class="button"></p>

            <input type="hidden" name="tab" id="tab" value="">

            {literal}
                <script type="text/javascript">
                    function showDialog(name) {
                        $("#" + name).dialog('open');
                    }


                </script>
            {/literal}
        </div>

    </div>
</form>