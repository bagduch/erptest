<?php



$type = ($_REQUEST["type"] ? : '');
//echo $type;
// Allow selection of product type for cleaner audits
echo('<form action="reports.php?report=' . $report . '" method="POST">Choose Account Type: <select name="type" onchange="this.form.submit()">');
echo('<option value="all"' . (($type == "all") ? ' selected' : '') . '>Every Account</option>');
echo('<option value="other"' . (($type == "other") ? ' selected' : '') . '>All Included Account</option>');
echo('<option value="exclude"' . (($type == "exclude") ? ' selected' : '') . '>All Excluded Accounts</option>');
echo('</select></form>');

//phpinfo(); 

$reportdata["title"] = "HD All Invoices per Service Monthly Invoice Audit";


//  GIDs:
//    71 - Broadband
//    98 - Wholesale Broadband
//  PIDs:
//    693 = Wholesale Portal Access
$ProductFilter = "
	WHERE (tblcustomerservices.descriptionstatus = 'Active')
";
//AND (tblproducts.id not in (693))


if ($type == 'all') {
    $ProductFilter .= '';
}
if (($type) == 'other') {
    $ProductFilter .= ' AND (tblallconnectreport_TESTREMOVE.exclude=0)';
} elseif ($type == 'exclude') {
    $ProductFilter .= ' AND (tblallconnectreport_TESTREMOVE.exclude=1)';
} else {
    $ProductFilter .= ' ';
}
//if ($type == 'all') {
//    $ProductFilter .= ' AND (tblallconnectreport_TESTREMOVE.exclude!=1)'; // show all accounts, everything *ben added.
//} elseif (($type) == 'Dedicaded') {
//    $ProductFilter .= ' AND (tblproducts.gid=97)';
//} elseif (($type) == 'other') {
//    $ProductFilter .= ' AND (tblproducts.gid!=97) AND (tblproducts.gid!=71) And (tblproducts.gid!=36)';
//} elseif (($type) == 'boradband') {
//    $ProductFilter .= ' AND (tblproducts.gid=71)';
//} elseif (($type) == 'colo') {
//    $ProductFilter .= ' AND (tblproducts.gid=36)';
//} elseif ($type == 'exclude') {
//    $ProductFilter .= ' AND (tblallconnectreport_TESTREMOVE.exclude=1)';
//} else {
//    $ProductFilter .= ' AND (tblallconnectreport_TESTREMOVE.exclude!=1)'; // show all accounts, everything *ben added.
//}
//



if (substr($_GET['orderBy'], 0, 1) == "+") {

    $invert = "-";
    $order = "DESC";
} else {
    $invert = "%2B";
    $order = "ASC";
}


$query = "
		SELECT
            tblcustomerservices.id AS id,
			tblclients.firstname as firstname,
			tblclients.lastname as lastname,
			tblclients.companyname as companyname,
			tblclients.id AS clientid,
			tblcustomerservices.description AS description, 
			tblcustomerservices.id AS hostingid,
			tblcustomerservices.username AS username,
			tblcustomerservices.dedicatedip AS ip,
			tblcustomerservices.regdate AS regdate,
			tblcustomerservices.nextduedate AS nextduedate,
			tblcustomerservices.amount AS monthly,
                        tblallconnectreport_TESTREMOVE.exclude AS exclude,
                        tblcustomerservices.nextinvoicedate AS nextinvoicedate,
			tblproducts.name as productname,
                        tblproducts.id as productid,
                        tblproducts.gid AS gid,
			cfasidv.value AS asid,
			tblproductconfigoptionssub.optionname AS dsltech,
			cffclcircuitidv.value AS fclcircuitid

		FROM tblcustomerservices
		            INNER JOIN tblproducts ON tblproducts.id=tblcustomerservices.packageid
		            INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid
                            LEFT JOIN tblallconnectreport_TESTREMOVE ON tblallconnectreport_TESTREMOVE.hosting_id=tblcustomerservices.id

			LEFT OUTER JOIN tblcustomfields as cfasid ON cfasid.relid=tblcustomerservices.packageid AND cfasid.fieldname like 'ASID'
			LEFT OUTER JOIN tblcustomfieldsvalues as cfasidv ON cfasidv.relid = tblcustomerservices.id  AND cfasidv.fieldid=cfasid.id 

			LEFT OUTER JOIN tblcustomfields as cffclcircuitid ON cffclcircuitid.relid=tblcustomerservices.packageid AND cffclcircuitid.fieldname like 'FCL Circuit ID'
			LEFT OUTER JOIN tblcustomfieldsvalues as cffclcircuitidv ON cffclcircuitidv.relid = tblcustomerservices.id  AND cffclcircuitidv.fieldid=cffclcircuitid.id

			LEFT OUTER JOIN tblproductconfiglinks on tblproductconfiglinks.pid = tblcustomerservices.packageid
			LEFT OUTER JOIN tblproductconfigoptions ON tblproductconfigoptions.gid = tblproductconfiglinks.gid
			LEFT OUTER JOIN tblcustomerservicesconfigoptions ON tblcustomerservicesconfigoptions.relid=tblcustomerservices.id AND tblcustomerservicesconfigoptions.configid = tblproductconfigoptions.id
			LEFT OUTER JOIN tblproductconfigoptionssub ON tblproductconfigoptionssub.configid=tblcustomerservicesconfigoptions.configid AND tblcustomerservicesconfigoptions.relid=tblcustomerservices.id AND tblcustomerservicesconfigoptions.optionid=tblproductconfigoptionssub.id
                 

	" . $ProductFilter . " AND tblclients.id!=14362 AND tblcustomerservices.billingcycle like 'Monthly'

	GROUP BY tblcustomerservices.id	ORDER BY " . ((@$_GET['orderBy']) ? mysqli_real_escape_string(substr($_GET['orderBy'], 1)) : "monthly") . " " . mysqli_real_escape_string($order) . "

	";
