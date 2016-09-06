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
    public $inforbox;
    public $userid;
    public $servicefirstid;

    public function __construct($userid, $id) {

        $this->userid = $userid;
        if (!$id) {
            $this->getFirstServiceId();
            $this->getServiceDatas();
            $this->getAddonProduct();
        }
    }

    public function getFirstServiceId() {
        $query = "select * from tblcustomerservices where parent is null AND userid=" . $this->userid . " limit 1";
        $result = full_query_i($query);
        if ($result->num_rows > 0) {
            $data = mysqli_fetch_array($result);
            $this->servicefirstid = $data['id'];
        } else {
            $this->inforbox = "<a href=\"ordersadd.php?userid=%d\">No Service Avaliable</a>";
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
                $this->inforbox = "No Addons";
            }
        } else {
            $this->inforbox = "<a href=\"ordersadd.php?userid=%d\">No Service Avaliable</a>";
        }
    }

    public function updateService($data) {

        if (!empty($data)) {
            update_query("tblcustomerservices", $data);
        }
    }

    public function getCustomefield($sid,$id) {
        $customdata[] = getServiceCustomFields($sid, $id);
        return $customdata;
    }

}

?>