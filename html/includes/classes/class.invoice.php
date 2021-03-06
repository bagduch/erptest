<?php

// vim: ai ts=4 sts=4 et sw=4 ft=php

class RA_Invoice {

    public $pdf = "";
    private $invoiceid = ""; // assigned by autoincrement sql
    private $invoicenum = "";
    private $data = array();
    private $output = array();
    private $userid;
    private $totalbalance = 0;

    // constructor for invoices
    // if invoiceid is passed, we pull details from the database
    // otherwise, create and write to DB a new invoice
    public function __construct($invoiceid = null) {

        if ($invoiceid) {
            $this->setID($invoiceid);
        }

        $setting = array();
        $sresult = select_query_i("ra_config", "*");
        while ($data = mysqli_fetch_array($sresult)) {
            $setting[$data['setting']] = $data['value'];
        }
        // echo "<pre>", print_r($data, 1), "</pre>";
    }

    public function setID($invoiceid) {
        $this->invoiceid = $invoiceid;
        return true;
    }

    public function getID() {
        return (int) $this->invoiceid;
    }

    public function loadData($force = true) {
        if (!$force && count($this->data)) {
            return false;
        }

        $result = select_query_i(
            "ra_bills", 
            "ra_bills.*,
            (
                SELECT value
                FROM ra_modules_gateways
                WHERE gateway=ra_bills.paymentmethod
                AND setting='name' LIMIT 1
            ) AS gateway,
            IFNULL(
                (SELECT SUM(amountin-amountout) FROM ra_transactions WHERE invoiceid=ra_bills.id),
                0
            ) as amountpaid", 
            array("id" => $this->invoiceid));
  

        $data = mysqli_fetch_assoc($result);


        if (!$data['id']) {
            return false;
        }

        $data['invoiceid'] = $data['id'];
        $data['invoicenumorig'] = $data['invoicenum'];

        if (!$data['invoicenum']) {
            $data['invoicenum'] = $data['id'];
        }

        $data['paymentmodule'] = $data['paymentmethod'];
        $data['paymentmethod'] = $data['gateway'];
        $data['balance'] = sprintf("%01.2f", $data['total'] - $data['amountpaid']);
        $this->data = $data;
        return true;
    }

    public function getData($var = "") {
        $this->loadData(false);

        return isset($this->data[$var]) ? $this->data[$var] : $this->data;
    }

    public function getStatuses() {
        return array("Unpaid", "Paid", "Cancelled", "Refunded", "Collections");
    }

    public function isAllowed($uid = "") {
        $this->loadData(false);

        if (!$uid) {
            $uid = $_SESSION['uid'];
        }


        if (!$uid || $this->data['userid'] != $uid) {
            return false;
        }

        return true;
    }

