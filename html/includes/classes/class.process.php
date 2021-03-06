<?php

class RA_Process {

    public $productdata = array();
    public $addondata = array();
    public $orderdata = array();
    public $customfield = array();
    public $session;
    public $infobox;
    public $currency;
    public $firstpayment = 0;
    public $oneoff = 0;
    public $recurring = 0;
    public $step = 0;
    public $config;
    public $gateway;
    public $taxrate;
    public $clientsdetails;

    public function __construct($session, $CONFIG) {

        if (isset($session['address']) && isset($session['fpid'])) {
            $this->session = $session;
            $this->config = $CONFIG;
            $this->currency = getCurrency();
            $this->clientsdetails = getClientsDetails($this->session['uid']);
            $this->gateway = getClientsPaymentMethod($this->session['uid']);
            $this->getProductDetail();
        } else {
            if (!isset($session['address'])) {
                $this->infobox['icon'] = "warning";
                $this->infobox['info'] = "Please enter Your address";
            } elseif (!isset($session['fpid'])) {
                $this->infobox['icon'] = "warning";
                $this->infobox['info'] = "Please choose a Service";
            } else {
                $this->infobox['icon'] = "warning";
                $this->infobox['info'] = "Please Enable Your Browser Session";
            }
        }
    }

    public function caculateTotal() {
        if (!isset($this->productdata['pricing'])) {
            $this->getProductDetail();
        } else {
            if ($this->productdata['pricing']['type'] == "onetime") {
                $this->oneoff +=$this->productdata['pricing']['rawpricing']['monthly'] + $this->productdata['pricing']['rawpricing']['msetupfee'];
            } else {
                if (isset($this->productdata['pricing']['rawpricing'][$this->productdata['pricing']['minprice']['cycle']])) {
                    $this->recurring += $this->productdata['pricing']['rawpricing'][$this->productdata['pricing']['minprice']['cycle']];
                }
                foreach ($this->productdata['pricing']['rawpricing'] as $title => $data) {
                    if (strpos($title, 'setup') !== false) {
                        $this->oneoff +=$data;
                    }
                }
            }
            if (!empty($this->productdata['avalialeaddons'])) {
                foreach ($this->productdata['avalialeaddons'] as $data) {
                    if ($data['select']) {

                        if ($data['price']['type'] == "onetime") {
                            $this->oneoff +=$data['price']['rawpricing']['msetupfee'] + $data['price']['rawpricing']['monthly'];
                        } else {
                            if (isset($data['price']['rawpricing'][$data['price']['type']])) {
                                $this->recurring +=$data['price']['rawpricing'][$data['price']['type']];
                            }
                            foreach ($data['price']['rawpricing'] as $title => $datas) {
                                if (strpos($title, 'setup') !== false) {
                                    $this->oneoff +=$data;
                                }
                            }
                        }
                    }
                }
            }

            $this->firstpayment = $this->recurring + $this->oneoff;
        }
    }

    public function addonAdd($addonid) {
        if (isset($this->productdata["avalialeaddons"][$addonid])) {
            $this->productdata["avalialeaddons"][$addonid]['select'] = 1;
            unset($_SESSION['avalialeaddons']);
            $_SESSION['avalialeaddons'] = $this->productdata["avalialeaddons"];
        }
    }

    public function removeAdd($addonid) {
        if (isset($this->productdata["avalialeaddons"][$addonid])) {
            $this->productdata["avalialeaddons"][$addonid]['select'] = 0;
            unset($_SESSION['avalialeaddons']);
            $_SESSION['avalialeaddons'] = $this->productdata["avalialeaddons"];
        }
    }

