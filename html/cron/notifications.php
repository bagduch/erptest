<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ($cron->isScheduled("invoicereminders")) {
    $data = invoicereminders($CONFIG);
    if (!empty($data)) {
        $cron->logActivity("Sent " . count($data['id']) . " Unpaid Invoice Payment Reminders" . $data['number'], true);
        $cron->emailLog(count($data['id']) . " Unpaid Invoice Payment Reminders Sent" . $data['number']);
    }
}