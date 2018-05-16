<?php

/** RA - Version 0.1 **/

require dirname(__FILE__) . "/../init.php";
include ROOTDIR . "/includes/clientfunctions.php";
include ROOTDIR . "/includes/modulefunctions.php";
include ROOTDIR . "/includes/gatewayfunctions.php";
include ROOTDIR . "/includes/ccfunctions.php";
include ROOTDIR . "/includes/processinvoices.php";
include ROOTDIR . "/includes/invoicefunctions.php";
include ROOTDIR . "/includes/backupfunctions.php";
include ROOTDIR . "/includes/ticketfunctions.php";
include ROOTDIR . "/includes/currencyfunctions.php";
$cron = RA_Cron::init();
$cron->raiseLimits();
releaseSession();

$escalations = (((is_array($_SERVER['argv']) && in_array("escalations", $_SERVER['argv'])) || isset($_GET['escalations'])) ? true : false);

if ($escalations) {
    include ROOTDIR . "/includes/adminfunctions.php";
    $lastruntime = $CONFIG['TicketEscalationLastRun'];
    $result = select_query_i("tblticketescalations", "", "");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $name = $data['name'];
        $departments = $data['departments'];
        $statuses = $data['statuses'];
        $priorities = $data['priorities'];
        $timeelapsed = $data['timeelapsed'];
        $newdepartment = $data['newdepartment'];
        $newpriority = $data['newpriority'];
        $newstatus = $data['newstatus'];
        $flagto = $data['flagto'];
        $notify = $data['notify'];
        $addreply = $data['addreply'];
        $ticketsqry = "SELECT * FROM ra_ticket WHERE ";

        if ($departments) {
            $departments = explode(",", $departments);
            $ticketsqry .= "did IN (" . db_build_in_array($departments) . ") AND ";
        }


        if ($statuses) {
            $statuses = explode(",", $statuses);
            $ticketsqry .= "status IN (" . db_build_in_array($statuses) . ") AND ";
        }


        if ($priorities) {
            $priorities = explode(",", $priorities);
            $ticketsqry .= "urgency IN (" . db_build_in_array($priorities) . ") AND ";
        }


        if ($timeelapsed) {
            $tickettime = date("Y-m-d H:i:s", mktime(date("H"), date("i") - $timeelapsed, date("s"), date("m"), date("d"), date("Y")));
            $ticketlasttime = date("Y-m-d H:i:s", strtotime("" . $lastruntime . " - " . $timeelapsed . " minutes"));
            $ticketsqry .= "lastreply>'" . $ticketlasttime . "' AND lastreply<='" . $tickettime . "' AND ";
        }

        $ticketsqry = substr($ticketsqry, 0, 0 - 5);
        $result2 = full_query_i($ticketsqry);

        while ($data = mysqli_fetch_array($result2)) {
            $ticketid = $data['id'];
            $tickettid = $data['tid'];
            $ticketsubject = $data['title'];
            $ticketuserid = $data['userid'];
            $ticketfromname = $data['name'];
            $ticketdeptid = $data['did'];
            $ticketpriority = $data['urgency'];
            $ticketstatus = $data['status'];
            $ticketmsg = $data['message'];
            $updateqry = array();

            if ($newdepartment) {
                $updateqry['did'] = $newdepartment;
            }


            if ($newpriority) {
                $updateqry['urgency'] = $newpriority;
            }


            if ($newstatus) {
                $updateqry['status'] = $newstatus;
            }


            if ($flagto) {
                $updateqry['flag'] = $flagto;
                sendAdminMessage("Support Ticket Flagged", array("ticket_id" => $ticketid, "ticket_tid" => $tickettid, "client_id" => $ticketuserid, "client_name" => get_query_val("ra_user", "CONCAT(firstname,' ',lastname)", array("id" => $ticketuserid)), "ticket_department" => getDepartmentName(($newdepartment ? $newdepartment : $ticketdeptid)), "ticket_subject" => $ticketsubject, "ticket_priority" => ($newpriority ? $newpriority : $ticketpriority), "ticket_message" => ticketMessageFormat($ticketmsg)), "support", ($newdepartment ? $newdepartment : $ticketdeptid), $flagto);
            }


            if (count($updateqry)) {
                update_query("ra_ticket", $updateqry, array("id" => $ticketid));
            }


            if ($notify) {
                $notify = explode(",", $notify);

                if (in_array("all", $notify)) {
                    sendAdminMessage("Escalation Rule Notification", array("rule_name" => $name, "ticket_id" => $ticketid, "ticket_tid" => $tickettid, "client_id" => $ticketuserid, "client_name" => get_query_val("ra_user", "CONCAT(firstname,' ',lastname)", array("id" => $ticketuserid)), "ticket_department" => getDepartmentName(($newdepartment ? $newdepartment : $ticketdeptid)), "ticket_subject" => $ticketsubject, "ticket_priority" => ($newpriority ? $newpriority : $ticketpriority), "ticket_message" => ticketMessageFormat($ticketmsg)), "support", ($newdepartment ? $newdepartment : $ticketdeptid));
                }

                foreach ($notify as $notifyid) {

                    if (is_numeric($notifyid)) {
                        sendAdminMessage("Escalation Rule Notification", array("rule_name" => $name, "ticket_id" => $ticketid, "ticket_tid" => $tickettid, "client_id" => $ticketuserid, "client_name" => get_query_val("ra_user", "CONCAT(firstname,' ',lastname)", array("id" => $ticketuserid)), "ticket_department" => getDepartmentName(($newdepartment ? $newdepartment : $ticketdeptid)), "ticket_subject" => $ticketsubject, "ticket_priority" => ($newpriority ? $newpriority : $ticketpriority), "ticket_message" => ticketMessageFormat($ticketmsg), "ticket_status" => $ticketstatus), "support", "", $notifyid);
                        continue;
                    }
                }
            }


            if ($addreply) {
                if (!$newstatus) {
                    $newstatus = $ticketstatus;
                }

                AddReply($ticketid, "", "", $addreply, "System", "", "", $newstatus, "", true);
            }
        }
    }

    update_query("ra_config", array("value" => date("Y-m-d H:i:s")), array("setting" => "TicketEscalationLastRun"));
    exit();
}

