<?php

function getOrderStatusColour($status) {
    $statuscolors = array("Active" => "779500", "Pending" => "CC0000", "Fraud" => "000000", "Cancelled" => "888");
    return "<span style=\"color:#" . $statuscolors[$status] . "\">" . $status . "</span>";
}

function getProductInfo($pid) {

    $result = select_query_i("tblservices", "tblservices.id,tblservices.gid,tblservices.type,tblservices.name AS prodname,tblservicegroups.name AS groupname,tblservices.description", array("tblservices.id" => $pid), "", "", "", "tblservicegroups ON tblservicegroups.id=tblservices.gid");
    $data = mysqli_fetch_array($result);
    $productinfo = array();
    $productinfo['pid'] = $data['id'];
    $productinfo['gid'] = $data['gid'];
    $productinfo['type'] = $data['type'];
    $productinfo['groupname'] = $data['groupname'];
    $productinfo['name'] = $data['prodname'];
    $productinfo['description'] = nl2br($data['description']);
    $productinfo['freedescription'] = $data['freedescription'];
    $productinfo['freedescriptionpaymentterms'] = explode(",", $data['freedescriptionpaymentterms']);
    $productinfo['freedescriptiontlds'] = explode(",", $data['freedescriptiontlds']);
    $stockcontrol = $data['stockcontrol'];

    if ($stockcontrol) {
        $productinfo['qty'] = $data['qty'];
    }

    return $productinfo;
}

