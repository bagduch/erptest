<?php

/**
 *
 * @ RA
 *
 * */
function createInvoices($func_userid = "", $noemails = "", $nocredit = "", $specificitems = "") {
    global $ra;
    global $cron;
    global $CONFIG;
    global $_LANG;
    global $invoicecount;
    global $invoiceid;
    global $continuous_invoicing_active_only;
//    error_log(print_r($specificitems, 1), 3, "/tmp/php_errors.log");
    $continvoicegen = $ra->get_config("ContinuousInvoiceGeneration");
    $invoicedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBefore'], date("Y")));
    $invoicedatemonthly = ($CONFIG['CreateInvoiceDaysBeforeMonthly'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeMonthly'], date("Y"))) : $invoicedate);
    $invoicedatequarterly = ($CONFIG['CreateInvoiceDaysBeforeQuarterly'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeQuarterly'], date("Y"))) : $invoicedate);
    $invoicedatesemiannually = ($CONFIG['CreateInvoiceDaysBeforeSemiAnnually'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeSemiAnnually'], date("Y"))) : $invoicedate);
    $invoicedateannually = ($CONFIG['CreateInvoiceDaysBeforeAnnually'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeAnnually'], date("Y"))) : $invoicedate);
    $invoicedatebiennially = ($CONFIG['CreateInvoiceDaysBeforeBiennially'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeBiennially'], date("Y"))) : $invoicedate);
    $invoicedatetriennially = ($CONFIG['CreateInvoiceDaysBeforeTriennially'] ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateInvoiceDaysBeforeTriennially'], date("Y"))) : $invoicedate);
    $descriptioninvoicedate = (0 < $ra->get_config("CreateServiceInvoiceDaysBefore") ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['CreateServiceInvoiceDaysBefore'], date("Y"))) : $invoicedate);
    $matchfield = ($continvoicegen ? "nextinvoicedate" : "nextduedate");
    $statusfilter = "'Pending','Active'";




    if (!$continuous_invoicing_active_only) {
        $statusfilter .= ",'Suspended'";
    }

    $hostingquery = "paymentmethod!=''
            AND servicestatus IN (" . $statusfilter . ")
            AND billingcycle!='Free'
            AND billingcycle!='Free Account'
            AND nextduedate!='00000000'
            AND nextinvoicedate!='00000000'
            AND (
                    (
                        billingcycle='Monthly'
                        AND " . $matchfield . "<='" . $invoicedatemonthly . ("'
                    ) OR (
                        billingcycle='Quarterly'
                        AND " . $matchfield . "<='") . $invoicedatequarterly . ("'
                    ) OR (
                        billingcycle='Semi-Annually'
                        AND " . $matchfield . "<='") . $invoicedatesemiannually . ("'
                    ) OR (
                        billingcycle='Annually'
                        AND " . $matchfield . "<='") . $invoicedateannually . ("'
                    ) OR (
                        billingcycle='Biennially'
                        AND " . $matchfield . "<='") . $invoicedatebiennially . ("'
                    ) OR (
                        billingcycle='Triennially'
                        AND " . $matchfield . "<='") . $invoicedatetriennially . "'
                    ) OR (
                        billingcycle='One Time'
                    )
            )";
    $descriptionquery = "
    paymentmethod!=''
    AND (donotrenew='' OR `status`='Pending')
    AND `status` IN (" . $statusfilter . ")
    AND " . $matchfield . "<='" . $descriptioninvoicedate . "'";
    $hostingaddonsquery = "tblserviceaddons.paymentmethod!='' AND tblserviceaddons.billingcycle!='Free' AND tblserviceaddons.billingcycle!='Free Account' AND tblserviceaddons.status IN (" . $statusfilter . ") AND tblserviceaddons.nextduedate!='00000000' AND tblserviceaddons.nextinvoicedate!='00000000' AND ((tblserviceaddons.billingcycle='Monthly' AND tblserviceaddons." . $matchfield . "<='" . $invoicedatemonthly . ("') OR (tblserviceaddons.billingcycle='Quarterly' AND tblserviceaddons." . $matchfield . "<='") . $invoicedatequarterly . ("') OR (tblserviceaddons.billingcycle='Semi-Annually' AND tblserviceaddons." . $matchfield . "<='") . $invoicedatesemiannually . ("') OR (tblserviceaddons.billingcycle='Annually' AND tblserviceaddons." . $matchfield . "<='") . $invoicedateannually . ("') OR (tblserviceaddons.billingcycle='Biennially' AND tblserviceaddons." . $matchfield . "<='") . $invoicedatebiennially . ("') OR (tblserviceaddons.billingcycle='Triennially' AND tblserviceaddons." . $matchfield . "<='") . $invoicedatetriennially . "') OR (tblserviceaddons.billingcycle='One Time'))";
    $i = 0;
    $billableitemqry = "";

    if ($func_userid != "") {
        $hostingquery .= " AND userid=" . (int) $func_userid;
        $descriptionquery .= " AND userid=" . (int) $func_userid;
        $hostingaddonsquery .= " AND tblcustomerservices.userid=" . (int) $func_userid;
        $billableitemqry = " AND userid=" . (int) $func_userid;
    }


    if (is_array($specificitems)) {
        $hostingquery = $descriptionquery = $hostingaddonsquery = "";

        if ($specificitems['products']) {
            $hostingquery .= "(id IN (" . db_build_in_array(db_escape_numarray($specificitems['products'])) . ") AND billingcycle!='Free' AND billingcycle!='Free Account')";
        }


        if ($specificitems['addons']) {
            $hostingaddonsquery .= "tblserviceaddons.id IN (" . db_build_in_array(db_escape_numarray($specificitems['addons'])) . ") AND tblserviceaddons.billingcycle!='Free' AND tblserviceaddons.billingcycle!='Free Account'";
        }


        if ($specificitems['description']) {
            $descriptionquery .= "id IN (" . db_build_in_array(db_escape_numarray($specificitems['description'])) . ")";
        }
    }

    $AddonsArray = $AddonSpecificIDs = array();

    if ($hostingquery) {
        $servicecount = 0;
        $cancellationreqids = array();
        $result = select_query_i("tblcancelrequests", "DISTINCT relid", "");

        while ($data = mysqli_fetch_array($result)) {
            $cancellationreqids[] = $data[0];
        }
//promoid removed
        $result = select_query_i(
                "tblcustomerservices", "tblcustomerservices.id,
            tblcustomerservices.userid,
            tblcustomerservices.nextduedate,
            tblcustomerservices.nextinvoicedate,
            tblcustomerservices.billingcycle,
            tblcustomerservices.regdate,
            tblcustomerservices.firstpaymentamount,
            tblcustomerservices.amount,
            tblcustomerservices.description,
            tblcustomerservices.paymentmethod,
            tblcustomerservices.packageid,
            tblcustomerservices.servicestatus", $hostingquery, "description", "ASC"
        );
        $totalservicerows = mysqli_num_rows($result);

        while ($data = mysqli_fetch_array($result)) {
            $id = $serviceid = $data['id'];

            if (!in_array($serviceid, $cancellationreqids)) {
                $userid = $data['userid'];
                $nextduedate = $data[$matchfield];
                $billingcycle = $data['billingcycle'];
                $status = $data['servicestatus'];
                $num_rows = get_query_val(
                        "tblinvoiceitems", "COUNT(id)", array(
                    "userid" => $userid,
                    "type" => "Service",
                    "relid" => $serviceid,
                    "duedate" => $nextduedate
                        )
                );
                $contblock = false;

                if ((!$num_rows && $continvoicegen) && $status == "Pending") {
                    $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", array("userid" => $userid, "type" => "Service", "relid" => $serviceid));
                    $contblock = true;
                }
                if ($num_rows == 0) {
                    $regdate = $data['regdate'];
                    $amount = ($regdate == $nextduedate ? $data['firstpaymentamount'] : $data['amount']);
                    $description = $data['description'];
                    $paymentmethod = $data['paymentmethod'];
                    $pid = $data['packageid'];
                    $promoid = $data['promoid'];
                    $productdetails = getInvoiceProductDetails($id, $pid, $regdate, $nextduedate, $billingcycle, $description);
                    $description = $productdetails['description'];
                    $tax = $productdetails['tax'];
                    $recurringcycles = $productdetails['recurringcycles'];
                    $recurringfinished = false;

                    if ($recurringcycles) {
                        $num_rows3 = get_query_val("tblinvoiceitems", "COUNT(id)", array("userid" => $userid, "type" => "Service", "relid" => $id));

                        if ($recurringcycles <= $num_rows3) {
                            update_query("tblcustomerservices", array("servicestatus" => "Completed"), array("id" => $id));
                            run_hook("ServiceRecurringCompleted", array("serviceid" => $id, "recurringinvoices" => $num_rows3));
                            $recurringfinished = true;
                        }
                    }


                    if (!$recurringfinished) {
                        $promovals = getInvoiceProductPromo($amount, $promoid, $userid, $id);

                        if (isset($promovals['description'])) {
                            $amount -= $promovals['amount'];
                        }

//                        insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Service", "relid" => $id, "description" => $description, "amount" => $amount, "taxed" => $tax, "duedate" => $nextduedate, "paymentmethod" => $paymentmethod));

                        if (isset($promovals['description'])) {
                            insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Promo", "relid" => $id, "description" => $promovals['description'], "amount" => $promovals['amount'], "taxed" => $tax, "duedate" => $nextduedate, "paymentmethod" => $paymentmethod));
                        }
                    }
                } else {
                    if ((!$contblock && $continvoicegen) && $billingcycle != "One Time") {
                        update_query("tblcustomerservices", array("nextinvoicedate" => getInvoicePayUntilDate($nextduedate, $billingcycle, true)), array("id" => $id));
                    }
                }
            }


            if ($hostingaddonsquery) {
                $result3 = select_query_i(
                        "tblserviceaddons", "tblserviceaddons.*,
                    tblserviceaddons.regdate AS addonregdate,
                    tblcustomerservices.userid,
                    tblcustomerservices.description", $hostingaddonsquery . (" AND tblserviceaddons.hostingid='" . $id . "'"), "tblserviceaddons`.`name", "ASC", "", "tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid"
                );

                while ($data = mysqli_fetch_array($result3)) {
                    $id = $data['id'];
                    $userid = $data['userid'];
                    $nextduedate = $data[$matchfield];
                    $status = $data['status'];
                    $num_rows = get_query_val(
                            "tblinvoiceitems", "COUNT(id)", array(
                        "userid" => $userid,
                        "type" => "Addon",
                        "relid" => $id,
                        "duedate" => $nextduedate
                            )
                    );
                    $contblock = false;

                    if ((!$num_rows && $continvoicegen) && $status == "Pending") {
                        $num_rows = get_query_val(
                                "tblinvoiceitems", "COUNT(id)", array(
                            "userid" => $userid,
                            "type" => "Addon",
                            "relid" => $id
                                )
                        );
                        $contblock = true;
                    }

                    if ($num_rows == 0) {
                        $hostingid = $serviceid = $data['hostingid'];
                        $addonid = $data['addonid'];
                        $description = $data['description'];
                        $regdate = $data['addonregdate'];
                        $name = $data['name'];
                        $setupfee = $data['setupfee'];
                        $amount = $data['recurring'];
                        $paymentmethod = $data['paymentmethod'];
                        $billingcycle = $data['billingcycle'];
                        $tax = $data['tax'];
                        if (!$name) {
                            if (isset($AddonsArray[$addonid])) {
                                $name = $AddonsArray[$addonid];
                            } else {
                                $AddonsArray[$addonid] = $name = get_query_val("tbladdons", "name", array("id" => $addonid));
                            }
                        }

                        $tax = (($CONFIG['TaxEnabled'] && $tax) ? "1" : "0");
                        $invoicepayuntildate = getInvoicePayUntilDate($nextduedate, $billingcycle);
                        $paydates = "";

                        if ($billingcycle != "One Time") {
                            $paydates = "(" . fromMySQLDate($nextduedate) . " - " . fromMySQLDate($invoicepayuntildate) . ")";
                        }
                        $num_rows = get_query_val(
                                "tblinvoiceitems", "COUNT(id)", array(
                            "userid" => $userid,
                            "type" => "Addon",
                            "relid" => $id,
                            "duedate" => $nextduedate
                                )
                        );
                        if ($num_rows == 0) {
                            if (!in_array($serviceid, $cancellationreqids)) {
                                if ($regdate == $nextduedate) {
                                    $amount = $amount + $setupfee;
                                }
                                if ($description) {
                                    $description = "(" . $description . ") ";
                                }
                                $description = $_LANG['orderaddon'] . (" " . $description . "- " . $name . " " . $paydates);
                                insert_query(
                                        "tblinvoiceitems", array(
                                    "userid" => $userid,
                                    "type" => "Addon",
                                    "relid" => $id,
                                    "description" => $description,
                                    "amount" => $amount,
                                    "taxed" => $tax,
                                    "duedate" => $nextduedate,
                                    "paymentmethod" => $paymentmethod
                                        )
                                );
                                $AddonSpecificIDs[] = $id;
                            }
                        }
                        if (!$contblock && $continvoicegen) {
                            update_query(
                                    "tblserviceaddons", array(
                                "nextinvoicedate" => getInvoicePayUntilDate($nextduedate, $billingcycle, true)
                                    ), array(
                                "id" => $id
                                    )
                            );
                        }
                    }
                }
            }
            ++$servicecount;
            if (is_object($cron)) {
                $cron->logActivityDebug("Invoicing Loop Service ID " . $serviceid . " - " . $servicecount . " of " . $totalservicerows);
            }
        }
    }

    if ($hostingaddonsquery) {
        $addoncount = 0;
        if (count($AddonSpecificIDs)) {
            $hostingaddonsquery .= " AND tblserviceaddons.id NOT IN (" . db_build_in_array(db_escape_numarray($AddonSpecificIDs)) . ")";
        }
        $result = select_query_i("tblserviceaddons", "tblserviceaddons.*,tblserviceaddons.regdate AS addonregdate,tblcustomerservices.userid,tblcustomerservices.description", $hostingaddonsquery, "tblserviceaddons`.`name", "ASC", "", "tblcustomerservices ON tblcustomerservices.id=tblserviceaddons.hostingid");
        $totaladdonrows = mysqli_num_rows($result);
        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $userid = $data['userid'];
            $nextduedate = $data[$matchfield];
            $status = $data['status'];
            $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", array("userid" => $userid, "type" => "Addon", "relid" => $id, "duedate" => $nextduedate));
            $contblock = false;
            if ((!$num_rows && $continvoicegen) && $status == "Pending") {
                $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", array("userid" => $userid, "type" => "Addon", "relid" => $id));
                $contblock = true;
            }

            if ($num_rows == 0) {
                $hostingid = $serviceid = $data['hostingid'];
                $addonid = $data['addonid'];
                $description = $data['description'];
                $regdate = $data['addonregdate'];
                $name = $data['name'];
                $setupfee = $data['setupfee'];
                $amount = $data['recurring'];
                $paymentmethod = $data['paymentmethod'];
                $billingcycle = $data['billingcycle'];
                $tax = $data['tax'];
                if (!$name) {
                    if ($AddonsArray[$addonid]) {
                        $name = $AddonsArray[$addonid];
                    } else {
                        $AddonsArray[$addonid] = $name = get_query_val("tbladdons", "name", array("id" => $addonid));
                    }
                }
                $tax = (($CONFIG['TaxEnabled'] && $tax) ? "1" : "0");
                $invoicepayuntildate = getInvoicePayUntilDate($nextduedate, $billingcycle);
                $paydates = "";
                if ($billingcycle != "One Time") {
                    $paydates = "(" . fromMySQLDate($nextduedate) . " - " . fromMySQLDate($invoicepayuntildate) . ")";
                }
                if (!in_array($serviceid, $cancellationreqids)) {
                    if ($regdate == $nextduedate) {
                        $amount = $amount + $setupfee;
                    }
                    if ($description) {
                        $description = "(" . $description . ") ";
                    }
                    $description = $_LANG['orderaddon'] . (" " . $description . "- " . $name . " " . $paydates);
                    insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Addon", "relid" => $id, "description" => $description, "amount" => $amount, "taxed" => $tax, "duedate" => $nextduedate, "paymentmethod" => $paymentmethod));
                }
            } else {
                if (!$contblock && $continvoicegen) {
                    update_query("tblserviceaddons", array("nextinvoicedate" => getInvoicePayUntilDate($nextduedate, $billingcycle, true)), array("id" => $id));
                }
            }
            ++$addoncount;
            if (is_object($cron)) {
                $cron->logActivityDebug("Invoicing Loop Addon ID " . $id . " - " . $addoncount . " of " . $totaladdonrows);
            }
        }
    }
    if ($descriptionquery) {
        $descriptioncount = 0;
        $result = select_query_i("tblcustomerservices", "", $descriptionquery, "description", "ASC");
        $totaldomainrows = mysqli_num_rows($result);
        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $userid = $data['userid'];
            $nextduedate = $data[$matchfield];
            $status = $data['status'];
            $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", "userid='" . $userid . "' AND type IN ('Service','Upgrade','ServiceTransfer') AND relid='" . $id . "' AND duedate='" . $nextduedate . "'");
            $contblock = false;

            if ((!$num_rows && $continvoicegen) && $status == "Pending") {
                $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", "userid='" . $userid . "' AND type IN ('Service','Upgrade','ServiceTransfer') AND relid='" . $id . "'");
                $contblock = true;
            }


            if ($num_rows == 0) {
                $type = $data['type'];
                $description = $data['description'];
                $registrationperiod = $data['registrationperiod'];
                $regdate = $data['registrationdate'];
                $expirydate = $data['expirydate'];
                $paymentmethod = $data['paymentmethod'];
                $dnsmanagement = $data['dnsmanagement'];
                $emailforwarding = $data['emailforwarding'];
                $idprotection = $data['idprotection'];
                $promoid = $data['promoid'];
                getUsersLang($userid);

                if ($expirydate == "0000-00-00") {
                    $expirydate = $nextduedate;
                }


                if ($regdate == $nextduedate) {
                    $amount = $data['firstpaymentamount'];

                    if ($type == "Transfer") {
                        $descriptiondesc = $_LANG['domaintransfer'];
                    } else {
                        $descriptiondesc = $_LANG['domainregistration'];
                        $type = "Register";
                    }
                } else {
                    $amount = $data['recurringamount'];
                    $descriptiondesc = $_LANG['domainrenewal'];
                    $type = "";
                }

                $tax = (($CONFIG['TaxEnabled'] && $CONFIG['TaxServices']) ? "1" : "0");
                $descriptiondesc .= " - " . $description . " - " . $registrationperiod . " " . $_LANG['orderyears'];

                if ($type != "Transfer") {
                    $descriptiondesc .= " (" . fromMySQLDate($expirydate) . " - " . fromMySQLDate(getInvoicePayUntilDate($expirydate, $registrationperiod)) . ")";
                }


                if ($dnsmanagement) {
                    $descriptiondesc .= "\r\n + " . $_LANG['domaindnsmanagement'];
                }


                if ($emailforwarding) {
                    $descriptiondesc .= "\r\n + " . $_LANG['domainemailforwarding'];
                }


                if ($idprotection) {
                    $descriptiondesc .= "\r\n + " . $_LANG['domainidprotection'];
                }

                $promo_description = $promo_amount = 0;

                if ($promoid) {
                    $data = get_query_vals("tblpromotions", "", array("id" => $promoid));
                    $promo_id = $data['id'];

                    if ($promo_id) {
                        $promo_code = $data['code'];
                        $promo_type = $data['type'];
                        $promo_recurring = $data['recurring'];
                        $promo_value = $data['value'];

                        if ($promo_recurring || (!$promo_recurring && $regdate == $nextduedate)) {
                            if ($promo_type == "Percentage") {
                                $promo_amount = round($amount / (1 - $promo_value / 100), 2) - $amount;
                                $promo_value .= "%";
                            } else {
                                if ($promo_type == "Fixed Amount") {
                                    $promo_amount = $promo_value;
                                    $currency = getCurrency($userid);
                                    $promo_value = formatCurrency($promo_value);
                                }
                            }

                            $amount += $promo_amount;
                            $promo_recurring = ($promo_recurring ? $_LANG['recurring'] : $_LANG['orderpaymenttermonetime']);
                            $promo_description = $_LANG['orderpromotioncode'] . (": " . $promo_code . " - " . $promo_value . " " . $promo_recurring . " ") . $_LANG['orderdiscount'];
                            $promo_amount *= 0 - 1;
                        }
                    }
                }

                insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "Service" . $type, "relid" => $id, "description" => $descriptiondesc, "amount" => $amount, "taxed" => $tax, "duedate" => $nextduedate, "paymentmethod" => $paymentmethod));

                if ($promo_description) {
                    insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "PromoService", "relid" => $id, "description" => $promo_description, "amount" => $promo_amount, "taxed" => $tax, "duedate" => $nextduedate, "paymentmethod" => $paymentmethod));
                }
            } else {
                if (!$contblock && $continvoicegen) {
                    $year = substr($nextduedate, 0, 4);
                    $month = substr($nextduedate, 5, 2);
                    $day = substr($nextduedate, 8, 2);
                    $new_time = mktime(0, 0, 0, $month, $day, $year + $registrationperiod);
                    $nextinvoicedate = date("Ymd", $new_time);
                    update_query("tblcustomerservices", array("nextinvoicedate" => $nextinvoicedate), array("id" => $id));
                }
            }

            getUsersLang(0);
            ++$descriptioncount;

            if (is_object($cron)) {
                $cron->logActivityDebug("Invoicing Loop Service ID " . $id . " - " . $descriptioncount . " of " . $totaldomainrows);
            }
        }
    }


    if (!is_array($specificitems)) {
        $billableitemstax = (($CONFIG['TaxEnabled'] && $CONFIG['TaxBillableItems']) ? "1" : "0");
        $result = select_query_i("tblbillableitems", "", "((invoiceaction='1' AND invoicecount='0') OR (invoiceaction='3' AND invoicecount='0' AND duedate<='" . $invoicedate . "') OR (invoiceaction='4' AND duedate<='" . $invoicedate . "' AND (recurfor='0' OR invoicecount<recurfor)))" . $billableitemqry);

        while ($data = mysqli_fetch_array($result)) {
            $paymentmethod = getClientsPaymentMethod($data['userid']);

            if ($data['invoiceaction'] != "4") {
                insert_query("tblinvoiceitems", array("userid" => $data['userid'], "type" => "Item", "relid" => $data['id'], "description" => $data['description'], "amount" => $data['amount'], "taxed" => $billableitemstax, "duedate" => $data['duedate'], "paymentmethod" => $paymentmethod));
            }

            $updatearray = array("invoicecount" => "+1");

            if ($data['invoiceaction'] == "4") {
                $num_rows = get_query_val("tblinvoiceitems", "COUNT(id)", array("type" => "Item", "relid" => $data['id'], "duedate" => $data['duedate']));

                if ($num_rows == 0) {
                    insert_query("tblinvoiceitems", array("userid" => $data['userid'], "type" => "Item", "relid" => $data['id'], "description" => $data['description'], "amount" => $data['amount'], "taxed" => $billableitemstax, "duedate" => $data['duedate'], "paymentmethod" => $paymentmethod));
                }

                $adddays = $addmonths = $addyears = 0;

                if ($data['recurcycle'] == "Days") {
                    $adddays = $data['recur'];
                } else {
                    if ($data['recurcycle'] == "Weeks") {
                        $adddays = $data['recur'] * 7;
                    } else {
                        if ($data['recurcycle'] == "Months") {
                            $addmonths = $data['recur'];
                        } else {
                            if ($data['recurcycle'] == "Years") {
                                $addyears = $data['recur'];
                            }
                        }
                    }
                }

                $year = substr($data['duedate'], 0, 4);
                $month = substr($data['duedate'], 5, 2);
                $day = substr($data['duedate'], 8, 2);
                $updatearray['duedate'] = date("Ymd", mktime(0, 0, 0, $month + $addmonths, $day + $adddays, $year + $addyears));
            }

            update_query("tblbillableitems", $updatearray, array("id" => $data['id']));
        }
    }

    $invoicecount = $invoiceid = 0;
    $where = array();
    $where[] = "invoiceid is null";

    if ($func_userid) {
        $where[] = "userid=" . (int) $func_userid;
    }


    if (is_array($specificitems)) {


        $where[] = "tblclients.separateinvoices=''";
        $where[] = "(tblclientgroups.separateinvoices='' OR tblclientgroups.separateinvoices is null)";
    }

    $result = select_query_i("tblinvoiceitems", "DISTINCT tblinvoiceitems.userid,tblinvoiceitems.duedate,tblinvoiceitems.paymentmethod", implode(" AND ", $where), "duedate", "ASC", "", "tblclients ON tblclients.id=tblinvoiceitems.userid LEFT JOIN tblclientgroups ON tblclientgroups.id=tblclients.groupid");

    while ($data = mysqli_fetch_array($result)) {

        createInvoicesProcess($data, $noemails, $nocredit);
    }


    if (!is_array($specificitems)) {
        error_log(print_r("orhere", 1), 3, "/tmp/php_errors.log");
        $where = array();
        $where[] = "invoiceid=0";

        if ($func_userid) {
            $where[] = "userid=" . (int) $func_userid;
        }

        $where[] = "(tblclients.separateinvoices='on' OR tblclientgroups.separateinvoices='on')";
        $result = select_query_i("tblinvoiceitems", "tblinvoiceitems.id,tblinvoiceitems.userid,tblinvoiceitems.type,tblinvoiceitems.relid,tblinvoiceitems.duedate,tblinvoiceitems.paymentmethod", implode(" AND ", $where), "duedate", "ASC", "", "tblclients ON tblclients.id=tblinvoiceitems.userid LEFT JOIN tblclientgroups ON tblclientgroups.id=tblclients.groupid");
        while ($data = mysqli_fetch_array($result)) {


            createInvoicesProcess($data, $noemails, $nocredit);
        }
    }


    if (is_object($cron)) {
        $cron->logActivity("" . $invoicecount . " Invoices Created", true);
        $cron->emailLog($invoicecount . " Invoices Created");
    }


    if ($func_userid) {
        return $invoiceid;
    }
}

function invoicereminders($CONFIG) {
    if ($CONFIG['SendReminder'] == "on") {
        $reminders = "";
        if ($CONFIG['SendInvoiceReminderDays']) {
            $invoiceids = array();
            $invoicedateyear = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $CONFIG['SendInvoiceReminderDays'], date("Y")));
            $query = "SELECT * FROM tblinvoices WHERE duedate='" . $invoicedateyear . "' AND `status`='Unpaid'";
            $result = full_query_i($query);
            while ($data = mysqli_fetch_array($result)) {
                $id = $data['id'];
                sendMessage("Invoice Payment Reminder", $id);
                run_hook("InvoicePaymentReminder", array("invoiceid" => $id, "type" => "reminder"));
                $invoiceids[] = $id;
            }

            $invoicenums = (count($invoiceids) ? " to Invoice Numbers " . implode(",", $invoiceids) : "");
            $returnvalue = array("number" => $invoicenums, "id" => $invoiceids);
        }
    }
    SendOverdueInvoiceReminders();
    return $returnvalue;
}

function createInvoicesProcess($data, $noemails = "", $nocredit = "") {
    global $ra;
    global $cron;
    global $CONFIG;
    global $_LANG;
    global $invoicecount;
    global $invoiceid;
    //error_log(print_r($data, 1), 3, "/tmp/php_errors.log");
    $itemid = $data['id'];
    $userid = $data['userid'];
    $type = $data['type'];
    $relid = $data['relid'];
    $duedate = $data['duedate'];
    $paymentmethod = $invpaymentmethod = $data['paymentmethod'];

    if (!$invpaymentmethod) {
        if (!function_exists("getClientsPaymentMethod")) {
            require ROOTDIR . "/includes/clientfunctions.php";
        }

        $invpaymentmethod = getClientsPaymentMethod($userid);
        update_query("tblinvoiceitems", array("paymentmethod" => $invpaymentmethod), array("id" => $itemid));
    }


    if ($itemid) {
        if (get_query_val("tblinvoiceitems", "invoiceid", array("id" => $itemid))) {
            return false;
        }
    }

    $taxrate = $taxrate2 = 0;

    if ($CONFIG['TaxEnabled']) {
        $data = get_query_vals("tblclients", "taxexempt,state,country,separateinvoices", array("id" => $userid));
        $taxexempt = $data['taxexempt'];
        $taxstate = $data['state'];
        $taxcountry = $data['country'];

        if (!$taxexempt) {
            $taxrate = getTaxRate(1, $taxstate, $taxcountry);
            $taxrate2 = getTaxRate(2, $taxstate, $taxcountry);
            $taxrate = $taxrate['rate'];
            $taxrate2 = $taxrate2['rate'];
        }
    }

    $invoicearray = array("date" => "now()", "duedate" => $duedate, "userid" => $userid, "status" => "Unpaid", "taxrate" => $taxrate, "taxrate2" => $taxrate2, "paymentmethod" => $invpaymentmethod, "notes" => $invoicenotes);

    $invoiceid = insert_query("tblinvoices", $invoicearray);

    if ($itemid) {
        update_query("tblinvoiceitems", array("invoiceid" => $invoiceid), array("invoiceid" => "0", "userid" => $userid, "type" => "Promo" . $type, "relid" => $relid));
        $where = array("id" => $itemid);
    } else {
        $where = array("invoiceid" => "NULL", "duedate" => $duedate, "userid" => $userid, "paymentmethod" => $paymentmethod);
    }

    update_query("tblinvoiceitems", array("invoiceid" => $invoiceid), $where);
    logActivity("Created Invoice - Invoice ID: " . $invoiceid, $userid);

    if (is_object($cron)) {
        $cron->logActivityDebug("Generated Invoice #" . $invoiceid);
    }

    $billableitemstax = (($CONFIG['TaxEnabled'] && $CONFIG['TaxCustomInvoices']) ? "1" : "0");
    $result2 = select_query_i("tblbillableitems", "", array("userid" => $userid, "invoiceaction" => "2", "invoicecount" => "0"));

    while ($data = mysqli_fetch_array($result2)) {
        insert_query("tblinvoiceitems", array("invoiceid" => $invoiceid, "userid" => $userid, "type" => "Item", "relid" => $data['id'], "description" => $data['description'], "amount" => $data['amount'], "taxed" => $billableitemstax));
        update_query("tblbillableitems", array("invoicecount" => "+1"), array("id" => $data['id']));
    }

    updateInvoiceTotal($invoiceid);
    $data2 = get_query_vals("tblclients", "credit,groupid", array("id" => $userid));
    $credit = $data2['credit'];
    $groupid = $data2['groupid'];
    $data2 = get_query_vals("tblinvoices", "subtotal,total", array("id" => $invoiceid));
    $subtotal = $data2['subtotal'];
    $total = $data2['total'];
    $isaddfundsinvoice = get_query_val("tblinvoiceitems", "COUNT(id)", "invoiceid='" . $invoiceid . "' AND (type='AddFunds' OR type='Invoice')");

    if ($groupid && !$isaddfundsinvoice) {
        $discountpercent = get_query_val("tblclientgroups", "discountpercent", array("id" => $groupid));

        if (0 < $discountpercent) {
            $discountamount = $subtotal * ($discountpercent / 100) * (0 - 1);
            insert_query("tblinvoiceitems", array("invoiceid" => $invoiceid, "userid" => $userid, "type" => "GroupDiscount", "description" => $_LANG['clientgroupdiscount'], "amount" => $discountamount, "taxed" => "1"));
            updateInvoiceTotal($invoiceid);
            $data2 = get_query_vals("tblclients", "credit,groupid", array("id" => $userid));
            $credit = $data2['credit'];
            $groupid = $data2['groupid'];
            $data2 = get_query_vals("tblinvoices", "subtotal,total", array("id" => $invoiceid));
            $subtotal = $data2['subtotal'];
            $total = $data2['total'];
        }
    }


    if ($ra->get_config("ContinuousInvoiceGeneration")) {
        $result2 = select_query_i("tblinvoiceitems", "", array("invoiceid" => $invoiceid));

        while ($data = mysqli_fetch_array($result2)) {
            $type = $data['type'];
            $relid = $data['relid'];
            $nextinvoicedate = $data['duedate'];
            $year = substr($nextinvoicedate, 0, 4);
            $month = substr($nextinvoicedate, 5, 2);
            $day = substr($nextinvoicedate, 8, 2);
            $proratabilling = false;

            if ($type == "Services") {
                $data = get_query_vals("tblcustomerservices", "billingcycle,packageid,regdate,nextduedate", array("id" => $relid));
                $billingcycle = $data['billingcycle'];
                $packageid = $data['packageid'];
                $regdate = $data['regdate'];
                $nextduedate = $data['nextduedate'];
                $data = get_query_vals("tblservices", "proratabilling,proratadate,proratachargenextmonth", array("id" => $packageid));
                $proratabilling = $data['proratabilling'];
                $proratadate = $data['proratadate'];
                $proratachargenextmonth = $data['proratachargenextmonth'];
                $proratamonths = getBillingCycleMonths($billingcycle);
                $nextinvoicedate = date("Ymd", mktime(0, 0, 0, $month + $proratamonths, $day, $year));
            } else {
                if (($type == "Service" || $type == "Upgrade") || $type == "ServiceTransfer") {
                    $data = get_query_vals("tblcustomerservices", "registrationperiod,nextduedate", array("id" => $relid));
                    $registrationperiod = $data['registrationperiod'];
                    $nextduedate = explode("-", $data['nextduedate']);
                    $billingcycle = "";
                    $nextinvoicedate = date("Ymd", mktime(0, 0, 0, $nextduedate[1], $nextduedate[2], $nextduedate[0] + $registrationperiod));
                } else {
                    if ($type == "Addon") {
                        $billingcycle = get_query_val("tblserviceaddons", "billingcycle", array("id" => $relid));
                        $proratamonths = getBillingCycleMonths($billingcycle);
                        $nextinvoicedate = date("Ymd", mktime(0, 0, 0, $month + $proratamonths, $day, $year));
                    }
                }
            }


            if ($billingcycle == "One Time") {
                $nextinvoicedate = "00000000";
            }


            if ($regdate == $nextduedate && $proratabilling) {
                if ($billingcycle != "Monthly") {
                    $proratachargenextmonth = 0;
                }

                $orderyear = substr($regdate, 0, 4);
                $ordermonth = substr($regdate, 5, 2);
                $orderday = substr($regdate, 8, 2);

                if ($orderday < $proratadate) {
                    $proratamonth = $ordermonth;
                } else {
                    $proratamonth = $ordermonth + 1;
                }

                $days = (strtotime(date("Y-m-d", mktime(0, 0, 0, $proratamonth, $proratadate, $orderyear))) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
                $totaldays = 30;
                $nextinvoicedate = date("Y-m-d", mktime(0, 0, 0, $proratamonth, $proratadate, $orderyear));

                if ($proratachargenextmonth <= $orderday && $days < 31) {
                    $nextinvoicedate = date("Y-m-d", mktime(0, 0, 0, $proratamonth + $proratamonths, $proratadate, $orderyear));
                }
            }


            if ($type == "Service") {
                update_query("tblcustomerservices", array("nextinvoicedate" => $nextinvoicedate), array("id" => $relid));
            }


            if (($type == "Service" || $type == "Upgrade") || $type == "ServiceTransfer") {
                update_query("tblcustomerservices", array("nextinvoicedate" => $nextinvoicedate), array("id" => $relid));
            }


            if ($type == "Addon") {
                update_query("tblserviceaddons", array("nextinvoicedate" => $nextinvoicedate), array("id" => $relid));
            }
        }
    }

    $doprocesspaid = false;

    if ((!$nocredit && $credit != "0.00") && !$CONFIG['NoAutoApplyCredit']) {
        if ($total <= $credit) {
            $creditleft = $credit - $total;
            $credit = $total;
            $doprocesspaid = true;
        } else {
            $creditleft = 0;
        }

        logActivity("Credit Automatically Applied at Invoice Creation - Invoice ID: " . $invoiceid . " - Amount: " . $credit, $userid);
        insert_query("tblcredit", array("clientid" => $userid, "date" => "now()", "description" => "Credit Applied to Invoice #" . $invoiceid, "amount" => $credit * (0 - 1)));
        update_query("tblclients", array("credit" => $creditleft), array("id" => $userid));
        update_query("tblinvoices", array("credit" => $credit), array("id" => $invoiceid));
        updateInvoiceTotal($invoiceid);
    }

    run_hook("InvoiceCreationPreEmail", array("invoiceid" => $invoiceid));

    if ($doprocesspaid) {
        processPaidInvoice($invoiceid);
    }

    $result2 = select_query_i("tblpaymentgateways", "value", array("gateway" => $invpaymentmethod, "setting" => "type"));
    $data2 = mysqli_fetch_array($result2);
    $paymenttype = $data2['value'];

    if ($noemails != "true") {
        sendMessage((($paymenttype == "CC" || $paymenttype == "OfflineCC") ? "Credit Card " : "") . "Invoice Created", $invoiceid);
    }

    $result2 = select_query_i("tblinvoices", "total", array("id" => $invoiceid, "status" => "Unpaid"));
    $data2 = mysqli_fetch_array($result2);
    $total = $data2['total'];

    if ($total == "0.00") {
        processPaidInvoice($invoiceid);
    }

    run_hook("InvoiceCreated", array("invoiceid" => $invoiceid));
    $invoicetotal = 0;
    ++$invoicecount;

    if (1 < $CONFIG['InvoiceIncrement']) {
        $invoiceincrement = $CONFIG['InvoiceIncrement'] - 1;
        $counter = 1;

        while ($counter <= $invoiceincrement) {
            $tempinvoiceid = insert_query("tblinvoices", array("date" => "now()"));
            delete_query("tblinvoices", array("id" => $tempinvoiceid));
            $counter += 1;
        }
    }
}

function getInvoiceProductDetails($id, $pid, $regdate, $nextduedate, $billingcycle, $description) {
    global $CONFIG;
    global $_LANG;
    global $currency;

    $data = get_query_vals("tblservices", "type,name,tax,proratabilling,proratadate,proratachargenextmonth,recurringcycles", array("id" => $pid));
    $type = $data['type'];
    $package = $data['name'];
    $tax = $data['tax'];
    $proratabilling = $data['proratabilling'];
    $proratadate = $data['proratadate'];
    $proratachargenextmonth = $data['proratachargenextmonth'];
    $recurringcycles = $data['recurringcycles'];
    $userid = get_query_val("tblcustomerservices", "userid", array("id" => $id));
    $currency = getCurrency($userid);

    if ($tax && $CONFIG['TaxEnabled']) {
        $tax = "1";
    } else {
        $tax = "0";
    }

    $paydates = "";

    if ($regdate || $nextduedate) {
        if ($regdate == $nextduedate && $proratabilling) {
            $orderyear = substr($regdate, 0, 4);
            $ordermonth = substr($regdate, 5, 2);
            $orderday = substr($regdate, 8, 2);
            $proratavalues = getProrataValues($billingcycle, 0, $proratadate, $proratachargenextmonth, $orderday, $ordermonth, $orderyear);
            $invoicepayuntildate = $proratavalues['invoicedate'];
        } else {
            $invoicepayuntildate = getInvoicePayUntilDate($nextduedate, $billingcycle);
        }


        if ($billingcycle != "One Time") {
            $paydates = " (" . fromMySQLDate($nextduedate) . " - " . fromMySQLDate($invoicepayuntildate) . ")";
        }
    }

    $description = $package;

    if ($description) {
        $description .= " - " . $description;
    }

    $description .= $paydates;
    $configbillingcycle = $billingcycle;

    if ($configbillingcycle == "One Time" || $configbillingcycle == "Free Account") {
        $configbillingcycle = "monthly";
    }

    $configbillingcycle = strtolower(str_replace("-", "", $configbillingcycle));
    $query = "SELECT tblserviceconfigoptions.id, tblserviceconfigoptions.optionname AS confoption, tblserviceconfigoptions.optiontype AS conftype, tblserviceconfigoptionssub.optionname, tblhostingconfigoptions.qty,tblhostingconfigoptions.optionid FROM tblhostingconfigoptions INNER JOIN tblserviceconfigoptions ON tblserviceconfigoptions.id = tblhostingconfigoptions.configid INNER JOIN tblserviceconfigoptionssub ON tblserviceconfigoptionssub.id = tblhostingconfigoptions.optionid INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblhostingconfigoptions.relid INNER JOIN tblserviceconfiglinks ON tblserviceconfiglinks.gid=tblserviceconfigoptions.gid WHERE tblhostingconfigoptions.relid=" . (int) $id . " AND tblserviceconfigoptions.hidden='0' AND tblserviceconfigoptionssub.hidden='0' AND tblserviceconfiglinks.pid=tblcustomerservices.packageid ORDER BY tblserviceconfigoptions.`order`,tblserviceconfigoptions.id ASC";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $confoption = $data['confoption'];
        $conftype = $data['conftype'];

        if (strpos($confoption, "|")) {
            $confoption = explode("|", $confoption);
            $confoption = trim($confoption[1]);
        }

        $optionname = $data['optionname'];
        $optionqty = $data['qty'];
        $optionid = $data['optionid'];

        if (strpos($optionname, "|")) {
            $optionname = explode("|", $optionname);
            $optionname = trim($optionname[1]);
        }


        if ($conftype == 3) {
            if ($optionqty) {
                $optionname = $_LANG['yes'];
            } else {
                $optionname = $_LANG['no'];
            }
        } else {
            if ($conftype == 4) {
                $optionname = "" . $optionqty . " x " . $optionname . " ";
                $qtyprice = get_query_val("tblpricing", $configbillingcycle, array("type" => "configoptions", "currency" => $currency['id'], "relid" => $optionid));
                $optionname .= formatCurrency($qtyprice);
            }
        }

        $description .= ("\r\n") . $confoption . ": " . $optionname;
    }

    $result = select_query_i("tblcustomfields", "tblcustomfields.id,tblcustomfields.fieldname,(SELECT value FROM tblcustomfieldsvalues WHERE tblcustomfieldsvalues.fieldid=tblcustomfields.id AND tblcustomfieldsvalues.relid=" . $id . " LIMIT 1) AS value", array("type" => "product", "relid" => $pid, "showinvoice" => "on"));

    while ($data = mysqli_fetch_assoc($result)) {
        if ($data['value']) {
            $description .= "\r\n" . $data['fieldname'] . ": " . $data['value'];
        }
    }

    return array("description" => $description, "tax" => $tax, "recurringcycles" => $recurringcycles);
}

function InvoicesAddLateFee() {
    global $CONFIG;
    global $_LANG;
    global $cron;

    if ($CONFIG['TaxLateFee']) {
        $taxlatefee = "1";
    }

    $invoiceids = array();

    if ($CONFIG['InvoiceLateFeeAmount'] != "0.00") {
        if ($CONFIG['AddLateFeeDays'] == "") {
            $CONFIG['AddLateFeeDays'] = "0";
        }

        $adddate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $CONFIG['AddLateFeeDays'], date("Y")));
        $query = "SELECT tblinvoices.* FROM tblinvoices INNER JOIN tblclients ON tblclients.id=tblinvoices.userid WHERE duedate<'" . $adddate . "' AND tblinvoices.status='Unpaid' AND duedate!=date AND latefeeoveride=''";
        $result = full_query_i($query);

        while ($data = mysqli_fetch_array($result)) {
            $userid = $data['userid'];
            $invoiceid = $data['id'];
            $duedate = $data['duedate'];
            $paymentmethod = $data['paymentmethod'];
            $total = $data['total'];

            if (!get_query_val("tblinvoiceitems", "COUNT(id)", array("type" => "LateFee", "invoiceid" => $invoiceid))) {
                if ($CONFIG['LateFeeType'] == "Percentage") {
                    $amountpaid = get_query_val("tblaccounts", "SUM(amountin)-SUM(amountout)", array("invoiceid" => $invoiceid));
                    $balance = round($total - $amountpaid, 2);
                    $latefeeamount = format_as_currency($balance * ($CONFIG['InvoiceLateFeeAmount'] / 100));
                } else {
                    $latefeeamount = $CONFIG['InvoiceLateFeeAmount'];
                }

                if (0 < $CONFIG['LateFeeMinimum'] && $latefeeamount < $CONFIG['LateFeeMinimum']) {
                    $latefeeamount = $CONFIG['LateFeeMinimum'];
                }

                getUsersLang($userid);
                insert_query("tblinvoiceitems", array("userid" => $userid, "type" => "LateFee", "invoiceid" => $invoiceid, "description" => $_LANG['latefee'] . " (" . $_LANG['latefeeadded'] . " " . fromMySQLDate(date("Y-m-d")) . ")", "amount" => $latefeeamount, "duedate" => $duedate, "paymentmethod" => $paymentmethod, "taxed" => $taxlatefee));

                if (!function_exists("updateInvoiceTotal")) {
                    require dirname(__FILE__) . "/invoicefunctions.php";
                }

                updateInvoiceTotal($invoiceid);
                run_hook("AddInvoiceLateFee", array("invoiceid" => $invoiceid));
                $invoiceids[] = $invoiceid;
            }
        }
    }


    if (is_object($cron)) {
        $cron->logActivity("Late Invoice Fees added to " . count($invoiceids) . " Invoices" . (count($invoiceids) ? " (Invoice Numbers: " . implode(",", $invoiceids) . ")" : ""), true);
        $cron->emailLog(count($invoiceids) . " Late Fees Added" . (count($invoiceids) ? " to Invoice Numbers " . implode(",", $invoiceids) : ""));
    }
}

