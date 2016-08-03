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
                <form class="form form-horizontal" role="form" method="post">
                    <h2 class="text-center">Registration</h2>

                    {if $step==""}
                        <div class="row step-one">
                            {$error}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="#fname">First Name</label>
                                    <input type="text" id="fname" class="form-control" name="rfname" >
                                </div>
                                <div class="form-group">
                                    <label for="#fname">Last Name</label>
                                    <input type="text" id="fname" class="form-control" name="rfname">
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
                    {elseif $step==2}
                        <div class="row step-two">
                            <div class="form-group">
                                <div class="bg-info">
                                    <label class="checkbox-inline rememberme">
                                        <input type="checkbox" name=""> is Contract
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="">
                                    <input type="text" id="password" class="form-control" name="password">
                                    <label for="#password">ETF</label>
                                </div>
                                <div class="">
                                    <input type="text" id="password" class="form-control" name="password">
                                    <label for="#password">Terms</label>
                                </div>
                            </div>

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
                                            <tbody><tr class="newccinfo">
                                                    <td class="fieldlabel">Card Type</td><td class="fieldarea">
                                                        <select name="cctype">
                                                            <option selected=""></option>
                                                        </select></td></tr>
                                                <tr class="newccinfo"><td class="fieldlabel">Card Number</td><td class="fieldarea"><input type="text" name="ccnumber" size="30" value="" autocomplete="off"></td></tr>
                                                <tr class="newccinfo"><td class="fieldlabel">Expiry Date</td><td class="fieldarea">
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
                                                        </select></td></tr>
                                                <tr><td class="fieldlabel">CVV/CVC2 Number</td><td class="fieldarea"><input type="text" name="cccvv" value="" size="5" autocomplete="off"> <a href="#" onclick="window.open('images/ccv.gif', '', 'width=280,height=200,scrollbars=no,top=100,left=100');
                                                        return false">Where do I find this?</a></td></tr>
                                            </tbody></table>
                                    </div>

                                </div>

                            </div>



                        </div>

                    {elseif $step==4}
                    {/if}
                </form>
            </div>
        </div>
        {literal}
            <script type="text/javascript">
                $(document).ready(function () {
                    $("input[name^='addons']").click(function () {


                    });

                });
            </script>
        {/literal}
    </body>
</html>