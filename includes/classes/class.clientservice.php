<?php

/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 * */
class RA_ClientService {

    public $servicedata;
    public $errorbox = "";
    public $userid;
    public $servicefirstid;
    public $addons;
    public $currecy;
    public $id;

    public function __construct($userid, $id) {
      
        $this->currecy = getCurrency();
        $this->userid = $userid;
        if (!$id || $id == 0) {
            $this->getFirstServiceId();
        } else {
            $this->id = $id;
        }
        if ($this->errorbox == "") {
            $this->getServiceDatas();
            $this->getAddonProduct();
            $this->getAlladdons();
        }
    }

    public function getFirstServiceId() {
        $query = "select * from tblcustomerservices where parent is null or parent=0 AND userid=" . $this->userid . " limit 1";
        $result = full_query_i($query);
        if ($result->num_rows > 0) {
            $data = mysqli_fetch_array($result);
            $this->servicefirstid = $data['id'];
            $this->id = $this->servicefirstid;
        } else {
            $this->errorbox = "<a href=\"ordersadd.php?userid=%d\">No Service Avaliable</a>";
        }
    }

    public function getPromocode() {
        $promoarr = array();
        $result = select_query_i("tblpromotions", "", "", "code", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $promo_id = $data['id'];
            $promo_code = $data['code'];
            $promo_type = $data['type'];
            $promo_recurring = $data['recurring'];
            $promo_value = $data['value'];

            if ($promo_type == "Percentage") {
                $promo_value .= "%";
            } else {
                $promo_value = formatCurrency($promo_value);
            }


            if ($promo_type == "Free Setup") {
                $promo_value = "Free Setup";
            }

            $promo_recurring = ($promo_recurring ? "Recurring" : "One Time");

            if ($promo_type == "Price Override") {
                $promo_recurring = "Price Override";
            }


            if ($promo_type == "Free Setup") {
                $promo_recurring = "";
            }

            $promoarr[$promo_id] = $promo_code . " - " . $promo_value . " " . $promo_recurring;
        }
        return $promoarr;
    }

    public function getServiceDatas() {

        if (isset($this->servicefirstid)) {
            $this->servicedata = getServiceData($this->servicefirstid);
        } else if (isset($this->id)) {
            $this->servicedata = getServiceData($this->id);
        } else {
            $this->servicedata = getServiceData();
        }
    }

    public function getAddonProduct() {
        if (!empty($this->servicedata)) {
            $query = "select tblcustomerservices.*,tblservices.name,tblservices.type from tblcustomerservices LEFT JOIN tblservices on tblservices.id=tblcustomerservices.packageid where tblcustomerservices.parent=" . $this->servicedata['id'];
            $result = full_query_i($query);
            if ($result->num_rows > 0) {
                $data = mysqli_fetch_assoc($result);
                $this->servicedata['addon'][$data['id']] = $data;
            } else {
                $this->errorbox = "No Addons";
            }
        } else {
            $this->errorbox = "<a href=\"ordersadd.php?userid=%d\">No Service Avaliable</a>";
        }
    }

    public function getAlladdons() {
        if (!empty($this->servicedata)) {

            $query = "select * from tblservicetoservice as tsts LEFT JOIN tblservices as ts on tsts.children_id=ts.id where tsts.parent_id=" . $this->servicedata['packageid'];
            $result = full_query_i($query);
            if ($result->num_rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $this->addons[$data['id']] = $data;
                    $this->addons[$data['id']]['price'] = getPricingInfo($data['id'], $inclconfigops = false, $upgrade = false, $this->currecy);
                    if (!empty(getServiceCustomFields($data['id']))) {
                        $this->addons[$data['id']]['customfield'][] = getServiceCustomFields($data['id']);
                    } else {
                        $this->addons[$data['id']]['customfield'] = 0;
                    }
                }
            }
        } else {
            $this->errorbox = "No Addons Avalible";
        }
    }

    public function addaddon($id,$payment) {

        $addon = array();
        $query = "select * from tblservices where id=" . $id;
        $result = full_query_i($query);

        if ($result->num_rows > 0) {
            $data = mysqli_fetch_assoc($result);
            $addon['addon'] = $data;
            $addon['price'] = getPricingInfo($data['id']);
        } else {
            $this->errorbox = "No Addons Avalible";
        }


        if ($addon['price']['type'] == "onetime") {
            $firstpaymet = $addon['price']['rawpricing']['msetupfee'] + $addon['price']['rawpricing']['monthly'];
            $recurr = 0;
        } else {
            $firstpaymet = $addon['price']['rawpricing']['msetupfee'];
            $recurr = $addon['price']['rawpricing']['monthly'];
        }


        $array = array(
            "userid" => $this->servicedata['userid'],
            "orderid" => $this->servicedata['orderid'],
            "packageid" => $id,
            "parent" => $this->servicedata['id'],
            "regdate" => "now()",
            "description" => $this->servicedata['description'],
            "paymentmethod" => $payment,
            "firstpaymentamount" => $firstpaymet,
            "amount" => $recurr,
            "billingcycle" => $addon['price']['type'],
            "nextduedate" => "now()",
            "nextinvoicedate" => "now()",
            "servicestatus" => "Pending",
            "suspendreason" => "",
            "overideautosuspend" => "",
            "overidesuspenduntil" => "",
            "lastupdate" => "now()",
            "notes" => "",
        );

        //   return $addon;
        $id = insert_query("tblcustomerservices", $array);
        return $id;
    }

    public function updateService($data) {

        if (!empty($data)) {
            update_query("tblcustomerservices", $data);
        }
    }

    public function getCustomefield($sid, $id) {
        $customdata[] = getServiceCustomFields($sid, $id);
        return $customdata;
    }

}

?>