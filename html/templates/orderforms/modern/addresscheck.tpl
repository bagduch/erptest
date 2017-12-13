<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="templates/orderforms/{$carttpl}/uff/js/jquery.js"></script>
        <script src="templates/orderforms/{$carttpl}/uff/js/bootstrap.min.js"></script>
        {*        <script src="templates/orderforms/{$carttpl}/selectstyle/js/classie.js"></script>*}
        {*        <script src="templates/orderforms/{$carttpl}/selectstyle/js/selectFx.js"></script>*}
        <script src="templates/orderforms/{$carttpl}/calander/js/bootstrap-datepicker.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>

        <link href="templates/fontsawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/animate.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/uff/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/calander/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/checkbox/css/build.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="templates/orderforms/modern/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
        <link rel='stylesheet' id='hexa-css' href='templates/{$template}/css/ui-portal.css' type='text/css' media='all' />
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
    <body class="address-page">
        <div class="bodybg"></div>
        <div class="bodybg1"></div>
            <div class="row head addr-plan"><div class="navbar">
                <div class="navbar-header">
                    <div class="navbar-brand"><a href="clientarea.php"><img src="/templates/uihex/img/ui-logo-332x80.png"><h6>Client Area</h6></a></div>
                </div>
            </div></div>

            <div class="address container row">
                <div class="form-container">
                    <div class="form addr-chk">
                        <div class="address-check">
                            <label for="searchTextField">Please enter your address below to see what services are available to you.</label>
                            <label for="searchTextField" class="searched">The following services are available at</label>
                            <input id="searchTextField"  class="form-control address" type="text" name="address" value=""><i class="fa fa-search"></i><i class="fa fa-pencil  "></i>
                        </div>
                    </div>
                    <div class="form speed-chk">
                        <!--<div class="address-checked">
                            <p class="text">The following broadband technologies are available at</p>
                            <p class="address">7E Douglas Alexander Parade, Rosedale Auckland 0632</p>
                            <a href="#">Change Address</a>
                        </div>-->
                        <div class="connection-speeds">
                            <ul>
                                <li class="ufb">
                                    <a href="#" class="available selected"><strong>UFB</strong>Upto 200Mbps</a>
                                </li>
                                <li class="vdsl">
                                    <a href="#" class="available"><strong>VDSL</strong>Upto 80Mbps</a>
                                </li>
                                <li class="adsl">
                                    <a href="#" class="available"><strong>ADSL</strong>Upto 24Mbps</a>
                                </li>
                            </ul>
                        </div>
                        <!--<div class="btm-btn-cont">
                        <a class="bck" href="#">< BACK</a>
                        </div>-->
                    </div>

                    <div class="form ufb plan-chk">
                        <!--<div class="address-checked">
                            <p class="text">The following UFB plans are available at</p>
                            <p class="address">7E Douglas Alexander Parade, Rosedale Auckland 0632</p>
                            <a href="#">Change Address</a>
                        </div>-->
                        <div class="instruction">
                            <p class="text">Choose your Plan</p>
                        </div>
                        <div class="plan-container">
                            <ul>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 30/10</strong></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 100/20</strong></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 100/100</strong></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 200/20</strong></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 200/10</strong></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>UFB 200/200</strong></a>
                                </li>
                            </ul>
                        </div>
                        <!--<div class="btm-btn-cont">
                        <a class="bck" href="#">< BACK</a>
                        <a class="cont" href="#">Continue</a>
                        </div>-->
                    </div>

                    <div class="form vdsl plan-chk">
                        <!--<div class="address-checked">
                            <p class="text">The following VDSL plans are available at</p>
                            <p class="address">7E Douglas Alexander Parade, Rosedale Auckland 0632</p>
                            <a href="#">Change Address</a>
                        </div>-->
                        <div class="instruction">
                            <p class="text">Choose your Plan</p>
                        </div>
                        <div class="plan-container">
                            <ul>
                                <li class="plan invisible">
                                    <a href="#" class="available"></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>VDSL</strong>Upto 80Mbps</a>
                                </li>
                                <li class="plan invisible">
                                    <a href="#" class="available"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="activedsl">
                            <fieldset>
                                    <legend>Do you have an active and working ADSL/VDSL internet connection with another ISP at the address ?</legend>
                                            <input type="radio" name="activedsl" value="Yes" /><label for="switch_left">Yes</label>
                                            <input type="radio" name="activedsl" value="No" /><label for="switch_right">No</label>
                                            <p class="nodsl">Please note: a connection fee of $150 may apply.<p>
                            </fieldset>
                        </div>
                        <!--<div class="btm-btn-cont">
                        <a class="bck" href="#">< BACK</a>
                        <a class="cont" href="#">Continue</a>
                        </div>-->
                    </div>


                    <div class="form adsl plan-chk">
                        <!--<div class="address-checked">
                            <p class="text">The following ADSL plans are available at</p>
                            <p class="address">7E Douglas Alexander Parade, Rosedale Auckland 0632</p>
                            <a href="#">Change Address</a>
                        </div>-->
                        <div class="instruction">
                            <p class="text">Choose your Plan</p>
                        </div>
                        <div class="plan-container">
                            <ul>
                                <li class="plan invisible">
                                    <a href="#" class="available"></a>
                                </li>
                                <li class="plan">
                                    <a href="#" class="available"><strong>ADSL</strong>Upto 24Mbps</a>
                                </li>
                                <li class="plan invisible">
                                    <a href="#" class="available"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="activedsl">
                            <fieldset>
                                    <legend>Do you have an active and working ADSL/VDSL internet connection with another ISP at the address ?</legend>
                                            <input type="radio" name="activedsl" value="Yes" /><label for="switch_left">Yes</label>
                                            <input type="radio" name="activedsl" value="No" /><label for="switch_right">No</label>
                                            <p class="nodsl">Please note: a connection fee of $150 may apply.<p>
                            </fieldset>
                        </div>
                        <!--<div class="btm-btn-cont">
                        <a class="bck" href="#">< BACK</a>
                        <a class="cont" href="#">Continue</a>
                        </div>-->
                    </div>

                    <div id="order-modern">
                        <div class="products">
                            {foreach from=$services key=id item=row}
                                <div class="product" id="product{$id}">
                                    <table class="border">
                                        <tbody>
                                            <tr>
                                                <td> 
                                                    <div class="name">
                                                        {$row.name}
                                                    </div>
                                                </td>
                                                <td> 
                                                    <div class="pricing">
                                                        {$row.price}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>          
                                                    <div class="clear"></div>
                                                    <div class="description">drttre<br>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form method="post" action="">
                                                        <input class="addressorder" type="hidden" name="address" value="{$id}">
                                                        <input type="hidden" name="uid" value="{$id}">
                                                        <div class="ordernowbox"><input type="submit" value="Order Now »" class="ordernow"></div>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        {literal}
            <script type="text/javascript">
                var input = document.getElementById('searchTextField');
                var options = {
                    componentRestrictions: {country: 'nz'}
                };
                autocomplete = new google.maps.places.Autocomplete(input, options);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    hdregion = [
                        "Ashburton",
                        "Blenheim",
                        "Gisborne",
                        "Greymouth",
                        "Invercargill",
                        "Kapiti",
                        "Paraparaumu",
                        "Levin",
                        "Masterton",
                        "Napier",
                        "Hastings",
                        "Nelson",
                        "Oamaru",
                        "Queenstown",
                        "Wanaka",
                        "Taupo",
                        "Tauranga",
                        "Timaru",
                        "Whakatane",
                        "Whanganui"
                    ];
                    var place = autocomplete.getPlace();
                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();

                    region = place.vicinity;
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "addresscheck.php",
                        data: {address: input.value, lat: lat, lng: lng, region: region}
                    }).done(function (data) {
                        data = JSON.parse(data);
                        $(".addressorder").val(input.value);

                        for (var i = 0; i < data.results.length; i++)
                        {
                            if (data.results[i].availability == 'Available')
                            {
                                if (data.results[i].technology == "Fibre")
                                {
                                }
                                if (data.results[i].technology == "VDSL")
                                {

                                }
                                if (data.results[i].technology == "ADSL")
                                {


                                }
                            }
                        }
                    });
                });
            </script>
        {/literal}
    </body>
</html>