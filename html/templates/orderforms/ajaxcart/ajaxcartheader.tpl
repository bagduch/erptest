{*<script type="text/javascript" src="includes/jscript/jqueryui.js"></script>*}
<script type="text/javascript" src="templates/orderforms/{$carttpl}/js/main.js"></script>
<script type="text/javascript" src="templates/orderforms/{$carttpl}/js/jqueryfloat.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="templates/orderforms/{$carttpl}/style.css" />
{literal}<script type="text/javascript">
    function removeItem(type, num) {
        var response = confirm("{/literal}{$LANG.cartremoveitemconfirm}{literal}");
        if (response) {
            jQuery.post("cart.php", 'a=remove&r=' + type + '&i=' + num, function () {
                recalcsummary();
            });
        }
    }
    function emptyCart(type, num) {
        var response = confirm("{/literal}{$LANG.cartemptyconfirm}{literal}");
        if (response) {
            window.location = 'cart.php?a=empty';
        }
    }
    </script>{/literal}

    <div id="order-ajaxcart">

        <table cellpadding="0" cellspacing="0" class="ajaxcart">
            <tr><td valign="top">