-- MySQL dump 10.16  Distrib 10.2.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ra
-- ------------------------------------------------------
-- Server version	10.2.13-MariaDB-10.2.13+maria~stretch-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `ra`
--

USE `ra`;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mod_staffboard`
--

LOCK TABLES `mod_staffboard` WRITE;
/*!40000 ALTER TABLE `mod_staffboard` DISABLE KEYS */;
INSERT INTO `mod_staffboard` VALUES (1,'test','2016-04-29 04:20:09','yellow',1,0,0,1);
/*!40000 ALTER TABLE `mod_staffboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaccounts`
--

LOCK TABLES `tblaccounts` WRITE;
/*!40000 ALTER TABLE `tblaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladdonmodules`
--

LOCK TABLES `tbladdonmodules` WRITE;
/*!40000 ALTER TABLE `tbladdonmodules` DISABLE KEYS */;
INSERT INTO `tbladdonmodules` VALUES ('paypal_addon','version','2.0'),('staffboard','version','1.1'),('paypal_addon','username','admin'),('paypal_addon','password','admin'),('paypal_addon','signature',''),('paypal_addon','showbalance1','on'),('paypal_addon','showbalance2','on'),('paypal_addon','showbalance3','on'),('staffboard','masteradmin1','on'),('staffboard','masteradmin2','on'),('staffboard','masteradmin3','on'),('paypal_addon','access','1,2,3'),('staffboard','access','1,2,3'),('staffboard','lastviewed','a:1:{i:1;i:1464140360;}'),('hdtolls','version','1.0');
/*!40000 ALTER TABLE `tbladdonmodules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladdons`
--

LOCK TABLES `tbladdons` WRITE;
/*!40000 ALTER TABLE `tbladdons` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladdons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladdontoservice`
--

