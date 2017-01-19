<?php
ini_set("error_log","/tmp/guydev.utilities.log");
ini_set("display_errors",0);
ini_set("log_errors",1);


include '../include/class.hbwrapper.php';
include '../../dbconnect.php';
include '../include/hostbill_dbconnect.php';

echo "<pre>";
delete_query("tblclients",array(id=>8017));
HBWrapper::setAPI('https://my.unlimitedinternet.co.nz/admin/api.php','1d964d625b897c3a2e5d','2d485b0d0f73f5a24546');
  $params = array(
            'id'=>8017
        );
   $hbclients = HBWrapper::singleton()->getClientDetails($params);
error_log(print_r($hbclients),1);
foreach ($hbclients as $client) {
    insert_query("tblclients",array(
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
          defaultgateway=> ($client["cardtype"] == "Visa") ? "paystation" : "banktransfer",
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
HBWrapper::setAPI('https://my.unlimitedinternet.co.nz/admin/api.php','1d964d625b897c3a2e5d','2d485b0d0f73f5a24546');
           $accounts = HBWrapper::singleton()->getAccountDetails($client['id']);
           var_dump($accounts);
    //    var_dump($client);
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
