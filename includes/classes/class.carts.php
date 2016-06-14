<?php

/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 * */
include "../functions.php";
include "../orderfunctions.php";
include "../domainfunctions.php";
include "../whoisfunctions.php";
include "../configoptionsfunctions.php";
include "../customfieldfunctions.php";
include "../clientfunctions.php";
include "../invoicefunctions.php";
include "../processinvoices.php";
include "../gatewayfunctions.php";
include "../fraudfunctions.php";
include "../modulefunctions.php";
include "../ccfunctions.php";
include "../cartfunctions.php";

use RA_Gateways;
use RA_Validate;

class RA_Carts {

    private $orderfrm;
    public $data;
    private $ra;

    public function __construct($orderfrm, $ra) {
        $this->data = "";
        $this->orderfrm = $orderfrm;
        $this->ra = $ra;
    }

    public function AddServices($pid) {

        if ($pid) {

            if (substr($pid, 0, 1) == "b") {
                redir("a=adds&bid=" . substr($pid, 1));
            }

            $templatefile = "configureserviceform";
            $productinfo = $this->orderfrm->setPid($pid);
            if (!$productinfo) {
                redir();
                $_SESSION['cart']['domainoptionspid'] = $productinfo['pid'];
                $smartyvalues['productinfo'] = $productinfo;
                $pid = $smartyvalues['pid'] = $productinfo['pid'];
                $type = $productinfo['type'];
                $des = $productinfo['description'];
            }

            if ($configoption) {
                $passedvariables['configoption'] = $configoption;
            }


            if ($customfield) {
                $passedvariables['customfield'] = $customfield;
            }

            if ($addons) {
                if (!is_array($addons)) {
                    $passedvariables['addons'] = explode(",", $addons);
                } else {
                    foreach ($addons as $k => $v) {
                        $passedvariables['addons'][] = trim($k);
                    }
                }
            }
            if (count($passedvariables)) {
                $_SESSION['cart']['passedvariables'] = $passedvariables;
            }
            $passedvariables = $_SESSION['cart']['passedvariables'];
            $prodarray = array("pid" => $pid, "billingcycle" => $passedvariables['billingcycle'], "configoptions" => $passedvariables['configoption'], "customfields" => $passedvariables['customfield'], "addons" => $passedvariables['addons'], "server" => "", "noconfig" => true);
            if (isset($passedvariables['bnum'])) {
                $prodarray['bnum'] = $passedvariables['bnum'];
            }


            if (isset($passedvariables['bitem'])) {
                $prodarray['bitem'] = $passedvariables['bitem'];
            }
            $_SESSION['cart']['products'][] = $prodarray;
            $newprodnum = count($_SESSION['cart']['products']) - 1;
//
//            if ($this->orderfrm['directpidstep1'] && !$ajax) {
//                redir("pid=" . $pid);
//                exit();
//            }

            $_SESSION['cart']['newproduct'] = true;

            if ($ajax) {
                $ajax = "&ajax=1";
            } else {
                if ($passedvariables['skipconfig']) {
                    unset($_SESSION['cart']['products'][$newprodnum]['noconfig']);
                    $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $newprodnum);
                    redir("a=view");
                    exit();
                }
            }

            redir("a=confservice&i=" . $newprodnum . $ajax);


            $this->data['template'] = $templatefile;
            $this->data['smarty'] = $smartyvalues;
            return $this->data;
        }
    }

    public function confservice() {
        
    }

    public function Viewcart($check) {
        $templatefile = "viewcart";
        $errormessage = "";
        $gateways = new RA_Gateways();
        $availablegateways = getAvailableOrderPaymentGateways();
        $securityquestions = getSecurityQuestions();

        if ($check) {

            $_SESSION['cart']['paymentmethod'] = $paymentmethod;
            $_SESSION['cart']['notes'] = $notes;

            if (!$_SESSION['uid']) {
                if ($custtype == "existing") {
                    if (!validateClientLogin($loginemail, $loginpw)) {
                        $errormessage .= "<li>" . $_LANG['loginincorrect'];
                    }
                } else {
                    $_SESSION['cart']['user'] = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
                    $errormessage = checkDetailsareValid("", true, true, false);
                }
            }



            if ($contact == "new") {
                redir("a=addcontact");
                exit();
            }


            if ($contact == "addingnew") {
                $errormessage .= checkContactDetails("", false, "domaincontact");
            }


            if ($availablegateways[$paymentmethod]['type'] == "CC" && $ccinfo) {
                if ($ccinfo == "new") {
                    $errormessage .= updateCCDetails("", $cctype, $ccnumber, $cccvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum);
                }


                if (!$cccvv) {
                    $errormessage .= "<li>" . $_LANG['creditcardccvinvalid'];
                }

                $_SESSION['cartccdetail'] = encrypt(base64_encode(serialize(array($cctype, $ccnumber, $ccexpirymonth, $ccexpiryyear, $ccstartmonth, $ccstartyear, $ccissuenum, $cccvv, $nostore))));
            }

            $validate = new RA_Validate();

            run_validate_hook($validate, "ShoppingCartValidateCheckout", $_REQUEST);

            if (isset($_SESSION['uid']) && $this->ra->get_config("EnableTOSAccept")) {
                $validate->validate("required", "accepttos", "ordererroraccepttos");
            }


            if ($validate->hasErrors()) {
                $errormessage .= $validate->getHTMLErrorOutput();
            }

            $currency = getCurrency($_SESSION['uid'], $_SESSION['currency']);


            if ($_POST['updateonly']) {
                $errormessage = "";
            }


            if ($ajax && $errormessage) {
                exit($errormessage);
            }


            if (!$errormessage && !$_POST['updateonly']) {
                if (!$_SESSION['uid']) {
                    $userid = addClient($firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password, $securityqid, $securityqans);
                }


                if ($contact == "addingnew") {
                    $contact = addContact($_SESSION['uid'], $domaincontactfirstname, $domaincontactlastname, $domaincontactcompanyname, $domaincontactemail, $domaincontactaddress1, $domaincontactaddress2, $domaincontactcity, $domaincontactstate, $domaincontactpostcode, $domaincontactcountry, $domaincontactphonenumber);
                }

                $_SESSION['cart']['contact'] = $contact;
                $carttotals = calcCartTotals(true);

                if ($ccinfo == "new" && !$nostore) {
                    updateCCDetails($_SESSION['uid'], $cctype, $ccnumber, $cccvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum);
                }

                $orderid = $_SESSION['orderdetails']['OrderID'];
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
                    if ($ajax) {
                        exit();
                    }

                    redir("a=complete");
                    exit();
                }

                logActivity("Order ID " . $orderid . " Fraud Check Initiated");
                update_query("tblorders", array("status" => "Fraud"), array("id" => $orderid));

                if ($_SESSION['orderdetails']['Products']) {
                    foreach ($_SESSION['orderdetails']['Products'] as $productid) {
                        update_query("tblcustomerservices", array("servicestatus" => "Fraud"), array("id" => $productid, "servicestatus" => "Pending"));
                    }
                }


                if ($_SESSION['orderdetails']['Addons']) {
                    foreach ($_SESSION['orderdetails']['Addons'] as $addonid) {
                        update_query("tblserviceaddons", array("status" => "Fraud"), array("id" => $addonid, "status" => "Pending"));
                    }
                }




                update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $_SESSION['orderdetails']['InvoiceID'], "status" => "Unpaid"));
                $results = runFraudCheck($orderid, $fraudmodule);
                $_SESSION['orderdetails']['fraudcheckresults'] = $results;

                if ($ajax) {
                    exit();
                }

                redir("a=fraudcheck");
                exit();
            }


            if (!$paymentmethod) {
                $errormessage .= "<li>No payment gateways available so order cannot proceed";
            }
        }

        $smartyvalues['errormessage'] = $errormessage;

        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $i => $qty) {

                if (is_array($_SESSION['cart']['products'][$i])) {
                    $_SESSION['cart']['products'][$i]['qty'] = (int) $qty;
                    continue;
                }
            }
        }


        if ($promocode) {
            $promoerrormessage = SetPromoCode($promocode);

            if ($promoerrormessage) {
                $smartyvalues['errormessage'] = "<li>" . $promoerrormessage;
            }


            if ($paymentmethod) {
                $_SESSION['cart']['paymentmethod'] = $paymentmethod;
            }


            if ($notes) {
                $_SESSION['cart']['notes'] = $notes;
            }


            if ($firstname) {
                $_SESSION['cart']['user'] = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
            }
        }

        $smartyvalues['promotioncode'] = $_SESSION['cart']['promo'];
        $ignorenoconfig = ($cartsummary ? true : false);
        $carttotals = calcCartTotals("", $ignorenoconfig);
        $promotype = $carttotals['promotype'];
        $promovalue = $carttotals['promovalue'];
        $promorecurring = $carttotals['promorecurring'];
        $promodescription = ($promotype == "Percentage" ? $promovalue . "%" : $promovalue);

        if ($promotype == "Price Override") {
            $promodescription .= " " . $_LANG['orderpromopriceoverride'];
        } else {
            if ($promotype == "Free Setup") {
                $promodescription = $_LANG['orderpromofreesetup'];
            }
        }

        $promodescription .= " " . $promorecurring . " " . $_LANG['orderdiscount'];
        $smartyvalues['promotiondescription'] = $promodescription;
        foreach ($carttotals as $k => $v) {
            $smartyvalues[$k] = $v;
        }

        $smartyvalues['taxenabled'] = $CONFIG['TaxEnabled'];
        $paymentmethod = $_SESSION['cart']['paymentmethod'];

        if (!$paymentmethod) {
            foreach ($availablegateways as $k => $v) {
                $paymentmethod = $k;
                break;
            }
        }

        $smartyvalues['selectedgateway'] = $paymentmethod;
        $smartyvalues['selectedgatewaytype'] = $availablegateways[$paymentmethod]['type'];
        $smartyvalues['gateways'] = $availablegateways;
        $smartyvalues['ccinfo'] = $ccinfo;
        $smartyvalues['cctype'] = $cctype;
        $smartyvalues['ccnumber'] = $ccnumber;
        $smartyvalues['ccexpirymonth'] = $ccexpirymonth;
        $smartyvalues['ccexpiryyear'] = $ccexpiryyear;
        $smartyvalues['ccstartmonth'] = $ccstartmonth;
        $smartyvalues['ccstartyear'] = $ccstartyear;
        $smartyvalues['ccissuenum'] = $ccissuenum;
        $smartyvalues['cccvv'] = $cccvv;
        $smartyvalues['acceptedcctypes'] = explode(",", $CONFIG['AcceptedCardTypes']);
        $smartyvalues['showccissuestart'] = $CONFIG['ShowCCIssueStart'];
        $smartyvalues['shownostore'] = $CONFIG['CCAllowCustomerDelete'];
        $smartyvalues['months'] = $gateways->getCCDateMonths();
        $smartyvalues['startyears'] = $gateways->getCCStartDateYears();
        $smartyvalues['expiryyears'] = $smartyvalues['years'] = $gateways->getCCExpiryDateYears();
        $cartitems = count($carttotals['products']) + count($carttotals['addons']) + count($carttotals['domains']) + count($carttotals['renewals']);

        if (!$cartitems) {
            $allowcheckout = false;
        }

        $smartyvalues['cartitems'] = $cartitems;
        $smartyvalues['checkout'] = $allowcheckout;

        if ($_SESSION['uid']) {
            $clientsdetails = getClientsDetails();
            $clientsdetails['country'] = $clientsdetails['countryname'];
            $custtype = "existing";
            $smartyvalues['loggedin'] = true;
        } else {
            $clientsdetails = $_SESSION['cart']['user'];
            $customfields = getCustomFields("client", "", "", "", "on", $customfield);
            $_SESSION['loginurlredirect'] = "cart.php?a=login";

            if (!$custtype) {
                $custtype = "new";
            }
        }

        $smartyvalues['custtype'] = $custtype;
        $smartyvalues['clientsdetails'] = $clientsdetails;
        include "../countries.php";

        if (!isset($country)) {
            $country = $_SESSION['cart']['user']['country'];
        }

        $smartyvalues['clientcountrydropdown'] = getCountriesDropDown($country);
        $smartyvalues['password'] = $password;
        $smartyvalues['password2'] = $password2;
        $smartyvalues['customfields'] = $customfields;
        $smartyvalues['securityquestions'] = $securityquestions;
        $smartyvalues['shownotesfield'] = $CONFIG['ShowNotesFieldonCheckout'];

        if (!$notes) {
            $notes = $_LANG['ordernotesdescription'];
        }

        $smartyvalues['notes'] = $notes;
        $smartyvalues['accepttos'] = $CONFIG['EnableTOSAccept'];
        $smartyvalues['tosurl'] = $CONFIG['TermsOfService'];

        if (count($_SESSION['cart']['domains'])) {
            $smartyvalues['domainsinorder'] = true;
        }

        $domaincontacts = array();
        $result = select_query("tblcontacts", "", array("userid" => $_SESSION['uid'], "address1" => array("sqltype" => "NEQ", "value" => "")), "firstname` ASC,`lastname", "ASC");

        while ($data = mysql_fetch_array($result)) {
            $domaincontacts[] = array("id" => $data['id'], "name" => $data['firstname'] . " " . $data['lastname']);
        }

        $smartyvalues['domaincontacts'] = $domaincontacts;
        $smartyvalues['contact'] = $contact;

        if ($contact == "addingnew") {
            $addcontact = true;
        }

        $smartyvalues['addcontact'] = $addcontact;
        $smartyvalues['domaincontact'] = array("firstname" => $domaincontactfirstname, "lastname" => $domaincontactlastname, "companyname" => $domaincontactcompanyname, "email" => $domaincontactemail, "address1" => $domaincontactaddress1, "address2" => $domaincontactaddress2, "city" => $domaincontactcity, "state" => $domaincontactstate, "postcode" => $domaincontactpostcode, "country" => $domaincontactcountry, "phonenumber" => $domaincontactphonenumber);
        $smartyvalues['domaincontactcountrydropdown'] = getCountriesDropDown($domaincontactcountry, "domaincontactcountry");
        $gatewaysoutput = array();
        foreach ($availablegateways as $module => $vals) {
            $params = getGatewayVariables($module);
            $params['amount'] = $carttotals['rawtotal'];
            $params['currency'] = $currency['code'];

            if (function_exists($module . "_orderformoutput")) {
                $gatewaysoutput[] = call_user_func($module . "_orderformoutput", $params);
                continue;
            }
        }

        $smartyvalues['gatewaysoutput'] = $gatewaysoutput;

        if ($cartsummary) {
            $ajax = "1";
            $templatefile = "cartsummary";
            $productinfo = $orderfrm->setPid($_SESSION['cart']['cartsummarypid']);
        }

        $this->data['template'] = $templatefile;
        $this->data['smarty'] = $smartyvalues;
        return $this->data;
    }

}
