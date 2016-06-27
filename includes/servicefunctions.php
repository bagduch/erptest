<?php

// return relevant info about a service
// custom fields need to be queried separately for now
function getServiceData($id = NULL) {
    error_log("getting service data");
    $query = "
    SELECT
        tblcustomerservices.id,
        tblcustomerservices.userid,
        tblcustomerservices.orderid,
        tblcustomerservices.packageid,
        tblcustomerservices.server,
        tblcustomerservices.regdate,
        tblcustomerservices.description,
        tblcustomerservices.paymentmethod,
        tblcustomerservices.firstpaymentamount,
        tblcustomerservices.amount,
        tblcustomerservices.billingcycle,
        tblcustomerservices.nextduedate,
        tblcustomerservices.nextinvoicedate,
        tblcustomerservices.servicestatus,
        tblcustomerservices.username,
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
    WHERE tblcustomerservices.id=".(int)$id
    ." AND tblservicegroups.type='service'";

    $result = full_query_i($query);
    $service_data = mysqli_fetch_array($result);
    //echo "<pre>".print_r($service_data, 1)."</pre>";
    return $service_data;
}

// return all custom fields and values for product, 
// even if value not populated (return null)
// if id is supplied but not gid, determine gid
//function getCustomFields(int $id = null, int $gid = null) {
//    // find gid of product
//    if (is_null((int) $gid)) {
//        return true;
//    }
//}

// sid is the id from tblservices
// csid is the id from tblcustomerservices (if applicable) for population of values
function getServiceCustomFields($sid, $csid=null) {
    global $ramysqli;
    error_log("calling getServiceCustomFields with sid ".$sid." and csid ".$csid);
    if (!is_int($sid)) {
        return false;
    }
    $query_selectvals=
        "SELECT
            tblcustomfields.name,
            tblcustomfieldsvalues.value ";
    $query_tables = 
      " FROM
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
    if (is_int($csid)) {
        $query_tables .= 
      " LEFT JOIN tblcustomfieldsvalues
            ON (tblcustomfieldsvalues.cfid=tblcustomfields.cfid AND tblcustomfieldsvalues.relid=?) ";
    }
    $query_where = " WHERE tblservices.id=?";
    $query = $query_selectvals . $query_tables . $query_where;
    $returnvals = array();
    if ($stmt = $ramysqli->prepare($query)) {
        $stmt->bind_param("ii",$csid,$sid);
        $stmt->execute();
        $stmt->bind_result($name,$val);
        while ($stmt->fetch()) {
            $returnvals[$name]=$val;
        }
    }
    return $returnvals;
    
}

?>
