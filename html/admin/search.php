<?php

/** RA - Version 0.1 **/
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("loginonly");

if ($a == "savenotes") {
    check_token("RA.admin.default");
    update_query("ra_admin", array("notes" => $notes), array("id" => $_SESSION['adminid']));
    exit();
}


if ($a == "minsidebar") {
    wSetCookie("MinSidebar", "1");
    exit();
}


if ($a == "maxsidebar") {
    wDelCookie("MinSidebar");
    exit();
}

$matches = $tempmatches = $invoicematches = $ticketmatches = "";

if ($intellisearch) {
    check_token("RA.admin.default");
    $value = trim($_POST['value']);
    if (strlen($value) < 3 && !is_numeric($value)) {
        exit();
    }

    $value = db_escape_string($value);

    if (checkPermission("List Clients", true) || checkPermission("View Clients Summary", true)) {
        $query = "SELECT id,firstname,lastname,companyname,email,status FROM ra_user WHERE concat(firstname,' ',lastname) LIKE '%" . $value . "%' OR companyname LIKE '%" . $value . "%' OR address1 LIKE '%" . $value . "%' OR address2 LIKE '%" . $value . "%' OR postcode LIKE '%" . $value . "%' OR phonenumber LIKE '%" . $value . "%'";


        if (is_numeric($value)) {
            $query .= " OR id='" . $value . "'";
        }


        if (is_numeric($value) && strlen($value) == 4) {
//            $query .= " OR cardlastfour='" . $value . "'";
        } else {
            $query .= " OR city LIKE '%" . $value . "%' OR state LIKE '%" . $value . "%' OR email LIKE '%" . $value . "%'";
        }

        $query .= " LIMIT 0,10";
        $result = full_query_i($query);

        $colstart = "<div class=\"dummy-column\"><h2>Clients</h2>";
        while ($data = mysqli_fetch_array($result)) {
            $userid = $data['id'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];
            $email = $data['email'];
            $status = $data['status'];

            if ($companyname) {
                $companyname = " (" . $companyname . ")";
            }

            $tempmatches .= "<a class=\"dummy-media-object\" href=\"clientssummary.php?userid=" . $userid . "\"><h3>" . $firstname . " " . $lastname . $companyname . "</h3> #" . $userid . " <span class=\"label " . strtolower($status) . ("\">" . $status . "</span><br /><span class=\"desc\">" . $email . "</span></a>");
        }


        if ($tempmatches) {
            $matches .= $colstart . $tempmatches . "</div>";
        }

        $tempmatches = "";
        $query = "SELECT id,userid,firstname,lastname,companyname,email FROM ra_user_contacts WHERE concat(firstname,' ',lastname) LIKE '%" . $value . "%' OR companyname LIKE '%" . $value . "%' OR address1 LIKE '%" . $value . "%' OR address2 LIKE '%" . $value . "%' OR postcode LIKE '%" . $value . "%' OR phonenumber LIKE '%" . $value . "%'";

        if (is_numeric($value)) {
            $query .= " OR id='" . $value . "'";
        } else {
            $query .= " OR city LIKE '%" . $value . "%' OR state LIKE '%" . $value . "%' OR email LIKE '%" . $value . "%'";
        }

        $query .= " LIMIT 0,10";
        $result = full_query_i($query);
        $colstart = "<div class=\"dummy-column\"><h2>Contacts</h2>";
        while ($data = mysqli_fetch_array($result)) {
            $contactid = $data['id'];
            $userid = $data['userid'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];
            $email = $data['email'];

            if ($companyname) {
                $companyname = " (" . $companyname . ")";
            }

            $tempmatches .= "<a class=\"dummy-media-object\" href=\"clientscontacts.php?userid=" . $userid . "&contactid=" . $contactid . "\"><h3>" . $firstname . " " . $lastname . $companyname . "</h3> #" . $contactid . "<br /><span class=\"desc\">" . $email . "</span></a>";
        }


        if ($tempmatches) {
            $matches .= $colstart . $tempmatches . "</div>";
        }
    }


    if (checkPermission("List Services", true) || checkPermission("View Clients Products/Services", true)) {
        $tempmatches = "";
        $query = "SELECT ra_user.firstname,ra_user.lastname,ra_user.companyname,tblcustomerservices.id,tblcustomerservices.userid,tblcustomerservices.description,ra_catalog.name,tblcustomerservices.servicestatus FROM tblcustomerservices INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid WHERE ";

        if (is_numeric($value)) {
            $query .= "tblcustomerservices.id='" . $value . "' OR";
        }

        $query .= " tblcustomerservices.description LIKE '%" . $value . "%' OR tblcustomerservices.notes LIKE '%" . $value . "%'";
        $query .= " LIMIT 0,10";

        $result = full_query_i($query);
        $colstart = "<div class=\"dummy-column\"><h2>Services/Products</h2>";
        while ($data = mysqli_fetch_array($result)) {
            $productid = $data['id'];
            $userid = $data['userid'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];

            if ($companyname) {
                $companyname = " (" . $companyname . ")";
            }

            $description = $data['description'];
            $productname = $data['name'];

            if (!$description) {
                $description = "No Domain";
            }

            $status = $data['servicestatus'];
            $tempmatches .= "<a class=\"dummy-media-object\" href=\"clientshosting.php?userid=" . $userid . "&id=" . $productid . "\"><h3>" . $productname . " - " . $description . "</h3> <span class=\"label " . strtolower($status) . ("\">" . $status . "</span><br /><span class=\"desc\">" . $firstname . " " . $lastname . $companyname . " #" . $userid . "</span></a>");
        }


        if ($tempmatches) {
            $matches .= $colstart . $tempmatches . "</div>";
        }
    }






    if (is_numeric($value)) {
        if (checkPermission("List Invoices", true) || checkPermission("Manage Invoice", true)) {
            $query = "SELECT ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_bills.id,ra_bills.userid,ra_bills.status FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.id like '" . $value . "%' LIMIT 5";
            $result = full_query_i($query);
            $colstart = "<div class=\"dummy-column\"><h2>Invoice/Products</h2>";
            while ($data = mysqli_fetch_array($result)) {
                $invoiceid = $data['id'];
                $userid = $data['userid'];
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];
                $companyname = $data['companyname'];
                $status = $data['status'];

                if ($companyname) {
                    $companyname = " (" . $companyname . ")";
                }

                $id = $data['id'];
                $invoicematches .= "<a href=\"invoices.php?action=edit&id=" . $invoiceid . "\"><h3>Invoice #" . $id . "</h3> <span class=\"label " . strtolower($status) . ("\">" . $status . "</span><br><span class=\"desc\">" . $firstname . " " . $lastname . $companyname . " #" . $userid . "</span></a>");
            }
        }
        if (checkPermission("View Orders", true) || checkPermission("Create Upgrade/Downgrade Orders", true)) {
            $query = "SELECT ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_orders.id,ra_orders.userid,ra_orders.status FROM ra_orders INNER JOIN ra_user ON ra_user.id=ra_orders.userid WHERE ra_orders.id LIKE '" . $value . "%' LIMIT 5";
            $result = full_query_i($query);
            $ocolstart = "<div class=\"dummy-column\"><h2>Orders</h2>";
            while ($data = mysqli_fetch_array($result)) {
                $orderid = $data['id'];
                $userid = $data['userid'];
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];
                $companyname = $data['companyname'];
                $status = $data['status'];

                if ($companyname) {
                    $companyname = " (" . $companyname . ")";
                }

                $id = $data['id'];
                $ordermatches .= "<a class=\"dummy-media-object\" href=\"orders.php?action=view&id=" . $orderid . "\"><h3>Order #" . $id . "</h3> <span class=\"label " . strtolower($status) . ("\">" . $status . "</span><br><span class=\"desc\">" . $firstname . " " . $lastname . $companyname . " #" . $userid . "</span></a>");
            }
        }
    }


    if (checkPermission("List Support Tickets", true) || checkPermission("View Support Ticket", true)) {
        $query = "SELECT id,tid,title FROM ra_ticket WHERE ra_ticket.tid='" . $value . "' OR ra_ticket.title LIKE '%" . $value . "%' ORDER BY lastreply DESC LIMIT 0,10";
        $result = full_query_i($query);
        $tcolstart = "<div class=\"dummy-column\"><h2>Support Tickets</h2>";
        while ($data = mysqli_fetch_array($result)) {
            $ticketid = $data['id'];
            $tid = $data['tid'];
            $title = $data['title'];
            $ticketmatches .= "<a class=\"dummy-media-object\" href=\"supporttickets.php?action=viewticket&id=" . $ticketid . "\"><h3>Ticket #" . $tid . "</h3><br /><span class=\"desc\">" . $title . "</span></a>";
        }
    }


    if (checkPermission("List Invoices", true) || checkPermission("Manage Invoice", true)) {
        $query = "SELECT ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_bills.id,ra_bills.userid,ra_bills.status FROM ra_bills INNER JOIN ra_user ON ra_user.id=ra_bills.userid WHERE ra_bills.invoicenum='" . $value . "'";
        $result = full_query_i($query);
        $icolstart = "<div class=\"dummy-column\"><h2>Invoices</h2>";
        while ($data = mysqli_fetch_array($result)) {
            $invoiceid = $data['id'];
            $userid = $data['userid'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];
            $status = $data['status'];

            if ($companyname) {
                $companyname = " (" . $companyname . ")";
            }

            $id = $data['id'];
            $invoicematches .= "<a class=\"dummy-media-object\" href=\"invoices.php?action=edit&id=" . $invoiceid . "\"><h3>Invoice #" . $id . "</h3> <span class=\"label " . strtolower($status) . ("\">" . $status . "</span><br><span class=\"desc\">" . $firstname . " " . $lastname . $companyname . " #" . $userid . "</span></a>");
        }
    }


    if ($invoicematches) {
        $matches .= $icolstart . $invoicematches . "</div>";
    }
    if ($ordermatches) {
        $matches .= $ocolstart . $ordermatches . "</div>";
    }

    if ($ticketmatches) {
        $matches .= $tcolstart . $ticketmatches . "</div>";
    }


    if (!$matches) {
        $matches = "<h2>No Matches Found!</h2>";
    }

    echo $matches;
    exit();
}


