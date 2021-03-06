<?php

define("ADMINAREA", true);
require "../init.php";
$action = $ra->get_req_var("action");
$deptid = $ra->get_req_var("deptid");
$icon = "tickets";
$departmentshtml = "";
$departments = array();
$result = select_query_i("ra_ticket_teams", "", "", "order", "ASC");
while ($data = mysqli_fetch_array($result)) {
    $departments[] = array("id" => $data['id'], "name" => $data['name']);
    $departmentshtml .= "<option value=\"" . $data['id'] . "\"" . ($data['id'] == $deptid ? " selected" : "") . ">" . $data['name'] . "</option>";
}

$supporttickets = new RA_Support($id, $action);

$aInt = $supporttickets->aInt;
$filt = $supporttickets->filt;
$smartyvalues = array();
if ($ra->get_req_var("ticketid")) {
    $action = "search";
}
if ($action == "gettags") {
    check_token("RA.admin.default");
    echo $supporttickets->gettages();
    exit();
} elseif ($action == "savetags") {
    check_token("RA.admin.default");
    $supporttickets->savetags();
    exit();
} elseif ($action == "deletetag") {
    check_token("RA.admin.default");
    echo $supporttickets->removetag();
    exit();
} elseif ($action == "checkstatus") {
    check_token("RA.admin.default");
    echo $supporttickets->checkstatus($ticketstatus);
    exit();
} elseif ($action == "split") {
    check_token("RA.admin.default");
    $sdata = array(
        "rids" => $rids,
        "splitsubject" => $splitsubject,
        "splitdeptid" => $splitdeptid,
        "splitpriority" => $splitpriority,
    );
    $ticketid = $supporttickets->splite($sdata);
    redir("action=viewticket&id=" . $ticketid);
    exit();
} elseif ($action == "getmsg") {
    check_token("RA.admin.default");
    $msg = $supporttickets->getmsg($ref);
    echo html_entity_decode($msg, ENT_QUOTES);
    exit();
} elseif ($action == "getticketlog") {
    check_token("RA.admin.default");
    echo $supporttickets->getticketlog($offset, $target);
    exit();
} elseif ($action == "getclientlog") {
    check_token("RA.admin.default");
    checkPermission("View Activity Log");
    echo $supporttickets->getclientlog($offset, $target);
    exit();
} elseif ($action == "gettickets") {
    check_token("RA.admin.default");
    echo $supporttickets->gettickets($offset, $target);
    exit();
} elseif ($action == "getallservices") {
    check_token("RA.admin.default");
    echo $supporttickets->getallservices();
    exit();
} elseif ($action == "updatereply") {
    $supporttickets->updatereply($ref, $text, $id);
    echo $text;
    exit();
} elseif ($action == "makingreply") {
    check_token("RA.admin.default");
    $access = validateAdminTicketAccess($id);

    if ($access) {
        exit();
    }

    $result = select_query_i("ra_ticket", "replyingadmin,replyingtime", array("id" => $id, "replyingadmin" => array("sqltype" => ">", "value" => "0")));

    if (mysqli_num_rows($result)) {
        $data = mysqli_fetch_assoc($result);
        $replyingadmin = $data['replyingadmin'];
        $replyingtime = $data['replyingtime'];
        $replyingtime = fromMySQLDate($replyingtime, "time");

        if ($replyingadmin != $_SESSION['adminid']) {
            $result = select_query_i("ra_admin", "", array("id" => $replyingadmin));
            $data = mysqli_fetch_array($result);
            $replyingadmin = ucfirst($data['username']);
            echo "<div class=\"errorbox\">" . $replyingadmin . " " . $aInt->lang("support", "viewedandstarted") . (" @ " . $replyingtime . "</div>");
        }
    } else {
        update_query("ra_ticket", array("replyingadmin" => $_SESSION['adminid'], "replyingtime" => "now()"), array("id" => $id));
    }

    exit();
} elseif ($action == "endreply") {
    check_token("RA.admin.default");
    $access = validateAdminTicketAccess($id);

    if ($access) {
        exit();
    }

    update_query("ra_ticket", array("replyingadmin" => ""), array("id" => $id));
    exit();
} elseif ($action == "changestatus") {
    check_token("RA.admin.default");
    $access = validateAdminTicketAccess($id);

    if ($access) {
        exit();
    }


    if ($status == "Closed") {
        closeTicket($id);
    } else {
        addTicketLog($id, "Status changed to " . $status);
        update_query("ra_ticket", array("status" => $status), array("id" => $id));
        run_hook("TicketStatusChange", array("adminid" => $_SESSION['adminid'], "status" => $status, "ticketid" => $id));
    }

    exit();
} elseif ($action == "changeflag") {
    check_token("RA.admin.default");
    $flag = $_POST['flag'];
    $access = validateAdminTicketAccess($id);

    if ($access) {
        exit();
    }

    addTicketLog($id, "Flagged to " . getAdminName($flag));
    update_query("ra_ticket", array("flag" => $flag), array("id" => $id));

    if ($flag != 0 && $flag != $_SESSION['adminid']) {
        echo "1";
    }

    exit();
} elseif ($action == "loadpredefinedreplies") {
    check_token("RA.admin.default");
    echo genPredefinedRepliesList($cat, $predefq);
    exit();
} elseif ($action == "getpredefinedreply") {
    check_token("RA.admin.default");
    $result = select_query_i("ra_macro_categories_templates", "", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $reply = html_entity_decode($data['reply'], ENT_QUOTES);
    echo $reply;
    exit();
} elseif ($action == "getquotedtext") {
    check_token("RA.admin.default");
    $replytext = "";

    if ($id) {
        $access = validateAdminTicketAccess($id);

        if ($access) {
            exit();
        }

        $result = select_query_i("ra_ticket", "message", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $replytext = $data['message'];
    } else {
        if ($ids) {
            $result = select_query_i("ra_ticket_replies", "tid,message", array("id" => $ids));
            $data = mysqli_fetch_array($result);
            $id = $data['tid'];
            $access = validateAdminTicketAccess($id);

            if ($access) {
                exit();
            }

            $replytext = $data['message'];
        }
    }

    $replytext = wordwrap(html_entity_decode(strip_tags($replytext), ENT_QUOTES), 80);
    $replytext = explode("\r\n", $replytext);

    foreach ($replytext as $line) {
        echo "> " . $line . "\r\n";
    }

    exit();
} elseif ($action == "getcontacts") {
    check_token("RA.admin.default");
    echo getTicketContacts($userid);
    exit();
} elseif ((!$action) || ($action == "list")) {
    if ($sub == "deleteticket") {
        check_token("RA.admin.default");
        checkPermission("Delete Ticket");
        deleteTicket($id);
        redir();
    }


    if ($sub == "multipleaction") {
        check_token("RA.admin.default");

        if ($close) {
            foreach ($selectedtickets as $id) {
                closeTicket($id);
            }
        }


        if ($delete) {
            checkPermission("Delete Ticket");
            foreach ($selectedtickets as $id) {
                deleteTicket($id);
            }
        }


        if ($blockdelete) {
            checkPermission("Delete Ticket");
            foreach ($selectedtickets as $id) {
                $result = select_query_i("ra_ticket", "userid,email", array("id" => $id));
                $data = mysqli_fetch_array($result);
                $userid = $data['userid'];
                $email = $data['email'];

                if ($userid) {
                    $result = select_query_i("ra_user", "email", array("id" => $userid));
                    $data = mysqli_fetch_array($result);
                    $email = $data['email'];
                }

                $result = select_query_i("ra_ticketpamfilters", "COUNT(*)", array("type" => "Sender", "content" => $email));
                $data = mysqli_fetch_array($result);
                $blockedalready = $data[0];

                if (!$blockedalready) {
                    insert_query("ra_ticketpamfilters", array("type" => "Sender", "content" => $email));
                }

                deleteTicket($id);
            }
        }


        if ($merge) {
            sort($selectedtickets);
            $mastertid = $selectedtickets[0];
            $adminname = getAdminName();
            addTicketLog($mastertid, "Merged Tickets " . implode(",", $selectedtickets));
            $adminname = "";
            $result = select_query_i("ra_ticket", "title,userid", array("id" => $mastertid));
            $data = mysqli_fetch_array($result);
            $userid = $data['userid'];
            getUsersLang($userid);
            $merge = $_LANG['ticketmerge'];

            if (!$merge) {
                $merge = "MERGED";
            }

            $subject = (strpos($data[0], (" [" . $merge . "]")) === FALSE ? $data[0] . (" [" . $merge . "]") : $data[0]);
            update_query("ra_ticket", array("title" => $subject), array("id" => $mastertid));
            foreach ($selectedtickets as $id) {
                update_query("ra_ticket_notes", array("ticketid" => $mastertid), array("ticketid" => $id));
                update_query("ra_ticket_replies", array("tid" => $mastertid), array("tid" => $id));

                if ($id != $mastertid) {
                    $result = select_query_i("ra_ticket", "", array("id" => $id));
                    $data = mysqli_fetch_array($result);
                    $userid = $data['userid'];
                    $name = $data['name'];
                    $email = $data['email'];
                    $date = $data['date'];
                    $message = $data['message'];
                    $admin = $data['admin'];
                    $attachment = $data['attachment'];
                    insert_query(
                            "ra_ticket_replies", array("tid" => $mastertid,
                        "userid" => $userid,
                        "name" => $name,
                        "email" => $email,
                        "date" => $date,
                        "message" => $message,
                        "adminname" => $admin,
                        "attachment" => $attachment
                            )
                    );
                    delete_query("ra_ticket", array("id" => $id));
                    continue;
                }
            }
        }

        $filt->redir();
    }
} elseif ($action == "deletereply") {
    $replyid = $ra->get_req_var('replyid');
    $result = delete_query("ra_ticket_replies", array("id" => $replyid));
    if ($result) {
        addTicketLog($id, "Deleted Ticket Reply (ID: " . $replyid . ")");
        logActivity("Deleted Ticket Reply - ID: " . $replyid);
    } else {
        redir("action=viewticket&id=" . $id);
    }
} elseif ($action == "mergeticket") {
    check_token("RA.admin.default");
    $result = select_query_i("ra_ticket", "id", array("tid" => $mergetid));
    $data = mysqli_fetch_array($result);
    $mergeid = $data['id'];

    if (!$mergeid) {
        exit($aInt->lang("support", "mergeidnotfound"));
    }


    if ($mergeid == $id) {
        exit($aInt->lang("support", "mergeticketequal"));
    }

    $mastertid = $id;

    if ($mergeid < $mastertid) {
        $mastertid = $mergeid;
        $mergeid = $id;
    }

    $adminname = getAdminName();
    addTicketLog($mastertid, "Merged Ticket " . $mergeid);
    $adminname = "";
    $result = select_query_i("ra_ticket", "title,userid", array("id" => $mastertid));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    getUsersLang($userid);
    $merge = $_LANG['ticketmerge'];

    if (!$merge) {
        $merge = "MERGED";
    }

    $subject = (strpos($data[0], (" [" . $merge . "]")) === FALSE ? $data[0] . (" [" . $merge . "]") : $data[0]);
    update_query("ra_ticket", array("title" => $subject), array("id" => $mastertid));
    update_query("ra_ticket_notes", array("ticketid" => $mastertid), array("ticketid" => $mergeid));
    update_query("ra_ticket_replies", array("tid" => $mastertid), array("tid" => $mergeid));
    $result = select_query_i("ra_ticket", "", array("id" => $mergeid));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    $name = $data['name'];
    $email = $data['email'];
    $date = $data['date'];
    $message = $data['message'];
    $admin = $data['admin'];
    $attachment = $data['attachment'];
    insert_query(
            "ra_ticket_replies", array(
        "tid" => $mastertid,
        "userid" => $userid,
        "name" => $name,
        "email" => $email,
        "date" => $date,
        "message" => $message,
        "adminname" => $admin,
        "attachment" => $attachment));
    delete_query("ra_ticket", array("id" => $mergeid));
    redir("action=viewticket&id=" . $mastertid);
    exit();
} elseif ($action == "openticket") {
    check_token("RA.admin.default");
    $errormessage = "";

    if (!trim($message)) {
        $errormessage = $aInt->lang("support", "ticketmessageerror");
    }


    if (!trim($subject)) {
        $errormessage = $aInt->lang("support", "ticketsubjecterror");
    }

    if (!$client) {
        if (!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9+_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
            $errormessage = $aInt->lang("support", "ticketemailvalidationerror");
        }


        if (!$email) {
            $errormessage = $aInt->lang("support", "ticketemailerror");
        }


        if (!$name) {
            $errormessage = $aInt->lang("support", "ticketnameerror");
        }
    }

    if (!$errormessage) {
        $attachments = uploadTicketAttachments(true);
        echo $client;
        $ticketdata = openNewTicket(
                $client, $contactid, $deptid, $subject, $message, $priority, $attachments, array("name" => $name, "email" => $email), $relatedservice, $ccemail, ($sendemail ? false : true), true);
        $id = $ticketdata['ID'];
        redir("action=viewticket&id=" . $id);
        exit();
    } else {
        $action = "open";
    }
} elseif ($action == "viewticket") {

    $access = validateAdminTicketAccess($id);

    if ($access == "invalidid") {
        $aInt->gracefulExit($aInt->lang("support", "ticketnotfound"));
    }

    if ($access == "deptblocked") {
        $aInt->gracefulExit($aInt->lang("support", "deptnoaccess"));
    }

    if ($access == "flagged") {
        $aInt->gracefulExit($aInt->lang("support", "flagnoaccess") . ": " . getAdminName($flag));
    }

    if ($access) {
        exit();
    }

    if ($postreply || $postaction) {
        check_token("RA.admin.default");

        if ($postaction == "note") {
            AddNote($id, $message);
        } else {
            $attachments = uploadTicketAttachments(true);

            if ($postaction == "close") {
                $newstatus = "Closed";
            } elseif (substr($postaction, 0, 9) == "setstatus") {
                $result = select_query_i("ra_tickettatuses", "title", array("id" => substr($postaction, 9)));
                $data = mysqli_fetch_array($result);
                $newstatus = $data[0];
            } elseif ($postaction == "onhold") {
                $newstatus = "On Hold";
            } elseif ($postaction == "inprogress") {
                $newstatus = "In Progress";
            } else {
                $newstatus = "Answered";
            }


            AddReply($id, "NULL", "NULL", $message, true, $attachments, null, $newstatus);


            run_hook("TicketStatusChange", array("adminid" => $_SESSION['adminid'], "status" => $newstatus, "ticketid" => $id));

            if ($billingdescription && $billingdescription != $aInt->lang("support", "toinvoicedes")) {
                checkPermission("Create Invoice");
                $result = select_query_i("ra_ticket", "", array("id" => $id));
                $data = mysqli_fetch_array($result);
                $userid = $data['userid'];
                $contactid = $data['contactid'];
                $invoicenow = false;

                if ($billingaction == "3") {
                    $invoicenow = true;
                    $billingaction = "1";
                }

                $billingamount = preg_replace("/[^0-9.]/", "", $billingamount);
                insert_query("tblbillableitems", array("userid" => $userid, "description" => $billingdescription, "amount" => $billingamount, "recur" => 0, "recurcycle" => 0, "recurfor" => 0, "invoiceaction" => $billingaction, "duedate" => "now()"));

                if ($invoicenow) {
                    require ROOTDIR . "/includes/clientfunctions.php";
                    require ROOTDIR . "/includes/processinvoices.php";
                    require ROOTDIR . "/includes/invoicefunctions.php";
                    createInvoices($userid);
                }
            }
        }

        update_query("ra_ticket", array("replyingadmin" => "", "replyingtime" => ""), array("id" => $id));

        if ($postaction == "close") {
            closeTicket($id);
            $filt->redir();
        } else {
            if ($postaction == "return") {
                $filt->redir();
            } else {
                if ($postaction == "onhold") {
                    update_query("ra_ticket", array("status" => "On Hold"), array("id" => $id));
                    run_hook("TicketStatusChange", array("adminid" => $_SESSION['adminid'], "status" => "On Hold", "ticketid" => $id));
                } else {
                    if ($postaction == "inprogress") {
                        update_query("ra_ticket", array("status" => "In Progress"), array("id" => $id));
                        run_hook("TicketStatusChange", array("adminid" => $_SESSION['adminid'], "status" => "In Progress", "ticketid" => $id));
                    }
                }
            }
        }


//        redir("action=viewticket&id=" . $id);
    }

    if ($deptid) {
        check_token("RA.admin.default");
        $adminname = getAdminName();
        $result = select_query_i("ra_ticket", "", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $orig_userid = $data['userid'];
        $orig_contactid = $data['contactid'];
        $orig_deptid = $data['did'];
        $orig_status = $data['status'];
        $orig_priority = $data['urgency'];
        $orig_flag = $data['flag'];
        $orig_cc = $data['cc'];

        if ($orig_userid != $userid) {
            addTicketLog($id, "Ticket Assigned to User ID " . $userid);
        }

        if ($orig_deptid != $deptid) {
            $ticket = new RA_Tickets();
            $ticket->setID($id);
            $ticket->changeDept($deptid);
        }

        if ($orig_status != $status) {
            if ($status == "Closed") {
                closeTicket($id);
            } else {
                addTicketLog($id, "Status changed to " . $status);
            }
        }

        if ($orig_priority != $priority) {
            addTicketLog($id, "Priority changed to " . $priority);
        }

        if ($orig_cc != $cc) {
            addTicketLog($id, "Modified CC Recipients");
        }

        if ($orig_flag != $flagto) {
            $ticket = new RA_Tickets();
            $ticket->setID($id);
            $ticket->setFlagTo($flagto);
        }

        $table = "ra_ticket";
        $array = array("status" => $_POST['status'], "urgency" => $_POST['priority'], "title" => $_POST['subject'], "cc" => $_POST['cc']);
        $where = array("id" => $id);
        update_query($table, $array, $where);

        if ($orig_status != "Closed" && $status == "Closed") {
            run_hook("TicketClose", array("ticketid" => $id));
        }

        if ($mergetid) {
            redir("action=mergeticket&id=" . $id . "&mergetid=" . $mergetid . generate_token("link"));
            exit();
        }

        redir("action=viewticket&id=" . $id);
        exit();
    }

    if ($removeattachment) {
        check_token("RA.admin.default");

        if ($type == "r") {
            $result = select_query_i("ra_ticket_replies", "", array("id" => $idsd));
            $data = mysqli_fetch_array($result);
            $attachment = $data['attachment'];

            if (strpos($attachment, "|") !== FALSE) {
                $attachment = explode("|", $attachment);
                $count = 0;
                foreach ($attachment as $file) {

                    if ($count != $filecount) {
                        $keepfile .= $file . "|";
                    } else {
                        $filetoremove = $file;
                    }

                    ++$count;
                }

                $keepfile = substr($keepfile, 0, 0 - 1);
                deleteFile($attachments_dir, $filetoremove);
                update_query("ra_ticket_replies", array("attachment" => $keepfile), array("id" => $idsd));
            } else {
                deleteFile($attachments_dir, $attachment);
                update_query("ra_ticket_replies", array("attachment" => ""), array("id" => $idsd));
            }
        } else {
            $result = select_query_i("ra_ticket", "", array("id" => $idsd));
            $data = mysqli_fetch_array($result);
            $attachment = $data['attachment'];

            if (strpos($attachment, "|") !== FALSE) {
                $attachment = explode("|", $attachment);
                $count = 0;
                foreach ($attachment as $file) {

                    if ($count != $filecount) {
                        $keepfile .= $file . "|";
                    } else {
                        $filetoremove = $file;
                    }

                    ++$count;
                }

                $keepfile = substr($keepfile, 0, 0 - 1);
                deleteFile($attachments_dir, $filetoremove);
                update_query("ra_ticket", array("attachment" => $keepfile), array("id" => $idsd));
            } else {
                deleteFile($attachments_dir, $attachment);
                update_query("ra_ticket", array("attachment" => ""), array("id" => $idsd));
            }
        }

        redir("action=viewticket&id=" . $id);
        exit();
    }


    if ($sub == "del") {
        check_token("RA.admin.default");
        checkPermission("Delete Ticket");
        deleteTicket($id, $idsd);
        redir("action=viewticket&id=" . $id);
        exit();
    }



    if ($sub == "delnote") {
        check_token("RA.admin.default");
        delete_query("ra_ticket_notes", array("id" => $idsd));
        addTicketLog($id, "Deleted Ticket Note ID " . $idsd);
        redir("action=viewticket&id=" . $id);
        exit();
    }


    if ($blocksender) {
        check_token("RA.admin.default");
        $result = select_query_i("ra_ticket", "userid,email", array("id" => $id));
        $data = get_query_vals("ra_ticket", "userid,email", array("id" => $id));
        $userid = $data['userid'];
        $email = $data['email'];

        if ($userid) {
            $email = get_query_val("ra_user", "email", array("id" => $userid));
        }

        $blockedalready = get_query_val("ra_ticketpamfilters", "COUNT(*)", array("type" => "Sender", "content" => $email));

        if ($blockedalready) {
            infoBox($aInt->lang("support", "spamupdatefailed"), $aInt->lang("support", "spamupdatefailedinfo"));
        } else {
            insert_query("ra_ticketpamfilters", array("type" => "Sender", "content" => $email));
            infoBox($aInt->lang("support", "spamupdatesuccess"), $aInt->lang("support", "spamupdatesuccessinfo"));
        }
    }
} else {
    
}
if ($action == "updnote") {
    check_token("RA.admin.default");
    update_query("ra_ticket_notes", array("message" => $_POST['msg'], "date" => date("Y-m-d H:i:s")), array("id" => $_POST['noteid']));
    addTicketLog($id, "Update Ticket Note ID " . $_POST['noteid']);
    echo $_POST['msg'] = nl2br($_POST['msg']);
    exit();
}
if ($action == "delnote") {
    check_token("RA.admin.default");
    delete_query("ra_ticket_notes", array("id" => $_POST['noteid']));
    addTicketLog($id, "Delete Ticket Note ID " . $_POST['noteid']);
    exit();
}
$supportdepts = getAdminDepartmentAssignments();
$smartyvalues['ticketfilterdata'] = array("view" => $filt->getFromSession("view"), "deptid" => $filt->getFromSession("deptid"), "subject" => $filt->getFromSession("subject"), "email" => $filt->getFromSession("email"));

if ($action == "list" || $action == "") {
    $smartyvalues['inticketlist'] = true;

    if (!count($supportdepts)) {
        $aInt->gracefulExit($aInt->lang("permissions", "accessdenied") . " - " . $aInt->lang("support", "noticketdepts"));
    }

    $tickets = new RA_Tickets();


    if ($_COOKIE['RAAutoRefresh'] && !$action) {
        $refreshtime = intval($_COOKIE['RAAutoRefresh']) * 60;

        if ($refreshtime && !$disable_auto_ticket_refresh) {
            $meta = "<meta http-equiv=\"refresh\" content=\"" . $refreshtime . "\">";
        }
    }

    $filt->setAllowedVars(array("view", "deptid", "client", "subject", "email", "tag"));
    $view = $filt->get("view");

    if ($view == "") {
        $view = $_GET['status'];
    }

    $deptid = $filt->get("deptid");
    $client = $filt->get("client");
    $subject = $filt->get("subject");
    $email = $filt->get("email");
    $tag = $filt->get("tag");
    $aInt->assign(
            "ticketfilterdata", array(
        "view" => $view,
        "deptid" => $deptid,
        "client" => $client,
        "subject" => $subject,
        "email" => $email,
        "tag" => $tag
            )
    );

    $tagjoin = ($tag ? " INNER JOIN ra_ticket_tags ON ra_ticket_tags.ticketid=ra_ticket.id" : "");
    $query = " FROM ra_ticket LEFT JOIN ra_user ON ra_user.id=ra_ticket.userid" . $tagjoin . " WHERE ";
    $filters = $statusfilter = array();

    if ($view == "") {
        $result = select_query_i("ra_tickettatuses", "title", array("showawaiting" => "1"));

        while ($data = mysqli_fetch_array($result)) {
            $statusfilter[] = $data[0];
        }

        $filters[] = "ra_ticket.status IN (" . db_build_in_array($statusfilter) . ")";
    } else {
        if ($view == "any") {
            
        } else {
            if ($view == "active") {
                $result = select_query_i("ra_tickettatuses", "title", array("showactive" => "1"));

                while ($data = mysqli_fetch_array($result)) {
                    $statusfilter[] = $data[0];
                }

                $filters[] = "ra_ticket.status IN (" . db_build_in_array($statusfilter) . ")";
            } else {
                if ($view == "flagged") {
                    $result = select_query_i("ra_tickettatuses", "title", array("showactive" => "1"));

                    while ($data = mysqli_fetch_array($result)) {
                        $statusfilter[] = $data[0];
                    }

                    $filters[] = "ra_ticket.status IN (" . db_build_in_array($statusfilter) . ") AND flag=" . (int) $_SESSION['adminid'];
                } else {
                    $filters[] = "ra_ticket.status='" . db_escape_string($view) . "'";
                }
            }
        }
    }

    $deptfilter = false;

    if ((($client || $subject) || $email) || $clientname) {
        
    } else {
        if (!checkPermission("View Flagged Tickets", true)) {
            $filters[] = "(flag=" . (int) $_SESSION['adminid'] . " OR flag=0)";
        }

        $deptfilter = true;
    }


    if ($client) {
        if (is_int($client)) {
            $filters[] = "ra_ticket.userid='" . db_escape_string($client) . "'";
        } else {
            $filters[] = "(ra_ticket.name LIKE '%" . db_escape_string($client) . "%' "
                    . "OR concat(ra_user.firstname,' ',ra_user.lastname) LIKE '%" . db_escape_string($client) . "%')";
        }
    }
    if ($deptid) {
        $filters[] = "ra_ticket.did='" . db_escape_string($deptid) . "'";
    }
    if ($subject) {
        $filters[] = "(ra_ticket.title LIKE '%" . db_escape_string($subject) . "%' "
                . " OR ra_ticket.message LIKE '%" . db_escape_string($subject) . "%')";
    }
    if ($email) {
        $filters[] = "(ra_ticket.email LIKE '%" . db_escape_string($email) . "%' "
                . "OR ra_user.email LIKE '%" . db_escape_string($email) . "%' "
                . "OR ra_ticket.name LIKE '%" . db_escape_string($email) . "%')";
    }
    if ($clientname) {
        $filters[] = "(ra_ticket.name LIKE '%" . db_escape_string($clientname) . "%' "
                . "OR concat(ra_user.firstname,' ',ra_user.lastname) LIKE '%" . db_escape_string($clientname) . "%')";
    }
    if ($tag) {
        $filters[] = "ra_ticket_tags.tag='" . db_escape_string($tag) . "'";
    }

    releaseSession();

    $query .= implode(" AND ", array_merge($filters, array("ra_ticket.flag=" . (int) $_SESSION['adminid']))) . " ORDER BY ra_ticket.lastreply DESC";
    $numresultsquery = "SELECT COUNT(ra_ticket.id)" . $query;
    $result = full_query_i($numresultsquery);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    $aInt->sortableTableInit("nopagination");
    $query = "SELECT ra_ticket.*,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid" . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
    $result = full_query_i($query);
    buildAdminTicketListArray($result);
    $tableformurl = "?view=" . $view . "&sub=multipleaction";
    $tableformbuttons = "<input onclick=\"return confirm('" . $aInt->lang("support", "massmergeconfirm", "1") . "');\" type=\"submit\" value=\"" . $aInt->lang("clientsummary", "merge") . "\" name=\"merge\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $aInt->lang("support", "masscloseconfirm", "1") . "');\" type=\"submit\" value=\"" . $aInt->lang("global", "close") . "\" name=\"close\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $aInt->lang("support", "massdeleteconfirm", "1") . "');\" type=\"submit\" value=\"" . $aInt->lang("global", "delete") . "\" name=\"delete\" class=\"btn-small\" />";

    if (count($tabledata)) {
        $yourtickets = "
                <p>" . sprintf($aInt->lang("support", "numticketsassigned"), count($tabledata)) . "</p>"
                . $aInt->sortableTable(array("checkall", "", $aInt->lang("fields", "subject"), "Client", $aInt->lang("support", "department"), "Tag", array("flag", "Assigned To"), $aInt->lang("fields", "status"), "Last Replier", $aInt->lang("support", "lastreply")), $tabledata, $tableformurl, $tableformbuttons) . "<br />";
    }

    $aInt->sortableTableInit("lastreply", "ASC");
    $tabledata = array();

    $query = " FROM ra_ticket LEFT JOIN (SELECT tid,name as rname,adminname as radminname,max(date) FROM `ra_ticket_replies` group by ra_ticket_replies.tid) as replies ON replies.tid=ra_ticket.id  LEFT JOIN ra_user ON ra_user.id=ra_ticket.userid" . $tagjoin . " WHERE ";
    $filters[] = "ra_ticket.flag!=" . (int) $_SESSION['adminid'];
    $filters[] = "ra_ticket.flag !=0";
    if ($deptfilter) {
        $filters[] = "did IN (" . db_build_in_array(getAdminDepartmentAssignments()) . ")";
    }

    $query .= implode(" AND ", $filters) . (" ORDER BY ra_ticket." . $orderby . " " . $order);

    $numresultsquery = "SELECT COUNT(ra_ticket.id)" . $query;

    $result = full_query_i($numresultsquery);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    $query = "SELECT ra_ticket.*,ra_user.firstname,rname,radminname,ra_user.lastname,ra_user.companyname,ra_user.groupid" . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
    $result = full_query_i($query);

    buildAdminTicketListArray($result);

    $table = $supporttickets->tablehtml($tabledata, $view);

    $query = "SELECT ra_ticket.*,ra_user.firstname,rname,radminname,ra_user.lastname,"
            . "ra_user.companyname,ra_user.groupid FROM "
            . "ra_ticket LEFT JOIN (SELECT tid,name as rname,adminname as radminname,max(date)"
            . " FROM `ra_ticket_replies` group by ra_ticket_replies.tid) as replies ON replies.tid=ra_ticket.id "
            . "LEFT JOIN ra_user ON ra_user.id=ra_ticket.userid "
            . "WHERE ra_ticket.status IN ('Open','Answered','Customer-Reply','Closed','On Hold','In Progress')"
            . " AND ra_ticket.flag=0 AND did IN (1) ORDER BY ra_ticket.lastreply ASC LIMIT " . (int) $page * $limit . "," . (int) $limit;


    $unsignresult = full_query_i($query);
    $numresultsquery = "SELECT COUNT(ra_ticket.id) from ra_ticket where ra_ticket.flag=0";

    $result = full_query_i($numresultsquery);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    buildAdminTicketListArray($unsignresult);

    $unsignedtable = $aInt->sortableTable(
            array(
        "checkall",
        "",
        array("title", $aInt->lang("fields", "subject")),
        "Client",
        $aInt->lang("support", "department"),
        "Tag",
        array("flag", "Assigned To"),
        array("status", $aInt->lang("fields", "status")),
        "Last Replier",
        array("lastreply", $aInt->lang("support", "lastreply"))
            ), $tabledata, $tableformurl, $tableformbuttons, true
    );


    $aInt->assign("meta", $meta);

    $smartyvalues['tagcloud'] = $tickets->buildTagCloud();
    $smartyvalues['unsignedtable'] = $unsignedtable;
    $smartyvalues['yourticket'] = $yourtickets;
    $template = "support/supporttickets";
}
if ($action == "search") {
    $where = "tid='" . db_escape_string($ticketid) . "' AND did IN (" . db_build_in_array(db_escape_numarray(getAdminDepartmentAssignments())) . ")";
    $result = select_query_i("ra_ticket", "", $where);
    $data = mysqli_fetch_array($result);
    $id = $data['id'];

    if (!$id) {
        echo "<p>" . $aInt->lang("support", "ticketnotfound") . "  <a href=\"javascript:history.go(-1)\">" . $aInt->lang("support", "pleasetryagain") . "</a>.</p>";
    } else {
        $action = "viewticket";
    }
}
if ($action == "viewticket") {
    releaseSession();
    $smartyvalues['inticket'] = true;
    $ticket = new RA_Tickets();
    $ticket->setID($id);
    $data = $ticket->getData();
    //echo "<pre>", print_r($data, 1), "</pre>";
    $id = $data['id'];
    $tid = $data['tid'];
    $deptid = $data['did'];
    $pauserid = $data['userid'];
    $pacontactid = $data['contactid'];
    $name = $data['name'];
    $email = $data['email'];
    $cc = $data['cc'];
    $date = $data['date'];
    $title = $data['title'];
    $message = $data['message'];
    $tstatus = $data['status'];
    $admin = $data['adminname'];
    $attachment = $data['attachment'];
    $urgency = $data['urgency'];
    $lastreply = $data['lastreply'];
    $flag = $data['flag'];
    $replyingadmin = $data['replyingadmin'];
    $replyingtime = $data['replyingtime'];
    $service = $data['service'];
    $replyingtime = fromMySQLDate($replyingtime, "time");
    $access = validateAdminTicketAccess($id);

    if (isset($_POST['tag'])) {
        insert_query("ra_ticket_tags", array("ticketid" => $id, "tag" => $_POST['tag']));
    }
    if ($access == "invalidid") {
        $aInt->gracefulExit($aInt->lang("support", "ticketnotfound"));
    }
    if ($access == "deptblocked") {
        $aInt->gracefulExit($aInt->lang("support", "deptnoaccess"));
    }
    if ($access == "flagged") {
        $aInt->gracefulExit($aInt->lang("support", "flagnoaccess") . ": " . getAdminName($flag));
    }
    if ($access) {
        exit();
    }
    if ($updateticket) {
        check_token("RA.admin.default");

        if ($updateticket == "deptid") {
            $ticket->changeDept($value);
            exit();
        }


        if ($updateticket == "flagto") {
            $ticket->setFlagTo($value);
            exit();
        }


        if ($updateticket == "priority") {
            if (!in_array($value, array("High", "Medium", "Low"))) {
                exit();
            }

            update_query("ra_ticket", array("urgency" => $value), array("id" => (int) $id));
            addTicketLog($id, "Priority changed to " . $value);
            exit();
        }
    }
    if ($sub == "savecustomfields") {
        check_token("RA.admin.default");
        $customfields = getCustomFields("support", $deptid, $id, true);
        foreach ($customfields as $v) {
            $k = $v['id'];
            $customfieldsarray[$k] = $customfield[$k];
        }

        saveCustomFields($id, $customfieldsarray);
        $adminname = getAdminName();
        addTicketLog($id, "Custom Field Values Modified by " . $adminname);
    }
    AdminRead($id);
    if ($replyingadmin && $replyingadmin != $_SESSION['adminid']) {
        $result = select_query_i("ra_admin", "", array("id" => $replyingadmin));
        $data = mysqli_fetch_array($result);
        $replyingadmin = ucfirst($data['username']);
        $smartyvalues['replyingadmin'] = array("name" => $replyingadmin, "time" => $replyingtime);
    }
    $clientname = $contactname = $clientgroupcolour = "";
    if ($pauserid) {
        $clientname = strip_tags($aInt->outputClientLink($pauserid));
    }
    if ($pacontactid) {
        $contactname = strip_tags($aInt->outputClientLink(array($pauserid, $pacontactid)));
    }
    $staffinvolved = array();
    $result = select_query_i("ra_ticket_replies", "DISTINCT adminname", array("tid" => $id));
    while ($data = mysqli_fetch_array($result)) {
        if (trim($data[0])) {
            $staffinvolved[] = $data[0];
        }
    }
    $addons_html = run_hook("AdminAreaViewTicketPage", array("ticketid" => $id));
    $smartyvalues['addons_html'] = $addons_html;
    $department = getDepartmentName($deptid);

    if (!$lastreply) {
        $lastreply = $date;
    }

    $date = fromMySQLDate($date, true);
    $outstatus = getStatusColour($tstatus);
    $tags = array();
    $result = select_query_i("ra_ticket_tags", "id,tag", array("ticketid" => $id), "tag", "ASC");
    while ($data = mysqli_fetch_array($result)) {
        $tags[$data['id']] = $data['tag'];
    }

    $smartyvalues['tags'] = $tags;

    $tags = json_encode($tags);
    $csrfToken = generate_token("plain");
    $jsheadrer = "<script type=\"text/javascript\">
var ticketid = '" . $id . "';
var userid = '" . $pauserid . "';
var ticketTags = " . $tags . ";
var csrfToken = '" . $csrfToken . "';
var langdelreplysure = \"" . $_ADMINLANG['support']['delreplysure'] . "\";
var langdelticketsure = \"" . $_ADMINLANG['support']['delticketsure'] . "\";
var langdelnotesure = \"" . $_ADMINLANG['support']['delnotesure'] . "\";
var langloading = \"" . $_ADMINLANG['global']['loading'] . "\";
var langstatuschanged = \"" . $_ADMINLANG['support']['statuschanged'] . "\";
var langstillsubmit = \"" . $_ADMINLANG['support']['stillsubmit'] . "\";
</script>
<script type=\"text/javascript\" src=\"../includes/jscript/admintickets.js\"></script>
<script type = \"text/javascript\" src=\"../includes/jscript/sisyphus.js\"></script>";

    $aInt->addHeadOutput($jsheadoutput);
    $smartyvalues['infobox'] = $infobox;
    $smartyvalues['ticketid'] = $id;
    $smartyvalues['headeroutput'] = $jsheadrer;
    $smartyvalues['deptid'] = $deptid;
    $smartyvalues['tid'] = $tid;
    $smartyvalues['subject'] = $title;
    $smartyvalues['status'] = $tstatus;
    $smartyvalues['userid'] = $pauserid;
    $smartyvalues['contactid'] = $pacontactid;
    $smartyvalues['clientname'] = $clientname;
    $smartyvalues['contactname'] = $contactname;
    $smartyvalues['clientgroupcolour'] = $clientgroupcolour;
    $smartyvalues['lastreply'] = getLastReplyTime($lastreply);
    $smartyvalues['priority'] = $urgency;
    $smartyvalues['flag'] = $flag;
    $smartyvalues['cc'] = $cc;
    $smartyvalues['staffinvolved'] = $staffinvolved;
    $smartyvalues['deleteperm'] = checkPermission("Delete Ticket", true);
    $result = select_query_i("ra_admin", "firstname,lastname,signature", array("id" => $_SESSION['adminid']));
    $data = mysqli_fetch_array($result);
    $signature = $data['signature'];
    $smartyvalues['signature'] = $signature;
    $smartyvalues['predefinedreplies'] = genPredefinedRepliesList(0);
    $smartyvalues['clientnotes'] = array();
    $result = select_query_i("ra_notes", "ra_notes.*,(SELECT CONCAT(firstname,' ',lastname) FROM ra_admin WHERE ra_admin.id=ra_notes.adminid) AS adminuser", array("userid" => $pauserid, "sticky" => "1"), "modified", "DESC");
    while ($data = mysqli_fetch_assoc($result)) {
        $data['created'] = fromMySQLDate($data['created'], 1);
        $data['modified'] = fromMySQLDate($data['modified'], 1);
        $data['note'] = autoHyperLink(nl2br($data['note']));
        $smartyvalues['clientnotes'][] = $data;
    }
    $notes = array();
    $result = select_query_i("ra_ticket_notes", "", array("ticketid" => $id), "date", "ASC");
    while ($data = mysqli_fetch_array($result)) {
        $notes[] = array("id" => $data['id'], "adminid" => $data['adminid'], "admin" => getAdminName($data['adminid']), "date" => fromMySQLDate($data['date'], true), "message" => ticketAutoHyperlinks($data['message']));
    }
    $smartyvalues['notes'] = $notes;
    $smartyvalues['numnotes'] = count($notes);
    $customfields = getCustomFields("support", $deptid, $id, true);
    $smartyvalues['customfields'] = $customfields;
    $smartyvalues['numcustomfields'] = count($customfields);
    $smartyvalues['departments'] = $departments;
    $staff = array();
    $result = select_query_i("ra_admin", "id,firstname,lastname,supportdepts", "disabled=0 OR id='" . (int) $flag . "'", "firstname` ASC,`lastname", "ASC");
    while ($data = mysqli_fetch_array($result)) {
        $staff[] = array("id" => $data['id'], "name" => $data['firstname'] . " " . $data['lastname']);
    }
    $smartyvalues['staff'] = $staff;

    if ($service) {
        switch (substr($service, 0, 1)) {
            case "S":
                $result = select_query_i("tblcustomerservices", "tblcustomerservices.id,tblcustomerservices.userid,tblcustomerservices.regdate,tblcustomerservices.domain,tblcustomerservices.servicestatus,tblcustomerservices.nextduedate,tblcustomerservices.billingcycle,ra_catalog.name,tblcustomerservices.username,tblcustomerservices.password,ra_catalog.servertype", array("tblcustomerservices.id" => substr($service, 1)), "", "", "", "ra_catalog ON ra_catalog.id=tblcustomerservices.packageid");
                $data = mysqli_fetch_array($result);
                $service_id = $data['id'];
                $service_userid = $data['userid'];
                $service_name = $data['name'];
                $service_domain = $data['domain'];
                $service_status = $data['servicestatus'];
                $service_regdate = $data['regdate'];
                $service_nextduedate = $data['nextduedate'];
                $service_username = $data['username'];
                $service_password = decrypt($data['password']);
                $service_servertype = $data['servertype'];

                if ($service_servertype) {
                    if (!isValidforPath($service_servertype)) {
                        exit("Invalid Server Module Name");
                    }

                    include "../modules/servers/" . $service_servertype . "/" . $service_servertype . ".php";

                    if (function_exists($service_servertype . "_LoginLink")) {
                        $service_loginlink = ServerLoginLink($service_id);
                        echo $service_loginlink;
                    }
                }

                $smartyvalues['relatedproduct'] = array("id" => $service_id, "name" => $service_name, "regdate" => fromMySQLDate($service_regdate), "domain" => $service_domain, "nextduedate" => fromMySQLDate($service_nextduedate), "username" => $service_username, "password" => $service_password, "loginlink" => $service_loginlink, "status" => $service_status);
                break;

            case "D":
                $result = select_query_i("tbldomains", "", array("id" => substr($service, 1)));
                $data = mysqli_fetch_array($result);
                $service_id = $data['id'];
                $service_userid = $data['userid'];
                $service_type = $data['type'];
                $service_domain = $data['domain'];
                $service_status = $data['status'];
                $service_nextduedate = $data['nextduedate'];
                $service_regperiod = $data['registrationperiod'];
                $service_registrar = $data['registrar'];
                $smartyvalues['relateddomain'] = array("id" => $service_id, "domain" => $service_domain, "nextduedate" => fromMySQLDate($service_nextduedate), "registrar" => ucfirst($service_registrar), "regperiod" => $service_regperiod, "ordertype" => $service_type, "status" => $service_status);
        }
    }


    if ($pauserid && checkPermission("List Services", true)) {
        $currency = getCurrency($pauserid);
        $smartyvalues['relatedservices'] = array();
        $totalitems = get_query_val("tblcustomerservices", "COUNT(id)", array("userid" => $pauserid)) + get_query_val("ra_catalog_user_sales_addons", "COUNT(ra_catalog_user_sales_addons.id)", array("tblcustomerservices.userid" => $pauserid), "", "", "", "tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid") + get_query_val("tbldomains", "COUNT(id)", array("userid" => $pauserid));
        $lefttoselect = 10;
        $result = select_query_i("tblcustomerservices", "tblcustomerservices.*,ra_catalog.name", array("userid" => $pauserid), "servicestatus` ASC,`id", "DESC", "0," . $lefttoselect, "ra_catalog ON ra_catalog.id=tblcustomerservices.packageid");

        while ($data = mysqli_fetch_array($result)) {
            $service_id = $data['id'];
            $service_name = $data['name'];
            $service_domain = $data['domain'];
            $service_firstpaymentamount = $data['firstpaymentamount'];
            $service_recurringamount = $data['amount'];
            $service_billingcycle = $data['billingcycle'];
            $service_signupdate = $data['regdate'];
            $service_nextduedate = $data['nextduedate'];
            $service_status = $data['servicestatus'];
            $service_signupdate = fromMySQLDate($service_signupdate);

            if ($service_nextduedate == "0000-00-00") {
                $service_nextduedate = "-";
            } else {
                $service_nextduedate = fromMySQLDate($service_nextduedate);
            }


            if ($service_recurringamount <= 0) {
                $service_amount = $service_firstpaymentamount;
            } else {
                $service_amount = $service_recurringamount;
            }

            $service_amount = formatCurrency($service_amount);
            $selected = ((substr($service, 0, 1) == "S" && substr($service, 1) == $service_id) ? true : false);
            $smartyvalues['relatedservices'][] = array("id" => $service_id, "type" => "product", "name" => "<a href=\"clientsservices.php?userid=" . $pauserid . "&id=" . $service_id . "\" target=\"_blank\">" . $service_name . "</a> - <a href=\"http://" . $service_domain . "/\" target=\"_blank\">" . $service_domain . "</a>", "product" => $service_name, "domain" => $service_domain, "amount" => $service_amount, "billingcycle" => $service_billingcycle, "regdate" => $service_signupdate, "nextduedate" => $service_nextduedate, "status" => $service_status, "selected" => $selected);
        }

        $predefinedaddons = array();
        $result = select_query_i("tbladdons", "", "");

        while ($data = mysqli_fetch_array($result)) {
            $addon_id = $data['id'];
            $addon_name = $data['name'];
            $predefinedaddons[$addon_id] = $addon_name;
        }

        $lefttoselect = 10 - count($smartyvalues['relatedservices']);

        if (0 < $lefttoselect) {
            $result = select_query_i(
                    "ra_catalog_user_sales_addons", "ra_catalog_user_sales_addons.*,ra_catalog_user_sales_addons.id AS addonid,ra_catalog_user_sales_addons.addonid AS addonid2,ra_catalog_user_sales_addons.name AS addonname,tblcustomerservices.id AS hostingid,tblcustomerservices.domain,ra_catalog.name", array("tblcustomerservices.userid" => $pauserid), "status` ASC,`tblhosting`.`id", "DESC", "0," . $lefttoselect, "tblcustomerservices ON tblcustomerservices.id=ra_catalog_user_sales_addons.hostingid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid");

            while ($data = mysqli_fetch_array($result)) {
                $service_id = $data['id'];
                $hostingid = $data['hostingid'];
                $service_addonid = $data['addonid2'];
                $service_name = $data['name'];
                $service_addon = $data['addonname'];
                $service_domain = $data['domain'];
                $service_recurringamount = $data['recurring'];
                $service_billingcycle = $data['billingcycle'];
                $service_signupdate = $data['regdate'];
                $service_nextduedate = $data['nextduedate'];
                $service_status = $data['status'];

                if (!$service_addon) {
                    $service_addon = $predefinedaddons[$service_addonid];
                }

                $service_signupdate = fromMySQLDate($service_signupdate);

                if ($service_nextduedate == "0000-00-00") {
                    $service_nextduedate = "-";
                } else {
                    $service_nextduedate = fromMySQLDate($service_nextduedate);
                }

                $service_amount = formatCurrency($service_recurringamount);
                $selected = ((substr($service, 0, 1) == "A" && substr($service, 1) == $service_id) ? true : false);
                $smartyvalues['relatedservices'][] = array("id" => $service_id, "type" => "addon", "serviceid" => $hostingid, "name" => $aInt->lang("orders", "addon") . (" - " . $service_addon . "<br /><a href=\"clientsservices.php?userid=" . $pauserid . "&id=" . $hostingid . "&aid=" . $service_id . "\" target=\"_blank\">" . $service_name . "</a> - <a href=\"http://" . $service_domain . "/\" target=\"_blank\">" . $service_domain . "</a>"), "product" => $service_addon, "domain" => $service_domain, "amount" => $service_amount, "billingcycle" => $service_billingcycle, "regdate" => $service_signupdate, "nextduedate" => $service_nextduedate, "status" => $service_status, "selected" => $selected);
            }
        }

        $lefttoselect = 10 - count($smartyvalues['relatedservices']);

        if (0 < $lefttoselect) {
            $result = select_query_i("tbldomains", "", array("userid" => $pauserid), "status` ASC,`id", "DESC", "0," . $lefttoselect);

            while ($data = mysqli_fetch_array($result)) {
                $service_id = $data['id'];
                $service_domain = $data['domain'];
                $service_firstpaymentamount = $data['firstpaymentamount'];
                $service_recurringamount = $data['recurringamount'];
                $service_registrationperiod = $data['registrationperiod'] . " Year(s)";
                $service_signupdate = $data['registrationdate'];
                $service_nextduedate = $data['nextduedate'];
                $service_status = $data['status'];
                $service_signupdate = fromMySQLDate($service_signupdate);

                if ($service_nextduedate == "0000-00-00") {
                    $service_nextduedate = "-";
                } else {
                    $service_nextduedate = fromMySQLDate($service_nextduedate);
                }


                if ($service_recurringamount <= 0) {
                    $service_amount = $service_firstpaymentamount;
                } else {
                    $service_amount = $service_recurringamount;
                }

                $service_amount = formatCurrency($service_amount);
                $selected = ((substr($service, 0, 1) == "D" && substr($service, 1) == $service_id) ? true : false);
                $smartyvalues['relatedservices'][] = array("id" => $service_id, "type" => "domain", "name" => "<a href=\"clientsdomains.php?userid=" . $pauserid . "&id=" . $service_id . "\" target=\"_blank\">" . $aInt->lang("fields", "domain") . ("</a> - <a href=\"http://" . $service_domain . "/\" target=\"_blank\">" . $service_domain . "</a>"), "product" => $aInt->lang("fields", "domain"), "domain" => $service_domain, "amount" => $service_amount, "billingcycle" => $service_registrationperiod, "regdate" => $service_signupdate, "nextduedate" => $service_nextduedate, "status" => $service_status, "selected" => $selected);
            }
        }


        if (count($smartyvalues['relatedservices']) < $totalitems) {
            $smartyvalues['relatedservicesexpand'] = true;
        }
    }

    $jscode = "function insertKBLink(url) {
    $(\"#replymessage\").addToReply(url);
}";
    $aInt->jscode = $jscode;
    $jquerycode = "(function() {
    var fieldSelection = {
        addToReply: function() {
            var e = this.jquery ? this[0] : this;
            var text = arguments[0] || '';
            return (
                ('selectionStart' in e && function() {
                    if (e.value==\"\\n\\n" . str_replace("\r\n", "\n", $signature) . "\") {
                        e.selectionStart=0;
                        e.selectionEnd=0;
                    }
                    e.value = e.value.substr(0, e.selectionStart) + text + e.value.substr(e.selectionEnd, e.value.length);
                    e.focus();
                    return this;
                }) ||
                (document.selection && function() {
                    e.focus();
                    document.selection.createRange().text = text;
                    return this;
                }) ||
                function() {
                    e.value += text;
                    return this;
                }
            )();
        }
    };
    jQuery.each(fieldSelection, function(i) { jQuery.fn[i] = this; });
    })();";
    $aInt->jquerycode = $jquerycode;
    $replies = array();
    $result = select_query_i("ra_ticket", "userid,contactid,name,email,date,title,message,adminname,attachment", array("id" => $id));
    $data = mysqli_fetch_array($result);
    $userid = $data['userid'];
    $contactid = $data['contactid'];
    $name = $data['name'];
    $email = $data['email'];
    $date = $data['date'];
    $title = $data['title'];
    $message = $data['message'];
    $admin = $data['adminname'];
    $attachment = $data['attachment'];
    $friendlydate = (substr($date, 0, 10) == date("Y-m-d") ? "" : (substr($date, 0, 4) == date("Y") ? date("l jS F", strtotime($date)) : date("l jS F Y", strtotime($date))));
    $friendlytime = date("H:i", strtotime($date));
    $date = fromMySQLDate($date, true);
    $message = ticketMessageFormat($message);

    if ($userid) {
        $name = $aInt->outputClientLink(array($userid, $contactid));
    }

    $attachments = getTicketAttachmentsInfo($id, "", $attachment);
    $replies[] = array("id" => 0, "admin" => $admin, "userid" => $userid, "contactid" => $contactid, "clientname" => $name, "clientemail" => $email, "date" => $date, "friendlydate" => $friendlydate, "friendlytime" => $friendlytime, "message" => $message, "attachments" => $attachments, "numattachments" => count($attachments));
    $result = select_query_i("ra_ticket_replies", "", array("tid" => $id), "date", "DESC");

    while ($data = mysqli_fetch_array($result)) {

        $replyid = $data['id'];
        $userid = $data['userid'];
        $contactid = $data['contactid'];
        $name = $data['name'];
        $email = $data['email'];
        $date = $data['date'];
        $message = $data['message'];
        $attachment = $data['attachment'];
        $admin = $data['adminname'];
        $rating = $data['rating'];
        $friendlydate = (substr($date, 0, 10) == date("Y-m-d") ? "" : (substr($date, 0, 4) == date("Y") ? date("l jS F", strtotime($date)) : date("l jS F Y", strtotime($date))));
        $friendlytime = date("H:i", strtotime($date));
        $date = fromMySQLDate($date, true);
        $message = ticketMessageFormat($message);
        $message = html_entity_decode($message);
        if ($userid) {
            $name = $aInt->outputClientLink(array($userid, $contactid));
        }

        $attachments = getTicketAttachmentsInfo($id, $replyid, $attachment);
        $ratingstars = "";

        if ($admin && $rating) {
            $i = 1;

            while ($i <= 5) {
                $ratingstars .= ($i <= $rating ? "<img src=\"../images/rating_pos.png\" align=\"absmiddle\">" : "<img src=\"../images/rating_neg.png\" align=\"absmiddle\">");
                ++$i;
            }
        }
        $replies[] = array(
            "id" => $replyid,
            "admin" => $admin,
            "userid" => $userid,
            "contactid" => $contactid,
            "clientname" => $name,
            "clientemail" => $email,
            "date" => $date,
            "friendlydate" => $friendlydate,
            "friendlytime" => $friendlytime,
            "message" => $message,
            "attachments" => $attachments,
            "numattachments" => count($attachments),
            "rating" => $ratingstars
        );
    }

    if ($CONFIG['SupportTicketOrder'] == "DESC") {
        krsort($replies);
    }
//    echo "<pre>", print_r($replies, 1), "</pre>";
    $smartyvalues['replies'] = $replies;
    $smartyvalues['repliescount'] = count($replies);
    $smartyvalues['thumbnails'] = ($CONFIG['AttachmentThumbnails'] ? true : false);
    $splitticketdialog = $aInt->jqueryDialog("splitticket", $aInt->lang("support", "splitticketdialogtitle"), "<p>" . $aInt->lang("support", "splitticketdialoginfo") . "</p><table><tr><td align=\"right\" width=\"120\">" . $aInt->lang("support", "department") . (":</td><td><select id=\"splitdeptidx\">" . $departmentshtml . "</select></td></tr><tr><td align=\"right\">") . $aInt->lang("support", "splitticketdialognewticketname") . (":</td><td><input type=\"text\" id=\"splitsubjectx\" size=\"35\" value=\"" . $title . "\" /></td></tr><tr><td align=\"right\">") . $aInt->lang("support", "priority") . ":</td><td><select id=\"splitpriorityx\"><option value=\"High\"" . ($urgency == "High" ? " selected" : "") . ">High</option><option value=\"Medium\"" . ($urgency == "Medium" ? " selected" : "") . ">Medium</option><option value=\"Low\"" . ($urgency == "Low" ? " selected" : "") . ">Low</option></select></td></tr><tr><td align=\"right\">" . $aInt->lang("support", "splitticketdialognotifyclient") . ":</td><td><label><input type=\"checkbox\" id=\"splitnotifyclientx\" /> " . $aInt->lang("support", "splitticketdialognotifyclientinfo") . "</label></td></tr></table>", array($aInt->lang("global", "submit") => "$('#splitdeptid').val($('#splitdeptidx').val());$('#splitsubject').val($('#splitsubjectx').val());$('#splitpriority').val($('#splitpriorityx').val());$('#splitnotifyclient').val($('#splitnotifyclientx').attr('checked'));$('#ticketreplies').submit();", $aInt->lang("supportreq", "cancel") => ""), "", "400", "");
    $smartyvalues['splitticketdialog'] = $splitticketdialog;
    $template = "support/viewticket";
} else {
    if ($action == "open") {
        $result = select_query_i("ra_admin", "signature", array("id" => $_SESSION['adminid']));
        $data = mysqli_fetch_array($result);
        $signature = $data['signature'];

        if ($errormessage != "") {
            infoBox($aInt->lang("global", "validationerror"), $errormessage);
            echo $infobox;
        }

        if ($userid) {
            $result = select_query_i("ra_user", "id,firstname,lastname,companyname,email", array("id" => $userid));
            $data = mysqli_fetch_array($result);
            $client = $data['id'];

            if ($client) {
                $clientname = $data['firstname'] . " " . $data['lastname'];

                if ($data['companyname']) {
                    $clientname .= " (" . $data['companyname'] . ")";
                }

                $email = $data['email'];
            }
        }

        $contactsdata = "";

        if ($client) {
            $contactsdata = getTicketContacts($client);
        }


        $depidoption = "";
        $result = select_query_i("ra_admin", "", array("id" => $_SESSION['adminid']));
        $data = mysqli_fetch_array($result);
        $supportdepts = $data['supportdepts'];
        $supportdepts = explode(",", $supportdepts);
        $result = select_query_i("ra_ticket_teams", "", "", "order", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $name = $data['name'];

            if (in_array($id, $supportdepts)) {
                $depidoption .= "<option value=\"" . $id . "\"";

                if ($id == $department) {
                    $depidoption .= " selected";
                }

                $depidoption .= ">" . $name . "</option>";
            }
        }
        $aInt->assign('depidoption', $depidoption);

        $template = "support/supportopen";
    }
}
$result = select_query_i("ra_tickettatuses", "", "");
while ($data = mysqli_fetch_array($result)) {
    $statuseshtml .= "<option value=\"" . $data['id'] . "\">" . $data['title'] . "</option>";
}
$aInt->assign("replacemenu", "View Tickets");
$aInt->assign("menuitem", $supporttickets->getMenuItem($PHP_SELF));
$result = select_query_i("ra_ticket_tags", "", "", "id", "DESC");
$tags = "";
while ($data = mysqli_fetch_array($result)) {
    $tags .= "<a href=" . $PHP_SELF . "?tag=" . $data['tag'] . " style=\"margin-right:3px\" class=\"label label-info\">" . $data['tag'] . "</a>";
}
$tagcontainer = "$('div#tickets').append('<div class=\"tag-list\"><h3>Tags:</h3>" . $tags . "</div>');";

$aInt->jquerycode .= $menuselect;
$aInt->jquerycode .= $tagcontainer;
$aInt->assign("clientname", $clientname);
$aInt->assign("email", $email);
$aInt->assign("addto", "Support");
$aInt->assign("smartyvalues", $smartyvalues);
$aInt->assign("table", $table);
$aInt->assign("csrfToken", get_token("plain"));
$aInt->assign("departmentshtml", $departmentshtml);
$aInt->assign("statuseshtml", $statuseshtml);
// $aInt->templatevars = $smartyvalues;
$aInt->template = $template;
$aInt->display();
?>