    public function formatForOutput() {
        global $ra;
        global $currency;

        $this->output = $this->data;
        $array = array("date", "duedate", "datepaid");
        foreach ($array as $v) {
            $this->output[$v] = (substr($this->output[$v], 0, 10) != "0000-00-00" ? fromMySQLDate($this->output[$v], ($v == "datepaid" ? "1" : "0"), 1) : "");
        }

        $this->output['datecreated'] = $this->output['date'];
        $this->output['datedue'] = $this->output['duedate'];
        $currency = getCurrency($this->getData("userid"));
        $array = array("subtotal", "credit", "tax", "tax2", "total", "balance", "amountpaid");
        foreach ($array as $v) {
            $this->output[$v] = formatCurrency($this->output[$v]);
        }


        if (!function_exists("getClientsDetails")) {
            require ROOTDIR . "/includes/clientfunctions.php";
        }

        $clientsdetails = getClientsDetails($this->getData("userid"), "billing");

        $clientsdetails['country'] = $clientsdetails['countryname'];
        $this->output['clientsdetails'] = $clientsdetails;
        $customfields = array();
        $result = select_query_i("ra_catalog_user_sales_fields", "ra_catalog_user_sales_fields.cfid,ra_catalog_user_sales_fields.fieldname,(SELECT value FROM ra_catalog_user_sales_fieldsvalues WHERE ra_catalog_user_sales_fieldsvalues.fieldid=ra_catalog_user_sales_fields.cfid AND ra_catalog_user_sales_fieldsvalues.relid=" . (int) $this->getData("userid") . ") AS value", array("type" => "client", "showinvoice" => "on"));

        while ($data = mysqli_fetch_assoc($result)) {
            if ($data['value']) {
                $customfields[] = $data;
            }
        }

        $this->output['customfields'] = $customfields;

        if (0 < $this->getData("taxrate")) {
            $taxname = getTaxRate(1, $clientsdetails['state'], $clientsdetails['countrycode']);
            $this->output['taxname'] = $taxname['name'];
        } else {
            $this->output['taxrate'] = "0";
        }


        if (0 < $this->getData("taxrate2")) {
            $taxname = getTaxRate(2, $clientsdetails['state'], $clientsdetails['countrycode']);
            $this->output['taxname2'] = $taxname['name'];
        } else {
            $this->output['taxrate2'] = "0";
        }

        $this->output['statuslocale'] = $ra->get_lang("invoices" . strtolower($this->output['status']));
        $this->output['pagetitle'] = $ra->get_lang("invoicenumber") . $this->getData("invoicenum");
        $this->output['payto'] = nl2br($ra->get_config("InvoicePayTo"));
        $this->output['notes'] = nl2br($this->output['notes']);

        $this->output['subscrid'] = get_query_val("ra_bill_lineitems", "tblcustomerservices.subscriptionid", "ra_bill_lineitems.type='Hosting' AND ra_bill_lineitems.invoiceid=" . $this->getData("id") . " AND tblcustomerservices.subscriptionid!=''", "tblhosting`.`id", "ASC", "", "tblcustomerservices ON tblcustomerservices.id=ra_bill_lineitems.relid");
        $clienttotals = get_query_vals("ra_bills", "SUM(credit),SUM(total)", array("userid" => $this->getData("userid")));
        $alldueinvoicespayments = get_query_val("ra_transactions", "SUM(amountin-amountout)", "invoiceid IN (SELECT id FROM ra_bills WHERE userid=" . (int) $this->getData("userid") . " AND status='Unpaid')");

        $this->output['clienttotaldue'] = formatCurrency($clienttotals[0] + $clienttotals[1]);
        $this->output['clientpreviousbalance'] = formatCurrency($clienttotals[1] - $this->getData("total"));
        $this->output['clientbalancedue'] = formatCurrency($clienttotals[1] - $alldueinvoicespayments);
        $lastpayment = get_query_vals("ra_transactions", "(amountin-amountout),transid", array("invoiceid" => $this->getData("id")), "id", "DESC");
        $this->output['lastpaymentamount'] = formatCurrency($lastpayment[0]);
        $this->output['lastpaymenttransid'] = $lastpayment[1];
    }

    public function getOutput($pdf = false) {

        $this->loadData(false);

        $this->formatForOutput();

        if ($pdf) {
            $this->makePDFFriendly();
        }

        return $this->output;
    }

    public function getPaymentLink() {
        if (!function_exists("getGatewayVariables")) {
            require ROOTDIR . "/includes/gatewayfunctions.php";
        }

        $params = getGatewayVariables($this->getData("paymentmodule"), $this->getData("invoiceid"), $this->getData("balance"));
        $paymentbutton = (function_exists($this->getData("paymentmodule") . "_link") ? call_user_func($this->getData("paymentmodule") . "_link", $params) : "");
        return $paymentbutton;
    }

    public function getLineItems($entitydecode = false) {
        global $ra;

        getUsersLang($this->getData("userid"));
        $invoiceid = $this->getID();
        $invoiceitems = array();

        if ($ra->get_config("GroupSimilarLineItems")) {
            $result = full_query_i("SELECT COUNT(*),id,type,relid,description,amount,taxed FROM ra_bill_lineitems WHERE invoiceid=" . (int) $invoiceid . " GROUP BY `description`,`amount` ORDER BY id ASC");
        } else {
            $result = select_query_i("ra_bill_lineitems", "0,id,type,relid,description,amount,taxed", array("invoiceid" => $invoiceid), "id", "ASC");
        }


        while ($data = mysqli_fetch_array($result)) {
            $qty = $data[0];
            $description = $data[4];
            $amount = $data[5];
            $taxed = ($data[6] ? true : false);

            if (1 < $qty) {
                $description = $qty . " x " . $description . " @ " . $amount . $ra->get_lang("invoiceqtyeach");
                $amount *= $qty;
            }


            if ($entitydecode) {
                $description = htmlspecialchars(html_entity_decode($description, ENT_QUOTES));
            } else {
                $description = nl2br($description);
            }

            $invoiceitems[] = array("id" => $data[1], "type" => $data[2], "relid" => $data[3], "description" => $description, "rawamount" => $amount, "amount" => formatCurrency($amount), "taxed" => $taxed);
        }

        return $invoiceitems;
    }

    public function getTransactions() {
        $invoiceid = $this->invoiceid;
        $transactions = array();
        $result = select_query_i("ra_transactions", "id,date,transid,amountin,amountout,(SELECT value FROM ra_modules_gateways WHERE gateway=ra_transactions.gateway AND setting='name' LIMIT 1) AS gateway", array("invoiceid" => $invoiceid), "date` ASC,`id", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $tid = $data['id'];
            $date = $data['date'];
            $gateway = $data['gateway'];
            $amountin = $data['amountin'];
            $amountout = $data['amountout'];
            $transid = $data['transid'];
            $date = fromMySQLDate($date, 0, 1);

            if (!$gateway) {
                $gateway = "-";
            }

            $transactions[] = array("id" => $tid, "date" => $date, "gateway" => $gateway, "transid" => $transid, "amount" => formatCurrency($amountin - $amountout));
        }

        return $transactions;
    }

