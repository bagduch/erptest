<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Affiliates Overview";
$reportdata["description"] = "An overview of affiliates for the current year";

$reportdata["tableheadings"] = array('Affiliate ID','Affiliate Name','Visitors','Pending Commissions','Available to Withdraw','Withdrawn Amount','YTD Total Commissions Paid');

$result = select_query("ra_partners","ra_partners.id,ra_partners.clientid,ra_partners.visitors,ra_partners.balance,ra_partners.withdrawn,ra_user.firstname,ra_user.lastname,ra_user.companyname","","visitors","DESC","","ra_user ON ra_user.id=ra_partners.clientid");
while ($data = mysql_fetch_array($result)) {

    $affid = $data['id'];
    $clientid = $data['clientid'];
    $visitors = $data['visitors'];
    $balance = $data['balance'];
    $withdrawn = $data['withdrawn'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $companyname = $data['companyname'];

    $name = $firstname.' '.$lastname;
    if ($companyname) $name .= ' ('.$companyname.')';

    $result2 = select_query("ra_partnerspending","COUNT(*),SUM(ra_partnerspending.amount)",array("affiliateid"=>$affid),"clearingdate","DESC","","ra_partnersaccounts ON ra_partnersaccounts.id=ra_partnerspending.affaccid INNER JOIN tblhosting ON tblcustomerservices.id=ra_partnersaccounts.relid INNER JOIN ra_catalog ON ra_catalog.id=tblcustomerservices.packageid INNER JOIN ra_user ON ra_user.id=tblcustomerservices.userid");
    $data = mysql_fetch_array($result2);
    $pendingcommissions = $data[0];
    $pendingcommissionsamount = $data[1];

    $result2 = select_query("ra_partnershistory","SUM(amount)","affiliateid=$affid AND date LIKE '".date("Y")."-%'");
    $data = mysql_fetch_array($result2);
    $ytdtotal = $data[0];

    $currency = getCurrency($clientid);
    $pendingcommissionsamount = formatCurrency($pendingcommissionsamount);
    $ytdtotal = formatCurrency($ytdtotal);

    $reportdata["tablevalues"][] = array('<a href="affiliates.php?action=edit&id='.$affid.'">'.$affid.'</a>',$name,$visitors,$pendingcommissionsamount,$balance,$withdrawn,$ytdtotal);

}

?>