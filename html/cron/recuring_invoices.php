<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ($cron->isScheduled("invoices")) {
    createInvoices();
    
   $cron->logActivity("Invoices Create Done", true); 
}