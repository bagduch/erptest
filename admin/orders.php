<?php

define("ADMINAREA", true);
require "../init.php";
$action = $ra->get_req_var("action");

if ($action == "view") {
    $reqperm = "View Order Details";
} else {
    $reqperm = "View Orders";
}

$aInt = new RA_Admin($reqperm);
$aInt->title = $aInt->lang("orders", "manage");
$aInt->sidebar = "orders";
$aInt->icon = "orders";
//$aInt->helplink = "Order Management";
$aInt->requiredFiles(array("gatewayfunctions", "orderfunctions", "modulefunctions", "invoicefunctions", "processinvoices", "clientfunctions", "ccfunctions", "fraudfunctions"));

if ($ra->get_req_var("rerunfraudcheck")) {
    check_token("RA.admin.default");
    $result = select_query_i("tblorders", "id,userid,ipaddress", array("id" => $orderid));
    $data = mysqli_fetch_array($result);
    $orderid = $data['id'];
    $userid = $data['userid'];
    $ipaddress = $data['ipaddress'];
    $fraudmodule = "maxmind";
    $results = runFraudCheck($orderid, $fraudmodule, $userid, $ipaddress);
    $fraudoutput = $results['fraudoutput'];
    $fraudresults = getResultsArray($fraudoutput);

    if ($fraudresults) {
        echo "<div id=\"fraudresults\"><table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\"><tr>";
        $i = 0;
        foreach ($fraudresults as $key => $value) {
            ++$i;
            echo "<td class=\"fieldlabel\" width=\"30%\">" . $key . "</td><td class=\"fieldarea\"";

            if ($key == "Explanation") {
                echo " colspan=\"3\"";
                $i = 2;
            } else {
                echo " width=\"20%\"";
            }

            echo ">" . $value . "</td>";

            if ($i == "2") {
                echo "</tr><tr>";
                $i = 0;
                continue;
            }
        }
    }

    exit();
}