    public function pdfCreate() {
        global $ra;

        $l = array();
        $l['a_meta_charset'] = $ra->get_config("Charset");
        $l['a_meta_dir'] = "ltr";
        $l['a_meta_language'] = "en";
        $l['w_page'] = "page";
        $unicode = (strtolower(substr($ra->get_config("Charset"), 0, 3)) == "iso" ? false : true);
        $this->pdf = new TCPDF("P", "mm", "A4", $unicode, $ra->get_config("Charset"), false);
        $this->pdf->SetCreator("ra V" . $ra->get_config("Version"));
        $this->pdf->SetAuthor($ra->get_config("CompanyName"));
        $this->pdf->SetMargins(15, 25, 15);
        $this->pdf->SetFooterMargin(15);
        $this->pdf->SetAutoPageBreak(TRUE, 25);
        $this->pdf->setLanguageArray($l);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        return $this->pdf;
    }

    public function makePDFFriendly() {
        global $ra;

        $this->output['companyname'] = $ra->get_config("CompanyName");
        $this->output['companyurl'] = $ra->get_config("Domain");
        $companyaddress = $ra->get_config("InvoicePayTo");
        $this->output['companyaddress'] = explode("\r\n", $companyaddress);

        if (trim($this->output['notes'])) {
            $this->output['notes'] = str_replace("<br />", "", $this->output['notes']) . "\r\n";
        }

        $this->output = $this->pdfEntityDecode($this->output);
        return true;
    }

    public function pdfEntityDecode($vars) {
        foreach ($vars as $k => $v) {

            if (is_array($v)) {
                $vars[$k] = $this->pdfEntityDecode($v);
                continue;
            }

            $vars[$k] = html_entity_decode($v, ENT_QUOTES);
        }

        return $vars;
    }

    public function pdfInvoicePage($invoiceid = "") {
        global $ra;
        global $currency;

        if ($invoiceid) {
            $this->setID($invoiceid);
            $invoiceexists = $this->loadData();

            if (!$invoiceexists) {
                return false;
            }
        }

        $this->pdf->SetTitle($ra->get_lang("invoicenumber") . $this->getData("invoicenum"));

        $tplvars = $this->getOutput(true);

        $invoiceitems = $this->getLineItems(true);
        $tplvars['invoiceitems'] = $invoiceitems;
        $transactions = $this->getTransactions();
        $tplvars['transactions'] = $transactions;
        $this->pdfAddPage("invoicepdf.tpl", $tplvars);

        return true;
    }

    public function pdfLateFee($latefeeid = "") {
        global $ra;
        global $currency;

        if ($invoiceid) {

            $latefeeid = get_query_val("tblinvoice", "latefeeid", array("id" => $invoiceid));
            if (isset($latefeeid)) {
                $this->setID($latefeeid);
            }
            $invoiceexists = $this->loadData();

            if (!$invoiceexists) {
                return false;
            }
        }

        $this->pdf->SetTitle($ra->get_lang("invoicenumber") . $this->getData("invoicenum"));
        $tplvars = $this->getOutput(true);
        $invoiceitems = $this->getLineItems(true);
        $tplvars['invoiceitems'] = $invoiceitems;
        $transactions = $this->getTransactions();
        $tplvars['transactions'] = $transactions;
        $this->pdfAddPage("invoicepdf.tpl", $tplvars);

        return true;
    }

    public function pdfHtmlpage($tplfile, $tplvars) {
        global $ra;
        global $_LANG;

        $this->pdf->AddPage();
        $this->pdf->SetFont("freesans", "", 10);
        $this->pdf->SetTextColor(0);
        ob_start();
        include ROOTDIR . "/templates/" . $ra->get_sys_tpl_name() . "/pdf/" . $tplfile;
        $pdf = ob_get_clean();
        $this->pdf->WriteHtml($pdf);
        return true;
    }

    public function pdfAddPage($tplfile, $tplvars) {
        global $ra;
        global $_LANG;

        $this->pdf->AddPage();
        $this->pdf->SetFont("freesans", "", 10);
        $this->pdf->SetTextColor(0);
        foreach ($tplvars as $k => $v) {
            $$k = $v;
        }

        $pdf = &$this->pdf;

        include ROOTDIR . "/templates/" . $ra->get_sys_tpl_name() . "/" . $tplfile;
        return true;
    }

    public function pdfOutput() {
        return $this->pdf->Output("", "S");
    }

