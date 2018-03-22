<?php

/**
 *
 * @ RA
 *
 * */
class RA_Support {

    public $tickets;
    public $ticket;
    public $aInt;
    public $id;
    public $action;
    public static $icon = "tickets";
    public $smartyvalues = array();
    public $filt;
    public static $qlimit = 10;
    public $offset;
    public $userid;

    public function __construct($id, $action, $userid) {


        if (isset($userid)) {
            $this->userid = $userid;
        }


        if (isset($id)) {
            $result = select_query_i("tbltickets", "", array("id" => $id));
            $this->ticket = mysqli_fetch_array($result);
            $this->id = $id;
        } else {
            $result = select_query_i("tbltickets");
            $this->tickets = mysqli_fetch_array($result);
        }
        if ($action == "viewticket") {
            $reqperm = "View Support Ticket";
            $this->aInt = new RA_Admin($reqperm);
        } elseif ($action == "openticket" || $action == "open") {
            $reqperm = "Open New Ticket";
            $this->icon = "ticketsopen";
            $this->aInt = new RA_Admin($reqperm);
        } elseif ((!$action) && (!$sub)) {
            $action = "list";
            $reqperm = "List Support Tickets";
            $this->aInt = new RA_Admin($reqperm, false);
        } else {
            $reqperm = "List Support Tickets";
            $this->aInt = new RA_Admin($reqperm, false);
        }

        $this->Setup($reqperm);

        $this->action = $action;
    }

    protected function Setup($reqperm) {
        $title = $reqperm;
        $this->aInt = $this->aInt;
        $this->aInt->title = $title;
        $this->aInt->sidebar = "support";
        $this->aInt->icon = $this->icon;
        $this->aInt->helplink = "Support Tickets";
        $this->aInt->requiredFiles(array("ticketfunctions", "modulefunctions", "customfieldfunctions"));
        $this->filt = new RA_Filter("tickets");
    }

    protected function accessCheck() {
        $access = validateAdminTicketAccess($this->id);

        if ($access) {
            exit();
        }
    }

    public function gettages() {
        $array = array();
        $result = select_query_i("tbltickettags", "DISTINCT tag", "tag LIKE '" . db_escape_string($q) . "%'", "tag", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $array[] = $data[0];
        }

        return json_encode($array);
    }

    public function removetag() {
        $tagid = $_POST['tagid'];
        $result = delete_query("tbltickettags", array("id" => $tagid));
        return $result;
    }

    public function savetags() {
        $this->accessCheck();
        $tags = json_decode(html_entity_decode($tags, ENT_QUOTES), true);
        foreach ($tags as $k => $tag) {
            $tags[$k] = strip_tags($tag);
        }

        $existingtags = array();
        $result = select_query_i("tbltickettags", "tag", array("ticketid" => $id));

        while ($data = mysqli_fetch_assoc($result)) {
            $existingtags[] = $data['tag'];
        }

        foreach ($existingtags as $tag) {

            if (trim($tag)) {
                if (!in_array($tag, $tags)) {
                    delete_query("tbltickettags", array("ticketid" => $id, "tag" => $tag));
                    addTicketLog($id, "Deleted Tag " . $tag);
                    continue;
                }

                continue;
            }
        }

        foreach ($tags as $tag) {

            if (trim($tag)) {
                if (!in_array($tag, $existingtags)) {
                    insert_query("tbltickettags", array("ticketid" => $id, "tag" => $tag));
                    addTicketLog($id, "Added Tag " . $tag);
                    continue;
                }

                continue;
            }
        }
    }

    public function checkstatus($ticketstatus) {
        $this->accessCheck();
        if ($this->ticket['status'] == $ticketstatus) {
            return "true";
        } else {
            return "false";
        }
    }

