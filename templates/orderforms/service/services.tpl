<script type="text/javascript" src="templates/orderforms/{$carttpl}/js/main.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<link rel="stylesheet" type="text/css" href="templates/orderforms/{$carttpl}/style.css" />

<div id="order-modern">
    <h1>Order</h1>
    {*<div align="center"><a href="#" onclick="showcats();return false;">({$LANG.cartchooseanothercategory})</a></div>*}
    <div class="form-container">
        <div class="">
            <label for="searchTextField">Your Address</label>
            <input id="searchTextField" class="form-control" type="text" name="address" value="">
        </div>
    </div>
    <div class="clear"></div>
    <form action ="myorder.php" method="post">
        <div class="products"></div>
        <input type="hidden" class="orderaddress" name="address" />
        <input type="hidden" class="orderid"  name="fpid" />
    </form>
    <h1>Order Product</h1>
    <div class="products">
        {debug}
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
                // data = JSON.parse(data);
                $(".orderaddress").val(input.value);
                html = "";
                for (i = 0; i < data.length; i++)
                {
                    html += "<div class='product' id='product" + data[i].id + "' onclick=''>";
                    html += "<table class='border>";
                    html += "<tbody><tr><td>";
                    html += "<div class='name'>" + data[i].name + "</div>";
                    html += "</td><td><div class='pricing'>$" + data[i].monthly + " NZD</div></td></tr></tbody></table>";
                    html += "<button class='btn btn-default buttonorder' data-id='" + data[i].id + "'>Order Now</button></div>";
                }

                $(".products").html(html);

                $(".buttonorder").click(function () {
                    $(".orderid").val($(this).attr("data-id"));
                });

            });
        });
    </script>
{/literal}