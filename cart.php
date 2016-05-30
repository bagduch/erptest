<?php

/**
 *
 * @ RA
 * */
define("CLIENTAREA", true);
require "init.php";
require "includes/orderfunctions.php";
require "includes/domainfunctions.php";
require "includes/whoisfunctions.php";
require "includes/configoptionsfunctions.php";
require "includes/customfieldfunctions.php";
require "includes/clientfunctions.php";
require "includes/invoicefunctions.php";
require "includes/processinvoices.php";
require "includes/gatewayfunctions.php";
require "includes/fraudfunctions.php";
require "includes/modulefunctions.php";
require "includes/ccfunctions.php";
require "includes/cartfunctions.php";
initialiseClientArea($_LANG['carttitle'], "", "<a href=\"cart.php\">" . $_LANG['carttitle'] . "</a>");
checkContactPermission("orders");
$orderfrm = new RA_OrderForm();
$cart = new RA_Carts($orderfrm, $ra);
$a = $ra->get_req_var("a");
$gid = $ra->get_req_var("gid");
$pid = (int) $ra->get_req_var("pid");
$aid = (int) $ra->get_req_var("aid");
$ajax = $ra->get_req_var("ajax");
$sld = $ra->get_req_var("sld");
$tld = $ra->get_req_var("tld");
$domains = $ra->get_req_var("domains");
$step = $ra->get_req_var("step");
$submit = $ra->get_req_var("submit");
$checkout = $ra->get_req_var("checkout");
$validatepromo = $ra->get_req_var("validatepromo");
$orderfrmtpl = $ra->get_config("OrderFormTemplate");

if (!isValidforPath($orderfrmtpl)) {
    exit("Invalid Order Form Template Name");
}

$orderconf = array();
$orderfrmconfig = ROOTDIR . "/templates/orderforms/" . $orderfrmtpl . "/config.php";

//if (file_exists($orderfrmconfig)) {
//    include $orderfrmconfig;
//}


if (((!$ajax && isset($orderconf['denynonajaxaccess'])) && is_array($orderconf['denynonajaxaccess'])) && in_array($a, $orderconf['denynonajaxaccess'])) {
    redir();
    exit();
}

$orderform = true;
$nowrapper = false;
$errormessage = $allowcheckout = "";
$userid = (isset($_SESSION['uid']) ? $_SESSION['uid'] : "");
$currencyid = (isset($_SESSION['currency']) ? $_SESSION['currency'] : "");
$currency = getCurrency($userid, $currencyid);
$smartyvalues['currency'] = $currency;
$smartyvalues['ipaddress'] = $remote_ip;
$smartyvalues['ajax'] = ($ajax ? true : false);
$numproducts = (isset($_SESSION['cart']['products']) ? count($_SESSION['cart']['products']) : 0);
$numaddons = (isset($_SESSION['cart']['addons']) ? count($_SESSION['cart']['addons']) : 0);
$numdomains = (isset($_SESSION['cart']['domains']) ? count($_SESSION['cart']['domains']) : 0);
$numrenewals = (isset($_SESSION['cart']['renewals']) ? count($_SESSION['cart']['renewals']) : 0);
$smartyvalues['numitemsincart'] = $numproducts + $numaddons + $numdomains + $numrenewals;

if (isset($_SESSION['cart']['lastconfigured'])) {
    bundlesStepCompleteRedirect($_SESSION['cart']['lastconfigured']);
    unset($_SESSION['cart']['lastconfigured']);
}


if ($step == "fraudcheck") {
    $a = "fraudcheck";
}


if ($promocode = $ra->get_req_var("promocode")) {
    SetPromoCode($promocode);
}


if ($a == "empty") {
    unset($_SESSION['cart']);
    redir("a=view");
    exit();
}


if ($a == "startover") {
    unset($_SESSION['cart']);
    redir();
    exit();
}


if ($a == "remove") {
    if ($r == "p") {
        unset($_SESSION['cart']['products'][$i]);
        $_SESSION['cart']['products'] = array_values($_SESSION['cart']['products']);
    } else {
        if ($r == "a") {
            unset($_SESSION['cart']['addons'][$i]);
            $_SESSION['cart']['addons'] = array_values($_SESSION['cart']['addons']);
        } else {
            if ($r == "d") {
                unset($_SESSION['cart']['domains'][$i]);
                $_SESSION['cart']['domains'] = array_values($_SESSION['cart']['domains']);
            } else {
                if ($r == "r") {
                    unset($_SESSION['cart']['renewals'][$i]);
                }
            }
        }
    }

    redir("a=view");
    exit();
}


if ($a == "applypromo") {
    $promoerrormessage = SetPromoCode($promocode);
    echo $promoerrormessage;
    exit();
}