function SendOverdueInvoiceReminders() {
    global $ra;
    global $CONFIG;
    global $cron;

    $count = 0;
    $types = array("First", "Second", "Third");
    foreach ($types as $type) {

        if ($CONFIG["Send" . $type . "OverdueInvoiceReminder"] != "0") {
            $adddate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $CONFIG["Send" . $type . "OverdueInvoiceReminder"], date("Y")));
            $result = select_query_i("tblinvoices,tblclients", "tblinvoices.id,tblinvoices.userid,tblclients.firstname,tblclients.lastname", array("tblinvoices.duedate" => $adddate, "tblinvoices.status" => "Unpaid", "tblclients.overideduenotices" => "", "tblclients.id" => array("sqltype" => "TABLEJOIN", "value" => "tblinvoices.userid")));

            while ($data = mysqli_fetch_array($result)) {
                $invoiceid = $data['id'];
                $userid = $data['userid'];
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];
                $result2 = full_query_i("SELECT COUNT(tblinvoiceitems.id) FROM tblinvoiceitems INNER JOIN tblcustomerservices ON tblcustomerservices.id=tblinvoiceitems.relid WHERE tblinvoiceitems.type = 'Service' AND tblcustomerservices.overideautosuspend = '1' AND tblcustomerservices.overidesuspenduntil>'" . date("Y-m-d") . "' AND tblcustomerservices.overidesuspenduntil!='0000-00-00' AND tblinvoiceitems.invoiceid = " . (int) $invoiceid);
                $data2 = mysqli_fetch_array($result2);
                $numoverideautosuspend = $data2[0];

                if ($numoverideautosuspend == "0") {
                    sendMessage($type . " Invoice Overdue Notice", $invoiceid);
                    run_hook("InvoicePaymentReminder", array("invoiceid" => $invoiceid, "type" => strtolower($type) . "overdue"));

                    if (is_object($cron)) {
                        $cron->emailLogSub("Sent " . $type . " Notice to User " . $firstname . " " . $lastname);
                    }

                    ++$count;
                }
            }

            continue;
        }
    }


    if (is_object($cron)) {
        $cron->logActivity("Sent " . $count . " Reminders", true);
        $cron->emailLog($count . " Overdue Invoice Reminders Sent");
    }
}

