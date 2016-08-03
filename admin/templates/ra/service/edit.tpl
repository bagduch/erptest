<div style="float:left;width:100%;">
    <h1>Services</h1>
    <script type="text/javascript" src="../includes/jscript/jquerylq.js"></script>
    <script type="text/javascript" src="../includes/jscript/jqueryFileTree.js"></script>
    <link href="../includes/jscript/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen">

    <h2>Edit Product</h2>
    <form method="post" action="/admin/configservices.php?action=save&amp;id=3" name="packagefrm">
        <input type="hidden" name="token" value="a77ffd97d8a752f1165226d85ae274ae6f19f719"><div id="tabs"><ul><li id="tab0" class="tab tabselected"><a href="javascript:;">Details</a></li><li id="tab1" class="tab"><a href="javascript:;">Pricing</a></li><li id="tab2" class="tab"><a href="javascript:;">Module Settings</a></li><li id="tab3" class="tab"><a href="javascript:;">Configurable Options</a></li><li id="tab4" class="tab"><a href="javascript:;">Other</a></li></ul></div>

        <div id="tab0box" class="tabbox">
            <div id="tab_content">
                <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><tr><td class="fieldlabel">Product Type</td><td class="fieldarea"><select name="type" onchange="doFieldUpdate()"><option value="hostingaccount" selected="">Hosting Account</option><option value="reselleraccount">Reseller Account</option><option value="server">Dedicated/VPS Server</option><option value="other">Other</option></select></td></tr>
                        <tr><td class="fieldlabel">Service Group</td><td class="fieldarea"><select name="gid"><option value="1" selected="">Broadband</option></select></td></tr>
                        <tr><td class="fieldlabel"></td><td class="fieldarea"><input type="text" size="40" name="name" value="ADSL2"></td></tr>
                        <tr><td class="fieldlabel">Service Description</td><td class="fieldarea"><table cellsapcing="0" cellpadding="0"><tbody><tr><td><textarea name="description" cols="60" rows="5"></textarea></td><td>You may use HTML in this field<br>&lt;br /&gt; New line<br>&lt;strong&gt;Bold&lt;/strong&gt; <b>Bold</b><br>&lt;em&gt;Italics&lt;/em&gt; <i>Italics</i></td></tr></tbody></table></td></tr>
<tr><td class="fieldlabel">Welcome Email</td><td class="fieldarea"><select name="welcomeemail"><option value="0">None</option><option value="1">Hosting Account Welcome Email</option><option value="4">Reseller Account Welcome Email</option><option value="17">Dedicated/VPS Server Welcome Email</option><option value="18">Other Product/Service Welcome Email</option></select></td></tr>
<tr><td class="fieldlabel">Apply Tax</td><td class="fieldarea"><label><input type="checkbox" name="tax"> Tick this box to charge tax for this Service</label></td></tr>
<tr><td class="fieldlabel">Hidden</td><td class="fieldarea"><label><input type="checkbox" name="hidden"> Tick to hide from order form</label></td></tr>
<tr><td class="fieldlabel">Retired</td><td class="fieldarea"><label><input type="checkbox" name="retired" value="1"> Tick to hide from admin area service dropdown menus (does not apply to services already with this service)</label></td></tr>
            <tr><td class="fieldlabel">Contract</td><td class="fieldarea"><label><input type="checkbox" name="contract" value="1">Tick if this service is contract</label></td></tr>
 <tr><td></td><td class="fieldarea"><input type="text" name="etf"><label>ETF</label></td></tr>
</tbody></table>

  </div>
</div>
<div id="tab1box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td class="fieldlabel">Payment Type</td><td class="fieldarea"><label><input type="radio" name="paytype" value="free"> Free</label> <label><input type="radio" name="paytype" value="onetime"> One Time</label> <label><input type="radio" name="paytype" value="recurring" checked=""> Recurring</label></td></tr>
<tr><td colspan="2" align="center"><br>
<table cellspacing="1" bgcolor="#cccccc">
<tbody><tr bgcolor="#efefef" style="text-align:center;font-weight:bold"><td width="80">Currency</td><td width="80"></td><td width="120">One Time/Monthly</td><td width="90">Quarterly</td><td width="100">Semi-Annually</td><td width="90">Annually</td><td width="90">Biennially</td><td width="90">Triennially</td></tr>
<tr bgcolor="#ffffff" style="text-align:center"><td rowspan="2" bgcolor="#efefef"><b>NZD</b></td><td>Setup Fee</td><td><input type="text" name="currency[1][msetupfee]" size="10" value="49.00"></td><td><input type="text" name="currency[1][qsetupfee]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][ssetupfee]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][asetupfee]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][bsetupfee]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][tsetupfee]" size="10" value="-1.00"></td></tr><tr bgcolor="#ffffff" style="text-align:center"><td>Price</td><td><input type="text" name="currency[1][monthly]" size="10" value="69.00"></td><td><input type="text" name="currency[1][quarterly]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][semiannually]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][annually]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][biennially]" size="10" value="-1.00"></td><td><input type="text" name="currency[1][triennially]" size="10" value="-1.00"></td></tr></tbody></table><br>
(Set Price to -1.00 to disable any of the payment term options - leave Setup Fee at zero)<br><br>
</td></tr>
<tr><td class="fieldlabel">Allow Multiple Quantities</td><td class="fieldarea"><input type="checkbox" name="allowqty" value="1"> Tick this box to allow customers to specify if they want more than 1 of this item when ordering (must not require separate config)</td></tr>
<tr><td class="fieldlabel">Recurring Cycles Limit</td><td class="fieldarea"><input type="text" name="recurringcycles" value="0" size="7"> To limit this service to only recur a fixed number of times, enter the total number of times to invoice (0 = Unlimited)</td></tr>
<tr><td class="fieldlabel">Auto Terminate/Fixed Term</td><td class="fieldarea"><input type="text" name="autoterminatedays" value="0" size="7"> Enter the number of days after activation to automatically terminate (eg. free trials, time limited services, etc...)</td></tr>
<tr><td class="fieldlabel">Termination Email</td><td class="fieldarea"><select name="autoterminateemail"><option value="0">None</option><option value="1">Hosting Account Welcome Email</option><option value="4">Reseller Account Welcome Email</option><option value="10">Service Suspension Notification</option><option value="17">Dedicated/VPS Server Welcome Email</option><option value="18">Other Product/Service Welcome Email</option><option value="25">SHOUTcast Welcome Email</option><option value="35">Cancellation Request Confirmation</option></select> Choose the email template to send when the fixed term comes to an end</td></tr>
</tbody></table>

  </div>
