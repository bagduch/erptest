<?php

class RA_Order {
	private $orderid = "";
	private $data = array();

	public function __construct() {
	}

	public function setID($orderid) {
		$this->orderid = (int)$orderid;
		return $this->loadData();
	}

	public function loadData() {
        $result = select_query_i(
            "ra_orders", 
            "ra_orders.*,ra_user.firstname,ra_user.lastname,ra_user.email,ra_user.companyname,ra_user.address1,ra_user.address2,ra_user.city,ra_user.state,ra_user.postcode,ra_user.country,ra_user.groupid,(SELECT status FROM ra_bills WHERE id=ra_orders.invoiceid) AS invoicestatus", 
            array("ra_orders.id" => $this->orderid), 
            "", 
            "", 
            "", 
            "ra_user ON ra_user.id=ra_orders.userid"
        );
		$data = mysqli_fetch_array($result);

		if (!$data['id']) {
			return false;
		}

		$this->data = $data;
		return true;
	}

	public function getData($var = "") {
		return array_key_exists($var, $this->data) ? $this->data[$var] : "";
	}

	public function createOrder($userid, $paymentmethod, $contactid = "") {
		global $ra;

		$order_number = generateUniqueID();
        $this->orderid = insert_query(
            "ra_orders", 
            array(
                "ordernum" => $order_number, 
                "userid" => $userid, 
                "contactid" => $contactid, 
                "date" => "now()", 
                "status" => "Pending", 
                "paymentmethod" => $paymentmethod, 
                "ipaddress" => $ra->get_user_ip()
            )
        );
		logActivity("New Order Created - Order ID: " . $orderid . " - User ID: " . $userid);
		return $this->orderid;
	}

	public function updateOrder($data) {
	}
}

?>