    public function splite($pdata) {

        if (empty($data['rids'])) {
            redir("action=viewticket&id=" . $id . "");
            exit();
        }
        $this->accessCheck();

        $pdata['rids'] = db_escape_numarray($pdata['rids']);
        $pdata['rids'] = implode(", ", $pdata['rids']);
        $noemail = (!$pdata['splitnotifyclient'] ? TRUE : FALSE);
        $result = select_query_i("tblticketreplies", "id,message", "`id` IN (" . $pdata['rids'] . ")", "date", "ASC", "0,1");
        $data = mysqli_fetch_array($result);

        $subject = (trim($pdata["splitsubject"]) ? $pdata["splitsubject"] : $this->ticket['title']);
        $deptid = (trim($pdata["splitdeptid"]) ? $pdata["splitdeptid"] : $this->ticket['did']);
        $priority = (trim($pdata['splitpriority']) ? $pdata['splitpriority'] : $this->ticket['urgency']);

        $newOpenedTicketResults = openNewTicket($this->ticket['userid'], $this->ticket['did'], $deptid, $subject, $data['id'], $priority, $data['message'], array("name" => $this->ticket['userid'], "email" => $newTicketEmail), $this->ticket['userid'], $this->ticket['userid'], $noemail, $data['admin']);

        $ticketid = $newOpenedTicketResults['ID'];
        delete_query("tblticketreplies", array("id" => $data['id']));
        update_query("tblticketreplies", array("tid" => $ticketid), "`id` IN (" . $pdata['rids'] . ")");
        return $ticketid;
    }

    public function getmsg($ref) {
        $msg = "";
        $id = substr($ref, 1);

        if (substr($ref, 0, 1) == "t") {
            $this->accessCheck();

            $msg = get_query_val("tbltickets", "message", array("id" => $id));
        } else {
            if (substr($ref, 0, 1) == "r") {
                $data = get_query_vals("tblticketreplies", "tid,message", array("id" => $id));
                $id = $data['tid'];
                $msg = $data['message'];
                $this->accessCheck();
            }
        }

        return $msg;
    }

