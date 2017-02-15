<?php

define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Add New Order", false);
$aInt->title = $aInt->lang("orders", "addnew");
$aInt->sidebar = "orders";
$aInt->icon = "orders";
$aInt->requiredFiles(array("orderfunctions", "servicefunctions", "whoisfunctions", "configoptionsfunctions", "customfieldfunctions", "clientfunctions", "invoicefunctions", "processinvoices", "gatewayfunctions", "fraudfunctions", "modulefunctions", "cartfunctions"));
$action = $ra->get_req_var("action");
$userid = $ra->get_req_var("userid");
$currency = getCurrency($userid);
$menuselect = "$('#menu').multilevelpushmenu('expand','Orders');";

if ($action == "createpromo") {
    check_token("RA.admin.default");
    if (!$code) {
        exit("Promotion Code is Required");
    }
    if ($pvalue <= 0) {
        exit("Promotion Value must be greater than zero");
    }
    $result = select_query_i("tblpromotions", "COUNT(*)", array("code" => $code));
    $data = mysqli_fetch_array($result);
    $duplicates = $data[0];

    if ($duplicates) {
        exit("Promotion Code already exists. Please try another.");
    }

    $promoid = insert_query("tblpromotions", array("code" => $code,
        "type" => $type,
        "recurring" =>
        $recurring, "value" => $pvalue, "maxuses" => "1", "recurfor" => $recurfor, "expirationdate" => "0000-00-00", "notes" => "Order Process One Off Custom Promo"));
    $promo_type = $type;
    $promo_value = $pvalue;
    $promo_recurring = $recurring;
    $promo_code = $code;

    if ($promo_type == "Percentage") {
        $promo_value .= "%";
    } else {
        $promo_value = formatCurrency($promo_value);
    }

    $promo_recurring = ($promo_recurring ? "Recurring" : "One Time");
    echo "<option value=\"" . $promo_code . "\">" . $promo_code . " - " . $promo_value . " " . $promo_recurring . "</option>";
    exit();
}


if ($action == "getconfigoptions") {

    $configoptions = getCartConfigOptions($pid, "", $cycle);

    if (count($configoptions) > 0) {
        $options .= "<p><b>" . $aInt->lang("setup", "configoptions") . "</b></p>
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">";
        foreach ($configoptions as $configoption) {
            $options .= "<tr><td width=\"130\" class=\"fieldlabel\">" . $configoption['optionname'] . "</td><td class=\"fieldarea\">";

            if ($configoption['optiontype'] == "1") {
                $options .= "<select onchange=\"updatesummary()\" name=\"configoption[" . $orderid . "][" . $configoption['id'] . "]\">";
                foreach ($configoption['options'] as $optiondata) {
                    $options .= "<option value=\"" . $optiondata['id'] . "\"";

                    if ($optiondata['id'] == $configoption['selectedvalue']) {
                        $options .= " selected";
                    }

                    $options .= ">" . $optiondata['name'] . "</option>";
                }

                $options .= "</select>";
            } else {
                if ($configoption['optiontype'] == "2") {
                    foreach ($configoption['options'] as $optiondata) {
                        $options .= "<input type=\"radio\" onclick=\"updatesummary()\" name=\"configoption[" . $orderid . "][" . $configoption['id'] . "]\" value=\"" . $optiondata['id'] . "\"";

                        if ($optiondata['id'] == $configoption['selectedvalue']) {
                            $options .= " checked=\"checked\"";
                        }

                        $options .= "> " . $optiondata['name'] . "<br />";
                    }
                } else {
                    if ($configoption['optiontype'] == "3") {
                        $options .= "<input type=\"checkbox\" onclick=\"updatesummary()\" name=\"configoption[" . $orderid . "][" . $configoption['id'] . "]\" value=\"1\"";

                        if ($configoption['selectedqty']) {
                            $options .= " checked=\"checked\"";
                        }

                        $options .= "> " . $configoption['options'][0]['name'];
                    } else {
                        if ($configoption['optiontype'] == "4") {
                            $options .= "<input type=\"text\" onclick=\"updatesummary()\" name=\"configoption[" . $orderid . "][" . $configoption['id'] . "]\" value=\"" . $configoption['selectedqty'] . "\" size=\"5\"> x " . $configoption['options'][0]['name'];
                        }
                    }
                }
            }

            $options .= "</td></tr>";
        }

        $options .= "</table>";
    }

    $customfields = getCustomFields("", $pid, "", "", "on");

    if (count($customfields)) {
        $options .="<div class=\"box\"><div class='box-header'>
                                <h3 class='box-title'>" . $aInt->lang("setup", "customfields") . "</h3>
                            </div>
<table class=\"table\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">";
        foreach ($customfields as $customfield) {
            $inputfield = str_replace("name=\"customfield", "name=\"customfield[" . $orderid . "]", $customfield['input']);
            $options .= "<tr><td width=\"130\" class=\"fieldlabel\">" . $customfield['name'] . "</td><td class=\"fieldarea\">" . $inputfield . "</td></tr>";
        }

        $options .= "</table></div>";
    }

    $addonshtml = "";
    $addonsarray = getAddons($pid);

    if (count($addonsarray)) {
        foreach ($addonsarray as $addon) {
            $addonshtml .= "<label>" . str_replace("<input type=\"checkbox\" name=\"addons", "<input type=\"checkbox\" onclick=\"updatesummary()\" name=\"addons[" . $orderid . "]", $addon['checkbox']) . " " . $addon['name'] . " (" . $addon['pricing'] . ")";

            if ($addon['description']) {
                $addonshtml .= " - " . $addon['description'];
            }

            $addonshtml .= "</label><br />";
        }
    }

    echo json_encode(array("options" => $options, "addons" => $addonshtml));
    exit();
}


