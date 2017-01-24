<?php
ini_set("error_log","/tmp/guydev.utilities.log");
ini_set("display_errors",0);
ini_set("log_errors",1);

include '../include/functions.php';
include '../include/class.hbwrapper.php';
include '../../dbconnect.php';
$hbsqli = new mysqli("hc2.hd.net.nz","unlimite_radev","m[U*gUZFgu&d", "unlimite_hostbill");


echo "<pre>";
delete_query("tblclients",array(id=>8017));
HBWrapper::setAPI('https://my.unlimitedinternet.co.nz/admin/api.php','1d964d625b897c3a2e5d','2d485b0d0f73f5a24546');
$params = array('id'=>8017);
$hbclients = HBWrapper::singleton()->getClientDetails($params);
error_log(print_r($hbclients),1);
foreach ($hbclients as $client) {
    $clientid = $client['id'];
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

    if ($hbsqli_clientnotes = $hbsqli->prepare("SELECT id,admin_id,date,note FROM hb_notes WHERE type LIKE 'client' AND rel_id = ?")) {
        $hbsqli_clientnotes->bind_param('i',$clientid);
        $hbsqli_clientnotes->execute();
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $hbsqli_clientnotes->bind_result($note['id'],$note['admin_id'],$note['date'],$note['note']);
        while ($hbsqli_clientnotes->fetch()) {
            var_dump($note);
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
           
    HBWrapper::setAPI('https://my.unlimitedinternet.co.nz/admin/api.php','1d964d625b897c3a2e5d','2d485b0d0f73f5a24546');
   $accounts = HBWrapper::singleton()->getAccountDetails($client['id']);
    var_dump($accounts);

}   
$result=select_query_i("tblclients","",array(id=>8017));
var_dump($result);
while ($clientres = $result->fetch_assoc()) {
    var_dump($clientres);
}

/* foreach ($hbaccounts['accounts'] as $hbaccount) {
    error_log(print_r($hbaccount,1));
    error_log(print_r(HBWrapper::singleton()->getAccountDetails($hbaccount)),1);
} */

echo "</pre>";
?>
