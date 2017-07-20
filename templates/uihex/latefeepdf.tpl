<?php
### Variables that you can set ###

$invoicevars = array(
	'pageformat'         => 'A4',    // Either 'LETTER' or 'A4'
	'custom_tax_name'    => "TAX INVOICE",
	'tax_number'         => "GST Number: 96-983-506",
	'tagline'            => "",
	'phonenumber'        => "64 9 280 4135",
	'faxnumber'          => "64 9 280 4134",
	'Remittance1'        => "Please pay by the Due Date and use the Invoice number provided as the reference.",
	'Remittance2'        => "\nAcc Number: 02-0272-0179921-000\nAcc Name: HD NET LTD",
	'paybuttontext'      => "",
	'totalpayable'       => "Total Payable",
	'dueby'              => "Due By",
	'statusdatepaid'     => "Date Paid",
	'statusvia'          => "Via",
	'invoiceinfoheader'  => "Invoice Information",
	'accountinfoheader'  => "Account Information",
	'accountnumber'      => "Account ID",
	'accountname'        => "Account Owner",
	'accountusername'    => "Username",
	'contactinfoheader'  => "Contact Information",
	'contactweb'         => "Web: www.hd.net.nz",
	'contactemail'       => "Email: s@hd.net.nz",
	'contactphone'       => "Phone",
	'contactfax'         => "Fax",
	'Remittance'         => "Remittance:",
	'remittanceto'       => "To:",
    'hdrra'       		 => "HD",
    'hdrrb'      		 => "PO Box 26 Westpark Village",
    'hdrrc'       		 => "Waitakere Auckland",
    'hdrrd'       		 => "0661",
    'hdrre'       		 => "New Zealand",
	'remittancetotaldue' => "Total Payment Due",
	'paymethodtext'      => "\nAcc Number: 02-0272-0179921-000\nAcc Name: HD NET LTD\n\nPlease pay by the Due Date and use the Invoice number provided as the reference.",
	'backcolor'          => array(255,255,255),
	'boxbackcolor'       => array(229,242,254),
	'headerbgcolor'      => array(153,204,255),
	'headerfgcolor'      => array(0,0,0),
	'bordercolor'        => array(0,0,0),
	'buttoncolor'        => array(204,204,204),
	'taxnamecolor'       => array(0,0,0),
	'taglinecolor'       => array(0,0,255),
	'textcolor'          => array(0,0,0),
	'cancelledcolor'     => array(204,204,204),
        'draftcolor'         => array(255,153,51),
	'unpaidcolor'        => array(204,0,51),
	'paidcolor'          => array(153,204,0),
	'refundedcolor'      => array(34,68,136),
	'collectionscolor'   => array(255,204,0),
);
### DO NOT EDIT UNDER THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING ###
######################################################################

extract($invoicevars);
$invoiceprefix = $_LANG["invoicenumber"];

if ($CONFIG['SequentialInvoiceNumbering'] == 'on') {
	## This code is for EU companies using the sequential invoice numbering so that when unpaid it is shown as a proforma invoice **
	if ($status!="Paid") {
		$invoiceprefix = $_LANG["proformainvoicenumber"];
	}
}

if ($status == 'Paid' || $status == 'Cancelled') {
	$invoicetotal = number_format(0,2);
} else{
	$invoicetotal = number_format($total,2);
}

if ($clientsdetails["companyname"]) {
	$addressline = array($clientsdetails["companyname"],$clientsdetails["address1"],$clientsdetails["city"],$clientsdetails["state"]." ".$clientsdetails["postcode"]." ".$clientsdetails["country"]);
} else {
	$addressline = array($clientsdetails["firstname"]." ".$clientsdetails["lastname"],$clientsdetails["address1"],$clientsdetails["city"],$clientsdetails["state"]." ".$clientsdetails["postcode"]." ".$clientsdetails["country"]);
}

$result2a = select_query_i("tblinvoices","total",array("id"=>$id));
$dataa = mysqli_fetch_array($result2a);
$invtotal = $dataa["total"];