//$cron->logactivity("Starting");
full_query_i("DELETE FROM ra_bills WHERE userid NOT IN (SELECT id FROM ra_user)");
full_query_i("UPDATE ra_ticket SET did=(SELECT id FROM ra_ticket_teams ORDER BY `order` ASC LIMIT 1) WHERE did NOT IN (SELECT id FROM ra_ticket_teams)");
update_query("ra_user", array("currency" => "1"), array("currency" => "0"));
update_query("ra_transactions", array("currency" => "1"), array("currency" => "0", "userid" => "0"));

if ($ra->get_config("CurrencyAutoUpdateExchangeRates") && $cron->isScheduled("updaterates")) {
    currencyUpdateRates();
    $cron->logActivity("Done", true);
}


if ($ra->get_config("CurrencyAutoUpdateProductPrices") && $cron->isScheduled("updatepricing")) {
    currencyUpdatePricing();
    $cron->logActivity("Done", true);
}


if ($cron->isScheduled("invoices")) {
    createInvoices();
    
   $cron->logActivity("Invoices Create Done", true); 
}


if ($cron->isScheduled("latefees")) {
    InvoicesAddLateFee();
}


if ($cron->isScheduled("ccprocessing")) {
    ccProcessing();
}


if ($cron->isScheduled("invoicereminders")) {
    $data = invoicereminders($CONFIG);
    if (!empty($data)) {
        $cron->logActivity("Sent " . count($data['id']) . " Unpaid Invoice Payment Reminders" . $data['number'], true);
        $cron->emailLog(count($data['id']) . " Unpaid Invoice Payment Reminders Sent" . $data['number']);
    }
}

if ($CONFIG['AutoCancellationRequests'] && $cron->isScheduled("cancelrequests")) {
    $i = 0;
    $terminatedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    $query = "SELECT * FROM ra_cancellations INNER JOIN tblcustomerservices ON tblcustomerservices.id = ra_cancellations.relid WHERE (servicestatus!='Terminated' AND servicestatus!='Cancelled') AND (type='Immediate' OR (type='End of Billing Period' AND nextduedate<='" . $terminatedate . "\')) AND (tblcustomerservices.billingcycle='Free' OR tblcustomerservices.billingcycle='Free Account' OR tblcustomerservices.nextduedate != '0000-00-00') ORDER BY domain ASC";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $userid = $data['userid'];
        $description = $data['description'];
        $nextduedate = $data['nextduedate'];
        $packageid = $data['packageid'];
        $regdate = fromMySQLDate($regdate);
        $nextduedate = fromMySQLDate($nextduedate);
        $result2 = select_query_i("ra_user", "firstname,lastname", array("id" => $userid));
        $data2 = mysqli_fetch_array($result2);
        $firstname = $data2['firstname'];
        $lastname = $data2['lastname'];
        $result2 = select_query_i("ra_catalog", "name,servertype", array("id" => $packageid));
        $data2 = mysqli_fetch_array($result2);
        $prodname = $data2['name'];
        $module = $data2['servertype'];
        $serverresult = "No Module";
        logActivity("Cron Job: Processing Cancellation Request - Service ID: " . $id);

        if ($module) {
            $serverresult = ServerTerminateAccount($id);
        }


        if ($description) {
            $description = " - " . $description;
        }

        $loginfo = $firstname . " " . $lastname . " - " . $prodname . $description . " (Due Date: " . $nextduedate . ")";

        if ($serverresult == "success") {
            update_query("tblcustomerservices", array("servicestatus" => "Cancelled"), array("id" => $id));
            update_query("ra_catalog_user_sales_addons", array("status" => "Cancelled"), array("hostingid" => $id));
            run_hook("AddonCancelled", array("id" => "all", "userid" => $userid, "serviceid" => $id, "addonid" => ""));
            $cron->emailLogSub("SUCCESS: " . $loginfo, true);
            ++$i;
        }

        $cron->emailLogSub("ERROR: Manual Cancellation Required - " . $serverresult . " - " . $loginfo, true);
    }

    $cron->logActivity("Processed " . $i . " Cancellations", true);
    $cron->emailLog($i . " Cancellation Requests Processed");
}