if ($a == "removepromo") {
    $_SESSION['cart']['promo'] = "";

    if ($ajax) {
        exit();
    }

    redir("a=view");
    exit();
}


if ((!$a || ($a == "add" && $pid)) && ((($sld && $tld) && !is_array($sld)) || is_array($domains))) {
    if (is_array($domains)) {
        $tempdomain = $domains[0];
        $tempdomain = explode(".", $tempdomain, 2);
        $sld = $tempdomain[0];
        $tld = "." . $tempdomain[1];
    }

    $_SESSION['cartdomain']['sld'] = $sld;
    $_SESSION['cartdomain']['tld'] = $tld;
}


if (!$a) {
    if ($CONFIG['AllowRegister']) {
        $smartyvalues['registerdomainenabled'] = true;
    }


    if ($CONFIG['AllowTransfer']) {
        $smartyvalues['transferdomainenabled'] = true;
    }


    if ($CONFIG['EnableDomainRenewalOrders']) {
        $smartyvalues['renewalsenabled'] = true;
    }


    if ($gid == "domains") {
        redir("a=add&domain=register");
        exit();
    } else {
        if ($gid == "addons") {
            if (!$_SESSION['uid']) {
                $orderform = false;
                require "login.php";
            }

            $smarty->assign("gid", "addons");
            $templatefile = "addons";
            $productgroups = $orderfrm->getProductGroups();
            $smarty->assign("productgroups", $productgroups);
            $where = array();
            $where['userid'] = $_SESSION['uid'];
            $where['domainstatus'] = "Active";

            if ($pid) {
                $where["tblcustomerservices.id"] = $pid;
            }

            $productids = array();
            $result = select_query("tblcustomerservices", "tblcustomerservices.id,domain,packageid,name", $where, "", "", "", "tblproducts ON tblproducts.id=tblcustomerservices.packageid");

            while ($data = mysql_fetch_array($result)) {
                $productstoids[$data['packageid']][] = array("id" => $data['id'], "product" => $data['name'], "domain" => $data['domain']);

                if (!in_array($data['packageid'], $productids)) {
                    $productids[] = $data['packageid'];
                }
            }

            $addonids = array();
            $result = select_query("tbladdons", "id,packages", "");

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $packages = $data['packages'];
                $packages = explode(",", $packages);
                foreach ($productids as $productid) {

                    if (in_array($productid, $productids) && !in_array($id, $addonids)) {
                        $addonids[] = $id;
                        continue;
                    }
                }
            }

            $addons = array();

            if (count($addonids)) {
                $result = select_query("tbladdons", "", "id IN (" . db_build_in_array($addonids) . ")", "weight` ASC,`name", "ASC");

                while ($data = mysql_fetch_array($result)) {
                    $addonid = $data['id'];
                    $packages = $data['packages'];
                    $packages = explode(",", $packages);
                    $name = $data['name'];
                    $description = $data['description'];
                    $billingcycle = $data['billingcycle'];
                    $free = false;

                    if ($billingcycle == "Free Account") {
                        $free = true;
                    } else {
                        $result2 = select_query("tblpricing", "", array("type" => "addon", "currency" => $currency['id'], "relid" => $addonid));
                        $data = mysql_fetch_array($result2);
                        $setupfee = $data['msetupfee'];
                        $recurring = $data['monthly'];
                        $setupfee = ($setupfee == "0.00" ? "" : formatCurrency($setupfee));
                    }

                    $billingcycle = RA_ClientArea::getrawstatus($billingcycle);
                    $billingcycle = $_LANG["orderpaymentterm" . $billingcycle];
                    $packageids = array();
                    foreach ($packages as $packageid) {
                        $thisaddonspackages = "";
                        $thisaddonspackages = $productstoids[$packageid];

                        if ($thisaddonspackages) {
                            $packageids = array_merge($packageids, $thisaddonspackages);
                            continue;
                        }
                    }


                    if (count($packageids)) {
                        $addons[] = array("id" => $addonid, "name" => $name, "description" => $description, "free" => $free, "setupfee" => $setupfee, "recurringamount" => formatCurrency($recurring), "billingcycle" => $billingcycle, "productids" => $packageids);
                    }
                }
            }

            $smarty->assign("addons", $addons);

            if (!count($addons)) {
                $smarty->assign("noaddons", true);
            }
        } else {
            if ($gid == "renewals") {
                if (!$CONFIG['EnableDomainRenewalOrders']) {
                    redir("", "clientarea.php");
                }


                if (!$_SESSION['uid']) {
                    $orderform = false;
                    require "login.php";
                }

                $smarty->assign("gid", "renewals");
                $templatefile = "domainrenewals";
                $productgroups = $orderfrm->getProductGroups();
                $smartyvalues['productgroups'] = $productgroups;
                $DomainRenewalGracePeriods = $DomainRenewalMinimums = array();
                require ROOTDIR . "/configuration.php";
                $DomainRenewalGracePeriods = array_merge(array(".com" => "30", ".net" => "30", ".org" => "30", ".info" => "15", ".biz" => "30", ".mobi" => "30", ".name" => "30", ".asia" => "30", ".tel" => "30", ".in" => "15", ".mn" => "30", ".bz" => "30", ".cc" => "30", ".tv" => "30", ".eu" => "0", ".co.uk" => "97", ".org.uk" => "97", ".me.uk" => "97", ".us" => "30", ".ws" => "0", ".me" => "30", ".cn" => "30", ".nz" => "0", ".ca" => "30"), $DomainRenewalGracePeriods);
                $DomainRenewalMinimums = array_merge(array(".co.uk" => "180", ".org.uk" => "180", ".me.uk" => "180", ".com.au" => "90", ".net.au" => "90", ".org.au" => "90"), $DomainRenewalMinimums);
                $DomainRenewalPriceOptions = array();
                $renewals = array();
                $result = select_query("tbldomains", "", "userid='" . (int) $_SESSION['uid'] . "' AND (status='Active' OR status='Expired')", "expirydate", "ASC");

                while ($data = mysql_fetch_array($result)) {
                    $id = $data['id'];
                    $domain = $data['domain'];
                    $expirydate = $data['expirydate'];
                    $status = $data['status'];

                    if ($expirydate == "0000-00-00") {
                        $expirydate = $data['nextduedate'];
                    }

                    $todaysdatetime = strtotime(date("Ymd"));
                    $expirydatetime = strtotime($expirydate);
                    $daysuntilexpiry = round(($expirydatetime - $todaysdatetime) / 86400);
                    $domainparts = explode(".", $domain, 2);
                    $tld = "." . $domainparts[1];
                    $beforerenewlimit = $ingraceperiod = $pastgraceperiod = false;

                    if (array_key_exists($tld, $DomainRenewalMinimums)) {
                        if ($DomainRenewalMinimums[$tld] < $daysuntilexpiry) {
                            $beforerenewlimit = true;
                        }
                    }


                    if (array_key_exists($tld, $DomainRenewalGracePeriods)) {
                        if ($DomainRenewalGracePeriods[$tld] < $daysuntilexpiry * (0 - 1)) {
                            $pastgraceperiod = true;
                        }
                    } else {
                        if ($daysuntilexpiry < 0) {
                            $pastgraceperiod = true;
                        }
                    }


                    if (!$pastgraceperiod && $daysuntilexpiry < 0) {
                        $ingraceperiod = true;
                    }


                    if (!array_key_exists($tld, $DomainRenewalPriceOptions)) {
                        $temppricelist = getTLDPriceList($tld, true, true);
                        $renewaloptions = array();
                        foreach ($temppricelist as $regperiod => $options) {

                            if ($options['renew']) {
                                $renewaloptions[] = array("period" => $regperiod, "price" => $options['renew']);
                                continue;
                            }
                        }

                        $DomainRenewalPriceOptions[$tld] = $renewaloptions;
                    } else {
                        $renewaloptions[] = $DomainRenewalPriceOptions[$tld];
                    }

                    $rawstatus = RA_ClientArea::getrawstatus($status);

                    if (count($renewaloptions)) {
                        $renewals[] = array("id" => $id, "domain" => $domain, "tld" => $tld, "status" => $_LANG["clientarea" . $rawstatus], "expirydate" => fromMySQLDate($expirydate), "daysuntilexpiry" => $daysuntilexpiry, "beforerenewlimit" => $beforerenewlimit, "beforerenewlimitdays" => $DomainRenewalMinimums[$tld], "ingraceperiod" => $ingraceperiod, "pastgraceperiod" => $pastgraceperiod, "graceperioddays" => $DomainRenewalGracePeriods[$tld], "renewaloptions" => $DomainRenewalPriceOptions[$tld]);
                    }
                }

                $smartyvalues['renewals'] = $renewals;
            } else {

                $templatefile = "services";
                $productgroups = $orderfrm->getProductGroups();

                $smartyvalues['productgroups'] = $productgroups;


                //    echo "<pre>",  print_r($_SESSION['cart'],1),"</pre>";

                $productservice = $orderfrm->getServiceGroups();
                $smartyvalues['productservices'] = $productservice;

                if ($pid) {
                    $result = select_query("tblservices", "id,gid", array("id" => $pid));
                    $data = mysql_fetch_array($result);
                    $pid = $data['id'];
                    $gid = $data['gid'];
                    $smartyvalues['pid'] = $pid;
                } else {
                    if (!$gid) {
                        $gid = $productservice[0]['gid'];
                    }
                }
                $type = select_query('tblservicegroups', "type", array("id" => $gid));
                $typedata = mysql_fetch_array($result);
                $smartyvalues['type'] = $typedata['type'];
                $groupinfo = $orderfrm->getProductGroupInfo($gid);

                if (count($productgroups) && !$groupinfo) {
                    redir();
                }



                $smartyvalues['gid'] = $groupinfo['id'];
                $smartyvalues['carts'] = $_SESSION['cart'];
                $smartyvalues['groupname'] = $groupinfo['name'];
                $products = $orderfrm->getProducts($gid, true, true);

                //$services = $orderfrm->getServices($gid, true, true);
                $smartyvalues['products'] = $products;
                $smartyvalues['productscount'] = count($products);
            }
        }
    }
}


