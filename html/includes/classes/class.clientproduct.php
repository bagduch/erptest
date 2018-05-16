<?php

/** RA - Version 0.1 **/
class RA_ClientProduct {

    public $servicedata;
    public $errorbox = "";
    public $userid;
    public $product_data = array();
    public $addons;
    public $currency;
    public $id;

    public function __construct($userid, $id) {

        $this->getAllproducts();
        $this->currency = getCurrency();
        $this->userid = $userid;
        if (!$id || $id == 0) {
            $this->getFirstProduct();
        } else {
            $this->id = $id;
        }
    }

    public function getAllproducts() {
        $this->product_data = array();
        $query = "SELECT tblcustomerservices.*,ra_catalog.servertype,ra_catalog.type from tblcustomerservices LEFT JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_catalog_groups ON (ra_catalog.gid=ra_catalog_groups.id AND ra_catalog_groups.type='product')";
        $result = full_query_i($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $this->product_data[$data['id']] = $data;
        }
    }

    public function getFirstProduct() {

        if (!empty($this->product_data)) {
            $arrayKeys = array_keys($this->product_data);
            $this->id = $arrayKeys[0];
        }
    }

    public function getPromocode() {
        $promoarr = array();
        $result = select_query_i("ra_promos", "", "", "code", "ASC");

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

}

?>