if ($CONFIG['AutoSuspension'] && $cron->isScheduled("suspensions")) {
    update_query("tblcustomerservices", array("overideautosuspend" => ""), "(overideautosuspend='on' OR overideautosuspend='1') AND overidesuspenduntil<'" . date("Y-m-d") . "' AND overidesuspenduntil!='0000-00-00'");
    $i = 0;
    $suspenddate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $CONFIG['AutoSuspensionDays'], date("Y")));
    $query3 = "SELECT * FROM tblcustomerservices WHERE servicestatus='Active' AND billingcycle!='Free Account' AND billingcycle!='Free' AND billingcycle!='One Time' AND overideautosuspend!='on' AND overideautosuspend!='1' AND nextduedate<='" . $suspenddate . "' ORDER BY description ASC";
    $result3 = full_query_i($query3);

    while ($data = mysqli_fetch_array($result3)) {
        $id = $data['id'];
        $userid = $data['userid'];
        $domain = $data['description'];
        $packageid = $data['packageid'];
        $result2 = select_query_i("ra_user", "", array("id" => $userid));
        $data2 = mysqli_fetch_array($result2);
        $firstname = $data2['firstname'];
        $lastname = $data2['lastname'];
        $groupid = $data2['groupid'];
        $result2 = select_query_i("ra_catalog", "name,servertype", array("id" => $packageid));
        $data2 = mysqli_fetch_array($result2);
        $prodname = $data2['name'];
        $module = $data2['servertype'];
        $result2 = select_query_i("ra_user_group", "susptermexempt", array("id" => $groupid));
        $data2 = mysqli_fetch_array($result2);
        $susptermexempt = $data2['susptermexempt'];

        if (!$susptermexempt) {
            $serverresult = "No Module";
            logActivity("Cron Job: Suspending Service - Service ID: " . $id);

            if ($module) {
                $serverresult = ServerSuspendAccount($id);
            }

            if ($domain) {
                $domain = " - " . $domain;
            }

            $loginfo = $firstname . " " . $lastname . " - " . $prodname . $domain . " (Service ID: " . $id . " - User ID: " . $userid . ")";

            if ($serverresult == "success") {
                sendMessage("Service Suspension Notification", $id);
                $cron->emailLogSub("SUCCESS: " . $loginfo, true);
                ++$i;
            }

            $cron->emailLogSub("ERROR: Manual Suspension Required - " . $serverresult . " - " . $loginfo, true);
        }
    }

    $query3 = "SELECT ra_catalog_user_sales_addons.*,tblcustomerservices.userid,tblcustomerservices.packageid,tblcustomerservices.domain,ra_user.firstname,ra_user.lastname,ra_user.groupid FROM ra_catalog_user_sales_addons INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE ra_catalog_user_sales_addons.status='Active' AND ra_catalog_user_sales_addons.billingcycle!='Free' AND ra_catalog_user_sales_addons.billingcycle!='Free Account' AND ra_catalog_user_sales_addons.billingcycle!='One Time' AND ra_catalog_user_sales_addons.nextduedate<='" . $suspenddate . "' AND tblcustomerservices.overideautosuspend!='on' AND tblcustomerservices.overideautosuspend!='1' ORDER BY ra_catalog_user_sales_addons.name ASC";
    $result3 = full_query_i($query3);

    while ($data = mysqli_fetch_array($result3)) {
        $id = $data['id'];
        $serviceid = $data['hostingid'];
        $addonid = $data['addonid'];
        $name = $data['name'];
        $nextduedate = $data['nextduedate'];
        $userid = $data['userid'];
        $packageid = $data['packageid'];
        $domain = $data['domain'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $groupid = $data['groupid'];
        $result2 = select_query_i("ra_user_group", "susptermexempt", array("id" => $groupid));
        $data2 = mysqli_fetch_array($result2);
        $susptermexempt = $data2['susptermexempt'];

        if (!$susptermexempt) {
            update_query("ra_catalog_user_sales_addons", array("status" => "Suspended"), array("id" => $id));
            $loginfo = $firstname . " " . $lastname . " - " . $name . " (Service ID: " . $serviceid . " - Addon ID: " . $id . ")";
            $cron->logActivity("SUCCESS: " . $loginfo, true);
            run_hook("AddonSuspended", array("id" => $id, "userid" => $userid, "serviceid" => $serviceid, "addonid" => $addonid));

            if ($addonid) {
                $result2 = select_query_i("tbladdons", "suspendproduct", array("id" => $addonid));
                $data2 = mysqli_fetch_array($result2);
                $suspendproduct = $data2[0];

                if ($suspendproduct) {
                    $result2 = select_query_i("ra_catalog", "name,servertype", array("id" => $packageid));
                    $data2 = mysqli_fetch_array($result2);
                    $prodname = $data2['name'];
                    $module = $data2['servertype'];
                    $serverresult = "No Module";
                    logActivity("Cron Job: Suspending Parent Service - Service ID: " . $serviceid);

                    if ($module) {
                        $serverresult = ServerSuspendAccount($serviceid, "Parent Service Suspended due to Overdue Addon");
                    }


                    if ($domain) {
                        $domain = " - " . $domain;
                    }

                    $loginfo = $firstname . " " . $lastname . " - " . $prodname . $domain . " (Service ID: " . $serviceid . " - User ID: " . $userid . ")";

                    if ($serverresult == "success") {
                        sendMessage("Service Suspension Notification", $serviceid);
                        $cron->emailLogSub("SUCCESS: " . $loginfo, true);
                    } else {
                        $cron->emailLogSub("ERROR: Manual Parent Service Suspension Required - " . $serverresult . " - " . $loginfo, true);
                    }
                }
            }
            ++$i;
        }
    }

    $cron->logActivity("Processed " . $i . " Suspensions", true);
    $cron->emailLog($i . " Services Suspended");
}