</div>
<div id="tab2box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td class="fieldlabel" width="150">Module Name</td><td class="fieldarea"><select name="servertype" onchange="submit()"><option value="">None</option></select></td></tr>
</tbody></table>

<br>


  </div>
</div>

<div id="tab3box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td width="150" class="fieldlabel">Assigned Option Groups</td><td class="fieldarea"><select name="configoptionlinks[]" size="8" style="width:90%" multiple="">
</select></td></tr>
</tbody></table>

  </div>
</div>

<div id="tab4box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td class="fieldlabel">Free Domain</td><td class="fieldarea"><input type="radio" name="freedomain" value="" checked=""> None<br><input type="radio" name="freedomain" value="once"> Offer a free domain registration/transfer only (renew as normal)<br><input type="radio" name="freedomain" value="on"> Offer a free domain registration/transfer and free renewal (if service is renewed)</td></tr>
<tr><td class="fieldlabel">Free Domain Payment Terms</td><td class="fieldarea"><select name="freedomainpaymentterms[]" size="6" multiple="">
<option value="onetime">One Time</option>
<option value="monthly">Monthly</option>
<option value="quarterly">Quarterly</option>
<option value="semiannually">Semi-Annually</option>
<option value="annually">Annually</option>
<option value="biennially">Biennially</option>
<option value="triennially">Triennially</option>
</select><br>Free Domain TLD's</td></tr>
<tr><td class="fieldlabel">Free Domain TLD's</td><td class="fieldarea"><select name="freedomaintlds[]" size="5" multiple=""></select><br>Use Ctrl + Click to select multiple payment terms and TLD's</td></tr>
</tbody></table>

  </div>
</div>
<div id="tab5box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td class="fieldlabel">Custom Affiliate Payout</td><td class="fieldarea"><input type="radio" name="affiliatepaytype" value="" checked=""> Use Default <input type="radio" name="affiliatepaytype" value="percentage"> Percentage <input type="radio" name="affiliatepaytype" value="fixed"> Fixed Amount <input type="radio" name="affiliatepaytype" value="none"> No Commission</td></tr>
<tr><td class="fieldlabel">Affiliate Pay Amount</td><td class="fieldarea"><input type="text" name="affiliatepayamount" value="0.00" size="10"> <input type="checkbox" name="affiliateonetime"> One Time Payout (Default is Recurring)</td></tr>

<tr><td class="fieldlabel">Associated Downloads</td><td class="fieldarea">This is where you can specify files that are granted access to by purchasing this service.<br>
<table align="center"><tbody><tr><td valign="top">
<div align="center"><strong>Available Files</strong></div>
<div id="productdownloadsbrowser" style="width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;" class=""><ul class="jqueryFileTree" style=""></ul></div>
</td><td>&lt;&gt;</td><td valign="top">
<div align="center"><strong>Selected Files</strong></div>
<div id="productdownloadslist" style="width: 250px;height: 200px;border-top: solid 1px #BBB;border-left: solid 1px #BBB;border-bottom: solid 1px #FFF;border-right: solid 1px #FFF;background: #FFF;overflow: scroll;padding: 5px;"><ul class="jqueryFileTree"></ul></div>
</td></tr></tbody></table>
<div align="center"><input type="button" value="Add Category" class="button" id="showadddownloadcat"> <input type="button" value="Quick Upload" class="button" id="showquickupload"></div>
</td></tr>

</tbody></table>

  </div>
</div>
<div id="tab8box" class="tabbox" style="display: none;">
  <div id="tab_content">

<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
<tbody><tr><td class="fieldlabel">Direct Shopping Cart Link</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid=3" readonly=""></td></tr>
<tr><td class="fieldlabel">Direct Shopping Cart Link Specifying Template</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid=3&amp;carttpl=cart" readonly=""></td></tr>
<tr><td class="fieldlabel">Direct Shopping Cart Link Including Domain</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?a=add&amp;pid=3&amp;sld=ra&amp;tld=.com" readonly=""></td></tr>
<tr><td class="fieldlabel">Service Group Cart Link</td><td class="fieldarea"><input type="text" size="100" value="https://dev.roboticaccounting.com/cart.php?gid=1" readonly=""></td></tr>
</tbody></table>

  </div>
</div>

<p align="center"><input type="submit" value="Save Changes" class="button"> <input type="button" value="Back to Service List" onclick="window.location = 'configservices.php'" class="button"></p>

<input type="hidden" name="tab" id="tab" value="">

</form>




</div>