if ($clientsearch || $ticketclientsearch) {
    if ($clientsearch) {
        if (!checkPermission("List Clients", true)) {
            exit("Access Denied");
        }
    }


    if ($ticketclientsearch) {
        if (!checkPermission("List Support Tickets", true)) {
            exit("Access Denied");
        }
    }

    $value = trim($_POST['value']);

    if (strlen($value) < 3 || is_numeric($value)) {
        exit();
    }

    $value = db_escape_string($value);
    $tempmatches = "";
    $query = "SELECT id,firstname,lastname,companyname,email FROM ra_user WHERE concat(firstname,' ',lastname) LIKE '%" . $value . "%' OR companyname LIKE '%" . $value . "%' OR email LIKE '%" . $value . "%' LIMIT 0,5";
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $userid = $data['id'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $companyname = $data['companyname'];
        $email = $data['email'];

        if ($companyname) {
            $companyname = " (" . $companyname . ")";
        }

        $tempmatches .= "<div <a href=\"#\" onclick=\"searchselectclient('" . $userid . "','" . addslashes($firstname . " " . $lastname . $companyname) . "','" . addslashes($email) . ("');return false\"><h3>" . $firstname . " " . $lastname . $companyname . "</h3> #" . $userid . "<br /><span class=\"desc\">" . $email . "</span></a></div>");
    }


    if ($tempmatches) {
        $matches .= "<div class=\"searchresultheader\">Search Results</div>" . $tempmatches;
    }



    if (!$matches) {
        $matches = "<div class=\"searchresultheader\">No Matches Found!</div>";
    }

    echo $matches;
    exit();
}