if ($CONFIG['AutoTermination'] && $cron->isScheduled("terminations")) {
    $i = 0;
    $terminatedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $CONFIG['AutoTerminationDays'], date("Y")));
    $query = "SELECT * FROM tblcustomerservices WHERE (servicestatus='Active' OR servicestatus='Suspended') AND billingcycle!='Free Account' AND billingcycle!='One Time' AND nextduedate<='" . $terminatedate . "' AND tblcustomerservices.nextduedate != '0000-00-00' AND overideautosuspend!='on' AND overideautosuspend!='1' ORDER BY domain ASC";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $serviceid = $data['id'];
        $userid = $data['userid'];
        $domain = $data['domain'];
        $packageid = $data['packageid'];
        $result2 = select_query_i("ra_user", "", array("id" => $userid));
        $data2 = mysqli_fetch_array($result2);
        $firstname = $data2['firstname'];
        $lastname = $data2['lastname'];
        $groupid = $data2['groupid'];
        $result2 = select_query_i("ra_catalog", "name,servertype", array("id" => $packageid));
        $data2 = mysqli_fetch_array($result2);
        $prodname = $data2['name'];
        $module = $data2['servertype'];
        $result2 = select_query_i("ra_user_group", "susptermexempt", array("id" => $groupid));
        $data2 = mysqli_fetch_array($result2);
        $susptermexempt = $data2['susptermexempt'];
        if (!$susptermexempt) {
            $serverresult = "No Module";
            logActivity("Cron Job: Terminating Service - Service ID: " . $serviceid);

            if ($module) {
                $serverresult = ServerTerminateAccount($serviceid);
            }

            if ($domain) {
                $domain = " - " . $domain;
            }
            $loginfo = $firstname . " " . $lastname . " - " . $prodname . $domain . " (Service ID: " . $serviceid . " - User ID: " . $userid . ")";

            if ($serverresult == "success") {
                $cron->emailLogSub("SUCCESS: " . $loginfo, true);
                ++$i;
            }

            $cron->emailLogSub("ERROR: Manual Terminate Required - " . $serverresult . " - " . $loginfo, true);
        }
    }

    $query = "UPDATE ra_catalog_user_sales_addons SET status='Terminated' WHERE (status='Active' OR status='Suspended') AND billingcycle!='Free Account' AND billingcycle!='Free' AND billingcycle!='One Time' AND nextduedate!='0000-00-00' AND nextduedate<='" . $terminatedate . "'";
    $result = full_query_i($query);
    $cron->logActivity("Processed " . $i . " Terminations", true);
    $cron->emailLog($i . " Services Terminated");
}


if ($cron->isScheduled("fixedtermterminations")) {
    $count = 0;
    $result = select_query_i("ra_catalog", "id,autoterminatedays,autoterminateemail,servertype,name", "autoterminatedays>0", "id", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $pid = $data[0];
        $autoterminatedays = $data[1];
        $autoterminateemail = $data[2];
        $module = $data[3];
        $prodname = $data[4];

        if ($autoterminateemail) {
            $result2 = select_query_i("ra_templates_mail", "name", array("id" => $autoterminateemail));
            $data = mysqli_fetch_array($result2);
            $emailtplname = $data[0];
        }

        $terminatebefore = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $autoterminatedays, date("Y")));
        $result2 = select_query_i("tblcustomerservices", "tblcustomerservices.id,userid,domain,firstname,lastname", "packageid=" . $pid . " AND regdate<='" . $terminatebefore . "' AND (servicestatus='Active' OR servicestatus='Suspended')", "id", "ASC", "", "ra_user ON ra_user.id=tblcustomerservices.userid");

        while ($data = mysqli_fetch_array($result2)) {
            $serviceid = $data[0];
            $userid = $data[1];
            $domain = $data[2];
            $firstname = $data[3];
            $lastname = $data[4];
            $moduleresult = "No Module";
            logActivity("Cron Job: Auto Terminating Fixed Term Service - Service ID: " . $serviceid);

            if ($module) {
                $moduleresult = ServerTerminateAccount($serviceid);
            }


            if ($domain) {
                $domain = " - " . $domain;
            }

            $loginfo = $firstname . " " . $lastname . " - " . $prodname . $domain . " (Service ID: " . $serviceid . " - User ID: " . $userid . ")";

            if ($moduleresult == "success") {
                if ($autoterminateemail) {
                    sendMessage($emailtplname, $serviceid);
                }

                $cron->logActivity("SUCCESS: " . $loginfo, true);
                ++$count;
            }

            $cron->logActivity("ERROR: Manual Terminate Required - " . $moduleresult . " - " . $loginfo, true);
        }
    }

    $cron->logActivity("Processed " . $count . " Terminations", true);
    $cron->emailLog($count . " Fixed Term Terminations Processed");
}


if ($cron->isScheduled("closetickets")) {
    closeInactiveTickets();
}


if ($CONFIG['AffiliatesDelayCommission'] && $cron->isScheduled("affcommissions")) {
    $affiliatepaymentscleared = 0;
    $query = "SELECT * FROM ra_partnerspending WHERE clearingdate<='" . date("Y-m-d") . "'";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $affaccid = $data['affaccid'];
        $amount = $data['amount'];
        $query2 = "SELECT * FROM ra_partnersaccounts WHERE id=" . (int) $affaccid;
        $result2 = full_query_i($query2);
        $data = mysqli_fetch_array($result2);
        $affaccid = $data['id'];
        $relid = $data['relid'];
        $affid = $data['affiliateid'];
        $query2 = "SELECT servicestatus FROM tblcustomerservices WHERE id=" . (int) $relid;
        $result2 = full_query_i($query2);
        $data = mysqli_fetch_array($result2);
        $servicestatus = $data['servicestatus'];

        if ($affaccid && $servicestatus == "Active") {
            update_query("ra_partners", array("balance" => "+=" . $amount), array("id" => (int) $affid));
            update_query("ra_partnersaccounts", array("lastpaid" => "now()"), array("id" => (int) $affaccid));
            insert_query("ra_partnershistory", array("affiliateid" => $affid, "date" => "now()", "affaccid" => $affaccid, "amount" => $amount));
            ++$affiliatepaymentscleared;
        }
    }

    $cron->logActivity("Processed " . $affiliatepaymentscleared . " Pending Affiliate Payments", true);
    $cron->emailLog($affiliatepaymentscleared . " Pending Affiliate Payments Cleared");
    $query = "DELETE FROM ra_partnerspending WHERE clearingdate<='" . date("Y-m-d") . "'";
    $result = full_query_i($query);
}


