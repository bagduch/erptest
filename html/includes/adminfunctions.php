<?php

/** RA - Version 0.1 **/
function getAdminPermsArray() {
    $adminpermsarray = array(
        1 => "Main Homepage",
        2 => "Sidebar Statistics",
        3 => "My Account",
        4 => "List Clients",
        5 => "List Services",
        6 => "List Addons",
        7 => "List Domains",
        8 => "Add New Client",
        104 => "View Clients Summary",
        120 => "Allow Login as Client",
        9 => "Edit Clients Details",
        128 => "View Credit Log",
        129 => "Manage Credits",
        10 => "View Credit Card Details",
        106 => "Decrypt Full Credit Card Number",
        107 => "Update/Delete Stored Credit Card",
        123 => "Attempts CC Captures",
        11 => "View Clients Products/Services",
        12 => "Edit Clients Products/Services",
        99 => "Create Upgrade/Downgrade Orders",
        13 => "Delete Clients Products/Services",
        14 => "Perform Server Operations", 15 => "View Clients Domains", 16 => "Edit Clients Domains", 17 => "Delete Clients Domains", 98 => "Perform Registrar Operations", 95 => "Manage Clients Files", 18 => "View Clients Notes", 19 => "Add/Edit Client Notes", 97 => "Delete Client Notes", 20 => "Delete Client", 21 => "Mass Mail", 22 => "View Cancellation Requests", 23 => "Manage Affiliates", 24 => "View Orders", 25 => "Delete Order", 26 => "View Order Details", 27 => "Add New Order", 28 => "List Transactions", 94 => "View Income Totals", 29 => "Add Transaction", 30 => "Edit Transaction", 31 => "Delete Transaction", 33 => "List Invoices", 34 => "Create Invoice", 124 => "Generate Due Invoices", 35 => "Manage Invoice", 36 => "Delete Invoice", 92 => "Refund Invoice Payments", 89 => "View Billable Items", 90 => "Manage Billable Items", 37 => "Offline Credit Card Processing", 32 => "View Gateway Log", 85 => "Manage Quotes", 38 => "Support Center Overview", 39 => "Manage Announcements", 40 => "Manage Knowledgebase", 41 => "Manage Downloads", 84 => "Manage Network Issues", 42 => "List Support Tickets", 105 => "View Support Ticket",
        121 => "Access All Tickets Directly", 82 => "View Flagged Tickets", 43 => "Open New Ticket", 93 => "Delete Ticket", 125 => "Create Predefined Replies", 44 => "Manage Predefined Replies", 126 => "Delete Predefined Replies", 45 => "View Reports", 88 => "CSV Downloads", 46 => "Addon Modules", 101 => "Email Marketer", 47 => "Link Tracking", 48 => "Browser", 49 => "Calendar", 50 => "To-Do List", 51 => "WHOIS Lookups", 52 => "Domain Resolver Checker", 53 => "View Integration Code", 54 => "WHM Import Script", 55 => "Database Status", 56 => "System Cleanup Operations", 57 => "View PHP Info", 58 => "View Activity Log", 59 => "View Admin Log", 60 => "View Email Message Log", 61 => "View Ticket Mail Import Log", 62 => "View WHOIS Lookup Log", 103 => "View Module Debug Log", 63 => "Configure General Settings", 64 => "Configure Administrators", 65 => "Configure Admin Roles", 127 => "Configure Two-Factor Authentication", 100 => "Configure Addon Modules", 91 => "Configure Client Groups", 66 => "Configure Servers", 67 => "Configure Automation Settings", 86 => "Configure Currencies", 68 => "Configure Payment Gateways", 69 => "Configure Tax Setup", 70 => "View Email Templates", 150 => 'View TXT Templates', 113 => "Create/Edit Email Templates", 114 => "Delete Email Templates", 115 => "Manage Email Template Languages", 71 => "View Products/Services", 119 => "Manage Product Groups", 116 => "Create New Products/Services", 117 => "Edit Products/Services", 118 => "Delete Products/Services", 72 => "Configure Product Addons", 102 => "Configure Product Bundles", 73 => "View Promotions", 108 => "Create/Edit Promotions", 109 => "Delete Promotions", 74 => "Configure Domain Pricing", 75 => "Configure Support Departments", 96 => "Configure Ticket Statuses", 122 => "Configure Order Statuses", 76 => "Configure Spam Control", 110 => "View Banned IPs", 111 => "Add Banned IP", 112 => "Unban Banned IP", 77 => "Configure Banned Emails", 78 => "Configure Domain Registrars", 79 => "Configure Fraud Protection", 80 => "Configure Custom Client Fields", 87 => "Configure Security Questions", 83 => "Configure Database Backups",
        81 => "API Access",
        130=>"View Invoice Payment Monitor",
        131=>"Edit Invoice Payment Monitor");
    return $adminpermsarray;
}