//
//echo $query;
//echo $ProductFilter;

$result = mysqli_query($query);


$ntplnames = array();
$i = 0;
$total_monthly = 0.00;
$total_unpayed = 0.00;


while ($row = mysqli_fetch_assoc($result)) {
    if ($row['exclude'] == "") {
        $insert = "INSERT INTO tblallconnectreport_TESTREMOVE (hosting_id,exclude) values(" . $row['hostingid'] . " ,0)";
        //  echo $insert;
        mysqli_query($insert);
    }

    $sql2 = " SELECT optionname,relid FROM tblproductconfigoptionssub RIGHT JOIN tblcustomerservicesconfigoptions ON tblcustomerservicesconfigoptions.optionid=tblproductconfigoptionssub.id where tblcustomerservicesconfigoptions.relid=" . $row['id'];
    $option = mysqli_query($sql2);
    $optionarray = array();
    while ($rowss = mysqli_fetch_assoc($option)) {
        $optionarray[$rowss['relid']][] = $rowss['optionname'];
    }

    $sql = "SELECT * FROM tblinvoiceitems LEFT JOIN tblinvoices ON tblinvoiceitems.invoiceid=tblinvoices.id WHERE tblinvoiceitems.description LIKE '%" . $row['description'] . "%' AND type='Hosting' AND date > '2017-01-01' AND status != 'Cancelled' AND relid=" . $row['id'] . " order by tblinvoiceitems.duedate DESC";
    $invoice = mysqli_query($sql);
    $invoicelist = "";
    $total = "";
    $duedate = "";
    $duedatecheck = array();
    $color;
    $monthly = 0;
    $k = 1;

    while ($rows = mysqli_fetch_assoc($invoice)) {


        if ($rows ['status'] == 'Paid') {
            $color = 'green';
        } else {
            $color = 'red';
        }

        //echo "<pre>", print_r($rows, true), "</pre>";

        $pattern = '/\((.*?)\-(.*?)\)/';
        preg_match($pattern, $rows['description'], $match, PREG_OFFSET_CAPTURE);

        $invoicelist .= '<a style="color:' . $color . '" href="invoices.php?action=edit&id=' . $rows['invoiceid'] . '">' . $rows['invoiceid'] . '</a></br>';
        $duedate.=$match[0][0] . "</br>";
        $total.="" . $rows['amount'] . "</br>";

        if ($k == 1) {
            $monthly = $rows['amount'];
        }

        if (isset($rows['duedate'])) {
            array_push($duedatecheck, $rows['duedate']);
        }$k++;
    }


    // echo "<pre>", print_r($duedatecheck[0], true), "</pre>";


    $missingamount = 0;

    $ealiest = strtotime($duedatecheck[sizeof($duedatecheck) - 1]);
//    $regdate = strtotime($row['regdate']);
//    $lastest = strtotime(date("Y-m-d"));
    $latedate = strtotime($duedatecheck[0]);

    $orange = "black";

//    $test1 = new DateTime(date("Y-m-01"));
//    $test2 = new DateTime(date('Y-m-01', $ealiest));
//
//    $diff = $test1->diff($test2);
//    $timediffmonth = ($diff->format('%y') * 12) + $diff->format('%m');
//
//    $timediffmonth = round(($lastest - $ealiest) / 2592000) + 1;
////

    $d1 = new DateTime(date('Y-m-d', $ealiest));
    $d2 = new DateTime(date('Y-m-d', $latedate));

    $m2 = (int) $d2->format('m');
    $m1 = (int) $d1->format('m');


    $timediffmonth = $m2 - $m1 + ($d1->diff($d2)->y * 12)+1;


    $missingamount = 0;
    if ($timediffmonth > sizeof($duedatecheck)) {

        $orange = "orange";
        $missingamount = $timediffmonth - sizeof($duedatecheck);
    }

    $newduedate = "";


    if (strtotime($row['nextduedate']) > strtotime("+1 month", $latedate)) {
        $newduedate = "Should be<p style='color:orange'>" . date('Y-m-d', strtotime("+1 month", $latedate)) . "</p>";
    }

    $description = '';

    foreach ($optionarray[$row['id']] as $des) {

        $description .= "<span style='color:blue;float:left;text-align: left;'>" . $des . "</span></br>";
    }

    $lightgreen = "black";
    if (stripos($row['description'], 'auckland') !== false) {
        $lightgreen = 'green';
    }
    if ($invoicelist !== "" && $monthly != 0) {
        if ($orange == "orange") {
            $orangelist[] = array(
                '<a style="color:' . $orange . ';font-size: 15px;font-weight: bold;" href="https://my.hd.net.nz/billing_hd_ems_007/clientsservices.php?id=' . $row['id'] . '">' . $row['productname'] . '</br></a>' . $description,
                '<p style="width:100px;color:' . $lightgreen . '">' . $row['description'] . '</p>',
                $row["nextinvoicedate"] . "</br>" . $newduedate,
                '<a href="clientssummary.php?userid=' . $row['clientid'] . '">' . $row['companyname'] . '</a>',
                $row['asid'],
                $invoicelist,
                $duedate,
                $total,
                abs($missingamount) * $monthly,
                $row['exclude'] ? "<input class='exclude' type='checkbox' checked name='exclude'><input class='hostid' name='hostid' type='hidden' value='" . $row['id'] . "'>" : "<input class='exclude' type='checkbox' name='exclude'><input  class='hostid' name='hostid' type='hidden' value='" . $row['id'] . "'>",
            );
        }
        if ($orange == "black") {
            $blacklist[] = array(
                '<a style="color:' . $orange . ';font-size: 15px;font-weight: bold;" href="https://my.hd.net.nz/billing_hd_ems_007/clientsservices.php?id=' . $row['id'] . '">' . $row['productname'] . '</br></a>' . $description,
                '<p style="width:100px;color:' . $lightgreen . '">' . $row['description'] . '</p>',
                $row["nextinvoicedate"] . "</br>" . $newduedate,
                '<a href="clientssummary.php?userid=' . $row['clientid'] . '">' . $row['companyname'] . '</a>',
                $row['asid'],
                $invoicelist,
                $duedate,
                $total,
                abs($missingamount) * $monthly,
                $row['exclude'] ? "<input class='exclude' type='checkbox' checked name='exclude'><input class='hostid' name='hostid' type='hidden' value='" . $row['id'] . "'>" : "<input class='exclude' type='checkbox' name='exclude'><input  class='hostid' name='hostid' type='hidden' value='" . $row['id'] . "'>",
            );
        }

        $i++;
        $total_monthly += $row['monthly'];
        $total_unpayed +=abs($missingamount) * $monthly;
    }
}

$totallist = array_merge((array) $orangelist, (array) $blacklist);


$reportdata["tablevalues"] = $totallist;


$reportdata["tableheadings"] = array(
    'Product',
    'Address',
    '<div style="width:150px">Reg Date</div>',
    "Company",
    'Asid',
    'Invoice ID#',
    '<div style="width:300px"> Invoice Due Date</div>',
    '<a href="reports.php?report=' . $report . '&orderBy=' . $invert . 'monthly&type=' . $type . '">Monthly ($)</a>',
    'Missing Amount',
    'Exclude'
);
$reportdata['headertext'] = '<p>There are <strong>' . $i . '</strong> ' . $type . ' accounts, giving <strong>$' . number_format($total_monthly, 0) . '</strong> total monthly income<strong> unpaid $' . number_format($total_unpayed, 0) . '</strong></p>';
$reportdata['footertext'] = "

<script type='text/javascript'>

    $(document).ready(function () {
      
        $('.exclude').click(function () {
              id= $(this).parent().find('.hostid').val();
              check=$(this).attr('checked') ? 1 : 0;
      $.ajax({
      method:'POST',
      url:'updateaudit.php',
      data:{id:id,status:check},
});
        });

    });

</script>    ";
?>