    public function getProductDetail() {

        if (isset($this->session['fpid'])) {
            $result = full_query_i("SELECT ra_catalog.*,ra_catalog_groups.name as groupname FROM ra_catalog LEFT JOIN ra_catalog_groups ON ra_catalog.gid = ra_catalog_groups.id where ra_catalog.id =" . $this->session['fpid']);
            $data = mysqli_fetch_assoc($result);
            if (!empty($data)) {


                $this->productdata["data"] = $data;
                $this->productdata["avalialeaddons"] = isset($this->session['avalialeaddons']) ? $this->session['avalialeaddons'] : $this->getAsscoiateService($data['id']);
                $this->productdata["customfield"] = getServiceCustomFields($data['id']);
                $this->productdata["pricing"] = getPricingInfo($data['id'], $inclconfigops = false, $upgrade = false, $this->currency);

                //  echo "<pre>", print_r($this->productdata["avalialeaddons"], 1), "</pre>";
                // $_SESSION['avalialeaddons'] = $this->productdata["avalialeaddons"];
                // $currency = getCurrency();
            }
        } else {
            $this->infobox['icon'] = "warning";
            $this->infobox['info'] = "Please Enable Your Browser Session";
        }
    }

    public function getAsscoiateService($pid) {

        $addons = array();

        $query = "select ra_catalog.* from ra_service2service LEFT JOIN ra_catalog on ra_service2service.children_id=ra_catalog.id where ra_service2service.parent_id=" . $pid;
        // echo $query;
        $result = full_query_i($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $addons[$data['id']] = $data;
            $addons[$data['id']]['price'] = getPricingInfo($data['id'], $inclconfigops = false, $upgrade = false, $this->currency);
            $addons[$data['id']]['customfield'] = getServiceCustomFields($data['id']);
            $addons[$data['id']]['checkbox'] = "<button class=\"btn btn-default btn-circle\" id=\"a" . $data['id'] . "\" data-addon=\"" . $data['id'] . "\"> <i class=\"\"></i></button>";
            $addons[$data['id']]['select'] = 0;
        }
        return $addons;
    }

    public function draftOrder() {
        global $ra;
        if ($this->checkdraftduplication()) {
            $order_number = generateUniqueID();
            $remote_ip = $ra->get_user_ip();
            $orderid = insert_query("ra_orders", array(
                "ordernum" => $order_number,
                "userid" => $this->session['uid'],
                "date" => "now()",
                "status" => "Draft",
                "paymentmethod" => "",
                "ipaddress" => $remote_ip,
                "notes" => "")
            );
            $_SESSION['orderid'] = $orderid;
            $this->draftSerivceAccoutn();
        }
    }

