<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Customer / Transaction Statement";
$reportdata["description"] = "";

$reportdata["headertext"] = '<form method="post" action="reports.php?report=client_statement">Enter Client ID: <input type="text" name="userid" value="'.$userid.'" size="10" /> Date Range: <input type="text" name="datefrom" value="'.$datefrom.'" class="datepick" /> to <input type="text" name="dateto" value="'.$dateto.'" class="datepick" /> (Leave blank for all time) <input type="submit" value="Generate Report" /></form>';

$currency = getCurrency($userid);
$statement = array();
$count = $balance = $totalcredits = $totaldebits = 0;

if ($userid) {

$result = select_query_i("tblinvoices","","userid='".db_escape_string($userid)."' AND status IN ('Unpaid','Paid','Collections')","date","ASC");
while($data = mysqli_fetch_array($result)) {
	$invoiceid = $data["id"];
    $date = $data["date"];
	$total = $data["credit"]+$data["total"];
    $result2 = select_query_i("tblinvoiceitems","id","invoiceid='$invoiceid' AND (type='AddFunds' OR type='Invoice')");
    $data = mysqli_fetch_array($result2);
    $addfunds = $data[0];
    if (!$addfunds) $statement[str_replace('-','',$date)."_".$count] = array("Invoice",$date,"<a href=\"invoices.php?action=edit&id=$invoiceid\" target=\"_blank\">#$invoiceid</a>",0,$total);
    $count++;
}

$result = select_query_i("tblaccounts","","userid='$userid'","date","ASC");
while($data = mysqli_fetch_array($result)) {
	$transid = $data["id"];
    $date = $data["date"];
    $description = $data["description"];
	$amountin = $data["amountin"];
    $amountout = $data["amountout"];
    $invoiceid = $data["invoiceid"];
    $date = substr($date,0,10);
    $result2 = select_query_i("tblinvoiceitems","type",array("invoiceid"=>$invoiceid));
    $data = mysqli_fetch_array($result2);
    $itemtype = $data[0];
    $adminid = $data["adminid"];
    if ($adminid>0) {
	$result3= select_query_i("tbladmins","username",array("id"=>$adminid));
	$data = mysqli_fetch_array($result3);
	$adminname = $data[0];
	} else {
     $adminname = "BLANK";
	}
	

    if ($itemtype=="AddFunds") {
        $description = "Credit Prefunding";
    } elseif ($itemtype=="Invoice") {
        $description = "Mass Invoice Payment - ";
        $result2 = select_query_i("tblinvoiceitems","relid",array("invoiceid"=>$invoiceid),"relid","ASC");
        while ($data = mysqli_fetch_array($result2)) {
            $invoiceid = $data[0];
            $description .= "<a href=\"invoices.php?action=edit&id=$invoiceid\" target=\"_blank\">#$invoiceid</a>, ";
        }
        $description = substr($description,0,-2);
    } else {
        $description = $description;
        if ($invoiceid) $description .= " - <a href=\"invoices.php?action=edit&id=$invoiceid\" target=\"_blank\">#$invoiceid</a>";
    }
	$statement[str_replace('-','',$date)."_".$count] = array("Transaction",$date,$description,$amountin,$amountout,$adminname);
	print($adminname."adminnameadminname");
    $count++;
}

}

$datefrom = ($datefrom) ? str_replace('-','',toMySQLDate($datefrom)) : '';
$dateto = ($dateto) ? str_replace('-','',toMySQLDate($dateto)) : '';

$reportdata["tableheadings"] = array("Type","Date","Description","Credits","Debits","Balance","Admin");
ksort($statement);
foreach ($statement AS $date=>$entry) {
    $date = substr($date,0,8);
    if (($date<=$dateto)OR(!$dateto)) {
        $totalcredits += $entry[3];
        $totaldebits -= $entry[4];
        $balance += $entry[3]-$entry[4];
    }
    if ((($date>=$datefrom)AND($date<=$dateto))OR(!$dateto)) $reportdata["tablevalues"][] = array($entry[0],fromMySQLDate($entry[1]),$entry[2],formatCurrency($entry[3]),formatCurrency($entry[4]),formatCurrency($balance),$entry[5]);
}
$reportdata["tablevalues"][] = array('#efefef','','','<b>Ending Balance</b>','<b>'.formatCurrency($totalcredits).'</b>','<b>'.formatCurrency($totaldebits).'</b>','<b>'.formatCurrency($balance).'</b>');

?>