$resultb = select_query_i("tblaccounts","SUM(amountin)-SUM(amountout)",array("invoiceid"=>$id));
$datab = mysqli_fetch_array($resultb);
$amountpaid = $datab[0];

$balance = $invtotal - $amountpaid;
$balance = sprintf("%01.2f", $balance);
$balance = formatCurrency($balance);



$transactions = array();
$resultc = select_query_i("tblaccounts","",array("invoiceid"=>$id),"date` ASC,`id","ASC");
while ($datac = mysqli_fetch_array($resultc)) {
	$date = $datac["date"];
	$date = fromMySQLDate($date);
	$gateway = $datac["gateway"];
	$description = $datac["description"];
	$amountin = $datac["amountin"];
	$fees = $datac["fees"];
    $amountout = $datac["amountout"];
	$transid = $datac["transid"];
	$invoiceid = $datac["invoiceid"];
  	$gateway = $gatewaysarray[$gateway];
    if (!$gateway) {
    	$gateway="-";
    }
    $transactions[] = array(
    "date" => $date,
    "gateway" => $gateway,
	"description" => $description,
    "transid" => $transid,
    "amount" => formatCurrency($amountin-$amountout),
    );
}

###### START PDF INVOICE ######
#$pdf->setPageFormat( $pageformat, 'P');
$pdf->SetMargins(20, 15, 20); # set margins
$pdf->SetAutoPageBreak(TRUE, 5);
$preferences = array(
    'HideToolbar' => false,
    'HideMenubar' => true,
    'HideWindowUI' => true,
    'FitWindow' => true,
    'CenterWindow' => true,
    'DisplayDocTitle' => true,
    'NonFullScreenPageMode' => 'UseNone', // UseNone, UseOutlines, UseThumbs, UseOC
    'ViewArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'ViewClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintScaling' => 'AppDefault', // None, AppDefault
    'Duplex' => 'DuplexFlipLongEdge', // Simplex, DuplexFlipShortEdge, DuplexFlipLongEdge
    'PickTrayByPDFSize' => true,
    'PrintPageRange' => array(1,1,2,3),
    'NumCopies' => 1
);

// set pdf viewer preferences
$pdf->setViewerPreferences($preferences);

#$pdf->SetProtection(array('print'));
$pdf->SetCreator("Sparky's Mods");
if (!function_exists ('drawnewinvoicepage')){
function &drawnewinvoicepage($gatewaysarray,$balance,$invoicenum,$notes,$datecreated,$duedate,$paymentmethod,$clientsdetails,$invoicevars,$addressline,$companyaddress,$total,$invoiceprefix,$status,$statustext,$invoiceid,$datepaid,$CONFIG,$_LANG,$pdf){
global $data;
extract($invoicevars);
$pdf->SetDrawColorArray($bordercolor);
$pdf->SetFillColorArray($headerbgcolor);


if ($paymentmethod=="Credit Card - Visa or Mastercard") {
    $paymethodtext="\nPayment will be debited from your credit card on the night before the invoice due date.";
}



# DRAW ROUNDED BOXES
if ($pageformat == 'LETTER') {
	$pdf->RoundedRect(15, 15, 180, 260, 3.50, '1111', 'DF', null, $backcolor);
	$pdf->SetFooterMargin(5);
} else {
	$pdf->RoundedRect(15, 15, 180, 277, 3.50, '1111', 'DF', null, $backcolor);
	$pdf->SetFooterMargin(10);
}
$pdf->RoundedRect(20, 48, 80, 18, 3.50, '1111', 'DF', null, $boxbackcolor);
$pdf->RoundedRect(105, 48, 40, 18, 3.50, '1111', 'DF', null, $boxbackcolor);
$pdf->RoundedRect(150, 48, 40, 18, 3.50, '1111', 'DF', null, $boxbackcolor);
$pdf->RoundedRect(20, 67.5, 170, 157.5, 3.50, '1111', 'DF', null, $boxbackcolor);

# DRAW LINES
$pdf->SetDrawColorArray($bordercolor);
$pdf->SetLineWidth(1);
$pdf->Line(20,36.5,190,36.5,array('cap'=>'round'));
$pdf->Line(60,25,60,42,array('cap'=>'round'));
$pdf->SetLineWidth(0);
$pdf->Line(135,67.5,135,225);
$pdf->Line(100,233,180,233);
$pdf->Line(100,248,180,248);
$pdf->Line(20,230,190,230,array('dash'=>'4','color' => array(255, 0, 0)));
$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0))); 