if (($CONFIG['SendAffiliateReportMonthly'] && date("d") == "1") && $cron->isScheduled("affreports")) {
    $query = "SELECT aff.* FROM ra_partners aff join ra_user client on aff.clientid = client.id where client.status = 'Active'";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        sendMessage("Affiliate Monthly Referrals Report", $id);
    }

    $cron->logActivity("Sent Successfully", true);
    $cron->emailLog("Monthly Affiliate Reports Sent");
}


if ($cron->isScheduled("emailmarketing")) {
    $emails = false;
    $result = select_query_i("tblemailmarketer", "", array("disable" => "0"), "id", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $name = $data['name'];
        $type = $data['type'];
        $settings = $data['settings'];
        $marketing = $data['marketing'];
        $settings = unserialize($settings);
        $clientnumdays = $settings['clientnumdays'];
        $clientsminactive = $settings['clientsminactive'];
        $clientsmaxactive = $settings['clientsmaxactive'];
        $clientemailtpl = $settings['clientemailtpl'];
        $prodpids = $settings['prodpids'];
        $prodstatus = $settings['prodstatus'];
        $prodnumdays = $settings['prodnumdays'];
        $prodfiltertype = $settings['prodfiltertype'];
        $prodexcludepid = $settings['prodexcludepid'];
        $prodexcludeaid = $settings['prodexcludeaid'];
        $prodemailtpl = $settings['prodemailtpl'];
        $query = $query1 = "";
        $criteria = array();

        if ($type == "client") {
            $emailtplid = $clientemailtpl;
            $query = "SELECT id FROM ra_user WHERE ";

            if (0 < $clientnumdays) {
                $criteria[] = "datecreated='" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $clientnumdays, date("Y"))) . "'";
            }


            if (strlen($clientsminactive)) {
                $criteria[] = "(SELECT COUNT(*) FROM tblcustomerservices WHERE tblcustomerservices.userid=ra_user.id AND tblcustomerservices.servicestatus='Active')>=" . (int) $clientsminactive;
            }


            if (strlen($clientsmaxactive)) {
                $criteria[] = "(SELECT COUNT(*) FROM tblcustomerservices WHERE tblcustomerservices.userid=ra_user.id AND tblcustomerservices.servicestatus='Active')<=" . (int) $clientsmaxactive;
            }


            if ($marketing) {
                $criteria[] = "emailoptout = '0'";
            }

            $query .= implode(" AND ", $criteria);
        } else {
            if ($type == "product") {
                $emailtplid = $prodemailtpl;
                $filterpids = $filteraids = array();
                foreach ($prodpids as $pid) {

                    if (substr($pid, 0, 1) == "P") {
                        $filterpids[] = (int) substr($pid, 1);
                        continue;
                    }


                    if (substr($pid, 0, 1) == "A") {
                        $filteraids[] = (int) substr($pid, 1);
                        continue;
                    }
                }


                if (count($filterpids)) {
                    $query = "SELECT id FROM tblcustomerservices WHERE ";
                    $criteria[] = "packageid IN (" . db_build_in_array($filterpids) . ")";

                    if (0 < $prodnumdays) {
                        if ($prodfiltertype == "afterorder") {
                            $criteria[] = "regdate='" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $prodnumdays, date("Y"))) . "'";
                        } else {
                            if ($prodfiltertype == "beforedue") {
                                $criteria[] = "nextduedate='" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + $prodnumdays, date("Y"))) . "'";
                            }
                        }
                    }


                    if (count($prodstatus)) {
                        $criteria[] = "servicestatus IN (" . db_build_in_array($prodstatus) . ")";
                    }


                    if (count($prodexcludepid)) {
                        if (implode($prodexcludepid)) {
                            $criteria[] = "(SELECT COUNT(*) FROM tblcustomerservices h2 WHERE h2.userid=tblcustomerservices.userid AND h2.packageid IN (" . db_build_in_array($prodexcludepid) . ") AND h2.servicestatus='Active')=0";
                        }
                    }


                    if (count($prodexcludeaid)) {
                        if (implode($prodexcludeaid)) {
                            $criteria[] = "(SELECT COUNT(*) FROM ra_catalog_user_sales_addons WHERE ra_catalog_user_sales_addons.hostingid=tblcustomerservices.id AND ra_catalog_user_sales_addons.addonid IN (" . db_build_in_array($prodexcludeaid) . ") AND ra_catalog_user_sales_addons.status='Active')=0";
                        }
                    }


                    if ($marketing) {
                        $criteria[] = "(SELECT COUNT(*) FROM ra_user h3 WHERE h3.id=tblcustomerservices.userid AND h3.emailoptout = '0')=1";
                    }

                    $query .= implode(" AND ", $criteria);
                }


                if (count($filteraids)) {
                    $criteria = array();
                    $query1 = "SELECT hostingid FROM ra_catalog_user_sales_addons WHERE ";
                    $criteria[] = "addonid IN (" . db_build_in_array($filteraids) . ")";

                    if (0 < $prodnumdays) {
                        if ($prodfiltertype == "afterorder") {
                            $criteria[] = "regdate='" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $prodnumdays, date("Y"))) . "'";
                        } else {
                            if ($prodfiltertype == "beforedue") {
                                $criteria[] = "nextduedate='" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + $prodnumdays, date("Y"))) . "'";
                            }
                        }
                    }


                    if (count($prodstatus)) {
                        $criteria[] = "status IN (" . db_build_in_array($prodstatus) . ")";
                    }


                    if (count($prodexcludepid)) {
                        if (implode($prodexcludepid)) {
                            $criteria[] = "(SELECT COUNT(*) FROM tblcustomerservices h2 WHERE h2.userid=(SELECT userid FROM tblcustomerservices WHERE tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid) AND h2.packageid IN (" . db_build_in_array($prodexcludepid) . ") AND h2.servicestatus='Active')=0";
                        }
                    }


                    if (count($prodexcludeaid)) {
                        if (implode($prodexcludeaid)) {
                            $criteria[] = "(SELECT COUNT(*) FROM ra_catalog_user_sales_addons h2 WHERE h2.hostingid=ra_catalog_user_sales_addons.hostingid AND ra_catalog_user_sales_addons.addonid IN (" . db_build_in_array($prodexcludeaid) . ") AND ra_catalog_user_sales_addons.status='Active')=0";
                        }
                    }


                    if ($marketing) {
                        $criteria[] = "(SELECT COUNT(*) FROM ra_user h3 WHERE h3.id=(SELECT userid FROM tblcustomerservices WHERE tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid) AND h3.emailoptout = '0')=1";
                    }

                    $query1 .= implode(" AND ", $criteria);
                }
            }
        }

        $result2 = select_query_i("ra_templates_mail", "name", array("id" => $emailtplid));
        $data = mysqli_fetch_array($result2);
        $emailtplname = $data[0];
        $count = 0;

        if ($query) {
            $result2 = full_query_i($query);

            while ($data = mysqli_fetch_array($result2)) {
                $id = $data[0];
                sendMessage($emailtplname, $id);
                ++$count;
            }
        }


        if ($query1) {
            $result2 = full_query_i($query1);

            while ($data = mysqli_fetch_array($result2)) {
                $id = $data[0];
                sendMessage($emailtplname, $id);
                ++$count;
            }
        }

        $cron->logActivity("Email Rule \"" . $name . "\" sent to " . $count . " Users", true);
        $cron->emailLogSub("Email Rule \"" . $name . "\" sent to " . $count . " Users");
        $emails = true;
    }

    $cron->emailLog("Processed Email Marketer Rules");
}


