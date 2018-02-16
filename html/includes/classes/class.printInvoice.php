<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PrintInvoice {

    public $invoiceid;
    protected $invoiceData;
    protected $invoicenum;
    protected $invoice;

    public function __construct($invoiceid, $invoicenum) {
        $this->invoiceid = $invoiceid;
        $this->invoice = new RA_Invoice($this->invoiceid);
        $result = select_query_i("invoiceData", "", array("id" => $invoiceid));
        $data = mysqli_fetch_assoc($result);
        if ($data['id']) {
            exit("Invalid Access Attempt");
        }
        $this->invoiceData = $data;
        if (!$invoiceid) {
            redir("", "clientarea.php");
        }
        if (!isset($_SESSION['adminid']) && $_SESSION['uid'] != $data['userid']) {
            downloadLogin();
        }
        if (!$invoicenum) {
            $this->invoicenum = $invoiceid;
        }
    }

    protected function head($pdfdata, $viewpdf) {
        header("Pragma: public");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0, private");
        header("Cache-Control: private", false);
        header("Content-Type: application/pdf");
        header("Content-Disposition: " . ($viewpdf ? "inline" : "attachment") . "; filename=\"Invoice-" . $this->invoicenum . ".pdf\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . strlen($pdfdata));
        echo $pdfdata;
        exit();
        return 1;
    }

    public function printInvoicePdf($viewpdf) {
        $this->invoice->pdfCreate();
        $this->invoice->pdfInvoicePage($this->invoiceid);
        $pdfdata = $this->invoice->pdfOutput();
        $this->head($pdfdata, $viewpdf);
    }

    public function printLateFeePdf() {
        $this->invoice->pdfCreate();
        $this->invoice->pdfLateFee($this->invoiceid);
        $pdfdata = $this->invoice->pdfOutput();
        $this->head($pdfdata, $viewpdf);
    }

}