if ($a == "add") {
    
}


if ($a == "domainoptions") {
    $productinfo = $orderfrm->setPid($_SESSION['cart']['domainoptionspid']);

    if ($checktype == "register" || $checktype == "transfer") {
        if ($domain) {
            $domainparts = explode(".", $domain, 2);
            $sld = $domainparts[0];
            $tld = $domainparts[1];
        }

        $sld = cleanDomainInput($sld);
        $tld = cleanDomainInput($tld);
        $domain = $sld . $tld;

        if ((($sld != "www" && $sld) && $tld) && checkDomainisValid($sld, $tld)) {
            if (substr($tld, 0, 1) != ".") {
                $tld = "." . $tld;
            }


            if ($CONFIG['AllowDomainsTwice']) {
                $result = select_query("tbldomains", "COUNT(*)", "domain='" . db_escape_string($sld . $tld) . "' AND (status!='Expired' AND status!='Cancelled')");
                $data = mysql_fetch_array($result);
                $domaincheck = $data[0];
            }


            if ($domaincheck) {
                $smartyvalues['alreadyindb'] = true;
            } else {
                $regenabled = $CONFIG['AllowRegister'];
                $transferenabled = $CONFIG['AllowTransfer'];
                $owndomainenabled = $CONFIG['AllowOwnDomain'];
                $whoislookup = lookupDomain($sld, $tld);
                $domainstatus = $whoislookup['result'];

                if (!$checktype) {
                    $checktype = ($domainstatus == "available" ? "register" : "transfer");
                }

                $smartyvalues['status'] = $domainstatus;

                if ($regenabled) {
                    $regoptions = getTLDPriceList($tld, true);
                    $smartyvalues['regoptionscount'] = count($regoptions);
                    $smartyvalues['regoptions'] = $regoptions;
                }


                if ($transferenabled) {
                    $transferoptions = getTLDPriceList($tld, true, "transfer");
                    $smartyvalues['transferoptionscount'] = count($transferoptions);
                    $smartyvalues['transferoptions'] = $transferoptions;
                    $transferprice = current($transferoptions);
                    $smartyvalues['transferterm'] = key($transferoptions);
                    $smartyvalues['transferprice'] = $transferprice['transfer'];
                }

                $smartyvalues['domain'] = $domain;
                $smartyvalues['checktype'] = $checktype;
                $smartyvalues['regenabled'] = $regenabled;
                $smartyvalues['transferenabled'] = $transferenabled;
                $smartyvalues['owndomainenabled'] = $owndomainenabled;

                if ($checktype == "register" && $regenabled) {
                    $tldslist = $CONFIG['BulkCheckTLDs'];
                    $othersuggestions = array();

                    if ($tldslist) {
                        $tldslist = explode(",", $tldslist);
                        foreach ($tldslist as $lookuptld) {

                            if ($lookuptld != $tld && checkDomainisValid($sld, $lookuptld)) {
                                $result = lookupDomain($sld, $lookuptld);

                                if ($result['result'] == "available") {
                                    $othersuggestions[] = array("domain" => $sld . $lookuptld, "status" => $result['result'], "regoptions" => getTLDPriceList($lookuptld, true));
                                    continue;
                                }

                                continue;
                            }
                        }
                    }
                }

                $smartyvalues['othersuggestions'] = $othersuggestions;
            }
        } else {
            $smartyvalues['invalid'] = true;
        }
    } else {
        if ($checktype == "owndomain" || $checktype == "subdomain") {
            if (($sld && $tld) && checkDomainisValid($sld, $tld)) {
                if (substr($tld, 0, 1) != ".") {
                    $tld = "." . $tld;
                }


                if ($CONFIG['AllowDomainsTwice']) {
                    $result = select_query("tblcustomerservices", "COUNT(*)", "domain='" . db_escape_string($sld . $tld) . "' AND (domainstatus!='Terminated' AND domainstatus!='Cancelled' AND domainstatus!='Fraud')");
                    $data = mysql_fetch_array($result);
                    $domaincheck = $data[0];

                    if ($domaincheck) {
                        $smartyvalues['alreadyindb'] = true;
                    }
                }

                $smartyvalues['checktype'] = $checktype;
                $smartyvalues['sld'] = $sld;
                $smartyvalues['tld'] = $tld;
            } else {
                $smartyvalues['invalid'] = true;
            }
        } else {
            if ($checktype == "incart") {
                $smartyvalues['checktype'] = "owndomain";
                $domainparts = explode(".", $sld, 2);
                $sld = $domainparts[0];
                $tld = $domainparts[1];
                $smartyvalues['sld'] = $sld;
                $smartyvalues['tld'] = $tld;
            }
        }
    }

    $templatefile = "domainoptions";
}


