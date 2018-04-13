<?php

// return relevant info about a service
// custom fields need to be queried separately for now
function getServiceData($id = NULL) {
    $query = "
    SELECT
        tblcustomerservices.id,
        tblcustomerservices.userid,
        tblcustomerservices.orderid,
        tblcustomerservices.packageid,
        tblcustomerservices.regdate,
        tblcustomerservices.description,
        tblcustomerservices.paymentmethod,
        tblcustomerservices.firstpaymentamount,
        tblcustomerservices.amount,
        tblcustomerservices.billingcycle,
        tblcustomerservices.nextduedate,
        tblcustomerservices.nextinvoicedate,
        tblcustomerservices.servicestatus,
        tblcustomerservices.suspendreason,
        tblcustomerservices.overideautosuspend,
        tblcustomerservices.overidesuspenduntil,
        tblcustomerservices.lastupdate,
        tblcustomerservices.notes,
        tblservices.servertype,
        tblservices.type 
    FROM tblcustomerservices 
    LEFT JOIN tblservices 
        ON tblservices.id=tblcustomerservices.packageid 
    LEFT JOIN tblservicegroups 
        ON (tblservices.gid=tblservicegroups.id ) 
    WHERE tblcustomerservices.id=" . (int) $id;

    $result = full_query_i($query);
    $service_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $service_data['regdate'] = fromMySQLDate($service_data['regdate']);
    $service_data['nextduedate'] = fromMySQLDate($service_data['nextduedate']);
    return $service_data;
}

// return all custom fields and values for product, 
// even if value not populated (return null)
// if id is supplied but not gid, determine gid
// sid is the id from tblservices
// csid is the id from tblcustomerservices (if applicable) for population of values
function getServiceCustomFields($sid, $csid = null) {


    global $ramysqli;
    $sid = intval($sid);
    if (!is_int($sid)) {

        return false;
    }
    $query_selectvals = "SELECT
            tblcustomfields.*";
    $query_tables = " FROM
            tblservices
        LEFT JOIN tblservicegroups
            ON (tblservices.gid=tblservicegroups.id)
        LEFT JOIN tblcustomfieldsgrouplinks
            ON (tblcustomfieldsgrouplinks.serviceid=tblservices.id)
            OR (tblcustomfieldsgrouplinks.servicegid=tblservicegroups.id)
        LEFT JOIN tblcustomfieldsgroupnames
            ON (tblcustomfieldsgrouplinks.cfgid=tblcustomfieldsgroupnames.cfgid)
        LEFT JOIN tblcustomfieldsgroupmembers
            ON (tblcustomfieldsgroupmembers.cfgid=tblcustomfieldsgroupnames.cfgid)
        LEFT JOIN tblcustomfieldslinks
            ON (tblservices.id=tblcustomfieldslinks.serviceid) OR (tblservicegroups.id=tblcustomfieldslinks.servicegid)
        LEFT JOIN tblcustomfields 
            ON (tblcustomfields.cfid=tblcustomfieldsgroupmembers.cfid)
            OR (tblcustomfields.cfid=tblcustomfieldslinks.cfid)";
    if (isset($csid)) {
        $query_selectvals .= ", tblcustomfieldsvalues.value ";
        $query_tables .= " LEFT JOIN tblcustomfieldsvalues
            ON (tblcustomfieldsvalues.cfid=tblcustomfields.cfid AND tblcustomfieldsvalues.relid=" . (int) $csid . ") ";
    }
    $query_where = " WHERE tblservices.id=" . (int) $sid;
    $query = $query_selectvals . $query_tables . $query_where;
    $query .= " order by tblcustomfields.sortorder";

    $result = full_query_i($query);
    $returnvals = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $returnvals[$row['cfid']] = $row;

        $data = explode(",", $row['fieldoptions']);
        foreach ($data as $value) {
            $returnvals[$row['cfid']]['fieldoptions'][] = $data;
        }
    }

    foreach ($returnvals as $cfid => $row) {
        if (isset($returnvals[$row["parent_id"]])) {
            $returnvals[$row["parent_id"]]['children'][] = $row;
            unset($returnvals[$cfid]);
        }
    }

    return $returnvals;
}

function getClientFields() {
    $returnvals = array();
    $query = "select * from tblclientfields";
    $result = full_query_i($query);
    while ($row = mysqli_fetch_assoc($result)) {
        $returnvals[$row['cfid']] = $row;
    }
    foreach ($returnvals as $cfid => $row) {
        if (isset($returnvals[$row["parent_id"]])) {
            $returnvals[$row["parent_id"]]['children'][] = $row;
            unset($returnvals[$cfid]);
        }
    }
    return $returnvals;
}