$searchin = "";

if ($type == "clients") {
    if ($field == "ID" || $field == "Client ID") {
        $searchin = "userid";
    } elseif (($field == "First Name" || $field == "Last Name") || $field == "Client Name") {
        $searchin = "clientname";
    } elseif ($field == "Company Name") {
        $searchin = "companyname";
    } elseif ($field == "Email Address") {
        $searchin = "email";
    } elseif ($field == "Address 1") {
        $searchin = "address";
    } elseif ($field == "Address 2") {
        $searchin = "address";
    } elseif ($field == "City") {
        $searchin = "address";
    } elseif ($field == "State") {
        $searchin = "address";
    } elseif ($field == "Postcode") {
        $searchin = "address";
    } elseif ($field == "Country") {
        $searchin = "country";
    } elseif ($field == "Phone Number") {
        $searchin = "phonenumber";
    } elseif ($field == "CC Last Four") {
        $searchin = "cardlastfour";
    } else {
        
    }

    redir("" . $searchin . "=" . $q, "clients.php");
    return 1;
}


if ($type == "orders") {
    if ($field == "Order ID") {
        $searchin = "orderid";
    } else {
        if ($field == "Order #") {
            $searchin = "ordernum";
        } else {
            if ($field == "Order Date") {
                $searchin = "orderdate";
            } else {
                if ($field == "Client Name") {
                    $searchin = "clientname";
                } else {
                    if ($field == "Amount") {
                        $searchin = "amount";
                    }
                }
            }
        }
    }

    redir("" . $searchin . "=" . $q, "orders.php");
    return 1;
}


