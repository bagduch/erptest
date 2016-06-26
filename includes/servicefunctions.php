<?php

// return relevant info about a service
// custom fields need to be queried separately for now
function getServiceData($id = NULL) {
    $query = "
SELECT
    tblcustomerservices.*,
    tblservices.servertype,
    tblservices.type 
FROM tblcustomerservices 
INNER JOIN tblservices 
    ON tblservices.id=tblcustomerservices.packageid 
INNER JOIN tblservicegroups 
    ON (tblservices.gid=tblservicegroups.id ) 
WHERE tblcustomerservices.id=" . (int) $id
            . ' AND tblservicegroups.type="service"';


    $result = full_query_i($query);
    $service_data = mysqli_fetch_array($result);
    error_log(print_r($service_data, 1));
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

function getCustomFields($gid, $pid) {


    if (isset($gid)) {
        
    } else if (isset($pid)) {
        $query = "SELECT tc.* FROM tblcustomfields as tc
                INNER JOIN tblcustomfieldsgroupmembers as tgm ON tc.cfid = tgm.cfid
                INNER JOIN tblcustomfieldsgroupnames as tcfg ON tcfg.cfgid = tgm.cfgid  
                INNER JOIN tblcustomfieldsgrouplinks as tcgl ON tcgl.cfgid = tcfg.cfgid
                INNER JOIN tblservices as ts ON ts.id = tcgl.serviceid
                WHERE ts.id = " . $pid;
        echo $query;
    } else {
        return false;
    }
}

?>
