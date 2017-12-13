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
        $customfieldjoin = ($this->customfieldsfilter ? " INNER JOIN tblcustomfieldsvalues ON tblcustomfieldsvalues.relid=tblclients.id" : "");
        $result = full_query_i("SELECT COUNT(*) FROM tblclients" . $customfieldjoin . $where);
        $data = mysqli_fetch_array($result);
        $this->getPageObj()->setNumResults($data[0]);
        $clients = array();
        $query = "SELECT id,firstname,lastname,companyname,email,datecreated,groupid,status FROM tblclients" . $customfieldjoin . $where . " ORDER BY " . $this->getPageObj()->getOrderBy() . " " . $this->getPageObj()->getSortDirection() . " LIMIT " . $this->getQueryLimit();
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

                $query = "SELECT
                    COALESCE(SUM(tcs.servicestatus IN ('Pending','Active','Suspended')),0) AS services,
                    COUNT(tcs.*) AS totalservices
                    FROM tblcustomerservices as tcs INNER JOIN tblservices AS ts ON tcs.packageid=ts.id INNER JOIN tblservicegroups as tsg on ts.gid=tsg.id WHERE tsg.type='service' AND tcs.userid=" . (int) $id;

                $result2 = full_query_i($query);
                $data = mysqli_fetch_array($result2);
                $services = $data['services'];
                $totalservices = $data['totalservices'];
            }


//            $resut3 = full_query_i("SELECT
//                    COALESCE(SUM(servicestatus IN ('Pending','Active','Suspended')),0) AS services,
//                    COUNT(*) AS totalproducts
//                    FROM tblcustomerservices WHERE userid=" . (int) $id);

            $clients[] = array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname, "companyname" => $companyname, "groupid" => $groupid, "groupcolor" => $groupcolor, "email" => $email, "services" => $services, "totalservices" => $totalservices, "products" => $products, "totalproducts" => $totalproducts, "datecreated" => $datecreated, "status" => $status);
        }


      //  error_log(print_r($clients), 3, "/tmp/php_errors.log");
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
                    $cfquery[] = "(tblcustomfieldsvalues.fieldid='" . db_escape_string($fieldid) . "' AND tblcustomfieldsvalues.value LIKE '%" . db_escape_string($fieldvalue) . "%')";
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
        $result = select_query_i("tblclientgroups", "", "");

        while ($data = mysqli_fetch_array($result)) {
            $this->groups[$data['id']] = array("name" => $data['groupname'], "colour" => $data['groupcolour'], "discountpercent" => $data['discountpercent'], "susptermexempt" => $data['susptermexempt'], "separateinvoices" => $data['separateinvoices']);
        }

        return $this->groups;
    }

}

?>