if ($type == "services") {
    if ($field == "ID" || $field == "Service ID") {
        $searchin = "id";
    } else {
        if ($field == "Domain") {
            $searchin = "description";
        } else {
            if ($field == "Client Name") {
                $searchin = "clientname";
            } else {
                if ($field == "Package" || $field == "Product") {
                    $searchin = "packagesearch";
                } else {
                    if ($field == "Billing Cycle") {
                        $searchin = "billingcycle";
                    } else {
                        if ($field == "Status") {
                            $searchin = "status";
                        } else {
                            if ($field == "Username") {
                                $searchin = "username";
                            } else {
                                if ($field == "Dedicated IP") {
                                    $searchin = "dedicatedip";
                                } else {
                                    if ($field == "Assigned IPs") {
                                        $searchin = "assignedips";
                                    } else {
                                        if ($field == "Subscription ID") {
                                            $searchin = "subscriptionid";
                                        } else {
                                            if ($field == "Notes") {
                                                $searchin = "notes";
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

    redir("" . $searchin . "=" . $q, "clientshostinglist.php");
    return 1;
}


if ($type == "descriptions") {
    if ($field == "ID" || $field == "Domain ID") {
        $searchin = "id";
    } else {
        if ($field == "Domain") {
            $searchin = "description";
        } else {
            if ($field == "Client Name") {
                $searchin = "clientname";
            } else {
                if ($field == "Registrar") {
                    $searchin = "registrar";
                } else {
                    if ($field == "Status") {
                        $searchin = "status";
                    } else {
                        if ($field == "Subscription ID") {
                            $searchin = "subscriptionid";
                        } else {
                            if ($field == "Notes") {
                                $searchin = "notes";
                            }
                        }
                    }
                }
            }
        }
    }

    redir("" . $searchin . "=" . $q, "clientsdescriptionlist.php");
    return 1;
}


if ($type == "invoices") {
    if ($field == "Invoice #") {
        $searchin = "invoicenum";
    } else {
        if ($field == "Client Name") {
            $searchin = "clientname";
        } else {
            if ($field == "Line Item") {
                $searchin = "lineitem";
            } else {
                if ($field == "Invoice Date") {
                    $searchin = "invoicedate";
                } else {
                    if ($field == "Due Date") {
                        $searchin = "duedate";
                    } else {
                        if ($field == "Date Paid") {
                            $searchin = "datepaid";
                        } else {
                            if ($field == "Total Due") {
                                redir("totalfrom=" . $q . "&totalto=" . $q, "invoices.php");
                            } else {
                                if ($field == "Status") {
                                    $searchin = "status";
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    redir("" . $searchin . "=" . $q, "invoices.php");
    return 1;
}


if ($type == "tickets") {
    if ($field == "Ticket #") {
        $searchin = "ticketid";
    } else {
        if ($field == "Tag") {
            $searchin = "tag";
        } else {
            if ($field == "Subject") {
                $searchin = "subject";
            } else {
                if ($field == "Email Address") {
                    $searchin = "email";
                } else {
                    if ($field == "Client Name") {
                        $searchin = "clientname";
                    }
                }
            }
        }
    }

    redir("" . $searchin . "=" . $q, "supporttickets.php");
}
?>