function getInvoiceProductPromo($amount, $promoid, $userid = "", $serviceid = "", $orderamt = "") {
    global $_LANG;
    global $currency;

    if (!$promoid) {
        return array();
    }

    $data = get_query_vals("tblpromotions", "", array("id" => $promoid));
    $promo_id = $data['id'];

    if (!$promo_id) {
        return array();
    }

    $promo_code = $data['code'];
    $promo_type = $data['type'];
    $promo_recurring = $data['recurring'];
    $promo_value = $data['value'];
    $promo_recurfor = $data['recurfor'];

    if ($userid) {
        $currency = getCurrency($userid);
    }


    if ($serviceid) {
        $data = get_query_vals("tblcustomerservices", "packageid,regdate,nextduedate,firstpaymentamount,billingcycle", array("id" => $serviceid));
        $pid = $data['packageid'];
        $regdate = $data['regdate'];
        $nextduedate = $data['nextduedate'];
        $firstpaymentamount = $data['firstpaymentamount'];
        $billingcycle = $data['billingcycle'];
        $billingcycle = str_replace("-", "", strtolower($billingcycle));

        if ($billingcycle == "one time") {
            $billingcycle = "monthly";
        }
    }


    if ($serviceid && $promo_recurfor) {
        $promo_recurringcount = get_query_val("tblinvoiceitems", "COUNT(id)", array("userid" => $userid, "type" => "Service", "relid" => $serviceid));

        if ($promo_recurfor - 1 < $promo_recurringcount) {
            $amount = getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate);

            if (!function_exists("getCartConfigOptions")) {
                require ROOTDIR . "/includes/configoptionsfunctions.php";
            }

            $configoptions = getCartConfigOptions($pid, "", $billingcycle, $serviceid);
            foreach ($configoptions as $configoption) {
                $amount += $configoption['selectedrecurring'];
            }

            update_query("tblcustomerservices", array("amount" => $amount, "promoid" => "0"), array("id" => $serviceid));
        }
    }


    if (!$promo_id) {
        return array();
    }


    if (!$serviceid || ($promo_recurring || (!$promo_recurring && $regdate == $nextduedate))) {
        if ($promo_type == "Percentage") {
            $promo_amount = round($amount / (1 - $promo_value / 100), 2) - $amount;

            if (0 < $promo_value && $promo_amount <= 0) {
                $promo_amount = ($orderamt ? $orderamt : getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate));
            }

            $promo_value .= "%";
        } else {
            if ($promo_type == "Fixed Amount") {
                if ($currency['id'] != 1) {
                    $promo_value = convertCurrency($promo_value, 1, $currency['id']);
                }

                $default_price = "";
                $default_price = getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate, $serviceid, $userid);

                if ($default_price < $promo_value) {
                    $promo_value = $default_price;
                }

                $default_price = "";
                $promo_amount = $promo_value;
                $promo_value = formatCurrency($promo_value);
            } else {
                if ($promo_type == "Price Override") {
                    if ($currency['id'] != 1) {
                        $promo_value = convertCurrency($promo_value, 1, $currency['id']);
                    }

                    $promo_amount = ($orderamt ? $orderamt : getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate));
                    $promo_amount -= $promo_value;
                    $promo_value = formatCurrency($promo_value) . " " . $_LANG['orderpromopriceoverride'];
                } else {
                    if ($promo_type == "Free Setup") {
                        $promo_amount = ($orderamt ? $orderamt : getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate));
                        $promo_amount -= $firstpaymentamount;
                        $promo_value = $_LANG['orderpromofreesetup'];
                    }
                }
            }
        }

        getUsersLang($userid);
        $promo_recurring = ($promo_recurring ? $_LANG['recurring'] : $_LANG['orderpaymenttermonetime']);
        $promo_description = $_LANG['orderpromotioncode'] . (": " . $promo_code . " - " . $promo_value . " " . $promo_recurring . " ") . $_LANG['orderdiscount'];
        getUsersLang(0);
        return array("description" => $promo_description, "amount" => $promo_amount * (0 - 1));
    }

    return array();
}

function getInvoiceProductDefaultPrice($pid, $billingcycle, $regdate, $nextduedate, $serviceid = "", $userid = "") {
    global $currency;

    $data = get_query_vals("tblpricing", "", array("type" => "product", "currency" => $currency['id'], "relid" => $pid));
    $amount = $data[$billingcycle];

    if ($regdate == $nextduedate) {
        $amount += $data[substr($billingcycle, 0, 1) . "setupfee"];
    }


    if ($serviceid) {
        if (!function_exists("recalcRecurringProductPrice")) {
            require ROOTDIR . "/includes/clientfunctions.php";
        }


        if ($regdate == $nextduedate) {
            $amount = recalcRecurringProductPrice($serviceid, $userid, $pid, ucfirst($billingcycle), "empty", 0, true);
        } else {
            $amount = recalcRecurringProductPrice($serviceid, $userid, $pid, ucfirst($billingcycle), "empty", 0);
        }
    }

    return $amount;
}

?>