LOCK TABLES `tbladdontoservice` WRITE;
/*!40000 ALTER TABLE `tbladdontoservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladdontoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladminperms`
--

LOCK TABLES `tbladminperms` WRITE;
/*!40000 ALTER TABLE `tbladminperms` DISABLE KEYS */;
INSERT INTO `tbladminperms` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57),(1,58),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64),(1,65),(1,66),(1,67),(1,68),(1,69),(1,70),(1,71),(1,72),(1,73),(1,74),(1,75),(1,76),(1,77),(1,78),(1,79),(1,80),(1,81),(1,82),(1,83),(1,84),(1,85),(1,86),(1,87),(1,88),(1,89),(1,90),(1,91),(1,92),(1,93),(1,94),(1,95),(1,96),(1,97),(1,98),(1,99),(1,100),(1,101),(1,102),(1,103),(1,104),(1,105),(1,106),(1,107),(1,108),(1,109),(1,110),(1,111),(1,112),(1,113),(1,114),(1,115),(1,116),(1,117),(1,118),(1,119),(1,120),(1,121),(1,122),(1,123),(1,124),(1,125),(1,126),(1,127),(1,128),(1,129),(1,150),(2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(2,25),(2,26),(2,27),(2,28),(2,29),(2,30),(2,31),(2,32),(2,33),(2,34),(2,35),(2,36),(2,37),(2,38),(2,39),(2,40),(2,41),(2,42),(2,43),(2,44),(2,45),(2,46),(2,47),(2,48),(2,49),(2,50),(2,51),(2,52),(2,71),(2,73),(2,85),(2,98),(2,99),(2,101),(2,104),(2,105),(2,110),(2,120),(2,123),(2,124),(2,125),(2,125),(2,126),(2,126),(2,128),(2,129),(3,38),(3,39),(3,40),(3,41),(3,42),(3,43),(3,44),(3,50),(3,105),(3,125),(3,125),(3,126),(3,128);
/*!40000 ALTER TABLE `tbladminperms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladminroles`
--

LOCK TABLES `tbladminroles` WRITE;
/*!40000 ALTER TABLE `tbladminroles` DISABLE KEYS */;
INSERT INTO `tbladminroles` VALUES (1,'Full Admin','admin_activity,client_log,income_forecast,income_left_overview,order_left_overview,recent_left_orders','clients_by_country,transactions,affiliates_overview,top_25_clients_by_income,monthly_orders,AUDIT-All-Services,new_customers,client_sources,direct_debit_processing,income_by_product,ticket_tags,region_report,ticket_ratings_reviewer,suspend_customers,heat_map,check,totalorder,cancel_customer,invoices,annual_income_report,support_ticket_replies,aging_invoices,monthly_transactions,credits_reviewer,totalsale,sales_tax_liability,server_revenue_forecasts,promotions_usage,product_suspensions,income_forecast,ticket_feedback_scores,pdf_batch,ticket_feedback_comments,services,client_statement,daily_performance,',1,1,1),(2,'Sales Operator','activity_log,getting_started,income_forecast,income_overview,my_','',0,1,1),(3,'Support Operator','activity_log,getting_started,my_notes,todo_list,ra_news,supportt','',0,0,1);
/*!40000 ALTER TABLE `tbladminroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladmins`
--

LOCK TABLES `tbladmins` WRITE;
/*!40000 ALTER TABLE `tbladmins` DISABLE KEYS */;
INSERT INTO `tbladmins` VALUES (1,1,'raadmin','','','Sample','Admin','default@example.com','','','ra_flat','english',0,0,'1','','calendar:true,orders_overview:true,supporttickets_overview:true,my_notes:true,client_activity:true,open_invoices:true,activity_log:true|income_overview:true,system_overview:true,sysinfo:true,admin_activity:true,todo_list:true,income_forecast:true|','$1$xyz$PPirjAc2drfJW1BFPc5FY0'),(6,1,'test','','','test','test','test@test.test','test','test','ra_flat','english',0,0,'','','','$2y$10$av0D7nwelVQ6loSpspMeye/sW/GXbFTVbeGdPUKYF52zUNXvpkrsS');
/*!40000 ALTER TABLE `tbladmins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbladresschecker`
--

LOCK TABLES `tbladresschecker` WRITE;
/*!40000 ALTER TABLE `tbladresschecker` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladresschecker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaffiliates`
--

LOCK TABLES `tblaffiliates` WRITE;
/*!40000 ALTER TABLE `tblaffiliates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaffiliatesaccounts`
--

LOCK TABLES `tblaffiliatesaccounts` WRITE;
/*!40000 ALTER TABLE `tblaffiliatesaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliatesaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaffiliateshistory`
--

LOCK TABLES `tblaffiliateshistory` WRITE;
/*!40000 ALTER TABLE `tblaffiliateshistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliateshistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaffiliatespending`
--

LOCK TABLES `tblaffiliatespending` WRITE;
/*!40000 ALTER TABLE `tblaffiliatespending` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliatespending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblaffiliateswithdrawals`
--

LOCK TABLES `tblaffiliateswithdrawals` WRITE;
/*!40000 ALTER TABLE `tblaffiliateswithdrawals` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliateswithdrawals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblbannedemails`
--

LOCK TABLES `tblbannedemails` WRITE;
/*!40000 ALTER TABLE `tblbannedemails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblbannedemails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblbannedips`
--

LOCK TABLES `tblbannedips` WRITE;
/*!40000 ALTER TABLE `tblbannedips` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblbannedips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcalendar`
--

LOCK TABLES `tblcalendar` WRITE;
/*!40000 ALTER TABLE `tblcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcalendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcancelrequests`
--

LOCK TABLES `tblcancelrequests` WRITE;
/*!40000 ALTER TABLE `tblcancelrequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcancelrequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblclientfields`
--

LOCK TABLES `tblclientfields` WRITE;
/*!40000 ALTER TABLE `tblclientfields` DISABLE KEYS */;
INSERT INTO `tblclientfields` VALUES (1,'Where did you hear about us?','dropdown','','Word of Mouth,Google Search,Forum,Social Media,Radio,Other Search Engine,Other Marketing,Affiliates,Other','',0,1,0,0,1,NULL),(2,'Credit Control &amp; Account Notes','textarea','','','',0,0,0,0,0,NULL),(3,'Mobile Number','text','','','',0,1,0,0,1,NULL),(4,'Fax Number','text','','','',0,0,0,0,1,NULL),(5,'Affiliate Bank Account Details &amp; Notes','textarea','','','',0,0,0,0,0,NULL),(6,'Test 2','dropdown','','test 1,test 2,test 3,test 4','',0,0,0,0,0,NULL),(7,'test 1','tickbox','','','',0,0,0,0,0,NULL),(8,'test 3','date','','','',0,0,0,0,0,NULL),(9,'test 4','textarea','','','',0,0,0,0,0,NULL);
/*!40000 ALTER TABLE `tblclientfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblclientfieldsvalues`
--

LOCK TABLES `tblclientfieldsvalues` WRITE;
/*!40000 ALTER TABLE `tblclientfieldsvalues` DISABLE KEYS */;
INSERT INTO `tblclientfieldsvalues` VALUES (1,0,'Word of Mouth'),(2,0,''),(3,0,''),(4,0,''),(5,0,''),(6,0,'test 1'),(8,0,''),(9,0,''),(7,0,'on'),(1,1001,'Word of Mouth'),(2,1001,''),(3,1001,''),(4,1001,''),(5,1001,''),(6,1001,'test 1'),(8,1001,''),(9,1001,'');
/*!40000 ALTER TABLE `tblclientfieldsvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblclientgroups`
--

LOCK TABLES `tblclientgroups` WRITE;
/*!40000 ALTER TABLE `tblclientgroups` DISABLE KEYS */;
INSERT INTO `tblclientgroups` VALUES (0,'Default',NULL,0.00,NULL,''),(1,'Business','#ffff00',0.00,'on',''),(2,'Residential','#ffffff',0.00,'','');
/*!40000 ALTER TABLE `tblclientgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblclientsfiles`
--

LOCK TABLES `tblclientsfiles` WRITE;
/*!40000 ALTER TABLE `tblclientsfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblclientsfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcontacts`
--

LOCK TABLES `tblcontacts` WRITE;
/*!40000 ALTER TABLE `tblcontacts` DISABLE KEYS */;
INSERT INTO `tblcontacts` VALUES (1,3,'TESTFIRST','TESTLAST','','guy@te','','','','','','NZ','phone','mobile',0,'217e08456aa692ed020e10ae50d4a2e4:)wuXf','',0,0,0,0,0,0,'',0),(2,12442,'test searcher','','dsadsa','','','','','','','NZ','','',0,'$2y$10$8l60udoKW0Iw0OZxRwYQceC6wIkWSaWE8WMdH1Le8eCouIXWJbw4q','',0,0,0,0,0,0,'',0),(3,8017,'yue','zhang','HD','waikatozhang@gmail.com','280 queen','','Auckland','','321321','NZ','212220588','',1,'$2y$10$TUH7dOCVxadKvccqLmthT.j6GQYAftMKWldxx8YCPXchJmGm434..','profile,contacts,products,invoices,tickets,affiliates',0,1,0,1,0,0,'',0);
/*!40000 ALTER TABLE `tblcontacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcredit`
--

LOCK TABLES `tblcredit` WRITE;
/*!40000 ALTER TABLE `tblcredit` DISABLE KEYS */;
INSERT INTO `tblcredit` VALUES (1,8019,'2017-08-04','Credit Applied to Invoice #152079',-10.00,NULL,NULL);
/*!40000 ALTER TABLE `tblcredit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcreditcards`
--

LOCK TABLES `tblcreditcards` WRITE;
/*!40000 ALTER TABLE `tblcreditcards` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcreditcards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcurrencies`
--

LOCK TABLES `tblcurrencies` WRITE;
/*!40000 ALTER TABLE `tblcurrencies` DISABLE KEYS */;
INSERT INTO `tblcurrencies` VALUES (1,'NZD','$',' NZD',1,1.00000,1);
/*!40000 ALTER TABLE `tblcurrencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomerservices`
--

LOCK TABLES `tblcustomerservices` WRITE;
/*!40000 ALTER TABLE `tblcustomerservices` DISABLE KEYS */;
INSERT INTO `tblcustomerservices` VALUES (238,1000,0,240,52,NULL,'2017-07-12 17:50:35','56 Queen Street, Auckland, New Zealand','banktransfer',114.00,65.00,'Monthly','2017-07-12','2017-07-12','Pending',NULL,NULL,NULL,NULL,'2018-03-07 23:51:02',NULL),(239,1000,0,241,50,NULL,'2017-07-13 15:43:03','','banktransfer',0.00,0.00,'One Time','0000-00-00','0000-00-00','Active',NULL,NULL,'','0000-00-00','2018-03-07 23:51:02',''),(240,1000,0,241,52,NULL,'2017-07-13 15:43:03','','banktransfer',114.00,65.00,'Monthly','2017-07-13','2017-07-13','Active',NULL,NULL,NULL,NULL,'2018-03-07 23:51:02',NULL),(241,1000,0,251,51,0,'2017-08-02 12:39:08','122 Great South Road, Papakura, New Zealand','banktransfer',114.00,65.00,'Monthly','2017-08-02','2017-08-02','Active',NULL,NULL,NULL,NULL,'2018-03-07 23:51:02','');
/*!40000 ALTER TABLE `tblcustomerservices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfields`
--

LOCK TABLES `tblcustomfields` WRITE;
/*!40000 ALTER TABLE `tblcustomfields` DISABLE KEYS */;
INSERT INTO `tblcustomfields` VALUES (25,'address','text','','','',0,1,1,1,0,NULL),(26,'ip','text','','','',0,1,1,2,0,NULL),(28,'I want to move from another provider','more','','','',0,0,0,3,0,NULL),(30,'Provider Name','text','','','',0,1,0,0,0,28),(31,'Provider ID','text','','','',0,1,0,0,0,28),(32,'Preferred date','date','','','',0,1,0,0,0,NULL),(33,'Install Date','date','','','',0,0,0,0,0,NULL),(34,'Address','text','','','',0,0,0,0,0,NULL);
/*!40000 ALTER TABLE `tblcustomfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfieldsgrouplinks`
--

LOCK TABLES `tblcustomfieldsgrouplinks` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgrouplinks` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgrouplinks` VALUES (43,4,48,NULL);
/*!40000 ALTER TABLE `tblcustomfieldsgrouplinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfieldsgroupmembers`
--

LOCK TABLES `tblcustomfieldsgroupmembers` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgroupmembers` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgroupmembers` VALUES (4,33),(4,34);
/*!40000 ALTER TABLE `tblcustomfieldsgroupmembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfieldsgroupnames`
--

LOCK TABLES `tblcustomfieldsgroupnames` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgroupnames` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgroupnames` VALUES (4,'Residential Broadband');
/*!40000 ALTER TABLE `tblcustomfieldsgroupnames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfieldslinks`
--

LOCK TABLES `tblcustomfieldslinks` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldslinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcustomfieldslinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblcustomfieldsvalues`
--

LOCK TABLES `tblcustomfieldsvalues` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsvalues` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsvalues` VALUES (33,238,''),(33,239,''),(33,240,''),(34,238,''),(34,239,''),(34,240,'');
/*!40000 ALTER TABLE `tblcustomfieldsvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbldownloadcats`
--

LOCK TABLES `tbldownloadcats` WRITE;
/*!40000 ALTER TABLE `tbldownloadcats` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldownloadcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbldownloads`
--

LOCK TABLES `tbldownloads` WRITE;
/*!40000 ALTER TABLE `tbldownloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldownloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblemailtemplates`
--

LOCK TABLES `tblemailtemplates` WRITE;
/*!40000 ALTER TABLE `tblemailtemplates` DISABLE KEYS */;
INSERT INTO `tblemailtemplates` VALUES (7,'support','Support Ticket Opened','New Support Ticket Opened','<p>\r\n{$client_name},\r\n</p>\r\n<p>\r\nThank you for contacting our support team. A support ticket has now been opened for your request. You will be notified when a response is made by email. The details of your ticket are shown below.\r\n</p>\r\n<p>\r\nSubject: {$ticket_subject}<br />\r\nPriority: {$ticket_priority}<br />\r\nStatus: {$ticket_status}\r\n</p>\r\n<p>\r\nYou can view the ticket at any time at {$ticket_link}\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(8,'support','Support Ticket Reply','Support Ticket Response','<p>\r\n{$ticket_message}\r\n</p>\r\n<p>\r\n----------------------------------------------<br />\r\nTicket ID: #{$ticket_id}<br />\r\nSubject: {$ticket_subject}<br />\r\nStatus: {$ticket_status}<br />\r\nTicket URL: {$ticket_link}<br />\r\n----------------------------------------------\r\n</p>\r\n','','','','','','','',0),(9,'general','Client Signup Email','Welcome to Unlimited Internet','<p>Dear {$client_name},</p>\r\n<p>Thank you for signing up with us. Your new account has been setup and you can now login to our client area using the details below.</p>\r\n<p>Email Address: {$client_email}<br /> Password: {$client_password}</p>\r\n<p>To login, visit {$ra_url}</p>\r\n<p>{$signature}</p>','','','','','','','',0),(10,'product','Service Suspension Notification','Service Suspension Notification','<p>Dear {$client_name},</p><p>This is a notification that your service has now been suspended.  The details of this suspension are below:</p><p>Product/Service: {$service_product_name}<br />{if $service_domain}Domain: {$service_domain}<br />{/if}Amount: {$service_recurring_amount}<br />Due Date: {$service_next_due_date}<br />Suspension Reason: <strong>{$service_suspension_reason}</strong></p><p>Please contact us as soon as possible to get your service reactivated.</p><p>{$signature}</p>','','','','','','','',0),(13,'invoice','Invoice Payment Confirmation','Invoice Payment Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is a payment receipt for Invoice {$invoice_num} sent on {$invoice_date_created}</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount: {$invoice_last_payment_amount}<br />Transaction #: {$invoice_last_payment_transid}<br />Total Paid: {$invoice_amount_paid}<br />Remaining Balance: {$invoice_balance}<br />Status: {$invoice_status}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>Note: This email will serve as an official receipt for this payment.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(14,'invoice','Invoice Created','Customer Invoice','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is a notice that an invoice has been generated on {$invoice_date_created}. \r\n</p>\r\n<p>\r\nYour payment method is: {$invoice_payment_method} \r\n</p>\r\n<p>\r\nInvoice #{$invoice_num}<br />\r\nAmount Due: {$invoice_total}<br />\r\nDue Date: {$invoice_date_due} \r\n</p>\r\n<p>\r\n<strong>Invoice Items</strong> \r\n</p>\r\n<p>\r\n{$invoice_html_contents} <br />\r\n------------------------------------------------------ \r\n</p>\r\n<p>\r\nYou can login to your client area to view and pay the invoice at {$invoice_link} \r\n</p>\r\n<p>\r\n{$signature} \r\n</p>\r\n','','','','','','','',0),(15,'invoice','Invoice Payment Reminder','Invoice Payment Reminder','<p>\r\nDear {$client_name},\r\n</p>\r\n<p>\r\nThis is a billing reminder that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is due on {$invoice_date_due}.\r\n</p>\r\n<p>\r\nYour payment method is: {$invoice_payment_method}\r\n</p>\r\n<p>\r\nInvoice: {$invoice_num}<br />\r\nBalance Due: {$invoice_balance}<br />\r\nDue Date: {$invoice_date_due}\r\n</p>\r\n<p>\r\nYou can login to your client area to view and pay the invoice at {$invoice_link}\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(16,'general','Order Confirmation','Order Confirmation','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nWe have received your order and will be processing it shortly. The details of the order are below: \r\n</p>\r\n<p>\r\nOrder Number: <b>{$order_number}</b></p>\r\n<p>\r\n{$order_details} \r\n</p>\r\n<p>\r\nYou will receive an email from us shortly once your account has been setup. Please quote your order reference number if you wish to contact us about this order. \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(18,'product','Other Product/Service Welcome Email','New Product Information','<p>\r\nDear {$client_name},\r\n</p>\r\n<p>\r\nYour order for {$service_product_name} has now been activated. Please keep this message for your records.\r\n</p>\r\n<p>\r\nProduct/Service: {$service_product_name}<br />\r\nPayment Method: {$service_payment_method}<br />\r\nAmount: {$service_recurring_amount}<br />\r\nBilling Cycle: {$service_billing_cycle}<br />\r\nNext Due Date: {$service_next_due_date}\r\n</p>\r\n<p>\r\nThank you for choosing us.\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(19,'invoice','Credit Card Payment Confirmation','Credit Card Payment Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is a payment receipt for Invoice {$invoice_num} sent on {$invoice_date_created}</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount: {$invoice_last_payment_amount}<br />Transaction #: {$invoice_last_payment_transid}<br />Total Paid: {$invoice_amount_paid}<br />Remaining Balance: {$invoice_balance}<br />Status: {$invoice_status}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>Note: This email will serve as an official receipt for this payment.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(20,'invoice','Credit Card Payment Failed','Credit Card Payment Failed','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is a notice that a recent credit card payment we attempted on the card we have registered for you failed. \r\n</p>\r\n<p>\r\nInvoice Date: {$invoice_date_created}<br />\r\nInvoice No: {$invoice_num}<br />\r\nAmount: {$invoice_total}<br />\r\nStatus: {$invoice_status} \r\n</p>\r\n<p>\r\nYou now need to login to your client area to pay the invoice manually. During the payment process you will be given the opportunity to change the card on record with us.<br />\r\n{$invoice_link} \r\n</p>\r\n<p>\r\nNote: This email will serve as an official receipt for this payment. \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(21,'invoice','Credit Card Invoice Created','Customer Invoice','<p> Dear {$client_name}, </p> <p> This is a notice that an invoice has been generated on {$invoice_date_created}. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice #{$invoice_num}<br /> Amount Due: {$invoice_total}<br /> Due Date: {$invoice_date_due} </p> <p> <strong>Invoice Items</strong> </p> <p> {$invoice_html_contents} <br /> ------------------------------------------------------ </p> <p> Payment will be taken automatically on {$invoice_date_due} from your credit card on record with us. To update or change the credit card details we hold for your account please login at {$invoice_link} and click Pay Now then following the instructions on screen. </p> <p> {$signature} </p>','','','','','','','',0),(22,'affiliate','Affiliate Monthly Referrals Report','Affiliate Monthly Referrals Report','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is your monthly affiliate referrals report. You can view your referral statistics at any time by logging in to the client area. \r\n</p>\r\n<p>\r\nTotal Visitors Referred: {$affiliate_total_visits}<br />\r\nCurrent Earnings: {$affiliate_balance}<br />\r\nAmount Withdrawn: {$affiliate_withdrawn} \r\n</p>\r\n<p>\r\n<strong>Your New Signups this Month</strong> \r\n</p>\r\n<p>\r\n{$affiliate_referrals_table} \r\n</p>\r\n<p>\r\nRemember, you can refer new customers using your unique affiliate link: {$affiliate_referral_url} \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(23,'support','Support Ticket Opened by Admin','{$ticket_subject}','{$ticket_message}','','','','','','','',0),(24,'invoice','First Invoice Overdue Notice','First Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is a billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(26,'invoice','Second Invoice Overdue Notice','Second Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is the second billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(27,'invoice','Third Invoice Overdue Notice','Third Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is the third and final billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. Failure to make payment will result in account suspension.</p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(31,'support','Bounce Message','Support Ticket Not Opened','<p>{$client_name},</p><p>Your email to our support system could not be accepted because it was not recognized as coming from an email address belonging to one of our customers.  If you need assistance, please email from the address you registered with us that you use to login to our client area.</p><p>{$signature}</p>','','','','','','','',0),(32,'general','Credit Card Expiring Soon','Credit Card Expiring Soon','<p>Dear {$client_name}, </p><p>This is a notice to inform you that your {$client_cc_type} credit card ending with {$client_cc_number} will be expiring next month on {$client_cc_expiry}. Please login to update your credit card information as soon as possible and prevent any interuptions in service at {$whmcs_url}<br /><br />If you have any questions regarding your account, please open a support ticket from the client area.</p><p>{$signature}</p>','','','','','','','',0),(33,'support','Support Ticket Auto Close Notification','Support Ticket Resolved','<p>{$client_name},</p><p>This is a notification to let you know that we are changing the status of your ticket #{$ticket_id} to Closed as we have not received a response from you in over {$ticket_auto_close_time} hours.</p><p>Subject: {$ticket_subject}<br>Department: {$ticket_department}<br>Priority: {$ticket_priority}<br>Status: {$ticket_status}</p><p>If you have any further questions then please just reply to re-open the ticket.</p><p>{$signature}</p>','','','','','','','',0),(34,'invoice','Credit Card Payment Due','Invoice Payment Reminder','<p>Dear {$client_name},</p><p>This is a notice to remind you that you have an invoice due on {$invoice_date_due}. We tried to bill you automatically but were unable to because we don\'t have your credit card details on file.</p><p>Invoice Date: {$invoice_date_created}<br>Invoice #{$invoice_num}<br>Amount Due: {$invoice_total}<br>Due Date: {$invoice_date_due}</p><p>Please login to our client area at the link below to submit your card details or make payment using a different method.</p><p>{$invoice_link}</p><p>{$signature}</p>','','','','','','','',0),(35,'product','Cancellation Request Confirmation','Cancellation Request Confirmation','<p>Dear {$client_name},</p><p>This email is to confirm that we have received your cancellation request for the service listed below.</p><p>Product/Service: {$service_product_name}<br />Domain: {$service_domain}</p><p>{if $service_cancellation_type==\"Immediate\"}The service will be terminated within the next 24 hours.{else}The service will be cancelled at the end of your current billing period on {$service_next_due_date}.{/if}</p><p>Thank you for using {$company_name} and we hope to see you again in the future.</p><p>{$signature}</p>','','','','','','','',0),(37,'general','Password Reset Validation','Your login details for {$company_name}','<p>Dear {$client_name},</p><p>Recently a request was submitted to reset your password for our client area. If you did not request this, please ignore this email. It will expire and become useless in 2 hours time.</p><p>To reset your password, please visit the url below:<br /><a href=\"{$pw_reset_url}\">{$pw_reset_url}</a></p><p>When you visit the link above, your password will be reset, and the new password will be emailed to you.</p><p>{$signature}</p>','','','','','','','',0),(38,'general','Automated Password Reset','Your new password for {$company_name}','<p>Dear {$client_name},</p><p>As you requested, your password for our client area has now been reset.  Your new login details are as follows:</p><p>{$whmcs_link}<br />Email: {$client_email}<br />Password: {$client_password}</p><p>To change your password to something more memorable, after logging in go to My Details > Change Password.</p><p>{$signature}</p>','','','','','','','',0),(39,'admin','Automatic Setup Failed','WHMCS Automatic Setup Failed','<p>An order has received its first payment but the automatic provisioning has failed and requires you to manually check & resolve.</p>\r\n<p>Client ID: {$client_id}<br />{if $service_id}Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}{else}Domain ID: {$domain_id}<br />Registration Type: {$domain_type}<br />Domain: {$domain_name}{/if}<br />Error: {$error_msg}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(40,'admin','Automatic Setup Successful','WHMCS Automatic Setup Successful','<p>An order has received its first payment and the product/service has been automatically provisioned successfully.</p>\r\n<p>Client ID: {$client_id}<br />{if $service_id}Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}{else}Domain ID: {$domain_id}<br />Registration Type: {$domain_type}<br />Domain: {$domain_name}{/if}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(43,'admin','New Order Notification','WHMCS New Order Notification','<p><strong>Order Information</strong></p>\r\n<p>Order ID: {$order_id}<br />\r\nOrder Number: {$order_number}<br />\r\nDate/Time: {$order_date}<br />\r\nInvoice Number: {$invoice_id}<br />\r\nPayment Method: {$order_payment_method}</p>\r\n<p><strong>Customer Information</strong></p>\r\n<p>Customer ID: {$client_id}<br />\r\nName: {$client_first_name} {$client_last_name}<br />\r\nEmail: {$client_email}<br />\r\nCompany: {$client_company_name}<br />\r\nAddress 1: {$client_address1}<br />\r\nAddress 2: {$client_address2}<br />\r\nCity: {$client_city}<br />\r\nState: {$client_state}<br />\r\nPostcode: {$client_postcode}<br />\r\nCountry: {$client_country}<br />\r\nPhone Number: {$client_phonenumber}</p>\r\n<p><strong>Order Items</strong></p>\r\n<p>{$order_items}</p>\r\n{if $order_notes}<p><strong>Order Notes</strong></p>\r\n<p>{$order_notes}</p>{/if}\r\n<p><strong>ISP Information</strong></p>\r\n<p>IP: {$client_ip}<br />\r\nHost: {$client_hostname}</p><p><a href=\"{$whmcs_admin_url}orders.php?action=view&id={$order_id}\">{$whmcs_admin_url}orders.php?action=view&id={$order_id}</a></p>','','','','','','','',0),(44,'admin','Service Unsuspension Failed','WHMCS Service Unsuspension Failed','<p>This product/service has received its next payment but the automatic reactivation has failed.</p>\r\n<p>Client ID: {$client_id}<br />Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}<br />Error: {$error_msg}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(45,'admin','Service Unsuspension Successful','WHMCS Service Unsuspension Successful','<p>This product/service has received its next payment and has been reactivated successfully.</p>\r\n<p>Client ID: {$client_id}<br />Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(46,'admin','Support Ticket Created','[Ticket ID: {$ticket_tid}] New Support Ticket Opened','<p>A new support ticket has been opened.</p>\r\n<p>Client: {$client_name}{if $client_id} #{$client_id}{/if}<br />Department: {$ticket_department}<br />Subject: {$ticket_subject}<br />Priority: {$ticket_priority}</p>\r\n<p>---<br />{$ticket_message}<br />---</p>\r\n<p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p>\r\n<p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(47,'admin','Support Ticket Response','[Ticket ID: {$ticket_tid}] New Support Ticket Response','<p>A new support ticket response has been made.</p>\r\n<p>Client: {$client_name}{if $client_id} #{$client_id}{/if} <br />Department: {$ticket_department} <br />Subject: {$ticket_subject} <br />Priority: {$ticket_priority}</p>\r\n<p>--- <br />{$ticket_message} <br />---</p>\r\n<p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p>\r\n<p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(48,'admin','Escalation Rule Notification','[Ticket ID: {$tickettid}] Escalation Rule Notification','<p>The escalation rule {$rule_name} has just been applied to this ticket.</p><p>Client: {$client_name}{if $client_id} #{$client_id}{/if} <br />Department: {$ticket_department} <br />Subject: {$ticket_subject} <br />Priority: {$ticket_priority}</p><p>---<br />{$ticket_message}<br />---</p><p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(49,'admin','Support Ticket Department Reassigned','[Ticket ID: {$ticket_tid}] Support Ticket Department Reassigned','<p>The department this ticket is assigned to has been changed to a department you are a member of.</p><p>Client: {$client_name}{if $client_id} #{$client_id}{/if}<br />Department: {$ticket_department}<br />Subject: {$ticket_subject}<br />Priority: {$ticket_priority}</p><p>---<br />{$ticket_message}<br />---</p><p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(50,'invoice','Invoice Refund Confirmation','Invoice Refund Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is confirmation that a {if $invoice_status eq \"Refunded\"}full{else}partial{/if} refund has been processed for Invoice #{$invoice_num}</p>\r\n<p>The refund has been {if $invoice_refund_type eq \"credit\"}credited to your account balance with us{else}returned via the payment method you originally paid with{/if}.</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount Refunded: {$invoice_last_payment_amount}{if $invoice_last_payment_transid}<br />Transaction #: {$invoice_last_payment_transid}{/if}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(51,'admin','New Cancellation Request','New Cancellation Request','<p>A new cancellation request has been submitted.</p><p>Client ID: {$client_id}<br>Client Name: {$clientname}<br>Service ID: {$service_id}<br>Product Name: {$product_name}<br>Cancellation Type: {$service_cancellation_type}<br>Cancellation Reason: {$service_cancellation_reason}</p><p>{$whmcs_admin_link}</p>','','','','','','','',0),(52,'admin','Support Ticket Flagged','New Support Ticket Flagged to You','<p>A new support ticket has been flagged to you.</p><p>Ticket #: {$ticket_tid}<br>Client Name: {$client_name} (ID {$client_id})<br>Department: {$ticket_department}<br>Subject: {$ticket_subject}<br>Priority: {$ticket_priority}</p><p>----------------------<br />{$ticket_message}<br />----------------------</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(56,'general','Password Reset Confirmation','Your password has been reset for {$company_name}','<p>Dear {$client_name},</p><p>As you requested, your password for our client area has now been reset. </p><p>If it was not at your request, then please contact support immediately.</p><p>{$signature}</p>','','','','','','','',0),(57,'support','Support Ticket Feedback Request','Your Feedback is Requested for Ticket #{$ticket_id}','<p>This support request has been marked as completed.</p><p>We would really appreciate it if you would just take a moment to let us know about the quality of your experience.</p><p><a href=\"{$ticket_url}&feedback=1\">{$ticket_url}&feedback=1</a></p><p>Your feedback is very important to us.</p><p>Thank you for your business.</p><p>{$signature}</p>','','','','','','','',0);
/*!40000 ALTER TABLE `tblemailtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblgatewaylog`
--

LOCK TABLES `tblgatewaylog` WRITE;
/*!40000 ALTER TABLE `tblgatewaylog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgatewaylog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblgrouptogroup`
--

LOCK TABLES `tblgrouptogroup` WRITE;
/*!40000 ALTER TABLE `tblgrouptogroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgrouptogroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblknowledgebase`
--

LOCK TABLES `tblknowledgebase` WRITE;
/*!40000 ALTER TABLE `tblknowledgebase` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledgebase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblknowledgebasecats`
--

LOCK TABLES `tblknowledgebasecats` WRITE;
/*!40000 ALTER TABLE `tblknowledgebasecats` DISABLE KEYS */;
INSERT INTO `tblknowledgebasecats` VALUES (11,NULL,'test','test','0',0,''),(12,11,'test child','test child','',0,'');
/*!40000 ALTER TABLE `tblknowledgebasecats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblknowledgebaselinks`
--

LOCK TABLES `tblknowledgebaselinks` WRITE;
/*!40000 ALTER TABLE `tblknowledgebaselinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledgebaselinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblmodulelog`
--

LOCK TABLES `tblmodulelog` WRITE;
/*!40000 ALTER TABLE `tblmodulelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmodulelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblnetworkissues`
--

LOCK TABLES `tblnetworkissues` WRITE;
/*!40000 ALTER TABLE `tblnetworkissues` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnetworkissues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblnotes`
--

LOCK TABLES `tblnotes` WRITE;
/*!40000 ALTER TABLE `tblnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblorders`
--

LOCK TABLES `tblorders` WRITE;
/*!40000 ALTER TABLE `tblorders` DISABLE KEYS */;
INSERT INTO `tblorders` VALUES (1,3595888055,1000,NULL,'2018-03-07 23:41:19',NULL,NULL,NULL,NULL,NULL,118.00,'banktransfer',NULL,'Pending','192.168.121.1',NULL,NULL,''),(2,9289660017,1000,NULL,'2018-03-07 23:41:51',NULL,NULL,NULL,NULL,NULL,114.00,'banktransfer',NULL,'Pending','192.168.121.1',NULL,NULL,'');
/*!40000 ALTER TABLE `tblorders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblorderstatuses`
--

LOCK TABLES `tblorderstatuses` WRITE;
/*!40000 ALTER TABLE `tblorderstatuses` DISABLE KEYS */;
INSERT INTO `tblorderstatuses` VALUES (1,'Pending','#cc0000',1,0,0,10),(2,'Active','#779500',0,1,0,20),(3,'Cancelled','#888888',0,0,1,30),(4,'Fraud','#000000',0,0,0,40),(5,'Draft','#f2d342',0,0,0,50);
/*!40000 ALTER TABLE `tblorderstatuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblpaymentgatewaynames`
--

LOCK TABLES `tblpaymentgatewaynames` WRITE;
/*!40000 ALTER TABLE `tblpaymentgatewaynames` DISABLE KEYS */;
INSERT INTO `tblpaymentgatewaynames` VALUES (4,'banktransfer'),(2,'mailin'),(3,'offlinecc'),(1,'paypal'),(5,'paystation');
/*!40000 ALTER TABLE `tblpaymentgatewaynames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblpaymentgateways`
--

LOCK TABLES `tblpaymentgateways` WRITE;
/*!40000 ALTER TABLE `tblpaymentgateways` DISABLE KEYS */;
INSERT INTO `tblpaymentgateways` VALUES (20,'banktransfer','name','Bank Transfer',1),(21,'banktransfer','type','Invoices',0),(22,'banktransfer','visible','on',0),(32,'mailin','name','Mail In Payment',2),(33,'mailin','type','Invoices',0),(34,'mailin','visible','on',0),(56,'paystation','name','Credit Card (Visa / Master Card)',3),(57,'paystation','type','Invoices',0),(58,'paystation','visible','on',0),(59,'paystation','paystationid','',0),(60,'paystation','gatewayid','',0),(61,'paystation','hashkey','',0),(62,'paystation','url','',0),(63,'paystation','testmode','',0),(64,'paystation','convertto','',0),(65,'mailin','instructions','Bank Name: BNZ\r\nPayee Name: UNLIMITED INTERNET\r\nAccount Number:',0),(66,'mailin','convertto','',0);
/*!40000 ALTER TABLE `tblpaymentgateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblpricing`
--

LOCK TABLES `tblpricing` WRITE;
/*!40000 ALTER TABLE `tblpricing` DISABLE KEYS */;
INSERT INTO `tblpricing` VALUES (1,'domainaddons',1,0,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(2,'product',1,1,10.00,-1.00,-1.00,-1.00,-1.00,-1.00,75.00,-1.00,-1.00,-1.00,-1.00,-1.00),(3,'product',1,2,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(4,'product',1,3,49.00,0.00,0.00,0.00,0.00,0.00,69.00,0.00,0.00,0.00,0.00,0.00),(5,'product',1,4,70.00,0.00,0.00,0.00,0.00,0.00,49.00,0.00,0.00,0.00,0.00,0.00),(6,'product',1,5,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(7,'configoptions',1,1,45.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00),(8,'addon',1,1,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(9,'addon',1,2,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(10,'product',1,6,49.00,0.00,0.00,0.00,0.00,0.00,75.00,0.00,0.00,0.00,0.00,0.00),(11,'product',1,7,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(12,'product',1,8,0.00,0.00,0.00,0.00,0.00,0.00,189.00,0.00,0.00,0.00,0.00,0.00),(13,'product',1,0,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(14,'product',1,9,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(15,'product',1,10,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(16,'product',1,11,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(17,'product',1,12,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(18,'product',1,38,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(19,'product',1,14,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(20,'product',1,25,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(21,'product',1,46,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(22,'product',1,34,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(23,'product',1,36,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(24,'product',1,37,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(25,'product',1,47,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(26,'product',1,48,49.00,0.00,0.00,0.00,0.00,0.00,69.00,0.00,0.00,0.00,0.00,0.00),(27,'product',1,49,0.00,0.00,0.00,0.00,0.00,0.00,159.00,0.00,0.00,0.00,0.00,0.00),(28,'product',1,50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(29,'product',1,51,49.00,0.00,0.00,0.00,0.00,0.00,65.00,0.00,0.00,0.00,0.00,0.00),(30,'product',1,52,49.00,0.00,0.00,0.00,0.00,0.00,65.00,0.00,0.00,0.00,0.00,0.00),(31,'product',1,53,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `tblpricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblpromotions`
--

LOCK TABLES `tblpromotions` WRITE;
/*!40000 ALTER TABLE `tblpromotions` DISABLE KEYS */;
INSERT INTO `tblpromotions` VALUES (1,'test','Percentage',0,10.00,'','','',0,'0000-00-00','0000-00-00',1,2,0,0,0,0,0,0,0,'','Order Process One Off Custom Promo');
/*!40000 ALTER TABLE `tblpromotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservergroups`
--

LOCK TABLES `tblservergroups` WRITE;
/*!40000 ALTER TABLE `tblservergroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservergroupsrel`
--

LOCK TABLES `tblservergroupsrel` WRITE;
/*!40000 ALTER TABLE `tblservergroupsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservergroupsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservers`
--

LOCK TABLES `tblservers` WRITE;
/*!40000 ALTER TABLE `tblservers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblserviceaddons`
--

LOCK TABLES `tblserviceaddons` WRITE;
/*!40000 ALTER TABLE `tblserviceaddons` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceaddons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblserviceconfiggroups`
--

LOCK TABLES `tblserviceconfiggroups` WRITE;
/*!40000 ALTER TABLE `tblserviceconfiggroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfiggroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblserviceconfiglinks`
--

LOCK TABLES `tblserviceconfiglinks` WRITE;
/*!40000 ALTER TABLE `tblserviceconfiglinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfiglinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblserviceconfigoptions`
--

LOCK TABLES `tblserviceconfigoptions` WRITE;
/*!40000 ALTER TABLE `tblserviceconfigoptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfigoptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblserviceconfigoptionssub`
--

LOCK TABLES `tblserviceconfigoptionssub` WRITE;
/*!40000 ALTER TABLE `tblserviceconfigoptionssub` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfigoptionssub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservicegroups`
--

LOCK TABLES `tblservicegroups` WRITE;
/*!40000 ALTER TABLE `tblservicegroups` DISABLE KEYS */;
INSERT INTO `tblservicegroups` VALUES (9,'Service','service','service','','',1),(10,'Product','product','product','','',2);
/*!40000 ALTER TABLE `tblservicegroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservices`
--

LOCK TABLES `tblservices` WRITE;
/*!40000 ALTER TABLE `tblservices` DISABLE KEYS */;
INSERT INTO `tblservices` VALUES (48,'residential',9,0,0,0,0,'ADSL ',0,0,0,'drttre','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,1,'','',0.00,0,0),(49,'other',10,1,0,0,0,'ADSL / VDSL / UFB / VOIP / Gigabit Router',100,10,100,'','','',NULL,'',0,0,'onetime','','',0,0,0,'0','',0,1,'','',0.00,0,0),(50,'other',10,0,0,0,0,'Router 1',0,0,0,'',NULL,'',NULL,'',0,0,'free','','',0,0,0,'','',0,0,'','',0.00,0,0),(51,'residential',9,0,0,0,0,'VDSL',0,0,0,'','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,0,'','',0.00,0,0),(52,'residential',9,0,0,0,0,'UFB 30/10',0,0,0,'','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,0,'','',0.00,0,0);
/*!40000 ALTER TABLE `tblservices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblservicetoservice`
--

LOCK TABLES `tblservicetoservice` WRITE;
/*!40000 ALTER TABLE `tblservicetoservice` DISABLE KEYS */;
INSERT INTO `tblservicetoservice` VALUES (48,49),(48,50);
/*!40000 ALTER TABLE `tblservicetoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblsmstemplate`
--

LOCK TABLES `tblsmstemplate` WRITE;
/*!40000 ALTER TABLE `tblsmstemplate` DISABLE KEYS */;
INSERT INTO `tblsmstemplate` VALUES (4,'test','account','test 3 '),(5,'invoice txt','invoice','xdsadas  dsad adsa dasd asdas dsd ');
/*!40000 ALTER TABLE `tblsmstemplate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbltax`
--

LOCK TABLES `tbltax` WRITE;
/*!40000 ALTER TABLE `tbltax` DISABLE KEYS */;
INSERT INTO `tbltax` VALUES (1,1,'GST','','New Zealand',15.00),(2,1,'World','','',0.00);
/*!40000 ALTER TABLE `tbltax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketbreaklines`
--

LOCK TABLES `tblticketbreaklines` WRITE;
/*!40000 ALTER TABLE `tblticketbreaklines` DISABLE KEYS */;
INSERT INTO `tblticketbreaklines` VALUES (1,'> -----Original Message-----'),(2,'----- Original Message -----'),(3,'-----Original Message-----'),(4,'<!-- Break Line -->'),(5,'====== Please reply above this line ======'),(6,'_____');
/*!40000 ALTER TABLE `tblticketbreaklines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketdepartments`
--

LOCK TABLES `tblticketdepartments` WRITE;
/*!40000 ALTER TABLE `tblticketdepartments` DISABLE KEYS */;
INSERT INTO `tblticketdepartments` VALUES (1,'Provisioning','','provisioningtest','','','','',1,'','110','','fZjPBXW3a+6AgoUdYX0mFLhL8/g=');
/*!40000 ALTER TABLE `tblticketdepartments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketfeedback`
--

LOCK TABLES `tblticketfeedback` WRITE;
/*!40000 ALTER TABLE `tblticketfeedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketfeedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketlog`
--

LOCK TABLES `tblticketlog` WRITE;
/*!40000 ALTER TABLE `tblticketlog` DISABLE KEYS */;
INSERT INTO `tblticketlog` VALUES (1,'2018-03-08 03:05:18',31,'Status changed to  (by Sample Admin)');
/*!40000 ALTER TABLE `tblticketlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketmaillog`
--

LOCK TABLES `tblticketmaillog` WRITE;
/*!40000 ALTER TABLE `tblticketmaillog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketmaillog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketnotes`
--

LOCK TABLES `tblticketnotes` WRITE;
/*!40000 ALTER TABLE `tblticketnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketpredefinedcats`
--

LOCK TABLES `tblticketpredefinedcats` WRITE;
/*!40000 ALTER TABLE `tblticketpredefinedcats` DISABLE KEYS */;
INSERT INTO `tblticketpredefinedcats` VALUES (6,0,'test');
/*!40000 ALTER TABLE `tblticketpredefinedcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketpredefinedreplies`
--

LOCK TABLES `tblticketpredefinedreplies` WRITE;
/*!40000 ALTER TABLE `tblticketpredefinedreplies` DISABLE KEYS */;
INSERT INTO `tblticketpredefinedreplies` VALUES (1,6,'reply one ','dsadsadasdas');
/*!40000 ALTER TABLE `tblticketpredefinedreplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketreplies`
--

LOCK TABLES `tblticketreplies` WRITE;
/*!40000 ALTER TABLE `tblticketreplies` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketreplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbltickets`
--

LOCK TABLES `tbltickets` WRITE;
/*!40000 ALTER TABLE `tbltickets` DISABLE KEYS */;
INSERT INTO `tbltickets` VALUES (30,'745673',1,NULL,NULL,'guy@hd.net.nz','guy@hd.net.nz','guy@hd.net.nz','WjEs0s0a','2016-06-17 15:34:47','guy@hd.net.nz','\r\n\r\nguy@hd.net.nz','Answered','Medium','Guy Lowe','','2017-02-21 16:34:56',2,1,',1',0,'2017-04-19 12:41:51',''),(31,'614919',1,NULL,NULL,'guy@hd.net.nz','guy@hd.net.nz','','vssbJ3yg','2016-06-17 15:36:45','guy@hd.net.nz','\r\nguy@hd.net.nz','Answered','Medium','Guy Lowe','','2017-01-16 13:37:10',1,1,',1',0,'2017-02-22 12:40:40','');
/*!40000 ALTER TABLE `tbltickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketspamfilters`
--

LOCK TABLES `tblticketspamfilters` WRITE;
/*!40000 ALTER TABLE `tblticketspamfilters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketspamfilters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tblticketstatuses`
--

LOCK TABLES `tblticketstatuses` WRITE;
/*!40000 ALTER TABLE `tblticketstatuses` DISABLE KEYS */;
INSERT INTO `tblticketstatuses` VALUES (1,'Open','#1ba1ff',1,1,1,0),(2,'Answered','#0C0',2,1,1,1),(3,'Customer-Reply','#ff6600',3,1,1,1),(4,'Closed','#888888',10,1,1,0),(5,'On Hold','#224488',5,1,1,0),(6,'In Progress','#cc0000',6,1,1,0),(7,'TEST STATUS','',0,0,0,0);
/*!40000 ALTER TABLE `tblticketstatuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbltickettags`
--

LOCK TABLES `tbltickettags` WRITE;
/*!40000 ALTER TABLE `tbltickettags` DISABLE KEYS */;
INSERT INTO `tbltickettags` VALUES (6,30,'test');
/*!40000 ALTER TABLE `tbltickettags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tbltodolist`
--

LOCK TABLES `tbltodolist` WRITE;
/*!40000 ALTER TABLE `tbltodolist` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbltodolist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