if (date("d") == $CONFIG['CCDaySendExpiryNotices'] && $cron->isScheduled("ccexpirynotices")) {
    $expiryemailcount = 0;
    $expirymonth = date("my", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    $result = select_query_i("ra_user", "id", "cardtype!='' AND status='Active'");

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['id'];
        $cchash = md5($cc_encryption_hash . $userid);
        $result2 = select_query_i("ra_user", "id", "id='" . $userid . "' AND AES_DECRYPT(expdate,'" . $cchash . "')='" . $expirymonth . "'");
        $data = mysqli_fetch_array($result2);
        $userid = $data['id'];

        if ($userid) {
            sendMessage("Credit Card Expiring Soon", $userid);

            if (!$CONFIG['CCDoNotRemoveOnExpiry']) {
                update_query("ra_user", array("cardtype" => "", "cardlastfour" => "", "cardnum" => "", "expdate" => "", "issuenumber" => "", "startdate" => ""), array("id" => $userid));
            }

            ++$expiryemailcount;
        }
    }

    $cron->logActivity("Sent " . $expiryemailcount . " Credit Card Expiry Notices", true);
    $cron->emailLog($expiryemailcount . " Credit Card Expiry Notices Sent");
}


if ($CONFIG['UpdateStatsAuto'] && $cron->isScheduled("usagestats")) {
    ServerUsageUpdate();
    $cron->logActivity("Done", true);
    $cron->emailLog("Updated Disk & Bandwidth Usage Stats");
}

$overagesbillingdate = date("Ymd", mktime(0, 0, 0, date("m") + 1, 0, date("Y")));

