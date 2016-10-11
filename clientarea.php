<?php

/**
 *
 * @ RA
 * */
define("CLIENTAREA", true);
require "init.php";
require "includes/clientfunctions.php";
require "includes/gatewayfunctions.php";
require "includes/ccfunctions.php";
require "includes/customfieldfunctions.php";
require "includes/invoicefunctions.php";
require "includes/configoptionsfunctions.php";
$action = $ra->get_req_var("action");
$sub = $ra->get_req_var("sub");
$id = (int) $ra->get_req_var("id");
$q = $ra->get_req_var("q");
$paymentmethod = RA_Gateways::makesafename($ra->get_req_var("paymentmethod"));

if ($action == "changesq" || $ra->get_req_var("2fasetup")) {
    $action = "security";
}

$ca = new RA_ClientArea();
$ca->setPageTitle($ra->get_lang("clientareatitle"));
$ca->addToBreadCrumb("index.php", $ra->get_lang("globalsystemname"));
$ca->addToBreadCrumb("clientarea.php", $ra->get_lang("clientareatitle"));
$ca->initPage();
$ca->requireLogin();

if ($action == "details") {
    $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
}


if ($action == "hosting") {
    $ca->addToBreadCrumb("clientarea.php?action=hosting", $ra->get_lang("clientareanavhosting"));
}


if (in_array($action, array("products", "cancel"))) {
    $ca->addToBreadCrumb("clientarea.php?action=products", $ra->get_lang("clientareaproducts"));
}


if ($action == "invoices") {
    $ca->addToBreadCrumb("clientarea.php?action=invoices", $ra->get_lang("invoices"));
}


if ($action == "emails") {
    $ca->addToBreadCrumb("clientarea.php?action=emails", $ra->get_lang("clientareaemails"));
}


if ($action == "addfunds") {
    $ca->addToBreadCrumb("clientarea.php?action=addfunds", $ra->get_lang("addfunds"));
}


if ($action == "masspay") {
    $ca->addToBreadCrumb("clientarea.php?action=masspay" . ($all ? "&all=true" : "") . "#", $ra->get_lang("masspaytitle"));
}


if ($action == "quotes") {
    $ca->addToBreadCrumb("clientarea.php?action=quotes", $ra->get_lang("quotestitle"));
}

$client = new RA_Client(RA_Session::get("uid"));
$currency = $client->getCurrency();
$ca->assign("action", $action);
$ca->assign("clientareaaction", $action);

