<?php

class RA_Process {

    public $productdata = array();
    public $addondata = array();
    public $orderdata = array();
    public $customefield = array();
    public $session;
    public $infobox;
    public $currecy;
    public $firstpayment = 0;
    public $oneoff = 0;
    public $recurring = 0;
    public $step = 0;

    public function __construct($session) {

        if (isset($session['address']) && isset($session['fpid'])) {
            $this->session = $session;
            $this->currecy = getCurrency();
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
            if (isset($this->productdata['pricing']['rawpricing'][$this->productdata['pricing']['minprice']['cycle']])) {
                $this->recurring += $this->productdata['pricing']['rawpricing'][$this->productdata['pricing']['minprice']['cycle']];
            }
            foreach ($this->productdata['pricing']['rawpricing'] as $title => $data) {
                if (strpos($title, 'setup') !== false) {
                    $this->oneoff +=$data;
                }
            }
            if (!empty($this->productdata['avalialeaddons'])) {
                foreach ($this->productdata['avalialeaddons'] as $data) {
                    if (isset($data['select'])) {

                        if ($data['cycle'] == "onetime") {
                            $this->oneoff += $data['value'];
                        } else {
                            $this->recurring +=$data['value'];
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
            $result = full_query_i("SELECT tblservices.*,tblservicegroups.name as groupname FROM tblservices LEFT JOIN tblservicegroups ON tblservices.gid = tblservicegroups.id where tblservices.id =" . $this->session['fpid']);
            $data = mysqli_fetch_assoc($result);
            if (!empty($data)) {

                $this->productdata["data"] = $data;
                $this->productdata["avalialeaddons"] = isset($this->session['avalialeaddons']) ? $this->session['avalialeaddons'] : getAddons($data['id'], array(), $this->currecy);
                $this->productdata["customefield"] = getServiceCustomFields($data['id']);
                $this->productdata["pricing"] = getPricingInfo($data['id'], $inclconfigops = false, $upgrade = false, $this->currecy);

                // $_SESSION['avalialeaddons'] = $this->productdata["avalialeaddons"];
                // $currecy = getCurrency();
            }
        } else {
            $this->infobox['icon'] = "warning";
            $this->infobox['info'] = "Please Enable Your Browser Session";
        }
    }

    public function draftOrder() {
        global $ra;
        if ($this->checkdraftduplication()) {
            $order_number = generateUniqueID();
            $remote_ip = $ra->get_user_ip();
            $orderid = insert_query("tblorders", array(
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

    public function draftSerivceAccoutn() {

        $hostingquerydates = date("Y-m-d");
        $serviceid = insert_query("tblcustomerservices", array(
            "userid" => $this->session['uid'],
            "orderid" => $_SESSION['orderid'],
            "packageid" => $this->session['fpid'],
            "regdate" => "now()",
            "description" => $this->session['address'],
            "paymentmethod" => "banktransfer",
            "firstpaymentamount" => $this->firstpayment,
            "amount" => $this->recurring,
            "billingcycle" => $this->productdata['pricing']['pricing']['cycle'],
            "nextduedate" => $hostingquerydates,
            "nextinvoicedate" => $hostingquerydates,
            "servicestatus" => "Draft",
            "lastupdate" => "now",
            "notes" => "",
                )
        );

        $_SESSION['serviceid'] = $serviceid;
    }

    public function pendingOrder() {

        if ($this->session['uid']) {
            update_query("tblcustomerservices", array('servicestatus' => 'Pending'), array("id" => $this->session['serviceid']));
            update_query("tblorders", array('status' => 'Pending'), array("id" => $this->session['orderid']));
        }
    }

    public function insertCustomefields($data) {
        $success_id = false;
        if (!empty($data) && !empty($this->session['avalialeaddons'])) {
            foreach ($data as $key => $value) {
                $success_id = insert_query("tblcustomfieldsvalues", array("cfid" => $key, "relid" => $this->session['serviceid'], "value" => $value));
            }
        }
        return $success_id;
    }

    public function insertAddon() {

        $success_id = false;
        if (!empty($this->productdata)) {
            foreach ($this->productdata['avalialeaddons'] as $data) {
                if ($data['select']) {
                    $insertdata = array(
                        "orderid" => $this->session['orderid'],
                        "serviceid" => $this->session['serviceid'],
                        "addonid" => $data['id'],
                        "name" => $data['name'],
                        "setupfee" => $data['cycle'] == "onetime" ? $data['value'] : 0,
                        "recurring" => $data['cycle'] == "monthly" ? $data['value'] : 0,
                        "billingcycle" => $data['cycle'],
                        "tax" => 0,
                        "status" => "Pending",
                        "regdate" => "now()",
                        "nextduedate" => "now()",
                        "nextinvoicedate" => "now()",
                        "paymentmethod" => ""
                    );

                    $success_id = insert_query("tblserviceaddons", $insertdata);
                }
            }
        }
        return $success_id;
    }

    public function finishorder($data) {
        $this->pendingOrder();
        if ($this->insertAddon() && $this->insertCustomefields($data)) {
            echo "<pre>", print_r($_SESSION, 1), "</pre>";
            unset($_SESSION['avalialeaddons']);
            $this->step = 3;
        }
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
