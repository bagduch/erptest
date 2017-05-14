<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("CLIENTAREA", true);
require "init.php";
initialiseClientArea();
?>
<html>
    <head>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
    </head>
    <body>
        <form method="post" action="myorder.php">
            <div class="">
                <input type="hidden" value="<?php echo generate_token("plain") ?>">
                input your address
                <input id="searchTextField" type="text" name="address" value="11c piermark Drive">
                <input id="street_number" name="streetnumber" type="text">
                <input id="route" name="address2" type="text">
                <input id="locality" name="locality" type="text">
                <input id="administrative_area_level_1" name="region" type="text">
                <input id="country" name="country" type="text">
                <input id="postal_code" name="zip" type="text">


                your product id
                <input type="text" name="fpid" value="3">
                <input type="submit">
            </div>
        </form>
    </body>
</html>

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
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (addressType in componentForm)
            {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
                console.log(addressType);
            }
        }
    });

</script>