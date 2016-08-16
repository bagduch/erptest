<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery-ultimate-fancy-form.min.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/bootstrap.min.js"></script>
        <link href="templates/fontsawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/animate.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/custom.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container row">
            <h1>UI Order Process</h1>
            <div class="form-container">
                <h2 class="text-center">Registration</h2>
                <div class="address-container">
                    <div class="left-bar">
                        <div class="bg-success address-bar">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> Your Address
                        </div>
                    </div>
                    <div class="right-bar">
                        <div class="bg-primary address-bar">
                            {$address}
                            <span class="editaddress">Change <i class="fa fa-pencil" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                {if $step==""}
                    <form action="myorder.php" method="post">
                        <div class="row step-one">
                            {$error}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="#fname">First Name</label>
                                    <input type="text" id="fname" class="form-control" name="rfname" >
                                </div>
                                <div class="form-group">
                                    <label for="#fname">Last Name</label>
                                    <input type="text" id="fname" class="form-control" name="rlname">
                                </div>
                                <div class="form-group">
                                    <label for="#fname">Email</label>
                                    <input type="text" id="fname" class="form-control" name="remail">
                                </div>
                                <div class="form-group">
                                    <label for="#password">Password</label>
                                    <input type="password" id="password" class="form-control" name="rpassword">
                                </div>
                                <div class="form-group">
                                    <label for="#password2">Confirm Password</label>
                                    <input type="password" id="password2" class="form-control" name="rpassword2">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-default" name="signup" type="submit" value="Sign Up">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  for="#username">Username</label>
                                    <input type="text" id="username" class="form-control" name="username">
                                </div>
                                <div class="form-group">
                                    <label for="#password">Password</label>
                                    <input type="text" id="password" class="form-control" name="password">
                                </div>
                                <div class="remember">
                                    <label class="checkbox-inline rememberme"><input type="checkbox" name="rememberme" id="rememberme">Remember Me</label>
                                    <a href="pwreset.php" class="forgot" style="float:right;">Request a Password Reset</a>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-default" name="login" type="submit" value="Login">
                                </div>
                            </div>
                        </div>
                    </form>
                {elseif $step==2}
                    {if $product.contract && $contractnotsign}
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="contractform" action="myorder.php?step=2" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">Your Plan is a Contract</h4>    
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input type="text" id="etf" class="form-control" name="etf" disabled value="{$product.etf}">
                                                <label for="#etf">ETF</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" id="terms" class="form-control" name="terms" disabled value="{$product.term}">
                                                <label for="#terms">Terms</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-inline agreecontract">
                                                    <input type="checkbox" name="agreecontract"> Your Service is A contract please tick the box to agree the terms
                                                </label>

                                            </div>
                                            <p style="display:none" id="termwarning" class="bg-danger">please accept terms before continue</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" id="subterm" class="btn btn-primary">Are you agreed the terms?</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    {else}
                        <div class="row step-two">
                            <div class="addons">
                                <table width="100%" cellspacing="0" cellpadding="0" class="configtable">
                                    <tbody>
                                        {foreach from=$addons item=addon}
                                            <tr>
                                                <td class="radiofield">{$addon.checkbox}</td>
                                                <td class="fieldarea">
                                                    <label for="a{$addon.id}"><strong>{$addon.name}</strong> - {$addon.pricing}
                                                        <br>{$addon.description}
                                                    </label>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <div class="customfields">
                                {foreach from=$customefield item=fields}
                                    {if !$fields.adminonly}
                                        {if $fields.fieldtype eq "text"}
                                            {if $fields.fieldname eq "address"}
                                                <input name="customfield[{$fields.cfid}]" type="hidden" class="form-control input-lg" id="{$fields.fieldname}{$fields.cfid}"  value="{$address}"/>
                                            {else}

                                                <div class="form-group">
                                                    <label for="#{$fields.fieldname}{$fields.cfid}" class="control-label">{$fields.fieldname}{if $fields.required}<span>*</span>{/if}</label>
                                                    <input name="customfield[{$fields.cfid}]" type="text" class="form-control input-lg" id="{$fields.fieldname}{$fields.cfid}" placeholder="{$fields.description }"/>
                                                </div>
                                            {/if}
                                        {elseif $fields.fieldtype eq "password"}
                                        {elseif $fields.fieldtype eq "dropdown"}
                                        {elseif $fields.fieldtype eq "tickbox"}
                                        {else}
                                        {/if}
                                    {/if}
                                {/foreach}
                            </div>
                            <div class="sum">
                                <div class="">
                                    <table>
                                        <tr>
                                            <td colspan="3">{$product.groupname} - {$product.name}</td>
                                        </tr>
                                        <tr>
                                            <td>{$product.name}</td>
                                            <td></td>
                                            <td>{$pricing.minprice.price}</td>
                                        </tr>
                                        <tr>
                                            <td>Setup Fees:</td>
                                            <td></td>
                                            <td>{$currecy.prefix}{$pricing.rawpricing.msetupfee|number_format:2} {$currecy.code}</td>
                                        </tr>
                                        <tr>
                                            <td>Billing Cycle:</td>
                                            <td></td>
                                            <td>{$pricing.cycles.monthly}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">{$currecy.prefix}{$total|number_format:2} {$currecy.code}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/if}
                {elseif $step==3}
                    <div class="">
                        <div class="checkoutcol1">
                            <div class="signupfields padded">
                                <h2>Promotional Code</h2>
                                <input type="text" name="promocode" size="20" value=""> <input type="submit" name="validatepromo" value="Validate Code >>">            </div>
                        </div>
                        <div class="checkoutcol2">
                            <div class="signupfields padded">
                                <h2>Payment Method</h2>
                                {foreach from=$availablegateways item=row}
                                    <label>
                                        <input type="radio" name="paymentmethod" value="{$row.sysname}" id="pgbtn{$row.sysname}" onclick="hideCCForm()"> {$row.name}
                                    </label>
                                {/foreach}
                                <br><br>
                                <div id="ccinputform" class="signupfields hidden" style="">
                                    <table width="100%" cellspacing="0" cellpadding="0" class="configtable textleft">
                                        <input type="hidden" name="ccinfo" value="new">                          
                                        <tbody>
                                            <tr class="newccinfo">
                                                <td class="fieldlabel">Card Type</td>
                                                <td class="fieldarea">
                                                    <select name="cctype">
                                                        <option selected=""></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="newccinfo">
                                                <td class="fieldlabel">Card Number</td>
                                                <td class="fieldarea"><input type="text" name="ccnumber" size="30" value="" autocomplete="off"></td>
                                            </tr>
                                            <tr class="newccinfo">
                                                <td class="fieldlabel">Expiry Date</td>
                                                <td class="fieldarea">
                                                    <select name="ccexpirymonth" id="ccexpirymonth" class="newccinfo">
                                                        {foreach from=$months key=value item=row}
                                                            <option>{$row}</option>
                                                        {/foreach}
                                                    </select> / <select name="ccexpiryyear" class="newccinfo">
                                                        <option>2016</option>
                                                        <option>2017</option>
                                                        <option>2018</option>
                                                        <option>2019</option>
                                                        <option>2020</option>
                                                        <option>2021</option>
                                                        <option>2022</option>
                                                        <option>2023</option>
                                                        <option>2024</option>
                                                        <option>2025</option>
                                                        <option>2026</option>
                                                        <option>2027</option>
                                                        <option>2028</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fieldlabel">CVV/CVC2 Number</td>
                                                <td class="fieldarea">
                                                    <input type="text" name="cccvv" value="" size="5" autocomplete="off"> 
                                                    <a href="#" onclick="window.open('images/ccv.gif', '', 'width=280,height=200,scrollbars=no,top=100,left=100');
                                                            return false">Where do I find this?</a>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                </div>
                            </div>
                        </div>
                    </div>
                {elseif $step==4}
                {/if}
            </div>
        </div>
        {literal}
            <script type="text/javascript">
                $(document).ready(function () {
                    $("input[name^='addons']").click(function () {

                    });
                    $("#myModal").modal('show');
                    $("#subterm").click(function (e) {
                        e.preventDefault();
                        if ($("input[name='agreecontract']").is(":checked"))
                        {
                            $("#contractform").submit();

                        } else {
                            $("#termwarning").fadeIn();
                        }
                    });

                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.href = "https://peter.dev.roboticaccounting.com/myorder.php";
                    });
                });
            </script>
        {/literal}
    </body>
</html>