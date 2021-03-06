<?php

/** RA - Version 0.1 **/
class RA_Clients extends RA_TableModel {

    private $groups = "";
    private $customfieldsfilter = "";

    public function _execute($criteria = null) {
        return $this->getClients($criteria);
    }

    public function getClients($criteria = array()) {
        global $disable_clients_list_services_summary;

        $clientgroups = $this->getGroups();
        $filters = $this->buildCriteria($criteria);
        $where = (count($filters) ? " WHERE " . implode(" AND ", $filters) : "");
        $customfieldjoin = ($this->customfieldsfilter ? " INNER JOIN ra_catalog_user_sales_fieldsvalues ON ra_catalog_user_sales_fieldsvalues.relid=ra_user.id" : "");
        $result = full_query_i("SELECT COUNT(*) FROM ra_user" . $customfieldjoin . $where);
        $data = mysqli_fetch_array($result);
        $this->getPageObj()->setNumResults($data[0]);
        $clients = array();
        $query = "SELECT id,firstname,lastname,companyname,email,datecreated,groupid,status FROM ra_user" . $customfieldjoin . $where . " ORDER BY " . $this->getPageObj()->getOrderBy() . " " . $this->getPageObj()->getSortDirection() . " LIMIT " . $this->getQueryLimit();
        $result = full_query_i($query);

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $companyname = $data['companyname'];
            $email = $data['email'];
            $datecreated = $data['datecreated'];
            $groupid = $data['groupid'];
            $status = $data['status'];
            $datecreated = fromMySQLDate($datecreated);
            $groupcolor = (isset($clientgroups[$groupid]['colour']) ? $clientgroups[$groupid]['colour'] . "\"" : "");
            $services = $totalservices = "-";
            $products = $totalproducts = 0;

            if (!$disable_clients_list_services_summary) {
                $query = "SELECT COUNT(IF(tsg.type='product',1,null)) 'totalproducts', 
                      COUNT(IF(tsg.type='service',1,null)) 'totalservices', 
                      COUNT(IF(tsg.type='product' AND tcs.servicestatus IN ('Pending','Active','Suspended'),1,NULL)) 'products',
                      COUNT(IF(tsg.type='service' AND tcs.servicestatus IN ('Pending','Active','Suspended'),1,NULL)) 'services' 
                      FROM tblcustomerservices as tcs 
                      INNER JOIN ra_catalog AS ts ON tcs.packageid=ts.id 
                      INNER JOIN ra_catalog_groups as tsg on ts.gid=tsg.id WHERE tcs.userid=" . (int) $id;
                $result2 = full_query_i($query);
                $data = mysqli_fetch_array($result2);
                $services = $data['services'];
                $totalservices = $data['totalservices'];
                $products = $data['products'];
                $totalproducts = $data['totalproducts'];
            }

            $clients[] = array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname, "companyname" => $companyname, "groupid" => $groupid, "groupcolor" => $groupcolor, "email" => $email, "services" => $services, "totalservices" => $totalservices, "products" => $products, "totalproducts" => $totalproducts, "datecreated" => $datecreated, "status" => $status);
        }

        return $clients;
    }

    private function buildCriteria($criteria) {
        $filters = array();

        if ($criteria['userid']) {
            $filters[] = "id=" . (int) $criteria['userid'];
        }


        if ($criteria['clientname']) {
            $filters[] = "concat(firstname,' ',lastname) LIKE '%" . db_escape_string($criteria['clientname']) . "%'";
        }


        if ($criteria['address']) {
            $filters[] = "concat(address1,' ',address2,' ',city,' ',state,' ',postcode) LIKE '%" . db_escape_string($criteria['address']) . "%'";
        }


        if ($criteria['state']) {
            $filters[] = "state LIKE '%" . db_escape_string($criteria['state']) . "%'";
        }


        if ($criteria['country']) {
            $filters[] = "country='" . db_escape_string($criteria['country']) . "'";
        }


        if ($criteria['companyname']) {
            $filters[] = "companyname LIKE '%" . db_escape_string($criteria['companyname']) . "%'";
        }


        if ($criteria['email']) {
            $filters[] = "email LIKE '%" . db_escape_string($criteria['email']) . "%'";
        }


        if ($criteria['phonenumber']) {
            $filters[] = "phonenumber LIKE '%" . db_escape_string($criteria['phonenumber']) . "%'";
        }


        if ($criteria['status']) {
            $filters[] = "status='" . db_escape_string($criteria['status']) . "'";
        }


        if ($criteria['clientgroup']) {
            $filters[] = "groupid='" . db_escape_string($criteria['clientgroup']) . "'";
        }


        if ($criteria['cardlastfour']) {
            $filters[] = "cardlastfour='" . db_escape_string($criteria['cardlastfour']) . "'";
        }


        if ($criteria['currency']) {
            $filters[] = "currency='" . db_escape_string($criteria['currency']) . "'";
        }

        $cfquery = array();

        if (is_array($criteria['customfields'])) {
            foreach ($criteria['customfields'] as $fieldid => $fieldvalue) {
                $fieldvalue = trim($fieldvalue);

                if ($fieldvalue) {
                    $cfquery[] = "(ra_catalog_user_sales_fieldsvalues.fieldid='" . db_escape_string($fieldid) . "' AND ra_catalog_user_sales_fieldsvalues.value LIKE '%" . db_escape_string($fieldvalue) . "%')";
                    $this->customfieldsfilter = true;
                    continue;
                }
            }
        }


        if (count($cfquery)) {
            $filters[] = implode(" OR ", $cfquery);
        }

        return $filters;
    }

    public function getGroups() {
        if (is_array($this->groups)) {
            return $this->groups;
        }

        $this->groups = array();
        $result = select_query_i("ra_user_group", "", "");

        while ($data = mysqli_fetch_array($result)) {
            $this->groups[$data['id']] = array("name" => $data['groupname'], "colour" => $data['groupcolour'], "discountpercent" => $data['discountpercent'], "susptermexempt" => $data['susptermexempt'], "separateinvoices" => $data['separateinvoices']);
        }

        return $this->groups;
    }

}

?>