function checkPermission($action, $noredirect = "") {
    static $AdminRoleID = 0;
    static $AdminRolePerms = array();

    $permid = array_search($action, getAdminPermsArray());


    if (isset($_SESSION['adminid'])) {

        if ($AdminRoleID == 0) {
            $result = select_query_i("ra_admin", "roleid", array("id" => $_SESSION['adminid']));
            $data = mysqli_fetch_array($result);
            $roleid = $data['roleid'];
            $AdminRoleID = $roleid;
        }


        if (!count($AdminRolePerms)) {
            $result = select_query_i("ra_adminpriv", "permid", array("roleid" => $AdminRoleID));

            while ($data = mysqli_fetch_array($result)) {
                $AdminRolePerms[] = $data[0];
            }
        }
    }


    $match = (in_array($permid, $AdminRolePerms) ? true : false);

    if ($noredirect) {
        if ($match) {
            return true;
        }

        return false;
    }


    if (!$match) {
        redir("permid=" . $permid, "accessdenied.php");
    }
}

function infoBox($title, $description, $status = "") {
    global $infobox;

    $infobox = "<div class=\"alert alert-";

    if ($status == "error") {
        $infobox .= "danger";
    } else {
        if ($status == "success") {
            $infobox .= "success";
        } else {
            $infobox .= "info";
        }
    }

    $infobox .= "\"><button type=\"button\" aria-hidden=\"true\" class=\"close\">x</button><span><b>" . $title . "</b><br />" . $description . "</span></div>";
}

function getAdminName($adminid = "") {
    if (!$adminid) {
        $adminid = $_SESSION['adminid'];
    }

    $result = select_query_i("ra_admin", "firstname,lastname", array("id" => $adminid));
    $data = mysqli_fetch_array($result);
    $adminname = trim($data['firstname'] . " " . $data['lastname']);
    return $adminname;
}

