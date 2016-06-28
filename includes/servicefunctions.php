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
    WHERE tblcustomerservices.id=" . (int) $id
            . " AND tblservicegroups.type='service'";

    $result = full_query_i($query);
    $service_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //echo "<pre>".print_r($service_data, 1)."</pre>";
    return $service_data;
}

// return all custom fields and values for product, 
// even if value not populated (return null)
// if id is supplied but not gid, determine gid
// sid is the id from tblservices
// csid is the id from tblcustomerservices (if applicable) for population of values
function getServiceCustomFields($sid, $csid = null) {

    $csid = null;
    global $ramysqli;
    $sid = intval($sid);
    error_log("calling getServiceCustomFields with sid " . $sid . " and csid " . $csid);
    if (!is_int($sid)) {

        return false;
    }
    $query_selectvals = "SELECT
            tblcustomfields.cfid,
            tblcustomfields.fieldname,
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
    if (is_int($csid)) {
        $query_selectvals .= ", tblcustomfieldsvalues.value ";
        $query_tables .=
                " LEFT JOIN tblcustomfieldsvalues
            ON (tblcustomfieldsvalues.cfid=tblcustomfields.cfid AND tblcustomfieldsvalues.relid=" . (int) $csid . ") ";
    }
    $query_where = " WHERE tblservices.id=" . (int) $sid;
    $query = $query_selectvals . $query_tables . $query_where;

    $result = full_query_i($query);
    $returnvals = array();
    $service_data = mysqli_fetch_array($result, MYSQLI_ASSOC);

    while ($row = mysqli_fetch_assoc($result)) {
        $returnvals[] = $row;
    }

    return $returnvals;
}

// takes csid per above, and an array of fieldname->values
function updateServiceCustomFieldValues($csid, $valarray) {
    foreach ($valarray as $fieldname => $value) {
        // get list of cfids
    }
}

function getCustomerFieldHtml($data) {

    foreach ($data as $row) {

        $id = $data['id'];
        $fieldname = $data['fieldname'];

        if (strpos($fieldname, "|")) {
            $fieldname = explode("|", $fieldname);
            $fieldname = trim($fieldname[1]);
        }

 
        $fieldtype = $row['fieldtype'];
        $description = $row['description'];
        $fieldoptions = $row['fieldoptions'];
        $required = $row['required'];
        $adminonly = $row['adminonly'];
        $customfieldval = (is_array($row) ? $row[$id] : "");

        if ($required == "on") {
            $required = "*";
        }

        if ($fieldtype == "text" || ($fieldtype == "password" && $adminonly)) {
            $input = ("<input type=\"text\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";
        } else {
            if ($fieldtype == "link") {
                $webaddr = trim($customfieldval);

                if (substr($webaddr, 0, 4) == "www.") {
                    $webaddr = "http://" . $webaddr;
                }

                $input = ("<input type=\"text\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"40\" /> " . ($customfieldval ? "<a href=\"" . $webaddr . "\" target=\"_blank\">www</a>" : "");
                $customfieldval = "<a href=\"" . $webaddr . "\" target=\"_blank\">" . $customfieldval . "</a>";
            } else {
                if ($fieldtype == "password") {
                    $input = ("<input type=\"password\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";

                    if ($hidepw) {
                        $pwlen = strlen($customfieldval);
                        $customfieldval = "";
                        $i = 1;

                        while ($i <= $pwlen) {
                            $customfieldval .= "*";
                            ++$i;
                        }
                    }
                } else {
                    if ($fieldtype == "textarea") {
                        $input = ("<textarea name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" rows=\"3\" style=\"width:90%;\">" . $customfieldval . "</textarea>";
                    } else {
                        if ($fieldtype == "dropdown") {
                            $input = ("<select name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\">";
                            $fieldoptions = explode(",", $fieldoptions);
                            foreach ($fieldoptions as $optionvalue) {
                                $input .= ("<option value=\"" . $optionvalue . "\"");

                                if ($customfieldval == $optionvalue) {
                                    $input .= " selected";
                                }


                                if (strpos($optionvalue, "|")) {
                                    $optionvalue = explode("|", $optionvalue);
                                    $optionvalue = trim($optionvalue[1]);
                                }

                                $input .= ">" . $optionvalue . "</option>";
                            }

                            $input .= "</select>";
                        } else {
                            if ($fieldtype == "tickbox") {
                                $input = (("<input type=\"checkbox\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\"");

                                if ($customfieldval == "on") {
                                    $input .= " checked";
                                }

                                $input .= " />";
                            }
                        }
                    }
                }
            }
        }


        if ($fieldtype != "link" && strpos($customfieldval, "|")) {
            $customfieldval = explode("|", $customfieldval);
            $customfieldval = trim($customfieldval[1]);
        }

        $customfields[] = array("id" => $id, "name" => $fieldname, "description" => $description, "type" => $fieldtype, "input" => $input, "value" => $customfieldval, "rawvalue" => $rawvalue, "required" => $required, "adminonly" => $adminonly);
    }
    return $customfields;
}

?>