if ($a == "confservice") {
    $templatefile = "configureservice";
    $i = (int) $_REQUEST['i'];


    if (!is_array($_SESSION['cart']['products'][$i])) {
        if ($ajax) {
            exit($_LANG['invoiceserror']);
        }

        redir();
        exit();
    }

    $newproduct = $_SESSION['cart']['newproduct'];
    unset($_SESSION['cart']['newproduct']);
    $pid = $_SESSION['cart']['products'][$i]['pid'];
    $productinfo = $orderfrm->setPid($pid);

    if (!$productinfo) {
        redir();
    }

    $_SESSION['cart']['cartsummarypid'] = $productinfo['pid'];
    $pid = $productinfo['pid'];

    if ($configure) {
        global $errormessage;

        $errormessage = "";
        $result = select_query("tblproducts", "type", array("id" => $pid));
        $data = mysql_fetch_array($result);
        $producttype = $data['type'];

        if ($producttype == "server") {
            if (!$hostname) {
                $errormessage .= "<li>" . $_LANG['ordererrorservernohostname'];
            } else {
                $result = select_query("tblcustomfields", "COUNT(*)", array("domain" => $hostname . "." . $_SESSION['cart']['products'][$i]['domain'], "domainstatus" => array("sqltype" => "NEQ", "value" => "Cancelled"), "domainstatus" => array("sqltype" => "NEQ", "value" => "Terminated"), "domainstatus" => array("sqltype" => "NEQ", "value" => "Fraud")));
                $data = mysql_fetch_array($result);
                $existingcount = $data[0];

                if ($existingcount) {
                    $errormessage .= "<li>" . $_LANG['ordererrorserverhostnameinuse'];
                }
            }


            if (!$ns1prefix || !$ns2prefix) {
                $errormessage .= "<li>" . $_LANG['ordererrorservernonameservers'];
            }


            if (!$rootpw) {
                $errormessage .= "<li>" . $_LANG['ordererrorservernorootpw'];
            }

            $serverarray = array("hostname" => $hostname, "ns1prefix" => $ns1prefix, "ns2prefix" => $ns2prefix, "rootpw" => $rootpw);
        }


        if ($configoption) {
            foreach ($configoption as $opid => $opid2) {
                $result = select_query("tblserviceconfigoptions", "", array("id" => $opid));
                $data = mysql_fetch_array($result);
                $optionname = $data['optionname'];
                $optiontype = $data['optiontype'];
                $qtyminimum = $data['qtyminimum'];
                $qtymaximum = $data['qtymaximum'];

                if ($optiontype == 4) {
                    $opid2 = (int) $opid2;

                    if ($opid2 < 0) {
                        $opid2 = 0;
                    }


                    if (($qtyminimum || $qtymaximum) && ($opid2 < $qtyminimum || $qtymaximum < $opid2)) {
                        if (strpos($optionname, "|")) {
                            $optionname = explode("|", $optionname);
                            $optionname = trim($optionname[1]);
                        }

                        $errormessage .= "<li>" . sprintf($_LANG['configoptionqtyminmax'], $optionname, $qtyminimum, $qtymaximum);
                        $opid2 = 0;
                    }
                }

                $configoptionsarray[sanitize($opid)] = sanitize($opid2);
            }
        }

        $addonsarray = (is_array($addons) ? array_keys($addons) : "");
        $errormessage .= bundlesValidateProductConfig($i, $billingcycle, $configoptionsarray, $addonsarray);
        $_SESSION['cart']['products'][$i]['billingcycle'] = $billingcycle;
        $_SESSION['cart']['products'][$i]['server'] = $serverarray;
        $_SESSION['cart']['products'][$i]['configoptions'] = $configoptionsarray;
        $_SESSION['cart']['products'][$i]['customfields'] = $customfield;
        $_SESSION['cart']['products'][$i]['addons'] = $addonsarray;

        if ($calctotal) {
            $i = $ra->get_req_var("i");
            $productinfo = $orderfrm->setPid($_SESSION['cart']['products'][$i]['pid']);
            $ordersummarytemp = "/templates/orderforms/" . $orderfrm->getTemplate() . "/ordersummary.tpl";


            if (file_exists(ROOTDIR . $ordersummarytemp)) {
                $carttotals = calcCartTotals(false, true);

//               echo "<pre>",print_r($carttotals,1),"</pre>";
                $templatevars = array("producttotals" => $carttotals['products'][$i], "carttotals" => $carttotals);
                echo processSingleTemplate($ordersummarytemp, $templatevars);
            }

            exit();
        }


        if ((!$ajax && !$nocyclerefresh) && $previousbillingcycle != $billingcycle) {
            redir("a=confservice&i=" . $i);
            exit();
        }

        $validate = new RA_Validate();
        $validate->validateCustomFields("product", $pid, true);
        run_validate_hook($validate, "ShoppingCartValidateProductUpdate", $_REQUEST);

        if ($validate->hasErrors()) {
            $errormessage .= $validate->getHTMLErrorOutput();
        }


        if ($errormessage) {
            if ($ajax) {
                exit($errormessage);
            }

            $smartyvalues['errormessage'] = $errormessage;
        } else {
            unset($_SESSION['cart']['products'][$i]['noconfig']);
            $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $i);

            if ($ajax) {
                exit();
            }

            redir("a=confdomains");
            exit();
        }
    }

    $billingcycle = $_SESSION['cart']['products'][$i]['billingcycle'];
    $server = $_SESSION['cart']['products'][$i]['server'];
    $customfields = $_SESSION['cart']['products'][$i]['customfields'];
    $configoptions = $_SESSION['cart']['products'][$i]['configoptions'];
    $addons = $_SESSION['cart']['products'][$i]['addons'];
    $domain = $_SESSION['cart']['products'][$i]['domain'];
    $noconfig = $_SESSION['cart']['products'][$i]['noconfig'];
    $billingcycle = $orderfrm->validateBillingCycle($billingcycle);
    $pricing = getPricingInfo($pid);
    $configurableoptions = getCartConfigOptions($pid, $configoptions, $billingcycle, "", true);
    $customfields = getCustomFields("product", $pid, "", "", "on", $customfields);

    $addonsarray = getAddons($pid, $addons);
    $recurringcycles = 0;

    if ($pricing['type'] == "recurring") {
        if (0 <= $pricing['rawpricing']['monthly']) {
            ++$recurringcycles;
        }


        if (0 <= $pricing['rawpricing']['quarterly']) {
            ++$recurringcycles;
        }


        if (0 <= $pricing['rawpricing']['semiannually']) {
            ++$recurringcycles;
        }


        if (0 <= $pricing['rawpricing']['annually']) {
            ++$recurringcycles;
        }


        if (0 <= $pricing['rawpricing']['biennially']) {
            ++$recurringcycles;
        }
    }


    if ((((($newproduct && $productinfo['type'] != "server") && ($pricing['type'] != "recurring" || $recurringcycles <= 1)) && !count($configurableoptions)) && !count($customfields)) && !count($addonsarray)) {
        unset($_SESSION['cart']['products'][$i]['noconfig']);
        $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $i);

        if ($ajax) {
            exit();
        }

        redir("a=confdomains");
        exit();
    }

    $serverarray = array("hostname" => $server['hostname'], "ns1prefix" => $server['ns1prefix'], "ns2prefix" => $server['ns2prefix'], "rootpw" => $server['rootpw']);
    $smartyvalues['editconfig'] = true;
    $smartyvalues['firstconfig'] = ($noconfig ? true : false);
    $smartyvalues['i'] = $i;
    $smartyvalues['productinfo'] = $productinfo;
    $smartyvalues['pricing'] = $pricing;
    $smartyvalues['billingcycle'] = $billingcycle;
    $smartyvalues['server'] = $serverarray;
    $smartyvalues['configurableoptions'] = $configurableoptions;
    $smartyvalues['addons'] = $addonsarray;
    $smartyvalues['customfields'] = $customfields;
    $smartyvalues['domain'] = $domain;
}