    public function getInvoices($status = "", $userid = "", $orderby = "id", $sort = "DESC", $limit = "") {
        global $ra;

        $where = array();

        if ($status) {
            $where['status'] = $status;
        }


        if ($userid) {
            $where['userid'] = $userid;
        }

        $where["(select count(id) from ra_bill_lineitems where invoiceid=ra_bills.id and type='Invoice')"] = array("sqltype" => "<=", "value" => 0);
        $invoices = array();
        $result = select_query_i("ra_bills", "ra_bills.*,total-COALESCE((SELECT SUM(amountin-amountout) FROM ra_transactions WHERE ra_transactions.invoiceid=ra_bills.id),0) AS balance", $where, $orderby, $sort, $limit);

        while ($data = mysqli_fetch_array($result)) {
            $id = $data['id'];
            $invoicenum = $data['invoicenum'];
            $date = $data['date'];
            $duedate = $data['duedate'];
            $credit = $data['credit'];
            $total = $data['total'];
            $balance = $data['balance'];
            $status = $data['status'];

            if ($status == "Unpaid") {
                $this->totalbalance += $balance;
            }

            $date = fromMySQLDate($date, 0, 1);
            $duedate = fromMySQLDate($duedate, 0, 1);
            $rawstatus = strtolower($status);
            $overdue = 0;

            if (!$invoicenum) {
                $invoicenum = $id;
            }

            $invoices[] = array("id" => $id, "invoicenum" => $invoicenum, "datecreated" => $date, "datedue" => $duedate, "total" => formatCurrency($credit + $total), "balance" => formatCurrency($balance), "status" => getInvoiceStatusColour($status), "rawstatus" => $rawstatus, "statustext" => $ra->get_lang("invoices" . $rawstatus));
        }

        return $invoices;
    }

    public function getTotalBalance() {
        return $this->totalbalance;
    }

    public function getTotalBalanceFormatted() {
        return formatCurrency($this->getTotalBalance());
    }

    public function getEmailTemplates() {
        $status = $this->getData("status");
        $validtpls = array();
        $result = select_query_i("ra_templates_mail", "id,name", array("type" => "invoice", "language" => ""), "name", "ASC");

        while ($data = mysqli_fetch_array($result)) {
            $validtpls[$data['name']] = $data['id'];
        }

        $emailtplsoutput = array("Invoice Created", "Credit Card Invoice Created", "Invoice Payment Reminder", "First Invoice Overdue Notice", "Second Invoice Overdue Notice", "Third Invoice Overdue Notice", "Credit Card Payment Due", "Credit Card Payment Failed", "Invoice Payment Confirmation", "Credit Card Payment Confirmation", "Invoice Refund Confirmation");

        if ($status == "Paid") {
            $emailtplsoutput = array_merge(array("Invoice Payment Confirmation", "Credit Card Payment Confirmation"), $emailtplsoutput);
        }


        if ($status == "Refunded") {
            $emailtplsoutput = array_merge(array("Invoice Refund Confirmation"), $emailtplsoutput);
        }

        $returntpls = array();
        foreach ($emailtplsoutput as $tplname) {

            if (array_key_exists($tplname, $validtpls)) {
                $returntpls[] = array("name" => $tplname);
                unset($validtpls[$tplname]);
                continue;
            }
        }

        foreach ($validtpls as $tplname => $k) {
            $returntpls[] = array("name" => $tplname);
        }

        return $returntpls;
    }

    public function getFriendlyPaymentMethod() {
        global $aInt;

        $credit = $this->getData("credit");
        $result = select_query_i("ra_transactions", "COUNT(id),SUM(amountin)-SUM(amountout)", array("invoiceid" => $this->getData("id")));
        $data = mysqli_fetch_array($result);
        $transcount = $data[0];
        $amountpaid = $data[1];

        if ($this->getData("status") == "Unpaid") {
            $paymentmethodfriendly = $this->getData("paymentmethod");
        } else {
            if ($transcount == 0) {
                $paymentmethodfriendly = $aInt->lang("invoices", "notransapplied");
            } else {
                $paymentmethodfriendly = $this->getData("paymentmethod");
            }
        }


        if (0 < $credit) {
            if ($total == 0) {
                $paymentmethodfriendly = $aInt->lang("invoices", "fullypaidcredit");
            } else {
                $paymentmethodfriendly .= " + " . $aInt->lang("invoices", "partialcredit");
            }
        }

        return $paymentmethodfriendly;
    }

    public function getBalanceFormatted() {
        global $currency;

        $userid = $this->getData("userid");
        $currency = getCurrency($userid);
        $balance = $this->getData("balance");
        return "<span class=\"" . (0 < $balance ? "textred" : "textgreen") . "\">" . formatCurrency($balance) . "</span>";
    }

    public function sendEmailTpl($tplname) {
        return sendMessage($tplname, $this->getData("id"));
    }

}

?>