if ($ra->get_req_var("submitorder")) {
    check_token("RA.admin.default");
    $userid = get_query_val("tblclients", "id", array("id" => $userid));

    if (!$userid && !$calconly) {
        infoBox("Invalid Client ID", "Please enter or select a valid client to add the order to");
    } else {
        $_SESSION['uid'] = $userid;
        getUsersLang($userid);
        $_SESSION['cart'] = array();
        $_SESSION['cart']['paymentmethod'] = $paymentmethod;
        foreach ($pid as $k => $prodid) {

            if ($prodid) {
                $addons[$k] = array_keys($addons[$k]);

                if (!$qty[$k]) {
                    $qty[$k] = 1;
                }

                $productarray = array("pid" => $prodid, "domain" => $domain[$k], "billingcycle" => str_replace(array("-", " "), "", strtolower($billingcycle[$k])), "server" => "", "configoptions" => $configoption[$k], "customfields" => $customfield[$k], "addons" => $addons[$k]);

                if (strlen($_POST['priceoverride'][$k])) {
                    $productarray['priceoverride'] = $_POST['priceoverride'][$k];
                }

                $count = 1;

                while ($count <= $qty[$k]) {
                    $_SESSION['cart']['products'][] = $productarray;
                    ++$count;
                }

                continue;
            }
        }
        if ($promocode) {
            $_SESSION['cart']['promo'] = $promocode;
        }
        $_SESSION['cart']['orderconfdisabled'] = ($adminorderconf ? false : true);
        $_SESSION['cart']['geninvoicedisabled'] = ($admingenerateinvoice ? false : true);
        if (!$adminsendinvoice) {
            $CONFIG['NoInvoiceEmailOnOrder'] = true;
        }
        if ($calconly) {
            $ordervals = calcCartTotals();
            echo "<div class=\"ordersummarytitle\">Order Summary</div>
<div id=\"ordersummary\">
<table>
";

            if (is_array($ordervals['products'])) {
                foreach ($ordervals['products'] as $cartprod) {
                    echo "<tr class=\"item\"><td colspan=\"2\"><div class=\"itemtitle\">" . $cartprod['productinfo']['groupname'] . " - " . $cartprod['productinfo']['name'] . "</div>";
                    echo $aInt->lang("billingcycles", $cartprod['billingcycle']);

                    if ($cartprod['domain']) {
                        echo " - " . $cartprod['domain'];
                    }
                    echo "<div class=\"itempricing\">";
                    if ($cartprod['priceoverride']) {
                        echo formatCurrency($cartprod['priceoverride']) . "*";
                    } else {
                        echo $cartprod['pricingtext'];
                    }
                    echo "</div>";
                    if ($cartprod['configoptions']) {
                        foreach ($cartprod['configoptions'] as $cartcoption) {

                            if ($cartcoption['type'] == "1" || $cartcoption['type'] == "2") {
                                echo "<br />&nbsp;&raquo;&nbsp;" . $cartcoption['name'] . ": " . $cartcoption['value'];
                                continue;
                            }

                            if ($cartcoption['type'] == "3") {
                                echo "<br />&nbsp;&raquo;&nbsp;" . $cartcoption['name'] . ": ";

                                if ($cartcoption['qty']) {
                                    echo $aInt->lang("global", "yes");
                                    continue;
                                }

                                echo $aInt->lang("global", "no");
                                continue;
                            }

                            if ($cartcoption['type'] == "4") {
                                echo "<br />&nbsp;&raquo;&nbsp;" . $cartcoption['name'] . ": " . $cartcoption['qty'] . " x " . $cartcoption['option'];
                                continue;
                            }
                        }
                    }

                    echo "</td></tr>";

                    if ($cartprod['addons']) {
                        foreach ($cartprod['addons'] as $addondata) {
                            echo "<tr class=\"item\"><td colspan=\"2\"><div class=\"itemtitle\">" . $addondata['name'] . "</div><div class=\"itempricing\">" . $addondata['pricingtext'] . "</div></td></tr>";
                        }

                        continue;
                    }
                }
            }


            $cartitems = 0;
            foreach (array("products", "addons", "domains", "renewals") as $k) {

                if (array_key_exists($k, $ordervals)) {
                    $cartitems += count($ordervals[$k]);
                    continue;
                }
            }


            if (!$cartitems) {
                echo "<tr class=\"item\"><td colspan=\"2\"><div class=\"itemtitle\" align=\"center\">No Items Selected</div></td></tr>";
            }

            echo "<tr class=\"subtotal\"><td>Subtotal</td><td class=\"alnright\">" . $ordervals['subtotal'] . "</td></tr>";

            if ($ordervals['promotype']) {
                echo "<tr class=\"promo\"><td>Promo Discount</td><td class=\"alnright\">" . $ordervals['discount'] . "</td></tr>";
            }


            if ($ordervals['taxrate']) {
                echo "<tr class=\"tax\"><td>" . $ordervals['taxname'] . " @ " . $ordervals['taxrate'] . "%</td><td class=\"alnright\">" . $ordervals['taxtotal'] . "</td></tr>";
            }


            if ($ordervals['taxrate2']) {
                echo "<tr class=\"tax\"><td>" . $ordervals['taxname2'] . " @ " . $ordervals['taxrate2'] . "%</td><td class=\"alnright\">" . $ordervals['taxtotal2'] . "</td></tr>";
            }

            echo "<tr class=\"total\"><td width=\"140\">Total</td><td class=\"alnright\">" . $ordervals['total'] . "</td></tr>";

            if ((((($ordervals['totalrecurringmonthly'] || $ordervals['totalrecurringquarterly']) || $ordervals['totalrecurringsemiannually']) || $ordervals['totalrecurringannually']) || $ordervals['totalrecurringbiennially']) || $ordervals['totalrecurringtriennially']) {
                echo "<tr class=\"recurring\"><td>Recurring</td><td class=\"alnright\">";

                if ($ordervals['totalrecurringmonthly']) {
                    echo "" . $ordervals['totalrecurringmonthly'] . " Monthly<br />";
                }


                if ($ordervals['totalrecurringquarterly']) {
                    echo "" . $ordervals['totalrecurringquarterly'] . " Quarterly<br />";
                }


                if ($ordervals['totalrecurringsemiannually']) {
                    echo "" . $ordervals['totalrecurringsemiannually'] . " Semi-Annually<br />";
                }


                if ($ordervals['totalrecurringannually']) {
                    echo "" . $ordervals['totalrecurringannually'] . " Annually<br />";
                }


                if ($ordervals['totalrecurringbiennially']) {
                    echo "" . $ordervals['totalrecurringbiennially'] . " Biennially<br />";
                }


                if ($ordervals['totalrecurringtriennially']) {
                    echo "" . $ordervals['totalrecurringtriennially'] . " Triennially<br />";
                }

                echo "</td></tr>";
            }

            echo "</table>
</div>";
            exit();
        }
        $cartitems = count($_SESSION['cart']['products']) + count($_SESSION['cart']['addons']) + count($_SESSION['cart']['renewals']);

        if (!$cartitems) {
            redir("noselections=1");
        }

        calcCartTotals(true);
        unset($_SESSION['uid']);

        if ($orderstatus == "Active") {
            update_query("tblorders", array("status" => "Active"), array("id" => $_SESSION['orderdetails']['OrderID'])
            );

            if (is_array($_SESSION['orderdetails']['Products'])) {
                foreach ($_SESSION['orderdetails']['Products'] as $productid) {
                    update_query("tblcustomerservices", array("servicestatus" => "Active"), array("id" => $productid)
                    );
                }
            }
        }

        getUsersLang(0);
        redir("action=view&id=" . $_SESSION['orderdetails']['OrderID'], "orders.php");
        exit();
    }
}