# SHOW IMAGE

$setting = array();
$sresult = select_query_i("tblconfiguration","*");
while($data =  mysqli_fetch_array($sresult)) {
$setting[$data['setting']] = $data['value'];

}

$pdf->Image($setting['LogoURL'],20,23,0,9,'',$CONFIG['Domain'],'',true,150);

# SHOW COMPANY DETAILS
$pdf->SetFont('helvetica','',7);
$pdf->SetTextColorArray($textcolor);
$pdf->SetXY(62, 25);
foreach ($companyaddress AS $companyaddressline) {
	$tmpcompanyaddress .= "$companyaddressline\n";
}
$pdf->MultiCell(38,8,html_entity_decode($tmpcompanyaddress));

# SHOW CUSTOM INVOICE NAME IF SET
if ($custom_tax_name != "") {
	$pdf->SetXY(150,24);
	$pdf->SetFont('helvetica','B',19);
	$pdf->SetTextColorArray($taxnamecolor);
	$pdf->Cell(36,0,$custom_tax_name,0,0,'C');
}

# SHOW CUSTOM TAX NUMBER IF SET
if ($tax_number != "") {
	$pdf->SetXY(19,39);
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColorArray($textcolor);
	$pdf->Cell(36,0,$setting['gst'],0,0,'C');
}

# SHOW CLIENT DETAILS IN INVOICE TO
$pdf->SetFont('helvetica','B',8);
$pdf->SetXY(23,50);
foreach ($addressline AS $addressl) {
	$tmpaddressline .= "$addressl\n";
}
$pdf->MultiCell(76,15,html_entity_decode($tmpaddressline),0,'L');

# SHOW STATUS OF INVOICE
$pdf->SetXY(107,54);
if ($status=="Cancelled") {
	$statustext = $_LANG["invoicescancelled"];
    $pdf->SetTextColorArray($cancelledcolor);
} elseif ($status=="Unpaid") {
	$statustext = $_LANG["invoicesunpaid"];
    $pdf->SetTextColorArray($unpaidcolor);
} elseif ($status=="Paid") {
	$statustext = $_LANG["invoicespaid"];
    $pdf->SetTextColorArray($paidcolor);
} elseif($status=="Draft")
{
$statustext = "Draft";
 $pdf->SetTextColorArray($draftcolor);
}
elseif ($status=="Refunded") {
	$statustext = $_LANG["invoicesrefunded"];
    $pdf->SetTextColorArray($refundedcolor);
} elseif ($status=="Collections") {
	$statustext = $_LANG["invoicescollections"];
    $pdf->SetTextColorArray($collectionscolor);
}
$pdf->SetFont('helvetica','B',14);
$pdf->Cell(36,0,strtoupper($statustext),0,2,'C');

$pdf->SetTextColorArray($textcolor);
$pdf->SetFont('helvetica','B',8);
if ($status=="Cancelled") {
	
} elseif ($status=="Unpaid") {
	$pdf->SetFillColorArray($headerbgcolor);
	$pdf->SetLineWidth(0);
} elseif ($status=="Paid") {
	$pdf->SetFillColorArray($headerbgcolor);
	$pdf->SetLineWidth(0);
}