function getAdminHomeStats($type = "") {
    global $currency;

    $stats = array();
    $currency = getCurrency(0, 1);

    if (!$type || $type == "income") {
        $result = full_query_i("SELECT SUM((amountin-fees-amountout)/rate) FROM ra_transactions WHERE date LIKE '" . date("Y-m-d") . "%'");
        $data = mysqli_fetch_array($result);
        $todaysincome = formatCurrency($data[0]);
        $stats['income']['today'] = $todaysincome;
        $result = full_query_i("SELECT SUM((amountin-fees-amountout)/rate) FROM ra_transactions WHERE date LIKE '" . date("Y-m-") . "%'");
        $data = mysqli_fetch_array($result);
        $todaysincome = formatCurrency($data[0]);
        $stats['income']['thismonth'] = $todaysincome;
        $result = full_query_i("SELECT SUM((amountin-fees-amountout)/rate) FROM ra_transactions WHERE date LIKE '" . date("Y-") . "%'");
        $data = mysqli_fetch_array($result);
        $todaysincome = formatCurrency($data[0]);
        $stats['income']['thisyear'] = $todaysincome;

        if ($type == "income") {
            return $stats;
        }
    }

    $result = full_query_i("SELECT SUM(total)-COALESCE(SUM((SELECT SUM(amountin) FROM ra_transactions WHERE ra_transactions.invoiceid=ra_bills.id)),0) FROM ra_bills WHERE ra_bills.status='Unpaid' AND duedate<'" . date("Ymd") . "'");
    $data = mysqli_fetch_array($result);
    $overdueinvoices = $data[0];
    $stats['invoices']['overduebalance'] = $data[1];
    $result = full_query_i("SELECT COUNT(*) FROM ra_cancellations INNER JOIN tblcustomerservices ON tblcustomerservices.id=ra_cancellations.relid WHERE (tblcustomerservices.servicestatus!='Cancelled' AND tblcustomerservices.servicestatus!='Terminated')");
    $data = mysqli_fetch_array($result);
    $stats['cancellations']['pending'] = $data[0];
    $stats['orders']['today']['active'] = $stats['orders']['today']['fraud'] = $stats['orders']['today']['pending'] = $stats['orders']['today']['cancelled'] = 0;
    $query = "SELECT status,COUNT(*) FROM ra_orders WHERE date LIKE '" . date("Y-m-d") . "%' GROUP BY status";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $stats['orders']['today'][strtolower($data[0])] = $data[1];
    }

    $stats['orders']['today']['total'] = $stats['orders']['today']['active'] + $stats['orders']['today']['fraud'] + $stats['orders']['today']['pending'] + $stats['orders']['today']['cancelled'];
    $stats['orders']['yesterday']['active'] = $stats['orders']['yesterday']['fraud'] = $stats['orders']['yesterday']['pending'] = $stats['orders']['yesterday']['cancelled'] = 0;
    $query = "SELECT status,COUNT(*) FROM ra_orders WHERE date LIKE '" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))) . "%' GROUP BY status";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $stats['orders']['yesterday'][strtolower($data[0])] = $data[1];
    }

    $stats['orders']['yesterday']['total'] = $stats['orders']['yesterday']['active'] + $stats['orders']['yesterday']['fraud'] + $stats['orders']['yesterday']['pending'] + $stats['orders']['yesterday']['cancelled'];
    $query = "SELECT COUNT(*) FROM ra_orders WHERE date LIKE '" . date("Y-m-") . "%'";
    $result = full_query_i($query);
    $data = mysqli_fetch_array($result);
    $stats['orders']['thismonth']['total'] = $data[0];
    $query = "SELECT COUNT(*) FROM ra_orders WHERE date LIKE '" . date("Y-") . "%'";
    $result = full_query_i($query);
    $data = mysqli_fetch_array($result);
    $stats['orders']['thisyear']['total'] = $data[0];
        $allactive = $awaitingreply = 0;
        $ticketcounts = array();
        $query = "SELECT ra_tickettatuses.title,(SELECT COUNT(*) FROM ra_ticket WHERE ra_ticket.status=ra_tickettatuses.title),showactive,showawaiting FROM ra_tickettatuses ORDER BY sortorder ASC";
        $result = full_query_i($query);

        while ($data = mysqli_fetch_array($result)) {
            $stats['tickets'][preg_replace("/[^a-z0-9]/", "", strtolower($data[0]))] = $data[1];

            if ($data['showactive']) {
                $allactive += $data[1];
            }


            if ($data['showawaiting']) {
                $awaitingreply += $data[1];
            }
        }

        $result = select_query_i("ra_ticket", "COUNT(*)", "status!='Closed' AND flag='" . (int) $_SESSION['adminid'] . "'");
        $data = mysqli_fetch_array($result);
        $flaggedtickets = $data[0];
        $stats['tickets']['allactive'] = $allactive;
        $stats['tickets']['awaitingreply'] = $awaitingreply;
        $stats['tickets']['flaggedtickets'] = $flaggedtickets;

    $query = "SELECT COUNT(*) FROM tbltodolist WHERE status!='Completed' AND status!='Postponed' AND duedate<='" . date("Y-m-d") . "'";
    $result = full_query_i($query);
    $data = mysqli_fetch_array($result);
    $stats['todoitems']['due'] = $data[0];
    $query = "SELECT COUNT(*) FROM ra_system_status WHERE status!='Scheduled' AND status!='Resolved'";
    $result = full_query_i($query);
    $data = mysqli_fetch_array($result);
    $stats['networkissues']['open'] = $data[0];
    $result = select_query_i("tblbillableitems", "COUNT(*)", array("invoicecount" => "0"));
    $data = mysqli_fetch_array($result);
    $stats['billableitems']['uninvoiced'] = $data[0];
    $result = select_query_i("tblquotes", "COUNT(*)", array("validuntil" => array("sqltype" => ">", "value" => date("Ymd"))));
    $data = mysqli_fetch_array($result);
    $stats['quotes']['valid'] = $data[0];
    return $stats;
}

?>