    public function createtInvoice() {
        if ($this->config['TaxEnabled'] == "on") {
            if (!$this->clientsdetails['taxexempt']) {
                $state = $this->clientsdetails['state'];
                $country = $this->clientsdetails['country'];
                $taxdata = getTaxRate(1, $state, $country);
                $taxdata2 = getTaxRate(2, $state, $country);
                $taxrate = $taxdata['rate'];
                $taxrate2 = $taxdata2['rate'];
            }
        } else {
            $taxrate = 0;
            $taxrate2 = 0;
        }
        $duedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $this->config['CreateInvoiceDaysBefore'], date("Y")));
        $invoice = array(
            "date" => "now()",
            "duedate" => $duedate,
            "userid" => $this->session['uid'],
            "status" => "Draft",
            "paymentmethod" => $this->gateway,
            "taxrate" => $taxrate,
            "taxrate2" => $taxrate2
        );

        $invoiceid = insert_query("ra_bills", $invoice);
        return $invoiceid;
    }

    public function draftSerivceAccoutn() {

        $hostingquerydates = date("Y-m-d");
        $duedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $this->config['CreateInvoiceDaysBefore'], date("Y")));
        $serviceid = insert_query("tblcustomerservices", array(
            "userid" => $this->session['uid'],
            "orderid" => $_SESSION['orderid'],
            "packageid" => $this->session['fpid'],
            "parent" => "",
            "regdate" => "now()",
            "description" => $this->session['address'],
            "paymentmethod" => $this->gateway,
            "firstpaymentamount" => $this->firstpayment,
            "amount" => $this->recurring,
            "billingcycle" => $this->productdata['pricing']['minprice']['cycle'],
            "nextduedate" => $hostingquerydates,
            "nextinvoicedate" => $hostingquerydates,
            "servicestatus" => "Draft",
            "lastupdate" => "now()",
            "notes" => "",
                )
        );
        $_SESSION['serviceid'] = $serviceid;
        $_SESSION['invoiceid'] = $this->createtInvoice();
        insert_query("ra_bill_lineitems", array(
            "invoiceid" => $_SESSION['invoiceid'],
            "userid" => $this->session['uid'],
            "type" => $this->productdata['data']['type'],
            "relid" => $serviceid,
            "description" => $this->productdata['data']['name'],
            "amount" => $this->productdata['pricing']['minprice']['value'],
            "taxed" => $this->productdata['data']['tax'],
            "duedate" => $duedate,
            "paymentmethod" => $this->gateway
                )
        );
    }

    public function pendingOrder() {

        if ($this->session['uid']) {
            update_query("tblcustomerservices", array('servicestatus' => 'Pending'), array("id" => $this->session['serviceid']));
            updateInvoiceTotal($this->session['invoiceid']);

            $result = select_query_i("ra_bill_lineitems", "SUM(amount) as total", array("invoiceid" => $this->session['invoiceid']));
            $data = mysqli_fetch_assoc($result);

            update_query("ra_bills", array('status' => 'Unpaid'), array("id" => $this->session['invoiceid']));
            update_query("ra_orders", array('status' => 'Pending', 'amount' => $data['total'], 'invoiceid' => $this->session['invoiceid']), array("id" => $this->session['orderid']));
        }
    }

    public function insertCustomefields($data) {
        $success_id = false;
        if (!empty($data) && !empty($this->session['avalialeaddons'])) {
            foreach ($data as $key => $value) {
                $success_id = insert_query("ra_catalog_user_sales_fieldsvalues", array("cfid" => $key, "relid" => $this->session['serviceid'], "value" => $value));
            }
        }
        return $success_id;
    }

    public function insertAddon() {

        $success_id = false;
        $duedate = date("Ymd", mktime(0, 0, 0, date("m"), date("d") + $this->config['CreateInvoiceDaysBefore'], date("Y")));
        if (!empty($this->productdata)) {
            foreach ($this->productdata['avalialeaddons'] as $data) {
                if ($data['select']) {
                    $hostingquerydates = date("Y-m-d");
                    $insertdata = array(
                        "userid" => $this->session['uid'],
                        "orderid" => $_SESSION['orderid'],
                        "packageid" => $data['id'],
                        "regdate" => "now()",
                        "description" => "",
                        "paymentmethod" => $this->gateway,
                        "firstpaymentamount" => $data['price']["rawpricing"]["msetupfee"] + $data['price']["rawpricing"]["monthly"],
                        "amount" => $data['price']['type'] == "recurring" ? $data['price']["rawpricing"]["monthly"] : 0,
                        "billingcycle" => $data['price']['type'],
                        "nextduedate" => $hostingquerydates,
                        "nextinvoicedate" => $hostingquerydates,
                        "servicestatus" => "Pending",
                        "lastupdate" => "now()",
                        "notes" => "",
                        "parent" => $_SESSION['serviceid']
                    );


                    $success_id = insert_query("tblcustomerservices", $insertdata);
                    insert_query("ra_bill_lineitems", array(
                        "invoiceid" => $_SESSION['invoiceid'],
                        "userid" => $this->session['uid'],
                        "type" => $data['type'],
                        "relid" => $success_id,
                        "description" => $data['name'],
                        "amount" => $data['price']['minprice']['value'],
                        "taxed" => $data['tax'],
                        "duedate" => $duedate,
                        "paymentmethod" => $this->gateway
                            )
                    );
                }
            }
        }
        return $success_id;
    }

    public function finishorder($data) {

        if ($this->insertAddon() && $this->insertCustomefields($data)) {
            // echo "<pre>", print_r($_SESSION, 1), "</pre>";
            // $this->createtInvoice();
            unset($_SESSION['avalialeaddons']);
            $this->step = 3;
        }
        $this->pendingOrder();
    }

    public function checkdraftduplication() {
        $query = "select * from tblcustomerservices where description like '" . $this->session['address'] . "'";
        $result = full_query_i($query);
        if ($result->num_rows == 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
