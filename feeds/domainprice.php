<?php

require("../init.php");

/*
*** USAGE SAMPLES ***

<script language="javascript" src="feeds/domainprice.php?tld=.com&type=register&regperiod=1"></script>

<script language="javascript" src="feeds/domainprice.php?tld=.com&type=register&regperiod=1&currency=1&format=1"></script>

*/
if (! $ra || !($ra instanceof RA_Init)) {
    die('Unable to instantiate application');
};

$tld = $ra->get_req_var('tld');
$type = $ra->get_req_var('type');
$regperiod = $ra->get_req_var('regperiod');
$format = ($ra->get_req_var('format')) ? true : false;
$currencyId = $ra->get_req_var('currency');

if (!is_numeric($regperiod) || $regperiod < 1) {
    $regperiod = 1;
}

$result = select_query("tbldomainpricing","id",array("extension"=>$tld));
$data = mysql_fetch_array($result);
$did = $data['id'];

/**
 * Case 3482: see documentation on formatCurrency()
 */
$currencyId = $ra->get_req_var('currency');
if (!is_numeric($currencyId)) {
    $currency = array();
} else {
    $currency = getCurrency('', $currencyId);
}

if (!$currency || !is_array($currency) || !isset($currency['id'])) {
    $currency = getCurrency();
}
$currencyId = $currency['id'];

$validDomainActionRequests = array('register','transfer','renew');

if (!in_array($type, $validDomainActionRequests)) {
    $type = 'register';
}

$result = select_query("tblpricing","msetupfee,qsetupfee,ssetupfee,asetupfee,bsetupfee,tsetupfee,monthly,quarterly,semiannually,annually,biennially,triennially",array("type"=>"domain".$type,"currency"=>$currencyId,"relid"=>$did));
$data = mysql_fetch_array($result);

if ($regperiod < 6) {
    $regperiod = $regperiod - 1;
}

$price = $data[$regperiod];

if ($format) {
    $price = formatCurrency($price);
}

echo "document.write('".$price."');";

?>