if ($action == "") {
    $ca->setTemplate("clientareahome");
    require "includes/ticketfunctions.php";
    $tickets = array();
    $statusfilter = "";
    $result = select_query_i("tblticketstatuses", "title", array("showactive" => "1"));

    while ($data = mysqli_fetch_array($result)) {
        $statusfilter .= "'" . $data[0] . "',";
    }

    $statusfilter = substr($statusfilter, 0, 0 - 1);
    $result = select_query_i("tbltickets", "", "userid=" . (int) ($client->getID()) . (" AND status IN (" . $statusfilter . ")"), "lastreply", "DESC");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $tid = $data['tid'];
        $c = $data['c'];
        $deptid = $data['did'];
        $date = $data['date'];
        $date = fromMySQLDate($date, 1, 1);
        $subject = $data['title'];
        $tstatus = $data['status'];
        $urgency = $data['urgency'];
        $lastreply = $data['lastreply'];
        $lastreply = fromMySQLDate($lastreply, 1, 1);
        $clientunread = $data['clientunread'];
        $tstatus = getStatusColour($tstatus);
        $dept = getDepartmentName($deptid);
        $urgency = $_LANG["supportticketsticketurgency" . strtolower($urgency)];
        $tickets[] = array("id" => $id, "tid" => $tid, "c" => $c, "date" => $date, "department" => $dept, "subject" => $subject, "status" => $tstatus, "urgency" => $urgency, "lastreply" => $lastreply, "unread" => $clientunread);
    }

    $ca->assign("tickets", $tickets);
    $invoice = new RA_Invoice();
    $invoices = $invoice->getInvoices("Unpaid", $client->getID(), "id", "DESC");
    $exdetails = $client->getDetails();

    $ca->assign("clientfirstname", $ra->get_req_var_if($e, "firstname", $exdetails));
    $ca->assign("clientlastname", $ra->get_req_var_if($e, "lastname", $exdetails));
    $ca->assign("clientcompanyname", $ra->get_req_var_if($e, "companyname", $exdetails));
    $ca->assign("invoices", $invoices);

    $ca->assign("totalbalance", $invoice->getTotalBalanceFormatted());
    $ca->assign("masspay", $CONFIG['EnableMassPay']);
    $ca->assign("defaultpaymentmethod", getGatewayName($clientsdetails['defaultgateway']));
    $files = $client->getFiles($client->getID());
    $ca->assign("files", $files);
    $ca->assign("addfundsenabled", $CONFIG['AddFundsEnabled']);
    $announcements = array();
    $result = select_query_i("tblannouncements", "", array("published" => "on"), "date", "DESC", "0,3");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $date = $data['date'];
        $title = $data['title'];
        $announcement = $data['announcement'];
        $result2 = select_query_i("tblannouncements", "", array("parentid" => $id, "language" => $_SESSION['Language']));
        $data = mysqli_fetch_array($result2);

        if ($data['title']) {
            $title = $data['title'];
        }


        if ($data['announcement']) {
            $announcement = $data['announcement'];
        }

        $date = fromMySQLDate($date);
        $announcements[] = array("id" => $id, "date" => $date, "title" => $title, "urlfriendlytitle" => getModRewriteFriendlyString($title), "text" => $announcement);
    }



    $smartyvalues['announcements'] = $announcements;

    $captcha = clientAreaInitCaptcha();
    $smartyvalues['captcha'] = $captcha;
    $smartyvalues['recaptchahtml'] = clientAreaReCaptchaHTML();
    $addons_html = run_hook("ClientAreaHomepage", array());
    $ca->assign("addons_html", $addons_html);
} else {
    if ($action == "details") {
        checkContactPermission("profile");
        $ca->setTemplate("clientareadetails");
        $uneditablefields = explode(",", $CONFIG['ClientsProfileUneditableFields']);
        $smartyvalues['uneditablefields'] = $uneditablefields;
        $e = "";

        if ($save) {
            check_token();
            $e = checkDetailsareValid($client->getID(), false);

            if ($e) {
                $ca->assign("errormessage", $e);
            } else {
                $client->updateClient();
                $ca->assign("successful", true);
            }
        }


        if (!$e) {
            $exdetails = $client->getDetails();
        }




        include "includes/countries.php";
        $key = array_search($ra->get_req_var_if($e, "country", $exdetails), $countries);
     
        $ca->assign("clientfirstname", $ra->get_req_var_if($e, "firstname", $exdetails));
        $ca->assign("clientlastname", $ra->get_req_var_if($e, "lastname", $exdetails));
        $ca->assign("clientcompanyname", $ra->get_req_var_if($e, "companyname", $exdetails));
        $ca->assign("clientemail", $ra->get_req_var_if($e, "email", $exdetails));
        $ca->assign("clientaddress1", $ra->get_req_var_if($e, "address1", $exdetails));
        $ca->assign("clientaddress2", $ra->get_req_var_if($e, "address2", $exdetails));
        $ca->assign("clientcity", $ra->get_req_var_if($e, "city", $exdetails));
        $ca->assign("clientstate", $ra->get_req_var_if($e, "state", $exdetails));
        $ca->assign("clientpostcode", $ra->get_req_var_if($e, "postcode", $exdetails));
        $ca->assign("clientcountry", $countries[$ra->get_req_var_if($e, "country", $exdetails)]);
        $ca->assign("clientcountriesdropdown", getCountriesDropDown($key));
        $ca->assign("clientphonenumber", $ra->get_req_var_if($e, "phonenumber", $exdetails));
        $ca->assign("customfields", getCustomFields("client", "", $client->getID(), "", "", $_POST['customfield']));
        $ca->assign("contacts", $client->getContacts());
        $ca->assign("billingcid", $ra->get_req_var_if($e, "billingcid", $exdetails));
        $ca->assign("paymentmethods", showPaymentGatewaysList());
        $ca->assign("emailoptout", $ra->get_req_var_if($e, "emailoptout", $exdetails));
        $ca->assign("emailoptoutenabled", $ra->get_config("AllowClientsEmailOptOut"));
        $ca->assign("defaultpaymentmethod", $ra->get_req_var_if($e, "defaultgateway", $exdetails));
    } else {
        if ($action == "contacts") {
            checkContactPermission("contacts");
            $ca->setTemplate("clientareacontacts");
            $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
            $ca->addToBreadCrumb("clientarea.php?action=contacts", $ra->get_lang("clientareanavcontacts"));
            $contact_data = array();

            if ($contactid) {
                if ($contactid == "new") {
                    redir("action=addcontact");
                }

                $id = (int) $contactid;
            }

            if ($id) {
                $contact_data = $client->getContact($id);

                if (!$contact_data) {
                    redir("action=contacts", "clientarea.php");
                }

                $id = $contact_data['id'];
            }

            if ($delete) {
                $client->deleteContact($id);
                redir("action=contacts");
            }

            $e = "";

            if ($submit) {
                check_token();
                $errormessage = $e = checkContactDetails($id);

                if (!$subaccount) {
                    $password = $permissions = "";
                }

                $smartyvalues['errormessage'] = $errormessage;

                if (!$errormessage) {
                    $oldcontactdata = get_query_vals("tblcontacts", "", array("userid" => $client->getID(), "id" => $id));
                    $array = db_build_update_array(array("firstname", "lastname", "companyname", "email", "address1", "address2", "city", "state", "postcode", "country", "phonenumber", "subaccount", "permissions", "generalemails", "productemails", "descriptionemails", "invoiceemails", "supportemails"), "implode");
                    $array['subaccount'] = ($subaccount ? "1" : "0");

                    if ($password) {
                        $array['password'] = generateClientPW($password);
                    }

                    update_query("tblcontacts", $array, array("userid" => $client->getID(), "id" => $id));
                    run_hook("ContactEdit", array_merge(array("userid" => $client->getID(), "contactid" => $id, "olddata" => $oldcontactdata), $array));
                    logActivity("Client Contact Modified - Contact ID: " . $id . " - User ID: " . $client->getID());
                    $smartyvalues['successful'] = true;
                }
            }

            if ($success) {
                $smartyvalues['successful'] = true;
            }

            $contactsarray = $client->getContacts();

            if (!$id && count($contactsarray)) {
                $id = $contactsarray[0]['id'];
            }

            $smartyvalues['contacts'] = $contactsarray;
            include "includes/countries.php";
            $smartyvalues['contactid'] = $id;

            if (((!$errormessage && $submit) && $id) || ($id && !count($contact_data))) {
                $contact_data = $client->getContact($id);

                if (!$contact_data) {
                    redir("action=contacts", "clientarea.php");
                }
            }

            $smartyvalues['contactfirstname'] = $ra->get_req_var_if($e, "firstname", $contact_data);
            $smartyvalues['contactlastname'] = $ra->get_req_var_if($e, "lastname", $contact_data);
            $smartyvalues['contactcompanyname'] = $ra->get_req_var_if($e, "companyname", $contact_data);
            $smartyvalues['contactemail'] = $ra->get_req_var_if($e, "email", $contact_data);
            $smartyvalues['contactaddress1'] = $ra->get_req_var_if($e, "address1", $contact_data);
            $smartyvalues['contactaddress2'] = $ra->get_req_var_if($e, "address2", $contact_data);
            $smartyvalues['contactcity'] = $ra->get_req_var_if($e, "city", $contact_data);
            $smartyvalues['contactstate'] = $ra->get_req_var_if($e, "state", $contact_data);
            $smartyvalues['contactpostcode'] = $ra->get_req_var_if($e, "postcode", $contact_data);
            $smartyvalues['contactphonenumber'] = $ra->get_req_var_if($e, "phonenumber", $contact_data);
            $smartyvalues['countriesdropdown'] = getCountriesDropDown($ra->get_req_var_if($e, "country", $contact_data));
            $smartyvalues['subaccount'] = $ra->get_req_var_if($e, "subaccount", $contact_data);
            $smartyvalues['permissions'] = $ra->get_req_var_if($e, "permissions", $contact_data);
            $smartyvalues['generalemails'] = $ra->get_req_var_if($e, "generalemails", $contact_data);
            $smartyvalues['productemails'] = $ra->get_req_var_if($e, "productemails", $contact_data);
            $smartyvalues['invoiceemails'] = $ra->get_req_var_if($e, "invoiceemails", $contact_data);
            $smartyvalues['supportemails'] = $ra->get_req_var_if($e, "supportemails", $contact_data);
        } else {
            if ($action == "addcontact") {
                checkContactPermission("contacts");
                $ca->setTemplate("clientareaaddcontact");
                $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
                $ca->addToBreadCrumb("clientarea.php?action=addcontact", $ra->get_lang("clientareanavaddcontact"));
                include "includes/countries.php";

                if ($submit) {
                    check_token();
                    $errormessage = checkContactDetails("", true);

                    if (!$subaccount) {
                        $password = $permissions = "";
                    }

                    $smartyvalues['errormessage'] = $errormessage;

                    if (!$errormessage) {
                        $contactid = addContact($client->getID(), $firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password, $permissions, $generalemails, $productemails, $descriptionemails, $invoiceemails, $supportemails);
                        redir("action=contacts&id=" . $contactid . "&success=1");
                        exit();
                    }
                }

                $contactsarray = $client->getContacts();
                $smartyvalues['contacts'] = $contactsarray;

                if (!$permissions) {
                    $permissions = array();
                }

                $smartyvalues['contactfirstname'] = $firstname;
                $smartyvalues['contactlastname'] = $lastname;
                $smartyvalues['contactcompanyname'] = $companyname;
                $smartyvalues['contactemail'] = $email;
                $smartyvalues['contactaddress1'] = $address1;
                $smartyvalues['contactaddress2'] = $address2;
                $smartyvalues['contactcity'] = $city;
                $smartyvalues['contactstate'] = $state;
                $smartyvalues['contactpostcode'] = $postcode;
                $smartyvalues['contactphonenumber'] = $phonenumber;
                $smartyvalues['countriesdropdown'] = getCountriesDropDown($country);
                $smartyvalues['subaccount'] = $subaccount;
                $smartyvalues['permissions'] = $permissions;
                $smartyvalues['generalemails'] = $generalemails;
                $smartyvalues['productemails'] = $productemails;
                $smartyvalues['invoiceemails'] = $invoiceemails;
                $smartyvalues['supportemails'] = $supportemails;
            } else {
                if ($action == "creditcard") {
                    if (!CALinkUpdateCC()) {
                        redir();
                    }

                    checkContactPermission("invoices");
                    $ca->setTemplate("clientareacreditcard");
                    $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
                    $ca->addToBreadCrumb("clientarea.php?action=creditcard", $ra->get_lang("clientareanavchangecc"));
                    $gateways = new RA_Gateways();
                    $gotpm = false;

                    if (!$gotpm) {
                        $result = select_query_i("tblpaymentgateways", "gateway", array("setting" => "type", "value" => "CC"));
                    }

                    while ($data = mysqli_fetch_array($result)) {
                        $gateway = $data['gateway'];

                        if (function_exists($gateway . "_remoteupdate")) {
                            $params = getGatewayVariables($gateway);
                            $result = select_query_i("tblclients", "gatewayid", array("id" => $client->getID()));
                            $data = mysqli_fetch_array($result);
                            $params['gatewayid'] = $data['gatewayid'];
                            $remoteupdatecode = call_user_func($gateway . "_remoteupdate", $params);

                            if (!$remoteupdatecode) {
                                $remoteupdatecode = $_LANG['creditcardupdatenotpossible'];
                            }

                            $smartyvalues['remoteupdatecode'] = $remoteupdatecode;
                        }
                    }

                    if ($submit) {
                        check_token();
                        $errormessage = updateCCDetails($client->getID(), $cctype, $ccnumber, $cardcvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum);

                        if (!$errormessage) {
                            $smartyvalues['successful'] = true;
                        }
                    }

                    if ($delete && $CONFIG['CCAllowCustomerDelete']) {
                        updateCCDetails($client->getID(), "", "", "", "", "");
                        $errormessage = "<li>" . $_LANG['creditcarddeleteconfirmation'];
                    }

                    $smartyvalues['errormessage'] = $errormessage;
                    $data = getCCDetails($client->getID());
                    $smartyvalues['cardtype'] = $data['cardtype'];
                    $smartyvalues['cardnum'] = $data['cardnum'];
                    $smartyvalues['cardexp'] = $data['expdate'];
                    $smartyvalues['cardstart'] = $data['startdate'];
                    $smartyvalues['cardissuenum'] = $data['issuenumber'];
                    $acceptedcctypes = $CONFIG['AcceptedCardTypes'];
                    $acceptedcctypes = explode(",", $acceptedcctypes);
                    $smartyvalues['acceptedcctypes'] = $acceptedcctypes;
                    $smartyvalues['showccissuestart'] = $CONFIG['ShowCCIssueStart'];
                    $smartyvalues['allowcustomerdelete'] = $CONFIG['CCAllowCustomerDelete'];
                    $smartyvalues['cctype'] = $cctype;
                    $smartyvalues['ccnumber'] = $ccnumber;
                    $smartyvalues['ccexpirymonth'] = $ccexpirymonth;
                    $smartyvalues['ccexpiryyear'] = $ccexpiryyear;
                    $smartyvalues['ccstartmonth'] = $ccstartmonth;
                    $smartyvalues['ccstartyear'] = $ccstartyear;
                    $smartyvalues['ccissuenum'] = $ccissuenum;
                    $smartyvalues['months'] = $gateways->getCCDateMonths();
                    $smartyvalues['startyears'] = $gateways->getCCStartDateYears();
                    $smartyvalues['expiryyears'] = $smartyvalues['years'] = $gateways->getCCExpiryDateYears();
                } else {
                    if ($action == "changepw") {
                        $ca->setTemplate("clientareachangepw");
                        $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
                        $ca->addToBreadCrumb("clientarea.php?action=changepw", $ra->get_lang("clientareanavchangepw"));
                        $validate = new RA_Validate();

                        if ($submit) {
                            check_token();
                            $existingpw = html_entity_decode($existingpw);
                            $newpw = html_entity_decode($newpw);
                            $confirmpw = html_entity_decode($confirmpw);

                            if ($_SESSION['cid']) {
                                $result = select_query_i("tblcontacts", "password", array("id" => $_SESSION['cid'], "userid" => $client->getID()));
                            } else {
                                $result = select_query_i("tblclients", "password", array("id" => $client->getID()));
                            }

                            $data = mysqli_fetch_array($result);

                            $existingpwd = $data['password'];
                            $salt = explode(":", $existingpwd);
                            $salt = $salt[1];
                            $existingpw = generateClientPW($existingpw, $salt);

                            if ($validate->validate("match_value", "existingpwd", "existingpasswordincorrect", array($existingpw, $existingpwd))) {
                                if ($validate->validate("required", "newpw", "ordererrorpassword")) {
                                    if ($validate->validate("pwstrength", "newpw", "pwstrengthfail")) {
                                        if ($validate->validate("required", "confirmpw", "clientareaerrorpasswordconfirm")) {
                                            $validate->validate("match_value", "newpw", "clientareaerrorpasswordnotmatch", "confirmpw");
                                        }
                                    }
                                }
                            }

                            if (!$validate->hasErrors()) {
                                if ($_SESSION['cid']) {
                                    update_query("tblcontacts", array("password" => generateClientPW($newpw)), array("id" => $_SESSION['cid'], "userid" => $client->getID()));
                                } else {
                                    update_query("tblclients", array("password" => generateClientPW($newpw)), array("id" => $client->getID()));
                                    run_hook("ClientChangePassword", array("userid" => $client->getID(), "password" => $newpw));
                                }

                                logActivity("Modified Password - User ID: " . $client->getID() . ($_SESSION['cid'] ? " - Contact ID: " . $_SESSION['cid'] : ""));
                                $smartyvalues['successful'] = true;
                            }
                        }

                        $smartyvalues['errormessage'] = $validate->getHTMLErrorOutput();
                    } else {
                        if ($action == "security") {
                            checkContactPermission("changesq");
                            $ca->setTemplate("clientareasecurity");
                            $ca->addToBreadCrumb("clientarea.php?action=details", $ra->get_lang("clientareanavdetails"));
                            $ca->addToBreadCrumb("clientarea.php?action=security", $ra->get_lang("clientareanavsecurity"));

                            if ($ra->get_req_var("successful")) {
                                $smartyvalues['successful'] = true;
                            }

                            $securityquestions = getSecurityQuestions("");
                            $smartyvalues['securityquestions'] = $securityquestions;
                            $smartyvalues['securityquestionsenabled'] = (count($securityquestions) ? true : false);
                            $clientsdetails = getClientsDetails($client->getID());

                            if ($clientsdetails['securityqid'] == 0) {
                                $smartyvalues['nocurrent'] = true;
                            } else {
                                foreach ($securityquestions as $values) {

                                    if ($values['id'] == $clientsdetails['securityqid']) {
                                        $smartyvalues['currentquestion'] = $values['question'];
                                        continue;
                                    }
                                }
                            }

                            if ($ra->get_req_var("submit")) {
                                check_token();

                                if ($clientsdetails['securityqid'] && $clientsdetails['securityqans'] != $currentsecurityqans) {
                                    $errormessage .= "<li>" . $_LANG['securitycurrentincorrect'];
                                }

                                if (!$securityqans) {
                                    $errormessage .= "<li>" . $_LANG['securityanswerrequired'];
                                }

                                if ($securityqans != $securityqans2) {
                                    $errormessage .= "<li>" . $_LANG['securitybothnotmatch'];
                                }

                                if (!$errormessage) {
                                    update_query("tblclients", array("securityqid" => $securityqid, "securityqans" => encrypt($securityqans)), array("id" => $client->getID()));
                                    logActivity("Modified Security Question - User ID: " . $client->getID());
                                    redir("action=changesq&successful=true");
                                    exit();
                                }
                            }

                            $smartyvalues['errormessage'] = $errormessage;
                        } else {
                            if ($action == "hosting" || $action == "product" || $action == "services") {
                                checkContactPermission("products");
                                if ($action == "product") {
                                    $title = "My Products";
                                } else {
                                    $title = "My Services";
                                }
                                $ca->setPageTitle($title);
                                $ca->setTemplate("clientareaproducts");
                                $table = "tblcustomerservices";
                                $fields = "COUNT(*)";
                                $where = "userid='" . db_escape_string($client->getID()) . "' AND tblservices.type='" . $action . "' ";

                                if ($q) {
                                    $q = preg_replace("/[^a-z0-9-.]/", "", strtolower($q));
                                    $where .= "  AND description LIKE '%" . db_escape_string($q) . "%'";
                                    $smartyvalues['q'] = $q;
                                }

                                $innerjoin = "tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblservicegroups ON tblservicegroups.id=tblservices.gid";
                                $result = select_query_i($table, $fields, $where, "", "", "", $innerjoin);
                                $data = mysqli_fetch_array($result);
                                $numitems = $data[0];
                                list($orderby, $sort, $limit) = clientAreaTableInit("prod", "product", "ASC", $numitems);
                                $smartyvalues['orderby'] = $orderby;
                                $smartyvalues['sort'] = strtolower($sort);

                                if ($orderby == "price") {
                                    $orderby = "amount";
                                } else {
                                    if ($orderby == "billingcycle") {
                                        $orderby = "billingcycle";
                                    } else {
                                        if ($orderby == "nextduedate") {
                                            $orderby = "nextduedate";
                                        } else {
                                            if ($orderby == "status") {
                                                $orderby = "servicestatus";
                                            } else {
                                                $orderby = "description` " . $sort . ",`tblservices`.`name";
                                            }
                                        }
                                    }
                                }

                                $accounts = array();
                                $fields = "tblcustomerservices.*,tblservicegroups.name AS productgroup,tblservices.name,tblservices.type,tblservices.tax,tblservices.servertype";
                                $result = select_query_i($table, $fields, $where, $orderby, $sort, $limit, $innerjoin);

                                while ($data = mysqli_fetch_array($result)) {
                                    $id = $data['id'];
                                    $regdate = $data['regdate'];
                                    $description = $data['description'];
                                    $firstpaymentamount = $data['firstpaymentamount'];
                                    $recurringamount = $data['amount'];
                                    $nextduedate = $data['nextduedate'];
                                    $billingcycle = $data['billingcycle'];
                                    $status = $data['servicestatus'];
                                    $upgradepackages = count(unserialize($data['upgradepackages']));
                                    $downloads = unserialize($data['downloads']);
                                    $productgroup = $data['productgroup'];
                                    $productname = $data['name'];
                                    $tax = $data['tax'];
                                    $server = $data['server'];
                                    $username = $data['username'];
                                    $module = $data['servertype'];
                                    $downloadscount = 0;

                                    if (is_array($downloads)) {
                                        foreach ($downloads as $dl) {

                                            if (trim($dl)) {
                                                ++$downloadscount;
                                                continue;
                                            }
                                        }
                                    }

                                    $serverarray = array();

                                    if ($server) {
                                        $result2 = select_query_i("tblservers", "", array("id" => $server));
                                        $serverarray = mysqli_fetch_array($result2);
                                    }

                                    if ($tax) {
                                        
                                    }

                                    $regdate = fromMySQLDate($regdate, 0, 1, "-");
                                    $nextduedate = fromMySQLDate($nextduedate, 0, 1, "-");
                                    $langbillingcycle = $ca->getRawStatus($billingcycle);
                                    $rawstatus = $ca->getRawStatus($status);
                                    $xstatus = $status;

                                    if ($status == "Active") {
                                        $xcolor = "clientarealistactive";
                                    } else {
                                        if ($status == "Completed") {
                                            $xcolor = "clientarealistactive";
                                        } else {
                                            if ($status == "Pending") {
                                                $xcolor = "clientarealistpending";
                                            } else {
                                                if ($status == "Suspended") {
                                                    $xcolor = "clientarealistsuspended";
                                                } else {
                                                    $xcolor = "clientarealistterminated";
                                                    $xstatus = "terminated";
                                                }
                                            }
                                        }
                                    }

                                    $accounts[] = array("id" => $id, "regdate" => $regdate, "group" => $productgroup, "product" => $productname, "module" => $module, "server" => $serverarray, "description" => $description, "firstpaymentamount" => formatCurrency($firstpaymentamount), "recurringamount" => formatCurrency($recurringamount), "amount" => ($billingcycle == "One Time" ? formatCurrency($firstpaymentamount) : formatCurrency($recurringamount)), "nextduedate" => $nextduedate, "billingcycle" => $_LANG["orderpaymentterm" . $langbillingcycle], "username" => $username, "status" => $status, "rawstatus" => $rawstatus, "statustext" => $_LANG["clientarea" . $rawstatus], "class" => strtolower($xstatus), "addons" => (get_query_val("tblserviceaddons", "id", array("hostingid" => $id), "id", "DESC") ? "1" : ""), "packagesupgrade" => ($upgradepackages ? "1" : ""), "downloads" => ($downloads ? "1" : ""), "showcancelbutton" => $CONFIG['ShowCancellationButton']);
                                }

                                $ca->assign("services", $accounts);
                                $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
                            } else {
                                if ($action == "productdetails") {
                                    checkContactPermission("products");
                                    $ca->setTemplate("clientareaproductdetails");
                                    $service = new RA_Service($id, $client->getID());

                                    if ($service->isNotValid()) {
                                        redir("action=products", "clientarea.php");
                                    }

                                    $ca->addToBreadCrumb("clientarea.php?action=products", $ra->get_lang("clientareaproducts"));
                                    $ca->addToBreadCrumb("clientarea.php?action=productdetails#", $ra->get_lang("clientareaproductdetails"));
                                    $customfields = $service->getCustomFields();
                                    $ca->assign("id", $service->getData("id"));
                                    $ca->assign("pid", $service->getData("packageid"));
                                    $ca->assign("type", $service->getData("type"));
                                    $ca->assign("regdate", fromMySQLDate($service->getData("regdate"), 0, 1, "-"));
                                    $ca->assign("modulename", $service->getModule());
                                    $ca->assign("module", $service->getModule());
                                    $ca->assign("serverdata", $service->getServerInfo());
                                    $ca->assign("description", $service->getData("description"));
                                    $ca->assign("groupname", $service->getData("groupname"));
                                    $ca->assign("product", $service->getData("productname"));
                                    $ca->assign("paymentmethod", $service->getPaymentMethod());
                                    $ca->assign("firstpaymentamount", formatCurrency($service->getData("firstpaymentamount")));
                                    $ca->assign("recurringamount", formatCurrency($service->getData("amount")));
                                    $ca->assign("billingcycle", $service->getBillingCycleDisplay());
                                    $ca->assign("nextduedate", fromMySQLDate($service->getData("nextduedate"), 0, 1, "-"));
                                    $ca->assign("status", $service->getStatusDisplay());
                                    $ca->assign("rawstatus", strtolower($service->getData("status")));
                                    $ca->assign("dedicatedip", $service->getData("dedicatedip"));
                                    $ca->assign("assignedips", $service->getData("assignedips"));
                                    $ca->assign("ns1", $service->getData("ns1"));
                                    $ca->assign("ns2", $service->getData("ns2"));
                                    $ca->assign("packagesupgrade", $service->getAllowProductUpgrades());
                                    $ca->assign("configoptionsupgrade", $service->getAllowConfigOptionsUpgrade());
                                    $ca->assign("customfields", $customfields);
                                    $ca->assign("productcustomfields", $customfields);
                                    $ca->assign("suspendreason", $service->getSuspensionReason());
                                    $ca->assign("subscriptionid", $service->getData("subscriptionid"));
                                    $diskstats = $service->getDiskUsageStats();
                                    foreach ($diskstats as $k => $v) {
                                        $ca->assign($k, $v);
                                    }

                                    $ca->assign("showcancelbutton", $service->getAllowCancellation());
                                    $ca->assign("configurableoptions", $service->getConfigurableOptions());
                                    $ca->assign("addons", $service->getAddons());
                                    $ca->assign("addonsavailable", $service->hasProductGotAddons());
                                    $ca->assign("downloads", $service->getAssociatedDownloads());
                                    $servicepw = $service->getData("password");
                                    $moduleclientarea = "";

                                    if ($service->getModule() && $service->getData("status") == "Active") {
                                        $allowedclientmodulefunctions = array();
                                        $success = $service->moduleCall("ClientAreaAllowedFunctions");

                                        if ($success) {
                                            $data = $service->getModuleReturn("data");

                                            if (is_array($data)) {
                                                foreach ($data as $v) {
                                                    $allowedclientmodulefunctions[] = $v;
                                                }
                                            }
                                        }

                                        $success = $service->moduleCall("ClientAreaCustomButtonArray");

                                        if ($success) {
                                            $data = $service->getModuleReturn("data");
                                            $ca->assign("servercustombuttons", $data);
                                            $ca->assign("modulecustombuttons", $data);

                                            if (is_array($data)) {
                                                foreach ($data as $k => $v) {
                                                    $allowedclientmodulefunctions[] = $v;
                                                }
                                            }
                                        }

                                        $modop = $ra->get_req_var("modop");

                                        if ($ra->get_req_var("serveraction")) {
                                            $modop = $ra->get_req_var("serveraction");
                                        }

                                        $a = $ra->get_req_var("a");

                                        if ($modop == "custom" && in_array($a, $allowedclientmodulefunctions)) {
                                            checkContactPermission("manageproducts");
                                            $success = $service->moduleCall($a);

                                            if ($success) {
                                                $data = $service->getModuleReturn("data");

                                                if (is_array($data)) {
                                                    if (isset($data['templatefile'])) {
                                                        $ca->setTemplate("/modules/servers/" . $service->getModule() . "/" . $data['templatefile'] . ".tpl");
                                                    }

                                                    if (isset($data['breadcrumb'])) {
                                                        if (is_array($data['breadcrumb'])) {
                                                            foreach ($data['breadcrumb'] as $k => $v) {
                                                                $ca->addToBreadCrumb($k, $v);
                                                            }
                                                        }
                                                    }

                                                    if (is_array($data['vars'])) {
                                                        foreach ($data['vars'] as $k => $v) {
                                                            $smartyvalues[$k] = $v;
                                                        }
                                                    }
                                                } else {
                                                    $ca->assign("modulecustombuttonresult", "success");
                                                }
                                            } else {
                                                $ca->assign("modulecustombuttonresult", $service->getLastError());
                                            }
                                        }

                                        if ($service->hasFunction("ChangePassword") && $service->getAllowChangePassword()) {
                                            $ca->assign("serverchangepassword", true);
                                            $ca->assign("modulechangepassword", true);
                                            $modulechangepasswordmessage = "";
                                            $modulechangepassword = $ra->get_req_var("modulechangepassword");

                                            if ($ra->get_req_var("serverchangepassword")) {
                                                $modulechangepassword = true;
                                            }

                                            if ($modulechangepassword) {
                                                check_token();
                                                checkContactPermission("manageproducts");
                                                $newpwfield = "newpw";
                                                $newpassword1 = $ra->get_req_var("newpw");
                                                $newpassword2 = $ra->get_req_var("confirmpw");
                                                foreach (array("newpassword1", "newserverpassword1") as $key) {

                                                    if (!$newpassword1 && $ra->get_req_var($key)) {
                                                        $newpwfield = $key;
                                                        $newpassword1 = $ra->get_req_var($key);
                                                        continue;
                                                    }
                                                }

                                                foreach (array("newpassword2", "newserverpassword2") as $key) {

                                                    if ($ra->get_req_var($key)) {
                                                        $newpassword2 = $ra->get_req_var($key);
                                                        continue;
                                                    }
                                                }

                                                $validate = new RA_Validate();

                                                if ($validate->validate("match_value", "newpw", "clientareaerrorpasswordnotmatch", array($newpassword1, $newpassword2))) {
                                                    $validate->validate("pwstrength", $newpwfield, "pwstrengthfail");
                                                }

                                                if ($validate->hasErrors()) {
                                                    $modulechangepwresult = "error";
                                                    $modulechangepasswordmessage = $validate->getHTMLErrorOutput();
                                                } else {
                                                    update_query("tblcustomerservices", array("password" => encrypt($newpassword1)), array("id" => $id));
                                                    $success = $service->moduleCall("ChangePassword", array("password" => html_entity_decode($newpassword1)));

                                                    if ($success) {
                                                        logActivity("Module Change Password Successful - Service ID: " . $id);
                                                        $modulechangepwresult = "success";
                                                        $modulechangepasswordmessage = $_LANG['serverchangepasswordsuccessful'];
                                                        $servicepw = $newpassword1;
                                                    } else {
                                                        $modulechangepwresult = "error";
                                                        $modulechangepasswordmessage = $_LANG['serverchangepasswordfailed'];
                                                        update_query("tblcustomerservices", array("password" => encrypt($servicepw)), array("id" => $id));
                                                    }
                                                }

                                                $smartyvalues['modulechangepwresult'] = $modulechangepwresult;
                                                $smartyvalues['modulechangepasswordmessage'] = $modulechangepasswordmessage;
                                            }
                                        }

                                        if (checkContactPermission("manageproducts", true)) {
                                            $moduletemplatefile = "";
                                            $success = $service->moduleCall("ClientArea");
                                            $data = $service->getModuleReturn("data");

                                            if (is_array($data)) {
                                                if (isset($data['templatefile'])) {
                                                    $moduletemplatefile = "/modules/servers/" . $service->getModule() . "/" . $data['templatefile'] . ".tpl";
                                                }
                                            } else {
                                                $moduleclientarea = ($data != FUNCTIONDOESNTEXIST ? $data : "");
                                            }

                                            if (!$moduletemplatefile && file_exists(ROOTDIR . "/modules/servers/" . $service->getModule() . "/clientarea.tpl")) {
                                                $moduletemplatefile = "/modules/servers/" . $service->getModule() . "/clientarea.tpl";
                                            }

                                            if ($moduletemplatefile) {
                                                $moduleparams = $service->buildParams();

                                                if ((is_array($data) && array_key_exists("vars", $data)) && is_array($data['vars'])) {
                                                    foreach ($data['vars'] as $k => $v) {
                                                        $moduleparams[$k] = $v;
                                                    }
                                                }

                                                $moduleclientarea = $ca->getSingleTPLOutput($moduletemplatefile, $moduleparams);
                                            }
                                        }
                                    }

                                    if (checkContactPermission("manageproducts", true)) {
                                        $ca->assign("serverclientarea", $moduleclientarea);
                                        $ca->assign("moduleclientarea", $moduleclientarea);
                                        $ca->assign("username", $service->getData("username"));
                                        $ca->assign("password", $servicepw);
                                    }
                                } else {
                                    if ($action == "descriptions") {
                                        checkContactPermission("descriptions");
                                        $ca->setTemplate("clientareadescriptions");
                                        $where = "userid='" . db_escape_string($client->getID()) . "'";

                                        if ($q) {
                                            $q = preg_replace("/[^a-z0-9-.]/", "", strtolower($q));
                                            $where .= " AND description LIKE '%" . db_escape_string($q) . "%'";
                                            $smartyvalues['q'] = $q;
                                        }

                                        $result = select_query_i("tbldescriptions", "COUNT(*)", $where);
                                        $data = mysqli_fetch_array($result);
                                        $numitems = $data[0];
                                        list($orderby, $sort, $limit) = clientAreaTableInit("dom", "description", "ASC", $numitems);
                                        $smartyvalues['orderby'] = $orderby;
                                        $smartyvalues['sort'] = strtolower($sort);

                                        if ($orderby == "price") {
                                            $orderby = "recurringamount";
                                        } else {
                                            if ($orderby == "regdate") {
                                                $orderby = "registrationdate";
                                            } else {
                                                if ($orderby == "nextduedate") {
                                                    $orderby = "nextduedate";
                                                } else {
                                                    if ($orderby == "status") {
                                                        $orderby = "status";
                                                    } else {
                                                        if ($orderby == "autorenew") {
                                                            $orderby = "donotrenew";
                                                        } else {
                                                            $orderby = "description";
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $descriptions = array();
                                        $result = select_query_i("tbldescriptions", "", $where, $orderby, $sort, ($page - 1) * $pagelimit . ("," . $pagelimit));

                                        while ($data = mysqli_fetch_array($result)) {
                                            $id = $data['id'];
                                            $registrationdate = $data['registrationdate'];
                                            $description = $data['description'];
                                            $amount = $data['recurringamount'];
                                            $nextduedate = $data['nextduedate'];
                                            $expirydate = $data['expirydate'];
                                            $status = $data['status'];
                                            $donotrenew = $data['donotrenew'];
                                            $rawstatus = $ca->getRawStatus($status);
                                            $autorenew = ($donotrenew ? false : true);
                                            $registrationdate = fromMySQLDate($registrationdate, 0, 1, "-");
                                            $nextduedate = fromMySQLDate($nextduedate, 0, 1, "-");
                                            $expirydate = fromMySQLDate($expirydate, 0, 1, "-");
                                            $descriptions[] = array("id" => $id, "description" => $description, "amount" => formatCurrency($amount), "registrationdate" => $registrationdate, "nextduedate" => $nextduedate, "expirydate" => $expirydate, "status" => $status, "rawstatus" => $rawstatus, "statustext" => $_LANG["clientarea" . $rawstatus], "autorenew" => $autorenew);
                                        }

                                        $ca->assign("descriptions", $descriptions);
                                        $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
                                    } else {
                                        if ($action == "descriptiondetails") {
                                            checkContactPermission("descriptions");
                                            $ca->setTemplate("clientareadescriptiondetails");
                                            $descriptions = new RA_Domains();
                                            $description_data = $descriptions->getDomainsDatabyID($id);

                                            if (!$description_data) {
                                                redir("action=descriptions", "clientarea.php");
                                            }

                                            if ($autorenew == "enable") {
                                                update_query("tbldescriptions", array("donotrenew" => ""), array("id" => $id, "userid" => $client->getID()));
                                                $descriptionname = get_query_val("tbldescriptions", "description", array("userid" => $client->getID(), "id" => $id));
                                                logActivity("Client Enabled Domain Auto Renew - Domain ID: " . $id . " - Domain: " . $descriptionname);
                                                $ca->assign("updatesuccess", true);
                                            } else {
                                                if ($autorenew == "disable") {
                                                    disableAutoRenew($id);
                                                    $ca->assign("updatesuccess", true);
                                                }
                                            }

                                            $description_data = $descriptions->getDomainsDatabyID($id);
                                            $description = $descriptions->getData("description");
                                            $firstpaymentamount = $descriptions->getData("firstpaymentamount");
                                            $recurringamount = $descriptions->getData("recurringamount");
                                            $nextduedate = $descriptions->getData("nextduedate");
                                            $expirydate = $descriptions->getData("expirydate");
                                            $paymentmethod = $descriptions->getData("paymentmethod");
                                            $servicestatus = $descriptions->getData("status");
                                            $registrationperiod = $descriptions->getData("registrationperiod");
                                            $registrationdate = $descriptions->getData("registrationdate");
                                            $donotrenew = $descriptions->getData("donotrenew");
                                            $dnsmanagement = $descriptions->getData("dnsmanagement");
                                            $emailforwarding = $descriptions->getData("emailforwarding");
                                            $idprotection = $descriptions->getData("idprotection");
                                            $gatewaysarray = getGatewaysArray();
                                            $paymentmethod = $gatewaysarray[$paymentmethod];
                                            $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description);
                                            $registrationdate = fromMySQLDate($registrationdate, 0, 1, "-");
                                            $nextduedate = fromMySQLDate($nextduedate, 0, 1, "-");
                                            $expirydate = fromMySQLDate($expirydate, 0, 1, "-");
                                            $rawstatus = $ca->getRawStatus($servicestatus);
                                            $allowrenew = false;

                                            if ($servicestatus == "Active" || $servicestatus == "Expired") {
                                                $allowrenew = true;
                                            }

                                            $autorenew = ($donotrenew ? false : true);
                                            $sld = $descriptions->getSLD();
                                            $tld = $descriptions->getTLD();
                                            $ca->assign("descriptionid", $descriptions->getData("id"));
                                            $ca->assign("description", $description);
                                            $ca->assign("sld", $sld);
                                            $ca->assign("tld", $tld);
                                            $ca->assign("firstpaymentamount", formatCurrency($firstpaymentamount));
                                            $ca->assign("recurringamount", formatCurrency($recurringamount));
                                            $ca->assign("registrationdate", $registrationdate);
                                            $ca->assign("nextduedate", $nextduedate);
                                            $ca->assign("expirydate", $expirydate);
                                            $ca->assign("registrationperiod", $registrationperiod);
                                            $ca->assign("paymentmethod", $paymentmethod);
                                            $ca->assign("status", $_LANG["clientarea" . $rawstatus]);
                                            $ca->assign("rawstatus", $rawstatus);
                                            $ca->assign("donotrenew", $donotrenew);
                                            $ca->assign("autorenew", $autorenew);
                                            $ca->assign("subaction", $sub);
                                            $ca->assign("addonstatus", array("dnsmanagement" => $dnsmanagement, "emailforwarding" => $emailforwarding, "idprotection" => $idprotection));

                                            if ($allowrenew) {
                                                $ca->assign("renew", $allowrenew);
                                            }

                                            $tlddata = get_query_vals("tbldescriptionpricing", "", array("extension" => "." . $tld));
                                            $ca->assign("addons", array("dnsmanagement" => $tlddata['dnsmanagement'], "emailforwarding" => $tlddata['emailforwarding'], "idprotection" => $tlddata['idprotection']));
                                            $addonscount = 0;

                                            if ($tlddata['dnsmanagement']) {
                                                ++$addonscount;
                                            }

                                            if ($tlddata['emailforwarding']) {
                                                ++$addonscount;
                                            }

                                            if ($tlddata['idprotection']) {
                                                ++$addonscount;
                                            }

                                            $ca->assign("addonscount", $addonscount);
                                            $result = select_query_i("tblpricing", "", array("type" => "descriptionaddons", "currency" => $currency['id'], "relid" => 0));
                                            $data = mysqli_fetch_array($result);
                                            $descriptiondnsmanagementprice = $data['msetupfee'];
                                            $descriptionemailforwardingprice = $data['qsetupfee'];
                                            $descriptionidprotectionprice = $data['ssetupfee'];
                                            $ca->assign("addonspricing", array("dnsmanagement" => formatCurrency($descriptiondnsmanagementprice), "emailforwarding" => formatCurrency($descriptionemailforwardingprice), "idprotection" => formatCurrency($descriptionidprotectionprice)));

                                            if ($sub == "savereglock") {
                                                check_token();
                                                checkContactPermission("managedescriptions");
                                                $newlockstatus = ($ra->get_req_var("reglock") ? "locked" : "unlocked");
                                                $success = $descriptions->moduleCall("SaveRegistrarLock", array("lockenabled" => $newlockstatus));

                                                if ($success) {
                                                    $smartyvalues['updatesuccess'] = true;
                                                } else {
                                                    $smartyvalues['error'] = $descriptions->getLastError();
                                                }
                                            }

                                            $success = $descriptions->moduleCall("GetNameservers");

                                            if ($success) {
                                                $i = 1;

                                                while ($i <= 5) {
                                                    $ca->assign("ns" . $i, $descriptions->getModuleReturn("ns" . $i));
                                                    ++$i;
                                                }

                                                $smartyvalues['managens'] = true;
                                                $defaultns = array();
                                                $i = 1;

                                                while ($i <= 5) {
                                                    if (trim($CONFIG["DefaultNameserver" . $i])) {
                                                        $defaultns[trim($CONFIG["DefaultNameserver" . $i])] = true;
                                                    }

                                                    ++$i;
                                                }

                                                foreach ($values as $ns) {
                                                    unset($defaultns[$ns]);
                                                }

                                                if (!count($defaultns)) {
                                                    $smartyvalues['defaultns'] = true;
                                                }
                                            } else {
                                                $smartyvalues['error'] = $descriptions->getLastError();
                                            }

                                            if (!preg_match('/uk$/i', $tld) && $descriptions->hasFunction("GetRegistrarLock")) {
                                                $success = $descriptions->moduleCall("GetRegistrarLock");

                                                if ($success) {
                                                    $ca->assign("lockstatus", $descriptions->getModuleReturn());
                                                }
                                            }

                                            $smartyvalues['managecontacts'] = ($descriptions->hasFunction("GetContactDetails") ? true : false);
                                            $smartyvalues['registerns'] = ($descriptions->hasFunction("RegisterNameserver") ? true : false);
                                            $smartyvalues['dnsmanagement'] = (($dnsmanagement && $descriptions->hasFunction("GetDNS")) ? true : false);
                                            $smartyvalues['emailforwarding'] = (($emailforwarding && $descriptions->hasFunction("GetEmailForwarding")) ? true : false);
                                            $smartyvalues['getepp'] = (($tlddata['eppcode'] && $descriptions->hasFunction("GetEPPCode")) ? true : false);

                                            if (preg_match('/uk$/i', $tld) && $descriptions->hasFunction("ReleaseDomain")) {
                                                $allowrelease = false;

                                                if (isset($params['AllowClientTAGChange'])) {
                                                    if ($params['AllowClientTAGChange']) {
                                                        $allowrelease = true;
                                                    }
                                                } else {
                                                    $allowrelease = true;
                                                }

                                                if ($allowrelease) {
                                                    $smartyvalues['releasedescription'] = true;

                                                    if ($sub == "releasedescription") {
                                                        check_token();
                                                        checkContactPermission("managedescriptions");
                                                        $success = $descriptions->moduleCall("ReleaseDomain", array("transfertag" => $transtag));

                                                        if ($success) {
                                                            $ca->assign("status", $ra->get_lang("clientareacancelled"));
                                                            logActivity("Client Requested Domain Release to Tag " . $transtag);
                                                        } else {
                                                            $smartyvalues['error'] = $descriptions->getLastError();
                                                        }
                                                    }
                                                } else {
                                                    $smartyvalues['releasedescription'] = false;
                                                }
                                            }
                                        } else {
                                            if ($action == "descriptioncontacts") {
                                                checkContactPermission("managedescriptions");
                                                $ca->setTemplate("clientareadescriptioncontactinfo");
                                                $contactsarray = $client->getContactsWithAddresses();
                                                $smartyvalues['contacts'] = $contactsarray;
                                                $descriptions = new RA_Domains();
                                                $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                if ((!$description_data || !$descriptions->isActive()) || !$descriptions->hasFunction("GetContactDetails")) {
                                                    redir("action=descriptions", "clientarea.php");
                                                }

                                                $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description_data['description']);
                                                $ca->addToBreadCrumb("#", $ra->get_lang("descriptioncontactinfo"));

                                                if ($sub == "save") {
                                                    check_token();
                                                    $wc = $ra->get_req_var("wc");
                                                    $contactdetails = $ra->get_req_var("contactdetails");
                                                    foreach ($wc as $wc_key => $wc_val) {

                                                        if ($wc_val == "contact") {
                                                            $selctype = $sel[$wc_key][0];
                                                            $selcid = substr($sel[$wc_key], 1);
                                                            $tmpcontactdetails = array();

                                                            if ($selctype == "c") {
                                                                $tmpcontactdetails = get_query_vals("tblcontacts", "", array("userid" => $client->getID(), "id" => $selcid));
                                                            } else {
                                                                if ($selctype == "u") {
                                                                    $tmpcontactdetails = get_query_vals("tblclients", "", array("id" => $client->getID()));
                                                                }
                                                            }

                                                            $contactdetails[$wc_key] = $descriptions->buildWHOISSaveArray($tmpcontactdetails);
                                                            continue;
                                                        }
                                                    }

                                                    $success = $descriptions->moduleCall("SaveContactDetails", array("contactdetails" => $contactdetails));

                                                    if ($success) {
                                                        $smartyvalues['successful'] = true;
                                                    } else {
                                                        $smartyvalues['error'] = $descriptions->getLastError();
                                                    }
                                                }

                                                $success = $descriptions->moduleCall("GetContactDetails");

                                                if ($success) {
                                                    $smartyvalues['contactdetails'] = $descriptions->getModuleReturn();
                                                } else {
                                                    $smartyvalues['error'] = $descriptions->getLastError();
                                                }

                                                $smartyvalues['descriptionid'] = $descriptions->getData("id");
                                                $smartyvalues['description'] = $descriptions->getData("description");
                                                $smartyvalues['contacts'] = $client->getContactsWithAddresses();
                                            } else {
                                                if ($action == "descriptionemailforwarding") {
                                                    checkContactPermission("managedescriptions");
                                                    $ca->setTemplate("clientareadescriptionemailforwarding");
                                                    $descriptions = new RA_Domains();
                                                    $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                    if ((!$description_data || !$descriptions->isActive()) || !$descriptions->hasFunction("GetEmailForwarding")) {
                                                        redir("action=descriptions", "clientarea.php");
                                                    }

                                                    $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description_data['description']);
                                                    $ca->addToBreadCrumb("#", $ra->get_lang("descriptionemailforwarding"));

                                                    if ($sub == "save") {
                                                        check_token();
                                                        $key = 0;
                                                        $vars = array();

                                                        if ($ra->get_req_var("emailforwarderprefix")) {
                                                            foreach ($ra->get_req_var("emailforwarderprefix") as $key => $value) {
                                                                $vars['prefix'][$key] = $ra->get_req_var("emailforwarderprefix", $key);
                                                                $vars['forwardto'][$key] = $ra->get_req_var("emailforwarderforwardto", $key);
                                                            }
                                                        }

                                                        if ($ra->get_req_var("emailforwarderprefixnew")) {
                                                            ++$key;
                                                            $vars['prefix'][$key] = $ra->get_req_var("emailforwarderprefixnew");
                                                            $vars['forwardto'][$key] = $ra->get_req_var("emailforwarderforwardtonew");
                                                        }

                                                        $success = $descriptions->moduleCall("SaveEmailForwarding", $vars);

                                                        if (!$success) {
                                                            $smartyvalues['error'] = $descriptions->getLastError();
                                                        }
                                                    }

                                                    $success = $descriptions->moduleCall("GetEmailForwarding");

                                                    if (!$success) {
                                                        $smartyvalues['error'] = $descriptions->getLastError();
                                                    }

                                                    $smartyvalues['descriptionid'] = $description_data['id'];
                                                    $smartyvalues['description'] = $description_data['description'];

                                                    if ($descriptions->getModuleReturn("external")) {
                                                        $ca->assign("external", true);
                                                        $ca->assign("code", $descriptions->getModuleReturn("code"));
                                                    } else {
                                                        $ca->assign("emailforwarders", $descriptions->getModuleReturn());
                                                    }
                                                } else {
                                                    if ($action == "descriptiondns") {
                                                        checkContactPermission("managedescriptions");
                                                        $ca->setTemplate("clientareadescriptiondns");
                                                        $descriptions = new RA_Domains();
                                                        $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                        if ((!$description_data || !$descriptions->isActive()) || !$descriptions->hasFunction("GetDNS")) {
                                                            redir("action=descriptions", "clientarea.php");
                                                        }

                                                        $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description_data['description']);
                                                        $ca->addToBreadCrumb("#", $ra->get_lang("descriptiondnsmanagement"));

                                                        if ($sub == "save") {
                                                            check_token();
                                                            $vars = array();
                                                            foreach ($_POST['dnsrecordhost'] as $num => $dnshost) {
                                                                $vars[] = array("hostname" => $dnshost, "type" => $_POST['dnsrecordtype'][$num], "address" => $_POST['dnsrecordaddress'][$num], "priority" => $_POST['dnsrecordpriority'][$num], "recid" => $_POST['dnsrecid'][$num]);
                                                            }

                                                            $success = $descriptions->moduleCall("SaveDNS", array("dnsrecords" => $vars));

                                                            if (!$success) {
                                                                $smartyvalues['error'] = $descriptions->getLastError();
                                                            }
                                                        }

                                                        $success = $descriptions->moduleCall("GetDNS");

                                                        if (!$success) {
                                                            $smartyvalues['error'] = $descriptions->getLastError();
                                                        }

                                                        $smartyvalues['descriptionid'] = $description_data['id'];
                                                        $smartyvalues['description'] = $description_data['description'];

                                                        if ($descriptions->getModuleReturn("external")) {
                                                            $ca->assign("external", true);
                                                            $ca->assign("code", $descriptions->getModuleReturn("code"));
                                                        } else {
                                                            $ca->assign("dnsrecords", $descriptions->getModuleReturn());
                                                        }
                                                    } else {
                                                        if ($action == "descriptiongetepp") {
                                                            checkContactPermission("managedescriptions");
                                                            $ca->setTemplate("clientareadescriptiongetepp");
                                                            $descriptions = new RA_Domains();
                                                            $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                            if ((!$description_data || !$descriptions->isActive()) || !$descriptions->hasFunction("GetEPPCode")) {
                                                                redir("action=descriptions", "clientarea.php");
                                                            }

                                                            $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description_data['description']);
                                                            $ca->addToBreadCrumb("#", $ra->get_lang("descriptiongeteppcode"));
                                                            $smartyvalues['descriptionid'] = $description_data['id'];
                                                            $smartyvalues['description'] = $description_data['description'];
                                                            $success = $descriptions->moduleCall("GetEPPCode");

                                                            if (!$success) {
                                                                $smartyvalues['error'] = $descriptions->getLastError();
                                                            } else {
                                                                $smartyvalues['eppcode'] = $descriptions->getModuleReturn("eppcode");
                                                            }
                                                        } else {
                                                            if ($action == "descriptionregisterns") {
                                                                checkContactPermission("managedescriptions");
                                                                $ca->setTemplate("clientareadescriptionregisterns");
                                                                $descriptions = new RA_Domains();
                                                                $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                                if ((!$description_data || !$descriptions->isActive()) || !$descriptions->hasFunction("RegisterNameserver")) {
                                                                    redir("action=descriptions", "clientarea.php");
                                                                }

                                                                $ca->addToBreadCrumb("clientarea.php?action=descriptiondetails&id=" . $description_data['id'], $description_data['description']);
                                                                $ca->addToBreadCrumb("#", $ra->get_lang("descriptionregisterns"));
                                                                $smartyvalues['descriptionid'] = $description_data['id'];
                                                                $smartyvalues['description'] = $description_data['description'];
                                                                $result = "";
                                                                $vars = array();
                                                                $ns = $ra->get_req_var("ns");

                                                                if ($sub == "register") {
                                                                    check_token();
                                                                    $ipaddress = $ra->get_req_var("ipaddress");
                                                                    $nameserver = $ns . "." . $description_data['description'];
                                                                    $vars['nameserver'] = $nameserver;
                                                                    $vars['ipaddress'] = $ipaddress;
                                                                    $success = $descriptions->moduleCall("RegisterNameserver", $vars);
                                                                    $result = ($success ? $_LANG['descriptionregisternsregsuccess'] : $descriptions->getLastError());
                                                                } else {
                                                                    if ($sub == "modify") {
                                                                        check_token();
                                                                        $nameserver = $ns . "." . $description_data['description'];
                                                                        $currentipaddress = $ra->get_req_var("currentipaddress");
                                                                        $newipaddress = $ra->get_req_var("newipaddress");
                                                                        $vars['nameserver'] = $nameserver;
                                                                        $vars['currentipaddress'] = $currentipaddress;
                                                                        $vars['newipaddress'] = $newipaddress;
                                                                        $success = $descriptions->moduleCall("ModifyNameserver", $vars);
                                                                        $result = ($success ? $_LANG['descriptionregisternsmodsuccess'] : $descriptions->getLastError());
                                                                    } else {
                                                                        if ($sub == "delete") {
                                                                            check_token();
                                                                            $nameserver = $ns . "." . $description_data['description'];
                                                                            $vars['nameserver'] = $nameserver;
                                                                            $success = $descriptions->moduleCall("DeleteNameserver", $vars);
                                                                            $result = ($success ? $_LANG['descriptionregisternsdelsuccess'] : $descriptions->getLastError());
                                                                        }
                                                                    }
                                                                }

                                                                $smartyvalues['result'] = $result;
                                                            } else {
                                                                if ($action == "descriptionrenew") {
                                                                    checkContactPermission("orders");
                                                                    redir("gid=renewals", "cart.php");
                                                                } else {
                                                                    if ($action == "invoices") {
                                                                        checkContactPermission("invoices");
                                                                        $ca->setTemplate("clientareainvoices");
                                                                        $numitems = get_query_val("tblinvoices", "COUNT(*)", array("userid" => $client->getID()));
                                                                        list($orderby, $sort, $limit) = clientAreaTableInit("inv", "status", "DESC", $numitems);
                                                                        $smartyvalues['orderby'] = $orderby;
                                                                        $smartyvalues['sort'] = strtolower($sort);

                                                                        if ($orderby == "invoicenum") {
                                                                            $orderby = "invoicenum` " . $sort . ",`id";
                                                                        } else {
                                                                            if ($orderby == "date") {
                                                                                $orderby = "date";
                                                                            } else {
                                                                                if ($orderby == "duedate") {
                                                                                    $orderby = "duedate";
                                                                                } else {
                                                                                    if ($orderby == "total") {
                                                                                        $orderby = "total";
                                                                                    } else {
                                                                                        if ($orderby == "status") {
                                                                                            $orderby = "status";
                                                                                        } else {
                                                                                            $orderby = "status` DESC,`duedate";
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }

                                                                        $invoice = new RA_Invoice();
                                                                        $invoices = $invoice->getInvoices("", $client->getID(), $orderby, $sort, $limit);
                                                                        $ca->assign("invoices", $invoices);

                                                                        if ($invoice->getTotalBalance() <= 0) {
                                                                            $ca->assign("nobalance", true);
                                                                        }

                                                                        $ca->assign("totalbalance", $invoice->getTotalBalanceFormatted());
                                                                        $ca->assign("masspay", $CONFIG['EnableMassPay']);
                                                                        $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
                                                                    } else {
                                                                        if ($action == "emails") {
                                                                            checkContactPermission("emails");
                                                                            $ca->setTemplate("clientareaemails");
                                                                            $result = select_query_i("tblemails", "COUNT(*)", array("userid" => $client->getID()), "id", "DESC");
                                                                            $data = mysqli_fetch_array($result);
                                                                            $numitems = $data[0];
                                                                            list($orderby, $sort, $limit) = clientAreaTableInit("emails", "date", "DESC", $numitems);
                                                                            $smartyvalues['orderby'] = $orderby;
                                                                            $smartyvalues['sort'] = strtolower($sort);

                                                                            if ($orderby == "subject") {
                                                                                $orderby = "subject";
                                                                            } else {
                                                                                $orderby = "date";
                                                                            }

                                                                            $emails = array();
                                                                            $result = select_query_i("tblemails", "", array("userid" => $client->getID()), $orderby, $sort, $limit);

                                                                            while ($data = mysqli_fetch_array($result)) {
                                                                                $id = $data['id'];
                                                                                $date = $data['date'];
                                                                                $subject = $data['subject'];
                                                                                $date = fromMySQLDate($date, 1, 1);
                                                                                $emails[] = array("id" => $id, "date" => $date, "subject" => $subject);
                                                                            }

                                                                            $ca->assign("emails", $emails);
                                                                            $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
                                                                        } else {
                                                                            if ($action == "cancel") {
                                                                                checkContactPermission("orders");
                                                                                $service = new RA_Service($id, $client->getID());

                                                                                if ($service->isNotValid()) {
                                                                                    redir("action=products", "clientarea.php");
                                                                                }

                                                                                $allowedstatuscancel = array("Active", "Suspended");

                                                                                if (!in_array($service->getData("status"), $allowedstatuscancel)) {
                                                                                    redir("action=productdetails&id=" . $id);
                                                                                }

                                                                                $ca->setTemplate("clientareacancelrequest");
                                                                                $ca->addToBreadCrumb("clientarea.php?action=productdetails&id=" . $id, $ra->get_lang("clientareaproductdetails"));
                                                                                $ca->addToBreadCrumb("cancel&id=" . $id, $ra->get_lang("clientareacancelrequest"));
                                                                                $clientsdetails = getClientsDetails($client->getID());
                                                                                $smartyvalues['id'] = $service->getData("id");
                                                                                $smartyvalues['groupname'] = $service->getData("groupname");
                                                                                $smartyvalues['productname'] = $service->getData("productname");
                                                                                $smartyvalues['description'] = $service->getData("description");
                                                                                $cancelrequests = get_query_val("tblcancelrequests", "COUNT(*)", array("relid" => $id));

                                                                                if ($cancelrequests) {
                                                                                    $smartyvalues['invalid'] = "on";
                                                                                } else {
                                                                                    if ($sub == "submit") {
                                                                                        check_token();

                                                                                        if (!trim($cancellationreason)) {
                                                                                            $smartyvalues['error'] = true;
                                                                                        }

                                                                                        if (!$smartyvalues['error']) {
                                                                                            if (!in_array($type, array("Immediate", "End of Billing Period"))) {
                                                                                                $type = "End of Billing Period";
                                                                                            }

                                                                                            createCancellationRequest($client->getID(), $id, $cancellationreason, $type);

                                                                                            if ($canceldescription) {
                                                                                                $descriptionid = get_query_val("tbldescriptions", "id", array("userid" => $client->getID(), "description" => $service->getData("description")));

                                                                                                if ($descriptionid) {
                                                                                                    disableAutoRenew($descriptionid);
                                                                                                }
                                                                                            }

                                                                                            sendMessage("Cancellation Request Confirmation", $id);
                                                                                            sendAdminMessage("New Cancellation Request", array("client_id" => $client->getID(), "clientname" => $clientsdetails['firstname'] . " " . $clientsdetails['lastname'], "service_id" => $id, "product_name" => $service->getData("productname"), "service_cancellation_type" => $type, "service_cancellation_type" => $type, "service_cancellation_reason" => $cancellationreason), "account");
                                                                                            $smartyvalues['requested'] = "on";
                                                                                        }
                                                                                    }

                                                                                    if ($service->getData("description")) {
                                                                                        $data = get_query_vals("tbldescriptions", "id,recurringamount,registrationperiod,nextduedate", array("userid" => $client->getID(), "description" => $service->getData("description"), "status" => "Active", "donotrenew" => ""));
                                                                                        $smartyvalues['descriptionid'] = $data['id'];
                                                                                        $smartyvalues['descriptionprice'] = formatCurrency($data['recurringamount']);
                                                                                        $smartyvalues['descriptionregperiod'] = $data['registrationperiod'];
                                                                                        $smartyvalues['descriptionnextduedate'] = fromMySQLDate($data['nextduedate'], 0, 1);
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if ($action == "addfunds") {
                                                                                    checkContactPermission("invoices");
                                                                                    $clientsdetails = getClientsDetails();
                                                                                    $addfundsmaxbal = convertCurrency($CONFIG['AddFundsMaximumBalance'], 1, $clientsdetails['currency']);
                                                                                    $addfundsmax = convertCurrency($CONFIG['AddFundsMaximum'], 1, $clientsdetails['currency']);
                                                                                    $addfundsmin = convertCurrency($CONFIG['AddFundsMinimum'], 1, $clientsdetails['currency']);
                                                                                    $result = select_query_i("tblorders", "COUNT(*)", array("userid" => $client->getID(), "status" => "Active"));
                                                                                    $data = mysqli_fetch_array($result);
                                                                                    $numactiveorders = $data[0];

                                                                                    if (!$CONFIG['AddFundsRequireOrder']) {
                                                                                        $numactiveorders = 1;
                                                                                    }

                                                                                    if (!$CONFIG['AddFundsEnabled']) {
                                                                                        $smartyvalues['addfundsdisabled'] = true;
                                                                                    } else {
                                                                                        if (!$numactiveorders) {
                                                                                            $smartyvalues['notallowed'] = true;
                                                                                        } else {
                                                                                            if ($amount) {
                                                                                                check_token();
                                                                                                $totalcredit = $clientsdetails['credit'] + $amount;

                                                                                                if ($addfundsmaxbal < $totalcredit) {
                                                                                                    $errormessage = $_LANG['addfundsmaximumbalanceerror'] . " " . formatCurrency($addfundsmaxbal);
                                                                                                }

                                                                                                if ($addfundsmax < $amount) {
                                                                                                    $errormessage = $_LANG['addfundsmaximumerror'] . " " . formatCurrency($addfundsmax);
                                                                                                }

                                                                                                if ($amount < $addfundsmin) {
                                                                                                    $errormessage = $_LANG['addfundsminimumerror'] . " " . formatCurrency($addfundsmin);
                                                                                                }

                                                                                                if ($errormessage) {
                                                                                                    $ca->assign("errormessage", $errormessage);
                                                                                                } else {
                                                                                                    $paymentmethods = getGatewaysArray();

                                                                                                    if (!array_key_exists($paymentmethod, $paymentmethods)) {
                                                                                                        $paymentmethod = getClientsPaymentMethod($client->getID());
                                                                                                    }

                                                                                                    $paymentmethod = RA_Gateways::makesafename($paymentmethod);

                                                                                                    if (!$paymentmethod) {
                                                                                                        exit("Unexpected payment method value. Exiting.");
                                                                                                    }

                                                                                                    require "includes/processinvoices.php";
                                                                                                    $invoiceid = createInvoices($client->getID());
                                                                                                    insert_query("tblinvoiceitems", array("userid" => $client->getID(), "type" => "AddFunds", "relid" => "", "description" => $_LANG['addfunds'], "amount" => $amount, "taxed" => "0", "duedate" => "now()", "paymentmethod" => $paymentmethod));
                                                                                                    $invoiceid = createInvoices($client->getID(), "", true);
                                                                                                    $result = select_query_i("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "type"));
                                                                                                    $data = mysqli_fetch_array($result);
                                                                                                    $gatewaytype = $data['value'];

                                                                                                    if ($gatewaytype == "CC" || $gatewaytype == "OfflineCC") {
                                                                                                        if (!isValidforPath($paymentmethod)) {
                                                                                                            exit("Invalid Payment Gateway Name");
                                                                                                        }

                                                                                                        $gatewaypath = ROOTDIR . "/modules/gateways/" . $paymentmethod . ".php";

                                                                                                        if (file_exists($gatewaypath)) {
                                                                                                            require_once $gatewaypath;
                                                                                                        }

                                                                                                        if (!function_exists($paymentmethod . "_link")) {
                                                                                                            redir("invoiceid=" . (int) $invoiceid, "creditcard.php");
                                                                                                        }
                                                                                                    }

                                                                                                    $result = select_query_i("tblinvoices", "", array("userid" => $client->getID(), "id" => $invoiceid));
                                                                                                    $data = mysqli_fetch_array($result);
                                                                                                    $id = $data['id'];
                                                                                                    $total = $data['total'];
                                                                                                    $paymentmethod = $data['paymentmethod'];
                                                                                                    $clientsdetails = getClientsDetails($client->getID());
                                                                                                    $params = getGatewayVariables($paymentmethod, $id, $total);
                                                                                                    $paymentbutton = call_user_func($paymentmethod . "_link", $params);
                                                                                                    $ca->setTemplate("forwardpage");
                                                                                                    $ca->assign("message", $_LANG['forwardingtogateway']);
                                                                                                    $ca->assign("code", $paymentbutton);
                                                                                                    $ca->assign("invoiceid", $id);
                                                                                                    $ca->output();
                                                                                                    exit();
                                                                                                }
                                                                                            } else {
                                                                                                $amount = $addfundsmin;
                                                                                            }
                                                                                        }
                                                                                    }

                                                                                    $ca->setTemplate("clientareaaddfunds");
                                                                                    $ca->assign("minimumamount", formatCurrency($addfundsmin));
                                                                                    $ca->assign("maximumamount", formatCurrency($addfundsmax));
                                                                                    $ca->assign("maximumbalance", formatCurrency($addfundsmaxbal));
                                                                                    $ca->assign("amount", format_as_currency($amount));
                                                                                    $gatewayslist = showPaymentGatewaysList();
                                                                                    $ca->assign("gateways", $gatewayslist);
                                                                                } else {
                                                                                    if ($action == "masspay") {
                                                                                        checkContactPermission("invoices");
                                                                                        $ca->setTemplate("masspay");

                                                                                        if (!$CONFIG['EnableMassPay']) {
                                                                                            redir();
                                                                                            exit();
                                                                                        }

                                                                                        if ($all) {
                                                                                            $invoiceids = array();
                                                                                            $result = select_query_i("tblinvoices", "id", array("userid" => $client->getID(), "status" => "Unpaid", "(select count(id) from tblinvoiceitems where invoiceid=tblinvoices.id and type='Invoice')" => array("sqltype" => "<=", "value" => 0)), "id", "DESC");

                                                                                            while ($data = mysqli_fetch_array($result)) {
                                                                                                $invoiceids[] = $data['id'];
                                                                                            }
                                                                                        } else {
                                                                                            if (count($invoiceids) == 0) {
                                                                                                redir();
                                                                                                exit();
                                                                                            } else {
                                                                                                if (count($invoiceids) == 1) {
                                                                                                    redir("id=" . (int) $invoiceids[0], "viewinvoice.php");
                                                                                                } else {
                                                                                                    $tmp_invoiceids = db_escape_numarray($invoiceids);
                                                                                                    $invoiceids = array();
                                                                                                    $result = select_query_i("tblinvoices", "id", array("userid" => $client->getID(), "status" => "Unpaid", "id" => array("sqltype" => "IN", "values" => $tmp_invoiceids)), "id", "DESC");

                                                                                                    while ($data = mysqli_fetch_array($result)) {
                                                                                                        $invoiceids[] = $data['id'];
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                        $xmasspays = array();
                                                                                        $result = select_query_i("tblinvoiceitems", "invoiceid,relid", array("tblinvoiceitems.userid" => $client->getID(), "tblinvoiceitems.type" => "Invoice", "tblinvoices.status" => "Unpaid"), "", "", "", "tblinvoices ON tblinvoices.id=tblinvoiceitems.invoiceid");

                                                                                        while ($data = mysqli_fetch_array($result)) {
                                                                                            $xmasspays[$data[0]][$data[1]] = 1;
                                                                                        }

                                                                                        if (count($xmasspays)) {
                                                                                            $numsel = count($invoiceids);
                                                                                            foreach ($xmasspays as $iid => $vals) {

                                                                                                if (count($vals) == $numsel) {
                                                                                                    foreach ($invoiceids as $z) {
                                                                                                        unset($vals[$z]);
                                                                                                    }

                                                                                                    if (!count($vals)) {
                                                                                                        redir("id=" . $iid, "viewinvoice.php");
                                                                                                        continue;
                                                                                                    }

                                                                                                    continue;
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                        if ($geninvoice) {
                                                                                            check_token();
                                                                                        }

                                                                                        $paymentmethods = getGatewaysArray();

                                                                                        if (!array_key_exists($paymentmethod, $paymentmethods)) {
                                                                                            $paymentmethod = getClientsPaymentMethod($client->getID());
                                                                                        }

                                                                                        $paymentmethod = RA_Gateways::makesafename($paymentmethod);

                                                                                        if (!$paymentmethod) {
                                                                                            exit("Unexpected payment method value. Exiting.");
                                                                                        }

                                                                                        $subtotal = $credit = $tax = $tax2 = $total = $partialpayments = 0;
                                                                                        $invoiceitems = array();
                                                                                        foreach ($invoiceids as $invoiceid) {
                                                                                            $result = select_query_i("tblinvoices", "", array("id" => (int) $invoiceid, "userid" => $client->getID()));
                                                                                            $data = mysqli_fetch_array($result);
                                                                                            $invoiceid = $data['id'];

                                                                                            if ($invoiceid) {
                                                                                                $subtotal += $data['subtotal'];
                                                                                                $credit += $data['credit'];
                                                                                                $tax += $data['tax'];
                                                                                                $tax2 += $data['tax2'];
                                                                                                $thistotal = $data['total'];
                                                                                                $total += $thistotal;
                                                                                                $result = select_query_i("tblaccounts", "SUM(amountin)", array("invoiceid" => (int) $invoiceid));
                                                                                                $data = mysqli_fetch_array($result);
                                                                                                $thispayments = $data[0];
                                                                                                $partialpayments += $thispayments;
                                                                                                $thistotal = $thistotal - $thispayments;

                                                                                                if ($geninvoice) {
                                                                                                    insert_query("tblinvoiceitems", array("userid" => $client->getID(), "type" => "Invoice", "relid" => (int) $invoiceid, "description" => $_LANG['invoicenumber'] . (int) $invoiceid, "amount" => $thistotal, "duedate" => "now()", "paymentmethod" => $paymentmethod));
                                                                                                }

                                                                                                $result = select_query_i("tblinvoiceitems", "", array("invoiceid" => (int) $invoiceid));

                                                                                                while ($data = mysqli_fetch_array($result)) {
                                                                                                    $invoiceitems[(int) $invoiceid][] = array("id" => $data['id'], "description" => nl2br($data['description']), "amount" => formatCurrency($data['amount']), "tax" => $data['tax']);
                                                                                                }

                                                                                                continue;
                                                                                            }
                                                                                        }

                                                                                        if ($geninvoice) {
                                                                                            foreach ($xmasspays as $iid => $vals) {
                                                                                                update_query("tblinvoices", array("status" => "Cancelled"), array("id" => (int) $iid, "userid" => $client->getID()));
                                                                                            }

                                                                                            require "includes/processinvoices.php";
                                                                                            $invoiceid = createInvoices($client->getID(), true, true);
                                                                                            $invoiceid = (int) $invoiceid;
                                                                                            $result = select_query_i("tblpaymentgateways", "value", array("gateway" => $paymentmethod, "setting" => "type"));
                                                                                            $data = mysqli_fetch_array($result);
                                                                                            $gatewaytype = $data['value'];

                                                                                            if ($gatewaytype == "CC" || $gatewaytype == "OfflineCC") {
                                                                                                if (!isValidforPath($paymentmethod)) {
                                                                                                    exit("Invalid Payment Gateway Name");
                                                                                                }

                                                                                                $gatewaypath = ROOTDIR . "/modules/gateways/" . $paymentmethod . ".php";

                                                                                                if (file_exists($gatewaypath)) {
                                                                                                    require_once $gatewaypath;
                                                                                                }

                                                                                                if (!function_exists($paymentmethod . "_link")) {
                                                                                                    redir("invoiceid=" . (int) $invoiceid, "creditcard.php");
                                                                                                }
                                                                                            }

                                                                                            $result = select_query_i("tblinvoices", "", array("userid" => $client->getID(), "id" => $invoiceid));
                                                                                            $data = mysqli_fetch_array($result);
                                                                                            $id = $data['id'];
                                                                                            $total = $data['total'];
                                                                                            $paymentmethod = $data['paymentmethod'];
                                                                                            $paymentmethod = RA_Gateways::makesafename($paymentmethod);
                                                                                            $clientsdetails = getClientsDetails($client->getID());
                                                                                            $params = getGatewayVariables($paymentmethod, $id, $total);
                                                                                            $paymentbutton = call_user_func($paymentmethod . "_link", $params);
                                                                                            $ca->setTemplate("forwardpage");
                                                                                            $ca->assign("message", $_LANG['forwardingtogateway']);
                                                                                            $ca->assign("code", $paymentbutton);
                                                                                            $ca->assign("invoiceid", $id);
                                                                                            $ca->output();
                                                                                            exit();
                                                                                        }

                                                                                        $smartyvalues['subtotal'] = formatCurrency($subtotal);

                                                                                        if ($credit) {
                                                                                            $smartyvalues['credit'] = formatCurrency($credit);
                                                                                        }

                                                                                        if ($tax) {
                                                                                            $smartyvalues['tax'] = formatCurrency($tax);
                                                                                        }

                                                                                        if ($tax2) {
                                                                                            $smartyvalues['tax2'] = formatCurrency($tax2);
                                                                                        }

                                                                                        if ($partialpayments) {
                                                                                            $smartyvalues['partialpayments'] = formatCurrency($partialpayments);
                                                                                        }

                                                                                        $smartyvalues['total'] = formatCurrency($total - $partialpayments);
                                                                                        $smartyvalues['invoiceitems'] = $invoiceitems;
                                                                                        $gatewayslist = showPaymentGatewaysList();
                                                                                        $smartyvalues['gateways'] = $gatewayslist;
                                                                                        $smartyvalues['defaultgateway'] = key($gatewayslist);
                                                                                    } else {
                                                                                        if ($action == "quotes") {
                                                                                            $ca->setTemplate("clientareaquotes");
                                                                                            require ROOTDIR . "/includes/quotefunctions.php";
                                                                                            $result = select_query_i("tblquotes", "COUNT(*)", array("userid" => $client->getID()));
                                                                                            $data = mysqli_fetch_array($result);
                                                                                            $numitems = $data[0];
                                                                                            list($orderby, $sort, $limit) = clientAreaTableInit("quote", "id", "DESC", $numitems);

                                                                                            if (!in_array($orderby, array("id", "date", "duedate", "total", "stage"))) {
                                                                                                $orderby = "validuntil";
                                                                                            }

                                                                                            $smartyvalues['orderby'] = $orderby;
                                                                                            $smartyvalues['sort'] = strtolower($sort);
                                                                                            $quotes = array();
                                                                                            $result = select_query_i("tblquotes", "", array("userid" => $client->getID(), "stage" => array("sqltype" => "NEQ", "value" => "Draft")), $orderby, $sort, $limit);

                                                                                            while ($data = mysqli_fetch_assoc($result)) {
                                                                                                $data['datecreated'] = fromMySQLDate($data['datecreated'], 0, 1);
                                                                                                $data['validuntil'] = fromMySQLDate($data['validuntil'], 0, 1);
                                                                                                $data['lastmodified'] = fromMySQLDate($data['lastmodified'], 0, 1);
                                                                                                $data['stage'] = getQuoteStageLang($data['stage']);
                                                                                                $quotes[] = $data;
                                                                                            }

                                                                                            $smartyvalues['quotes'] = $quotes;
                                                                                            $smartyvalues = array_merge($smartyvalues, clientAreaTablePageNav($numitems));
                                                                                        } else {
                                                                                            if ($action == "bulkdescription") {
                                                                                                checkContactPermission("managedescriptions");
                                                                                                $ca->setTemplate("bulkdescriptionmanagement");
                                                                                                $descriptionids = "";
                                                                                                foreach ($domids as $domid) {
                                                                                                    $descriptionids .= (int) $domid . ",";
                                                                                                }

                                                                                                $descriptionids = substr($descriptionids, 0, 0 - 1);
                                                                                                $queryfilter = "userid=" . (int) $client->getID() . (" AND id IN (" . $descriptionids . ")");
                                                                                                $descriptions = $descriptionids = $errors = array();
                                                                                                $result = select_query_i("tbldescriptions", "id,description", $queryfilter, "description", "ASC");

                                                                                                while ($data = mysqli_fetch_assoc($result)) {
                                                                                                    $descriptionids[] = $data['id'];
                                                                                                    $descriptions[] = $data['description'];
                                                                                                }

                                                                                                if (!count($descriptionids)) {
                                                                                                    redir("action=descriptions");
                                                                                                }

                                                                                                if (!$update) {
                                                                                                    if ($nameservers) {
                                                                                                        $update = "nameservers";
                                                                                                    } else {
                                                                                                        if ($autorenew) {
                                                                                                            $update = "autorenew";
                                                                                                        } else {
                                                                                                            if ($reglock) {
                                                                                                                $update = "reglock";
                                                                                                            } else {
                                                                                                                if ($contactinfo) {
                                                                                                                    $update = "contactinfo";
                                                                                                                } else {
                                                                                                                    if ($renew) {
                                                                                                                        $update = "renew";
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }

                                                                                                $smartyvalues['descriptionids'] = $descriptionids;
                                                                                                $smartyvalues['descriptions'] = $descriptions;
                                                                                                $smartyvalues['update'] = $update;
                                                                                                $smartyvalues['save'] = $save;
                                                                                                $currpage = $_SERVER['PHP_SELF'] . "?action=bulkdescription";
                                                                                                $ca->addToBreadCrumb("clientarea.php?action=descriptions", $ra->get_lang("clientareanavdescriptions"));

                                                                                                if ($update == "nameservers") {
                                                                                                    $ca->addToBreadCrumb($currpage, $ra->get_lang("descriptionmanagens"));

                                                                                                    if ($save) {
                                                                                                        check_token();
                                                                                                        foreach ($descriptionids as $descriptionid) {
                                                                                                            $data = get_query_vals("tbldescriptions", "description,registrar", array("id" => $descriptionid, "userid" => $client->getID()));
                                                                                                            $description = $data['description'];
                                                                                                            $registrar = $data['registrar'];
                                                                                                            $descriptionparts = explode(".", $description, 2);
                                                                                                            $params = array();
                                                                                                            $params['descriptionid'] = $descriptionid;
                                                                                                            $params['sld'] = $descriptionparts[0];
                                                                                                            $params['tld'] = $descriptionparts[1];
                                                                                                            $params['registrar'] = $registrar;
                                                                                                            $params = RegBuildParams($params);

                                                                                                            if ($nschoice == "default") {
                                                                                                                $params = RegGetDefaultNameservers($params, $description);
                                                                                                            } else {
                                                                                                                $params['ns1'] = $ns1;
                                                                                                                $params['ns2'] = $ns2;
                                                                                                                $params['ns3'] = $ns3;
                                                                                                                $params['ns4'] = $ns4;
                                                                                                                $params['ns5'] = $ns5;
                                                                                                            }

                                                                                                            $values = RegSaveNameservers($params);

                                                                                                            if (!function_exists($registrar . "_SaveNameservers")) {
                                                                                                                $errors[] = $description . " " . $_LANG['descriptioncannotbemanaged'];
                                                                                                            }

                                                                                                            if ($values['error']) {
                                                                                                                $errors[] = $description . " - " . $values['error'];
                                                                                                                continue;
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                                                                                    if ($update == "autorenew") {
                                                                                                        $ca->addToBreadCrumb($currpage . "#", $ra->get_lang("descriptionautorenewstatus"));

                                                                                                        if ($save) {
                                                                                                            check_token();
                                                                                                            foreach ($descriptionids as $descriptionid) {

                                                                                                                if ($ra->get_req_var("enable")) {
                                                                                                                    update_query("tbldescriptions", array("donotrenew" => ""), array("id" => $descriptionid, "userid" => $client->getID()));
                                                                                                                    continue;
                                                                                                                }

                                                                                                                disableAutoRenew($descriptionid);
                                                                                                            }
                                                                                                        }
                                                                                                    } else {
                                                                                                        if ($update == "reglock") {
                                                                                                            $ca->addToBreadCrumb($currpage . "#", $ra->get_lang("descriptionreglockstatus"));

                                                                                                            if ($save) {
                                                                                                                check_token();
                                                                                                                foreach ($descriptionids as $descriptionid) {
                                                                                                                    $data = get_query_vals("tbldescriptions", "description,registrar", array("id" => $descriptionid, "userid" => $client->getID()));
                                                                                                                    $description = $data['description'];
                                                                                                                    $registrar = $data['registrar'];
                                                                                                                    $descriptionparts = explode(".", $description, 2);
                                                                                                                    $params = array();
                                                                                                                    $params['descriptionid'] = $descriptionid;
                                                                                                                    $params['sld'] = $descriptionparts[0];
                                                                                                                    $params['tld'] = $descriptionparts[1];
                                                                                                                    $params['registrar'] = $registrar;
                                                                                                                    $params = RegBuildParams($params);
                                                                                                                    $newlockstatus = ($_POST['enable'] ? "locked" : "unlocked");
                                                                                                                    $params['lockenabled'] = $newlockstatus;
                                                                                                                    $values = RegSaveRegistrarLock($params);

                                                                                                                    if (!function_exists($registrar . "_SaveRegistrarLock")) {
                                                                                                                        $errors[] = $description . " " . $_LANG['descriptioncannotbemanaged'];
                                                                                                                    }

                                                                                                                    if ($values['error']) {
                                                                                                                        $errors[] = $description . " - " . $values['error'];
                                                                                                                        continue;
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        } else {
                                                                                                            if ($update == "contactinfo") {
                                                                                                                if (!is_array($descriptionids) || count($descriptionids) <= 0) {
                                                                                                                    exit("Invalid Access Attempt");
                                                                                                                }

                                                                                                                $ca->addToBreadCrumb($currpage . "#", $ra->get_lang("descriptioncontactinfoedit"));

                                                                                                                if ($save) {
                                                                                                                    check_token();
                                                                                                                    $wc = $ra->get_req_var("wc");
                                                                                                                    $contactdetails = $ra->get_req_var("contactdetails");
                                                                                                                    foreach ($wc as $wc_key => $wc_val) {

                                                                                                                        if ($wc_val == "contact") {
                                                                                                                            $selctype = $sel[$wc_key][0];
                                                                                                                            $selcid = substr($sel[$wc_key], 1);
                                                                                                                            $tmpcontactdetails = array();

                                                                                                                            if ($selctype == "c") {
                                                                                                                                $tmpcontactdetails = get_query_vals("tblcontacts", "", array("userid" => $client->getID(), "id" => $selcid));
                                                                                                                            } else {
                                                                                                                                if ($selctype == "u") {
                                                                                                                                    $tmpcontactdetails = get_query_vals("tblclients", "", array("id" => $client->getID()));
                                                                                                                                }
                                                                                                                            }

                                                                                                                            $contactdetails[$wc_key] = $descriptions->buildWHOISSaveArray($tmpcontactdetails);
                                                                                                                            continue;
                                                                                                                        }
                                                                                                                    }

                                                                                                                    foreach ($descriptionids as $descriptionid) {
                                                                                                                        $descriptions = new RA_Domains();
                                                                                                                        $description_data = $descriptions->getDomainsDatabyID($descriptionid);

                                                                                                                        if (!$description_data) {
                                                                                                                            redir("action=descriptions", "clientarea.php");
                                                                                                                        }

                                                                                                                        $success = $descriptions->moduleCall("SaveContactDetails", array("contactdetails" => $contactdetails));

                                                                                                                        if (!$success) {
                                                                                                                            if ($descriptions->getLastError() == "Function not found") {
                                                                                                                                $errors[] = $description . " " . $_LANG['descriptioncannotbemanaged'];
                                                                                                                                continue;
                                                                                                                            }

                                                                                                                            $errors[] = $descriptions->getLastError();
                                                                                                                            continue;
                                                                                                                        }
                                                                                                                    }
                                                                                                                }

                                                                                                                $smartyvalues['contacts'] = $client->getContactsWithAddresses();
                                                                                                                $descriptions = new RA_Domains();
                                                                                                                $description_data = $descriptions->getDomainsDatabyID($descriptionids[0]);

                                                                                                                if (!$description_data) {
                                                                                                                    redir("action=descriptions", "clientarea.php");
                                                                                                                }

                                                                                                                $success = $descriptions->moduleCall("GetContactDetails");

                                                                                                                if ($success) {
                                                                                                                    $smartyvalues['contactdetails'] = $descriptions->getModuleReturn();
                                                                                                                }
                                                                                                            } else {
                                                                                                                if ($update == "renew") {
                                                                                                                    redir("gid=renewals", "cart.php");
                                                                                                                } else {
                                                                                                                    redir("action=descriptions");
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }

                                                                                                $smartyvalues['errors'] = $errors;
                                                                                            } else {
                                                                                                if ($action == "descriptionaddons") {
                                                                                                    check_token();
                                                                                                    $ca->setTemplate("clientareadescriptionaddons");
                                                                                                    $where = array("id" => $id, "userid" => $client->getID());
                                                                                                    $data = get_query_vals("tbldescriptions", "id,type,description,registrationperiod,registrar,dnsmanagement,emailforwarding,idprotection", $where);
                                                                                                    $descriptionid = $data['id'];
                                                                                                    $description = $data['description'];

                                                                                                    if (!$descriptionid) {
                                                                                                        redir();
                                                                                                    }

                                                                                                    $smartyvalues['descriptionid'] = $descriptionid;
                                                                                                    $smartyvalues['description'] = $data['description'];
                                                                                                    $descriptionparts = explode(".", $data['description'], 2);
                                                                                                    $result = select_query_i("tblpricing", "", array("type" => "descriptionaddons", "currency" => $currency['id'], "relid" => 0));
                                                                                                    $pricingdata = mysqli_fetch_array($result);
                                                                                                    $descriptiondnsmanagementprice = $pricingdata['msetupfee'];
                                                                                                    $descriptionemailforwardingprice = $pricingdata['qsetupfee'];
                                                                                                    $descriptionidprotectionprice = $pricingdata['ssetupfee'];
                                                                                                    $ca->assign("addonspricing", array("dnsmanagement" => formatCurrency($descriptiondnsmanagementprice), "emailforwarding" => formatCurrency($descriptionemailforwardingprice), "idprotection" => formatCurrency($descriptionidprotectionprice)));

                                                                                                    if ($disable) {
                                                                                                        $smartyvalues['action'] = "disable";
                                                                                                        $smartyvalues['addon'] = $disable;

                                                                                                        if ($disable == "dnsmanagement") {
                                                                                                            if (!$data['dnsmanagement']) {
                                                                                                                redir();
                                                                                                            }

                                                                                                            if ($confirm) {
                                                                                                                check_token();
                                                                                                                update_query("tbldescriptions", array("dnsmanagement" => "", "recurringamount" => "-=" . $descriptiondnsmanagementprice), $where);
                                                                                                                $smartyvalues['success'] = true;
                                                                                                            }
                                                                                                        } else {
                                                                                                            if ($disable == "emailfwd") {
                                                                                                                if (!$data['emailforwarding']) {
                                                                                                                    redir();
                                                                                                                }

                                                                                                                if ($confirm) {
                                                                                                                    check_token();
                                                                                                                    update_query("tbldescriptions", array("emailforwarding" => "", "recurringamount" => "-=" . $descriptionemailforwardingprice), $where);
                                                                                                                    $smartyvalues['success'] = true;
                                                                                                                }
                                                                                                            } else {
                                                                                                                if ($disable == "idprotect") {
                                                                                                                    if (!$data['idprotection']) {
                                                                                                                        redir();
                                                                                                                    }

                                                                                                                    if ($confirm) {
                                                                                                                        check_token();
                                                                                                                        update_query("tbldescriptions", array("idprotection" => "", "recurringamount" => "-=" . $descriptionidprotectionprice), $where);
                                                                                                                        $descriptionparts = explode(".", $description, 2);
                                                                                                                        $params = array();
                                                                                                                        $params['descriptionid'] = $data['id'];
                                                                                                                        $params['sld'] = $descriptionparts[0];
                                                                                                                        $params['tld'] = $descriptionparts[1];
                                                                                                                        $params['regperiod'] = $data['registrationperiod'];
                                                                                                                        $params['registrar'] = $data['registrar'];
                                                                                                                        $params['regtype'] = $data['type'];
                                                                                                                        $values = RegIDProtectToggle($params);

                                                                                                                        if ($values['error']) {
                                                                                                                            $smartyvalues['error'] = true;
                                                                                                                        } else {
                                                                                                                            $smartyvalues['success'] = true;
                                                                                                                        }
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    if ($id) {
                                                                                                                        redir("action=descriptiondetails&id=" . $id);
                                                                                                                    } else {
                                                                                                                        redir();
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }

                                                                                                    if ($buy) {
                                                                                                        $smartyvalues['action'] = "buy";
                                                                                                        $smartyvalues['addon'] = $buy;
                                                                                                        $paymentmethod = getClientsPaymentMethod($client->getID());
                                                                                                        $descriptiontax = ($ra->get_config("TaxDomains") ? 1 : 0);
                                                                                                        $invdesc = "";

                                                                                                        if ($buy == "dnsmanagement") {
                                                                                                            if ($confirm) {
                                                                                                                $invdesc = $_LANG['descriptionaddons'] . " (" . $_LANG['descriptionaddonsdnsmanagement'] . ") - " . $description . " - 1 " . $_LANG['orderyears'];
                                                                                                                $invamt = $descriptiondnsmanagementprice;
                                                                                                                $addontype = "DNS";
                                                                                                            }
                                                                                                        } else {
                                                                                                            if ($buy == "emailfwd") {
                                                                                                                if ($confirm) {
                                                                                                                    $invdesc = $_LANG['descriptionaddons'] . " (" . $_LANG['descriptionemailforwarding'] . ") - " . $description . " - 1 " . $_LANG['orderyears'];
                                                                                                                    $invamt = $descriptionemailforwardingprice;
                                                                                                                    $addontype = "EMF";
                                                                                                                }
                                                                                                            } else {
                                                                                                                if ($buy == "idprotect") {
                                                                                                                    if ($confirm) {
                                                                                                                        $invdesc = $_LANG['descriptionaddons'] . " (" . $_LANG['descriptionidprotection'] . ") - " . $description . " - 1 " . $_LANG['orderyears'];
                                                                                                                        $invamt = $descriptionidprotectionprice;
                                                                                                                        $addontype = "IDP";
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    if ($id) {
                                                                                                                        redir("action=descriptiondetails&id=" . $id);
                                                                                                                    } else {
                                                                                                                        redir();
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }

                                                                                                        if ($invdesc) {
                                                                                                            check_token();
                                                                                                            insert_query("tblinvoiceitems", array("userid" => $client->getID(), "type" => "DomainAddon" . $addontype, "relid" => $descriptionid, "description" => $invdesc, "amount" => $invamt, "taxed" => $descriptiontax, "duedate" => "now()", "paymentmethod" => $paymentmethod));

                                                                                                            if (!function_exists("createInvoices")) {
                                                                                                                require ROOTDIR . "/includes/processinvoices.php";
                                                                                                            }

                                                                                                            $invoiceid = createInvoices($client->getID());

                                                                                                            if ($invoiceid) {
                                                                                                                redir("id=" . $invoiceid, "viewinvoice.php");
                                                                                                            }

                                                                                                            redir();
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                                                                                    redir();
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$ca->output();
?>
