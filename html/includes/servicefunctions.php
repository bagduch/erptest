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
        ra_catalog.servertype,
        ra_catalog.type
    FROM tblcustomerservices
    LEFT JOIN ra_catalog
        ON ra_catalog.id=tblcustomerservices.packageid
    LEFT JOIN ra_catalog_groups
        ON (ra_catalog.gid=ra_catalog_groups.id )
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
// sid is the id from ra_catalog
// csid is the id from tblcustomerservices (if applicable) for population of values
function getServiceCustomFields($sid, $csid = null) {


    global $ramysqli;
    $sid = intval($sid);
    if (!is_int($sid)) {

        return false;
    }
    $query_selectvals = "SELECT
            ra_catalog_user_sales_fields.*";
    $query_tables = " FROM
            ra_catalog
        LEFT JOIN ra_catalog_groups
            ON (ra_catalog.gid=ra_catalog_groups.id)
        LEFT JOIN ra_catalog_user_sales_fieldsgrouplinks
            ON (ra_catalog_user_sales_fieldsgrouplinks.serviceid=ra_catalog.id)
        LEFT JOIN ra_catalog_user_sales_fieldsgroupnames
            ON (ra_catalog_user_sales_fieldsgrouplinks.cfgid=ra_catalog_user_sales_fieldsgroupnames.cfgid)
        LEFT JOIN ra_catalog_user_sales_fieldsgroupmembers
            ON (ra_catalog_user_sales_fieldsgroupmembers.cfgid=ra_catalog_user_sales_fieldsgroupnames.cfgid)
        LEFT JOIN ra_catalog_user_sales_fieldslinks
            ON (ra_catalog.id=ra_catalog_user_sales_fieldslinks.serviceid)
        LEFT JOIN ra_catalog_user_sales_fields
            ON (ra_catalog_user_sales_fields.cfid=ra_catalog_user_sales_fieldsgroupmembers.cfid)
            OR (ra_catalog_user_sales_fields.cfid=ra_catalog_user_sales_fieldslinks.cfid)";
    if (isset($csid)) {
        $query_selectvals .= ", ra_catalog_user_sales_fieldsvalues.value ";
        $query_tables .= " LEFT JOIN ra_catalog_user_sales_fieldsvalues
            ON (ra_catalog_user_sales_fieldsvalues.cfid=ra_catalog_user_sales_fields.cfid AND ra_catalog_user_sales_fieldsvalues.relid=" . (int) $csid . ") ";
    }
    $query_where = " WHERE ra_catalog.id=" . (int) $sid;
    $query = $query_selectvals . $query_tables . $query_where;
    $query .= " order by ra_catalog_user_sales_fields.sortorder";

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
    $query = "select * from ra_user_fields";
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
        $query = "select tcfgn.*,tcfgl.serviceid from ra_catalog_user_sales_fieldsgroupnames as tcfgn
                 LEFT JOIN ra_catalog_user_sales_fieldsgrouplinks as tcfgl on tcfgn.cfgid=tcfgl.cfgid
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
                insert_query('ra_catalog_user_sales_fieldsvalues', array('cfid' => $cfid, 'relid' => $csid, 'value' => $value));
                return true;
            } else {
                return $validate->errors_msgs;
            }
        }
    }
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
                //update_query('ra_catalog_user_sales_fieldsvalues', array('value' => $value), array('cfid' => $cfid, 'relid' => $relid));

                $query = "INSERT INTO ra_catalog_user_sales_fieldsvalues (cfid,relid,value)values('" . $cfid . "','" . $relid . "','" . $value . "') ON DUPLICATE KEY UPDATE
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
    $result = select_query_i("tblcustomerservices", "tblcustomerservices.amount,tblcustomerservices.id,tblcustomerservices.description,ra_catalog.name,tblcustomerservices.servicestatus,ra_catalog.type,ra_catalog_groups.name as gname", array("userid" => $userid, "ra_catalog_groups.type" => $type), "description", "ASC", "", "ra_catalog ON tblcustomerservices.packageid=ra_catalog.id INNER JOIN ra_catalog_groups ON ra_catalog_groups.id=ra_catalog.gid");

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
        } else if ($servicelist_status == "Suspended") {
            $color = "#ccff99";
        } elseif (in_array($servicelist_status, array("Terminated", "Cancelled", "Fraud"))) {
            $color = "#ff9999";
        } else {
            $color = "#fff";
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