    public function getticketlog($offset, $target) {
        $this->accessCheck();
        $offset = (int) $offset;
        $numberdetail = $this->getticketlogNumber($offset);
        $totaltickets = $numberdetail['totaltickets'];
        $endnum = $numberdetail['endnum'];
        $html = "<div style=\"padding:0 0 5px 0;text-align:left;\">Showing <strong>" . ($offset + 1) . "</strong> to <strong>" . ($totaltickets < $endnum ? $totaltickets : $endnum) . "</strong> of <strong>" . $totaltickets . " total</strong></div>";
        $this->aInt->sortableTableInit("nopagination");
        $html .= $this->aInt->sortableTable(array($this->aInt->lang("fields", "date"), $this->aInt->lang("permissions", "action")), $this->getticketlogDetail($offset));

        $html .= "<table width=\"80%\" align=\"center\"><tr><td style=\"text-align:left;\">";

        if (0 < $offset) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'ticketlog'," . ($offset - $qlimit) . ");return false\">";
        }

        $html .= "&laquo; Previous</a></td><td style=\"text-align:right;\">";

        if ($endnum < $totaltickets) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'ticketlog'," . $endnum . ");return false\">";
        }

        $html .= "Next &raquo;</a></td></tr></table>";
        return $html;
    }

    public function getticketlogDetail($offset) {
        $result = select_query_i("tblticketlog", "", array("tid" => $this->id), "date", "DESC", "" . $offset . "," . $this->qlimit);

        while ($data = mysqli_fetch_array($result)) {
            $tabledata[] = array(fromMySQLDate($data['date'], 1), "<div style=\"text-align:left;\">" . $data['action'] . "</div>");
        }
        return $tabledata;
    }

    public function getticketlogNumber($offset) {

        $totaltickets = get_query_val("tblticketlog", "COUNT(id)", array("tid" => $this->id));



        if ($offset < 0) {
            $offset = 0;
        }

        $endnum = $offset + $this->qlimit;
        $data = array(
            "totaltickets" => $totaltickets,
            "endnum" => $endnum,
        );

        return $data;
    }

    public function getclientlog($offset, $target) {
        $offset = (int) $offset;
        $endnum = $offset + $this->qlimit;
        $totaltickets = $this->getClientlogNumber();
        $html = "<div style=\"padding:0 0 5px 0;text-align:left;\">Showing <strong>" . ($offset + 1) . "</strong> to <strong>" . ($totaltickets < $endnum ? $totaltickets : $endnum) . "</strong> of <strong>" . $totaltickets . " total</strong></div>";
        $this->aInt->sortableTableInit("nopagination");
        $html .= $this->aInt->sortableTable(array($this->aInt->lang("fields", "date"), $this->aInt->lang("permissions", "action"), $this->aInt->lang("support", "user"), $this->aInt->lang("fields", "ipaddress")), $this->getClientlogDetails($offset));
        $html .= "<table width=\"80%\" align=\"center\"><tr><td style=\"text-align:left;\">";
        if (0 < $offset) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'clientlog'," . ($offset - $this->qlimit) . ");return false\">";
        }

        $html .= "&laquo; Previous</a></td><td style=\"text-align:right;\">";

        if ($endnum < $totaltickets) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'clientlog'," . $endnum . ");return false\">";
        }

        $html .= "Next &raquo;</a></td></tr></table>";
        return $html;
    }

    public function getClientlogDetails($offset) {
        $result = select_query_i("tblactivitylog", "", array("userid" => $this->userid), "date", "DESC", "" . $offset . "," . $this->qlimit);
        while ($data = mysqli_fetch_array($result)) {
            $description = $data['description'];
            $description .= " ";
            $description = RAHtmlspecialchars($description);
            $description = preg_replace($this->getClientPatterns(), $this->getClientReplacements(), $description);
            $tabledata[] = array(fromMySQLDate($data['date'], 1), "<div style=\"text-align:left;\">" . $description . "</div>", $data['user'], $data['ipaddr']);
        }
        return $tabledata;
    }

    public function getClientlogNumber() {
        $totaltickets = get_query_val("tblactivitylog", "COUNT(id)", array("userid" => $this->userid));
        return $totaltickets;
    }

    public function getClientPatterns() {
        $patterns = array();
        $patterns[] = "/User ID: (.*?) /";
        $patterns[] = "/Service ID: (.*?) /";
        $patterns[] = "/Domain ID: (.*?) /";
        $patterns[] = "/Invoice ID: (.*?) /";
        $patterns[] = "/Quote ID: (.*?) /";
        $patterns[] = "/Order ID: (.*?) /";
        $patterns[] = "/Transaction ID: (.*?) /";
        return $patterns;
    }

    public function getClientReplacements() {
        $replacements = array();
        $replacements[] = "<a href=\"clientssummary.php?userid=$1\">User ID: $1</a> ";
        $replacements[] = "<a href=\"clientsservices.php?id=$1\">Service ID: $1</a> ";
        $replacements[] = "<a href=\"clientsdomains.php?id=$1\">Domain ID: $1</a> ";
        $replacements[] = "<a href=\"invoices.php?action=edit&id=$1\">Invoice ID: $1</a> ";
        $replacements[] = "<a href=\"quotes.php?action=manage&id=$1\">Quote ID: $1</a> ";
        $replacements[] = "<a href=\"orders.php?action=view&id=$1\">Order ID: $1</a> ";
        $replacements[] = "<a href=\"transactions.php?action=edit&id=$1\">Transaction ID: $1</a> ";
        return $replacements;
    }

    public function gettickets($offset, $target) {
        $departmentsarray = getDepartments();

        if (isset($this->userid)) {
            $where = array("userid" => $this->userid);
        } else {
            $where = array("email" => get_query_val("tbltickets", "email", array("id" => $this->id)));
        }
        $totaltickets = get_query_val("tbltickets", "COUNT(id)", $where);
        $this->qlimit = 4;

        $offset = (int) $offset;

        if ($offset < 0) {
            $offset = 0;
        }
        $endnum = $offset + $this->qlimit;

        $html = "<div style=\"padding:0 0 5px 0;text-align:left;\">Showing <strong>" . ($offset + 1) . "</strong> to <strong>" . ($totaltickets < $endnum ? $totaltickets : $endnum) . "</strong> of <strong>" . $totaltickets . " total</strong></div>";
        $this->aInt->sortableTableInit("nopagination");
        $tabledata = $this->getticketDetails($where, $offset);
        $this->aInt->sortableTable(
                array(
            "",
            $this->aInt->lang("support", "datesubmitted"),
            $this->aInt->lang("support", "department"),
            $this->aInt->lang("fields", "subject"),
            $this->aInt->lang("fields", "status"),
            $this->aInt->lang("support", "lastreply")), $tabledata
        );

        $html .= "<table width=\"80%\" align=\"center\"><tr><td style=\"text-align:left;\">";

        if (0 < $offset) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'tickets'," . ($offset - $this->qlimit) . ");return false\">";
        }

        $html .= "&laquo; Previous</a></td><td style=\"text-align:right;\">";

        if ($endnum < $totaltickets) {
            $html .= "<a href=\"#\" onclick=\"loadTab(" . $target . ",'tickets'," . $endnum . ");return false\">";
        }

        $html .= "Next &raquo;</a></td></tr></table>";
        return $html;
    }

    public function getticketDetails($where, $offset) {
        $result = select_query_i("tbltickets", "", $where, "lastreply", "DESC", "" . $offset . "," . $qlimit);
        while ($data = mysqli_fetch_array($result)) {
            if (!in_array($_SESSION['adminid'], explode(",", $data['adminunread']))) {
                $unread = 1;
            } else {
                $unread = 0;
            }

            if (!trim($data['title'])) {
                $data['title'] = "(" . $this->aInt->lang("emails", "nosubject") . ")";
            }
            $flaggedto = "";
            if ($flag == $_SESSION['adminid']) {
                $showflag = "user";
            } else {
                if ($flag == 0) {
                    $showflag = "none";
                } else {
                    $showflag = "other";
                    $flaggedto = getAdminName($flag);
                }
            }

            $department = $departmentsarray[$did];

            if ($flaggedto) {
                $department .= " (" . $flaggedto . ")";
            }

            $date = fromMySQLDate($data['date'], "time");
            $lastactivity = fromMySQLDate($data['lastreply'], "time");
            $tstatus = getStatusColour($data['status']);
            $lastreply = getShortLastReplyTime($data['lastreply']);
            $flagstyle = ($showflag == "user" ? "<span class=\"ticketflag\">" : "");
            $title = "#" . $ticketnumber . " - " . $data['title'];

            if ($unread || $showflag == "user") {
                $title = "<strong>" . $data['title'] . "</strong>";
            }

            $ticketlink = ("<a href=\"" . $PHP_SELF . "?action=viewticket&id=" . $id . "\"") . $ainject . ">";
            $tabledata[] = array("<img src=\"images/" . strtolower($data['urgency']) . ("priority.gif\" width=\"16\" height=\"16\" alt=\"" . $data['urgency'] . "\" class=\"absmiddle\" />"), $flagstyle . $date, $flagstyle . $department, "<div style=\"text-align:left;\">" . $flagstyle . $ticketlink . $title . "</a></div>", $flagstyle . $tstatus, $flagstyle . $lastreply);
        }
        return $tabledata;
    }

    public function getallservices() {
        $pauserid = (int) $this->userid;
        $currency = getCurrency($pauserid);
        $service = $this->ticket['service'];
        $output = array();
        $result = select_query_i("tblcustomerservices", "tblcustomerservices.*,tblservices.name", array("userid" => $pauserid), "servicestatus` ASC,`id", "DESC", "", "tblservices ON tblservices.id=tblcustomerservices.packageid");
        while ($data = mysqli_fetch_array($result)) {
            $service_id = $data['id'];
            $service_name = $data['name'];
            $service_domain = $data['domain'];
            $service_firstpaymentamount = $data['firstpaymentamount'];
            $service_recurringamount = $data['amount'];
            $service_billingcycle = $data['billingcycle'];
            $service_regdate = $data['regdate'];
            $service_regdate = fromMySQLDate($service_regdate);
            $service_nextduedate = $data['nextduedate'];
            $service_nextduedate = ($service_nextduedate == "0000-00-00" ? "-" : fromMySQLDate($service_nextduedate));

            if ($service_recurringamount <= 0) {
                $service_amount = $service_firstpaymentamount;
            } else {
                $service_amount = $service_recurringamount;
            }

            $service_amount = formatCurrency($service_amount);
            $selected = (substr($service, 0, 1) == "S" && substr($service, 1) == $service_id) ? true : false;
            $service_name = "<a href=\"clientshosting.php?userid=" . $pauserid . "&id=" . $service_id . "\" target=\"_blank\">" . $service_name . "</a> - <a href=\"http://" . $service_domain . "/\" target=\"_blank\">" . $service_domain . "</a>";
            $output[] = "<tr" . ($selected ? " class=\"rowhighlight\"" : "") . "><td>" . $service_name . "</td><td>" . $service_amount . "</td><td>" . $service_billingcycle . "</td><td>" . $service_regdate . "</td><td>" . $service_nextduedate . "</td><td>" . $data['servicestatus'] . "</td></tr>";
        }

        $result = select_query_i("tblcustomerservices", "", array("userid" => $pauserid), "status` ASC,`id", "DESC");

        while ($data = mysqli_fetch_array($result)) {
            $service_id = $data['id'];
            $service_domain = $data['description'];
            $service_firstpaymentamount = $data['firstpaymentamount'];
            $service_recurringamount = $data['recurringamount'];
            $service_registrationperiod = $data['registrationperiod'] . " Year(s)";
            $service_regdate = $data['registrationdate'];
            $service_regdate = fromMySQLDate($service_regdate);
            $service_nextduedate = $data['nextduedate'];
            $service_nextduedate = ($service_nextduedate == "0000-00-00" ? "-" : fromMySQLDate($service_nextduedate));

            if ($service_recurringamount <= 0) {
                $service_amount = $service_firstpaymentamount;
            } else {
                $service_amount = $service_recurringamount;
            }

            $service_amount = formatCurrency($service_amount);
            $selected = ((substr($service, 0, 1) == "D" && substr($service, 1) == $service_id) ? true : false);
            $service_name = "<a href=\"clientsservices.php?userid=" . $pauserid . "&id=" . $service_id . "\" target=\"_blank\">Address: " . $service_domain . "</a>";
            $output[] = "<tr" . ($selected ? " class=\"rowhighlight\"" : "") . "><td>" . $service_name . "</td><td>" . $service_amount . "</td><td>" . $service_registrationperiod . "</td><td>" . $service_regdate . "</td><td>" . $service_nextduedate . "</td><td>" . $data['status'] . "</td></tr>";
        }

        $i = 0;

        while ($i <= 9) {
            unset($output[$i]);
            ++$i;
        }
        return implode($output);
    }

    public function updatereply($ref, $text, $id) {

        if (substr($ref, 0, 1) == "t") {
            update_query("tbltickets", array("message" => $text), array("id" => substr($ref, 1)));
        } else {
            if (substr($ref, 0, 1) == "r") {
                update_query("tblticketreplies", array("message" => $text), array("id" => substr($ref, 1)));
            } else {
                if ($id && is_numeric($id)) {
                    update_query("tblticketreplies", array("message" => $text), array("id" => $id));
                }
            }
        }

        $text = nl2br($text);
        $text = ticketAutoHyperlinks($text);

        return $text;
    }

    public function getTicketstatus() {

        $status = array();
        $result = select_query_i("tblticketstatuses", "", "", "sortorder", "ASC");
        while ($data = mysqli_fetch_array($result)) {
            $status[] = array("title" => $data['title'], "color" => $data['color'], "id" => $data['id']);
        }
        return $status;
    }

    public function getTicketstatusHtml() {


        if (!empty($this->ticket)) {
            $view = $this->ticket['status'];
        }
        $status = $this->getTicketstatus();

        foreach ($status as $row) {
            $statuseshtml .= "<option style=\"color:" . $row['color'] . "\" value=\"" . $row['title'] . "\"" . ($row['title'] == $view ? " selected" : "") . ">" . $row['title'] . "</option>";
        }

        return $statuseshtml;
    }

    public function getMenuItem($link) {
        $ticketmenustatus[0] = array(
            "name" => "All Tickets",
            "icon" => "",
            "link" => $link . "?status=",
        );
        $statuses = $this->getTicketstatus();
        foreach ($statuses as $row) {
            $ticketmenustatus[] = array(
                "name" => $row['title'],
                "icon" => "",
                "link" => $link . "?status=" . $row['title'],
            );
        }

        $menuitem = array(array(
                "name" => "View Tickets",
                "id" => "tickets",
                "icon" => 'fa fa-ticket',
                "items" => array(
                    array(
                        "title" => "View Tickets",
                        "id" => "ticketmenu",
                        "id" => "tickets",
                        "icon" => 'fa fa-ticket',
                        "items" => $ticketmenustatus
                    )
                )
            )
        );

        return json_encode($menuitem);
    }

    public function tablehtml($tabledata, $view) {


        $tableformurl = "?view=" . $view . "&sub=multipleaction";
        $tableformbuttons = "<input onclick=\"return confirm('" . $this->aInt->lang("support", "massmergeconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("clientsummary", "merge") . "\" name=\"merge\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $this->aInt->lang("support", "masscloseconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("global", "close") . "\" name=\"close\" class=\"btn-small\" /> <input onclick=\"return confirm('" . $this->aInt->lang("support", "massdeleteconfirm", "1") . "');\" type=\"submit\" value=\"" . $this->aInt->lang("global", "delete") . "\" name=\"delete\" class=\"btn-small\" />";
        $table = $this->aInt->sortableTable(
                array(
            "checkall",
            "",
            array("title", $this->aInt->lang("fields", "subject")),
            "Client",
            $this->aInt->lang("support", "department"),
            "Tag",
            array("flag", "Assigned To"),
            array("status", $this->aInt->lang("fields", "status")),
            "Last Replier",
            array("lastreply", $this->aInt->lang("support", "lastreply"))
                ), $tabledata, $tableformurl, $tableformbuttons, true
        );

        return $table;
    }

    public function postAction($postaction) {
        
    }

}

?>