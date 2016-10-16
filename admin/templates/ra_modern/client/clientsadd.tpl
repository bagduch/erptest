{debug}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Add New Client
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Client</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <form method="post" action="{$formurl}?action=add">
                    <div class="col-md-6">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td width="20%" class="fieldlabel">First Name</td><td class="fieldarea"><input class="form-control" type="text" size="30" name="firstname" value="" tabindex="1"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Last Name</td><td class="fieldarea"><input class="form-control" type="text" size="30" name="lastname" value="" tabindex="2"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Company Name <font color="#cccccc"><small>(Optional)</small></font></td><td class="fieldarea"><input class="form-control" type="text" size="30" name="companyname" value="" tabindex="3"> </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Email Address</td><td class="fieldarea"><input class="form-control" type="text" size="35" name="email" value="" tabindex="4"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Password</td><td class="fieldarea"><input class="form-control" type="text" size="20" name="password" value="" tabindex="5"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Security Question</td><td class="fieldarea">
                                        <select name="securityqid"  class="form-control"  style="width:225px;" tabindex="6">
                                            <option value="" selected="">None</option>
                                            {$securityoption}
                                        </select>
                                    </td>
                                <tr>
                                    <td class="fieldlabel">Security Answer</td><td class="fieldarea"><input class="form-control" type="text" name="securityqans" size="40" value="" tabindex="7"></td>
                                </tr>

                                <tr>
                                    <td class="fieldlabel">Late Fees</td>
                                    <td class="fieldarea">
                                        <label class="checkbox-padding">
                                            <input class="flat-red" type="checkbox" name="latefeeoveride" tabindex="15"> Don't Apply Late Fees
                                        </label>
                                    </td>
                                <tr>
                                    <td class="fieldlabel">Overdue Notices</td>
                                    <td class="fieldarea">
                                        <label class="checkbox-padding">
                                            <input class="flat-red" type="checkbox" name="overideduenotices" tabindex="16"> Don't Send Overdue Emails
                                        </label>
                                    </td>
                                <tr>
                                    <td class="fieldlabel">Tax Exempt</td><td class="fieldarea">
                                        <label class="checkbox-padding">
                                            <input class="flat-red" type="checkbox" name="taxexempt" tabindex="17"> Don't Apply Tax to Invoices
                                        </label>
                                    </td>
                                <tr>
                                    <td class="fieldlabel">Separate Invoices</td><td class="fieldarea">
                                        <label class="checkbox-padding">
                                            <input class="flat-red" type="checkbox" name="separateinvoices" tabindex="18"> Separate Invoices for Services
                                        </label>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">Disable CC Processing</td><td class="fieldarea">
                                        <label class="checkbox-padding">
                                            <input class="flat-red" type="checkbox" name="disableautocc" tabindex="19"> Disable Automatic CC Processing
                                        </label>
                                    </td>
                                <tr>
                                    <td class="fieldlabel">Credit Balance</td><td class="fieldarea">

                                        <input class="form-control" type="text" size="10" name="credit" value="" tabindex="25"></td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>                       
                                    <td class="fieldlabel" width="15%">Address 1</td><td class="fieldarea"><input class="form-control" type="text" size="30" name="address1" value="" tabindex="8"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Address 2 <font color="#cccccc"><small>(Optional)</small></font></td><td class="fieldarea"><input class="form-control" type="text" size="30" name="address2" value="" tabindex="9"> </td>

                                </tr>
                                <tr>
                                    <td class="fieldlabel">City</td><td class="fieldarea"><input class="form-control" type="text" size="25" name="city" value="" tabindex="10"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Region</td><td class="fieldarea"><input class="form-control" type="text" size="25" name="state" value="" tabindex="11"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Postcode</td><td class="fieldarea"><input class="form-control" type="text" size="14" name="postcode" value="" tabindex="12"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Country</td><td class="fieldarea">
                                        {$countrydrop}
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Phone Number</td><td class="fieldarea"><input class="form-control" type="text" size="20" name="phonenumber" value="" tabindex="14"></td></tr>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Payment Method</td><td class="fieldarea">
                                        {$paymentmethoddrop}
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Billing Contact</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="billingcid" tabindex="21">
                                            <option value="">Default</option>
                                            {$contactoption}
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Language</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="language" tabindex="22">
                                            <option value="">Default</option>
                                            {$langoption}
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Status</td><td class="fieldarea"><select class="form-control" name="status" tabindex="23">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Closed">Closed</option>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Currency</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="currency" tabindex="24">
                                            {$currencyoption}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel">Client Group</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="groupid" tabindex="26"><option value="0">None</option>
                                            {$groupoption}
                                        </select>
                                    </td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="textarea" placeholder="Place some text here" name="notes" rows="4" style="width:100%;" tabindex="27"></textarea>
                        </div>

                        <label><input type="checkbox" name="sendemail" checked="" tabindex="28"> Tick this box to send a New Account Information Message</label>
                        <br><br>

                        <div align="center"><input class="btn btn-success" type="submit" value="Add Client" tabindex="29"></div>
                    </div>

                </form>
            </div>
        </div>
    </section>

</div>

<script type="text/javascript">
    {literal}
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        $(".textarea").wysihtml5();
    {/literal}
</script>