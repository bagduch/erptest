<?php

/** RA - Version 0.1 **/
class RA_Orders extends RA_TableModel {

    private $orderid = 0;
    private $orderdata = null;
    private $statusoutputs = null;

    public function _execute($criteria = null) {
        return $this->getOrders($criteria);
    }

    public function getOrders($criteria = array()) {
        global $aInt;
        global $currency;

        $query = "FROM ra_orders INNER JOIN ra_user ON ra_user.id=ra_orders.userid LEFT JOIN ra_bills ON ra_bills.id=ra_orders.invoiceid";

        if ($criteria['paymentstatus']) {
            $query .= " INNER JOIN ra_bills ON ra_bills.id=ra_orders.invoiceid";
        }

        $filters = $this->buildCriteria($criteria);

        if (count($filters)) {
            $query .= " WHERE " . implode(" AND ", $filters);
        }

        $result = full_query_i("SELECT COUNT(ra_orders.id) " . $query);
        $data = mysqli_fetch_array($result);
        $this->getPageObj()->setNumResults($data[0]);
        $query .= " ORDER BY ra_orders." . $this->getPageObj()->getOrderBy() . " " . $this->getPageObj()->getSortDirection();
        $gateways = new RA_Gateways();
        $invoices = new RA_Invoices();
        $orders = array();
        $query = "SELECT ra_orders.*,ra_user.firstname,ra_user.lastname,ra_user.companyname,ra_user.groupid,ra_user.currency,ra_bills.status AS invoicestatus " . $query . " LIMIT " . $this->getQueryLimit();
        $result = full_query_i($query);

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $ordernum = $row['ordernum'];
            $userid = $row['userid'];
            $date = $row['date'];
            $amount = $row['amount'];
            $gateway = $row['paymentmethod'];
            $status = $row['status'];
            $invoiceid = $row['invoiceid'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $companyname = $row['companyname'];
            $groupid = $row['groupid'];
            $currency = $row['currency'];
            $ipaddress = $row['ipaddress'];
            $invoicestatus = $row['invoicestatus'];
            $date = fromMySQLDate($date, 1);
            $paymentmethod = $gateways->getDisplayName($gateway);
            $statusformatted = $this->formatStatus($status);

            if ($invoiceid == "0") {
                $paymentstatus = "<span class=\"textgreen\">" . $aInt->lang("orders", "noinvoicedue") . "</span>";
            } else {
                if (!$invoicestatus) {
                    $paymentstatus = "<span class=\"textred\">Invoice Deleted</span>";
                } else {
                    if ($invoicestatus == "Paid") {
                        $paymentstatus = "<span class=\"textgreen\">" . $aInt->lang("status", "complete") . "</span>";
                    } else {
                        if ($invoicestatus == "Unpaid") {
                            $paymentstatus = "<span class=\"textred\">" . $aInt->lang("status", "incomplete") . "</span>";
                        } else {
                            $paymentstatus = $invoices->formatStatus($invoicestatus);
                        }
                    }
                }
            }

            $currency = getCurrency("", $currency);
            $amount = formatCurrency($amount);
            $clientname = $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid);
            $orders[] = array("id" => $id, "ordernum" => $ordernum, "date" => $date, "clientname" => $clientname, "gateway" => $gateway, "paymentmethod" => $paymentmethod, "amount" => $amount, "paymentstatus" => strip_tags($paymentstatus), "paymentstatusformatted" => $paymentstatus, "status" => $status, "statusformatted" => $statusformatted);
        }

