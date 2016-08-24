<?php

/**
 * @ RA
 * */
define("CLIENTAREA", true);
require "init.php";
require "includes/orderfunctions.php";
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
require 'includes/servicefunctions.php';
initialiseClientArea($_LANG['carttitle'], "", "<a href=\"cart.php\">" . $_LANG['carttitle'] . "</a>");
checkContactPermission("orders");
check_token();

$orderfrm = new RA_OrderForm();
$cart = new RA_Carts($orderfrm, $ra);
$a = $ra->get_req_var("a");
$gid = $ra->get_req_var("gid");
$pid = (int) $ra->get_req_var("pid");
$aid = (int) $ra->get_req_var("aid");
$ajax = $ra->get_req_var("ajax");
$sld = $ra->get_req_var("sld");
$tld = $ra->get_req_var("tld");
$description = $ra->get_req_var("description");
$step = $ra->get_req_var("step");
$signup = $ra->get_req_var("signup");
$login = $ra->get_req_var("login");
$agreecontract = $ra->get_req_var("agreecontract");
$checkout = $ra->get_req_var("checkout");
$validatepromo = $ra->get_req_var("validatepromo");
$orderfrmtpl = $ra->get_config("OrderFormTemplate");
$username = trim($ra->get_req_var("username"));
$firstname = $ra->get_req_var("rfname");
$lastname = $ra->get_req_var("rlname");
$email = $ra->get_req_var("remail");
$notes = $ra->get_req_var("notes");

$password = trim($ra->get_req_var("rpassword"));
$hash = $ra->get_req_var("hash");
$goto = $ra->get_req_var("goto");
$address = $ra->get_req_var("address");
$fpid = $ra->get_req_var("fpid");
$ajax = $ra->get_req_var("ajax");
$addonid = $ra->get_req_var("addonid");
$checkout = $ra->get_req_var("checkout");
$address1 = $ra->get_req_var("streetnumber") . $ra->get_req_var("address2");

$city = $ra->get_req_var("locality");
$state = $ra->get_req_var("administrative_area_level_1");
$country = $ra->get_req_var("country");
$postcode = $ra->get_req_var("zip");


if (isset($address) && $address != "") {
    $_SESSION['address'] = $address;
    $_SESSION['address1'] = $address1;
    $_SESSION['city'] = $city;
    $_SESSION['state'] = $state;
    $_SESSION['country'] = $country;
    $_SESSION['postcode'] = $postcode;
}
if (isset($fpid) && $fpid != "") {
    $_SESSION['fpid'] = $fpid;
}

if (!isValidforPath($orderfrmtpl)) {
    exit("Invalid Order Form Template Name");
}
$orderconf = array();
$orderfrmconfig = ROOTDIR . "/templates/orderforms/" . $orderfrmtpl . "/config.php";

//
$orderform = true;
$nowrapper = false;