if ($a == "checkout") {
    $domainconfigerror = false;
    // include "includes/additionaldomainfields.php";
    $allowcheckout = true;
    $a = "view";
}

if ($a == "confdomains") {
    
}
if ($a == "addcontact") {
    $allowcheckout = true;
    $addcontact = true;
    $a = "view";
}


if ($a == "view") {


    if (($submit || $checkout) && !$validatepromo) {
        $viewdata = $cart->Viewcart(true);
    } else {
        $viewdata = $cart->Viewcart(false);
    }
    // echo "<pre>", print_r($viewdata, 1), "<pre>";
    $templatefile = $viewdata['template'];
    $smartyvalues = $viewdata['smarty'];
    // echo "<pre>", print_r($smartyvalues, 1), "<pre>";
}




if ($a == "login") {
    if ($_SESSION['uid']) {
        redir("a=checkout");
        exit();
    }

    $templatefile = "login";
    $_SESSION['loginurlredirect'] = "cart.php?a=login";

    if ($incorrect) {
        $smartyvalues['incorrect'] = true;
    }
}


if ($a == "fraudcheck") {
    $orderid = $_SESSION['orderdetails']['OrderID'];
    $results = (isset($_SESSION['orderdetails']['fraudcheckresults']) ? $_SESSION['orderdetails']['fraudcheckresults'] : "");
    unset($_SESSION['orderdetails']['fraudcheckresults']);

    if (!$results) {
        $fraudmodule = getActiveFraudModule();

        if ($CONFIG['SkipFraudForExisting']) {
            $result = select_query("tblorders", "COUNT(*)", array("status" => "Active", "userid" => $_SESSION['uid']));
            $data = mysql_fetch_array($result);

            if ($data[0]) {
                $fraudmodule = "";
            }
        }

        $result = full_query("SELECT COUNT(*) FROM tblinvoices INNER JOIN tblorders ON tblorders.invoiceid=tblinvoices.id WHERE tblorders.id='" . db_escape_string($orderid) . "' AND tblinvoices.status='Paid' AND subtotal>0");
        $data = mysql_fetch_array($result);

        if ($data[0]) {
            $fraudmodule = "";
        }


        if (!$fraudmodule) {
            redir("a=complete");
            exit();
        }

        $results = runFraudCheck($orderid, $fraudmodule);
    }

    $hookresults = array("orderid" => $orderid, "ordernumber" => $_SESSION['orderdetails']['OrderNumber'], "fraudresults" => $_SESSION['orderdetails']['fraudcheckresults'], "invoiceid" => $_SESSION['orderdetails']['InvoiceID'], "amount" => $_SESSION['orderdetails']['TotalDue'], "fraudresults" => $results, "isfraud" => $results['error'], "clientdetails" => getClientsDetails($_SESSION['uid']));
    run_hook("AfterFraudCheck", array($hookresults));
    $error = $results['error'];

    if ($results['userinput']) {
        logActivity("Order ID " . $orderid . " Fraud Check Awaiting User Input");
        $templatefile = "fraudcheck";
        $smarty->assign("errortitle", $results['title']);
        $smarty->assign("error", $results['description']);
        outputClientArea($templatefile);
        exit();
    }


    if ($error) {
        logActivity("Order ID " . $orderid . " Failed Fraud Check");
        $templatefile = "fraudcheck";
        $smarty->assign("errortitle", $error['title']);
        $smarty->assign("error", $error['description']);
        outputClientArea($templatefile);
        exit();
    } else {
        update_query("tblorders", array("status" => "Pending"), array("id" => $orderid));

        if ($_SESSION['orderdetails']['Products']) {
            foreach ($_SESSION['orderdetails']['Products'] as $productid) {
                update_query("tblcustomerservices", array("domainstatus" => "Pending"), array("id" => $productid, "domainstatus" => "Fraud"));
            }
        }


        if ($_SESSION['orderdetails']['Addons']) {
            foreach ($_SESSION['orderdetails']['Addons'] as $addonid) {
                update_query("tblserviceaddons", array("status" => "Pending"), array("id" => $addonid, "status" => "Fraud"));
            }
        }


        if ($_SESSION['orderdetails']['Domains']) {
            foreach ($_SESSION['orderdetails']['Domains'] as $domainid) {
                update_query("tbldomains", array("status" => "Pending"), array("id" => $domainid, "status" => "Fraud"));
            }
        }

        update_query("tblinvoices", array("status" => "Unpaid"), array("id" => $_SESSION['orderdetails']['InvoiceID'], "status" => "Cancelled"));
        logActivity("Order ID " . $orderid . " Passed Fraud Check");
        redir("a=complete");
        exit();
    }
}


