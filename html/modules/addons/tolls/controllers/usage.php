<?php

if (!defined("RA")) {
    die("This file cannot be accessed directly");
}
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require(dirname(dirname(__FILE__)) . '/models/tolls.php');

if (isset($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = date("Y-m");
}
if (isset($_GET['search'])) {
    $accountcode = $_GET['search'];
} else {
    $accountcode = null;
}

if (isset($_GET['field'])) {
    $field = $_GET['field'];
} else {
    $field = null;
}

$data['field'] = $field;
$data['accountcode'] = $accountcode;
$colorarray = array(
    "#c40434", "#63caa6", "#bfe6d0", "#bd6589", "#c0ceb4", "#f6a889", "#16d185", "#3ac8db", "#0559a7"
);


$data['date'] = $date;

$tolls = new tolls($accountcode, $date);

if (isset($_POST["search"])) {
    $result = $tolls->search_all($_POST['value']);
    echo $result;
    exit();
}
$categories_sum = $tolls->get_all_categroy_sum($accountcode, $date);
$i = 0;
$data['sumbill']['bill'] = 0;
$data['sumbill']['total'] = 0;
foreach ($categories_sum as $key => $row) {

    $value = array();
    if ($key == "") {
        $key = "Unrecognise";
    }
    $data['category'][] = $key;
    $total = sizeof($categories_sum);
    $value = array_pad($value, $total, 0);
    $value[$i] = $row['bill_sum'];
    $data['categraphic'][] = array(
        "label" => $key,
        "backgroundColor" => $colorarray[$i],
        "borderColor" => $colorarray[$i],
        "borderWidth" => 1,
        "data" => $value
    );
    $data['sumbill']['bill'] += $row['bill_sum'];
    $data['sumbill']['total'] += $row['count'];
    $i++;
}


//$outboundcalls = $tolls->getOutBoundcalls($accountcode, $date);
//$data["outboundcalls"] = $outboundcalls;
$data['categoriessum'] = $categories_sum;
$data['categraphic'] = json_encode($data['categraphic']);
$data['category'] = json_encode($data['category']);
//$data['calls'] = $tolls->data;
?>
