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

    public function getServiceDatas() {

        if (isset($this->servicefirstid)) {
            $this->servicedata = getServiceData($this->servicefirstid);
        } else {
            $this->servicedata = getServiceData();
        }
    }

    public function getAddonProduct() {
        if (!empty($this->servicedata)) {
            $query = "select * from tblcustomerservices where parent=" . $this->servicedata['id'];
            $result = full_query_i($query);
            if ($result->num_rows > 0) {
                $data = mysqli_fetch_array($result);
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

}

?>