function getPricingInfo($pid, $inclconfigops = false, $upgrade = false, $currencs = array()) {
    global $CONFIG;
    global $_LANG;
    global $currency;
    if (empty($currency)) {
        $currency = $currencs;
    }

    $result = select_query_i("tblservices", "", array("id" => $pid));
    $data = mysqli_fetch_array($result);
    $paytype = $data['paytype'];
    $freedescription = $data['freedescription'];
    $freedescriptionpaymentterms = $data['freedescriptionpaymentterms'];
    $result = select_query_i("tblpricing", "*", array("type" => "product", "currency" => $currency['id'], "relid" => $pid));
    $data = mysqli_fetch_array($result);
    //mail("peter@hd.net.nz", "hello", print_r($data, 1));
    $msetupfee = $data['msetupfee'];
    $qsetupfee = $data['qsetupfee'];
    $ssetupfee = $data['ssetupfee'];
    $asetupfee = $data['asetupfee'];
    $bsetupfee = $data['bsetupfee'];
    $tsetupfee = $data['tsetupfee'];
    $monthly = $data['monthly'];
    $quarterly = $data['quarterly'];
    $semiannually = $data['semiannually'];
    $annually = $data['annually'];
    $biennially = $data['biennially'];
    $triennially = $data['triennially'];
    $freedescriptionpaymentterms = explode(",", $freedescriptionpaymentterms);
    $monthlypricingbreakdown = $CONFIG['ProductMonthlyPricingBreakdown'];
    $minprice = 0;
    $mincycle = "";
    $hasconfigoptions = false;

    if ($paytype == "free") {
        $pricing['type'] = $mincycle = "free";
    } else {
        if ($paytype == "onetime") {
            $configoptions = getCartConfigOptions($pid, array(), "onetime", "", true);

            if (count($configoptions)) {
                if ($inclconfigops) {
                    foreach ($configoptions as $option) {
                        $monthly += $option['selectedsetup'] + $option['selectedrecurring'];
                    }
                }

                $hasconfigoptions = true;
            }

            $minprice = $monthly;
            $pricing['type'] = $mincycle = "onetime";
            $pricing['onetime'] = formatCurrency($monthly);

            if ($msetupfee != "0.00") {
                $pricing->onetime .= " + " . formatCurrency($msetupfee) . " " . $_LANG['ordersetupfee'];
            }


            if ((in_array("onetime", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                $pricing->onetime .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
            }
        } else {
            if ($paytype == "recurring") {
                $pricing['type'] = "recurring";

                if (0 <= $monthly) {
                    $configoptions = getCartConfigOptions($pid, array(), "monthly", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $msetupfee += $option['selectedsetup'];
                                $monthly += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $monthly;
                        $mincycle = "monthly";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['monthly'] = $_LANG['orderpaymentterm1month'] . " - " . formatCurrency($monthly);
                    } else {
                        $pricing['monthly'] = formatCurrency($monthly) . " " . $_LANG['orderpaymenttermmonthly'];
                    }


                    if ($msetupfee != "0.00") {
                        $pricing->monthly .= " + " . formatCurrency($msetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("monthly", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->monthly .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }


                if (0 <= $quarterly) {
                    $configoptions = getCartConfigOptions($pid, array(), "quarterly", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $qsetupfee += $option['selectedsetup'];
                                $quarterly += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $quarterly;
                        $mincycle = "quarterly";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['quarterly'] = $_LANG['orderpaymentterm3month'] . " - " . formatCurrency($quarterly / 3);
                    } else {
                        $pricing['quarterly'] = formatCurrency($quarterly) . " " . $_LANG['orderpaymenttermquarterly'];
                    }


                    if ($qsetupfee != "0.00") {
                        $pricing->quarterly .= " + " . formatCurrency($qsetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("quarterly", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->quarterly .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }


                if (0 <= $semiannually) {
                    $configoptions = getCartConfigOptions($pid, array(), "semiannually", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $ssetupfee += $option['selectedsetup'];
                                $semiannually += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $semiannually;
                        $mincycle = "semiannually";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['semiannually'] = $_LANG['orderpaymentterm6month'] . " - " . formatCurrency($semiannually / 6);
                    } else {
                        $pricing['semiannually'] = formatCurrency($semiannually) . " " . $_LANG['orderpaymenttermsemiannually'];
                    }


                    if ($ssetupfee != "0.00") {
                        $pricing->semiannually .= " + " . formatCurrency($ssetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("semiannually", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->semiannually .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }


                if (0 <= $annually) {
                    $configoptions = getCartConfigOptions($pid, array(), "annually", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $asetupfee += $option['selectedsetup'];
                                $annually += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $annually;
                        $mincycle = "annually";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['annually'] = $_LANG['orderpaymentterm12month'] . " - " . formatCurrency($annually / 12);
                    } else {
                        $pricing['annually'] = formatCurrency($annually) . " " . $_LANG['orderpaymenttermannually'];
                    }


                    if ($asetupfee != "0.00") {
                        $pricing->annually .= " + " . formatCurrency($asetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("annually", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->annually .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }


                if (0 <= $biennially) {
                    $configoptions = getCartConfigOptions($pid, array(), "biennially", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $bsetupfee += $option['selectedsetup'];
                                $biennially += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $biennially;
                        $mincycle = "biennially";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['biennially'] = $_LANG['orderpaymentterm24month'] . " - " . formatCurrency($biennially / 24);
                    } else {
                        $pricing['biennially'] = formatCurrency($biennially) . " " . $_LANG['orderpaymenttermbiennially'];
                    }


                    if ($bsetupfee != "0.00") {
                        $pricing->biennially .= " + " . formatCurrency($bsetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("biennially", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->biennially .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }


                if (0 <= $triennially) {
                    $configoptions = getCartConfigOptions($pid, array(), "triennially", "", true);

                    if (count($configoptions)) {
                        if ($inclconfigops) {
                            foreach ($configoptions as $option) {
                                $tsetupfee += $option['selectedsetup'];
                                $triennially += $option['selectedrecurring'];
                            }
                        }

                        $hasconfigoptions = true;
                    }


                    if (!$mincycle) {
                        $minprice = $triennially;
                        $mincycle = "triennially";
                    }


                    if ($monthlypricingbreakdown) {
                        $pricing['triennially'] = $_LANG['orderpaymentterm36month'] . " - " . formatCurrency($triennially / 36);
                    } else {
                        $pricing['triennially'] = formatCurrency($triennially) . " " . $_LANG['orderpaymenttermtriennially'];
                    }


                    if ($tsetupfee != "0.00") {
                        $pricing->triennially .= " + " . formatCurrency($tsetupfee) . " " . $_LANG['ordersetupfee'];
                    }


                    if ((in_array("triennially", $freedescriptionpaymentterms) && $freedescription) && !$upgrade) {
                        $pricing->triennially .= " (" . $_LANG['orderfreedescriptiononly'] . ")";
                    }
                }
            }
        }
    }

    $pricing['hasconfigoptions'] = $hasconfigoptions;

    if ($pricing['onetime']) {
        $pricing['cycles']['onetime'] = $pricing['onetime'];
    }


    if ($pricing['monthly']) {
        $pricing['cycles']['monthly'] = $pricing['monthly'];
    }


    if ($pricing['quarterly']) {
        $pricing['cycles']['quarterly'] = $pricing['quarterly'];
    }


    if ($pricing['semiannually']) {
        $pricing['cycles']['semiannually'] = $pricing['semiannually'];
    }


    if ($pricing['annually']) {
        $pricing['cycles']['annually'] = $pricing['annually'];
    }


    if ($pricing['biennially']) {
        $pricing['cycles']['biennially'] = $pricing['biennially'];
    }


    if ($pricing['triennially']) {
        $pricing['cycles']['triennially'] = $pricing['triennially'];
    }

    $pricing['rawpricing'] = array("msetupfee" => format_as_currency($msetupfee), "qsetupfee" => format_as_currency($qsetupfee), "ssetupfee" => format_as_currency($ssetupfee), "asetupfee" => format_as_currency($asetupfee), "bsetupfee" => format_as_currency($bsetupfee), "tsetupfee" => format_as_currency($tsetupfee), "monthly" => format_as_currency($monthly), "quarterly" => format_as_currency($quarterly), "semiannually" => format_as_currency($semiannually), "annually" => format_as_currency($annually), "biennially" => format_as_currency($biennially), "triennially" => format_as_currency($triennially));
    $pricing['minprice'] = array("price" => formatCurrency($minprice), "cycle" => $mincycle);
    return $pricing;
}

function calcCartTotals($checkout = "", $ignorenoconfig = "") {
    global $CONFIG;
    global $_LANG;
    global $remote_ip;
    global $currency;
    global $promo_data;

    $cart_total = $cart_discount = $cart_tax = 0;
    run_hook("PreCalculateCartTotals", $_SESSION['cart']);

    if (!$ignorenoconfig) {
        if (array_key_exists("products", $_SESSION['cart'])) {
            foreach ($_SESSION['cart']['products'] as $key => $productdata) {

                if ($productdata['noconfig']) {
                    unset($_SESSION['cart']['products'][$key]);
                    continue;
                }
            }
        }

        $bundlewarnings = bundlesValidateCheckout();

        if (array_key_exists("products", $_SESSION['cart'])) {
            $_SESSION['cart']['products'] = array_values($_SESSION['cart']['products']);
        }
    }



    if ($checkout) {
        if (!$_SESSION['cart']) {
            return false;
        }

        run_hook("PreShoppingCartCheckout", $_SESSION['cart']);
        $order_number = generateUniqueID();
        $paymentmethod = $_SESSION['cart']['paymentmethod'];
        $availablegateways = getAvailableOrderPaymentGateways();

        if (!array_key_exists($paymentmethod, $availablegateways)) {
            foreach ($availablegateways as $k => $v) {
                $paymentmethod = $k;
                break;
            }
        }

        $userid = $_SESSION['uid'];
        $ordernotes = "";

        if ($_SESSION['cart']['notes'] && $_SESSION['cart']['notes'] != $_LANG['ordernotesdescription']) {
            $ordernotes = $_SESSION['cart']['notes'];
        }

        $cartitems = count($_SESSION['cart']['products']) + count($_SESSION['cart']['addons']) + count($_SESSION['cart']['descriptions']) + count($_SESSION['cart']['renewals']);

        if (!$cartitems) {
            return false;
        }

        $orderid = insert_query("tblorders", array(
            "ordernum" => $order_number,
            "userid" => $userid,
//          "contactid" => $_SESSION['cart']['contact'], 
            "date" => "now()",
            "status" => "Pending",
            "paymentmethod" => $paymentmethod,
            "ipaddress" => $remote_ip,
            "notes" => $ordernotes)
        );
        $hostid = insert_query($table, $array);
        logActivity("New Order Placed - Order ID: " . $orderid . " - User ID: " . $userid);
        $descriptioneppcodes = array();
    }

    $promotioncode = (array_key_exists("promo", $_SESSION['cart']) ? $_SESSION['cart']['promo'] : "");

    if ($promotioncode) {
        $result = select_query_i("tblpromotions", "", array("code" => $promotioncode));
        $promo_data = mysqli_fetch_array($result);
    }


    if (!isset($_SESSION['uid'])) {
        if (!$_SESSION['cart']['user']['country']) {
            $_SESSION['cart']['user']['country'] = $CONFIG['DefaultCountry'];
        }

        $state = $_SESSION['cart']['user']['state'];
        $country = $_SESSION['cart']['user']['country'];
    } else {
        $clientsdetails = getClientsDetails($_SESSION['uid']);
        $state = $clientsdetails['state'];
        $country = $clientsdetails['country'];
    }


    if ($CONFIG['TaxEnabled']) {
        $taxdata = getTaxRate(1, $state, $country);
        $taxname = $taxdata['name'];
        $taxrate = $taxdata['rate'];
        $rawtaxrate = $taxrate;
        $inctaxrate = $taxrate / 100 + 1;
        $taxrate /= 100;
        $taxdata = getTaxRate(2, $state, $country);
        $taxname2 = $taxdata['name'];
        $taxrate2 = $taxdata['rate'];
        $rawtaxrate2 = $taxrate2;
        $inctaxrate2 = $taxrate2 / 100 + 1;
        $taxrate2 /= 100;
    }


    if ($CONFIG['TaxInclusiveDeduct'] && ((!$taxrate && !$taxrate2) || $clientsdetails['taxexempt'])) {
        $result = select_query_i("tbltax", "", "");
        $data = mysqli_fetch_array($result);
        $excltaxrate = 1 + $data['taxrate'] / 100;
    } else {
        $CONFIG['TaxInclusiveDeduct'] = 0;
    }

    $cartdata = $productsarray = $tempdescriptions = $orderproductids = $orderdescriptionids = $orderaddonids = $orderrenewalids = $freedescriptions = array();
    $recurring_cycles_total = array("monthly" => 0, "quarterly" => 0, "semiannually" => 0, "annually" => 0, "biennially" => 0, "triennially" => 0);

    if (array_key_exists("products", $_SESSION['cart']) && is_array($_SESSION['cart']['products'])) {

        foreach ($_SESSION['cart']['products'] as $key => $productdata) {
            $result = select_query_i("tblservices", "tblservices.id,tblservices.gid,tblservicegroups.name AS groupname,tblservices.name,tblservices.paytype,tblservices.proratabilling,tblservices.proratadate,tblservices.proratachargenextmonth,tblservices.tax,tblservices.servertype,tblservices.servergroup", array("tblservices.id" => $productdata['pid']), "", "", "", "tblservicegroups ON tblservicegroups.id=tblservices.gid");
            $data = mysqli_fetch_array($result);
            $pid = $data['id'];
            $gid = $data['gid'];
            $groupname = $data['groupname'];
            $productname = $data['name'];
            $paytype = $data['paytype'];
            $proratabilling = $data['proratabilling'];
            $proratadate = $data['proratadate'];
            $proratachargenextmonth = $data['proratachargenextmonth'];
            $tax = $data['tax'];
            $servertype = $data['servertype'];
            $servergroup = $data['servergroup'];
            $productinfo = getProductInfo($pid);
            $productdata['productinfo'] = $productinfo;

            if (!function_exists("getCustomFields")) {
                require ROOTDIR . "/includes/customfieldfunctions.php";
            }

            // $customfields = getCustomFields("product", $pid, "", true, "", $productdata['customfields']);
            // $productdata['customfields'] = $customfields;
            $pricing = getPricingInfo($pid);

            if ($pricing['type'] == "recurring") {
                $billingcycle = strtolower($productdata['billingcycle']);

                if (!in_array($billingcycle, array("monthly", "quarterly", "semiannually", "annually", "biennially", "triennially"))) {
                    $billingcycle = "";
                }


                if ($pricing['rawpricing'][$billingcycle] < 0) {
                    $billingcycle = "";
                }


                if (!$billingcycle) {
                    if (0 <= $pricing['rawpricing']['monthly']) {
                        $billingcycle = "monthly";
                    } elseif (0 <= $pricing['rawpricing']['quarterly']) {
                        $billingcycle = "quarterly";
                    } elseif (0 <= $pricing['rawpricing']['semiannually']) {
                        $billingcycle = "semiannually";
                    } elseif (0 <= $pricing['rawpricing']['annually']) {
                        $billingcycle = "annually";
                    } elseif (0 <= $pricing['rawpricing']['biennially']) {
                        $billingcycle = "biennially";
                    } elseif (0 <= $pricing['rawpricing']['triennially']) {
                        $billingcycle = "triennially";
                    }
                }
            } else {
                if ($pricing['type'] == "onetime") {
                    $billingcycle = "onetime";
                } else {
                    $billingcycle = "free";
                }
            }

            $productdata['billingcycle'] = $billingcycle;

            if ($billingcycle == "free") {
                $product_setup = $product_onetime = $product_recurring = "0";
                $databasecycle = "Free Account";
            } else {
                if ($billingcycle == "onetime") {
                    $product_setup = $pricing['rawpricing']['msetupfee'];
                    $product_onetime = $pricing['rawpricing']['monthly'];
                    $product_recurring = 0;
                    $databasecycle = "One Time";
                } else {
                    $product_setup = $pricing['rawpricing'][substr($billingcycle, 0, 1) . "setupfee"];
                    $product_onetime = $product_recurring = $pricing['rawpricing'][$billingcycle];
                    $databasecycle = ucfirst($billingcycle);

                    if ($databasecycle == "Semiannually") {
                        $databasecycle = "Semi-Annually";
                    }
                }
            }

            $before_priceoverride_value = "";

            if ($bundleoverride = bundlesGetProductPriceOverride("product", $key)) {
                $before_priceoverride_value = $product_setup + $product_onetime;
                $product_setup = 0;
                $product_onetime = $product_recurring = $bundleoverride;
            }

            $hookret = run_hook("OrderProductPricingOverride", array("key" => $key, "pid" => $pid, "proddata" => $productdata));
            foreach ($hookret as $hookret2) {

                if (is_array($hookret2)) {
                    if ($hookret2['setup']) {
                        $product_setup = $hookret2['setup'];
                    }


                    if ($hookret2['recurring']) {
                        $product_onetime = $product_recurring = $hookret2['recurring'];
                        continue;
                    }

                    continue;
                }
            }

            $productdata['pricing']['baseprice'] = formatCurrency($product_onetime);
            $configurableoptions = array();
            $configurableoptions = getCartConfigOptions($pid, $productdata['configoptions'], $billingcycle);
            $configoptions = "";

            if ($configurableoptions) {
                foreach ($configurableoptions as $confkey => $value) {
                    $configoptions[] = array("name" => $value['optionname'], "type" => $value['optiontype'], "option" => $value['selectedoption'], "optionname" => $value['selectedname'], "setup" => (0 < $value['selectedsetup'] ? formatCurrency($value['selectedsetup']) : ""), "recurring" => formatCurrency($value['selectedrecurring']), "qty" => $value['selectedqty']);
                    $configoptionsdb[$value['id']] = array("value" => $value['selectedvalue'], "qty" => $value['selectedqty']);
                    $product_setup += $value['selectedsetup'];
                    $product_onetime += $value['selectedrecurring'];

                    if (strlen($before_priceoverride_value)) {
                        $before_priceoverride_value += $value['selectedrecurring'];
                    }


                    if ($billingcycle != "onetime") {
                        $product_recurring += $value['selectedrecurring'];
                        continue;
                    }
                }
            }

            $productdata['configoptions'] = $configoptions;

            if (in_array($billingcycle, $freedescriptionpaymentterms)) {
                $description = $productdata['description'];
                $descriptionparts = explode(".", $description, 2);
                $tld = "." . $descriptionparts[1];

                if (in_array($tld, $freedescriptiontlds)) {
                    $freedescriptions[$description] = $freedescription;
                }
            }


            if ($proratabilling) {
                $proratavalues = getProrataValues($billingcycle, $product_onetime, $proratadate, $proratachargenextmonth, date("d"), date("m"), date("Y"), $_SESSION['uid']);
                $product_onetime = $proratavalues['amount'];
                $productdata['proratadate'] = fromMySQLDate($proratavalues['date']);
            }


            if ($CONFIG['TaxInclusiveDeduct']) {
                $product_setup = format_as_currency($product_setup / $excltaxrate);
                $product_onetime = format_as_currency($product_onetime / $excltaxrate);
                $product_recurring = format_as_currency($product_recurring / $excltaxrate);
            }

            $product_total_today_db = $product_setup + $product_onetime;
            $product_recurring_db = $product_recurring;
            $productdata['pricing']['setup'] = $product_setup * 1;
            $productdata['pricing']['recurring'][$billingcycle] = $product_recurring * 1;
            $productdata['pricing']['totaltoday'] = $product_total_today_db * 1;

            if ($product_onetime == 0 && $product_recurring == 0) {
                $pricing_text = $_LANG['orderfree'];
            } else {
                $pricing_text = "";

                if (strlen($before_priceoverride_value)) {
                    $pricing_text .= "<strike>" . formatCurrency($before_priceoverride_value) . "</strike> ";
                }

                $pricing_text .= formatCurrency($product_onetime);

                if (0 < $product_setup) {
                    $pricing_text .= " + " . formatCurrency($product_setup) . " " . $_LANG['ordersetupfee'];
                }


                if ($allowqty && 1 < $qty) {
                    $pricing_text .= $_LANG['invoiceqtyeach'] . "<br />" . $_LANG['invoicestotal'] . ": " . formatCurrency($productdata['pricing']['totaltoday']);
                }
            }

            $productdata['pricingtext'] = $pricing_text;

            if ($promotioncode) {
                $onetimediscount = $recurringdiscount = $promoid = 0;

                if ($promocalc = CalcPromoDiscount($pid, $databasecycle, $product_total_today_db, $product_recurring_db, $product_setup)) {
                    $onetimediscount = $promocalc['onetimediscount'];
                    $recurringdiscount = $promocalc['recurringdiscount'];
                    $product_total_today_db -= $onetimediscount;
                    $product_recurring_db -= $recurringdiscount;
                    $cart_discount += $onetimediscount * 1;
                    $promoid = $promo_data['id'];
                }
            }


            if (isset($productdata['priceoverride'])) {
                $product_total_today_db = $product_recurring_db = $product_onetime = $productdata['priceoverride'];
                $product_setup = 0;
            }

            $cart_total += $product_total_today_db * 1;
            $product_total_qty_recurring = $product_recurring_db * 1;

            if (($CONFIG['TaxEnabled'] && $tax) && !$clientsdetails['taxexempt']) {
                $cart_tax += $product_total_today_db * 1;

                if ($CONFIG['TaxType'] == "Exclusive") {
                    if ($CONFIG['TaxL2Compound']) {
                        $product_total_qty_recurring += $product_total_qty_recurring * $taxrate;
                        $product_total_qty_recurring += $product_total_qty_recurring * $taxrate2;
                    } else {
                        $product_total_qty_recurring += $product_total_qty_recurring * $taxrate + $product_total_qty_recurring * $taxrate2;
                    }
                }
            }

            $recurring_cycles_total[$billingcycle] += $product_total_qty_recurring;
            $description = $productdata['description'];
            $serverhostname = $productdata['server']['hostname'];
            $serverns1prefix = $productdata['server']['ns1prefix'];
            $serverns2prefix = $productdata['server']['ns2prefix'];
            $serverrootpw = encrypt($productdata['server']['rootpw']);

            if ($serverns1prefix && $description) {
                $serverns1prefix = $serverns1prefix . "." . $description;
            }


            if ($serverns2prefix && $description) {
                $serverns2prefix = $serverns2prefix . "." . $description;
            }


            if ($serverhostname) {
                $description = ($description ? $serverhostname . "." . $description : $serverhostname);
            }

            $productdata['description'] = $description;

            if ($checkout) {
                $multiqtyids = array();
                $qtycount = 1;
                $qty = 1;

                while ($qtycount <= $qty) {

                    $serverid = ($servertype ? getServerID($servertype, $servergroup) : "");
                    $hostingquerydates = ($databasecycle == "Free Account" ? "0000-00-00" : date("Y-m-d"));
                    $serviceid = insert_query("tblcustomerservices", array(
                        "userid" => $userid,
                        "orderid" => $orderid,
                        "packageid" => $pid,
                        "server" => $serverid,
                        "regdate" => "now()",
                        "description" => $description,
                        "paymentmethod" => $paymentmethod,
                        "firstpaymentamount" => $product_total_today_db,
                        "amount" => $product_recurring_db,
                        "billingcycle" => $databasecycle,
                        "nextduedate" => $hostingquerydates,
                        "nextinvoicedate" => $hostingquerydates,
                        "servicestatus" => "Pending",
                        "password" => $serverrootpw
                            )
                    );
                    $multiqtyids[$qtycount] = $serviceid;
                    $orderproductids[] = $serviceid;

                    if ($configoptionsdb) {
                        foreach ($configoptionsdb as $key => $value) {
                            insert_query("tblcustomerservicesconfigoptions", array("relid" => $serviceid, "configid" => $key, "optionid" => $value['value'], "qty" => $value['qty']));
                        }
                    }

                    if (!empty($productdata['customfields'])) {
                        foreach ($productdata['customfields'] as $key => $value) {
                            insert_query("tblcustomfieldsvalues", array("cfid" => $key, "relid" => $serviceid, "value" => $value));
                        }
                    }

                    $productdetails = getInvoiceProductDetails($serviceid, $pid, date("Y-m-d"), $hostingquerydates, $databasecycle, $description);
                    error_log(print_r($productdetails, 1));
                    $invoice_description = $productdetails['description'];
                    $invoice_tax = $productdetails['tax'];

                    if (!$_SESSION['cart']['geninvoicedisabled']) {
                        $prodinvoicearray = array();
                        $prodinvoicearray['userid'] = $userid;
                        $prodinvoicearray['type'] = "Hosting";
                        $prodinvoicearray['relid'] = $serviceid;
                        $prodinvoicearray['taxed'] = $invoice_tax;
                        $prodinvoicearray['duedate'] = $hostingquerydates;
                        $prodinvoicearray['paymentmethod'] = $paymentmethod;

                        if (0 < $product_setup) {
                            $prodinvoicearray['description'] = $productname . " " . $_LANG['ordersetupfee'];
                            $prodinvoicearray['amount'] = $product_setup;
                            insert_query("tblinvoiceitems", $prodinvoicearray);
                            $prodinvoicearray['type'] = "";
                            $prodinvoicearray['relid'] = 0;
                        }


                        if (0 < $product_onetime) {
                            $prodinvoicearray['description'] = $invoice_description;
                            $prodinvoicearray['amount'] = $product_onetime;
                            insert_query("tblinvoiceitems", $prodinvoicearray);
                        }

                        $promovals = getInvoiceProductPromo($product_total_today_db, $promoid, $userid, $serviceid, $product_setup + $product_onetime);

                        if ($promovals['description']) {
                            $prodinvoicearray['type'] = "PromoHosting";
                            $prodinvoicearray['description'] = $promovals['description'];
                            $prodinvoicearray['amount'] = $promovals['amount'];
                            insert_query("tblinvoiceitems", $prodinvoicearray);
                        }
                    }

                    $adminemailitems .= $_LANG['orderproduct'] . (": " . $groupname . " - " . $productname . "<br>\r\n");

                    if ($description) {
                        $adminemailitems .= $_LANG['orderdescription'] . (": " . $description . "<br>\r\n");
                    }

                    foreach ($configurableoptions as $confkey => $value) {
                        $adminemailitems .= $value['optionname'] . ": " . $value['selectedname'] . "<br />\r\n";
                    }

                    foreach ($customfields as $customfield) {

                        if (!$customfield['adminonly']) {
                            $adminemailitems .= "" . $customfield['name'] . ": " . $customfield['value'] . "<br />\r\n";
                            continue;
                        }
                    }

                    $adminemailitems .= $_LANG['firstpaymentamount'] . ": " . formatCurrency($product_total_today_db) . "<br>\r\n";

                    if ($product_recurring_db) {
                        $adminemailitems .= $_LANG['recurringamount'] . ": " . formatCurrency($product_recurring_db) . "<br>\r\n";
                    }

                    $adminemailitems .= $_LANG['orderbillingcycle'] . ": " . $_LANG["orderpaymentterm" . str_replace(array("-", " "), "", strtolower($databasecycle))] . "<br>\r\n";

                    if ($allowqty && 1 < $qty) {
                        $adminemailitems .= $_LANG['quantity'] . (": " . $qty . "<br>\r\n") . $_LANG['invoicestotal'] . ": " . $productdata['pricing']['totaltoday'] . "<br>\r\n";
                    }

                    $adminemailitems .= "<br>\r\n";
                    ++$qtycount;
                }
            }

            $addonsarray = array();
            $addons = $productdata['addons'];

            if ($addons) {
                foreach ($addons as $addonid) {
                    $result = select_query_i("tbladdons", "name,description,billingcycle,tax", array("id" => $addonid));
                    $data = mysqli_fetch_array($result);
                    $addon_name = $data['name'];
                    $addon_description = $data['description'];
                    $addon_billingcycle = $data['billingcycle'];
                    $addon_tax = $data['tax'];

                    if (!$CONFIG['TaxEnabled']) {
                        $addon_tax = "";
                    }

                    $result = select_query_i("tblpricing", "msetupfee,monthly", array("type" => "addon", "currency" => $currency['id'], "relid" => $addonid));
                    $data = mysqli_fetch_array($result);
                    $addon_setupfee = $data['msetupfee'];
                    $addon_recurring = $data['monthly'];
                    $hookret = run_hook("OrderAddonPricingOverride", array("key" => $key, "pid" => $pid, "addonid" => $addonid, "proddata" => $productdata));
                    foreach ($hookret as $hookret2) {

                        if (is_array($hookret2)) {
                            if ($hookret2['setup']) {
                                $addon_setupfee = $hookret2['setup'];
                            }


                            if ($hookret2['recurring']) {
                                $addon_recurring = $hookret2['recurring'];
                                continue;
                            }

                            continue;
                        }
                    }

                    $addon_total_today_db = $addon_setupfee + $addon_recurring;
                    $addon_recurring_db = $addon_recurring;
                    $addon_total_today = $addon_total_today_db * 1;

                    if ($CONFIG['TaxInclusiveDeduct']) {
                        $addon_total_today_db = round($addon_total_today_db / $excltaxrate, 2);
                        $addon_recurring_db = round($addon_recurring_db / $excltaxrate, 2);
                    }


                    if ($promotioncode) {
                        $onetimediscount = $recurringdiscount = $promoid = 0;

                        if ($promocalc = CalcPromoDiscount("A" . $addonid, $addon_billingcycle, $addon_total_today_db, $addon_recurring_db, $addon_setupfee)) {
                            $onetimediscount = $promocalc['onetimediscount'];
                            $recurringdiscount = $promocalc['recurringdiscount'];
                            $addon_total_today_db -= $onetimediscount;
                            $addon_recurring_db -= $recurringdiscount;
                            $cart_discount += $onetimediscount * 1;
                        }
                    }


                    if ($checkout) {
                        $qtycount = 1;

                        while ($qtycount <= $qty) {
                            $serviceid = $multiqtyids[$qtycount];
                            $addonsetupfee = $addon_total_today_db - $addon_recurring_db;
                            $aid = insert_query("tblserviceaddons", array("hostingid" => $serviceid, "addonid" => $addonid, "orderid" => $orderid, "regdate" => "now()", "name" => "", "setupfee" => $addonsetupfee, "recurring" => $addon_recurring_db, "billingcycle" => $addon_billingcycle, "status" => "Pending", "nextduedate" => "now()", "nextinvoicedate" => "now()", "paymentmethod" => $paymentmethod, "tax" => $addon_tax));
                            $orderaddonids[] = $aid;
                            $adminemailitems .= $_LANG['clientareaaddon'] . (": " . $addon_name . "<br>\r\n") . $_LANG['ordersetupfee'] . ": " . formatCurrency($addonsetupfee) . "<br>\r\n";

                            if ($addon_recurring_db) {
                                $adminemailitems .= $_LANG['recurringamount'] . ": " . formatCurrency($addon_recurring_db) . "<br>\r\n";
                            }

                            $adminemailitems .= $_LANG['orderbillingcycle'] . ": " . $_LANG["orderpaymentterm" . str_replace(array("-", " "), "", strtolower($addon_billingcycle))] . "<br>\r\n<br>\r\n";
                            ++$qtycount;
                        }
                    }

                    $addon_total_today_db *= $qty;
                    $cart_total += $addon_total_today_db;
                    $addon_recurring_db *= $qty;

                    if ($addon_tax && !$clientsdetails['taxexempt']) {
                        $cart_tax += $addon_total_today_db;

                        if ($CONFIG['TaxType'] == "Exclusive") {
                            if ($CONFIG['TaxL2Compound']) {
                                $addon_recurring_db += $addon_recurring_db * $taxrate;
                                $addon_recurring_db += $addon_recurring_db * $taxrate2;
                            } else {
                                $addon_recurring_db += $addon_recurring_db * $taxrate + $addon_recurring_db * $taxrate2;
                            }
                        }
                    }

                    $addon_billingcycle = str_replace(array("-", " "), "", strtolower($addon_billingcycle));
                    $recurring_cycles_total[$addon_billingcycle] += $addon_recurring_db;

                    if ($addon_setupfee == "0" && $addon_recurring == "0") {
                        $pricing_text = $_LANG['orderfree'];
                    } else {
                        $pricing_text = formatCurrency($addon_recurring);

                        if ($addon_setupfee != "0.00") {
                            $pricing_text .= " + " . formatCurrency($addon_setupfee) . " " . $_LANG['ordersetupfee'];
                        }


                        if ($allowqty && 1 < $qty) {
                            $pricing_text .= $_LANG['invoiceqtyeach'] . "<br />" . $_LANG['invoicestotal'] . ": " . formatCurrency($addon_total_today);
                        }
                    }
                    $qty = 1;
                    $addonsarray[] = array("name" => $addon_name, "pricingtext" => $pricing_text, "setup" => formatCurrency($addon_setupfee), "recurring" => formatCurrency($addon_recurring), "totaltoday" => formatCurrency($addon_total_today));
                    $productdata['pricing']['setup'] += $addon_setupfee * $qty;
                    $productdata['pricing']['addons'] += $addon_recurring * $qty;
                    $productdata['pricing']['recurring'][$addon_billingcycle] += $addon_recurring * $qty;
                    $productdata['pricing']['totaltoday'] += $addon_total_today;
                }
            }

            $productdata['addons'] = $addonsarray;
            $totaltaxrates = 1;

            if (($CONFIG['TaxEnabled'] && $tax) && !$clientsdetails['taxexempt']) {
                $product_tax = $productdata['pricing']['totaltoday'];

                if ($CONFIG['TaxType'] == "Inclusive") {
                    $totaltaxrates = 1 + ($taxrate + $taxrate2);
                    $total_without_tax = $productdata['pricing']['totaltoday'] = $product_tax / $totaltaxrates;
                    $total_tax_1 = $total_without_tax * $taxrate;
                    $total_tax_2 = $total_without_tax * $taxrate2;
                } else {
                    $total_tax_1 = $product_tax * $taxrate;

                    if ($CONFIG['TaxL2Compound']) {
                        $total_tax_2 = ($product_tax + $total_tax_1) * $taxrate2;
                    } else {
                        $total_tax_2 = $product_tax * $taxrate2;
                    }
                }

                $total_tax_1 = round($total_tax_1, 2);
                $total_tax_2 = round($total_tax_2, 2);
                $productdata['pricing']['totaltoday'] += $total_tax_1 + $total_tax_2;

                if (0 < $total_tax_1) {
                    $productdata['pricing']['tax1'] = formatCurrency($total_tax_1);
                }


                if (0 < $total_tax_2) {
                    $productdata['pricing']['tax2'] = formatCurrency($total_tax_2);
                }
            }

            $productdata['pricing']['setup'] = formatCurrency($productdata['pricing']['setup']);
            foreach ($productdata['pricing']['recurring'] as $cycle => $recurring) {
                unset($productdata['pricing']['recurring'][$cycle]);

                if (0 < $recurring) {
                    $recurringwithtax = $recurring;

                    if ((($CONFIG['TaxEnabled'] && $tax) && !$clientsdetails['taxexempt']) && $CONFIG['TaxType'] == "Exclusive") {
                        $rectax = $recurringwithtax * $taxrate;

                        if ($CONFIG['TaxL2Compound']) {
                            $rectax += ($recurringwithtax + $rectax) * $taxrate2;
                        } else {
                            $rectax += $recurringwithtax * $taxrate2;
                        }

                        $recurringwithtax += $rectax;
                    }

                    $productdata['pricing']['recurring'][$_LANG["orderpaymentterm" . $cycle]] = formatCurrency($recurringwithtax);
                    $productdata['pricing']['recurringexcltax'][$_LANG["orderpaymentterm" . $cycle]] = formatCurrency($recurring / $totaltaxrates);
                    continue;
                }
            }


            if (0 < $productdata['pricing']['addons']) {
                $productdata['pricing']['addons'] = formatCurrency($productdata['pricing']['addons']);
            }

            $productdata['pricing']['totaltoday'] = formatCurrency($productdata['pricing']['totaltoday']);
            $productsarray[$key] = $productdata;
        }
    }

    $cartdata['products'] = $productsarray;
    $addonsarray = array();

    if (array_key_exists("addons", $_SESSION['cart']) && is_array($_SESSION['cart']['addons'])) {
        foreach ($_SESSION['cart']['addons'] as $key => $addon) {
            $addonid = $addon['id'];
            $serviceid = $addon['productid'];
            $result = select_query_i("tbladdons", "name,description,billingcycle,tax", array("id" => $addonid));
            $data = mysqli_fetch_array($result);
            $addon_name = $data['name'];
            $addon_description = $data['description'];
            $addon_billingcycle = $data['billingcycle'];
            $addon_tax = $data['tax'];

            if (!$CONFIG['TaxEnabled']) {
                $addon_tax = "";
            }

            $result = select_query_i("tblpricing", "msetupfee,monthly", array("type" => "addon", "currency" => $currency['id'], "relid" => $addonid));
            $data = mysqli_fetch_array($result);
            $addon_setupfee = $data['msetupfee'];
            $addon_recurring = $data['monthly'];
            $hookret = run_hook("OrderAddonPricingOverride", array("key" => $key, "addonid" => $addonid, "serviceid" => $serviceid));
            foreach ($hookret as $hookret2) {

                if (strlen($hookret2)) {
                    if ($hookret2['setup']) {
                        $addon_setupfee = $hookret2['setup'];
                    }


                    if ($hookret2['recurring']) {
                        $addon_recurring = $hookret2['recurring'];
                        continue;
                    }

                    continue;
                }
            }

            $addon_total_today_db = $addon_setupfee + $addon_recurring;
            $addon_recurring_db = $addon_recurring;

            if ($CONFIG['TaxInclusiveDeduct']) {
                $addon_total_today_db = round($addon_total_today_db / $excltaxrate, 2);
                $addon_recurring_db = round($addon_recurring_db / $excltaxrate, 2);
            }


            if ($promotioncode) {
                $onetimediscount = $recurringdiscount = $promoid = 0;

                if ($promocalc = CalcPromoDiscount("A" . $addonid, $addon_billingcycle, $addon_total_today_db, $addon_recurring_db, $addon_setupfee)) {
                    $onetimediscount = $promocalc['onetimediscount'];
                    $recurringdiscount = $promocalc['recurringdiscount'];
                    $addon_total_today_db -= $onetimediscount;
                    $addon_recurring_db -= $recurringdiscount;
                    $cart_discount += $onetimediscount;
                }
            }


            if ($checkout) {
                $addonsetupfee = $addon_total_today_db - $addon_recurring_db;
                $aid = insert_query("tblserviceaddons", array("hostingid" => $serviceid, "addonid" => $addonid, "orderid" => $orderid, "regdate" => "now()", "name" => "", "setupfee" => $addonsetupfee, "recurring" => $addon_recurring_db, "billingcycle" => $addon_billingcycle, "status" => "Pending", "nextduedate" => "now()", "nextinvoicedate" => "now()", "paymentmethod" => $paymentmethod, "tax" => $addon_tax));
                $orderaddonids[] = $aid;
                $adminemailitems .= $_LANG['clientareaaddon'] . (": " . $addon_name . "<br>\r\n") . $_LANG['ordersetupfee'] . ": " . formatCurrency($addonsetupfee) . "<br>\r\n";

                if ($addon_recurring_db) {
                    $adminemailitems .= $_LANG['recurringamount'] . ": " . formatCurrency($addon_recurring_db) . "<br>\r\n";
                }

                $adminemailitems .= $_LANG['orderbillingcycle'] . ": " . $_LANG["orderpaymentterm" . str_replace(array("-", " "), "", strtolower($addon_billingcycle))] . "<br>\r\n<br>\r\n";
            }

            $cart_total += $addon_total_today_db;

            if ($addon_tax && !$clientsdetails['taxexempt']) {
                $cart_tax += $addon_total_today_db;

                if ($CONFIG['TaxType'] == "Exclusive") {
                    if ($CONFIG['TaxL2Compound']) {
                        $addon_recurring_db += $addon_recurring_db * $taxrate;
                        $addon_recurring_db += $addon_recurring_db * $taxrate2;
                    } else {
                        $addon_recurring_db = $addon_recurring_db + $addon_recurring_db * $taxrate + $addon_recurring_db * $taxrate2;
                    }
                }
            }

            $addon_billingcycle = str_replace(array("-", " "), "", strtolower($addon_billingcycle));
            $recurring_cycles_total[$addon_billingcycle] += $addon_recurring_db;

            if ($addon_setupfee == "0" && $addon_recurring == "0") {
                $pricing_text = $_LANG['orderfree'];
            } else {
                $pricing_text = formatCurrency($addon_recurring);

                if ($addon_setupfee != "0.00") {
                    $pricing_text .= " + " . formatCurrency($addon_setupfee) . " " . $_LANG['ordersetupfee'];
                }
            }

            $result = select_query_i("tblcustomerservices", "tblservices.name,tblcustomerservices.description", array("tblcustomerservices.id" => $serviceid), "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");
            $data = mysqli_fetch_array($result);
            $productname = $data['name'];
            $descriptionname = $data['description'];
            $addonsarray[] = array("name" => $addon_name, "productname" => $productname, "descriptionname" => $descriptionname, "pricingtext" => $pricing_text);
        }

        $cartdata['addons'] = $addonsarray;
    }

    include ROOTDIR . "/includes/additionaldescriptionfields.php";
    $totaldescriptionprice = 0;

    if (array_key_exists("descriptions", $_SESSION['cart']) && is_array($_SESSION['cart']['descriptions'])) {
        $result = select_query_i("tblpricing", "", array("type" => "descriptionaddons", "currency" => $currency['id'], "relid" => 0));
        $data = mysqli_fetch_array($result);
        $descriptiondnsmanagementprice = $data['msetupfee'];
        $descriptionemailforwardingprice = $data['qsetupfee'];
        $descriptionidprotectionprice = $data['ssetupfee'];
        foreach ($_SESSION['cart']['descriptions'] as $key => $description) {
            $descriptiontype = $description['type'];
            $descriptionname = $description['description'];
            $regperiod = $description['regperiod'];
            $descriptionparts = explode(".", $descriptionname, 2);
            $sld = $descriptionparts[0];
            $tld = $descriptionparts[1];
            $temppricelist = getTLDPriceList("." . $tld);

            if (!isset($temppricelist[$regperiod][$descriptiontype])) {
                $tldyears = array_keys($temppricelist);
                $regperiod = $tldyears[0];
            }


            if (!isset($temppricelist[$regperiod][$descriptiontype])) {
                exit("Invalid TLD/Registration Period Supplied for Domain Registration");
            }


            if (array_key_exists($descriptionname, $freedescriptions)) {
                $tldyears = array_keys($temppricelist);
                $regperiod = $tldyears[0];
                $descriptionprice = "0.00";
                $renewprice = ($freedescriptions[$descriptionname] == "once" ? $temppricelist[$regperiod]['renew'] : $renewprice = "0.00");
            } else {
                $descriptionprice = $temppricelist[$regperiod][$descriptiontype];
                $renewprice = $temppricelist[$regperiod]['renew'];
            }

            $before_priceoverride_value = "";

            if ($bundleoverride = bundlesGetProductPriceOverride("description", $key)) {
                $before_priceoverride_value = $descriptionprice;
                $descriptionprice = $renewprice = $bundleoverride;
            }

            $hookret = run_hook("OrderDomainPricingOverride", array("type" => $descriptiontype, "description" => $descriptionname, "regperiod" => $regperiod, "dnsmanagement" => $description['dnsmanagement'], "emailforwarding" => $description['emailforwarding'], "idprotection" => $description['idprotection'], "eppcode" => html_entity_decode($description['eppcode'])));
            foreach ($hookret as $hookret2) {

                if (strlen($hookret2)) {
                    $before_priceoverride_value = $descriptionprice;
                    $descriptionprice = $hookret2;
                    continue;
                }
            }


            if ($description['dnsmanagement']) {
                $dnsmanagement = true;
                $descriptionprice += $descriptiondnsmanagementprice * $regperiod;
                $renewprice += $descriptiondnsmanagementprice * $regperiod;

                if (strlen($before_priceoverride_value)) {
                    $before_priceoverride_value += $descriptiondnsmanagementprice * $regperiod;
                }
            } else {
                $dnsmanagement = false;
            }


            if ($description['emailforwarding']) {
                $emailforwarding = true;
                $descriptionprice += $descriptionemailforwardingprice * $regperiod;
                $renewprice += $descriptionemailforwardingprice * $regperiod;

                if (strlen($before_priceoverride_value)) {
                    $before_priceoverride_value += $descriptionemailforwardingprice * $regperiod;
                }
            } else {
                $emailforwarding = false;
            }


            if ($description['idprotection']) {
                $idprotection = true;
                $descriptionprice += $descriptionidprotectionprice * $regperiod;
                $renewprice += $descriptionidprotectionprice * $regperiod;

                if (strlen($before_priceoverride_value)) {
                    $before_priceoverride_value += $descriptionidprotectionprice * $regperiod;
                }
            } else {
                $idprotection = false;
            }


            if ($CONFIG['TaxInclusiveDeduct']) {
                $descriptionprice = round($descriptionprice / $excltaxrate, 2);
                $renewprice = round($renewprice / $excltaxrate, 2);
            }

            $description_price_db = $descriptionprice;
            $description_renew_price_db = $renewprice;

            if ($promotioncode) {
                $onetimediscount = $recurringdiscount = $promoid = 0;

                if ($promocalc = CalcPromoDiscount("D." . $tld, $regperiod . "Years", $description_price_db, $description_renew_price_db)) {
                    $onetimediscount = $promocalc['onetimediscount'];
                    $recurringdiscount = $promocalc['recurringdiscount'];
                    $description_price_db -= $onetimediscount;
                    $description_renew_price_db -= $recurringdiscount;
                    $cart_discount += $onetimediscount;
                    $promoid = $promo_data['id'];
                }
            }


            if ($regperiod == "1") {
                $description_billing_cycle = "annually";
            } else {
                if ($regperiod == "2") {
                    $description_billing_cycle = "biennially";
                } else {
                    if ($regperiod == "3") {
                        $description_billing_cycle = "triennially";
                    }
                }
            }

            $recurring_cycles_total[$description_billing_cycle] += $description_renew_price_db;

            if ((($CONFIG['TaxEnabled'] && $CONFIG['TaxDomains']) && $CONFIG['TaxType'] == "Exclusive") && !$clientsdetails['taxexempt']) {
                if ($CONFIG['TaxL2Compound']) {
                    $recurring_cycles_total[$description_billing_cycle] += $description_renew_price_db * $taxrate + ($description_renew_price_db + $description_renew_price_db * $taxrate) * $taxrate2;
                } else {
                    $recurring_cycles_total[$description_billing_cycle] += $description_renew_price_db * $taxrate + $description_renew_price_db * $taxrate2;
                }
            }


            if ($checkout) {
                $donotrenew = ($CONFIG['DomainAutoRenewDefault'] ? "" : "on");
                $descriptionid = insert_query("tbldescriptions", array("userid" => $userid, "orderid" => $orderid, "type" => $descriptiontype, "registrationdate" => "now()", "description" => $descriptionname, "firstpaymentamount" => $description_price_db, "recurringamount" => $description_renew_price_db, "registrationperiod" => $regperiod, "status" => "Pending", "paymentmethod" => $paymentmethod, "expirydate" => "00000000", "nextduedate" => "now()", "nextinvoicedate" => "now()", "dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection, "donotrenew" => $donotrenew, "promoid" => $promoid));
                $orderdescriptionids[] = $descriptionid;
                $adminemailitems .= $_LANG['orderdescriptionregistration'] . ": " . ucfirst($descriptiontype) . "<br>\r\n" . $_LANG['orderdescription'] . (": " . $descriptionname . "<br>\r\n") . $_LANG['firstpaymentamount'] . ": " . formatCurrency($description_price_db) . "<br>\r\n" . $_LANG['recurringamount'] . ": " . formatCurrency($description_renew_price_db) . "<br>\r\n" . $_LANG['orderregperiod'] . (": " . $regperiod . " ") . $_LANG['orderyears'] . "<br>\r\n";

                if ($dnsmanagement) {
                    $adminemailitems .= " + " . $_LANG['descriptiondnsmanagement'] . "<br>\r\n";
                }


                if ($emailforwarding) {
                    $adminemailitems .= " + " . $_LANG['descriptionemailforwarding'] . "<br>\r\n";
                }


                if ($idprotection) {
                    $adminemailitems .= " + " . $_LANG['descriptionidprotection'] . "<br>\r\n";
                }

                $adminemailitems .= "<br>\r\n";

                if ($descriptiontype == "register") {
                    unset($tempdescriptionfields);
                    $tempdescriptionfields = $additionaldescriptionfields["." . $tld];

                    if ($tempdescriptionfields) {
                        foreach ($tempdescriptionfields as $fieldkey => $value) {
                            $storedvalue = $description['fields'][$fieldkey];
                            insert_query("tbldescriptionsadditionalfields", array("descriptionid" => $descriptionid, "name" => $value['Name'], "value" => $storedvalue));
                        }
                    }
                }


                if ($descriptiontype == "transfer" && $description['eppcode']) {
                    $descriptioneppcodes[$descriptionname] = html_entity_decode($description['eppcode']);
                }
            }

            $pricing_text = "";

            if (strlen($before_priceoverride_value)) {
                $pricing_text .= "<strike>" . formatCurrency($before_priceoverride_value) . "</strike> ";
            }

            $pricing_text .= formatCurrency($descriptionprice);
            $tempdescriptions[$key] = array("type" => $descriptiontype, "description" => $descriptionname, "regperiod" => $regperiod, "price" => $pricing_text, "renewprice" => formatCurrency($renewprice), "dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection, "eppvalue" => $description['eppcode']);
            $totaldescriptionprice += $description_price_db;
        }
    }

    $cartdata['descriptions'] = $tempdescriptions;
    $cart_total += $totaldescriptionprice;

    if ($CONFIG['TaxDomains']) {
        $cart_tax += $totaldescriptionprice;
    }

    $orderrenewals = "";

    if (array_key_exists("renewals", $_SESSION['cart']) && is_array($_SESSION['cart']['renewals'])) {
        $result = select_query_i("tblpricing", "", array("type" => "descriptionaddons", "currency" => $currency['id'], "relid" => 0));
        $data = mysqli_fetch_array($result);
        $descriptiondnsmanagementprice = $data['msetupfee'];
        $descriptionemailforwardingprice = $data['qsetupfee'];
        $descriptionidprotectionprice = $data['ssetupfee'];
        foreach ($_SESSION['cart']['renewals'] as $descriptionid => $regperiod) {
            $result = select_query_i("tbldescriptions", "", array("id" => $descriptionid));
            $data = mysqli_fetch_array($result);
            $descriptionname = $data['description'];
            $expirydate = $data['expirydate'];

            if ($expirydate == "0000-00-00") {
                $expirydate = $data['nextduedate'];
            }

            $dnsmanagement = $data['dnsmanagement'];
            $emailforwarding = $data['emailforwarding'];
            $idprotection = $data['idprotection'];
            $descriptionparts = explode(".", $descriptionname, 2);
            $sld = $descriptionparts[0];
            $tld = "." . $descriptionparts[1];
            $temppricelist = getTLDPriceList($tld, "", true);

            if (!isset($temppricelist[$regperiod]['renew'])) {
                exit("Invalid TLD/Registration Period Supplied for Domain Renewal");
            }

            $renewprice = $temppricelist[$regperiod]['renew'];

            if ($dnsmanagement) {
                $renewprice += $descriptiondnsmanagementprice * $regperiod;
            }


            if ($emailforwarding) {
                $renewprice += $descriptionemailforwardingprice * $regperiod;
            }


            if ($idprotection) {
                $renewprice += $descriptionidprotectionprice * $regperiod;
            }


            if ($CONFIG['TaxInclusiveDeduct']) {
                $renewprice = round($renewprice / $excltaxrate, 2);
            }

            $description_renew_price_db = $renewprice;

            if ($promotioncode) {
                $onetimediscount = $recurringdiscount = $promoid = 0;

                if ($promocalc = CalcPromoDiscount("D" . $tld, $regperiod . "Years", $description_renew_price_db, $description_renew_price_db)) {
                    $onetimediscount = $promocalc['onetimediscount'];
                    $description_renew_price_db -= $onetimediscount;
                    $cart_discount += $onetimediscount;
                }
            }

            $cart_total += $description_renew_price_db;

            if ($CONFIG['TaxDomains']) {
                $cart_tax += $description_renew_price_db;
            }


            if ($checkout) {
                $description_renew_price_db = format_as_currency($description_renew_price_db);
                $orderrenewalids[] = $descriptionid;
                $orderrenewals .= "" . $descriptionid . "=" . $regperiod . ",";
                $adminemailitems .= $_LANG['descriptionrenewal'] . (": " . $descriptionname . " - " . $regperiod . " ") . $_LANG['orderyears'] . "<br>\r\n";
                $descriptiondesc = $_LANG['descriptionrenewal'] . (" - " . $descriptionname . " - " . $regperiod . " ") . $_LANG['orderyears'] . " (" . fromMySQLDate($expirydate) . " - " . fromMySQLDate(getInvoicePayUntilDate($expirydate, $regperiod)) . ")";

                if ($dnsmanagement) {
                    $adminemailitems .= " + " . $_LANG['descriptiondnsmanagement'] . "<br>\r\n";
                    $descriptiondesc .= "\r\n + " . $_LANG['descriptiondnsmanagement'];
                }


                if ($emailforwarding) {
                    $adminemailitems .= " + " . $_LANG['descriptionemailforwarding'] . "<br>\r\n";
                    $descriptiondesc .= "\r\n + " . $_LANG['descriptionemailforwarding'];
                }


                if ($idprotection) {
                    $adminemailitems .= " + " . $_LANG['descriptionidprotection'] . "<br>\r\n";
                    $descriptiondesc .= "\r\n + " . $_LANG['descriptionidprotection'];
                }

                $adminemailitems .= "<br>\r\n";
                $tax = ($CONFIG['TaxDomains'] ? "1" : "0");
                update_query("tbldescriptions", array("registrationperiod" => $regperiod, "recurringamount" => $description_renew_price_db), array("id" => $descriptionid));
                insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Domain", "relid" => $descriptionid, "description" => $descriptiondesc, "amount" => $description_renew_price_db, "taxed" => $tax, "duedate" => "now()", "paymentmethod" => $paymentmethod));
                $result = select_query_i("tblinvoiceitems", "tblinvoiceitems.id,tblinvoiceitems.invoiceid", array("type" => "Domain", "relid" => $descriptionid, "status" => "Unpaid", "tblinvoices.userid" => $_SESSION['uid']), "", "", "", "tblinvoices ON tblinvoices.id=tblinvoiceitems.invoiceid");

                while ($data = mysqli_fetch_array($result)) {
                    $itemid = $data['id'];
                    $invoiceid = $data['invoiceid'];
                    $result2 = select_query_i("tblinvoiceitems", "COUNT(*)", array("invoiceid" => $invoiceid));
                    $data = mysqli_fetch_array($result2);
                    $itemcount = $data[0];

                    if ($itemcount == 1) {
                        update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invoiceid));
                        logActivity("Cancelled Previous Domain Renewal Invoice - Invoice ID: " . $invoiceid . " - Domain: " . $descriptionname);
                    }

                    delete_query("tblinvoiceitems", array("id" => $itemid));
                    updateInvoiceTotal($invoiceid);
                    logActivity("Removed Previous Domain Renewal Line Item - Invoice ID: " . $invoiceid . " - Domain: " . $descriptionname);
                }
            }

            $cartdata['renewals'][$descriptionid] = array("description" => $descriptionname, "regperiod" => $regperiod, "price" => formatCurrency($renewprice), "dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection);
        }
    }

    $cart_adjustments = 0;
    $adjustments = run_hook("CartTotalAdjustment", $_SESSION['cart']);
    foreach ($adjustments as $k => $adjvals) {

        if ($checkout) {
            insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "", "relid" => "", "description" => $adjvals['description'], "amount" => $adjvals['amount'], "taxed" => $adjvals['taxed'], "duedate" => "now()", "paymentmethod" => $paymentmethod));
        }

        $adjustments[$k]['amount'] = formatCurrency($adjvals['amount']);
        $cart_adjustments += $adjvals['amount'];

        if ($adjvals['taxed']) {
            $cart_tax += $adjvals['amount'];
            continue;
        }
    }


    if ($CONFIG['TaxEnabled'] && !$clientsdetails['taxexempt']) {
        if ($CONFIG['TaxType'] == "Inclusive") {
            $totaltaxrates = 1 + ($taxrate + $taxrate2);
            $total_without_tax = $cart_tax / $totaltaxrates;
            $total_tax_1 = $total_without_tax * $taxrate;
            $total_tax_2 = $total_without_tax * $taxrate2;
        } else {
            $total_tax_1 = $cart_tax * $taxrate;

            if ($CONFIG['TaxL2Compound']) {
                $total_tax_2 = ($cart_tax + $total_tax_1) * $taxrate2;
            } else {
                $total_tax_2 = $cart_tax * $taxrate2;
            }
        }

        $total_tax_1 = round($total_tax_1, 2);
        $total_tax_2 = round($total_tax_2, 2);

        if ($CONFIG['TaxType'] == "Inclusive") {
            $cart_total -= $total_tax_1 + $total_tax_2;
        }
    } else {
        $total_tax_1 = $total_tax_2 = 0;
    }

    $cart_subtotal = $cart_total + $cart_discount;
    $cart_total += $total_tax_1 + $total_tax_2 + $cart_adjustments;
    $cart_subtotal = format_as_currency($cart_subtotal);
    $cart_discount = format_as_currency($cart_discount);
    $cart_adjustments = format_as_currency($cart_adjustments);
    $total_tax_1 = format_as_currency($total_tax_1);
    $total_tax_2 = format_as_currency($total_tax_2);
    $cart_total = format_as_currency($cart_total);

    if ($checkout) {
        $adminemailitems .= $_LANG['ordertotalduetoday'] . ": " . formatCurrency($cart_total);

        if ($promotioncode && $promo_data['promoapplied']) {
            update_query("tblpromotions", array("uses" => "+1"), array("code" => $promotioncode));
            $promo_recurring = ($promo_data['recurring'] ? "Recurring" : "One Time");
            update_query("tblorders", array("promocode" => $promo_data['code'], "promotype" => $promo_recurring . " " . $promo_data['type'], "promovalue" => $promo_data['value']), array("id" => $orderid));
        }


        if ($_SESSION['cart']['ns1'] && $_SESSION['cart']['ns1']) {
            $ordernameservers = $_SESSION['cart']['ns1'] . "," . $_SESSION['cart']['ns2'];

            if ($_SESSION['cart']['ns3']) {
                $ordernameservers .= "," . $_SESSION['cart']['ns3'];
            }


            if ($_SESSION['cart']['ns4']) {
                $ordernameservers .= "," . $_SESSION['cart']['ns4'];
            }


            if ($_SESSION['cart']['ns5']) {
                $ordernameservers .= "," . $_SESSION['cart']['ns5'];
            }
        }

        $descriptioneppcodes = (count($descriptioneppcodes) ? serialize($descriptioneppcodes) : "");
        $orderdata = array();

        if (is_array($_SESSION['cart']['bundle'])) {
            foreach ($_SESSION['cart']['bundle'] as $bvals) {
                $orderdata['bundleids'][] = $bvals['bid'];
            }
        }

        update_query("tblorders", array("amount" => $cart_total, "nameservers" => $ordernameservers, "transfersecret" => $descriptioneppcodes, "renewals" => substr($orderrenewals, 0, 0 - 1), "orderdata" => serialize($orderdata)), array("id" => $orderid));
        $invoiceid = 0;

        mail("peter@hd.net.nz", 'invoice', print_r($_SESSION['cart'], 1));
        if (!$_SESSION['cart']['geninvoicedisabled']) {
            if (!$userid) {
                exit("An Error Occurred");
            }

            $invoiceid = createInvoices($userid, true, "", array("products" => $orderproductids, "addons" => $orderaddonids, "descriptions" => $orderdescriptionids));

            if ($CONFIG['OrderDaysGrace']) {
                $new_time = mktime(0, 0, 0, date("m"), date("d") + $CONFIG['OrderDaysGrace'], date("Y"));
                $duedate = date("Y-m-d", $new_time);
                update_query("tblinvoices", array("duedate" => $duedate), array("id" => $invoiceid));
            }


            if (!$CONFIG['NoInvoiceEmailOnOrder']) {
                sendMessage("Invoice Created", $invoiceid);
            }
        }


        if ($invoiceid) {
            update_query("tblorders", array("invoiceid" => $invoiceid), array("id" => $orderid));
            $result = select_query_i("tblinvoices", "status", array("id" => $invoiceid));
            $data = mysqli_fetch_array($result);
            $status = $data['status'];

            if ($status == "Paid") {
                $invoiceid = "";
            }
        }


        if (!$_SESSION['adminid']) {
            if (isset($_COOKIE['RAAffiliateID'])) {
                $result = select_query_i("tblaffiliates", "clientid", array("id" => (int) $_COOKIE['RAAffiliateID']));
                $data = mysqli_fetch_array($result);
                $clientid = $data['clientid'];

                if ($clientid && $_SESSION['uid'] != $clientid) {
                    foreach ($orderproductids as $orderproductid) {
                        insert_query("tblaffiliatesaccounts", array("affiliateid" => (int) $_COOKIE['RAAffiliateID'], "relid" => $orderproductid));
                    }
                }
            }


            if (isset($_COOKIE['RALinkID'])) {
                update_query("tbllinks", array("conversions" => "+1"), array("id" => $_COOKIE['RALinkID']));
            }
        }

        $result = select_query_i("tblclients", "firstname, lastname, companyname, email, address1, address2, city, state, postcode, country, phonenumber, ip, host", array("id" => $userid));
        $data = mysqli_fetch_array($result);
        list($firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $ip, $host) = $data;
        $customfields = getCustomFields("client", "", $userid, "", true);
        $clientcustomfields = "";
        foreach ($customfields as $customfield) {
            $clientcustomfields .= "" . $customfield['name'] . ": " . $customfield['value'] . "<br />\r\n";
        }

        $result = select_query_i("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "name"));
        $data = mysqli_fetch_array($result);
        $nicegatewayname = $data['value'];
        sendAdminMessage("New Order Notification", array("order_id" => $orderid, "order_number" => $order_number, "order_date" => fromMySQLDate(date("Y-m-d H:i:s"), true), "invoice_id" => $invoiceid, "order_payment_method" => $nicegatewayname, "order_total" => formatCurrency($cart_total), "client_id" => $userid, "client_first_name" => $firstname, "client_last_name" => $lastname, "client_email" => $email, "client_company_name" => $companyname, "client_address1" => $address1, "client_address2" => $address2, "client_city" => $city, "client_state" => $state, "client_postcode" => $postcode, "client_country" => $country, "client_phonenumber" => $phonenumber, "client_customfields" => $clientcustomfields, "order_items" => $adminemailitems, "order_notes" => nl2br($ordernotes), "client_ip" => $ip, "client_hostname" => $host), "account");

        if (!$_SESSION['cart']['orderconfdisabled']) {
            sendMessage("Order Confirmation", $userid, array("order_id" => $orderid, "order_number" => $order_number, "order_details" => $adminemailitems));
        }

        $_SESSION['cart'] = array();
        $_SESSION['orderdetails'] = array("OrderID" => $orderid, "OrderNumber" => $order_number, "ServiceIDs" => $orderproductids, "DomainIDs" => $orderdescriptionids, "AddonIDs" => $orderaddonids, "RenewalIDs" => $orderrenewalids, "PaymentMethod" => $paymentmethod, "InvoiceID" => $invoiceid, "TotalDue" => $cart_total, "Products" => $orderproductids, "Domains" => $orderdescriptionids, "Addons" => $orderaddonids, "Renewals" => $orderrenewalids);
        run_hook("AfterShoppingCartCheckout", $_SESSION['orderdetails']);
    }

    $total_recurringmonthly = ($recurring_cycles_total['monthly'] <= 0 ? "" : formatCurrency($recurring_cycles_total['monthly']));
    $total_recurringquarterly = ($recurring_cycles_total['quarterly'] <= 0 ? "" : formatCurrency($recurring_cycles_total['quarterly']));
    $total_recurringsemiannually = ($recurring_cycles_total['semiannually'] <= 0 ? "" : formatCurrency($recurring_cycles_total['semiannually']));
    $total_recurringannually = ($recurring_cycles_total['annually'] <= 0 ? "" : formatCurrency($recurring_cycles_total['annually']));
    $total_recurringbiennially = ($recurring_cycles_total['biennially'] <= 0 ? "" : formatCurrency($recurring_cycles_total['biennially']));
    $total_recurringtriennially = ($recurring_cycles_total['triennially'] <= 0 ? "" : formatCurrency($recurring_cycles_total['triennially']));
    $cartdata['bundlewarnings'] = $bundlewarnings;
    $cartdata['rawdiscount'] = $cart_discount;
    $cartdata['subtotal'] = formatCurrency($cart_subtotal);
    $cartdata['discount'] = formatCurrency($cart_discount);
    $cartdata['promotype'] = $promo_data['type'];
    $cartdata['promovalue'] = (($promo_data['type'] == "Fixed Amount" || $promo_data['type'] == "Price Override") ? formatCurrency($promo_data['value']) : round($promo_data['value'], 2));
    $cartdata['promorecurring'] = ($promo_data['recurring'] ? $_LANG['recurring'] : $_LANG['orderpaymenttermonetime']);
    $cartdata['taxrate'] = $rawtaxrate;
    $cartdata['taxrate2'] = $rawtaxrate2;
    $cartdata['taxname'] = $taxname;
    $cartdata['taxname2'] = $taxname2;
    $cartdata['taxtotal'] = formatCurrency($total_tax_1);
    $cartdata['taxtotal2'] = formatCurrency($total_tax_2);
    $cartdata['adjustments'] = $adjustments;
    $cartdata['adjustmentstotal'] = formatCurrency($cart_adjustments);
    $cartdata['rawtotal'] = $cart_total;
    $cartdata['total'] = formatCurrency($cart_total);
    $cartdata['totalrecurringmonthly'] = $total_recurringmonthly;
    $cartdata['totalrecurringquarterly'] = $total_recurringquarterly;
    $cartdata['totalrecurringsemiannually'] = $total_recurringsemiannually;
    $cartdata['totalrecurringannually'] = $total_recurringannually;
    $cartdata['totalrecurringbiennially'] = $total_recurringbiennially;
    $cartdata['totalrecurringtriennially'] = $total_recurringtriennially;
    return $cartdata;
}

function SetPromoCode($promotioncode) {
    global $_LANG;

    $_SESSION['cart']['promo'] = "";
    $result = select_query_i("tblpromotions", "", array("code" => $promotioncode));
    $data = mysqli_fetch_array($result);
    $id = $data['id'];
    $maxuses = $data['maxuses'];
    $uses = $data['uses'];
    $startdate = $data['startdate'];
    $expiredate = $data['expirationdate'];
    $newsignups = $data['newsignups'];
    $existingclient = $data['existingclient'];
    $onceperclient = $data['onceperclient'];

    if (!$id) {
        $promoerrormessage = $_LANG['ordercodenotfound'];
        return $promoerrormessage;
    }


    if ($startdate != "0000-00-00") {
        $startdate = str_replace("-", "", $startdate);

        if (date("Ymd") < $startdate) {
            $promoerrormessage = $_LANG['orderpromoprestart'];
            return $promoerrormessage;
        }
    }


    if ($expiredate != "0000-00-00") {
        $expiredate = str_replace("-", "", $expiredate);

        if ($expiredate < date("Ymd")) {
            $promoerrormessage = $_LANG['orderpromoexpired'];
            return $promoerrormessage;
        }
    }


    if (0 < $maxuses) {
        if ($maxuses <= $uses) {
            $promoerrormessage = $_LANG['orderpromomaxusesreached'];
            return $promoerrormessage;
        }
    }


    if ($newsignups && $_SESSION['uid']) {
        $result = select_query_i("tblorders", "COUNT(*)", array("userid" => $_SESSION['uid']));
        $data = mysqli_fetch_array($result);
        $previousorders = $data[0];

        if (0 < $previousorders) {
            $promoerrormessage = $_LANG['promonewsignupsonly'];
            return $promoerrormessage;
        }
    }


    if ($existingclient) {
        if ($_SESSION['uid']) {
            $result = select_query_i("tblorders", "count(*)", array("status" => "Active", "userid" => $_SESSION['uid']));
            $orderCount = mysqli_fetch_array($result);

            if ($orderCount[0] == 0) {
                $promoerrormessage = $_LANG['promoexistingclient'];
                return $promoerrormessage;
            }
        } else {
            $promoerrormessage = $_LANG['promoexistingclient'];
            return $promoerrormessage;
        }
    }


    if ($onceperclient) {
        if ($_SESSION['uid']) {
            $result = select_query_i("tblorders", "count(*)", "promocode='" . db_escape_string($promotioncode) . "' AND userid=" . (int) $_SESSION['uid'] . " AND status IN ('Pending','Active')");
            $orderCount = mysqli_fetch_array($result);

            if (0 < $orderCount[0]) {
                $promoerrormessage = $_LANG['promoonceperclient'];
                return $promoerrormessage;
            }
        }
    }

    $_SESSION['cart']['promo'] = $promotioncode;
}

function CalcPromoDiscount($pid, $cycle, $fpamount, $recamount, $setupfee = 0) {
    global $promo_data;
    global $currency;

    $id = $promo_data['id'];
    $promotioncode = $promo_data['code'];

    if (!$id) {
        return false;
    }


    if ($_SESSION['adminid'] && !defined("CLIENTAREA")) {
        
    } else {
        $newsignups = $promo_data['newsignups'];

        if ($newsignups && $_SESSION['uid']) {
            $result = select_query_i("tblorders", "COUNT(*)", array("userid" => $_SESSION['uid']));
            $data = mysqli_fetch_array($result);
            $previousorders = $data[0];

            if (2 <= $previousorders) {
                return false;
            }
        }

        $existingclient = $promo_data['existingclient'];
        $onceperclient = $promo_data['onceperclient'];

        if ($existingclient) {
            $result = select_query_i("tblorders", "count(*)", array("status" => "Active", "userid" => $_SESSION['uid']));
            $orderCount = mysqli_fetch_array($result);

            if ($orderCount[0] < 1) {
                return false;
            }
        }


        if ($onceperclient) {
            $result = select_query_i("tblorders", "count(*)", "promocode='" . db_escape_string($promotioncode) . "' AND userid=" . (int) $_SESSION['uid'] . " AND status IN ('Pending','Active')");
            $orderCount = mysqli_fetch_array($result);

            if (0 < $orderCount[0]) {
                return false;
            }
        }

        $applyonce = $promo_data['applyonce'];
        $promoapplied = $promo_data['promoapplied'];

        if ($applyonce && $promoapplied) {
            return false;
        }

        $appliesto = explode(",", $promo_data['appliesto']);

        if (!in_array($pid, $appliesto)) {
            return false;
        }

        $expiredate = $promo_data['expirationdate'];

        if ($expiredate != "0000-00-00") {
            $year = substr($expiredate, 0, 4);
            $month = substr($expiredate, 5, 2);
            $day = substr($expiredate, 8, 2);
            $validuntil = $year . $month . $day;
            $dayofmonth = date("d");
            $monthnum = date("m");
            $yearnum = date("Y");
            $todaysdate = $yearnum . $monthnum . $dayofmonth;

            if ($validuntil < $todaysdate) {
                return false;
            }
        }

        $cycles = $promo_data['cycles'];

        if ($cycles) {
            $cycles = explode(",", $cycles);

            if (!in_array($cycle, $cycles)) {
                return false;
            }
        }

        $maxuses = $promo_data['maxuses'];

        if ($maxuses) {
            $uses = $promo_data['uses'];

            if ($maxuses <= $uses) {
                return false;
            }
        }

        $requires = $promo_data['requires'];
        $requiresexisting = $promo_data['requiresexisting'];

        if ($requires) {
            $requires = explode(",", $requires);
            $hasrequired = false;

            if (is_array($_SESSION['cart']['products'])) {
                foreach ($_SESSION['cart']['products'] as $values) {

                    if (in_array($values['pid'], $requires)) {
                        $hasrequired = true;
                    }


                    if (is_array($values['addons'])) {
                        foreach ($values['addons'] as $addonid) {

                            if (in_array("A" . $addonid, $requires)) {
                                $hasrequired = true;
                                continue;
                            }
                        }

                        continue;
                    }
                }
            }


            if (is_array($_SESSION['cart']['addons'])) {
                foreach ($_SESSION['cart']['addons'] as $values) {

                    if (in_array("A" . $values['id'], $requires)) {
                        $hasrequired = true;
                        continue;
                    }
                }
            }


            if (is_array($_SESSION['cart']['descriptions'])) {
                foreach ($_SESSION['cart']['descriptions'] as $values) {
                    $descriptionparts = explode(".", $values['description'], 2);
                    $tld = $descriptionparts[1];

                    if (in_array("D." . $tld, $requires)) {
                        $hasrequired = true;
                        continue;
                    }
                }
            }


            if (!$hasrequired && $requiresexisting) {
                $requiredproducts = $requiredaddons = array();
                $requireddescriptions = "";
                foreach ($requires as $v) {

                    if (substr($v, 0, 1) == "A") {
                        $requiredaddons[] = substr($v, 1);
                        continue;
                    }


                    if (substr($v, 0, 1) == "D") {
                        $requireddescriptions .= "description LIKE '%" . substr($v, 1) . "' OR ";
                        continue;
                    }

                    $requiredproducts[] = $v;
                }


                if (count($requiredproducts)) {
                    $result = select_query_i("tblcustomerservices", "COUNT(*)", "userid='" . (int) $_SESSION['uid'] . "' AND packageid IN (" . db_build_in_array($requiredproducts) . ") AND servicestatus='Active'");
                    $data = mysqli_fetch_array($result);

                    if ($data[0]) {
                        $hasrequired = true;
                    }
                }


                if (count($requiredaddons)) {
                    $result = select_query_i("tblserviceaddons", "COUNT(*)", "tblcustomerservices.userid='" . (int) $_SESSION['uid'] . "' AND addonid IN (" . db_build_in_array($requiredaddons) . ") AND status='Active'", "", "", "", "tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid");
                    $data = mysqli_fetch_array($result);

                    if ($data[0]) {
                        $hasrequired = true;
                    }
                }


                if ($requireddescriptions) {
                    $result = select_query_i("tbldescriptions", "COUNT(*)", "userid='" . (int) $_SESSION['uid'] . "' AND status='Active' AND (" . substr($requireddescriptions, 0, 0 - 4) . ")");
                    $data = mysqli_fetch_array($result);

                    if ($data[0]) {
                        $hasrequired = true;
                    }
                }
            }


            if (!$hasrequired) {
                return false;
            }
        }
    }

    $type = $promo_data['type'];
    $value = $promo_data['value'];
    $onetimediscount = 0;

    if ($type == "Percentage") {
        $onetimediscount = $fpamount * ($value / 100);
    } else {
        if ($type == "Fixed Amount") {
            if ($currency['id'] != 1) {
                $promo_data['value'] = $value = convertCurrency($value, 1, $currency['id']);
            }


            if ($fpamount < $value) {
                $onetimediscount = $fpamount;
            } else {
                $onetimediscount = $value;
            }
        } else {
            if ($type == "Price Override") {
                if ($currency['id'] != 1) {
                    $promo_data['value'] = convertCurrency($promo_data['value'], 1, $currency['id']);
                }


                if (!isset($promo_data['priceoverride'])) {
                    $promo_data['priceoverride'] = $promo_data['value'];
                }

                $onetimediscount = $fpamount - $promo_data['priceoverride'];
            } else {
                if ($type == "Free Setup") {
                    $onetimediscount = $setupfee;
                    $promo_data['value'] += $setupfee;
                }
            }
        }
    }

    $recurringdiscount = 0;
    $recurring = $promo_data['recurring'];

    if ($recurring) {
        if ($type == "Percentage") {
            $recurringdiscount = $recamount * ($value / 100);
        } else {
            if ($type == "Fixed Amount") {
                if ($recamount < $value) {
                    $recurringdiscount = $recamount;
                } else {
                    $recurringdiscount = $value;
                }
            } else {
                if ($type == "Price Override") {
                    $recurringdiscount = $recamount - $promo_data['priceoverride'];
                }
            }
        }
    }

    $onetimediscount = round($onetimediscount, 2);
    $recurringdiscount = round($recurringdiscount, 2);
    $promo_data['promoapplied'] = true;
    return array("onetimediscount" => $onetimediscount, "recurringdiscount" => $recurringdiscount);
}

function acceptOrder($orderid, $vars = array()) {
    if (!$orderid) {
        return false;
    }


    if (!is_array($vars)) {
        $vars = array();
    }

    $errors = array();
    run_hook("AcceptOrder", array("orderid" => $orderid));
    $result = select_query_i("tblcustomerservices", "", array("orderid" => $orderid, "servicestatus" => "Pending"));

    while ($data = mysqli_fetch_array($result)) {
        $productid = $data['id'];
        $updateqry = array();

        if ($vars['products'][$productid]['server']) {
            $updateqry['server'] = $vars['products'][$productid]['server'];
        }


        if ($vars['products'][$productid]['username']) {
            $updateqry['username'] = $vars['products'][$productid]['username'];
        }


        if ($vars['products'][$productid]['password']) {
            $updateqry['password'] = encrypt($vars['products'][$productid]['password']);
        }


        if ($vars['api']['serverid']) {
            $updateqry['server'] = $vars['api']['serverid'];
        }


        if ($vars['api']['username']) {
            $updateqry['username'] = $vars['api']['username'];
        }


        if ($vars['api']['password']) {
            $updateqry['password'] = $vars['api']['password'];
        }


        if (count($updateqry)) {
            update_query("tblcustomerservices", $updateqry, array("id" => $productid));
        }

        $result2 = select_query_i("tblcustomerservices", "tblservices.servertype,tblservices.autosetup", array("tblcustomerservices.id" => $productid), "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");
        $data = mysqli_fetch_array($result2);
        $module = $data['servertype'];
        $autosetup = $data['autosetup'];
        $autosetup = ($autosetup ? true : false);
        $sendwelcome = ($autosetup ? true : false);

        if (count($vars)) {
            $autosetup = $vars['products'][$productid]['runcreate'];
            $sendwelcome = $vars['products'][$productid]['sendwelcome'];

            if (isset($vars['api']['autosetup'])) {
                $autosetup = $vars['api']['autosetup'];
            }


            if (isset($vars['api']['sendemail'])) {
                $sendwelcome = $vars['api']['sendemail'];
            }
        }


        if ($autosetup) {
            if ($module) {
                logActivity("Running Module Create on Accept Pending Order");

                if (!isValidforPath($module)) {
                    exit("Invalid Server Module Name");
                }

                require_once ROOTDIR . ("/modules/servers/" . $module . "/" . $module . ".php");
                $moduleresult = ServerCreateAccount($productid);

                if ($moduleresult == "success") {
                    if ($sendwelcome) {
                        sendMessage("defaultnewacc", $productid);
                    }
                }

                $errors[] = $moduleresult;
            }
        }

        update_query("tblcustomerservices", array("servicestatus" => "Active"), array("id" => $productid));

        if ($sendwelcome) {
            sendMessage("defaultnewacc", $productid);
        }
    }

    $result = select_query_i("tblserviceaddons", "", array("orderid" => $orderid, "status" => "Pending"));

    while ($data = mysqli_fetch_array($result)) {
        $aid = $data['id'];
        $hostingid = $data['hostingid'];
        $addonid = $data['addonid'];

        if ($addonid) {
            $result2 = select_query_i("tbladdons", "", array("id" => $addonid));
            $data = mysqli_fetch_array($result2);
            $welcomeemail = $data['welcomeemail'];
            $sendwelcome = ($welcomeemail ? true : false);

            if (count($vars)) {
                $sendwelcome = $vars['addons'][$aid]['sendwelcome'];
            }


            if (isset($vars['api']['sendemail'])) {
                $sendwelcome = $vars['api']['sendemail'];
            }


            if ($welcomeemail && $sendwelcome) {
                $result3 = select_query_i("tblemailtemplates", "name", array("id" => $welcomeemail));
                $data = mysqli_fetch_array($result3);
                $welcomeemailname = $data['name'];
                sendMessage($welcomeemailname, $hostingid);
            }


            if (!$userid) {
                $result3 = select_query_i("tblorders", "userid", array("id" => $orderid));
                $data = mysqli_fetch_array($result3);
                $userid = $data['userid'];
            }

            run_hook("AddonActivation", array("id" => $aid, "userid" => $userid, "serviceid" => $hostingid, "addonid" => $addonid));
        }
    }

    update_query("tblserviceaddons", array("status" => "Active"), array("orderid" => $orderid, "status" => "Pending"));
    $result = select_query_i("tbldescriptions", "", array("orderid" => $orderid, "status" => "Pending"));

    while ($data = mysqli_fetch_array($result)) {
        $descriptionid = $data['id'];
        $regtype = $data['type'];
        $description = $data['description'];
        $registrar = $data['registrar'];
        $emailmessage = ($regtype == "Transfer" ? "Domain Transfer Initiated" : "Domain Registration Confirmation");

        if ($vars['descriptions'][$descriptionid]['registrar']) {
            $registrar = $vars['descriptions'][$descriptionid]['registrar'];
        }


        if ($vars['api']['registrar']) {
            $registrar = $vars['api']['registrar'];
        }


        if ($registrar) {
            update_query("tbldescriptions", array("registrar" => $registrar), array("id" => $descriptionid));
        }


        if ($vars['descriptions'][$descriptionid]['sendregistrar']) {
            $sendregistrar = "on";
        }


        if ($vars['descriptions'][$descriptionid]['sendemail']) {
            $sendemail = "on";
        }


        if (isset($vars['api']['sendregistrar'])) {
            $sendregistrar = $vars['api']['sendregistrar'];
        }


        if (isset($vars['api']['sendemail'])) {
            $sendemail = $vars['api']['sendemail'];
        }


        if ($sendregistrar && $registrar) {
            $params = array();
            $params['descriptionid'] = $descriptionid;
            $moduleresult = ($regtype == "Transfer" ? RegTransferDomain($params) : RegRegisterDomain($params));

            if (!$moduleresult['error']) {
                if ($sendemail) {
                    sendMessage($emailmessage, $descriptionid);
                }
            }

            $errors[] = $moduleresult['error'];
        }

        update_query("tbldescriptions", array("status" => "Active"), array("id" => $descriptionid, "status" => "Pending"));

        if ($sendemail) {
            sendMessage($emailmessage, $descriptionid);
        }
    }


    if (is_array($vars['renewals'])) {
        foreach ($vars['renewals'] as $descriptionid => $options) {

            if ($vars['renewals'][$descriptionid]['sendregistrar']) {
                $sendregistrar = "on";
            }


            if ($vars['renewals'][$descriptionid]['sendemail']) {
                $sendemail = "on";
            }


            if ($sendregistrar) {
                $params = array();
                $params['descriptionid'] = $descriptionid;
                $moduleresult = RegRenewDomain($params);

                if ($moduleresult['error']) {
                    $errors[] = $moduleresult['error'];
                    continue;
                }


                if ($sendemail) {
                    sendMessage("Domain Renewal Confirmation", $descriptionid);
                    continue;
                }

                continue;
            }


            if ($sendemail) {
                sendMessage("Domain Renewal Confirmation", $descriptionid);
                continue;
            }
        }
    }

    $result = select_query_i("tblorders", "userid,promovalue", array("id" => $orderid));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    $promovalue = $data['promovalue'];

    if (substr($promovalue, 0, 2) == "DR") {
        if ($vars['descriptions'][$descriptionid]['sendregistrar']) {
            $sendregistrar = "on";
        }


        if (isset($vars['api']['autosetup'])) {
            $sendregistrar = $vars['api']['autosetup'];
        }


        if ($sendregistrar) {
            $params = array();
            $params['descriptionid'] = $descriptionid;
            $moduleresult = RegRenewDomain($params);

            if ($moduleresult['error']) {
                $errors[] = $moduleresult['error'];
            } else {
                if ($sendemail) {
                    sendMessage("Domain Renewal Confirmation", $descriptionid);
                }
            }
        } else {
            if ($sendemail) {
                sendMessage("Domain Renewal Confirmation", $descriptionid);
            }
        }
    }

    if (!count($errors)) {
        update_query("tblorders", array("status" => "Active"), array("id" => $orderid));
        logActivity("Order Accepted - Order ID: " . $orderid, $userid);
    }

    return $errors;
}

function changeOrderStatus($orderid, $status) {
    if (!$orderid) {
        return false;
    }

    $orderid = (int) $orderid;

    if ($status == "Cancelled") {
        run_hook("CancelOrder", array("orderid" => $orderid));
    } else {
        if ($status == "Fraud") {
            run_hook("FraudOrder", array("orderid" => $orderid));
        } else {
            if ($status == "Pending") {
                run_hook("PendingOrder", array("orderid" => $orderid));
            }
        }
    }

    update_query("tblorders", array("status" => $status), array("id" => $orderid));

    if ($status == "Cancelled" || $status == "Fraud") {
        $result = select_query_i("tblcustomerservices", "tblcustomerservices.id,tblcustomerservices.servicestatus,tblservices.servertype,tblcustomerservices.packageid,tblservices.stockcontrol,tblservices.qty", array("orderid" => $orderid), "", "", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");

        while ($data = mysqli_fetch_array($result)) {
            $productid = $data['id'];
            $prodstatus = $data['servicestatus'];
            $module = $data['servertype'];
            $packageid = $data['packageid'];
            $stockcontrol = $data['stockcontrol'];
            $qty = $data['qty'];

            if ($module && ($prodstatus == "Active" || $prodstatus == "Suspended")) {
                logActivity("Running Module Terminate on Order Cancel");

                if (!isValidforPath($module)) {
                    exit("Invalid Server Module Name");
                }

                require_once ROOTDIR . ("/modules/servers/" . $module . "/" . $module . ".php");
                $moduleresult = ServerTerminateAccount($productid);

                if ($moduleresult == "success") {
                    update_query("tblcustomerservices", array("servicestatus" => $status), array("id" => $productid));

                    if ($stockcontrol == "on") {
                        update_query("tblservices", array("qty" => "+1"), array("id" => $packageid));
                    }
                }
            }

            update_query("tblcustomerservices", array("servicestatus" => $status), array("id" => $productid));

            if ($stockcontrol == "on") {
                update_query("tblservices", array("qty" => "+1"), array("id" => $packageid));
            }
        }
    } else {
        update_query("tblcustomerservices", array("servicestatus" => $status), array("orderid" => $orderid));
    }

    update_query("tblserviceaddons", array("status" => $status), array("orderid" => $orderid));

    if ($status == "Pending") {
        $result = select_query_i("tbldescriptions", "id,type", array("orderid" => $orderid));

        while ($data = mysqli_fetch_assoc($result)) {
            if ($data['type'] == "Transfer") {
                $status = "Pending Transfer";
            } else {
                $status = "Pending";
            }

            update_query("tbldescriptions", array("status" => $status), array("id" => $data['id']));
        }
    } else {
        update_query("tbldescriptions", array("status" => $status), array("orderid" => $orderid));
    }

    $result = select_query_i("tblorders", "userid,invoiceid", array("id" => $orderid));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    $invoiceid = $data['invoiceid'];

    if ($status == "Pending") {
        update_query("tblinvoices", array("status" => "Unpaid"), array("id" => $invoiceid, "status" => "Cancelled"));
    } else {
        update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $invoiceid, "status" => "Unpaid"));
        run_hook("InvoiceCancelled", array("invoiceid" => $invoiceid));
    }

    logActivity("Order Status set to " . $status . " - Order ID: " . $orderid, $userid);
}

function cancelRefundOrder($orderid) {
    $result = select_query_i("tblorders", "invoiceid", array("id" => $orderid));
    $data = mysqli_fetch_array($result);
    $invoiceid = $data['invoiceid'];
    $orderid = (int) $orderid;

    if ($invoiceid) {
        $result = select_query_i("tblinvoices", "status", array("id" => $invoiceid));
        $data = mysqli_fetch_array($result);
        $invoicestatus = $data['status'];

        if ($invoicestatus == "Paid") {
            $result = select_query_i("tblaccounts", "id", array("invoiceid" => $invoiceid));
            $data = mysqli_fetch_array($result);
            $transid = $data['id'];
            $gatewayresult = refundInvoicePayment($transid, "", true);

            if ($gatewayresult == "manual") {
                return "manual";
            }


            if ($gatewayresult != "success") {
                return "refundfailed";
            }
        } else {
            if ($invoicestatus == "Refunded") {
                return "alreadyrefunded";
            }

            return "notpaid";
        }
    }

    return "noinvoice";
}

function deleteOrder($orderid) {
    if (!$orderid) {
        return false;
    }

    $orderid = (int) $orderid;
    run_hook("DeleteOrder", array("orderid" => $orderid));
    $result = select_query_i("tblorders", "userid,invoiceid", array("id" => $orderid));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    $invoiceid = $data['invoiceid'];
    delete_query("tblcustomerservicesconfigoptions", "relid IN (SELECT id FROM tblcustomerservices WHERE orderid=" . $orderid . ")");
    delete_query("tblaffiliatesaccounts", "relid IN (SELECT id FROM tblcustomerservices WHERE orderid=" . $orderid . ")");
    delete_query("tblcustomerservices", array("orderid" => $orderid));
    delete_query("tblserviceaddons", array("orderid" => $orderid));
    delete_query("tbldescriptions", array("orderid" => $orderid));
    delete_query("tblorders", array("id" => $orderid));
    delete_query("tblinvoices", array("id" => $invoiceid));
    delete_query("tblinvoiceitems", array("invoiceid" => $invoiceid));
    logActivity("Deleted Order - Order ID: " . $orderid, $userid);
}

function getAddonDetail($addonid, $currencs = array()) {
    global $currency;
    global $_LANG;
    if (empty($currency)) {
        $currency = $currencs;
    }

    $addonsarray = array();
    $query = "SELECT *,addon.id as addonid FROM tbladdons AS addon INNER JOIN tblpricing as price ON (price.type='addon' AND price.currency = " . $currency['id'] . " AND price.relid=addon.id) where addon.id=" . $addonid;
    $result = full_query_i($query);
    $data = mysqli_fetch_array($result);
    $_SESSION['addon'][$addonid]=$data;
    $addonsarray['id'] = $data['addonid'];
    $addonsarray['name'] = $data['name'];
    $addonsarray['billingcycle'] = $data['billingcycle'];
    if ($data['billingcycle'] == "Free Account") {
        $addonsarray['oneoff'] = 0;
        $addonsarray['cycle'] = 0;
    } else if ($data['billingcycle'] == "One Time") {
        $addonsarray['oneoff'] = $data['msetupfee'] + $data['monthly'];
        $addonsarray['cycle'] = 0;
    } else if ($data['billingcycle'] == "Monthly") {
        $addonsarray['oneoff'] = $data['msetupfee'];
        $addonsarray['cycle'] = $data['monthly'];
    }
    $addonsarray['total'] = $addonsarray['oneoff'] + $addonsarray['cycle'];


    return $addonsarray;
}

function getAddons($pid, $addons, $currencs = array()) {
    global $currency;
    global $_LANG;

    if (!$addons) {
        $addons = array();
    }
    if (empty($currency)) {
        $currency = $currencs;
    }

    $addonsarray = array();
    $result = select_query_i("tbladdons", "", array("showorder" => "on"), "weight` ASC,`name", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $addon_id = $data['id'];
        $addon_packages = $data['packages'];
        $addon_name = $data['name'];
        $addon_description = $data['description'];
        $addon_recurring = $data['recurring'];
        $addon_setupfee = $data['setupfee'];
        $addon_billingcycle = $data['billingcycle'];
        $addon_free = $data['free'];
        $result2 = select_query_i("tblpricing", "", array("type" => "addon", "currency" => $currency['id'], "relid" => $addon_id));
        $data = mysqli_fetch_array($result2);
        $addon_setupfee = $data['msetupfee'];
        $addon_recurring = $data['monthly'];
        $addon_packages = explode(",", $addon_packages);

        if (in_array($pid, $addon_packages)) {
            $addon_status = (in_array($addon_id, $addons) ? true : false);

            if (in_array($addon_id, $addons)) {
                $addon_checkbox = "<button class=\"btn btn-info btn-circle\" id=\"a" . $addon_id . "\" > <i class=\"fa fa-check\"></i></button>";
            } else {
                $addon_checkbox = "<button class=\"btn btn-default btn-circle\" id=\"a" . $addon_id . "\" data-addon=\"" . $addon_id . "\"> <i class=\"\"></i></button>";
            }



            if ($addon_billingcycle == "Free") {
                $addon_pricingdetails = $_LANG['orderfree'];
            } else {
                $addon_pricingdetails = formatCurrency($addon_recurring) . " ";
                $addon_billingcycle = str_replace(array(" ", "-"), "", strtolower($addon_billingcycle));
                $addon_pricingdetails .= $_LANG["orderpaymentterm" . $addon_billingcycle];

                if (0 < $addon_setupfee) {
                    $addon_pricingdetails .= " + " . formatCurrency($addon_setupfee) . " " . $_LANG['ordersetupfee'];
                }
            }

            $addonsarray[] = array("id" => $addon_id, "checkbox" => $addon_checkbox, "value" => $addon_setupfee + $addon_recurring, "name" => $addon_name, "description" => $addon_description, "pricing" => $addon_pricingdetails, "status" => $addon_status);
        }
    }

    return $addonsarray;
}

function getAvailableOrderPaymentGateways() {
    $disabledgateways = "";

    if ($_SESSION['cart']['products']) {
        foreach ($_SESSION['cart']['products'] as $values) {
            $result = select_query_i("tblservices", "gid", array("id" => $values['pid']));
            $data = mysqli_fetch_array($result);
            $gid = $data['gid'];
            $result = select_query_i("tblservicegroups", "disabledgateways", array("id" => $gid));
            $data = mysqli_fetch_array($result);
            $disabledgateways .= $data['disabledgateways'];
        }
    }

    $disabledgateways = explode(",", $disabledgateways);

    if (!function_exists("showPaymentGatewaysList")) {
        require ROOTDIR . "/includes/gatewayfunctions.php";
    }

    $gatewayslist = showPaymentGatewaysList($disabledgateways);
    foreach ($gatewayslist as $module => $vals) {

        if ($vals['type'] == "CC" || $vals['type'] == "OfflineCC") {
            if (!isValidforPath($module)) {
                exit("Invalid Gateway Module Name");
            }

            $gatewaypath = ROOTDIR . "/modules/gateways/" . $module . ".php";

            if (file_exists($gatewaypath)) {
                if ((!function_exists($module . "_config") && !function_exists($module . "_link")) && !function_exists($module . "_capture")) {
                    require_once $gatewaypath;
                }
            }


            if (function_exists($module . "_nolocalcc")) {
                $gatewayslist[$module]['type'] = "Invoices";
                continue;
            }

            continue;
        }
    }

    return $gatewayslist;
}

?>