function getCustomeFieldGroup($sid) {

    $data = array();
    if (isset($sid)) {
        $query = "select tcfgn.*,tcfgl.serviceid from tblcustomfieldsgroupnames as tcfgn
                 LEFT JOIN tblcustomfieldsgrouplinks as tcfgl on tcfgn.cfgid=tcfgl.cfgid where tcfgl.servicegid is null
                ";
        $result = full_query_i($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['cfgid']] = $row;
            $data[$row['cfgid']]['current'] = $row['serviceid'] == $sid ? "selected" : "";
        }
        return $data;
    } else {

        return false;
    }
}

function addServiceCustomFieldVlues($csid, $valarray) {
    $validate = new RA_Validate();

    if (isset($csid) && !empty($valarray)) {
        foreach ($valarray as $cfid => $value) {
            // get list of cfids
            if ($validate->validateCustomFields("", $cfid, $order)) {
                insert_query('tblcustomfieldsvalues', array('cfid' => $cfid, 'relid' => $csid, 'value' => $value));
                return true;
            } else {
                return $validate->errors_msgs;
            }
        }
    }
}

// from customefield to services 
function cfieldgroupToServices($cfid = null, $cfgid = null) {

    $data = array();
    if (isset($cfid) || isset($cfgid)) {
        if (is_int($cfgid)) {
            $query = "select ts.* from tblservices as ts
           INNER JOIN tblcustomfieldsgrouplinks as tcfgl on ts.id=tcfgl.serviceid";
        }
        if (is_int($cfid)) {
            $query = "select ts.* from tblservices as ts
            LEFT JOIN tblcustomfieldslinks as tcfl on tcfl.serviceid = ts.id
            LEFT JOIN tblcustomfieldsgrouplinks as tcfgl on ts.id=tcfgl.serviceid 
            LEFT JOIN tblcustomfieldsgroupmembers as tcgm on tcgm.cfgid=tcfgl.cfgid
            INNER JOIN tblcustomfields as tcf on (tcfl.cfid=tcf.cfid or tcgm.cfid=tcf.cfid) where tcf.cfid=" . $cfid;
        }
        $result = full_query_i($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['id']] = $row;
        }
    } else {
        return false;
    }
    return $data;
}

// takes csid per above, and an array of fieldname->values
function updateServiceCustomFieldValues($relid, $valarray = array()) {
    global $query_count;
    global $ramysqli;
    $validate = new RA_Validate();
    if (isset($relid) && !empty($valarray)) {
        foreach ($valarray as $cfid => $value) {
            // get list of cfids

            if ($validate->validateCustomFields("", $cfid)) {
                //update_query('tblcustomfieldsvalues', array('value' => $value), array('cfid' => $cfid, 'relid' => $relid));

                $query = "INSERT INTO tblcustomfieldsvalues (cfid,relid,value)values('" . $cfid . "','" . $relid . "','" . $value . "') ON DUPLICATE KEY UPDATE 
                     value=VALUES(value), cfid=VALUES(cfid),relid=VALUES(relid)";
                mysqli_query($ramysqli, $query);
                $query_count++;
            } else {
                return $validate->errors_msgs;
            }
        }
        return true;
    }
}

function getServiceAndProductdata($type, $userid) {
    $servicesarr = $userarray = array();
    $result = select_query_i("tblcustomerservices", "tblcustomerservices.amount,tblcustomerservices.id,tblcustomerservices.description,tblservices.name,tblcustomerservices.servicestatus,tblservices.type,tblservicegroups.name as gname", array("userid" => $userid, "tblservicegroups.type" => $type), "description", "ASC", "", "tblservices ON tblcustomerservices.packageid=tblservices.id INNER JOIN tblservicegroups ON tblservicegroups.id=tblservices.gid");

    $i = 0;
    while ($data = mysqli_fetch_array($result)) {

        $servicelist_id = $data['id'];
        $servicelist_product = $data['name'];
        $servicelist_adress = $data['description'];
        $servicelist_status = $data['servicestatus'];
        if ($servicelist_adress) {
            $servicelist_product .= " - " . $servicelist_adress;
        }
        if ($servicelist_status == "Pending") {
            $color = "#ffffcc";
        } else {
            if ($servicelist_status == "Suspended") {
                $color = "#ccff99";
            } else {
                if (in_array($servicelist_status, array("Terminated", "Cancelled", "Fraud"))) {
                    $color = "#ff9999";
                } else {
                    $color = "#fff";
                }
            }
        }


        $gname = str_replace(" ", "_", $data['gname']);
        $i++;
        $userarray[$gname][$servicelist_id] = $data;
        $userarray[$gname][$servicelist_id]['color'] = $color;
        $servicesarr[$servicelist_id] = array($color, $servicelist_product);
    }

    $data = array(
        "servicesarr" => $servicesarr,
        "userarray" => $userarray,
        "total" => $i
    );
    return $data;
}

?>
