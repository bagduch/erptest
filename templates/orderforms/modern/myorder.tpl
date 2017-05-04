<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/bootstrap.min.js"></script>
        {*        <script src="templates/orderforms/{$carttpl}/selectstyle/js/classie.js"></script>*}
        {*        <script src="templates/orderforms/{$carttpl}/selectstyle/js/selectFx.js"></script>*}
        <script src="templates/orderforms/{$carttpl}/calander/js/bootstrap-datepicker.js"></script>

        <link href="templates/fontsawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/animate.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/custom.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/calander/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/checkbox/css/build.css" rel="stylesheet" type="text/css">

        {*        <link href="templates/orderforms/{$carttpl}/selectstyle/css/cs-select.css" rel="stylesheet" type="text/css">*}
        {*        <link href="templates/orderforms/{$carttpl}/selectstyle/css/cs-skin-elastic.css" rel="stylesheet" type="text/css">*}

        {literal}
            <style>
                .panel-title>a{
                    text-decoration: none;
                }
            </style>
        {/literal}
    </head>
    {debug}
    <body>
        <div class="container row">
            <h1>UI Order Process</h1>
            <div class="form-container">
                <div class="bg-warning address-bar">
                    <h2>LET US KNOW WHO YOU ARE</h2>
                </div>
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
                            <div class="chose">
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-body">
                                            <span class="hide-sm">
                                                <i class="fa fa-user fa-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="box-text">I am already with Unlimited Internet</span>
                                        </div>
                                        <div class="box-footer">
                                            <button class="btn btn-transparent" onclick="showlogin();
                                                    return false">Login</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-body">
                                            <span class="hide-sm">
                                                <i class="fa fa-user-plus fa-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="box-text">I am new to Unlimited Internet</span>
                                        </div>
                                        <div class="box-footer">
                                            <button class="btn btn-transparent" onclick="showsignup();
                                                    return false">Sign Up</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12">

                                <div class="sign-up">
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <span class="hide-sm">
                                                <i class="fa fa-user-plus fa-4" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="#fname">First Name</label>
                                                <input type="text" id="fname" class="form-control" name="rfname" >
                                            </div>
                                            <div class="form-group">
                                                <label for="#fname">Last Name</label>
                                                <input type="text" id="fname" class="form-control" name="rlname">
                                            </div>
                                            <div class="form-group">
                                                <label for="#dob">Date Of Birth</label>
                                                <input type="text" id="dob" class="form-control" name="rdob">
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

                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="box-footer">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-transparent" onclick="backtoselect();
                                                        return false">Back</button>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="btn btn-transparent" name="signup" type="submit" value="Sign Up">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="login">
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <span class="hide-sm">
                                                <i class="fa fa-user fa-4" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label  for="#username">Username</label>
                                                <input type="text" id="username" class="form-control" name="username">
                                            </div>
                                            <div class="form-group">
                                                <label for="#password">Password</label>
                                                <input type="password" id="password" class="form-control" name="password">
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-inline rememberme"><input type="checkbox" name="rememberme" id="rememberme">Remember Me</label>
                                                <a href="pwreset.php" class="forgot" style="float:right;">Request a Password Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="box-footer">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-transparent" onclick="backtoselect();
                                                            return false">Back</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="btn btn-transparent" name="login" type="submit" value="Login">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                        <form method="post" action="myorder.php">
                            <div class="row step-two">
                                <div class="col-xs-12 col-sm-6 col-md-8">


                                    <div class="intalldate">
                                        {include file='orderforms/modern/customefield.tpl'}
                                    </div>
                                    {if $addons}
                                        <div class="addons">

                                            <div class="panel panel-primary">
                                                <div class="panel-heading"> <h3 class="panel-title">Availble Addons</h3> </div>
                                                <div class="panel-body">

                                                    <div class="panel panel-default">
                                                        {foreach from=$addons item=addon key=cid}
                                                            <div class="panel-heading" role="tab" id="headingOne">
                                                                <table style="width:100%">
                                                                    <tr>
                                                                        <td>  
                                                                            <h4 class="panel-title">
                                                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{$cid}" aria-expanded="true" aria-controls="collapse{$cid}">
                                                                                    <strong>{$addon.name}</strong> 
                                                                                    <span style="color:green;padding-left: 20px;">
                                                                                        {$addon.price.minprice.price}
                                                                                        ({if $addon.price.minprice.cycle eq 'onetime'}One Off{else if $addon.price.minprice.cycle eq 'montly'}Montly{/if})</span>

                                                                                </a>
                                                                            </h4>
                                                                        </td>
                                                                        <td> 
                                                                            <div class="button-right">
                                                                                {$addon.checkbox}

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="collapse{$cid}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                                <div class="panel-body">
                                                                    {$addon.description}
                                                                    {if $addon.customefield}
                                                                        {foreach from=$addon.customefield item=customefield}
                                                                            {include file='orderforms/modern/customefield.tpl'}
                                                                        {/foreach}
                                                                    {/if}
                                                                </div>
                                                            </div>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="custome-notes">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"> <h3 class="panel-title">Notes</h3> </div>
                                            <div class="panel-body">
                                                <textarea class="form-control" rows="5" name="notes" placeholder="What want to say to us:"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="plan">

                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="right-sum">
                                        <div class="bg-success address-bar">
                                            <h2 style="text-transform: uppercase;margin-left: 5px;">Summary</h2>
                                        </div>
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div id="summary-list" class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                            {$product.groupname} {$pricing.cycles.monthly}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        {$product.name} {$pricing.cycles.monthly}<br/>{$product.description} 
                                                        <input type="hidden" class="monthlyprice" value="{$pricing.rawpricing.monthly}">
                                                    </div>
                                                </div>

                                                <div class="panel-heading" role="tab" id="headingTwo">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                            Setup Fees:{$currecy.prefix}{$pricing.rawpricing.msetupfee|number_format:2} {$currecy.code}
                                                            <input type="hidden" class="setupprice" value="{$pricing.rawpricing.msetupfee}">
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        One Off Free
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="cart-sumary-title">TOTAL</h3>


                                                    <div class="monthly">Monthly payments: <span class="right">{$currecy.prefix}{$pricing.rawpricing.monthly|number_format:2}</span></div>
                                                    <div class="oneoff">Total one-off payment:<span class="right">{$currecy.prefix}{$pricing.rawpricing.msetupfee|number_format:2}</span></div>
                                                    <div class="firstpayment">First payment: <span class="right">{$currecy.prefix}{$total|number_format:2}</span></div>

                                                    <input type="hidden" name="firstpaymentamount" value="{$total|number_format:2}">
                                                </div>
                                            </div>
                                            <div style="background-color:#428bca" class="bg-danger address-bar">
                                                <div class="button">
                                                    <input name="checkout" type="submit" value="Check Out" class="btn cart-default">
                                                </div>
                                                <div class="button">
                                                    <button type="button" class="btn cart-default cancel">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    {/if}
                {elseif $step==3}
                    <div class="">
                        <div class="checkoutcol1">
                            <div class="signupfields padded">
                                <h2>Promotional Code</h2>
                                <input type="text" name="promocode" size="20" value=""> <input type="submit" name="validatepromo" value="Validate Code >>">       
                            </div>
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


                function showlogin()
                {
                    $(".chose").hide();
                    $(".login").show();

                }
                function showsignup()
                {
                    $(".chose").hide();
                    $(".sign-up").show();

                }

                function backtoselect()
                {
                    $(".chose").show();
                    $(".sign-up").hide();
                    $(".login").hide();

                }
                function recaculate()
                {
                    monthly = parseFloat($(".monthlyprice").val());
                    setup = parseFloat($(".setupprice").val());
                    $(".addonsprice").each(function () {
                        if ($(this).attr("data-type") == "onetime")
                        {
                            setup += parseFloat($(this).val());
                        } else {
                            monthly += parseFloat($(this).val());
                        }
                    });
                    oneoff = setup;
                    total = monthly + setup;
                    $(".monthly").find("span").html("$" + monthly.toFixed(2));
                    $(".oneoff").find("span").html("$" + oneoff.toFixed(2));
                    $(".firstpayment").find("span").html("$" + total.toFixed(2));
                }
                function addonaddtocart(id)
                {
                    $.ajax({
                        method: "POST",
                        url: "myorder.php",
                        data: {ajax: "1", addonid: id, actions: "add"},
                        success: function (msg) {
                            console.log(msg);
                            $("#accordion").find("#summary-list").append(msg);
                            recaculate();
                        }
                    });
                }
                function removefromcart(id)
                {
                    $.ajax({
                        method: "POST",
                        url: "myorder.php",
                        data: {ajax: "1", addonid: id, actions: "remove"},
                        success: function (msg) {
                            $("#addon" + id + "").remove();
                            $("#addoncollapse" + id + "").remove();
                            recaculate();
                        }
                    });
                }
                $(document).ready(function () {

                    $("#accordion").on("show.bs.collapse", function (e)
                    {
                        clicked = $(document).find("[href='#" + $(e.target).attr('id') + "']");
                        clicked.find('i').attr('class', "fa fa-caret-down");
                    });

                    $("#accordion").on("hide.bs.collapse", function (e)
                    {
                        clicked = $(document).find("[href='#" + $(e.target).attr('id') + "']");
                        clicked.find('i').attr('class', "fa fa-caret-right");
                    });
                    $('#sandbox-container input').datepicker({
                        daysOfWeekDisabled: "0,6",
                        startDate: "+7d"
                    });
                    $(".btn-circle").click(function (e) {
                        e.preventDefault();
                        id = $(this).attr("data-addon");

                        if ($(this).hasClass(('btn-info')))
                        {
                            if (typeof id != "undefined")
                            {
                                removefromcart(id);
                            }
                            $(this).find("input").val("off");
                            $(this).removeClass('btn-info');
                            $(this).find("i").removeClass();
                        } else {
                            if (typeof id != "undefined")
                            {
                                addonaddtocart(id);
                            }

                            $(this).find("input").val("on");
                            $(this).addClass('btn-info');
                            $(this).find("i").attr('class', 'fa fa-check');
                        }



                    });
                    $(".hidden-button").click(function (e) {
                        e.preventDefault();
                        if ($(".hidden-option").is(":visible"))
                        {
                            $(".hidden-option").fadeOut();
                        } else {
                            $(".hidden-option").fadeIn();
                        }

                    });
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
                        location.reload();
                    });
                });
            </script>
        {/literal}
    </body>
</html>