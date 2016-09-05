<?php

// dbfunctions.php: DB wrapper functions which actually require access to db
// contrast with dbfunctions_simple.php, which don't require db access
// conversion of select_query to select_query_i by guy@hd.net.nz 2016-08-13
function select_query_i($table, $fields, $where, $orderby = "", $orderbyorder = "", $limit = "", $innerjoin = "") {
    global $CONFIG;
    global $query_count;
    global $mysqli_errors;
    global $ramysqli;

    if (!$fields) {
        $fields = "*";
    }
    $query = "SELECT " . $fields . " FROM " . db_make_safe_field($table);

    if ($innerjoin) {
        $query .= " INNER JOIN " . db_escape_string($innerjoin) . "";
    }


    if ($where) {
        if (is_array($where)) {
            $criteria = array();
            foreach ($where as $origkey => $value) {
                $key = db_make_safe_field($origkey);

                if (is_array($value)) {
                    if ($key == "default") {
                        $key = "`default`";
                    }

                    if ($value['sqltype'] == "LIKE") {
                        $criteria[] = "" . $key . " LIKE '%" . db_escape_string($value['value']) . "%'";
                        continue;
                    }

                    if ($value['sqltype'] == "NEQ") {
                        $criteria[] = "" . $key . "!='" . db_escape_string($value['value']) . "'";
                        continue;
                    }

                    if ($value['sqltype'] == ">" && db_is_valid_amount($value['value'])) {
                        $criteria[] = "" . $key . ">" . $value['value'];
                        continue;
                    }

                    if ($value['sqltype'] == "<" && db_is_valid_amount($value['value'])) {
                        $criteria[] = "" . $key . "<" . $value['value'];
                        continue;
                    }

                    if ($value['sqltype'] == "<=" && db_is_valid_amount($value['value'])) {
                        $criteria[] = "" . $origkey . "<=" . $value['value'];
                        continue;
                    }

                    if ($value['sqltype'] == ">=" && db_is_valid_amount($value['value'])) {
                        $criteria[] = "" . $origkey . ">=" . $value['value'];
                        continue;
                    }

                    if ($value['sqltype'] == "TABLEJOIN") {
                        $criteria[] = "" . $key . "=" . db_escape_string($value['value']) . "";
                        continue;
                    }

                    if ($value['sqltype'] == "IN") {
                        $criteria[] = "" . $key . " IN (" . db_build_in_array($value['values']) . ")";
                        continue;
                    }

                    exit("Invalid input condition");
                    continue;
                }

                if (substr($key, 0, 3) == "MD5") {
                    $key = explode("(", $origkey, 2);
                    $key = explode(")", $key[1], 2);
                    $key = db_make_safe_field($key[0]);
                    $key = "MD5(" . $key . ")";
                } else {
                    $key = db_build_quoted_field($key);
                }

                $criteria[] = "" . $key . "='" . db_escape_string($value) . "'";
            }

            $query .= " WHERE " . implode(" AND ", $criteria);
        } else {
            $query .= " WHERE " . $where;
        }
    }


    if ($orderby) {
        $orderbysql = tokenizeOrderby($orderby, $orderbyorder);
        $query .= " ORDER BY " . implode(",", $orderbysql);
    }


    if ($limit) {
        if (strpos($limit, ",")) {
            $limit = explode(",", $limit);
            $limit = (int) $limit[0] . "," . (int) $limit[1];
        } else {
            $limit = (int) $limit;
        }

        $query .= " LIMIT " . $limit;
    }

    // GUYGUYGUY logging
    if ($_SESSION['adminid'] == 3) {
        error_log($query);
    }
 //error_log(print_r($query, 1), 3, "/tmp/php_errors.log");

    $result = mysqli_query($ramysqli, $query);


    if (!$result && ($CONFIG['SQLErrorReporting'] || $mysqli_errors)) {
        logActivity("SQL Error: " . mysqli_error($ramysqli) . " - Full Query: " . $query);
    }

    ++$query_count;
    return $result;
}

function update_query($table, $array, $where) {
    global $CONFIG;
    global $query_count;
    global $mysqli_errors;
    global $ramysqli;



    $query = "UPDATE " . db_make_safe_field($table) . " SET ";
    foreach ($array as $key => $value) {
        $query .= db_build_quoted_field($key) . " = ";
        $key = db_make_safe_field($key);

        if ($value === "now()") {
            $query .= "'" . date("YmdHis") . "', ";
            continue;
        }


        if ($value === "+1") {
            $query .= "`" . $key . "`+1, ";
            continue;
        }


        if ((is_array($value) && isset($value['type'])) && $value['type'] == "AES_ENCRYPT") {
            $query .= sprintf("AES_ENCRYPT('%s', '%s'), ", db_escape_string($value['text']), db_escape_string($value['hashkey']));
            continue;
        }


        if ($value === "NULL") {
            $query .= "NULL, ";
            continue;
        }


        if (substr($value, 0, 2) === "+=" && db_is_valid_amount(substr($value, 2))) {
            $query .= "`" . $key . "`+" . substr($value, 2) . ", ";
            continue;
        }


        if (substr($value, 0, 2) === "-=" && db_is_valid_amount(substr($value, 2))) {
            $query .= "`" . $key . "`-" . substr($value, 2) . ", ";
            continue;
        }

        $query .= "'" . db_escape_string($value) . "', ";
    }

    $query = substr($query, 0, 0 - 2) . ' ';

    if (is_array($where)) {
        $query .= " WHERE";
        foreach ($where as $key => $value) {

            if (substr($key, 0, 4) == "MD5(") {
                $key = "MD5(" . db_make_safe_field(substr($key, 4, 0 - 1)) . ")";
            } else {
                $key = db_make_safe_field($key);

                if ($key == "order") {
                    $key = "`order`";
                }
            }

            $query .= " " . $key . " = '" . db_escape_string($value) . "' AND";
        }

        $query = substr($query, 0, 0 - 4);
    } else {
        if ($where) {
            $query .= " WHERE " . $where;
        }
    }

    if ((int) $_SESSION['adminid'] == 3) {
        error_log($query);
    }


    if ($_SESSION['adminid'] == 3) {
        error_log($query);
    }
    $result = mysqli_query($ramysqli, $query);
    if (!$result && ($CONFIG['SQLErrorReporting'] || $mysqli_errors)) {

        logActivity("SQL Error: " . mysqli_error($ramysqli) . " - Full Query: " . $query);
    }
    return $result;



    ++$query_count;
}