if ($overagesbillingdate == date("Ymd") && $cron->isScheduled("overagesbilling")) {
    if (!function_exists("ModuleBuildParams")) {
        require ROOTDIR . "/includes/modulefunctions.php";
    }

    $invoiceaction = $CONFIG['OverageBillingMethod'];

    if (!$invoiceaction) {
        $invoiceaction = "1";
    }

    $result = select_query_i("ra_catalog", "id,name,overagesenabled,overagesdisklimit,overagesbwlimit,overagesdiskprice,overagesbwprice", array("overagesenabled" => array("sqltype" => "NEQ", "value" => "")));

    while ($data = mysqli_fetch_array($result)) {
        $pid = $data['id'];
        $prodname = $data['name'];
        $overagesenabled = $data['overagesenabled'];
        $overagesdisklimit = $data['overagesdisklimit'];
        $overagesbwlimit = $data['overagesbwlimit'];
        $overagesbasediskprice = $data['overagesdiskprice'];
        $overagesbasebwprice = $data['overagesbwprice'];
        $overagesenabled = explode(",", $overagesenabled);
        $result2 = select_query_i("tblcustomerservices", "tblcustomerservices.*,ra_user.currency", "packageid=" . $pid . " AND (servicestatus='Active' OR servicestatus='Suspended')", "", "", "", "ra_user ON ra_user.id=tblcustomerservices.userid");

        while ($data = mysqli_fetch_array($result2)) {
            $serviceid = $data['id'];
            $userid = $data['userid'];
            $currency = $data['currency'];
            $domain = $data['domain'];
            $diskusage = $data['diskusage'];
            $disklimit = $data['disklimit'];
            $bwusage = $data['bwusage'];
            $bwlimit = $data['bwlimit'];
            $result3 = select_query_i_i("ra_currency", "rate", array("id" => $currency));
            $data = mysqli_fetch_array($result3);
            $convertrate = $data['rate'];

            if (!$convertrate) {
                $convertrate = 1;
            }

            $overagesdiskprice = $overagesbasediskprice * $convertrate;
            $overagesbwprice = $overagesbasebwprice * $convertrate;
            $moduleparams = ModuleBuildParams($serviceid);
            $thisoveragesdisklimit = $overagesdisklimit;
            $thisoveragesbwlimit = $overagesbwlimit;

            if ($moduleparams['customfields']["Disk Space"]) {
                $thisoveragesdisklimit = $moduleparams['customfields']["Disk Space"];
            }


            if ($moduleparams['customfields']['Bandwidth']) {
                $thisoveragesbwlimit = $moduleparams['customfields']['Bandwidth'];
            }


            if ($moduleparams['configoptions']["Disk Space"]) {
                $thisoveragesdisklimit = $moduleparams['configoptions']["Disk Space"];
            }


            if ($moduleparams['configoptions']['Bandwidth']) {
                $thisoveragesbwlimit = $moduleparams['configoptions']['Bandwidth'];
            }

            $diskunits = "MB";

            if ($overagesenabled[1] == "GB") {
                $diskunits = "GB";
                $diskusage = $diskusage / 1024;
            } else {
                if ($overagesenabled[1] == "TB") {
                    $diskunits = "TB";
                    $diskusage = $diskusage / (1024 * 1024);
                }
            }

            $bwunits = "MB";

            if ($overagesenabled[2] == "GB") {
                $bwunits = "GB";
                $bwusage = $bwusage / 1024;
            } else {
                if ($overagesenabled[2] == "TB") {
                    $bwunits = "TB";
                    $bwusage = $bwusage / (1024 * 1024);
                }
            }

            $diskoverage = $diskusage - $thisoveragesdisklimit;
            $bwoverage = $bwusage - $thisoveragesbwlimit;
            $overagedesc = $prodname;

            if ($domain) {
                $overagedesc .= " - " . $domain;
            }

            $overagesfrom = fromMySQLDate(date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))));
            $overagesto = getTodaysDate();
            $overagedesc .= " (" . $overagesfrom . " - " . $overagesto . ")";
            getUsersLang($userid);

            if ($thisoveragesdisklimit && 0 < $diskoverage) {
                if ($diskoverage < 0) {
                    $diskoverage = 0;
                }

                $diskoverage = round($diskoverage, 2);
                $diskoveragedesc = $overagedesc . "\r\n" . $_LANG['overagestotaldiskusage'] . (" = " . $diskusage . " ") . $diskunits . " - " . $_LANG['overagescharges'] . (" = " . $diskoverage . " ") . $diskunits . (" @ " . $overagesdiskprice . "/") . $diskunits;
                $diskoverageamount = $diskoverage * $overagesdiskprice;
                insert_query("tblbillableitems", array("userid" => $userid, "description" => $diskoveragedesc, "amount" => $diskoverageamount, "recur" => 0, "recurcycle" => 0, "recurfor" => 0, "invoiceaction" => $invoiceaction, "duedate" => date("Y-m-d")));
            }


            if ($thisoveragesbwlimit && 0 < $bwoverage) {
                if ($bwoverage < 0) {
                    $bwoverage = 0;
                }

                $bwoverage = round($bwoverage, 2);
                $bwoveragedesc = $overagedesc . "\r\n" . $_LANG['overagestotalbwusage'] . (" = " . $bwusage . " ") . $bwunits . " - " . $_LANG['overagescharges'] . (" = " . $bwoverage . " ") . $bwunits . (" @ " . $overagesbwprice . "/") . $bwunits;
                $bwoverageamount = $bwoverage * $overagesbwprice;
                insert_query("tblbillableitems", array("userid" => $userid, "description" => $bwoveragedesc, "amount" => $bwoverageamount, "recur" => 0, "recurcycle" => 0, "recurfor" => 0, "invoiceaction" => $invoiceaction, "duedate" => date("Y-m-d")));
            }
        }
    }

    $cron->emailLog("Calculated Disk & Bandwidth Overage Charges");
    createInvoices();
}


