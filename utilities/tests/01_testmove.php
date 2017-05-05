<?php
ini_set("error_log","/tmp/guydev.utilities.log");
ini_set("display_errors",0);
ini_set("log_errors",1);

include '../include/functions.php';
include '../include/class.hbwrapper.php';
include '../../dbconnect.php'; // $ramyqli exists
$hbsqli = new mysqli("hc2.hd.net.nz","unlimite_radev","m[U*gUZFgu&d", "unlimite_hostbill");

echo "<pre>";
//var_dump(get_defined_vars());


$clientid=8017;

// move products
$ramysqli->query("DELETE FROM tblservicegroups");
$ramysqli->query("DELETE FROM tblservices");
$ramysqli->query("DELETE FROM tblclients");
$ramysqli->query("DELETE FROM tblnotes");
$ramysqli->query("DELETE FROM tblorders");


// move categories
if ($result = $hbsqli->query("SELECT * FROM hb_categories")) {
    while ($row = $result->fetch_assoc()) {
        insert_query("tblservicegroups",
            array(
                id=>$row['id'],
                name=>$row['name'],
                type=>'service',
                orderfrmtpl=>$row['template'],
                disabledgateways=>null,
                hidden=>($row['visible'] == 0) ? 1 : 0,
                order=>$row['sort_order']
            ));
    }
}

// move products
 if ($result = $hbsqli->query("SELECT * FROM hb_products")) {
     while ($row = $result->fetch_assoc()) {
            insert_query("tblservices",
               array(
                   id=> $row['id'],
                   gid=> $row['category_id'],
                   type=>$row['type'],
                   name=>$row['name'],
                   description=>$row['description'],
                   hidden=>($row['visible'] == 0) ? 1 : 0,
                   retired=>($row['visible'] == 0) ? 1 : 0,
                    tax=>$row['tax']

               ));
        }
 }



// get desired clients
if ($result = $hbsqli->query("
    SELECT 
        hb_cd.id
 FROM hb_client_details hb_cd
    WHERE id=8017")) {
    var_dump($result);
    while ($row = $result->fetch_assoc()) {
        $clientids[] = (int)$row['id'];
    }
}

foreach ($clientids as $clientid) {
    var_dump($clientid);
    HBWrapper::setAPI('https://my.unlimitedinternet.co.nz/admin/api.php','1d964d625b897c3a2e5d','2d485b0d0f73f5a24546');
    $hbclient = HBWrapper::singleton()->getClientDetails(array(id=>$clientid));
    $client = $hbclient["client"];
    var_dump($client);

    insert_query("tblclients",
        array(
            id => $client['id'],
            firstname=>$client["firstname"],
            lastname=>$client["lastname"],
            //companyname
            email=>$client["email"],
            address1=>$client["address1"],
            address2=>$client["address2"],
            city=>$client["city"],
            state=>$client["state"],
            postcode=>$client["postcode"],
            country=> $client["countryname"],
            phonenumber=>$client["phonenumber"],
            mobilenumber=>$client["mobilenumber"],
            password=>$client["password"],
            // authmodule
            // authdata
            currency => 1, // NZD
            defaultgateway => ($client["cardtype"] == "Visa") ? "paystation" : "banktransfer",
            credit=>$client["credit"],
            taxexempt=>$client["taxexempt"],
            latefeeoveride=>$client["latefeeoverride"],
            overideduenotices=>$client["overideduenotices"],
            separateinvoices=>0,
            disableautocc=>0,
            datecreated=>$client["datecreated"],
            notes => "TODO", // FIXME
            billingcid=>0,
            securityqid=>NULL,
            securityqans=>NULL,
            groupid=>0,
            lastlogin=>$client["lastlogin"],
            ip=>$client["ip"],
            host=>$client["host"],
            state=>"Active",
            language=>"English",
            // pwresetkey
            // pwresetexpiry
            emailoptout=>0,
            overrideautoclose=>0,
            dateofbirth=>"0000-00-00"
        )
    );   


            // move clientnotes
    if ($hbsqli_clientnotes = $hbsqli->prepare("SELECT id,admin_id,date,note FROM hb_notes WHERE type LIKE 'client' AND rel_id = ?")) {
        $hbsqli_clientnotes->bind_param('i',$clientid);
        $hbsqli_clientnotes->execute();
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $hbsqli_clientnotes->bind_result($note['id'],$note['admin_id'],$note['date'],$note['note']);
        while ($hbsqli_clientnotes->fetch()) {
            insert_query("tblnotes",
                array(
                    userid => $clientid,
                    adminid => map_hbadmin_raadmin($note['admin_id']), 
                    created => $note['date'],
                    modified => $note['date'],
                    note => $note['note'],
                    sticky => 1
                )
            );
        }

    }
           
}

// copy orders
//
$query = sprintf("SELECT * FROM hb_orders WHERE  client_id=%s",$clientid);
if ($result = $hbsqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        insert_query("tblorders",array(
            id=>$row['id'],
            ordernum=>$row['number'],
            userid=>$row['client_id'],
            contactid=>NULL,
            date=>$row['date_created'],
            nameservers=>NULL,
            promocode=>NULL,
            promotype=>NULL,
            promovalue=>NULL,
            orderdata=>NULL,
            amount=>$row['total'],
            paymentmethod=>$row['payment_module'],
            invoiceid=>$row['invoice_id'],
            status=>$row['status'],
            ipaddress=>$row['order_ip'],
            fraudmodule=>NULL,
            fraudoutput=>NULL,
            notes=>$row['notes']
        ));
    }
}


// move accounts -> services
$query = sprintf("SELECT * FROM hb_accounts WHERE client_id=%s",$clientid);
if ($result = $hbsqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        //        var_dump($row);
        insert_query("tblcustomerservices",array(
            id=>$row['id'],
            userid=>$row['client_id'],
            assign_id=>NULL,
            orderid=>$row['order_id'],
            packageid=>$row['product_id'],
            parent=>NULL,
            regdate=>$row['date_created'],
            description=>$row['domain'],
            paymentmethod=>$row['payment_module'],
            firstpaymentamount=>$row['firstpayment'],
            amount=>$row['total'],
            billingcycle=>$row['billingcycle'],
            nextduedate=>$row['next_due'],
            nextinvoicedate=>$row['next_invoice'],
            servicestatus=>$row['status'],
            billstatus=>$row['status'],
            suspendreason=>$row['autosuspend'],
            overideautosuspend=>($row['autosuspend'] == 0) ? 1 : 0,
            overidesuspenduntil=>$row['autosuspend_date'],
            lastupdate=>$row['date_changed'],
            notes=>$row['notes']
        ));
    }
}
$result=select_query_i("tblclients","",array(id=>8017));
while ($clientres = $result->fetch_assoc()) {
}

/* foreach ($hbaccounts['accounts'] as $hbaccount) {
    error_log(print_r($hbaccount,1));
    error_log(print_r(HBWrapper::singleton()->getAccountDetails($hbaccount)),1);
} */

echo "</pre>";
?>
