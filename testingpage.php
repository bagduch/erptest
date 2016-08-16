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
        console.log(place);
    });

</script>