if ($a == "complete") {
    if (!is_array($_SESSION['orderdetails'])) {
        redir();
    }

    $orderid = $_SESSION['orderdetails']['OrderID'];
    $invoiceid = $_SESSION['orderdetails']['InvoiceID'];
    $paymentmethod = $_SESSION['orderdetails']['PaymentMethod'];
    $total = 0;

    if ($invoiceid) {
        $result = select_query("tblinvoices", "id,total,paymentmethod,status", array("userid" => $_SESSION['uid'], "id" => $invoiceid));
        $data = mysql_fetch_array($result);
        $invoiceid = $data['id'];
        $total = $data['total'];
        $paymentmethod = $data['paymentmethod'];
        $status = $data['status'];

        if (!$invoiceid) {
            exit("Invalid Invoice ID");
        }

        $clientsdetails = getClientsDetails($_SESSION['uid']);
    }

    $paymentmethod = RA_Gateways::makesafename($paymentmethod);

    if (!$paymentmethod) {
        exit("Unexpected payment method value. Exiting.");
    }

    $result = select_query("tblcustomerservices", "tblcustomerservices.id,tblproducts.servertype", array("tblcustomerservices.orderid" => $orderid, "tblcustomerservices.domainstatus" => "Pending", "tblproducts.autosetup" => "order"), "", "", "", "tblproducts ON tblproducts.id=tblcustomerservices.packageid");

    while ($data = mysql_fetch_array($result)) {
        $id = $data['id'];
        $servertype = $data['servertype'];

        if (getNewClientAutoProvisionStatus($_SESSION['uid'])) {
            logActivity("Running Module Create on Order");

            if (!isValidforPath($servertype)) {
                exit("Invalid Server Module Name");
            }

            include_once ROOTDIR . ("/modules/servers/" . $servertype . "/" . $servertype . ".php");
            $moduleresult = ServerCreateAccount($id);

            if ($moduleresult == "success") {
                sendMessage("defaultnewacc", $id);
            }
        }

        logActivity("Module Create on Order Suppressed for New Client");
    }

    loadGatewayModule($paymentmethod);

    if (($invoiceid && $status == "Unpaid") && function_exists($paymentmethod . "_orderformcheckout")) {
        $params = getGatewayVariables($paymentmethod, $invoiceid, $total);
        $captureresult = call_user_func($paymentmethod . "_orderformcheckout", $params);
        $gatewayname = get_query_val("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "name"));
        logTransaction($gatewayname, $captureresult['rawdata'], ucfirst($captureresult['status']));

        if ($captureresult['status'] == "success") {
            addInvoicePayment($invoiceid, $captureresult['transid'], "", $captureresult['fee'], $paymentmethod);
            $_SESSION['orderdetails']['paymentcomplete'] = true;
            $status = "Paid";
        }
    }


    if ($invoiceid && $status == "Unpaid") {
        $gatewaytype = get_query_val("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "type"));

        if (!isValidforPath($paymentmethod)) {
            exit("Invalid Payment Gateway Name");
        }

        $gatewaypath = ROOTDIR . "/modules/gateways/" . $paymentmethod . ".php";

        if (file_exists($gatewaypath)) {
            if ((!function_exists($paymentmethod . "_config") && !function_exists($paymentmethod . "_link")) && !function_exists($paymentmethod . "_capture")) {
                require_once $gatewaypath;
            }
        }


        if (($gatewaytype == "CC" || $gatewaytype == "OfflineCC") && ($CONFIG['AutoRedirectoInvoice'] == "on" || $CONFIG['AutoRedirectoInvoice'] == "gateway")) {
            if (function_exists($paymentmethod . "_nolocalcc")) {
                
            } else {
                redir("invoiceid=" . $invoiceid, "creditcard.php");
            }
        }


        if ($CONFIG['AutoRedirectoInvoice'] == "on") {
            redir("id=" . $invoiceid, "viewinvoice.php");
        }


        if ($CONFIG['AutoRedirectoInvoice'] == "gateway") {
            if (in_array($paymentmethod, array("mailin", "banktransfer"))) {
                redir("id=" . $invoiceid, "viewinvoice.php");
            }

            $params = getGatewayVariables($paymentmethod, $invoiceid, $total);
            $paymentbutton = call_user_func($paymentmethod . "_link", $params);
            unset($orderform);
            $templatefile = "forwardpage";
            $smarty->assign("message", $_LANG['forwardingtogateway']);
            $smarty->assign("code", $paymentbutton);
            $smarty->assign("invoiceid", $invoiceid);
            outputClientArea($templatefile);
            exit();
        }
    }

    $amount = get_query_val("tblorders", "amount", array("userid" => $_SESSION['uid'], "id" => $orderid));
    $templatefile = "complete";
    $smartyvalues = array_merge($smartyvalues, array("orderid" => $orderid, "ordernumber" => $_SESSION['orderdetails']['OrderNumber'], "invoiceid" => $invoiceid, "ispaid" => $_SESSION['orderdetails']['paymentcomplete'], "amount" => $amount, "paymentmethod" => $paymentmethod, "clientdetails" => getClientsDetails($_SESSION['uid'])));
    $addons_html = run_hook("ShoppingCartCheckoutCompletePage", $smartyvalues);
    $smartyvalues['addons_html'] = $addons_html;
}

if ($a == "adds") {

    $data = $cart->AddServices($pid);
    $templatefile = $data['template'];
    $smartyvalues = $data['smart'];
}


if (!$templatefile) {
    redir();
    exit();
}

$nowrapper = (isset($_REQUEST['ajax']) ? true : false);
$smartyvalues['carttpl'] = $orderfrm->getTemplate();
outputClientArea($templatefile, $nowrapper);
?>