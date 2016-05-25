<?php

require("../init.php");

/*
*** USAGE SAMPLES ***

<script language="javascript" src="feeds/productsinfo.php?pid=1&get=name"></script>

<script language="javascript" src="feeds/productsinfo.php?pid=1&get=description"></script>

<script language="javascript" src="feeds/productsinfo.php?pid=1&get=price&billingcycle=monthly&currency=1"></script>

<script language="javascript" src="feeds/productsinfo.php?pid=1&get=orderurl&carttpl=web20cart"></script>

*/

if (! $ra || !($ra instanceof RA_Init)) {
    die('Unable to instantiate application');
};
$pid = $ra->get_req_var('pid');
$get = $ra->get_req_var('get');
$billingcycle = $ra->get_req_var('billingcycle');
$configoptionnum = $ra->get_req_var('configoptionnum');

// Verify user input for pid exists, is numeric, and as is a valid id
if (is_numeric($pid)) {
    $result = select_query("tblservices", "", array("id" => $pid));
    $data = mysql_fetch_array($result);
    $pid = $data['id'];
    $name = $data['name'];
    $description = $data['description'];
} else {
    $pid = '';
}

if (!$pid || !is_numeric($pid)) {
    widgetoutput('Product ID Not Found');
}

if ($get=="name") widgetoutput($name);

if ($get=="description") {
    $description = str_replace(array("\r","\n","\r\n"),"",nl2br($description));
    widgetoutput($description);
}

if ($get=="configoption") widgetoutput($data['configoption']. (int) $configoptionnum);

if ($get=="orderurl") {
    $systemurl = ($CONFIG['SystemSSLURL']) ? $CONFIG['SystemSSLURL'].'/' : $CONFIG['SystemURL'].'/';
    if ($carttpl=="ajax") {
        widgetoutput($systemurl."order/?pid=$pid");
    } else {
        widgetoutput($systemurl."cart.php?a=add&pid=$pid&$carttpl=cart");
    }
}

if ($get=="price") {
    /**
     * Case 3482: see documentation on formatCurrency()
     */
    $currencyid = $ra->get_req_var('currency');
    if (!is_numeric($currencyid)) {
        $currency = array();
    } else {
        $currency = getCurrency('', $currencyid);
    }

    if (!$currency || !is_array($currency) || !isset($currency['id'])) {
        $currency = getCurrency();
    }
    $currencyid = $currency['id'];

    $result = select_query("tblpricing","",array("type" => "product", "currency" => $currencyid, "relid" => $pid));
    $data = mysql_fetch_array($result);
    $price = $data[$billingcycle];
    $price = formatCurrency($price);
    widgetoutput($price);
}

function widgetoutput($value) {
    echo "document.write('".addslashes($value)."');";
    exit;
}

?>