if ($CONFIG['AutoClientStatusChange'] != "1" && $cron->isScheduled("clientstatussync")) {
    $result = full_query_i("SELECT id,lastlogin FROM ra_user WHERE status='Active' AND overrideautoclose='0' AND (SELECT COUNT(id) FROM tblcustomerservices WHERE tblcustomerservices.userid=ra_user.id AND servicestatus IN ('Active','Suspended'))=0" . ($CONFIG['AutoClientStatusChange'] == "3" ? " AND lastlogin<='" . date("Ymd", mktime(0, 0, 0, date("m") - 3, date("d"), date("Y"))) . "'" : ""));

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['id'];
        $result2 = full_query_i("SELECT (SELECT COUNT(*) FROM tblcustomerservices WHERE userid=ra_user.id AND servicestatus IN ('Active','Suspended'))+(SELECT COUNT(*) FROM ra_catalog_user_sales_addons WHERE hostingid IN (SELECT id FROM tblcustomerservices WHERE userid=ra_user.id) AND status IN ('Active','Suspended'))+(SELECT COUNT(*) FROM tblcustomerservices WHERE userid=ra_user.id AND status IN ('Active')) AS activeservices FROM ra_user WHERE ra_user.id=" . (int) $userid . " LIMIT 1");
        $data = mysqli_fetch_array($result2);
        $totalactivecount = $data[0];

        if ($totalactivecount == 0) {
            update_query("ra_user", array("status" => "Inactive"), array("id" => $userid));
        }
    }

    $result = full_query_i("SELECT tblcustomerservices.userid FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE ra_user.status='Inactive' AND ra_user.overrideautoclose='0' AND tblcustomerservices.servicestatus IN ('Active','Suspended')");

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['userid'];
        update_query("ra_user", array("status" => "Active"), array("id" => $userid));
    }

    $result = full_query_i("SELECT tblcustomerservices.userid FROM ra_catalog_user_sales_addons INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE ra_user.status='Inactive' AND ra_user.overrideautoclose='0' AND ra_catalog_user_sales_addons.status IN ('Active','Suspended')");

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['userid'];
        update_query("ra_user", array("status" => "Active"), array("id" => $userid));
    }

    $result = full_query_i("SELECT tblcustomerservices.userid FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid WHERE ra_user.status='Inactive' AND ra_user.overrideautoclose='0' AND tblcustomerservices.status IN ('Active','Pending-Transfer')");

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['userid'];
        update_query("ra_user", array("status" => "Active"), array("id" => $userid));
    }

    $cron->logActivity("Done", true);
}
$query = "UPDATE tblcustomerservices SET status='Expired' WHERE expirydate<'" . date("Y-m-d") . "' AND expirydate!='00000000' AND status='Active'";
$result = full_query_i($query);
$cron->logActivity("Completed");
//$cron->emailReport();
run_hook("DailyCronJob", array());
$cron->logActivity("Cron Job Hooks Run...");
if ($cron->isScheduled("backups")) {
    $backupdata = $db_name = "";
    require ROOTDIR . "/configuration.php";

    if ($CONFIG['DailyEmailBackup'] || $CONFIG['FTPBackupHostname']) {
        $cron->logActivity("Starting Backup Generation");
        $backupdata = generateBackup();
        $cron->logActivity("Backup Generation Completed");
    }


    if ($CONFIG['DailyEmailBackup']) {
        $ra->load_class("phpmailer");
        $mail = new PHPMailer();
        $mail->From = $CONFIG['SystemEmailsFromEmail'];
        $mail->FromName = html_entity_decode($CONFIG['SystemEmailsFromName'], ENT_QUOTES);
        $mail->Subject = "ra Database Backup";

        if ($CONFIG['MailType'] == "mail") {
            $mail->Mailer = "mail";
        } else {
            if ($CONFIG['MailType'] == "smtp") {
                $mail->IsSMTP();
                $mail->Host = $CONFIG['SMTPHost'];
                $mail->Port = $CONFIG['SMTPPort'];
                $mail->Hostname = $_SERVER['SERVER_NAME'];

                if ($CONFIG['SMTPSSL']) {
                    $mail->SMTPSecure = $CONFIG['SMTPSSL'];
                }


                if ($CONFIG['SMTPUsername']) {
                    $mail->SMTPAuth = true;
                    $mail->Username = $CONFIG['SMTPUsername'];
                    $mail->Password = decrypt($CONFIG['SMTPPassword']);
                }

                $mail->Sender = $mail->From;
            }
        }


        if ($smtp_debug) {
            $mail->SMTPDebug = true;
        }

        $mail->Body = "Backup File Attached";
        $mail->AddAddress($CONFIG['DailyEmailBackup']);
        $mail->AddStringAttachment($backupdata, $db_name . "_backup_" . date("Ymd_His") . ".zip");

        if ($mail->Send()) {
            $cron->logActivity("Email Backup - Sent Successfully");
        } else {
            $cron->logActivity("Email Backup - Sending Failed - " . $mail->ErrorInfo);
        }

        $mail->ClearAddresses();
    }


    if ($CONFIG['FTPBackupHostname']) {
        $ftp_server = $CONFIG['FTPBackupHostname'];
        $ftp_port = $CONFIG['FTPBackupPort'];
        $ftp_user = $CONFIG['FTPBackupUsername'];
        $ftp_pass = decrypt($CONFIG['FTPBackupPassword']);
        $ftp_filename = $CONFIG['FTPBackupDestination'] . $db_name . "_backup_" . date("Ymd_His") . ".zip";

        if (!$ftp_port) {
            $ftp_port = "21";
        }

        $ftp_server = str_replace("ftp://", "", $ftp_server);
        ($ftpconnection = ftp_connect($ftp_server, $ftp_port) || $error = "Couldn't connect to " . $ftp_server);

        if (!ftp_login($ftpconnection, $ftp_user, $ftp_pass)) {
            $cron->logActivity("FTP Backup - Login Failed");
            exit();
        }


        if ($CONFIG['FTPPassiveMode']) {
            ftp_pasv($ftpconnection, true);
        }

        $tmp = tmpfile();
        fwrite($tmp, $backupdata);
        fseek($tmp, 0);
        $upload = ftp_fput($ftpconnection, $ftp_filename, $tmp, FTP_BINARY);

        if (!$upload) {
            $cron->logActivity("FTP Backup - Uploading Failed");
            exit();
        }

        fclose($tmp);
        ftp_close($ftpconnection);
        $cron->logActivity("FTP Backup - Completed Successfully");
    }

    $cron->logActivity("Backup Complete...");
}
$cron->logActivity("Goodbye");

?>