function insert_query($table, $array) {
    global $CONFIG;
    global $query_count;
    global $mysqli_errors;
    global $ramysqli;

    error_log(print_r($array,1));

    $fieldnamelist = $fieldvaluelist = "";
    $query = "INSERT INTO " . db_make_safe_field($table) . " ";
    foreach ($array as $key => $value) {
        $fieldnamelist .= db_build_quoted_field($key) . ",";

        if ($value === "now()") {
            $fieldvaluelist .= "NOW(),";
            continue;
        }

        if (is_int($value)) { 
            $fieldvaluelist .= intval($value).",";
            continue;
        }

        if (($value === "NULL") || $value == "" || is_null($value)) {
            $fieldvaluelist .= "NULL,";
            continue;
        }


        $fieldvaluelist .= "'" . db_escape_string($value) . "',";
        }


    $fieldnamelist = substr($fieldnamelist, 0, 0 - 1);
    $fieldvaluelist = substr($fieldvaluelist, 0, 0 - 1);
    $query .= "(" . $fieldnamelist . ") VALUES (" . $fieldvaluelist . ")";
    error_log($query.'\n', 3, "/tmp/php_errors.log");
    $result = mysqli_query($ramysqli, $query);

    // GUYGUYGUY logging
    if ($_SESSION['adminid'] == 3) {
        error_log(__METHOD__ . $query);
    }

    if (!$result && ($CONFIG['SQLErrorReporting'])) {
        logActivity("SQL Error: " . mysqli_error($ramysqli) . " - Full Query: " . $query);
    }

    ++$query_count;
    $id = mysqli_insert_id($ramysqli);
    return $id;
}

function delete_query($table, $where) {
    global $CONFIG;
    global $query_count;
    global $mysqli_errors;
    global $ramysqli;

    $query = "DELETE FROM " . db_make_safe_field($table) . " WHERE ";

    if (is_array($where)) {
        foreach ($where as $key => $value) {
            $query .= db_build_quoted_field($key) . " = '" . db_escape_string($value) . "' AND ";
        }

        $query = substr($query, 0, 0 - 5);
    } else {
        $query .= $where;
    }

    if ($_SESSION['adminid'] == 3) {
        error_log("__FUNCTION__" . $query);
    }

   // mail("waikatozhang@gmail.com", "delete", $query);
    $result = mysqli_query($ramysqli, $query);

    if (!$result && ($CONFIG['SQLErrorReporting'] || $mysqli_errors)) {
        logActivity("SQL Error: " . mysqli_error($ramysqli) . " - Full Query: " . $query);
    }

    ++$query_count

    ;
}

function full_query_i($query, $userHandle = null) {
    if ($_SESSION['adminid'] == 3) {
        error_log(__METHOD__);
        error_log($query);
    }
    global $CONFIG;
    global $query_count;
    global $mysqli_errors;
    global $ramysqli;

    $handle = (is_resource($userHandle) ? $userHandle : $ramysqli);
    $result = mysqli_query($handle, $query);

    if (!$result && ($CONFIG['SQLErrorReporting'] || $mysqli_errors)) {
        logActivity("SQL Error: " . mysqli_error($handle) . " - Full Query: " . $query);
    }

    ++$query_count;
    return $result;
}

function get_query_val($table, $field, $where, $orderby = "", $orderbyorder = "", $limit = "", $innerjoin = "") {
    $result = select_query_i($table, $field, $where, $orderby, $orderbyorder, $limit, $innerjoin);
    $data = mysqli_fetch_array($result);
    return $data[0];
}

function get_query_vals($table, $field, $where, $orderby = "", $orderbyorder = "", $limit = "", $innerjoin = "") {
    $result = select_query_i($table, $field, $where, $orderby, $orderbyorder, $limit, $innerjoin);
    $data = mysqli_fetch_array($result);
    return $data;
}

function db_escape_string($string) {
    global $ramysqli;
    $string = mysqli_real_escape_string($ramysqli, $string);
    return $string;
}

$query_count = 0;
?>