if ($action == "affassign") {
    if ($orderid && $affid) {
        $result = select_query_i("tblcustomerservices", "id", array("orderid" => $orderid));

        while ($data = mysqli_fetch_array($result)) {
            $serviceid = $data['id'];
            insert_query("tblaffiliatesaccounts", array("affiliateid" => $affid, "relid" => $serviceid));
        }

        exit();
    }

    echo $aInt->lang("orders", "chooseaffiliate") . "<br /><select name=\"affid\" id=\"affid\" style=\"width:270px;\">";
    $result = select_query_i("tblaffiliates", "tblaffiliates.id,tblclients.firstname,tblclients.lastname", "", "firstname", "ASC", "", "tblclients ON tblclients.id=tblaffiliates.clientid");

    while ($data = mysqli_fetch_array($result)) {
        $aff_id = $data['id'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        echo "<option value=\"" . $aff_id . "\">" . $firstname . " " . $lastname . "</option>";
    }

    echo "</select>";
    exit();
}


if ($action == "ajaxchangeorderstatus") {
    check_token("RA.admin.default");
    $id = get_query_val("tblorders", "id", array("id" => $id));
    $result = select_query_i("tblorderstatuses", "title", "", "sortorder", "ASC");

    while ($data = mysqli_fetch_array($result)) {
        $statusesarr[] = $data['title'];
    }


    if (in_array($status, $statusesarr) && $id) {
        update_query("tblorders", array("status" => $status), array("id" => $id));
        echo $id;
    } else {
        echo 0;
    }

    exit();
}

$filters = new RA_Filter();

if ($action == "delete" && $id) {
    check_token("RA.admin.default");
    checkPermission("Delete Order");
    deleteOrder($id);
    $filters->redir();
}


if ($ra->get_req_var("massaccept")) {
    check_token("RA.admin.default");
    checkPermission("View Order Details");

    if (is_array($selectedorders)) {
        foreach ($selectedorders as $orderid) {
            acceptOrder($orderid);
        }
    }

    $filters->redir();
}


if ($ra->get_req_var("masscancel")) {
    check_token("RA.admin.default");
    checkPermission("View Order Details");

    if (is_array($selectedorders)) {
        foreach ($selectedorders as $orderid) {
            changeOrderStatus($orderid, "Cancelled");
        }
    }

    $filters->redir();
}


if ($ra->get_req_var("massdelete")) {
    check_token("RA.admin.default");
    checkPermission("Delete Order");

    if (is_array($selectedorders)) {
        foreach ($selectedorders as $orderid) {
            deleteOrder($orderid);
        }
    }

    $filters->redir();
}


if ($ra->get_req_var("sendmessage")) {
    check_token("RA.admin.default");
    $clientslist = "";
    $result = select_query_i("tblorders", "DISTINCT userid", "id IN (" . db_build_in_array($selectedorders) . ")");

    while ($data = mysqli_fetch_array($result)) {
        $clientslist .= "selectedclients[]=" . $data['userid'] . "&";
    }

    redir("type=general&multiple=true&" . substr($clientslist, 0, 0 - 1), "sendmessage.php");
}


if (!$action) {
    releaseSession();
    $clients = $aInt->clientsDropDown($clientid, "", "clientid", true);
    $client = $filters->get("client");
    $clientid = $filters->get("clientid");

    if (!$clientid && $client) {
        $clientid = $client;
    }

    $clientname = $filters->get("clientname");
    $ordernum = $filters->get("ordernum");
    $orderid = $filters->get("orderid");
    $orderdate = $filters->get("orderdate");
    $paymentstatus = $filters->get("paymentstatus");
    $orderip = $filters->get("orderip");
    $amount = $filters->get("amount");
    $status = $filters->get("status");
    $filters->store();
    $deletejs = $aInt->deleteJSConfirm("doDelete", "orders", "confirmdelete", "orders.php?action=delete&id=");
    $name = "orders";
    $orderby = "id";
    $sort = "DESC";
    $pageObj = new RA_Pagination($name, $orderby, $sort);
    $pageObj->digestCookieData();
    $tbl = new RA_ListTable($pageObj);
    $tbl->setColumns(
            array("checkall",
                array("id", $aInt->lang("fields", "id")),
                array("ordernum", $aInt->lang("fields", "ordernum")),
                array("date", $aInt->lang("fields", "date")),
                $aInt->lang("fields", "clientname"),
                array("paymentmethod", $aInt->lang("fields", "paymentmethod")),
                array("amount", $aInt->lang("fields", "total")),
                array("status", "Order Status"),
                $aInt->lang("fields", "paymentstatus"),
//			array("status", $aInt->lang("fields", "status")), 
                ""
            )
    );
    $criteria = array("clientid" => $clientid, "amount" => $amount, "orderid" => $orderid, "ordernum" => $ordernum, "orderip" => $orderip, "orderdate" => $orderdate, "clientname" => $clientname, "status" => $status, "paymentstatus" => $paymentstatus);
    $ordersModel = new RA_Orders($pageObj);
    $ordersModel->execute($criteria);
    $numresults = $pageObj->getNumResults();

    if ($filters->isActive() && $numresults == 1) {
        $order = $pageObj->getOne();
        redir("action=view&id=" . $order['id']);
    } else {
        $orderlist = $pageObj->getData();
        foreach ($orderlist as $order) {
            $tbl->addRow(array(
                "<input type=\"checkbox\" name=\"selectedorders[]\" value=\"" . $order['id'] . "\" class=\"checkall\">",
                "<a href=\"" . $PHP_SELF . "?action=view&id=" . $order['id'] . "\"><b>" . $order['id'] . "</b></a>", $order['ordernum'],
                $order['date'], $order['clientname'],
                $order['paymentmethod'],
                $order['amount'],
                $order['statusformatted'],
                $order['paymentstatusformatted'],
                "<a href=\"#\" onClick=\"doDelete('" . $order['id'] . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete\"></a>")
            );
        }

        $table = $tbl->output();
    }
    $aInt->template = "order/view";
} else {
    if ($action == "view") {
        if ($ra->get_req_var("activate")) {
            check_token("RA.admin.default");
            $errors = acceptOrder($id, $vars);
            wSetCookie("OrderAccept", $errors);
            redir("action=view&id=" . $id . "&activated=true");
            exit();
        }
        if ($ra->get_req_var("cancel")) {
            check_token("RA.admin.default");
            changeOrderStatus($id, "Cancelled");
            redir("action=view&id=" . $id . "&cancelled=true");
            exit();
        }
        if ($ra->get_req_var("fraud")) {
            check_token("RA.admin.default");
            changeOrderStatus($id, "Fraud");
            redir("action=view&id=" . $id . "&frauded=true");
            exit();
        }
        if ($ra->get_req_var("pending")) {
            check_token("RA.admin.default");
            changeOrderStatus($id, "Pending");
            redir("action=view&id=" . $id . "&backpending=true");
            exit();
        }
        if ($ra->get_req_var("cancelrefund")) {
            check_token("RA.admin.default");
            checkPermission("Refund Invoice Payments");
            $error = cancelRefundOrder($id);
            redir("action=view&id=" . $id . "&cancelledrefunded=true&error=" . $error);
            exit();
        }
        if ($ra->get_req_var("activated") && isset($_COOKIE['RAOrderAccept'])) {
            $errors = wGetCookie("OrderAccept", 1);
            wDelCookie("OrderAccept");

            if (count($errors)) {
                infoBox($aInt->lang("orders", "statusaccepterror"), implode("<br>", $errors), "error");
            } else {
                infoBox($aInt->lang("orders", "statusaccept"), $aInt->lang("orders", "statusacceptmsg"), "success");
            }
        }
        if ($ra->get_req_var("cancelled")) {
            infoBox($aInt->lang("orders", "statuscancelled"), $aInt->lang("orders", "statuschangemsg"));
        }
        if ($ra->get_req_var("frauded")) {
            infoBox($aInt->lang("orders", "statusfraud"), $aInt->lang("orders", "statuschangemsg"));
        }
        if ($ra->get_req_var("backpending")) {
            infoBox($aInt->lang("orders", "statuspending"), $aInt->lang("orders", "statuschangemsg"));
        }
        if ($ra->get_req_var("cancelledrefunded")) {
            $error = $ra->get_req_var("error");

            if ($error == "noinvoice") {
                infoBox($aInt->lang("orders", "statusrefundfailed"), $aInt->lang("orders", "statusrefundnoinvoice"), "error");
            } else {
                if ($error == "notpaid") {
                    infoBox($aInt->lang("orders", "statusrefundfailed"), $aInt->lang("orders", "statusrefundnotpaid"), "error");
                } else {
                    if ($error == "alreadyrefunded") {
                        infoBox($aInt->lang("orders", "statusrefundfailed"), $aInt->lang("orders", "statusrefundalready"), "error");
                    } else {
                        if ($error == "refundfailed") {
                            infoBox($aInt->lang("orders", "statusrefundfailed"), $aInt->lang("orders", "statusrefundfailedmsg"), "error");
                        } else {
                            if ($error == "manual") {
                                infoBox($aInt->lang("orders", "statusrefundfailed"), $aInt->lang("orders", "statusrefundnoauto"), "error");
                            } else {
                                infoBox($aInt->lang("orders", "statusrefundsuccess"), $aInt->lang("orders", "statusrefundsuccessmsg"), "success");
                            }
                        }
                    }
                }
            }
        }
        if ($ra->get_req_var("updatenotes")) {
            check_token("RA.admin.default");
            update_query("tblorders", array("notes" => $notes), array("id" => $id));
            exit();
        }


        $gatewaysarray = getGatewaysArray();
        require ROOTDIR . "/includes/countries.php";
        $result = select_query_i("tblorders", "tblorders.*,tblclients.firstname,tblclients.lastname,tblclients.email,tblclients.companyname,tblclients.address1,tblclients.address2,tblclients.city,tblclients.state,tblclients.postcode,tblclients.country,tblclients.groupid,(SELECT status FROM tblinvoices WHERE id=tblorders.invoiceid) AS invoicestatus", array("tblorders.id" => $id), "", "", "", "tblclients ON tblclients.id=tblorders.userid");
        $orderdata = mysqli_fetch_array($result);
        $orderdata['amount'] = formatCurrency($orderdata['amount']);
        $orderdata['country'] = $countries[$orderdata['country']];
        $aInt->assign("orderdata", $orderdata);
        $id = $orderdata['id'];

        if (!$id) {
            exit("Order not found... Exiting...");
        }


        $statusoptions = "<select id=\"ajaxchangeorderstatus\" style=\"font-size:14px;\">";
        $result = select_query_i("tblorderstatuses", "", "", "sortorder", "ASC");
        while ($data = mysqli_fetch_array($result)) {
            $statusoptions .= "<option style=\"color:" . $data['color'] . "\"";

            if ($orderstatus == $data['title']) {
                $statusoptions .= " selected";
            }

            $statusoptions .= ">" . ($aInt->lang("status", strtolower($data['title'])) ? $aInt->lang("status", strtolower($data['title'])) : $data['title']) . "</option>";
        }

        $statusoptions .= "</select>&nbsp;<span id=\"orderstatusupdated\" style=\"display:none;padding-top:14px;\"><img src=\"images/icons/tick.png\" /></span>";
        // $orderdata = unserialize($orderdata);
        if ($orderdata['invoiceid'] == "0") {
            $paymentstatus = "<span class=\"textgreen\">" . $aInt->lang("orders", "noinvoicedue") . "</span>";
        } else {
            if (!$orderdata['invoicestatus']) {
                $paymentstatus = "<span class=\"textred\">Invoice Deleted</span>";
            } else {
                if ($orderdata['invoicestatus'] == "Paid") {
                    $paymentstatus = "<span class=\"textgreen\">" . $aInt->lang("status", "complete") . "</span>";
                } else {
                    if ($orderdata['invoicestatus'] == "Unpaid") {
                        $paymentstatus = "<span class=\"textred\">" . $aInt->lang("status", "incomplete") . "</span>";
                    } else {
                        $paymentstatus = getInvoiceStatusColour($orderdata['invoicestatus']);
                    }
                }
            }
        }
        run_hook("ViewOrderDetailsPage", array("orderid" => $id, "ordernum" => $ordernum, "userid" => $userid, "amount" => $amount, "paymentmethod" => $paymentmethod, "invoiceid" => $invoiceid, "status" => $orderstatus));
        $clientnotes = array();
        $result = select_query_i("tblnotes", "tblnotes.*,(SELECT CONCAT(firstname,' ',lastname) FROM tbladmins WHERE tbladmins.id=tblnotes.adminid) AS adminuser", array("userid" => $userid, "sticky" => "1"), "modified", "DESC");

        while ($data = mysqli_fetch_assoc($result)) {
            $data['created'] = fromMySQLDate($data['created'], 1);
            $data['modified'] = fromMySQLDate($data['modified'], 1);
            $data['note'] = autoHyperLink(nl2br($data['note']));
            $clientnotes[] = $data;
        }


        if (count($clientnotes)) {
            $notes = "<div id=\"clientsimportantnotes\">
";
            foreach ($clientnotes as $note) {
                $notes.= "<div class=\"ticketstaffnotes\">
    <table class=\"ticketstaffnotestable\">
        <tr>
            <td>" . $note['adminuser'] . "</td>
            <td align=\"right\">" . $note['modified'] . "</td>
        </tr>
    </table>
    <div>
        " . $note['note'] . "
        <div style=\"float:right;\"><a href=\"clientsnotes.php?userid=" . $userid . "&action=edit&id=" . $note['id'] . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" /></a></div>
    </div>
</div>
";
            }

            $notes.= "</div>";
        }




        if ($promocode) {
            if (strpos($promotype, "Percentage")) {
                $promocodetext.= $promocode . " - " . $promovalue . "% " . str_replace("Percentage", "", $promotype);
            } else {
                $promocodetext.= $promocode . " - " . formatCurrency($promovalue) . " " . str_replace("Fixed Amount", "", $promotype);
            }
        } else {
            $promocodetext = "None";
        }


        if (array_key_exists("bundleids", $orderdata) && is_array($orderdata['bundleids'])) {
            foreach ($orderdata['bundleids'] as $bid) {
                $bundlename = get_query_val("tblbundles", "name", array("id" => $bid));

                if (!$bundlename) {
                    $bundlename = "Bundle Has Been Deleted";
                }

                echo "Bundle ID " . $bid . " - " . $bundlename . "<br />";
            }
        } else {
            if (!$promocode) {
                $promocodetext = "None";
            }
        }


        $result = select_query_i("tblcustomerservices", "id", array("orderid" => $id));
        $data = mysqli_fetch_array($result);

        $firstproductinorder = $data['id'];
        $result = select_query_i("tblaffiliatesaccounts", "", array("relid" => $firstproductinorder));
        $data = mysqli_fetch_array($result);
        $affid = $data['affiliateid'];

        if ($affid) {
            $result = select_query_i("tblaffiliates", "tblaffiliates.id,firstname,lastname", array("tblaffiliates.id" => $affid), "", "", "", "tblclients ON tblclients.id=tblaffiliates.clientid");
            $data = mysqli_fetch_array($result);
        }


        $result = select_query_i("tblcustomerservices", "", array("orderid" => $id));

        $tblcustomerservices = array();
        while ($data = mysqli_fetch_assoc($result)) {
            $tblcustomerservices[$data['id']] = $data;
            $hostingid = $data['id'];
            $description = $data['description'];
            $billingcycle = $data['billingcycle'];
            $hostingstatus = $data['servicestatus'];
            $firstpaymentamount = formatCurrency($data['firstpaymentamount']);
            $tblcustomerservices[$data['id']]['firstpaymentamount'] = formatCurrency($data['firstpaymentamount']);
            $recurringamount = $data['amount'];
            $packageid = $data['packageid'];
            $server = $data['server'];
            $regdate = $data['regdate'];
            $nextduedate = $data['nextduedate'];
            $serverusername = $data['username'];
            $serverpassword = decrypt($data['password']);


            if (!$serverusername) {
                $serverusername = createServerUsername($description);
            }


            if (!$serverpassword) {
                $serverpassword = createServerPassword();
            }

            $result2 = select_query_i("tblservices", "tblservices.name,tblservices.type,tblservices.welcomeemail,tblservices.autosetup,tblservices.servertype,tblservicegroups.name AS groupname", array("tblservices.id" => $packageid), "", "", "", "tblservicegroups ON tblservices.gid=tblservicegroups.id");
            $tblservicesdata = mysqli_fetch_assoc($result2);

            $tblcustomerservices[$data['id']]['services'] = $tblservicesdata;
            $groupname = $tblservicesdata['groupname'];
            $productname = $tblservicesdata['name'];
            $producttype = $tblservicesdata['type'];
            $welcomeemail = $tblservicesdata['welcomeemail'];
            $autosetup = $tblservicesdata['autosetup'];
            $servertype = $tblservicesdata['servertype'];



            if ($showpending && $hostingstatus == "Pending") {
                echo "<tr><td style=\"background-color:#EFF2F9;text-align:center;\" colspan=\"6\">";

                if ($servertype) {
                    echo "" . $aInt->lang("fields", "username") . ((": <input type=\"text\" name=\"vars[products][" . $hostingid . "]") . "[username]\" size=\"12\" value=\"" . $serverusername . "\"> ") . $aInt->lang("fields", "password") . ((": <input type=\"text\" name=\"vars[products][" . $hostingid . "]") . "[password]\" size=\"12\" value=\"" . $serverpassword . "\"> ") . $aInt->lang("fields", "server") . ((": <select name=\"vars[products][" . $hostingid . "]") . "[server]\" style=\"width:150px;\"><option value=\"\">None</option>");
                    $result2 = select_query_i("tblservers", "", array("type" => $servertype), "name", "ASC");

                    while ($data2 = mysqli_fetch_array($result2)) {
                        $serverid = $data2['id'];
                        $servername = $data2['name'];
                        $servermaxaccounts = $data2['maxaccounts'];
                        $result3 = select_query_i("tblcustomerservices", "", "server='" . $serverid . "' AND (servicestatus='Active' OR servicestatus='Suspended')");
                        $servernumaccounts = $result3["num_rows"];
                        echo "<option value=\"" . $serverid . "\"";

                        if ($serverid == $server) {
                            echo " selected";
                        }

                        echo ">" . $servername . " (" . $servernumaccounts . "/" . $servermaxaccounts . ")";
                    }

                    echo ("</select> <label><input type=\"checkbox\" name=\"vars[products][" . $hostingid . "]") . "[runcreate]\"";

                    if ($hostingstatus == "Pending" && $autosetup) {
                        echo " checked";
                    }

                    echo "> " . $aInt->lang("orders", "runmodule") . "</label> ";
                }

                echo ("<label><input type=\"checkbox\" name=\"vars[products][" . $hostingid . "]") . "[sendwelcome]\"";

                if ($hostingstatus == "Pending" && $welcomeemail) {
                    echo " checked";
                }

                echo "> " . $aInt->lang("orders", "sendwelcome") . "</label></td></tr>";
            }
        }



        if ($renewals) {
            $renewals = explode(",", $renewals);
            foreach ($renewals as $renewal) {
                $renewal = explode("=", $renewal);
                $descriptionid = $renewal[0];
                $registrationperiod = $renewal[1];
                $result = select_query_i("tbldescriptions", "", array("id" => $descriptionid));
                $data = mysqli_fetch_array($result);
                $descriptionid = $data['id'];
                $type = $data['type'];
                $description = $data['description'];
                $registrar = $data['registrar'];
                $status = $data['status'];
                $regdate = $data['registrationdate'];
                $nextduedate = $data['nextduedate'];
                $descriptionamount = formatCurrency($data['recurringamount']);
                $descriptionregistrar = $data['registrar'];
                $dnsmanagement = $data['dnsmanagement'];
                $emailforwarding = $data['emailforwarding'];
                $idprotection = $data['idprotection'];
                echo "<tr><td><a href=\"clientsdescriptions.php?userid=" . $userid . "&descriptionid=" . $descriptionid . "\"><b>" . $aInt->lang("fields", "description") . "</b></a></td><td>" . $aInt->lang("descriptions", "renewal") . (" - " . $description . "<br>");

                if ($dnsmanagement) {
                    echo " + " . $aInt->lang("descriptions", "dnsmanagement") . "<br>";
                }


                if ($emailforwarding) {
                    echo " + " . $aInt->lang("descriptions", "emailforwarding") . "<br>";
                }


                if ($idprotection) {
                    echo " + " . $aInt->lang("descriptions", "idprotection") . "<br>";
                }

                $regperiods = (1 < $registrationperiod ? "s" : "");
                echo "</td><td>" . $registrationperiod . " " . $aInt->lang("descriptions", "year" . $regperiods) . ("</td><td>" . $descriptionamount . "</td><td>") . $aInt->lang("status", strtolower($status)) . ("</td><td><b>" . $paymentstatus . "</td></tr>");

                if ($showpending) {
                    $checkstatus = (($registrar && !$CONFIG['AutoRenewDomainsonPayment']) ? " checked" : " disabled");
                    echo ("<tr><td style=\"background-color:#EFF2F9\" colspan=\"6\"><label><input type=\"checkbox\" name=\"vars[renewals][" . $descriptionid . "]") . "[sendregistrar]\"" . $checkstatus . ((" /> Send to Registrar</label> <label><input type=\"checkbox\" name=\"vars[renewals][" . $descriptionid . "]") . "[sendemail]\"") . $checkstatus . " /> Send Confirmation Email</label></td></tr>";
                    continue;
                }
            }
        }


        if (substr($promovalue, 0, 2) == "DR") {
            $descriptionid = substr($promovalue, 2);
            $result = select_query_i("tbldescriptions", "", array("id" => $descriptionid));
            $data = mysqli_fetch_array($result);
            $descriptionid = $data['id'];
            $type = $data['type'];
            $description = $data['description'];
            $registrar = $data['registrar'];
            $registrationperiod = $data['registrationperiod'];
            $status = $data['status'];
            $regdate = $data['registrationdate'];
            $nextduedate = $data['nextduedate'];
            $descriptionamount = formatCurrency($data['firstpaymentamount']);
            $descriptionregistrar = $data['registrar'];
            $dnsmanagement = $data['dnsmanagement'];
            $emailforwarding = $data['emailforwarding'];
            $idprotection = $data['idprotection'];
            echo "<tr><td><a href=\"clientsdescriptions.php?userid=" . $userid . "&descriptionid=" . $descriptionid . "\"><b>" . $aInt->lang("fields", "description") . "</b></a></td><td>" . $aInt->lang("descriptions", "renewal") . (" - " . $description . "<br>");

            if ($dnsmanagement) {
                echo " + " . $aInt->lang("descriptions", "dnsmanagement") . "<br>";
            }


            if ($emailforwarding) {
                echo " + " . $aInt->lang("descriptions", "emailforwarding") . "<br>";
            }


            if ($idprotection) {
                echo " + " . $aInt->lang("descriptions", "idprotection") . "<br>";
            }

            $regperiods = (1 < $registrationperiod ? "s" : "");
            echo "</td><td>" . $registrationperiod . " " . $aInt->lang("descriptions", "year" . $regperiods) . ("</td><td>" . $descriptionamount . "</td><td>") . $aInt->lang("status", strtolower($status)) . ("</td><td><b>" . $paymentstatus . "</td></tr>");

            if ($showpending) {
                echo ("<tr><td style=\"background-color:#EFF2F9\" colspan=\"6\"><label><input type=\"checkbox\" name=\"vars[descriptions][" . $descriptionid . "]") . "[sendregistrar]\"";

                if ($registrar && !$CONFIG['AutoRenewDomainsonPayment']) {
                    echo " checked";
                } else {
                    echo " disabled";
                }

                echo ("> Send to Registrar</label> <label><input type=\"checkbox\" name=\"vars[descriptions][" . $descriptionid . "]") . "[sendemail]\"";

                if ($registrar) {
                    echo " checked";
                } else {
                    echo " disabled";
                }

                echo "> Send Confirmation Email</label></td></tr>";
            }
        }



        if (trim($nameservers[0])) {
            echo "<p><b>" . $aInt->lang("orders", "nameservers") . "</b></p><p>";
            foreach ($nameservers as $key => $ns) {

                if (trim($ns)) {
                    echo $aInt->lang("descriptions", "nameserver") . " " . ($key + 1) . ": " . $ns . "<br />";
                    continue;
                }
            }

            echo "</p>";
        }

        echo "<div id=\"notesholder\"" . ($notes ? "" : " style=\"display:none\"") . "><p><b>" . $aInt->lang("orders", "notes") . "</b></p><p align=\"center\"><table align=\"center\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><textarea rows=\"4\" cols=\"100\" id=\"notes\">" . $notes . "</textarea></td><td>&nbsp;&nbsp; <input type=\"button\" value=\"Update/Save\" id=\"savenotesbtn\" /></td></tr></table></p></div>";

        if ($fraudmodule) {
            if (!isValidforPath($fraudmodule)) {
                exit("Invalid Fraud Module Name");
            }

            include "../modules/fraud/" . $fraudmodule . "/" . $fraudmodule . ".php";
            $fraudresults = getResultsArray($fraudoutput);

            if ($fraudresults) {
                if ($fraudmodule == "maxmind") {
                    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><p><b>" . $aInt->lang("orders", "fraudcheckresults") . "</b></p></td><td align=\"right\"><div id=\"rerunfraud\"><a href=\"#\">" . $aInt->lang("orders", "fraudcheckrerun") . "</a></div></td></tr></table><br />";
                } else {
                    "<p><b>" . $aInt->lang("orders", "fraudcheckresults") . "</b></p>";
                }

                echo "<div id=\"fraudresults\"><table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\"><tr>";
                $i = 0;
                foreach ($fraudresults as $key => $value) {
                    ++$i;
                    echo "<td class=\"fieldlabel\" width=\"30%\">" . $key . "</td><td class=\"fieldarea\"";

                    if ($key == "Explanation") {
                        echo " colspan=\"3\"";
                        $i = 2;
                    } else {
                        echo " width=\"20%\"";
                    }

                    echo ">" . $value . "</td>";

                    if ($i == "2") {
                        echo "</tr><tr>";
                        $i = 0;
                        continue;
                    }
                }

                echo "</tr></table></div>";
                $jquerycode .= "$(\"#rerunfraud\").click(function () {
    $(\"#rerunfraud\").html(\"<img src=\\\"../images/spinner.gif\\\" align=\\\"absmiddle\\\" /> Performing Check...\");
    $.post(\"orders.php\", { action: \"view\", rerunfraudcheck: \"true\", orderid: " . $id . ", token: \"" . generate_token("plain") . "\" },
    function(data){
        $(\"#fraudresults\").html(data);
        $(\"#rerunfraud\").html(\"Update Completed\");
    });
    return false;
});";
            }
        }
    }

    $jscode = "$(\"#rerunfraud\").click(function () {
    $(\"#rerunfraud\").html(\"<img src=\\\"../images/spinner.gif\\\" align=\\\"absmiddle\\\" /> Performing Check...\");
    $.post(\"orders.php\", { action: \"view\", rerunfraudcheck: \"true\", orderid: , token: \"\" },
    function(data){
        $(\"#fraudresults\").html(data);
        $(\"#rerunfraud\").html(\"Update Completed\");
    });
    return false;
});";
    $aInt->assign("jquerycode", "adc");
    $aInt->assign("notes", $notes);
    $aInt->assign("promocodetext", $promocodetext);
    $aInt->assign("statusoptions", $statusoptions);
    $aInt->assign("paymentstatus", $paymentstatus);

    $aInt->assign("tblcustomerservices", $tblcustomerservices);
    $aInt->template = "order/viewdetail";
}

//echo "<pre>", print_r($tblcustomerservices, 1), "</pre>";
$aInt->assign("infobox", $infobox);
$aInt->assign("PHP_SELF", $PHP_SELF);
$aInt->assign("token", get_token());
$aInt->assign("filterdata", RA_Cookie::get("FD", true));
$aInt->assign("clientdropdown", $clients);
$aInt->assign('table', $table);

$aInt->display();
?>
