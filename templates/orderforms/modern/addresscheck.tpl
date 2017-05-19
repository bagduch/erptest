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
        <link href="templates/orderforms/{$carttpl}/uff/css/custom.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/calander/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
        <link href="templates/orderforms/{$carttpl}/checkbox/css/build.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="templates/orderforms/modern/style.css" />
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
    <body>
        <div class="container row">
            <h1>Order</h1>
            <div class="form-container">
                <form class="form" method="post" action="myorder.php">
                    <div class="">
                        <label for="searchTextField">input your address</label>
                        <input id="searchTextField"  class="form-control" type="text" name="address" value="">
                    </div>
                </form>
                <div id="order-modern">
                    <div class="products">
                        {foreach from=$services key=id item=row}
                            <div class="product" id="product{$id}" onclick="window.location = 'cart.php?a=adds&amp;pid=48'">
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
                                                <form method="post" action="cart.php?a=adds&amp;pid=48">
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

                        console.log(data.results);
                        for (var i = 0; i < data.results.length; i++)
                        {
                            if (data.results[i].availability == 'Available')
                            {
                                if (data.results[i].technology == "Fibre")
                                {
                                }
                                if (data.results[i].technology == "VDSL")
                                {
                                    console.log(data.results[i]);
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