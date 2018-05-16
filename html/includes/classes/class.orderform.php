<?php

/** RA - Version 0.1 **/
class RA_OrderForm {

    private $template = "";
    private $pid = "";
    private $productinfo = array();
    private $serviceinfo=array();
    private $validbillingcycles = array(0 => "free", 1 => "onetime", 2 => "monthly", 3 => "quarterly", 4 => "semiannually", 5 => "annually", 6 => "biennially", 7 => "triennially");

    public function __construct() {
        global $ra;
        $this->setTemplate($ra->get_config("OrderFormTemplate"));
    }

    public function setTemplate($tpl) {
        $this->template = $tpl;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function getProductGroups() {
        $groupsarray = array();
        $result = select_query_i("tblproductgroups", "", "hidden='' AND type=2", "order", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $groupid = $data['id'];
            $groupname = $data['name'];
            $groupsarray[] = array("gid" => $groupid, "name" => $groupname);
        }

        return $groupsarray;
    }

    public function getServiceGroups() {
        $groupsarray = array();
        $result = select_query_i("tblservicegroups", "","", "order", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $groupid = $data['id'];
            $groupname = $data['name'];
            $groupsarray[] = array("gid" => $groupid, "name" => $groupname);
        }

        return $groupsarray;
    }

    public function getProducts($gid, $inclconfigops = false, $inclbundles = false) {
        global $currency;

        if (!$gid) {
            $result = select_query_i("tblservicegroups", "id","hidden=''", "order", "ASC");
            $data = mysqli_fetch_array($result);
            $gid = $data[0];
        }

        $tmparray = array();
        $result = select_query_i("tblservices", "", array("gid" => $gid, "hidden" => ""), "order` ASC,`name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $type = $data['type'];
            $name = $data['name'];
            $description = $data['description'];
            $paytype = $data['paytype'];
            $freedomain = $data['freedomain'];
            $stockcontrol = $data['stockcontrol'];
            $qty = $data['qty'];
            $freedomainpaymentterms = $data['freedomainpaymentterms'];
            $sortorder = $data['order'];
            $freedomainpaymentterms = explode(",", $freedomainpaymentterms);
            $desc = $this->formatProductDescription($description);
            $product = array();
            $product['pid'] = $id;
            $product['type'] = $type;
            $product['name'] = $name;
            $product['description'] = $desc['original'];
            $product['features'] = $desc['features'];
            $product['featuresdesc'] = $desc['featuresdesc'];
            $product['paytype'] = $paytype;
            $product['pricing'] = getPricingInfo($id, $inclconfigops);
            $product['freedomain'] = $freedomain;
            $product['freedomainpaymentterms'] = $freedomainpaymentterms;

            if ($stockcontrol) {
                $product['qty'] = $qty;
            }

            $tmparray[$sortorder][] = $product;
        }


        if ($inclbundles) {
            $result = select_query_i("tblbundles", "", array("showgroup" => "1", "gid" => $gid));

            while ($data = mysqli_fetch_array($result)) {
                $description = $data['description'];
                $desc = $this->formatProductDescription($description);
                $displayprice = $data['displayprice'];
                $displayprice = (0 < $displayprice ? formatCurrency(convertCurrency($displayprice, 1, $currency['id'])) : "");
                $tmparray[$data['sortorder']][] = array("bid" => $data['id'], "name" => $data['name'], "description" => $desc['original'], "features" => $desc['features'], "featuresdesc" => $desc['featuresdesc'], "displayprice" => $displayprice);
            }
        }

        ksort($tmparray);
        $productsarray = array();
        foreach ($tmparray as $sort => $items) {
            foreach ($items as $item) {
                $productsarray[] = $item;
            }
        }

        return $productsarray;
    }

    public function formatProductDescription($desc) {
        $features = array();
        $featuresdesc = "";
        $descriptionlines = explode("\n", $desc);

        foreach ($descriptionlines as $line) {

            if (strpos($line, ":")) {
                $line = explode(":", $line, 2);
                $features[trim($line[0])] = trim($line[1]);
                continue;
            }


            if (trim($line)) {
                $featuresdesc .= $line . "\r\n";
                continue;
            }
        }

        return array("original" => nl2br($desc), "features" => $features, "featuresdesc" => nl2br($featuresdesc));
    }

    public function getProductGroupInfo($gid) {
        $result = select_query_i("tblservicegroups", "", array("id" => $gid));
        $data = mysqli_fetch_assoc($result);

        if (!$data['id']) {
            return false;
        }


        if ($data['orderfrmtpl']) {
            $this->setTemplate($data['orderfrmtpl']);
        }

        return $data;
    }

    public function setPid($pid) {
        $this->pid = $pid;
        $result = select_query_i("tblservices", "tblservices.id AS pid,tblservices.gid,tblservices.type,tblservices.name AS name,tblservicegroups.name AS groupname,tblservices.description,tblservices.paytype,tblservicegroups.orderfrmtpl", array("tblservices.id" => $pid), "", "", "", "tblservicegroups ON tblservicegroups.id=tblservices.gid");
        $data = mysqli_fetch_assoc($result);
      
        if (!$data['pid']) {
            return false;
        }

        if ($data['orderfrmtpl']) {
            $this->setTemplate($data['orderfrmtpl']);
        }

        $this->serviceinfo = $data;
      
        return $this->serviceinfo;
    }

    public function getProductInfo($var = "") {
        return $var ? $this->productinfo[$var] : $this->productinfo;
    }

    public function validateBillingCycle($billingcycle) {
        global $currency;

        if ($billingcycle && in_array($billingcycle, $this->validbillingcycles)) {
            return $billingcycle;
        }

        $paytype = $this->productinfo['paytype'];
        $result = select_query_i("tblpricing", "", array("type" => "product", "currency" => $currency['id'], "relid" => $this->productinfo['pid']));
        $data = mysqli_fetch_array($result);
        $monthly = $data['monthly'];
        $quarterly = $data['quarterly'];
        $semiannually = $data['semiannually'];
        $annually = $data['annually'];
        $biennially = $data['biennially'];
        $triennially = $data['triennially'];

        if ($paytype == "free") {
            $billingcycle = "free";
        } else {
            if ($paytype == "onetime") {
                $billingcycle = "onetime";
            } else {
                if ($paytype == "recurring") {
                    if (0 <= $monthly) {
                        $billingcycle = "monthly";
                    } else {
                        if (0 <= $quarterly) {
                            $billingcycle = "quarterly";
                        } else {
                            if (0 <= $semiannually) {
                                $billingcycle = "semiannually";
                            } else {
                                if (0 <= $annually) {
                                    $billingcycle = "annually";
                                } else {
                                    if (0 <= $biennially) {
                                        $billingcycle = "biennially";
                                    } else {
                                        if (0 <= $triennially) {
                                            $billingcycle = "triennially";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $billingcycle;
    }

}

?>