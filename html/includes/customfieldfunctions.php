<?php

/**
 *
 * @ RA
 *
 *
 * */
function getClientfieldshtml($relid2 = FALSE) {
    $datas = getClientFields();

    $customfields = array();
    // error_log(print_r($datas, 1), 3, "/tmp/php_errors.log");
    foreach ($datas as $data) {

        $id = $data['cfid'];
        $fieldname = $data['fieldname'];

        if (strpos($fieldname, "|")) {
            $fieldname = explode("|", $fieldname);
            $fieldname = trim($fieldname[1]);
        }

        $fieldtype = $data['fieldtype'];
        $description = $data['description'];
        $fieldoptions = $data['fieldoptions'];

        $required = $data['required'];
        $adminonly = $data['adminonly'];
        $customfieldval = (is_array($ordervalues) ? $ordervalues[$id] : "");

        if ($relid2) {
            $customfieldval = get_query_val("ra_user_fieldsvalues", "value", array("cfid" => $id, "relid" => $relid2));
        }

        $rawvalue = $customfieldval;

        if ($required == "on") {
            $required = "*";
        }


        if ($fieldtype == "text" || ($fieldtype == "password" && $admin)) {
            $input = ("<input type=\"text\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";
        } else {
            if ($fieldtype == "link") {
                $webaddr = trim($customfieldval);

                if (substr($webaddr, 0, 4) == "www.") {
                    $webaddr = "http://" . $webaddr;
                }

                $input = ("<input type=\"text\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"40\" /> " . ($customfieldval ? "<a href=\"" . $webaddr . "\" target=\"_blank\">www</a>" : "");
                $customfieldval = "<a href=\"" . $webaddr . "\" target=\"_blank\">" . $customfieldval . "</a>";
            } else {

                if ($fieldtype == "date") {
                    $input = ("<input type=\"text\" class=\"form-control datepick\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";
                }

                if ($fieldtype == "password") {
                    $input = ("<input type=\"password\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";

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
                        $input = ("<textarea class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" rows=\"3\" style=\"width:100%;\">" . $customfieldval . "</textarea>";
                    } else {
                        if ($fieldtype == "dropdown") {
                            $input = ("<select class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\">";
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
                                $input = (("<label class=\"checkbox\"><input data-toggle=\"checkbox\" type=\"checkbox\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\"");

                                if ($customfieldval == "on") {
                                    $input .= " checked";
                                }

                                $input .= " /></label>";
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

function getCustomFields($type, $relid, $relid2, $admin = "", $order = "", $ordervalues = "", $hidepw = "") {




    if (!function_exists("getServiceCustomFields")) {
        require dirname(__FILE__) . "/servicefunctions.php";
    }
    $datas = getServiceCustomFields($relid);
    $customfields = array();
    // error_log(print_r($datas, 1), 3, "/tmp/php_errors.log");
    foreach ($datas as $data) {
        //     echo "<pre>", print_r($data, 1), "</pre>";
        $id = $data['cfid'];
        $fieldname = $data['fieldname'];

        if (strpos($fieldname, "|")) {
            $fieldname = explode("|", $fieldname);
            $fieldname = trim($fieldname[1]);
        }

        $fieldtype = $data['fieldtype'];
        $description = $data['description'];
        $fieldoptions = $data['fieldoptions'];
        $required = $data['required'];
        $adminonly = $data['adminonly'];
        $customfieldval = (is_array($ordervalues) ? $ordervalues[$id] : "");
        if ($relid2) {
            $customfieldval = get_query_val("ra_catalog_user_sales_fieldsvalues", "value", array("fieldid" => $id, "relid" => $relid2));
        }
        $rawvalue = $customfieldval;

        if ($required == "on") {
            $required = "*";
        }

        if ($fieldtype == "text" || ($fieldtype == "password" && $admin)) {

            $input = ("<input type=\"text\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";
        } elseif ($fieldtype == "link") {
            $webaddr = trim($customfieldval);

            if (substr($webaddr, 0, 4) == "www.") {
                $webaddr = "http://" . $webaddr;
            }

            $input = ("<input type=\"text\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"40\" /> " . ($customfieldval ? "<a href=\"" . $webaddr . "\" target=\"_blank\">www</a>" : "");
            $customfieldval = "<a href=\"" . $webaddr . "\" target=\"_blank\">" . $customfieldval . "</a>";
        } elseif ($fieldtype == "date") {
            $input = ("<input type=\"text\" class=\"form-control datepick\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";
        } elseif ($fieldtype == "password") {
            $input = ("<input type=\"password\" class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" value=\"" . $customfieldval . "\" size=\"30\" />";

            if ($hidepw) {
                $pwlen = strlen($customfieldval);
                $customfieldval = "";
                $i = 1;

                while ($i <= $pwlen) {
                    $customfieldval .= "*";
                    ++$i;
                }
            }
        } elseif ($fieldtype == "textarea") {
            $input = ("<textarea class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\" rows=\"3\" style=\"width:90%;\">" . $customfieldval . "</textarea>";
        } elseif ($fieldtype == "dropdown") {
            $input = ("<select class=\"form-control\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\">";
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
        } elseif ($fieldtype == "tickbox") {
            $input = (("<input type=\"checkbox\" name=\"customfield[" . $id . "]") . "\" id=\"customfield" . $id . "\"");

            if ($customfieldval == "on") {
                $input .= " checked";
            }

            $input .= " />";
        } elseif ($fieldtype != "link" && strpos($customfieldval, "|")) {
            $customfieldval = explode("|", $customfieldval);
            $customfieldval = trim($customfieldval[1]);
        } else {
            
        }

        $customfields[] = array("id" => $id, "name" => $fieldname, "description" => $description, "type" => $fieldtype, "input" => $input, "value" => $customfieldval, "rawvalue" => $rawvalue, "required" => $required, "adminonly" => $adminonly);
    }


    return $customfields;
}

function saveClientFields($relid, $customfields) {
    if (is_array($customfields)) {
        foreach ($customfields as $id => $value) {

            $result = select_query_i("ra_user_fieldsvalues", "", array("cfid" => $id, "relid" => $relid));
            $num_rows = mysqli_num_rows($result);

            if ($num_rows == "0") {
                insert_query("ra_user_fieldsvalues", array("cfid" => $id, "relid" => $relid, "value" => $value));
                continue;
            }

            update_query("ra_user_fieldsvalues", array("value" => $value), array("cfid" => $id, "relid" => $relid));
        }
    }
}

function saveCustomFields($relid, $customfields, $type = "") {
    if (is_array($customfields)) {
        foreach ($customfields as $id => $value) {

            if ($type) {
                $where = array("id" => $id, "type" => $type);
                $result = select_query_i("ra_user_fields", "", $where);
                $data = mysqli_fetch_array($result);

                if (!$data['id']) {
                    continue;
                }
            }

            $result = select_query_i("ra_catalog_user_sales_fieldsvalues", "", array("fieldid" => $id, "relid" => $relid));
            $num_rows = mysqli_num_rows($result);

            if ($num_rows == "0") {
                insert_query("ra_catalog_user_sales_fieldsvalues", array("fieldid" => $id, "relid" => $relid, "value" => $value));
                continue;
            }

            update_query("ra_catalog_user_sales_fieldsvalues", array("value" => $value), array("fieldid" => $id, "relid" => $relid));
        }
    }
}

function migrateCustomFieldsBetweenProducts($serviceid, $newpid, $save = false) {
    $customfieldsarray = array();
    $result = select_query_i("tblcustomerservices", "packageid", array("id" => $serviceid));
    $data = mysqli_fetch_array($result);
    $existingpid = $data[0];

    if ($save) {
        $customfields = getCustomFields("product", $existingpid, $serviceid, true);
        foreach ($customfields as $v) {
            $k = $v['id'];
            $customfieldsarray[$k] = $_POST['customfield'][$k];
        }

        saveCustomFields($serviceid, $customfieldsarray);
    }


    if ($existingpid != $newpid) {
        $customfields = getCustomFields("product", $existingpid, $serviceid, true);
        foreach ($customfields as $v) {
            $cfid = $v['id'];
            $cfname = $v['name'];
            $cfval = $v['rawvalue'];
            $customfieldsarray[$cfname] = $cfval;
            delete_query("ra_catalog_user_sales_fieldsvalues", array("fieldid" => $cfid, "relid" => $serviceid));
        }

        $customfields = getCustomFields("product", $newpid, "", true);
        foreach ($customfields as $v) {
            $cfid = $v['id'];
            $cfname = $v['name'];

            if ($customfieldsarray[$cfname]) {
                insert_query("ra_catalog_user_sales_fieldsvalues", array("fieldid" => $cfid, "relid" => $serviceid, "value" => $customfieldsarray[$cfname]));
                continue;
            }
        }
    }
}

?>