# SHOW TOTAL BALANCE OWING AND DUE BY
$pdf->SetFont('helvetica','B',7);
$pdf->SetTextColorArray($textcolor);
$pdf->SetXY(152,49);
$pdf->Cell(36,0,$totalpayable,0,2,'C');
$pdf->SetFont('helvetica','B',12);
$pdf->SetTextColorArray($unpaidcolor);
$pdf->Cell(36,0,$balance,0,2,'C');
$pdf->SetFont('helvetica','B',7);
$pdf->SetTextColorArray($textcolor);
$pdf->Cell(36,0,(($status == 'Paid' || $status == 'Cancelled') ? '' : $dueby),0,2,'C');
$pdf->SetFont('helvetica','B',12);
$pdf->SetTextColorArray($unpaidcolor);
$pdf->Cell(36,0,(($status == 'Paid' || $status == 'Cancelled') ? '' : $duedate),0,2,'C');

# RESET FONT SIZE AND COLOR
$pdf->SetFont('helvetica','B',8);
$pdf->SetTextColorArray($textcolor);

$pdf->Ln(10);

# LEFT COLUMN

$pdf->SetXY(22,70);
$pdf->SetDrawColorArray($bordercolor);
$pdf->SetFillColorArray($headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->SetFont('helvetica','',8);
$pdf->RoundedRect(22, 70, 110, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->Cell(80,5,$_LANG["invoicesdescription"],0,0,'L',0);
$pdf->Cell(30,5,$_LANG["invoicesamount"],0,0,'R',0);
$pdf->SetTextColorArray($textcolor);

# RIGHT COLUMN
$pdf->SetDrawColorArray($bordercolor);
$pdf->SetFillColorArray($headerbgcolor);
$pdf->SetFont('helvetica','',7);

# INVOICE INFORMATION
$pdf->SetXY(136,70);
$pdf->RoundedRect(136, 70, 53, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->Cell(53,5,$invoiceinfoheader,0,0,'L',0);
$pdf->SetXY(135,77);
$pdf->SetTextColorArray($textcolor);
$pdf->MultiCell(50,0,"$invoiceprefix $invoicenum\n".$_LANG["invoicesdatecreated"]." - $datecreated\n".$_LANG["invoicesdatedue"]." - $duedate\n",0,0);

# SHOW INVOICE DETAILS
$pdf->SetXY(136,90);
$pdf->RoundedRect(136, 90, 53, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->Cell(53,5,$accountinfoheader,0,0,'L',0);
$pdf->SetXY(135,97);
$pdf->SetTextColorArray($textcolor);
$pdf->MultiCell(53,0,"$accountnumber - ".$clientsdetails["userid"]."\n$accountname - ".$clientsdetails["firstname"]." ".$clientsdetails["lastname"]."\n$accountusername - ".$clientsdetails["email"]."\n",0,0);

# CONTACT INFORMATION
$pdf->SetXY(136,110);
$pdf->RoundedRect(136, 110, 53, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->Cell(53,5,$contactinfoheader,0,0,'L',0);
$pdf->SetXY(135,117);
$pdf->SetTextColorArray($textcolor);
$pdf->MultiCell(53,0,$setting['invwebsite'].$CONFIG['Domain']."\n".$setting['invemail'].$CONFIG['Email']."\n$contactphone - $phonenumber\n$contactfax - $faxnumber",0,'L',0);

# PAYMENT METHOD
$pdf->SetXY(136,136);
$pdf->RoundedRect(136, 136, 53, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
//$pdf->Cell(53,5,"Internet Banking/Direct Credit",0,0,'L',0);
$pdf->Cell(53,5,$paymentmethod,0,0,'L',0);
$pdf->SetXY(135,142);
$pdf->SetTextColorArray($textcolor);
$pdf->MultiCell(55,0,(($paymethodtext == "") ? $paymentmethod : $paymethodtext),0,'L',0);

# INVOICE NOTES
$pdf->SetXY(136,170);
$pdf->RoundedRect(136, 170, 53, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->Cell(53,5,$_LANG["invoicesnotes"],0,0,'L',0);
$pdf->SetXY(135,176);
$pdf->SetTextColorArray($textcolor);
if ($notes) {
	$pdf->MultiCell(55,0,$notes,0,'C',0);
}

# FOOTER
$pdf->SetXY(20,231.5);
$pdf->SetDrawColorArray($bordercolor);
$pdf->SetTextColorArray($textcolor);
$pdf->SetFont('helvetica','',9);

$pdf->Cell(20,0,$Remittance,0,0,'L',0);
$pdf->Cell(65,0,(($clientsdetails["companyname"])?$clientsdetails["companyname"]:$clientsdetails["firstname"]." ".$clientsdetails["lastname"]),0,1,'L',0);
$pdf->SetXY(20,235);
$pdf->Cell(85,0,"",0,0,'L',0);
$pdf->Cell(50,0,$invoiceprefix,0,0,'L',0);
$pdf->Cell(35,0,$invoicenum,0,0,'L',0);$pdf->Ln();
$pdf->Cell(85,0,"",0,0,'L',0);
$pdf->Cell(50,0,$accountnumber,0,0,'L',0);
$pdf->Cell(35,0,$clientsdetails["userid"],0,0,'L',0);$pdf->Ln();
$pdf->Cell(20,0,$remittanceto,0,0,'L',0);
$pdf->Cell(65,0,$setting['invcompany'],0,0,'L',0);
$pdf->Cell(50,0,$remittancetotaldue,0,0,'L',0);
$pdf->Cell(35,0,$balance,0,0,'L',0);$pdf->Ln();
$pdf->Cell(20,0,"",0,0,'L',0);
$pdf->Cell(65,0,$setting['invpobox'],0,0,'L',0);
$pdf->Cell(50,0,"",0,0,'L',0);
$pdf->Cell(35,0,"",0,0,'L',0);$pdf->Ln();
$pdf->Cell(20,0,"",0,0,'L',0);
$pdf->Cell(65,0,$setting['invcity'],0,0,'L',0);
$pdf->Cell(85,0,"",0,0,'L',0);$pdf->Ln();
$pdf->Cell(20,0,"",0,0,'L',0);
$pdf->Cell(65,0,$setting['invpostcode'],0,0,'L',0);
$pdf->Cell(85,0,"",0,0,'L',0);$pdf->Ln();
$pdf->Cell(20,0,"",0,0,'L',0);
$pdf->Cell(65,0,$setting['invcountry'],0,0,'L',0);
$pdf->Ln();
$pdf->SetFont('helvetica','',8);
$pdf->SetXY(20,($pageformat=='A4') ? 269 : 290);
$pdf->MultiCell(170,0,$Remittance1,0,'C',0);
$pdf->MultiCell(170,0,"Acc Number:".$setting['invaccount'],0,'C',0);
$pdf->MultiCell(170,0,"Acc Name:".$setting['invname'],0,'C',0);
}
}

drawnewinvoicepage($gatewaysarray,$balance,$invoicenum,$notes,$datecreated,$duedate,$paymentmethod,$clientsdetails,$invoicevars,$addressline,$companyaddress,$total,$invoiceprefix,$status,$statustext,$invoiceid,$datepaid,$CONFIG,$_LANG,$pdf);

$pdf->SetXY(20,77);
$last=count($invoiceitems);
$i=1;
foreach ($invoiceitems AS $item) {
	$startx = $pdf->GetX();
    $starty = $pdf->GetY();
	if ($item["taxed"] && $taxname) { $pdf->Cell(2,0,''); } else { $pdf->Cell(2,0); }
    $pdf->MultiCell(80, 0, $item["description"], 0, 'L');
	$finishy = $pdf->GetY();
	$pdf->SetXY($startx+82,$starty);
    $pdf->MultiCell(30,$finishy-$starty,$item["amount"],0,'R','',1);
	if ($pdf->GetY()>=210) {
		$pdf->AddPage();
		drawnewinvoicepage($gatewaysarray,$balance,$invoicenum,$notes,$datecreated,$duedate,$paymentmethod,$clientsdetails,$invoicevars,$addressline,$companyaddress,$total,$invoiceprefix,$status,$statustext,$invoiceid,$datepaid,$CONFIG,$_LANG,$pdf);
		$pdf->SetXY(20,77);
	}

	if ($i==$last) {
		# TOTALS
		if ($taxname) {
			$pdf->SetFont('helvetica','B',8);
			$pdf->SetY($pdf->GetY()+2);
		}
		$pdf->Line(22,$pdf->GetY()+1,132.5,$pdf->GetY()+1);
		$pdf->SetY($pdf->GetY()+2);
		$pdf->Cell(2,0);
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(80,0,$_LANG["invoicessubtotal"],0,0,'R',0);
		$pdf->Cell(30,0,$subtotal,0,0,'R',0);
		$pdf->Ln();$pdf->Cell(2,0);
		if ($taxname) {
			$pdf->SetFont('helvetica','B',11);
			$pdf->Cell(80,0,$taxrate.'% '.$taxname,0,0,'R',0);
			$pdf->Cell(30,0,$tax,0,0,'R',0);
			$pdf->Ln();$pdf->Cell(2,0);
		}

		if ($taxname2) {
			$pdf->Cell(80,0,$taxrate2."% ".$taxname2,0,0,'R',0);
			$pdf->Cell(30,0,$tax2,0,0,'R',0);
			$pdf->Ln();$pdf->Cell(2,0);
		}
		global $data;
		if ($data["credit"]>0){
			$pdf->Cell(80,0,$_LANG["invoicescredit"],0,0,'R',0);
			$pdf->Cell(30,0,$credit,0,0,'R',0);
			$pdf->Ln();$pdf->Cell(2,0);
		}
		$pdf->Cell(80,0,"Total Due",0,0,'R',0);
		$pdf->Cell(30,0,$total,0,0,'R',0);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell(2,0);
	}
$i++;
}

$pdf->SetDrawColorArray($bordercolor);
$pdf->SetFillColorArray($headerbgcolor);
$pdf->SetFont('helvetica','',8);
$pdf->RoundedRect($pdf->GetX(), $pdf->GetY(), 110, 5, 1.25, '1111', 'DF', null, $headerbgcolor);
$pdf->SetTextColorArray($headerfgcolor);
$pdf->Cell(25,5,$_LANG["invoicestransdate"],0,0,'C','0');
$pdf->Cell(25,5,$_LANG["invoicesdescription"],0,0,'C','0');
$pdf->Cell(35,5,$_LANG["invoicestransid"],0,0,'C','0');
$pdf->Cell(25,5,$_LANG["invoicestransamount"],0,1,'R','0');
$pdf->SetTextColorArray($textcolor);


$pdf->SetFont('helvetica','',7);
$pdf->SetY($pdf->GetY()+.5);

if(count($transactions) > 0) {
	foreach ($transactions AS $tranitem) {
	
		$startx = $pdf->GetX();
    	$starty = $pdf->GetY();

		$pdf->Cell(2,0);
    	$pdf->MultiCell(25,0,$tranitem["date"],0,'C','',0);
		$pdf->MultiCell(25,0,$tranitem["description"],0,'C','',0);
		$pdf->MultiCell(35,0,$tranitem["transid"],0,'C','',1);

    	$finishy = $pdf->GetY();
    	$pdf->SetXY($startx+87,$starty);

    	$pdf->MultiCell(25,$finishy-$starty,$tranitem["amount"],0,'R','',1);

    	if ($pdf->GetY()>=210) {
		$pdf->AddPage();
		drawnewinvoicepage($gatewaysarray,$balance,$invoicenum,$notes,$datecreated,$duedate,$paymentmethod,$clientsdetails,$invoicevars,$addressline,$companyaddress,$total,$invoiceprefix,$status,$statustext,$invoiceid,$datepaid,$CONFIG,$_LANG,$pdf);
		$pdf->SetXY(20,77);
	}
	}
} else {
	$pdf->Cell(2,0);
	$pdf->Cell(110,7,$_LANG["invoicestransnonefound"],0,1,'C','0');
}

$pdf->Line(22,$pdf->GetY()+1,132.5,$pdf->GetY()+1);
$pdf->SetY($pdf->GetY()+2);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(2,0);
$pdf->Cell(80,7,"Balance Remaining",0,0,'R','0');
$pdf->Cell(30,7,$balance,0,0,'R','0');
$pdf->SetFont('helvetica','',7);

?>