        return $orders;
    }

    private function buildCriteria($criteria) {
        $filters = array();

        if ($criteria['status']) {
            if (($criteria['status'] == "Pending" || $criteria['status'] == "Active") || $criteria['status'] == "Cancelled") {
                $statusfilter = "";
                $where = array("show" . strtolower($criteria['status']) => "1");
                $result = select_query_i("ra_orderstatuses", "title", $where);

                while ($data = mysqli_fetch_array($result)) {
                    $statusfilter .= "'" . $data[0] . "',";
                }

                $statusfilter = substr($statusfilter, 0, 0 - 1);
                $filters[] = "ra_orders.status IN (" . $statusfilter . ")";
            } else {
                $filters[] = "ra_orders.status='" . db_escape_string($criteria['status']) . "'";
            }
        }


        if ($criteria['clientid']) {
            $filters[] = "ra_orders.userid='" . db_escape_string($criteria['clientid']) . "'";
        }


        if ($criteria['amount']) {
            $filters[] = "ra_orders.amount='" . db_escape_string($criteria['amount']) . "'";
        }


        if ($criteria['orderid']) {
            $filters[] = "ra_orders.id='" . db_escape_string($criteria['orderid']) . "'";
        }


        if ($criteria['ordernum']) {
            $filters[] = "ra_orders.ordernum='" . db_escape_string($criteria['ordernum']) . "'";
        }


        if ($criteria['orderip']) {
            $filters[] = "ra_orders.ipaddress='" . db_escape_string($criteria['orderip']) . "'";
        }


        if ($criteria['orderdate']) {
            $tempdate = toMySQLDate(urldecode($criteria['orderdate']));
            $filters[] = "ra_orders.date LIKE '" . db_escape_string($tempdate) . "%'";
        }


        if ($criteria['clientname']) {
            $filters[] = "concat(firstname,' ',lastname) LIKE '%" . db_escape_string($criteria['clientname']) . "%'";
        }


        if ($criteria['paymentstatus']) {
            $filters[] = "ra_bills.status='" . db_escape_string($criteria['paymentstatus']) . "'";
        }

        return $filters;
    }

    public function getStatuses() {
        $statuses = array();
        $result = select_query_i("ra_orderstatuses", "title,color", "", "sortorder", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $statuses[$data['title']] = "<span style=\"color:" . $data['color'] . "\">" . $data['title'] . "</span>";
        }

        $this->statusoutputs = $statuses;
        return $statuses;
    }

    public function formatStatus($status) {
        if (!$this->statusoutputs) {
            $this->getStatuses();
        }

        return array_key_exists($status, $this->statusoutputs) ? $this->statusoutputs[$status] : $status;
    }

    public function setID($orderid) {
        $this->orderid = (int) $orderid;
        $data = $this->loadData();
        return is_array($data) ? true : false;
    }

    public function loadData() {
        $result = select_query_i("ra_orders", "", array("id" => $this->orderid));
        $this->orderdata = mysqli_fetch_assoc($result);
        return $this->orderdata;
    }

    public function getData($var = "") {
        if (is_array($this->orderdata) && $var) {
            return isset($this->orderdata[$var]) ? $this->orderdata[$var] : "";
        }
    }

    public function getFraudResults() {
        global $ra;

        $fraudmodule = $this->getData("fraudmodule");

        if ($fraudmodule) {
            if (!isValidforPath($fraudmodule)) {
                exit("Invalid Fraud Module Name");
            }

            include ROOTDIR . ("/modules/fraud/" . $fraudmodule . "/" . $fraudmodule . ".php");
            $fraudoutput = $this->getData("fraudoutput");
            $fraudresults = getResultsArray($fraudoutput);
            return $fraudresults;
        }

        return false;
    }

    public function delete($orderid = "") {
        if (!$orderid) {
            $orderid = $this->orderid;
        }

        $orderid = (int) $orderid;
        run_hook("DeleteOrder", array("orderid" => $orderid));
        $result = select_query_i("ra_orders", "userid,invoiceid", array("id" => $orderid));
        $data = mysqli_fetch_array($result);
        $userid = $data['userid'];
        $invoiceid = $data['invoiceid'];
        delete_query("tblhostingconfigoptions", "relid IN (SELECT id FROM tblhosting WHERE orderid=" . $orderid . ")");
        delete_query("ra_partnersaccounts", "relid IN (SELECT id FROM tblhosting WHERE orderid=" . $orderid . ")");
        delete_query("tblcustomerservices", array("orderid" => $orderid));
        delete_query("ra_catalog_user_sales_addons", array("orderid" => $orderid));
        delete_query("tbldomains", array("orderid" => $orderid));
        delete_query("ra_orders", array("id" => $orderid));
        delete_query("ra_bills", array("id" => $invoiceid));
        delete_query("ra_bill_lineitems", array("invoiceid" => $invoiceid));
        logActivity("Deleted Order - Order ID: " . $orderid, $userid);
        return true;
    }

    public function setCancelled($orderid = "") {
        if (!$orderid) {
            $orderid = $this->orderid;
        }

        return $this->changeStatus($orderid, "Cancelled");
    }

    public function setFraud($orderid = "") {
        if (!$orderid) {
            $orderid = $this->orderid;
        }

        return $this->changeStatus($orderid, "Fraud");
    }

    public function setPending($orderid = "") {
        if (!$orderid) {
            $orderid = $this->orderid;
        }

        return $this->changeStatus($orderid, "Pending");
    }

    private function changeStatus($orderid, $status) {
        if (!$orderid) {
            return false;
        }

        $orderid = (int) $orderid;

        if ($status == "Cancelled") {
            run_hook("CancelOrder", array("orderid" => $orderid));
        } else {
            if ($status == "Fraud") {
                run_hook("FraudOrder", array("orderid" => $orderid));
            } else {
                if ($status == "Pending") {
                    run_hook("PendingOrder", array("orderid" => $orderid));
                }
            }
        }

        update_query("ra_orders", array("status" => $status), array("id" => $orderid));

        if ($status == "Cancelled" || $status == "Fraud") {
            $result = select_query_i("tblcustomerservices", "tblcustomerservices.id,tblcustomerservices.servicestatus,ra_catalog.servertype,tblcustomerservices.packageid,ra_catalog.stockcontrol,ra_catalog.qty", array("orderid" => $orderid), "", "", "", "ra_catalog ON ra_catalog.id=tblcustomerservices.packageid");

            while ($data = mysqli_fetch_array($result)) {
                $productid = $data['id'];
                $prodstatus = $data['servicestatus'];
                $module = $data['servertype'];
                $packageid = $data['packageid'];
                $stockcontrol = $data['stockcontrol'];
                $qty = $data['qty'];

                if ($module && ($prodstatus == "Active" || $prodstatus == "Suspended")) {
                    logActivity("Running Module Terminate on Order Cancel");

                    if (!isValidforPath($module)) {
                        exit("Invalid Server Module Name");
                    }

                    require_once ROOTDIR . ("/modules/servers/" . $module . "/" . $module . ".php");
                    $moduleresult = ServerTerminateAccount($productid);

                    if ($moduleresult == "success") {
                        update_query("tblcustomerservices", array("servicestatus" => $status), array("id" => $productid));

                        if ($stockcontrol == "on") {
                            update_query("ra_catalog", array("qty" => "+1"), array("id" => $packageid));
                        }
                    }
                }

                update_query("tblcustomerservices", array("servicestatus" => $status), array("id" => $productid));

                if ($stockcontrol == "on") {
                    update_query("ra_catalog", array("qty" => "+1"), array("id" => $packageid));
                }
            }
        } else {
            update_query("tblcustomerservices", array("servicestatus" => $status), array("orderid" => $orderid));
        }

        update_query("ra_catalog_user_sales_addons", array("status" => $status), array("orderid" => $orderid));

        if ($status == "Pending") {
            $result = select_query_i("tbldomains", "id,type", array("orderid" => $orderid));

            while ($data = mysqli_fetch_assoc($result)) {
                if ($data['type'] == "Transfer") {
                    $status = "Pending Transfer";
                } else {
                    $status = "Pending";
                }

                update_query("tbldomains", array("status" => $status), array("id" => $data['id']));
            }
        } else {
            update_query("tbldomains", array("status" => $status), array("orderid" => $orderid));
        }

        $result = select_query_i("ra_orders", "userid,invoiceid", array("id" => $orderid));
        $data = mysqli_fetch_array($result);
        $userid = $data['userid'];
        $invoiceid = $data['invoiceid'];

        if ($status == "Pending") {
            update_query("ra_bills", array("status" => "Unpaid"), array("id" => $invoiceid, "status" => "Cancelled"));
        } else {
            update_query("ra_bills", array("status" => "Cancelled"), array("id" => $invoiceid, "status" => "Unpaid"));
            run_hook("InvoiceCancelled", array("invoiceid" => $invoiceid));
        }

        logActivity("Order Status set to " . $status . " - Order ID: " . $orderid, $userid);
    }

    public function getItems() {
        global $aInt;

        $orderid = $this->orderid;
        $items = array();
        $result = select_query_i("tblcustomerservices", "", array("orderid" => $orderid));

        while ($data = mysqli_fetch_array($result)) {
            $hostingid = $data['id'];
            $domain = $data['domain'];
            $billingcycle = $data['billingcycle'];
            $hostingstatus = $data['servicestatus'];
            $firstpaymentamount = formatCurrency($data['firstpaymentamount']);
            $recurringamount = $data['amount'];
            $packageid = $data['packageid'];
            $server = $data['server'];
            $regdate = $data['regdate'];
            $nextduedate = $data['nextduedate'];
            $serverusername = $data['username'];
            $serverpassword = decrypt($data['password']);
            $result2 = select_query_i("ra_catalog", "ra_catalog.name,ra_catalog.type,ra_catalog.welcomeemail,ra_catalog.autosetup,ra_catalog.servertype,ra_catalog_groups.name AS groupname", array("ra_catalog.id" => $packageid), "", "", "", "ra_catalog_groups ON ra_catalog.gid=ra_catalog_groups.id");
            $data = mysqli_fetch_array($result2);
            $groupname = $data['groupname'];
            $productname = $data['name'];
            $producttype = $data['type'];
            $welcomeemail = $data['welcomeemail'];
            $autosetup = $data['autosetup'];
            $servertype = $data['servertype'];

            if ($producttype == "hostingaccount") {
                $type = $aInt->lang("orders", "sharedhosting");
            } else {
                if ($producttype == "reselleraccount") {
                    $type = $aInt->lang("orders", "resellerhosting");
                } else {
                    if ($producttype == "server") {
                        $type = $aInt->lang("orders", "server");
                    } else {
                        if ($producttype == "other") {
                            $type = $aInt->lang("orders", "other");
                        }
                    }
                }
            }

            $items[] = array("type" => "product", "producttype" => $type, "description" => $groupname . " - " . $productname, "domain" => $domain, "billingcycle" => $aInt->lang("billingcycles", str_replace(array("-", "account", " "), "", strtolower($billingcycle))), "amount" => $firstpaymentamount, "paymentstatus" => $paymentstatus, "status" => $aInt->lang("status", strtolower($hostingstatus)));
        }

        $predefinedaddons = array();
        $result = select_query_i("tbladdons", "", "");

        while ($data = mysqli_fetch_array($result)) {
            $addon_id = $data['id'];
            $addon_name = $data['name'];
            $addon_welcomeemail = $data['welcomeemail'];
            $predefinedaddons[$addon_id] = array("name" => $addon_name, "welcomeemail" => $addon_welcomeemail);
        }

        $result = select_query_i("ra_catalog_user_sales_addons", "", array("orderid" => $orderid));

        while ($data = mysqli_fetch_array($result)) {
            $aid = $data['id'];
            $hostingid = $data['hostingid'];
            $addonid = $data['addonid'];
            $name = $data['name'];
            $billingcycle2 = $data['billingcycle'];
            $addonamount = $data['recurring'] + $data['setupfee'];
            $addonstatus = $data['status'];
            $regdate = $data['regdate'];
            $nextduedate = $data['nextduedate'];
            $addonamount = formatCurrency($addonamount);

            if (!$name) {
                $name = $predefinedaddons[$addonid]['name'];
            }

            $items[] = array("type" => "addon", "producttype" => $aInt->lang("orders", "addon"), "description" => $name, "domain" => "", "billingcycle" => $aInt->lang("billingcycles", str_replace(array("-", "account", " "), "", strtolower($billingcycle2))), "amount" => $addonamount, "paymentstatus" => $paymentstatus, "status" => $aInt->lang("status", strtolower($addonstatus)));
        }

        $result = select_query_i("tbldomains", "", array("orderid" => $orderid));

        while ($data = mysqli_fetch_array($result)) {
            $domainid = $data['id'];
            $type = $data['type'];
            $domain = $data['domain'];
            $registrationperiod = $data['registrationperiod'];
            $status = $data['status'];
            $regdate = $data['registrationdate'];
            $nextduedate = $data['nextduedate'];
            $domainamount = formatCurrency($data['firstpaymentamount']);
            $domainregistrar = $data['registrar'];
            $dnsmanagement = $data['dnsmanagement'];
            $emailforwarding = $data['emailforwarding'];
            $idprotection = $data['idprotection'];
            $type = $aInt->lang("domains", strtolower($type));

            if ($dnsmanagement) {
                $type .= " + " . $aInt->lang("domains", "dnsmanagement");
            }


            if ($emailforwarding) {
                $type .= " + " . $aInt->lang("domains", "emailforwarding");
            }


            if ($idprotection) {
                $type .= " + " . $aInt->lang("domains", "idprotection");
            }

            $items[] = array("type" => "domain", "producttype" => $aInt->lang("fields", "domain"), "description" => $type, "domain" => $domain, "billingcycle" => $registrationperiod . " " . $aInt->lang("domains", "year" . $regperiods), "amount" => $domainamount, "paymentstatus" => $paymentstatus, "status" => $aInt->lang("status", strtolower(str_replace(" ", "", $status))));
        }
        return $items;
    }

}

?>