releaseSession();

$jquerycode = "
$(function(){
    var prodtemplate = $(\"#products .product:first\").clone();
    var productsCount = 0;
    window.addProduct = function(){
        productsCount++;
        var order = prodtemplate.clone().find(\"*\").each(function(){
            var newId = this.id.substring(0, this.id.length-1) + productsCount;

            $(this).prev().attr(\"for\", newId); // update label for
            this.id = newId; // update id

        }).end()
        .attr(\"id\", \"ord\" + productsCount)
        .appendTo(\"#products\");
        return false;
    }
    $(\".addproduct\").click(addProduct);

    var domainsCount = 0;
    window.addDomain = function(){
        domainsCount++;
        $('<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\" style=\"margin-top:10px;\"><tr><td width=\"130\" class=\"fieldlabel\">" . $aInt->lang("domains", "regtype", 1) . "</td><td class=\"fieldarea\"><input type=\"radio\" name=\"regaction['+domainsCount+']\" id=\"domnon'+domainsCount+'\" value=\"\" onclick=\"loaddomainoptions(this,0);updatesummary()\" checked /> <label for=\"domnon'+domainsCount+'\">" . $aInt->lang("global", "none", 1) . "</label> <input type=\"radio\" name=\"regaction['+domainsCount+']\" value=\"register\" id=\"domreg'+domainsCount+'\" onclick=\"loaddomainoptions(this,1);updatesummary()\" /> <label for=\"domreg'+domainsCount+'\">" . $aInt->lang("domains", "register", 1) . "</label> <input type=\"radio\" name=\"regaction['+domainsCount+']\" value=\"transfer\" id=\"domtrf'+domainsCount+'\" onclick=\"loaddomainoptions(this,2);updatesummary()\" /> <label for=\"domtrf'+domainsCount+'\">" . $aInt->lang("domains", "transfer", 1) . "</label></td></tr><tr class=\"hiddenrow\" id=\"domrowdn'+domainsCount+'\" style=\"display:none;\"><td class=\"fieldlabel\">" . $aInt->lang("fields", "domain", 1) . "</td><td class=\"fieldarea\"><input type=\"text\" class=\"regdomain\" id=\"regdomain'+domainsCount+'\" name=\"regdomain['+domainsCount+']\" size=\"40\" onkeyup=\"updatesummary()\" /></td></tr><tr class=\"hiddenrow\" id=\"domrowrp'+domainsCount+'\" style=\"display:none;\"><td class=\"fieldlabel\">" . $aInt->lang("domains", "regperiod", 1) . "</td><td class=\"fieldarea\"><select name=\"regperiod['+domainsCount+']\" onchange=\"updatesummary()\">" . $regperiods . "</select></td></tr><tr class=\"hiddentransrow\" id=\"domrowep'+domainsCount+'\" style=\"display:none;\"><td class=\"fieldlabel\">" . $aInt->lang("domains", "eppcode", 1) . "</td><td class=\"fieldarea\"><input type=\"text\" name=\"eppcode['+domainsCount+']\" size=\"20\" /></td></tr><tr class=\"hiddenrow\" id=\"domrowad'+domainsCount+'\" style=\"display:none;\"><td class=\"fieldlabel\">" . $aInt->lang("domains", "addons", 1) . "</td><td class=\"fieldarea\"><label><input type=\"checkbox\" name=\"dnsmanagement['+domainsCount+']\" onclick=\"updatesummary()\" /> " . $aInt->lang("domains", "dnsmanagement", 1) . "</label> <label><input type=\"checkbox\" name=\"emailforwarding['+domainsCount+']\" onclick=\"updatesummary()\" /> " . $aInt->lang("domains", "emailforwarding", 1) . "</label> <label><input type=\"checkbox\" name=\"idprotection['+domainsCount+']\" onclick=\"updatesummary()\" /> " . $aInt->lang("domains", "idprotection", 1) . "</label></td></tr><tr id=\"domainaddlfieldserase'+domainsCount+'\" style=\"display:none\"></tr></table>').appendTo(\"#domains\");
        return false;
    }
    $(\".adddomain\").click(addDomain);

    $(\"#domain0\").keyup(function() {
      $(\"#regdomain0\").val($(\"#domain0\").val());
    });

   

});
";
$jscode = "
function loadproductoptions(piddd) {
    var ord = piddd.id.substring(3);
    var pid = piddd.value;
    var billingcycle = $(\"#billingcycle option:selected\").val();
    if (pid==0) {
        $(\"#productconfigoptions\"+ord).html(\"\");
        $(\"#addonsrow\"+ord).hide();
        updatesummary();
    } else {
    $(\"#productconfigoptions\"+ord).html(\"<p align=\\\"center\\\">" . $aInt->lang("global", "loading") . "<br><img src=\\\"../images/loading.gif\\\"></p>\");
    $.post(\"ordersadd.php\", { action: \"getconfigoptions\", pid: pid, cycle: billingcycle, orderid: ord, token: \"" . generate_token("plain") . "\" },
    function(data){
    console.log(data);
        if (data.addons) {
            $(\"#addonsrow\"+ord).show();
            $(\"#addonscont\"+ord).html(data.addons);
        } else {
            $(\"#addonsrow\"+ord).hide();
        }
        $(\"#productconfigoptions\"+ord).html(data.options);
        updatesummary();
    },\"json\");
    }
}
function loaddomainoptions(domrd,type) {
    var ord = domrd.id.substring(6);
    if (type==1) {
        $(\"#domrowdn\"+ord).css(\"display\",\"\");
        $(\"#domrowrp\"+ord).css(\"display\",\"\");
        $(\"#domrowep\"+ord).css(\"display\",\"none\");
        $(\"#domrowad\"+ord).css(\"display\",\"\");
    } else if (type==2) {
        $(\"#domrowdn\"+ord).css(\"display\",\"\");
        $(\"#domrowrp\"+ord).css(\"display\",\"\");
        $(\"#domrowep\"+ord).css(\"display\",\"\");
        $(\"#domrowad\"+ord).css(\"display\",\"\");
    } else {
        $(\"#domrowdn\"+ord).css(\"display\",\"none\");
        $(\"#domrowrp\"+ord).css(\"display\",\"none\");
        $(\"#domrowep\"+ord).css(\"display\",\"none\");
        $(\"#domrowad\"+ord).css(\"display\",\"none\");
    }
}
function updatesummary() {
    jQuery.post(\"ordersadd.php\", \"submitorder=1&calconly=1&\"+jQuery(\"#orderfrm\").serialize(),
    function(data){
        jQuery(\"#ordersumm\").html(data);
    });
}
";
ob_start();

if (!checkActiveGateway()) {
    $aInt->gracefulExit($aInt->lang("gateways", "nonesetup"));
}


if ($userid && !$paymentmethod) {
    $paymentmethod = getClientsPaymentMethod($userid);
}


if ($ra->get_req_var("noselections")) {
    infoBox($aInt->lang("global", "validationerror"), $aInt->lang("orders", "noselections"));
}
echo $infobox;

$result = select_query_i("tblpromotions", "", "(maxuses<=0 OR uses<maxuses) AND (expirationdate='0000-00-00' OR expirationdate>='" . date("Ymd") . "')", "code", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $promo_id = $data['id'];
    $promo_code = $data['code'];
    $promo_type = $data['type'];
    $promo_recurring = $data['recurring'];
    $promo_value = $data['value'];

    if ($promo_type == "Percentage") {
        $promo_value .= "%";
    } else {
        $promo_value = formatCurrency($promo_value);
    }
    if ($promo_type == "Free Setup") {
        $promo_value = $aInt->lang("promos", "freesetup");
    }
    $promo_recurring = ($promo_recurring ? $aInt->lang("status", "recurring") : $aInt->lang("status", "onetime"));
    if ($promo_type == "Price Override") {
        $promo_recurring = $aInt->lang("promos", "priceoverride");
    }
    if ($promo_type == "Free Setup") {
        $promo_recurring = "";
    }
    $activepromotion = "<option value=\"" . $promo_code . "\">" . $promo_code . " - " . $promo_value . " " . $promo_recurring . "</option>";
}


$result = select_query_i("tblpromotions", "", "(maxuses>0 AND uses>=maxuses) OR (expirationdate!='0000-00-00' AND expirationdate<'" . date("Ymd") . "')", "code", "ASC");

while ($data = mysqli_fetch_array($result)) {
    $promo_id = $data['id'];
    $promo_code = $data['code'];
    $promo_type = $data['type'];
    $promo_recurring = $data['recurring'];
    $promo_value = $data['value'];

    if ($promo_type == "Percentage") {
        $promo_value .= "%";
    } else {
        $promo_value = formatCurrency($promo_value);
    }


    if ($promo_type == "Free Setup") {
        $promo_value = $aInt->lang("promos", "freesetup");
    }

    $promo_recurring = ($promo_recurring ? $aInt->lang("status", "recurring") : $aInt->lang("status", "onetime"));

    if ($promo_type == "Price Override") {
        $promo_recurring = $aInt->lang("promos", "priceoverride");
    }


    if ($promo_type == "Free Setup") {
        $promo_recurring = "";
    }

    $expireprmotion = "<option value=\"" . $promo_code . "\">" . $promo_code . " - " . $promo_value . " " . $promo_recurring . "</option>";
}


if (!$billingcycle) {
    $billingcycle = "Monthly";
}

echo $aInt->cyclesDropDown($billingcycle, "", "", "billingcycle[]", "updatesummary()");
echo "</td></tr>
<tr id=\"addonsrow0\" style=\"display:none;\"><td class=\"fieldlabel\">";
echo $aInt->lang("addons", "title");
echo "</td><td class=\"fieldarea\" id=\"addonscont0\"></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "quantity");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"qty[]\" value=\"1\" size=\"5\" onkeyup=\"updatesummary()\" /></td></tr>
<tr><td class=\"fieldlabel\">";
echo $aInt->lang("fields", "priceoverride");
echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"priceoverride[]\" size=\"10\" onkeyup=\"updatesummary()\" /> ";
echo $aInt->lang("orders", "priceoverridedesc");
echo "</td></tr>
</table>

<div id=\"productconfigoptions0\"></div>

</div>
</div>

<p style=\"padding-left:20px;\"><a href=\"#\" class=\"addproduct\"><img src=\"images/icons/add.png\" border=\"0\" align=\"absmiddle\" /> ";
echo $aInt->lang("orders", "anotherproduct");
echo "</a></p>

</td><td valign=\"top\">

<div id=\"ordersumm\" style=\"padding:15px;\"></div>

<div class=\"ordersummarytitle\"><input type=\"submit\" value=\"";
echo $aInt->lang("orders", "submit");
echo " &raquo;\" class=\"btn-primary\" style=\"font-size:20px;padding:12px 30px ;\" /></div></td></tr></table></form>";
echo "<script> updatesummary(); </script>";

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->assign("token", generate_token("plain"));

if (!$billingcycle) {
    $billingcycle = "Monthly";
}
$aInt->assign("cyclesDropDown", $aInt->cyclesDropDown($billingcycle, "", "", "billingcycle[]", "updatesummary()"));
$aInt->assign("createpromo", $createpromo);
$aInt->assign("activepromotion", $activepromotion);
$aInt->assign("expireprmotion", $expireprmotion);
$aInt->assign("PHP_SELF", $_SERVER['PHP_SELF']);
$aInt->assign("clientdrop", $aInt->clientsDropDown($userid));
$aInt->assign("productdrop", $aInt->productDropDown(0, true));
$aInt->assign("paymentdrop", paymentMethodsSelection());
$aInt->template = "order/add";
$aInt->jquerycode = $jquerycode;
$aInt->jquerycode .=$menuselect;
//$aInt->jscode = $jscode;
$aInt->display();
?>