if ($ajax) {
    if ($addonid) {
        $currecy = getCurrency();
        $templatefile = "cart/addons";
        $smartyvalues['addons'] = getAddonDetail($addonid, $currecy);
        outputClientArea($templatefile, true);
        exit();
    }
}
if ($_SESSION['address']) {

    // login for client 
    if (isset($_SESSION['uid'])) {
        if ($checkout) {
            $order_number = generateUniqueID();
            $remote_ip = $ra->get_user_ip();



            $orderid = insert_query("tblorders", array(
                "ordernum" => $order_number,
                "userid" => $_SESSION['uid'],
                "date" => "now()",
                "status" => "Draft",
                "paymentmethod" => "",
                "ipaddress" => $remote_ip,
                "notes" => mysqli_real_escape_string($notes))
            );



            $serviceid = insert_query("tblcustomerservices", array(
                "userid" => $_SESSION['uid'],
                "orderid" => $orderid,
                "packageid" => $_SESSION['fpid'],
                "server" => "",
                "regdate" => "now()",
                "description" => $_SESSION['address'],
                "paymentmethod" => "",
                "firstpaymentamount" => $product_total_today_db,
                "amount" => $product_recurring_db,
                "billingcycle" => $databasecycle,
                "nextduedate" => $hostingquerydates,
                "nextinvoicedate" => $hostingquerydates,
                "servicestatus" => "Pending",
                    )
            );

            if (!empty($_POST['customfield'])) {
                foreach ($_POST['customfield'] as $key => $value) {
                    insert_query("tblcustomfieldsvalues", array("cfid" => $key, "relid" => $serviceid, "value" => $value));
                }
            }

            if (!empty($_SESSION['addon'])) {
                foreach ($_SESSION['addon'] as $row) {
                    insert_query("tblserviceaddons", array(
                        "orderid" => $orderid,
                        "serviceid" => $serviceid,
                        "addonid" => $row['addonid'],
                        "name" => $row['name'],
                        "setupfee" => $row['billingcycle'] == "One Time" ? $row['msetupfee'] + $row['monthly'] : $row['msetupfee'],
                        "recurring" => $row['billingcycle'] == "Monthly" ? $row['msetupfee'] : 0,
                        "billingcycle" => $row['billingcycle'],
                        "tax" => $row['tax'],
                        "status" => "Draft",
                        "regdate" => "now()",
                        "nextduedate" => "now()",
                        "nextinvoicedate" => "now()",
                        "paymentmethod" => ""
                    ));
                }
            }
            //   $hostid = insert_query($table, $array);

            $step = 3;
        } else {
            $step = 2;
        }
    } else {
        $step = "";
        if (!empty($login)) {
            $loginsuccess = $istwofa = false;
            $twofa = new RA_2FA();
            if ($twofa->isActiveClients() && isset($_SESSION['2faverifyc'])) {
                $twofa->setClientID($_SESSION['2faclientid']);

                if ($ra->get_req_var("backupcode")) {
                    $success = $twofa->verifyBackupCode($ra->get_req_var("code"));
                } else {
                    $success = $twofa->moduleCall("verify");
                }


                if ($success) {
                    validateClientLogin(get_query_val("tblclients", "email", array("id" => $_SESSION['2faclientid'])), "", true);

                    if ($_SESSION['2farememberme']) {
                        wSetCookie("User", $_SESSION['uid'] . ":" . sha1($_SESSION['upw'] . $ra->get_hash()), time() + 60 * 60 * 24 * 365);
                    } else {
                        wDelCookie("User");
                    }

                    RA_Session::delete("2faclientid");
                    RA_Session::delete("2farememberme");
                    RA_Session::delete("2faverifyc");

                    if ($ra->get_req_var("backupcode")) {
                        RA_Session::set("2fabackupcodenew", true);
                        $gotourl = "clientarea.php?newbackupcode=true";
                        header("Location: " . $gotourl);
                        exit();
                    }

                    $loginsuccess = true;
                } else {
                    if (strpos($gotourl, "?")) {
                        $gotourl .= "&";
                    } else {
                        $gotourl .= "?";
                    }

                    $gotourl .= "incorrect=true";
                    header("Location: " . $gotourl);
                    exit();
                }
            }


            if (!$loginsuccess) {
                if (validateClientLogin($username, $password)) {
                    $loginsuccess = true;

                    if ($rememberme) {
                        wSetCookie("User", $_SESSION['uid'] . ":" . sha1($_SESSION['upw'] . $ra->get_hash()), time() + 60 * 60 * 24 * 365);
                    } else {
                        wDelCookie("User");
                    }
                } else {
                    if (isset($_SESSION['2faverifyc'])) {
                        $istwofa = true;
                    } else {
                        if ($hash) {
                            $autoauthkey = "";
                            require "configuration.php";

                            if ($autoauthkey) {
                                $login_uid = $login_cid = "";

                                if ($timestamp < time() - 15 * 60 || time() < $timestamp) {
                                    exit("Link expired");
                                }

                                $hashverify = sha1($email . $timestamp . $autoauthkey);

                                if ($hashverify == $hash) {
                                    $result = select_query_i("tblclients", "id,password,language", array("email" => $email, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
                                    $data = mysqli_fetch_array($result);
                                    $login_uid = $data['id'];
                                    $login_pwd = $data['password'];
                                    $language = $data['language'];

                                    if (!$login_uid) {
                                        $result = select_query_i("tblcontacts", "id,userid,password", array("email" => $email, "subaccount" => "1", "password" => array("sqltype" => "NEQ", "value" => "")));
                                        $data = mysqli_fetch_array($result);
                                        $login_cid = $data['id'];
                                        $login_uid = $data['userid'];
                                        $login_pwd = $data['password'];
                                        $result = select_query_i("tblclients", "id,language", array("id" => $login_uid, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
                                        $data = mysqli_fetch_array($result);
                                        $login_uid = $data['id'];
                                        $language = $data['language'];
                                    }

                                    if ($login_uid) {
                                        $fullhost = gethostbyaddr($remote_ip);
                                        update_query("tblclients", array("lastlogin" => "now()", "ip" => $remote_ip, "host" => $fullhost), array("id" => $login_uid));
                                        $_SESSION['uid'] = $login_uid;

                                        if ($login_cid) {
                                            $_SESSION['cid'] = $login_cid;
                                        }

                                        $haship = ($CONFIG['DisableSessionIPCheck'] ? "" : $ra->get_user_ip());
                                        $_SESSION['upw'] = sha1($login_uid . $login_cid . $login_pwd . $haship . substr(sha1($ra->get_hash()), 0, 20));
                                        $_SESSION['tkval'] = genRandomVal();

                                        if ($language) {
                                            $_SESSION['Language'] = $language;
                                        }

                                        run_hook("ClientLogin", array("userid" => $login_uid));
                                        $loginsuccess = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!$istwofa && !$loginsuccess) {
                $infobox = '<p class="text-danger bg-danger text-alert " id="login-error"><strong><span aria-hidden="true" class="icon icon-ban"></span> </strong><br>Login Details Incorrect. Please try again.</p>';
            } else {
                $step = 2;
                redir('step=2');
            }
        } else {
            if ($signup && $firstname && $lastname && $email && $password) {
                $userid = addClient($firstname, $lastname, $companyname = "", $email, $_SESSION['address1'], $address2, $_SESSION['city'], $_SESSION['state'], $_SESSION['postcode'], $_SESSION['country'], $phonenumber, $password);
                $step = 2;
                redir('step=2');
            }
        }
    }


    // $ajax
    // step 2 process the data from the product details
    if ($step == "2") {
        $fpid = $_SESSION['fpid'];
        $errormessage = "";
        if (isset($_SESSION['contractnotsign'])) {
            $contractnotsign = $_SESSION['contractnotsign'];
        } else {
            $contractnotsign = true;
        }
        $result = full_query_i("SELECT tblservices.*,tblservicegroups.name as groupname FROM tblservices LEFT JOIN tblservicegroups ON tblservices.gid = tblservicegroups.id where tblservices.id =" . $fpid);
        $data = mysqli_fetch_assoc($result);
        if (!empty($data)) {
            $currecy = getCurrency();
            $addons = getAddons($data['id'], array(), $currecy);
            $customefield = getServiceCustomFields($data['id']);
            $pricing = getPricingInfo(3, $inclconfigops = false, $upgrade = false, $currecy);
            // $currecy = getCurrency();
        }
        $total = 0;
        foreach ($pricing['rawpricing'] as $key => $item) {
            if ($item == -1) {
                unset($pricing['rawpricing'][$key]);
            } else {
                $total +=$item;
            }
        }

        if (isset($agreecontract)) {
            if ($agreecontract == "on") {
                $_SESSION['contractnotsign'] = false;
                $contractnotsign = false;
            }
        }
        if ($data['contract'] && $contractnotsign) {
            $smartyvalues['product'] = $data;
        } else {

            for ($range = 7; $range < 30; $range++) {
                $date[date("Y-m-d", strtotime($range . ' weekdays'))] = date("l d F Y", strtotime($range . ' weekdays'));
            }


            $today = date("Y-m-d");
            $smartyvalues['today'] = $today;
            $smartyvalues['daterange'] = $date;
            $smartyvalues['address'] = $_SESSION['address'];
            $smartyvalues['total'] = $total;
            $smartyvalues['currecy'] = $currecy;
            $smartyvalues['addons'] = $addons;
            $smartyvalues['pricing'] = $pricing;
            $smartyvalues['product'] = $data;
            $smartyvalues['customefield'] = $customefield;
        }
        $smartyvalues['contractnotsign'] = $contractnotsign;
    }
    if ($step == "3") {
        $gateways = new RA_Gateways();
        $availablegateways = getAvailableOrderPaymentGateways();
        $securityquestions = getSecurityQuestions();

        //   echo "<pre>", print_r($gateways->getCCDateMonths(), 1), "</pre>";

        $smartyvalues['availablegateways'] = $availablegateways;
        $smartyvalues['months'] = $gateways->getCCDateMonths();
        $smartyvalues['startyears'] = $gateways->getCCStartDateYears();
        $smartyvalues['expiryyears'] = $smartyvalues['years'] = $gateways->getCCExpiryDateYears();
    }

    $templatefile = "myorder";
    if (!$templatefile) {
        redir();
        exit();
    }

    $smartyvalues['address'] = $_SESSION['address'];
    $smartyvalues['step'] = $step;
    $smartyvalues['error'] = $infobox;
    $smartyvalues['carttpl'] = $orderfrm->getTemplate();
    outputClientArea($templatefile, true);
} else {

    echo print_r($_SESSION, 1);
}
?>