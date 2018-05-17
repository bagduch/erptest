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

?>