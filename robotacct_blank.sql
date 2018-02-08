-- MySQL dump 10.16  Distrib 10.2.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ra
-- ------------------------------------------------------
-- Server version	10.2.12-MariaDB-10.2.12+maria~stretch-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_staffboard`
--

DROP TABLE IF EXISTS `mod_staffboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_staffboard` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `note` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `color` varchar(10) NOT NULL,
  `adminid` int(10) NOT NULL,
  `x` int(4) NOT NULL,
  `y` int(4) NOT NULL,
  `z` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `mod_staffboard_ibfk_1` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_staffboard`
--

LOCK TABLES `mod_staffboard` WRITE;
/*!40000 ALTER TABLE `mod_staffboard` DISABLE KEYS */;
INSERT INTO `mod_staffboard` VALUES (1,'test','2016-04-29 04:20:09','yellow',1,0,0,1);
/*!40000 ALTER TABLE `mod_staffboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaccounts`
--

DROP TABLE IF EXISTS `tblaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaccounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `currency` int(10) NOT NULL,
  `gateway` varchar(128) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `description` text NOT NULL,
  `amountin` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amountout` decimal(10,2) NOT NULL DEFAULT 0.00,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `transid` varchar(64) NOT NULL,
  `invoiceid` int(10) DEFAULT NULL,
  `refundid` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `invoiceid` (`invoiceid`),
  KEY `userid` (`userid`),
  KEY `date` (`date`),
  KEY `transid` (`transid`(32)),
  KEY `currency` (`currency`),
  KEY `gateway` (`gateway`),
  CONSTRAINT `tblaccounts_ibfk_2` FOREIGN KEY (`currency`) REFERENCES `tblcurrencies` (`id`),
  CONSTRAINT `tblaccounts_ibfk_4` FOREIGN KEY (`gateway`) REFERENCES `tblpaymentgatewaynames` (`gateway`),
  CONSTRAINT `tblaccounts_ibfk_5` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblaccounts_ibfk_6` FOREIGN KEY (`invoiceid`) REFERENCES `tblinvoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaccounts`
--

LOCK TABLES `tblaccounts` WRITE;
/*!40000 ALTER TABLE `tblaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblactivitylog`
--

DROP TABLE IF EXISTS `tblactivitylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblactivitylog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `user` text NOT NULL,
  `userid` int(10) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `ipaddr` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblactivitylog`
--

LOCK TABLES `tblactivitylog` WRITE;
/*!40000 ALTER TABLE `tblactivitylog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblactivitylog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladdonmodules`
--

DROP TABLE IF EXISTS `tbladdonmodules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladdonmodules` (
  `module` varchar(64) DEFAULT NULL,
  `setting` varchar(64) NOT NULL,
  `value` varchar(128) NOT NULL,
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladdonmodules`
--

LOCK TABLES `tbladdonmodules` WRITE;
/*!40000 ALTER TABLE `tbladdonmodules` DISABLE KEYS */;
INSERT INTO `tbladdonmodules` VALUES ('paypal_addon','version','2.0'),('staffboard','version','1.1'),('paypal_addon','username','admin'),('paypal_addon','password','admin'),('paypal_addon','signature',''),('paypal_addon','showbalance1','on'),('paypal_addon','showbalance2','on'),('paypal_addon','showbalance3','on'),('staffboard','masteradmin1','on'),('staffboard','masteradmin2','on'),('staffboard','masteradmin3','on'),('paypal_addon','access','1,2,3'),('staffboard','access','1,2,3'),('staffboard','lastviewed','a:1:{i:1;i:1464140360;}'),('hdtolls','version','1.0');
/*!40000 ALTER TABLE `tbladdonmodules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladdons`
--

DROP TABLE IF EXISTS `tbladdons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladdons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `packages` text NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `billingcycle` text NOT NULL,
  `tax` text NOT NULL,
  `showorder` text NOT NULL,
  `autoactivate` text NOT NULL,
  `suspendproduct` text NOT NULL,
  `welcomeemail` int(10) DEFAULT NULL,
  `weight` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(32)),
  KEY `welcomeemail` (`welcomeemail`),
  CONSTRAINT `tbladdons_ibfk_1` FOREIGN KEY (`welcomeemail`) REFERENCES `tblemailtemplates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladdons`
--

LOCK TABLES `tbladdons` WRITE;
/*!40000 ALTER TABLE `tbladdons` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladdons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladdontoservice`
--

DROP TABLE IF EXISTS `tbladdontoservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladdontoservice` (
  `addonid` int(11) NOT NULL,
  `serviceid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladdontoservice`
--

LOCK TABLES `tbladdontoservice` WRITE;
/*!40000 ALTER TABLE `tbladdontoservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladdontoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladminlog`
--

DROP TABLE IF EXISTS `tbladminlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladminlog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `adminusername` varchar(64) NOT NULL,
  `logintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logouttime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ipaddress` varchar(64) NOT NULL,
  `sessionid` varchar(64) NOT NULL,
  `lastvisit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `adminid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logouttime` (`logouttime`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tbladminlog_ibfk_1` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladminlog`
--

LOCK TABLES `tbladminlog` WRITE;
/*!40000 ALTER TABLE `tbladminlog` DISABLE KEYS */;
INSERT INTO `tbladminlog` VALUES (1,'raadmin','2018-02-08 00:10:13','2018-02-08 00:10:20','192.168.121.227','97cn6gfeikcuqli0ivcoeeqlf2','2018-02-08 00:10:17',NULL);
/*!40000 ALTER TABLE `tbladminlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladminperms`
--

DROP TABLE IF EXISTS `tbladminperms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladminperms` (
  `roleid` int(1) NOT NULL,
  `permid` int(1) NOT NULL,
  KEY `roleid_permid` (`roleid`,`permid`),
  CONSTRAINT `tbladminperms_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `tbladminroles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladminperms`
--

LOCK TABLES `tbladminperms` WRITE;
/*!40000 ALTER TABLE `tbladminperms` DISABLE KEYS */;
INSERT INTO `tbladminperms` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57),(1,58),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64),(1,65),(1,66),(1,67),(1,68),(1,69),(1,70),(1,71),(1,72),(1,73),(1,74),(1,75),(1,76),(1,77),(1,78),(1,79),(1,80),(1,81),(1,82),(1,83),(1,84),(1,85),(1,86),(1,87),(1,88),(1,89),(1,90),(1,91),(1,92),(1,93),(1,94),(1,95),(1,96),(1,97),(1,98),(1,99),(1,100),(1,101),(1,102),(1,103),(1,104),(1,105),(1,106),(1,107),(1,108),(1,109),(1,110),(1,111),(1,112),(1,113),(1,114),(1,115),(1,116),(1,117),(1,118),(1,119),(1,120),(1,121),(1,122),(1,123),(1,124),(1,125),(1,126),(1,127),(1,128),(1,129),(1,150),(2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(2,25),(2,26),(2,27),(2,28),(2,29),(2,30),(2,31),(2,32),(2,33),(2,34),(2,35),(2,36),(2,37),(2,38),(2,39),(2,40),(2,41),(2,42),(2,43),(2,44),(2,45),(2,46),(2,47),(2,48),(2,49),(2,50),(2,51),(2,52),(2,71),(2,73),(2,85),(2,98),(2,99),(2,101),(2,104),(2,105),(2,110),(2,120),(2,123),(2,124),(2,125),(2,125),(2,126),(2,126),(2,128),(2,129),(3,38),(3,39),(3,40),(3,41),(3,42),(3,43),(3,44),(3,50),(3,105),(3,125),(3,125),(3,126),(3,128);
/*!40000 ALTER TABLE `tbladminperms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladminroles`
--

DROP TABLE IF EXISTS `tbladminroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladminroles` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `widgets` longtext NOT NULL,
  `report` text NOT NULL,
  `systememails` int(1) NOT NULL,
  `accountemails` int(1) NOT NULL,
  `supportemails` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladminroles`
--

LOCK TABLES `tbladminroles` WRITE;
/*!40000 ALTER TABLE `tbladminroles` DISABLE KEYS */;
INSERT INTO `tbladminroles` VALUES (1,'Full Admin','admin_activity,client_log,income_forecast,income_left_overview,order_left_overview,recent_left_orders','clients_by_country,transactions,affiliates_overview,top_25_clients_by_income,monthly_orders,AUDIT-All-Services,new_customers,client_sources,direct_debit_processing,income_by_product,ticket_tags,region_report,ticket_ratings_reviewer,suspend_customers,heat_map,check,totalorder,cancel_customer,invoices,annual_income_report,support_ticket_replies,aging_invoices,monthly_transactions,credits_reviewer,totalsale,sales_tax_liability,server_revenue_forecasts,promotions_usage,product_suspensions,income_forecast,ticket_feedback_scores,pdf_batch,ticket_feedback_comments,services,client_statement,daily_performance,',1,1,1),(2,'Sales Operator','activity_log,getting_started,income_forecast,income_overview,my_','',0,1,1),(3,'Support Operator','activity_log,getting_started,my_notes,todo_list,ra_news,supportt','',0,0,1);
/*!40000 ALTER TABLE `tbladminroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladmins`
--

DROP TABLE IF EXISTS `tbladmins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladmins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `roleid` int(1) NOT NULL,
  `username` varchar(64) NOT NULL,
  `authmodule` varchar(32) NOT NULL,
  `authdata` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `signature` text NOT NULL,
  `notes` text NOT NULL,
  `template` varchar(64) NOT NULL,
  `language` varchar(64) NOT NULL,
  `disabled` int(1) NOT NULL,
  `loginattempts` int(1) NOT NULL,
  `supportdepts` text NOT NULL,
  `ticketnotifications` text NOT NULL,
  `homewidgets` text NOT NULL,
  `passwordhash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32)),
  KEY `roleid` (`roleid`),
  CONSTRAINT `tbladmins_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `tbladminroles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladmins`
--

LOCK TABLES `tbladmins` WRITE;
/*!40000 ALTER TABLE `tbladmins` DISABLE KEYS */;
INSERT INTO `tbladmins` VALUES (1,1,'raadmin','','','Sample','Admin','default@example.com','','','ra','english',0,0,'1','','calendar:true,orders_overview:true,supporttickets_overview:true,my_notes:true,client_activity:true,open_invoices:true,activity_log:true|income_overview:true,system_overview:true,sysinfo:true,admin_activity:true,todo_list:true,income_forecast:true|','$1$xyz$PPirjAc2drfJW1BFPc5FY0');
/*!40000 ALTER TABLE `tbladmins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladresschecker`
--

DROP TABLE IF EXISTS `tbladresschecker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladresschecker` (
  `id` int(11) NOT NULL,
  `address` varchar(255) CHARACTER SET latin1 NOT NULL,
  `date` date NOT NULL,
  `user_agent` mediumtext CHARACTER SET latin1 NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladresschecker`
--

LOCK TABLES `tbladresschecker` WRITE;
/*!40000 ALTER TABLE `tbladresschecker` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbladresschecker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaffiliates`
--

DROP TABLE IF EXISTS `tblaffiliates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `clientid` int(10) NOT NULL,
  `visitors` int(1) NOT NULL,
  `paytype` text NOT NULL,
  `payamount` decimal(10,2) NOT NULL,
  `onetime` int(1) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `withdrawn` decimal(10,2) NOT NULL DEFAULT 0.00,
  KEY `affiliateid` (`id`),
  KEY `clientid` (`clientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaffiliates`
--

LOCK TABLES `tblaffiliates` WRITE;
/*!40000 ALTER TABLE `tblaffiliates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaffiliatesaccounts`
--

DROP TABLE IF EXISTS `tblaffiliatesaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliatesaccounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affiliateid` int(10) NOT NULL,
  `relid` int(10) NOT NULL,
  `lastpaid` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `affiliateid` (`affiliateid`),
  KEY `relid` (`relid`),
  CONSTRAINT `tblaffiliatesaccounts_ibfk_1` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`),
  CONSTRAINT `tblaffiliatesaccounts_ibfk_2` FOREIGN KEY (`affiliateid`) REFERENCES `tblaffiliates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaffiliatesaccounts`
--

LOCK TABLES `tblaffiliatesaccounts` WRITE;
/*!40000 ALTER TABLE `tblaffiliatesaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliatesaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaffiliateshistory`
--

DROP TABLE IF EXISTS `tblaffiliateshistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliateshistory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affiliateid` int(10) NOT NULL,
  `date` date NOT NULL,
  `affaccid` int(10) DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliateid` (`affiliateid`),
  KEY `affaccid` (`affaccid`),
  CONSTRAINT `tblaffiliateshistory_ibfk_1` FOREIGN KEY (`affiliateid`) REFERENCES `tblaffiliates` (`id`),
  CONSTRAINT `tblaffiliateshistory_ibfk_2` FOREIGN KEY (`affaccid`) REFERENCES `tblaffiliatesaccounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaffiliateshistory`
--

LOCK TABLES `tblaffiliateshistory` WRITE;
/*!40000 ALTER TABLE `tblaffiliateshistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliateshistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaffiliatespending`
--

DROP TABLE IF EXISTS `tblaffiliatespending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliatespending` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affaccid` int(10) NOT NULL DEFAULT 0,
  `amount` decimal(10,2) NOT NULL,
  `clearingdate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clearingdate` (`clearingdate`),
  KEY `affaccid` (`affaccid`),
  CONSTRAINT `tblaffiliatespending_ibfk_1` FOREIGN KEY (`affaccid`) REFERENCES `tblaffiliatesaccounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaffiliatespending`
--

LOCK TABLES `tblaffiliatespending` WRITE;
/*!40000 ALTER TABLE `tblaffiliatespending` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliatespending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblaffiliateswithdrawals`
--

DROP TABLE IF EXISTS `tblaffiliateswithdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliateswithdrawals` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affiliateid` int(10) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliateid` (`affiliateid`),
  CONSTRAINT `tblaffiliateswithdrawals_ibfk_1` FOREIGN KEY (`affiliateid`) REFERENCES `tblaffiliates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblaffiliateswithdrawals`
--

LOCK TABLES `tblaffiliateswithdrawals` WRITE;
/*!40000 ALTER TABLE `tblaffiliateswithdrawals` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblaffiliateswithdrawals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbannedemails`
--

DROP TABLE IF EXISTS `tblbannedemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbannedemails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain` varchar(64) NOT NULL,
  `count` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbannedemails`
--

LOCK TABLES `tblbannedemails` WRITE;
/*!40000 ALTER TABLE `tblbannedemails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblbannedemails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbannedips`
--

DROP TABLE IF EXISTS `tblbannedips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbannedips` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `reason` text NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbannedips`
--

LOCK TABLES `tblbannedips` WRITE;
/*!40000 ALTER TABLE `tblbannedips` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblbannedips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcalendar`
--

DROP TABLE IF EXISTS `tblcalendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcalendar` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `desc` text NOT NULL,
  `start` int(10) NOT NULL,
  `end` int(10) NOT NULL,
  `allday` int(1) NOT NULL,
  `adminid` int(10) NOT NULL,
  `recurid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tblcalendar_ibfk_1` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcalendar`
--

LOCK TABLES `tblcalendar` WRITE;
/*!40000 ALTER TABLE `tblcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcalendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcancelrequests`
--

DROP TABLE IF EXISTS `tblcancelrequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcancelrequests` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `submitted` datetime DEFAULT NULL,
  `requestedfor` datetime DEFAULT NULL,
  `relid` int(10) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `serviceid` (`relid`),
  CONSTRAINT `tblcancelrequests_ibfk_1` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcancelrequests`
--

LOCK TABLES `tblcancelrequests` WRITE;
/*!40000 ALTER TABLE `tblcancelrequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcancelrequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclientfields`
--

DROP TABLE IF EXISTS `tblclientfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclientfields` (
  `cfid` int(10) NOT NULL AUTO_INCREMENT,
  `fieldname` text NOT NULL COMMENT 'Name to show on forms',
  `fieldtype` enum('dropdown','textarea','text','tickbox','link','password','date','option','more') DEFAULT NULL COMMENT 'Custom field type',
  `description` text DEFAULT NULL,
  `fieldoptions` text DEFAULT NULL,
  `regexpr` text DEFAULT NULL COMMENT 'Regex which must be matched for value to be valid',
  `adminonly` tinyint(1) DEFAULT 0 COMMENT 'Whether only visible to admin / system',
  `required` tinyint(1) DEFAULT 0 COMMENT 'Whether customfield is required',
  `showinvoice` tinyint(1) DEFAULT 0 COMMENT 'Whether to show on client-facing invoices',
  `sortorder` int(10) NOT NULL DEFAULT 0,
  `showorder` int(10) DEFAULT NULL COMMENT 'Order to display in on order form, client area and admin area',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cfid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclientfields`
--

LOCK TABLES `tblclientfields` WRITE;
/*!40000 ALTER TABLE `tblclientfields` DISABLE KEYS */;
INSERT INTO `tblclientfields` VALUES (1,'Where did you hear about us?','dropdown','','Word of Mouth,Google Search,Forum,Social Media,Radio,Other Search Engine,Other Marketing,Affiliates,Other','',0,1,0,0,1,NULL),(2,'Credit Control &amp; Account Notes','textarea','','','',0,0,0,0,0,NULL),(3,'Mobile Number','text','','','',0,1,0,0,1,NULL),(4,'Fax Number','text','','','',0,0,0,0,1,NULL),(5,'Affiliate Bank Account Details &amp; Notes','textarea','','','',0,0,0,0,0,NULL),(6,'Test 2','dropdown','','test 1,test 2,test 3,test 4','',0,0,0,0,0,NULL),(7,'test 1','tickbox','','','',0,0,0,0,0,NULL),(8,'test 3','date','','','',0,0,0,0,0,NULL),(9,'test 4','textarea','','','',0,0,0,0,0,NULL);
/*!40000 ALTER TABLE `tblclientfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclientfieldsvalues`
--

DROP TABLE IF EXISTS `tblclientfieldsvalues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclientfieldsvalues` (
  `cfid` int(10) NOT NULL,
  `relid` int(10) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclientfieldsvalues`
--

LOCK TABLES `tblclientfieldsvalues` WRITE;
/*!40000 ALTER TABLE `tblclientfieldsvalues` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblclientfieldsvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclientgroups`
--

DROP TABLE IF EXISTS `tblclientgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclientgroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(45) NOT NULL,
  `groupcolour` varchar(45) DEFAULT NULL,
  `discountpercent` decimal(10,2) unsigned DEFAULT 0.00,
  `susptermexempt` text DEFAULT NULL,
  `separateinvoices` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclientgroups`
--

LOCK TABLES `tblclientgroups` WRITE;
/*!40000 ALTER TABLE `tblclientgroups` DISABLE KEYS */;
INSERT INTO `tblclientgroups` VALUES (0,'Default',NULL,0.00,NULL,''),(1,'Business','#ffff00',0.00,'on',''),(2,'Residential','#ffffff',0.00,'','');
/*!40000 ALTER TABLE `tblclientgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclients`
--

DROP TABLE IF EXISTS `tblclients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `companyname` varchar(256) NOT NULL,
  `email` text NOT NULL,
  `address1` varchar(256) NOT NULL,
  `address2` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `state` varchar(256) NOT NULL,
  `postcode` text NOT NULL,
  `country` varchar(256) NOT NULL,
  `phonenumber` text DEFAULT NULL,
  `mobilenumber` varchar(16) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `authmodule` text NOT NULL,
  `authdata` text NOT NULL,
  `currency` int(10) NOT NULL,
  `defaultgateway` varchar(64) DEFAULT NULL,
  `credit` decimal(10,2) NOT NULL,
  `taxexempt` text NOT NULL,
  `latefeeoveride` text NOT NULL,
  `overideduenotices` text DEFAULT NULL,
  `separateinvoices` text NOT NULL,
  `disableautocc` text NOT NULL,
  `datecreated` date NOT NULL,
  `notes` text NOT NULL,
  `billingcid` int(10) NOT NULL,
  `securityqid` int(10) DEFAULT NULL,
  `securityqans` text NOT NULL,
  `groupid` int(10) unsigned DEFAULT 0,
  `lastlogin` datetime DEFAULT NULL,
  `ip` text NOT NULL,
  `host` text NOT NULL,
  `status` enum('Active','Inactive','Closed') NOT NULL DEFAULT 'Active',
  `language` text NOT NULL,
  `pwresetkey` text NOT NULL,
  `pwresetexpiry` int(10) NOT NULL,
  `emailoptout` int(1) NOT NULL,
  `overrideautoclose` int(1) NOT NULL,
  `dateofbirth` date DEFAULT NULL,
  `email_notification` text NOT NULL,
  `txt_notification` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `firstname_lastname` (`firstname`(32),`lastname`(32)),
  KEY `email` (`email`(64)),
  KEY `groupid` (`groupid`),
  KEY `currency` (`currency`),
  KEY `defaultgateway` (`defaultgateway`),
  CONSTRAINT `tblclients_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `tblclientgroups` (`id`),
  CONSTRAINT `tblclients_ibfk_2` FOREIGN KEY (`currency`) REFERENCES `tblcurrencies` (`id`),
  CONSTRAINT `tblclients_ibfk_3` FOREIGN KEY (`defaultgateway`) REFERENCES `tblpaymentgatewaynames` (`gateway`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclients`
--

LOCK TABLES `tblclients` WRITE;
/*!40000 ALTER TABLE `tblclients` DISABLE KEYS */;
INSERT INTO `tblclients` VALUES (1,'Sample','Client','Sample Company','raclient@example.com','12 Place','Albany','Auckland','Auckland','0632','New Zealand','456575',NULL,'$1$xyz$iaHH2URWtrN/4vLGA7dzj1','','',1,'banktransfer',0.00,'','','','','','2017-02-21','',0,0,'rPUFl0h9a3EXo5K6wXiDyAf7Wts=',0,'2017-02-21 15:01:32','49.50.253.2','49.50.253.2','Active','','36998ab8babc74c588b86b0e0b4f6965',1498201700,0,0,'0000-00-00','','');
/*!40000 ALTER TABLE `tblclients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblclientsfiles`
--

DROP TABLE IF EXISTS `tblclientsfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclientsfiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `title` text NOT NULL,
  `filename` text NOT NULL,
  `adminonly` int(1) NOT NULL,
  `dateadded` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `tblclientsfiles_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblclientsfiles`
--

LOCK TABLES `tblclientsfiles` WRITE;
/*!40000 ALTER TABLE `tblclientsfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblclientsfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblconfiguration`
--

DROP TABLE IF EXISTS `tblconfiguration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblconfiguration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `setting` (`setting`(32))
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblconfiguration`
--

LOCK TABLES `tblconfiguration` WRITE;
/*!40000 ALTER TABLE `tblconfiguration` DISABLE KEYS */;
INSERT INTO `tblconfiguration` VALUES (1,'Language','en'),(2,'CompanyName','Unlimited Internet'),(3,'Email','email@dev.roboticaccounting.com'),(4,'Domain','http://peter.dev.roboticaccounting.com/'),(5,'LogoURL','https://unlimitedinternet.co.nz/wp-content/uploads/2016/08/UnlimitedInternet_Logo_Web497x124.png'),(6,'SystemURL','https://dev.roboticaccounting.com/'),(7,'SystemSSLURL',''),(8,'AutoSuspension','on'),(9,'AutoSuspensionDays','5'),(10,'CreateInvoiceDaysBefore','14'),(11,'AffiliateEnabled',''),(12,'AffiliateEarningPercent','0'),(13,'AffiliateBonusDeposit','0.00'),(14,'AffiliatePayout','0.00'),(15,'AffiliateLinks',''),(16,'ActivityLimit','10000'),(17,'DateFormat','DD/MM/YYYY'),(18,'PreSalesQuestions',''),(19,'Template','uihex'),(20,'AllowRegister','on'),(21,'AllowTransfer','on'),(22,'AllowOwnDomain','on'),(23,'EnableTOSAccept',''),(24,'TermsOfService',''),(25,'AllowLanguageChange','on'),(26,'Version','5.2.15'),(27,'AllowCustomerChangeInvoiceGateway','on'),(28,'DefaultNameserver1','ns1.yourdomain.com'),(29,'DefaultNameserver2','ns2.yourdomain.com'),(30,'SendInvoiceReminderDays','7'),(31,'SendReminder','on'),(32,'NumRecordstoDisplay','25'),(33,'BCCMessages',''),(34,'MailType','mail'),(35,'SMTPHost',''),(36,'SMTPUsername','admin'),(37,'SMTPPassword','Gj9qRk94o1t2x2Urt/krODjg+Dw='),(38,'SMTPPort','25'),(39,'ShowCancellationButton','on'),(40,'UpdateStatsAuto',''),(41,'InvoicePayTo','7 Douglas Alexander Parade\r\nRosedale\r\nAuckland 0632'),(42,'SendAffiliateReportMonthly','on'),(43,'InvalidLoginBanLength','15'),(44,'Signature',''),(45,'DomainOnlyOrderEnabled','on'),(46,'TicketBannedAddresses',''),(47,'SendEmailNotificationonUserDetailsChange','on'),(48,'TicketAllowedFileTypes','.jpg,.gif,.jpeg,.png'),(49,'CloseInactiveTickets','0'),(50,'InvoiceLateFeeAmount','10.00'),(51,'AutoTermination',''),(52,'AutoTerminationDays','30'),(53,'RegistrarAdminFirstName',''),(54,'RegistrarAdminLastName',''),(55,'RegistrarAdminCompanyName',''),(56,'RegistrarAdminAddress1',''),(57,'RegistrarAdminAddress2',''),(58,'RegistrarAdminCity',''),(59,'RegistrarAdminStateProvince',''),(60,'RegistrarAdminCountry','CN'),(61,'RegistrarAdminPostalCode',''),(62,'RegistrarAdminPhone',''),(63,'RegistrarAdminFax',''),(64,'RegistrarAdminEmailAddress',''),(65,'RegistrarAdminUseClientDetails','on'),(66,'Charset','utf-8'),(67,'AutoUnsuspend',''),(68,'RunScriptonCheckOut',''),(69,'License','MDA1MDdkNDQ2YjkzZjZlMWU5MGJmMzI5MjcxZTYyODZkNTEzMThhOG1kcFIzWUJKaU8yb3pjN0l5YzFS\r\nWFkwTm5JNllqT3p0akl4TVRMeUVUTDVrRE15SWlPd0VqT3p0aklsUlhZa1ZXZGtSSGVsNW1JNkVUTTZN\r\nM09pd1dZMjlXYmxKRkluNVdhazVXWXlKa0k2WVRNNk0zT2lVV2JoNW1JNlFqT3p0bk96b1RZN0VqT3Ax\r\nM09pVW1kcFIzWUJKaU8yb3pjN0l5YzFSWFkwTm5JNllqT3p0akl4TVRMeUVUTDVrRE15SWlPd0VqT3p0\r\naklsUlhZa1ZXZGtSSGVsNW1JNkVUTTZNM09pQUhjQkJDWnA5bWNrNVdRaW9UTXhvemM3SVNadEZtYmlv\r\nRE42TTNlNk1qT2h0RE02azJlNkFUTTZFbUk2TURPd0VqT3p0akl6NTJia1JXWWlvak42TTNPaVVtZHBS\r\nM1lCMURiaFozYnRWbVVnY21icFJtYmhKblFpb3pNeW96YzdJeWN1OVdhMEIzYm5sbVp1OTJZaW96TXhv\r\nemM3SUNidFJIYWZOV2FzSldkdzlTYnZObUxuNVdhMDVXZHZOMlloTldhMDltWXZKbkwyVkdadFFHYXZj\r\nM2QzOWljaFozTGlvVE8wb3pjN0l5Y3lsR1prbEdiaFpuSTZrak96dGpJczFHZG85MVlweG1ZMUIzTHQ5\r\nMll1Y21icFJuYjE5MllqRjJZcFIzYmk5bWN1WVhaazFDWm85eWQzZDNMeUZtZHZJaU81UWpPenRqSTVK\r\nM2IwTldaeWxHWmtsR2JoWm5JNlFUTTZNM09pSVRNdVVETnk0Q00xNFNPMElpT3lFak96dGpJekJYYWts\r\nR2JoWm5JNmdqT3p0akl5RWpMMVFqTXVBVE51a0ROaW9qTXhvemM3SUNjcFJXYXNGbWRpb3pONk0zT2k0\r\nMll1SVhadGxHZHQ1eWQzZEhMdDkyWXVjbWJwUm5iMTkyWWpGMllwUjNiaTltY3VZWFprSmlPNU1qT3p0\r\nakl6NVdhaDEyYmtSV2FzRm1kaW9qTXhvemM3SWliajVpY2wxV2EwMW1MM2QzZHMwMmJqNXladWxHZHVW\r\nM2JqTldZamxHZHZKMmJ5NWlkbFJtSTZrek02TTNPaTRXYWgxMmJrUldhc0ZtZGlvVE14b3pjN0lTWnRs\r\nR1ZnVW1iUEppTzRvemM3SVNac05XZWpkbWJweEdicEptSTZJVE02TTNPaUV6TXRJVE10Z1RNd0lqSTZB\r\nVE02TTNPaVVHZGhSV1oxUkdkNFZtYmlvVE14b3pjN0lpTXkwU013MENOeEFqTWlvRE14b3pjN0lTWjBG\r\nR1puVm1jaW96TjZNM09pSVZSTmxFVk5KaU8yb3pjN0lpY2x4R2JsTlhaeUppTzRvemM3SUNNaW9UTTZN\r\nM09pTTNjbE4yWWhSbmN2QkhjMU5uSTZNVE02TTNPaUFqSTZFak96dGpJelZHZGhSR2MxTlhaeWxXZHhW\r\nbWNpb1ROeG96YzdJaXU2U2VpRld1dFhhT2xBSytvbmlPdGdlZWdnZStvbmlPcUZXT2p1V3VJNkF6TTZN\r\nM09pVVdiaDVHZGpWSFp2SkhjaW9UTXhvemM3SVNOaW9UTTZNM09pUVdhME5XZGs5bWN3SmlPNW96YzdJ\r\nQ2lKZStvbmlPdGdldXU2U2VpRld1dFhhT0kyRWpMeTRTTmdNMVFOaDBWaW9UTXpvemM3SVNadEZtYmtW\r\nbWNsUjNjcGRXWnlKaU8wRWpPenRqSWxaWGEwTldRaW9qTjZNM09pTVhkMEZHZHpKaU8yb3pjN3BqTXlv\r\nVFk5MTk0OTc4MTczNzBhZDk1M2I1MWI5OTE3ZGEyOTY5NTM4MmIzNTY1PTAzT2lrak0wQWpOeEFqTWlv\r\nRE82TTNPaVVHZGhSMmFqVkdhakppTzVvemM3SWlabUpETnpnVFltTlRNbUZUWmlCek0xWVdPbTFDWmw1\r\nMmRQSmlPMklqT3p0akk1VjJhaW96TTZNM09pWVRNdUlqTDFJaU8yb3pjN0lpYnZsMmN5Vm1kME5YWjBG\r\nR2Jpb3pNeG96YzdJU040SVdNeUF6TWlORE0zVW1OMUFETnhJMk1sQmpOakpHTnhRVFpoTmpZeUlpT3lN\r\nak96dGpJb05YWW9WRFp0SmlPM296YzdJU2Y5dGpJbFpYYTBOV1Fpb2pONk0zT2lNWGQwRkdkekppTzJv\r\nemM3SVNNejBpTXgwU081QWpNaW9ETXhvemM3SVNaMEZHWmxWSFowaFhadUppT3hFak96dGpJd0JYUWdn\r\nREl6ZDNiazVXYVhKaU96RWpPenRqSWwxV1l1SmlPMG96Yzdwek02RTJPNW9UYTl0aklsWlhhME5XUWlv\r\nak42TTNPaU1YZDBGR2R6SmlPMm96YzdJU016MGlNeDBTTzVBak1pb0RNeG96YzdJU1owRkdabFZIWjBo\r\nWFp1SmlPeEVqT3p0akl6VkdkaFJHY1ZCQ1p1RkdJMEozYndCWGRUSmlPNUVqT3p0aklsMVdZdUppTzBv\r\nemM3cHpNNkUyTzRvVGE5dGpJbFpYYTBOV1Fpb2pONk0zT2lNWGQwRkdkekppTzJvemM3SVNNejBpTXgw\r\nU081QWpNaW9ETXhvemM3SVNaMEZHWmxWSFowaFhadUppT3hFak96dGpJdTlXYTBsR1pGQlNac2xtWXYx\r\na0k2UVRNNk0zT2lVV2JoNW1JNlFqT3p0bk96b1RZN2NqT3AxM09pVW1kcFIzWUJKaU8yb3pjN0l5YzFS\r\nWFkwTm5JNllqT3p0akl4TVRMeUVUTDVrRE15SWlPd0VqT3p0aklsUlhZa1ZXZGtSSGVsNW1JNkVUTTZN\r\nM09pUVhZb05FSWxaWGFNSmlPNW96YzdJU1p0Rm1iaW9ETjZNM2U2TWpPaHRqTjZrV2Y3SVNaMmxHZGpG\r\na0k2WWpPenRqSXpWSGRoUjNjaW9qTjZNM09pRXpNdElUTXRrVE93SWpJNkFUTTZNM09pVUdkaFJXWjFS\r\nR2Q0Vm1iaW9UTXhvemM3SWlidlJHWkJCeVp1bDJjdVYyWXB4a0k2VVRNNk0zT2lVV2JoNW1JNlFqT3p0\r\nbk96b1RZN1VqT3AxM09pVW1kcFIzWUJKaU8yb3pjN0l5YzFSWFkwTm5JNllqT3p0akl4TVRMeUVUTDVr\r\nRE15SWlPd0VqT3p0aklsUlhZa1ZXZGtSSGVsNW1JNkVUTTZNM09pQUhjQkJTWnU5R2FRbG1JNkFUTTZN\r\nM09pVVdiaDVtSTZRak96dG5Pem9UWTdRak9wMTNPaVVtZHBSM1lCSmlPMm96YzdJeWMxUlhZME5uSTZZ\r\nak96dGpJeE1UTHlFVEw1a0RNeUlpT3dFak96dGpJbFJYWWtWV2RrUkhlbDVtSTZFVE02TTNPaTQyYmtS\r\nV1FnUW5ibDFXWm5GbWJoMUVJME5XWnE5bWNRSmlPMElqT3p0aklsMVdZdUppTzBvemM3cHpNNkUyT3pv\r\nVGE5dGpJbFpYYTBOV1Fpb2pONk0zT2lNWGQwRkdkekppTzJvemM3SVNNejBpTXgwU081QWpNaW9ETXhv\r\nemM3SVNaMEZHWmxWSFowaFhadUppT3hFak96dGpJdTlHWmtGRUlsZFdZck5XWVFCU1pzSldZeVYzWnBa\r\nbWJ2TmtJNllqTTZNM09pVVdiaDVtSTZRak96dG5Pem9UWTdJak9wMTNPaVViN2RlZTYyYTEzZTM3NzMx\r\nOGM3MzI3YTYxZmQ0N2ZlMjMwZWIxODdm'),(70,'OrderFormTemplate','modern'),(71,'AllowDomainsTwice','on'),(72,'AddLateFeeDays','7'),(73,'TaxEnabled','on'),(74,'DefaultCountry','New Zealand'),(75,'AutoRedirectoInvoice','gateway'),(76,'EnablePDFInvoices','on'),(77,'CaptchaSetting','offloggedin'),(78,'SupportTicketOrder','ASC'),(79,'SendFirstOverdueInvoiceReminder','1'),(80,'TaxType','Inclusive'),(81,'DomainDNSManagement','5.00'),(82,'DomainEmailForwarding','5.00'),(83,'InvoiceIncrement','1'),(84,'ContinuousInvoiceGeneration',''),(85,'AutoCancellationRequests','on'),(86,'SystemEmailsFromName','RACompleteSolution'),(87,'SystemEmailsFromEmail','noreply@yourdomain.com'),(88,'AllowClientRegister','on'),(89,'BulkCheckTLDs',''),(90,'OrderDaysGrace','0'),(91,'CreditOnDowngrade','on'),(92,'AcceptedCardTypes','Visa,MasterCard,Discover,American Express,JCB,EnRoute,Diners Club'),(93,'TaxDomains',''),(94,'TaxLateFee',''),(95,'AdminForceSSL','on'),(96,'ProductMonthlyPricingBreakdown',''),(97,'LateFeeType','Percentage'),(98,'SendSecondOverdueInvoiceReminder','0'),(99,'SendThirdOverdueInvoiceReminder','0'),(100,'DomainIDProtection','5.00'),(101,'DomainRenewalNotices',''),(102,'SequentialInvoiceNumbering',''),(103,'SequentialInvoiceNumberFormat',''),(104,'SequentialInvoiceNumberValue',''),(105,'DefaultNameserver3',''),(106,'DefaultNameserver4',''),(107,'AffiliatesDelayCommission','0'),(108,'SupportModule',''),(109,'AddFundsEnabled','on'),(110,'AddFundsMinimum','10.00'),(111,'AddFundsMaximum','100.00'),(112,'AddFundsMaximumBalance','300.00'),(113,'OrderDaysGrace','0'),(115,'CCProcessDaysBefore','0'),(116,'CCAttemptOnlyOnce',''),(117,'CCDaySendExpiryNotices','25'),(118,'BulkDomainSearchEnabled','on'),(119,'AutoRenewDomainsonPayment','on'),(120,'DomainAutoRenewDefault','on'),(121,'CCRetryEveryWeekFor','0'),(122,'SupportTicketKBSuggestions','on'),(123,'DailyEmailBackup',''),(124,'FTPBackupHostname',''),(125,'FTPBackupUsername',''),(126,'FTPBackupPassword','O5wHaP5eFDlSbgRToElmrtA26U0='),(127,'FTPBackupDestination','/'),(128,'TaxL2Compound',''),(129,'EmailCSS','body,td { font-family: verdana; font-size: 11px; font-weight: normal; }\r\na { color: #0000ff; }'),(130,'SEOFriendlyUrls',''),(131,'ShowCCIssueStart',''),(132,'ClientDropdownFormat','1'),(133,'TicketRatingEnabled','on'),(134,'NetworkIssuesRequireLogin','on'),(135,'ShowNotesFieldonCheckout','on'),(136,'RequireLoginforClientTickets','on'),(137,'NOMD5',''),(138,'CurrencyAutoUpdateExchangeRates',''),(139,'CurrencyAutoUpdateProductPrices',''),(140,'RequiredPWStrength','50'),(141,'MaintenanceMode',''),(142,'MaintenanceModeMessage','We are currently performing maintenance and will be back shortly.'),(143,'SkipFraudForExisting',''),(144,'SMTPSSL',''),(145,'ContactFormDept',''),(146,'ContactFormTo',''),(147,'TicketEscalationLastRun','2009-01-01 00:00:00'),(148,'APIAllowedIPs','a:1:{i:0;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}}'),(149,'DisableSessionIPCheck','on'),(150,'DisableSupportTicketReplyEmailsLogging',''),(151,'OverageBillingMethod','1'),(152,'CCNeverStore',''),(153,'CCAllowCustomerDelete',''),(154,'CreateDomainInvoiceDaysBefore',''),(155,'NoInvoiceEmailOnOrder',''),(156,'TaxInclusiveDeduct',''),(157,'LateFeeMinimum','0.00'),(158,'AutoProvisionExistingOnly',''),(159,'EnableDomainRenewalOrders','on'),(160,'EnableMassPay','on'),(161,'NoAutoApplyCredit',''),(162,'CreateInvoiceDaysBeforeMonthly',''),(163,'CreateInvoiceDaysBeforeQuarterly',''),(164,'CreateInvoiceDaysBeforeSemiAnnually',''),(165,'CreateInvoiceDaysBeforeAnnually',''),(166,'CreateInvoiceDaysBeforeBiennially',''),(167,'CreateInvoiceDaysBeforeTriennially',''),(168,'ClientsProfileUneditableFields',''),(169,'ClientDisplayFormat','1'),(170,'CCDoNotRemoveOnExpiry',''),(171,'GenerateRandomUsername',''),(172,'AddFundsRequireOrder','on'),(173,'GroupSimilarLineItems','on'),(174,'ProrataClientsAnniversaryDate',''),(175,'TCPDFFont','helvetica'),(176,'CancelInvoiceOnCancellation','on'),(177,'AttachmentThumbnails','on'),(178,'EmailGlobalHeader','&lt;p&gt;&lt;a href=&quot;{$company_domain}&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;{$company_logo_url}&quot; alt=&quot;{$company_name}&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;/p&gt;'),(179,'EmailGlobalFooter',''),(180,'DomainSyncEnabled','on'),(181,'DomainSyncNextDueDate',''),(182,'DomainSyncNextDueDateDays','0'),(183,'TicketMask','%n%n%n%n%n%n'),(184,'AutoClientStatusChange','2'),(185,'AllowClientsEmailOptOut',''),(186,'BannedSubdomainPrefixes','mail,mx,gapps,gmail,webmail,cpanel,whm,ftp,clients,billing,members,login,accounts,access'),(187,'FreeDomainAutoRenewRequiresProduct','on'),(188,'DomainToDoListEntries','on'),(189,'InstanceID','kEblFpzf5eqY'),(190,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(191,'MaintenanceModeURL',''),(192,'ClientDateFormat',''),(193,'AllowIDNDomains',''),(194,'DomainSyncNotifyOnly',''),(195,'DefaultNameserver5',''),(196,'ShowClientOnlyDepts',''),(197,'TicketFeedback',''),(198,'DownloadsIncludeProductLinked',''),(199,'AffiliateDepartment','1'),(200,'CaptchaType','recaptcha'),(201,'ReCAPTCHAPrivateKey','6LdYrSMTAAAAAN_xfTd3B6odMsiVxsXCpcWEtr6C'),(202,'ReCAPTCHAPublicKey','6LdYrSMTAAAAAKabOMjEY4Y2e9wIgJNdFY_ed9Yo'),(203,'DisableAdminPWReset',''),(204,'TwitterUsername',''),(205,'AnnouncementsTweet',''),(206,'AnnouncementsFBRecommend',''),(207,'AnnouncementsFBComments',''),(208,'GooglePlus1',''),(209,'ClientsProfileOptionalFields',''),(210,'DefaultToClientArea',''),(211,'DisplayErrors','on'),(212,'SQLErrorReporting',''),(213,'ToggleInfoPopup','a:0:{}'),(214,'ActiveAddonModules',',hdtolls,paypal_addon,staffboard'),(215,'AddonModulesPerms','a:0:{}'),(216,'AddonModulesHooks','hdtolls'),(217,'FTPBackupPort','21'),(218,'ModuleHooks',''),(219,'LoginFailures','a:10:{s:23:\"2403:2f00:f006:911::180\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1466120237;}s:14:\"113.21.227.201\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1487810225;}s:38:\"2403:2f00:f006:911:28f2:c91f:bc7c:e983\";a:2:{s:5:\"count\";i:2;s:7:\"expires\";i:1470781314;}s:24:\"2403:2f00:f007:0:ffff::5\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1473386729;}s:24:\"2403:2f00:f007:0:ffff::7\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1476157540;}s:19:\"2403:2f00:f007::180\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1485227466;}s:23:\"2403:2f00:f007:0:ffff::\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1479335046;}s:11:\"49.50.253.2\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1501810015;}s:13:\"192.168.121.1\";a:2:{s:5:\"count\";i:1;s:7:\"expires\";i:1518049458;}s:15:\"192.168.121.227\";a:2:{s:5:\"count\";i:2;s:7:\"expires\";i:1518050301;}}'),(220,'WhitelistedIPs','a:4:{i:0;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}i:1;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}i:2;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}i:3;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}}'),(221,'InstanceID','m3tpTOXduQyr'),(222,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(223,'InstanceID','eenMkmVwgeMY'),(224,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(225,'InstanceID','3jE3Bq7OYUW8'),(226,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(227,'InstanceID','dr5dT2JhawDC'),(228,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(229,'InstanceID','bfaGhNUkH5ts'),(230,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(231,'InstanceID','rCfKFK548YCF'),(232,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(233,'InstanceID','qNSO1vmkd5fR'),(234,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(235,'InstanceID','xCAiIyz9E9Fd'),(236,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(237,'InstanceID','YOvtSaRNfAI8'),(238,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(239,'InstanceID','iYPwyKkrRj1n'),(240,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),(241,'gst','96-983-506'),(242,'invphone','64 9 280 4135'),(243,'invfax','64 9 280 4134'),(244,'invwebsite','Web: www.hd.net.nz'),(245,'invemail','Email: s@hd.net.nz'),(246,'invaccount',''),(247,'invname',''),(248,'invaddress',''),(249,'invcompany',''),(250,'invpobox',''),(251,'invcity',''),(252,'invpostcode',''),(253,'invcountry','');
/*!40000 ALTER TABLE `tblconfiguration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcontacts`
--

DROP TABLE IF EXISTS `tblcontacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcontacts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `companyname` text NOT NULL,
  `email` text NOT NULL,
  `address1` text NOT NULL,
  `address2` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `postcode` text NOT NULL,
  `country` text NOT NULL,
  `phonenumber` text NOT NULL,
  `mobilenumber` text DEFAULT NULL,
  `subaccount` int(1) NOT NULL DEFAULT 0,
  `password` text NOT NULL,
  `permissions` text NOT NULL,
  `domainemails` int(1) NOT NULL,
  `generalemails` int(1) NOT NULL,
  `invoiceemails` int(1) NOT NULL,
  `productemails` int(1) NOT NULL,
  `supportemails` int(1) NOT NULL,
  `affiliateemails` int(1) NOT NULL,
  `pwresetkey` text NOT NULL,
  `pwresetexpiry` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid_firstname_lastname` (`userid`,`firstname`(32),`lastname`(32)),
  KEY `email` (`email`(64)),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcontacts`
--

LOCK TABLES `tblcontacts` WRITE;
/*!40000 ALTER TABLE `tblcontacts` DISABLE KEYS */;
INSERT INTO `tblcontacts` VALUES (1,3,'TESTFIRST','TESTLAST','','guy@te','','','','','','NZ','phone','mobile',0,'217e08456aa692ed020e10ae50d4a2e4:)wuXf','',0,0,0,0,0,0,'',0),(2,12442,'test searcher','','dsadsa','','','','','','','NZ','','',0,'$2y$10$8l60udoKW0Iw0OZxRwYQceC6wIkWSaWE8WMdH1Le8eCouIXWJbw4q','',0,0,0,0,0,0,'',0),(3,8017,'yue','zhang','HD','waikatozhang@gmail.com','280 queen','','Auckland','','321321','NZ','212220588','',1,'$2y$10$TUH7dOCVxadKvccqLmthT.j6GQYAftMKWldxx8YCPXchJmGm434..','profile,contacts,products,invoices,tickets,affiliates',0,1,0,1,0,0,'',0);
/*!40000 ALTER TABLE `tblcontacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcredit`
--

DROP TABLE IF EXISTS `tblcredit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcredit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clientid` int(10) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `relid` int(10) DEFAULT NULL,
  `adminid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `adminid` (`adminid`),
  KEY `relid` (`relid`),
  CONSTRAINT `tblcredit_ibfk_1` FOREIGN KEY (`clientid`) REFERENCES `tblclients` (`id`),
  CONSTRAINT `tblcredit_ibfk_2` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`),
  CONSTRAINT `tblcredit_ibfk_3` FOREIGN KEY (`relid`) REFERENCES `tblinvoices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcredit`
--

LOCK TABLES `tblcredit` WRITE;
/*!40000 ALTER TABLE `tblcredit` DISABLE KEYS */;
INSERT INTO `tblcredit` VALUES (1,8019,'2017-08-04','Credit Applied to Invoice #152079',-10.00,NULL,NULL);
/*!40000 ALTER TABLE `tblcredit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcreditcards`
--

DROP TABLE IF EXISTS `tblcreditcards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcreditcards` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `cardtype` varchar(255) NOT NULL,
  `cardlastfour` text NOT NULL,
  `cardnum` blob NOT NULL,
  `startdate` blob NOT NULL,
  `expdate` blob NOT NULL,
  `issuenumber` blob NOT NULL,
  `bankname` text NOT NULL,
  `banktype` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `tblcreditcards_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcreditcards`
--

LOCK TABLES `tblcreditcards` WRITE;
/*!40000 ALTER TABLE `tblcreditcards` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcreditcards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcurrencies`
--

DROP TABLE IF EXISTS `tblcurrencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcurrencies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `prefix` text NOT NULL,
  `suffix` text NOT NULL,
  `format` int(1) NOT NULL,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `default` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcurrencies`
--

LOCK TABLES `tblcurrencies` WRITE;
/*!40000 ALTER TABLE `tblcurrencies` DISABLE KEYS */;
INSERT INTO `tblcurrencies` VALUES (1,'NZD','$',' NZD',1,1.00000,1);
/*!40000 ALTER TABLE `tblcurrencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomerservices`
--

DROP TABLE IF EXISTS `tblcustomerservices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomerservices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL COMMENT 'tblclients ID',
  `assign_id` int(11) NOT NULL,
  `orderid` int(10) NOT NULL COMMENT 'tblorders ID',
  `packageid` int(10) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `regdate` datetime NOT NULL,
  `description` varchar(128) DEFAULT NULL COMMENT 'freeform description as will appear on invoice',
  `paymentmethod` varchar(64) DEFAULT NULL,
  `firstpaymentamount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billingcycle` text NOT NULL,
  `nextduedate` date DEFAULT NULL,
  `nextinvoicedate` date NOT NULL,
  `servicestatus` enum('Pending','Active','Suspended','Terminated','Cancelled','Fraud','Draft') NOT NULL,
  `billstatus` enum('Active','Pending','Suspend','Terminate','Cancel') DEFAULT NULL,
  `suspendreason` text DEFAULT NULL,
  `overideautosuspend` text DEFAULT NULL,
  `overidesuspenduntil` date DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `serviceid` (`id`),
  KEY `userid` (`userid`),
  KEY `orderid` (`orderid`),
  KEY `productid` (`packageid`),
  KEY `domain` (`description`(64)),
  KEY `domainstatus` (`servicestatus`),
  KEY `paymentmethod` (`paymentmethod`),
  CONSTRAINT `tblcustomerservices_ibfk_3` FOREIGN KEY (`orderid`) REFERENCES `tblorders` (`id`),
  CONSTRAINT `tblcustomerservices_ibfk_5` FOREIGN KEY (`paymentmethod`) REFERENCES `tblpaymentgatewaynames` (`gateway`),
  CONSTRAINT `tblcustomerservices_ibfk_6` FOREIGN KEY (`packageid`) REFERENCES `tblservices` (`id`),
  CONSTRAINT `tblcustomerservices_ibfk_7` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomerservices`
--

LOCK TABLES `tblcustomerservices` WRITE;
/*!40000 ALTER TABLE `tblcustomerservices` DISABLE KEYS */;
INSERT INTO `tblcustomerservices` VALUES (238,8021,0,240,52,NULL,'2017-07-12 17:50:35','56 Queen Street, Auckland, New Zealand','banktransfer',114.00,65.00,'Monthly','2017-07-12','2017-07-12','Pending',NULL,NULL,NULL,NULL,'2017-07-12 05:50:35',NULL),(239,8022,0,241,50,NULL,'2017-07-13 15:43:03','','banktransfer',0.00,0.00,'One Time','0000-00-00','0000-00-00','Active',NULL,NULL,'','0000-00-00','2017-08-03 23:05:42',''),(240,8022,0,241,52,NULL,'2017-07-13 15:43:03','','banktransfer',114.00,65.00,'Monthly','2017-07-13','2017-07-13','Active',NULL,NULL,NULL,NULL,'2017-07-13 03:43:13',NULL),(241,8019,0,251,51,0,'2017-08-02 12:39:08','122 Great South Road, Papakura, New Zealand','banktransfer',114.00,65.00,'Monthly','2017-08-02','2017-08-02','Active',NULL,NULL,NULL,NULL,'2017-08-03 23:04:54','');
/*!40000 ALTER TABLE `tblcustomerservices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfields`
--

DROP TABLE IF EXISTS `tblcustomfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfields` (
  `cfid` int(10) NOT NULL AUTO_INCREMENT,
  `fieldname` text NOT NULL COMMENT 'Name to show on forms',
  `fieldtype` enum('dropdown','textarea','text','tickbox','link','password','date','option','more') DEFAULT NULL COMMENT 'Custom field type',
  `description` text DEFAULT NULL,
  `fieldoptions` text DEFAULT NULL,
  `regexpr` text DEFAULT NULL COMMENT 'Regex which must be matched for value to be valid',
  `adminonly` tinyint(1) DEFAULT 0 COMMENT 'Whether only visible to admin / system',
  `required` tinyint(1) DEFAULT 0 COMMENT 'Whether customfield is required',
  `showinvoice` tinyint(1) DEFAULT 0 COMMENT 'Whether to show on client-facing invoices',
  `sortorder` int(10) NOT NULL DEFAULT 0,
  `showorder` int(10) DEFAULT NULL COMMENT 'Order to display in on order form, client area and admin area',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cfid`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='Custom fields applied to products and services';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfields`
--

LOCK TABLES `tblcustomfields` WRITE;
/*!40000 ALTER TABLE `tblcustomfields` DISABLE KEYS */;
INSERT INTO `tblcustomfields` VALUES (25,'address','text','','','',0,1,1,1,0,NULL),(26,'ip','text','','','',0,1,1,2,0,NULL),(28,'I want to move from another provider','more','','','',0,0,0,3,0,NULL),(30,'Provider Name','text','','','',0,1,0,0,0,28),(31,'Provider ID','text','','','',0,1,0,0,0,28),(32,'Preferred date','date','','','',0,1,0,0,0,NULL),(33,'Install Date','date','','','',0,0,0,0,0,NULL),(34,'Address','text','','','',0,0,0,0,0,NULL);
/*!40000 ALTER TABLE `tblcustomfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldsgrouplinks`
--

DROP TABLE IF EXISTS `tblcustomfieldsgrouplinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsgrouplinks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cfgid` int(10) NOT NULL,
  `serviceid` int(10) DEFAULT NULL,
  `servicegid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cfgid` (`cfgid`),
  KEY `servicegid` (`servicegid`),
  KEY `serviceid` (`serviceid`),
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_1` FOREIGN KEY (`cfgid`) REFERENCES `tblcustomfieldsgroupnames` (`cfgid`),
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_3` FOREIGN KEY (`servicegid`) REFERENCES `tblservicegroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_4` FOREIGN KEY (`serviceid`) REFERENCES `tblservices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldsgrouplinks`
--

LOCK TABLES `tblcustomfieldsgrouplinks` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgrouplinks` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgrouplinks` VALUES (43,4,48,NULL);
/*!40000 ALTER TABLE `tblcustomfieldsgrouplinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldsgroupmembers`
--

DROP TABLE IF EXISTS `tblcustomfieldsgroupmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsgroupmembers` (
  `cfgid` int(10) NOT NULL COMMENT 'customfield group id',
  `cfid` int(10) NOT NULL COMMENT 'tblcustomfields id',
  PRIMARY KEY (`cfgid`,`cfid`),
  KEY `cfid` (`cfid`),
  CONSTRAINT `tblcustomfieldsgroupmembers_ibfk_1` FOREIGN KEY (`cfgid`) REFERENCES `tblcustomfieldsgroupnames` (`cfgid`),
  CONSTRAINT `tblcustomfieldsgroupmembers_ibfk_2` FOREIGN KEY (`cfid`) REFERENCES `tblcustomfields` (`cfid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldsgroupmembers`
--

LOCK TABLES `tblcustomfieldsgroupmembers` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgroupmembers` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgroupmembers` VALUES (4,33),(4,34);
/*!40000 ALTER TABLE `tblcustomfieldsgroupmembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldsgroupnames`
--

DROP TABLE IF EXISTS `tblcustomfieldsgroupnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsgroupnames` (
  `cfgid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`cfgid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldsgroupnames`
--

LOCK TABLES `tblcustomfieldsgroupnames` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsgroupnames` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsgroupnames` VALUES (4,'Residential Broadband');
/*!40000 ALTER TABLE `tblcustomfieldsgroupnames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldslinks`
--

DROP TABLE IF EXISTS `tblcustomfieldslinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldslinks` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'link identifier',
  `cfid` int(10) DEFAULT NULL COMMENT 'tblcustomfields id',
  `serviceid` int(10) DEFAULT NULL COMMENT 'tblservices id',
  `servicegid` int(10) DEFAULT NULL COMMENT 'tblservicegroups id',
  PRIMARY KEY (`id`),
  KEY `cfid` (`cfid`),
  KEY `serviceid` (`serviceid`),
  KEY `servicegid` (`servicegid`),
  CONSTRAINT `tblcustomfieldslinks_ibfk_1` FOREIGN KEY (`cfid`) REFERENCES `tblcustomfields` (`cfid`),
  CONSTRAINT `tblcustomfieldslinks_ibfk_2` FOREIGN KEY (`serviceid`) REFERENCES `tblservices` (`id`),
  CONSTRAINT `tblcustomfieldslinks_ibfk_3` FOREIGN KEY (`servicegid`) REFERENCES `tblservicegroups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='link customfields to services and servicegroups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldslinks`
--

LOCK TABLES `tblcustomfieldslinks` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldslinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblcustomfieldslinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcustomfieldsvalues`
--

DROP TABLE IF EXISTS `tblcustomfieldsvalues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsvalues` (
  `cfid` int(10) NOT NULL,
  `relid` int(10) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `unique_index` (`cfid`,`relid`),
  KEY `fieldid_relid` (`cfid`,`relid`),
  KEY `relid` (`relid`),
  CONSTRAINT `tblcustomfieldsvalues_ibfk_3` FOREIGN KEY (`cfid`) REFERENCES `tblcustomfields` (`cfid`),
  CONSTRAINT `tblcustomfieldsvalues_ibfk_4` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcustomfieldsvalues`
--

LOCK TABLES `tblcustomfieldsvalues` WRITE;
/*!40000 ALTER TABLE `tblcustomfieldsvalues` DISABLE KEYS */;
INSERT INTO `tblcustomfieldsvalues` VALUES (33,238,''),(33,239,''),(33,240,''),(34,238,''),(34,239,''),(34,240,'');
/*!40000 ALTER TABLE `tblcustomfieldsvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldownloadcats`
--

DROP TABLE IF EXISTS `tbldownloadcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldownloadcats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parentid` int(10) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `hidden` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  CONSTRAINT `tbldownloadcats_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `tbldownloadcats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldownloadcats`
--

LOCK TABLES `tbldownloadcats` WRITE;
/*!40000 ALTER TABLE `tbldownloadcats` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldownloadcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldownloads`
--

DROP TABLE IF EXISTS `tbldownloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldownloads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category` int(10) NOT NULL,
  `type` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `downloads` int(10) NOT NULL DEFAULT 0,
  `location` text NOT NULL,
  `clientsonly` text NOT NULL,
  `hidden` text NOT NULL,
  `productdownload` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`(32)),
  KEY `downloads` (`downloads`),
  KEY `category` (`category`),
  CONSTRAINT `tbldownloads_ibfk_1` FOREIGN KEY (`category`) REFERENCES `tbldownloadcats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldownloads`
--

LOCK TABLES `tbldownloads` WRITE;
/*!40000 ALTER TABLE `tbldownloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldownloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblemails`
--

DROP TABLE IF EXISTS `tblemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblemails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) DEFAULT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `to` text DEFAULT NULL,
  `cc` text DEFAULT NULL,
  `bcc` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `tblemails_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblemails`
--

LOCK TABLES `tblemails` WRITE;
/*!40000 ALTER TABLE `tblemails` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblemails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblemailtemplates`
--

DROP TABLE IF EXISTS `tblemailtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblemailtemplates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `name` text NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `attachments` text NOT NULL,
  `fromname` text NOT NULL,
  `fromemail` text NOT NULL,
  `disabled` text NOT NULL,
  `custom` text NOT NULL,
  `language` text NOT NULL,
  `copyto` text NOT NULL,
  `plaintext` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`(32)),
  KEY `name` (`name`(64))
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblemailtemplates`
--

LOCK TABLES `tblemailtemplates` WRITE;
/*!40000 ALTER TABLE `tblemailtemplates` DISABLE KEYS */;
INSERT INTO `tblemailtemplates` VALUES (7,'support','Support Ticket Opened','New Support Ticket Opened','<p>\r\n{$client_name},\r\n</p>\r\n<p>\r\nThank you for contacting our support team. A support ticket has now been opened for your request. You will be notified when a response is made by email. The details of your ticket are shown below.\r\n</p>\r\n<p>\r\nSubject: {$ticket_subject}<br />\r\nPriority: {$ticket_priority}<br />\r\nStatus: {$ticket_status}\r\n</p>\r\n<p>\r\nYou can view the ticket at any time at {$ticket_link}\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(8,'support','Support Ticket Reply','Support Ticket Response','<p>\r\n{$ticket_message}\r\n</p>\r\n<p>\r\n----------------------------------------------<br />\r\nTicket ID: #{$ticket_id}<br />\r\nSubject: {$ticket_subject}<br />\r\nStatus: {$ticket_status}<br />\r\nTicket URL: {$ticket_link}<br />\r\n----------------------------------------------\r\n</p>\r\n','','','','','','','',0),(9,'general','Client Signup Email','Welcome to Unlimited Internet','<p>Dear {$client_name},</p>\r\n<p>Thank you for signing up with us. Your new account has been setup and you can now login to our client area using the details below.</p>\r\n<p>Email Address: {$client_email}<br /> Password: {$client_password}</p>\r\n<p>To login, visit {$ra_url}</p>\r\n<p>{$signature}</p>','','','','','','','',0),(10,'product','Service Suspension Notification','Service Suspension Notification','<p>Dear {$client_name},</p><p>This is a notification that your service has now been suspended.  The details of this suspension are below:</p><p>Product/Service: {$service_product_name}<br />{if $service_domain}Domain: {$service_domain}<br />{/if}Amount: {$service_recurring_amount}<br />Due Date: {$service_next_due_date}<br />Suspension Reason: <strong>{$service_suspension_reason}</strong></p><p>Please contact us as soon as possible to get your service reactivated.</p><p>{$signature}</p>','','','','','','','',0),(13,'invoice','Invoice Payment Confirmation','Invoice Payment Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is a payment receipt for Invoice {$invoice_num} sent on {$invoice_date_created}</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount: {$invoice_last_payment_amount}<br />Transaction #: {$invoice_last_payment_transid}<br />Total Paid: {$invoice_amount_paid}<br />Remaining Balance: {$invoice_balance}<br />Status: {$invoice_status}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>Note: This email will serve as an official receipt for this payment.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(14,'invoice','Invoice Created','Customer Invoice','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is a notice that an invoice has been generated on {$invoice_date_created}. \r\n</p>\r\n<p>\r\nYour payment method is: {$invoice_payment_method} \r\n</p>\r\n<p>\r\nInvoice #{$invoice_num}<br />\r\nAmount Due: {$invoice_total}<br />\r\nDue Date: {$invoice_date_due} \r\n</p>\r\n<p>\r\n<strong>Invoice Items</strong> \r\n</p>\r\n<p>\r\n{$invoice_html_contents} <br />\r\n------------------------------------------------------ \r\n</p>\r\n<p>\r\nYou can login to your client area to view and pay the invoice at {$invoice_link} \r\n</p>\r\n<p>\r\n{$signature} \r\n</p>\r\n','','','','','','','',0),(15,'invoice','Invoice Payment Reminder','Invoice Payment Reminder','<p>\r\nDear {$client_name},\r\n</p>\r\n<p>\r\nThis is a billing reminder that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is due on {$invoice_date_due}.\r\n</p>\r\n<p>\r\nYour payment method is: {$invoice_payment_method}\r\n</p>\r\n<p>\r\nInvoice: {$invoice_num}<br />\r\nBalance Due: {$invoice_balance}<br />\r\nDue Date: {$invoice_date_due}\r\n</p>\r\n<p>\r\nYou can login to your client area to view and pay the invoice at {$invoice_link}\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(16,'general','Order Confirmation','Order Confirmation','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nWe have received your order and will be processing it shortly. The details of the order are below: \r\n</p>\r\n<p>\r\nOrder Number: <b>{$order_number}</b></p>\r\n<p>\r\n{$order_details} \r\n</p>\r\n<p>\r\nYou will receive an email from us shortly once your account has been setup. Please quote your order reference number if you wish to contact us about this order. \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(18,'product','Other Product/Service Welcome Email','New Product Information','<p>\r\nDear {$client_name},\r\n</p>\r\n<p>\r\nYour order for {$service_product_name} has now been activated. Please keep this message for your records.\r\n</p>\r\n<p>\r\nProduct/Service: {$service_product_name}<br />\r\nPayment Method: {$service_payment_method}<br />\r\nAmount: {$service_recurring_amount}<br />\r\nBilling Cycle: {$service_billing_cycle}<br />\r\nNext Due Date: {$service_next_due_date}\r\n</p>\r\n<p>\r\nThank you for choosing us.\r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(19,'invoice','Credit Card Payment Confirmation','Credit Card Payment Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is a payment receipt for Invoice {$invoice_num} sent on {$invoice_date_created}</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount: {$invoice_last_payment_amount}<br />Transaction #: {$invoice_last_payment_transid}<br />Total Paid: {$invoice_amount_paid}<br />Remaining Balance: {$invoice_balance}<br />Status: {$invoice_status}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>Note: This email will serve as an official receipt for this payment.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(20,'invoice','Credit Card Payment Failed','Credit Card Payment Failed','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is a notice that a recent credit card payment we attempted on the card we have registered for you failed. \r\n</p>\r\n<p>\r\nInvoice Date: {$invoice_date_created}<br />\r\nInvoice No: {$invoice_num}<br />\r\nAmount: {$invoice_total}<br />\r\nStatus: {$invoice_status} \r\n</p>\r\n<p>\r\nYou now need to login to your client area to pay the invoice manually. During the payment process you will be given the opportunity to change the card on record with us.<br />\r\n{$invoice_link} \r\n</p>\r\n<p>\r\nNote: This email will serve as an official receipt for this payment. \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(21,'invoice','Credit Card Invoice Created','Customer Invoice','<p> Dear {$client_name}, </p> <p> This is a notice that an invoice has been generated on {$invoice_date_created}. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice #{$invoice_num}<br /> Amount Due: {$invoice_total}<br /> Due Date: {$invoice_date_due} </p> <p> <strong>Invoice Items</strong> </p> <p> {$invoice_html_contents} <br /> ------------------------------------------------------ </p> <p> Payment will be taken automatically on {$invoice_date_due} from your credit card on record with us. To update or change the credit card details we hold for your account please login at {$invoice_link} and click Pay Now then following the instructions on screen. </p> <p> {$signature} </p>','','','','','','','',0),(22,'affiliate','Affiliate Monthly Referrals Report','Affiliate Monthly Referrals Report','<p>\r\nDear {$client_name}, \r\n</p>\r\n<p>\r\nThis is your monthly affiliate referrals report. You can view your referral statistics at any time by logging in to the client area. \r\n</p>\r\n<p>\r\nTotal Visitors Referred: {$affiliate_total_visits}<br />\r\nCurrent Earnings: {$affiliate_balance}<br />\r\nAmount Withdrawn: {$affiliate_withdrawn} \r\n</p>\r\n<p>\r\n<strong>Your New Signups this Month</strong> \r\n</p>\r\n<p>\r\n{$affiliate_referrals_table} \r\n</p>\r\n<p>\r\nRemember, you can refer new customers using your unique affiliate link: {$affiliate_referral_url} \r\n</p>\r\n<p>\r\n{$signature}\r\n</p>\r\n','','','','','','','',0),(23,'support','Support Ticket Opened by Admin','{$ticket_subject}','{$ticket_message}','','','','','','','',0),(24,'invoice','First Invoice Overdue Notice','First Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is a billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(26,'invoice','Second Invoice Overdue Notice','Second Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is the second billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. </p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(27,'invoice','Third Invoice Overdue Notice','Third Invoice Overdue Notice','<p> Dear {$client_name}, </p> <p> This is the third and final billing notice that your invoice no. {$invoice_num} which was generated on {$invoice_date_created} is now overdue. Failure to make payment will result in account suspension.</p> <p> Your payment method is: {$invoice_payment_method} </p> <p> Invoice: {$invoice_num}<br /> Balance Due: {$invoice_balance}<br /> Due Date: {$invoice_date_due} </p> <p> You can login to your client area to view and pay the invoice at {$invoice_link} </p> <p> Your login details are as follows: </p> <p> Email Address: {$client_email}<br /> Password: {$client_password} </p> <p> {$signature} </p>','','','','','','','',0),(31,'support','Bounce Message','Support Ticket Not Opened','<p>{$client_name},</p><p>Your email to our support system could not be accepted because it was not recognized as coming from an email address belonging to one of our customers.  If you need assistance, please email from the address you registered with us that you use to login to our client area.</p><p>{$signature}</p>','','','','','','','',0),(32,'general','Credit Card Expiring Soon','Credit Card Expiring Soon','<p>Dear {$client_name}, </p><p>This is a notice to inform you that your {$client_cc_type} credit card ending with {$client_cc_number} will be expiring next month on {$client_cc_expiry}. Please login to update your credit card information as soon as possible and prevent any interuptions in service at {$whmcs_url}<br /><br />If you have any questions regarding your account, please open a support ticket from the client area.</p><p>{$signature}</p>','','','','','','','',0),(33,'support','Support Ticket Auto Close Notification','Support Ticket Resolved','<p>{$client_name},</p><p>This is a notification to let you know that we are changing the status of your ticket #{$ticket_id} to Closed as we have not received a response from you in over {$ticket_auto_close_time} hours.</p><p>Subject: {$ticket_subject}<br>Department: {$ticket_department}<br>Priority: {$ticket_priority}<br>Status: {$ticket_status}</p><p>If you have any further questions then please just reply to re-open the ticket.</p><p>{$signature}</p>','','','','','','','',0),(34,'invoice','Credit Card Payment Due','Invoice Payment Reminder','<p>Dear {$client_name},</p><p>This is a notice to remind you that you have an invoice due on {$invoice_date_due}. We tried to bill you automatically but were unable to because we don\'t have your credit card details on file.</p><p>Invoice Date: {$invoice_date_created}<br>Invoice #{$invoice_num}<br>Amount Due: {$invoice_total}<br>Due Date: {$invoice_date_due}</p><p>Please login to our client area at the link below to submit your card details or make payment using a different method.</p><p>{$invoice_link}</p><p>{$signature}</p>','','','','','','','',0),(35,'product','Cancellation Request Confirmation','Cancellation Request Confirmation','<p>Dear {$client_name},</p><p>This email is to confirm that we have received your cancellation request for the service listed below.</p><p>Product/Service: {$service_product_name}<br />Domain: {$service_domain}</p><p>{if $service_cancellation_type==\"Immediate\"}The service will be terminated within the next 24 hours.{else}The service will be cancelled at the end of your current billing period on {$service_next_due_date}.{/if}</p><p>Thank you for using {$company_name} and we hope to see you again in the future.</p><p>{$signature}</p>','','','','','','','',0),(37,'general','Password Reset Validation','Your login details for {$company_name}','<p>Dear {$client_name},</p><p>Recently a request was submitted to reset your password for our client area. If you did not request this, please ignore this email. It will expire and become useless in 2 hours time.</p><p>To reset your password, please visit the url below:<br /><a href=\"{$pw_reset_url}\">{$pw_reset_url}</a></p><p>When you visit the link above, your password will be reset, and the new password will be emailed to you.</p><p>{$signature}</p>','','','','','','','',0),(38,'general','Automated Password Reset','Your new password for {$company_name}','<p>Dear {$client_name},</p><p>As you requested, your password for our client area has now been reset.  Your new login details are as follows:</p><p>{$whmcs_link}<br />Email: {$client_email}<br />Password: {$client_password}</p><p>To change your password to something more memorable, after logging in go to My Details > Change Password.</p><p>{$signature}</p>','','','','','','','',0),(39,'admin','Automatic Setup Failed','WHMCS Automatic Setup Failed','<p>An order has received its first payment but the automatic provisioning has failed and requires you to manually check & resolve.</p>\r\n<p>Client ID: {$client_id}<br />{if $service_id}Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}{else}Domain ID: {$domain_id}<br />Registration Type: {$domain_type}<br />Domain: {$domain_name}{/if}<br />Error: {$error_msg}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(40,'admin','Automatic Setup Successful','WHMCS Automatic Setup Successful','<p>An order has received its first payment and the product/service has been automatically provisioned successfully.</p>\r\n<p>Client ID: {$client_id}<br />{if $service_id}Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}{else}Domain ID: {$domain_id}<br />Registration Type: {$domain_type}<br />Domain: {$domain_name}{/if}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(43,'admin','New Order Notification','WHMCS New Order Notification','<p><strong>Order Information</strong></p>\r\n<p>Order ID: {$order_id}<br />\r\nOrder Number: {$order_number}<br />\r\nDate/Time: {$order_date}<br />\r\nInvoice Number: {$invoice_id}<br />\r\nPayment Method: {$order_payment_method}</p>\r\n<p><strong>Customer Information</strong></p>\r\n<p>Customer ID: {$client_id}<br />\r\nName: {$client_first_name} {$client_last_name}<br />\r\nEmail: {$client_email}<br />\r\nCompany: {$client_company_name}<br />\r\nAddress 1: {$client_address1}<br />\r\nAddress 2: {$client_address2}<br />\r\nCity: {$client_city}<br />\r\nState: {$client_state}<br />\r\nPostcode: {$client_postcode}<br />\r\nCountry: {$client_country}<br />\r\nPhone Number: {$client_phonenumber}</p>\r\n<p><strong>Order Items</strong></p>\r\n<p>{$order_items}</p>\r\n{if $order_notes}<p><strong>Order Notes</strong></p>\r\n<p>{$order_notes}</p>{/if}\r\n<p><strong>ISP Information</strong></p>\r\n<p>IP: {$client_ip}<br />\r\nHost: {$client_hostname}</p><p><a href=\"{$whmcs_admin_url}orders.php?action=view&id={$order_id}\">{$whmcs_admin_url}orders.php?action=view&id={$order_id}</a></p>','','','','','','','',0),(44,'admin','Service Unsuspension Failed','WHMCS Service Unsuspension Failed','<p>This product/service has received its next payment but the automatic reactivation has failed.</p>\r\n<p>Client ID: {$client_id}<br />Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}<br />Error: {$error_msg}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(45,'admin','Service Unsuspension Successful','WHMCS Service Unsuspension Successful','<p>This product/service has received its next payment and has been reactivated successfully.</p>\r\n<p>Client ID: {$client_id}<br />Service ID: {$service_id}<br />Product/Service: {$service_product}<br />Domain: {$service_domain}</p>\r\n<p>{$whmcs_admin_link}</p>','','','','','','','',0),(46,'admin','Support Ticket Created','[Ticket ID: {$ticket_tid}] New Support Ticket Opened','<p>A new support ticket has been opened.</p>\r\n<p>Client: {$client_name}{if $client_id} #{$client_id}{/if}<br />Department: {$ticket_department}<br />Subject: {$ticket_subject}<br />Priority: {$ticket_priority}</p>\r\n<p>---<br />{$ticket_message}<br />---</p>\r\n<p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p>\r\n<p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(47,'admin','Support Ticket Response','[Ticket ID: {$ticket_tid}] New Support Ticket Response','<p>A new support ticket response has been made.</p>\r\n<p>Client: {$client_name}{if $client_id} #{$client_id}{/if} <br />Department: {$ticket_department} <br />Subject: {$ticket_subject} <br />Priority: {$ticket_priority}</p>\r\n<p>--- <br />{$ticket_message} <br />---</p>\r\n<p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p>\r\n<p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(48,'admin','Escalation Rule Notification','[Ticket ID: {$tickettid}] Escalation Rule Notification','<p>The escalation rule {$rule_name} has just been applied to this ticket.</p><p>Client: {$client_name}{if $client_id} #{$client_id}{/if} <br />Department: {$ticket_department} <br />Subject: {$ticket_subject} <br />Priority: {$ticket_priority}</p><p>---<br />{$ticket_message}<br />---</p><p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(49,'admin','Support Ticket Department Reassigned','[Ticket ID: {$ticket_tid}] Support Ticket Department Reassigned','<p>The department this ticket is assigned to has been changed to a department you are a member of.</p><p>Client: {$client_name}{if $client_id} #{$client_id}{/if}<br />Department: {$ticket_department}<br />Subject: {$ticket_subject}<br />Priority: {$ticket_priority}</p><p>---<br />{$ticket_message}<br />---</p><p>You can respond to this ticket by simply replying to this email or through the admin area at the url below.</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(50,'invoice','Invoice Refund Confirmation','Invoice Refund Confirmation','<p>Dear {$client_name},</p>\r\n<p>This is confirmation that a {if $invoice_status eq \"Refunded\"}full{else}partial{/if} refund has been processed for Invoice #{$invoice_num}</p>\r\n<p>The refund has been {if $invoice_refund_type eq \"credit\"}credited to your account balance with us{else}returned via the payment method you originally paid with{/if}.</p>\r\n<p>{$invoice_html_contents}</p>\r\n<p>Amount Refunded: {$invoice_last_payment_amount}{if $invoice_last_payment_transid}<br />Transaction #: {$invoice_last_payment_transid}{/if}</p>\r\n<p>You may review your invoice history at any time by logging in to your client area.</p>\r\n<p>{$signature}</p>','','','','','','','',0),(51,'admin','New Cancellation Request','New Cancellation Request','<p>A new cancellation request has been submitted.</p><p>Client ID: {$client_id}<br>Client Name: {$clientname}<br>Service ID: {$service_id}<br>Product Name: {$product_name}<br>Cancellation Type: {$service_cancellation_type}<br>Cancellation Reason: {$service_cancellation_reason}</p><p>{$whmcs_admin_link}</p>','','','','','','','',0),(52,'admin','Support Ticket Flagged','New Support Ticket Flagged to You','<p>A new support ticket has been flagged to you.</p><p>Ticket #: {$ticket_tid}<br>Client Name: {$client_name} (ID {$client_id})<br>Department: {$ticket_department}<br>Subject: {$ticket_subject}<br>Priority: {$ticket_priority}</p><p>----------------------<br />{$ticket_message}<br />----------------------</p><p><a href=\"{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}\">{$whmcs_admin_url}supporttickets.php?action=viewticket&id={$ticket_id}</a></p>','','','','','','','',0),(56,'general','Password Reset Confirmation','Your password has been reset for {$company_name}','<p>Dear {$client_name},</p><p>As you requested, your password for our client area has now been reset. </p><p>If it was not at your request, then please contact support immediately.</p><p>{$signature}</p>','','','','','','','',0),(57,'support','Support Ticket Feedback Request','Your Feedback is Requested for Ticket #{$ticket_id}','<p>This support request has been marked as completed.</p><p>We would really appreciate it if you would just take a moment to let us know about the quality of your experience.</p><p><a href=\"{$ticket_url}&feedback=1\">{$ticket_url}&feedback=1</a></p><p>Your feedback is very important to us.</p><p>Thank you for your business.</p><p>{$signature}</p>','','','','','','','',0),(61,'','Mass Mail Template','','dfadadasdas','','','','','','','',0);
/*!40000 ALTER TABLE `tblemailtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgatewaylog`
--

DROP TABLE IF EXISTS `tblgatewaylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgatewaylog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gateway` varchar(64) DEFAULT NULL,
  `data` text NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway` (`gateway`),
  CONSTRAINT `tblgatewaylog_ibfk_1` FOREIGN KEY (`gateway`) REFERENCES `tblpaymentgatewaynames` (`gateway`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgatewaylog`
--

LOCK TABLES `tblgatewaylog` WRITE;
/*!40000 ALTER TABLE `tblgatewaylog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgatewaylog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgrouptogroup`
--

DROP TABLE IF EXISTS `tblgrouptogroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgrouptogroup` (
  `parent_group_id` int(11) DEFAULT NULL,
  `children_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgrouptogroup`
--

LOCK TABLES `tblgrouptogroup` WRITE;
/*!40000 ALTER TABLE `tblgrouptogroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblgrouptogroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblinvoiceitems`
--

DROP TABLE IF EXISTS `tblinvoiceitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblinvoiceitems` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(10) DEFAULT NULL,
  `userid` int(10) NOT NULL,
  `relid` int(10) DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `taxed` int(1) NOT NULL,
  `duedate` date DEFAULT NULL,
  `paymentmethod` text NOT NULL,
  `notes` text NOT NULL,
  `type` enum('AddFunds','Service','Invoice','Item','LateFee','Project','Upgrade','Addon','Promo') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoiceid` (`invoiceid`),
  KEY `userid` (`userid`),
  KEY `relid` (`relid`),
  CONSTRAINT `tblinvoiceitems_ibfk_4` FOREIGN KEY (`invoiceid`) REFERENCES `tblinvoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblinvoiceitems_ibfk_5` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblinvoiceitems`
--

LOCK TABLES `tblinvoiceitems` WRITE;
/*!40000 ALTER TABLE `tblinvoiceitems` DISABLE KEYS */;
INSERT INTO `tblinvoiceitems` VALUES (76,152066,8021,238,'UFB 30/10 One Off Fee',49.00,0,'2017-07-12','banktransfer','','Service'),(77,152066,8021,238,'UFB 30/10 - UFB 30/10 (12/07/2017 - 11/08/2017)',65.00,0,'2017-07-12','banktransfer','','Item'),(78,152068,8022,240,'UFB 30/10 One Off Fee',49.00,0,'2017-07-13','banktransfer','','Service'),(79,152068,8022,240,'UFB 30/10 - UFB 30/10 (13/07/2017 - 12/08/2017)',65.00,0,'2017-07-13','banktransfer','','Item'),(89,152079,8019,241,'VDSL',114.00,0,'2017-08-16','banktransfer','',''),(90,152080,8019,252,'',0.00,0,'2017-08-16','banktransfer','',''),(91,152081,8024,253,'',0.00,0,'2017-08-21','banktransfer','','');
/*!40000 ALTER TABLE `tblinvoiceitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblinvoices`
--

DROP TABLE IF EXISTS `tblinvoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblinvoices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `invoicenum` text NOT NULL,
  `date` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `datepaid` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `subtotal` decimal(10,2) NOT NULL,
  `credit` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `tax2` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `taxrate` decimal(10,2) NOT NULL,
  `taxrate2` decimal(10,2) NOT NULL,
  `status` enum('Draft','Unpaid','Overdue','Paid','Cancelled','Refunded','Collections') DEFAULT NULL,
  `paymentmethod` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`),
  CONSTRAINT `tblinvoices_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblinvoices`
--

LOCK TABLES `tblinvoices` WRITE;
/*!40000 ALTER TABLE `tblinvoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblinvoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowledgebase`
--

DROP TABLE IF EXISTS `tblknowledgebase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowledgebase` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `article` text NOT NULL,
  `views` int(10) NOT NULL DEFAULT 0,
  `useful` int(10) NOT NULL DEFAULT 0,
  `votes` int(10) NOT NULL DEFAULT 0,
  `private` text NOT NULL,
  `order` int(3) NOT NULL,
  `parentid` int(10) NOT NULL,
  `language` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowledgebase`
--

LOCK TABLES `tblknowledgebase` WRITE;
/*!40000 ALTER TABLE `tblknowledgebase` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledgebase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowledgebasecats`
--

DROP TABLE IF EXISTS `tblknowledgebasecats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowledgebasecats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parentid` int(10) DEFAULT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `hidden` text NOT NULL,
  `catid` int(10) NOT NULL,
  `language` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `name` (`name`(64)),
  CONSTRAINT `tblknowledgebasecats_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `tblknowledgebasecats` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowledgebasecats`
--

LOCK TABLES `tblknowledgebasecats` WRITE;
/*!40000 ALTER TABLE `tblknowledgebasecats` DISABLE KEYS */;
INSERT INTO `tblknowledgebasecats` VALUES (11,NULL,'test','test','0',0,''),(12,11,'test child','test child','',0,'');
/*!40000 ALTER TABLE `tblknowledgebasecats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblknowledgebaselinks`
--

DROP TABLE IF EXISTS `tblknowledgebaselinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblknowledgebaselinks` (
  `categoryid` int(10) NOT NULL,
  `articleid` int(10) NOT NULL,
  KEY `articleid` (`articleid`),
  KEY `categoryid` (`categoryid`),
  CONSTRAINT `tblknowledgebaselinks_ibfk_1` FOREIGN KEY (`articleid`) REFERENCES `tblknowledgebase` (`id`),
  CONSTRAINT `tblknowledgebaselinks_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `tblknowledgebasecats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblknowledgebaselinks`
--

LOCK TABLES `tblknowledgebaselinks` WRITE;
/*!40000 ALTER TABLE `tblknowledgebaselinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblknowledgebaselinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmodulelog`
--

DROP TABLE IF EXISTS `tblmodulelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmodulelog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `module` varchar(64) DEFAULT NULL,
  `action` text NOT NULL,
  `request` text NOT NULL,
  `response` text NOT NULL,
  `arrdata` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  CONSTRAINT `tblmodulelog_ibfk_1` FOREIGN KEY (`module`) REFERENCES `tbladdonmodules` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmodulelog`
--

LOCK TABLES `tblmodulelog` WRITE;
/*!40000 ALTER TABLE `tblmodulelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmodulelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnetworkissues`
--

DROP TABLE IF EXISTS `tblnetworkissues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnetworkissues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `type` enum('Server','System','Other') NOT NULL,
  `affecting` varchar(100) DEFAULT NULL,
  `server` int(10) unsigned DEFAULT NULL,
  `priority` enum('Critical','Low','Medium','High') NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime DEFAULT NULL,
  `status` enum('Reported','Investigating','In Progress','Outage','Scheduled','Resolved') NOT NULL,
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnetworkissues`
--

LOCK TABLES `tblnetworkissues` WRITE;
/*!40000 ALTER TABLE `tblnetworkissues` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnetworkissues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnotes`
--

DROP TABLE IF EXISTS `tblnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('account','order','client') DEFAULT NULL,
  `rel_id` int(10) NOT NULL,
  `adminid` int(10) DEFAULT NULL,
  `assignto` int(11) NOT NULL,
  `duedate` date NOT NULL,
  `donetime` date NOT NULL,
  `note` text NOT NULL,
  `sticky` int(1) NOT NULL DEFAULT 1,
  `flag` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `userid` (`rel_id`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tblnotes_ibfk_2` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnotes`
--

LOCK TABLES `tblnotes` WRITE;
/*!40000 ALTER TABLE `tblnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblorders`
--

DROP TABLE IF EXISTS `tblorders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblorders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ordernum` bigint(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `contactid` int(10) DEFAULT NULL,
  `date` datetime NOT NULL,
  `nameservers` text DEFAULT NULL,
  `promocode` text DEFAULT NULL,
  `promotype` text DEFAULT NULL,
  `promovalue` text DEFAULT NULL,
  `orderdata` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `paymentmethod` text NOT NULL,
  `invoiceid` int(10) DEFAULT NULL COMMENT 'First invoice only',
  `status` varchar(64) NOT NULL,
  `ipaddress` text NOT NULL,
  `fraudmodule` text DEFAULT NULL,
  `fraudoutput` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordernum` (`ordernum`),
  KEY `userid` (`userid`),
  KEY `contactid` (`contactid`),
  KEY `date` (`date`),
  KEY `status` (`status`),
  KEY `invoiceid` (`invoiceid`),
  CONSTRAINT `tblorders_ibfk_2` FOREIGN KEY (`status`) REFERENCES `tblorderstatuses` (`title`),
  CONSTRAINT `tblorders_ibfk_3` FOREIGN KEY (`contactid`) REFERENCES `tblcontacts` (`id`),
  CONSTRAINT `tblorders_ibfk_4` FOREIGN KEY (`invoiceid`) REFERENCES `tblinvoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblorders`
--

LOCK TABLES `tblorders` WRITE;
/*!40000 ALTER TABLE `tblorders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblorders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblorderstatuses`
--

DROP TABLE IF EXISTS `tblorderstatuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblorderstatuses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `color` text NOT NULL,
  `showpending` int(1) NOT NULL,
  `showactive` int(1) NOT NULL,
  `showcancelled` int(1) NOT NULL,
  `sortorder` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblorderstatuses`
--

LOCK TABLES `tblorderstatuses` WRITE;
/*!40000 ALTER TABLE `tblorderstatuses` DISABLE KEYS */;
INSERT INTO `tblorderstatuses` VALUES (1,'Pending','#cc0000',1,0,0,10),(2,'Active','#779500',0,1,0,20),(3,'Cancelled','#888888',0,0,1,30),(4,'Fraud','#000000',0,0,0,40),(5,'Draft','#f2d342',0,0,0,50);
/*!40000 ALTER TABLE `tblorderstatuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpaymentgatewaynames`
--

DROP TABLE IF EXISTS `tblpaymentgatewaynames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpaymentgatewaynames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_setting` (`gateway`(32)),
  KEY `gateway` (`gateway`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpaymentgatewaynames`
--

LOCK TABLES `tblpaymentgatewaynames` WRITE;
/*!40000 ALTER TABLE `tblpaymentgatewaynames` DISABLE KEYS */;
INSERT INTO `tblpaymentgatewaynames` VALUES (4,'banktransfer'),(2,'mailin'),(3,'offlinecc'),(1,'paypal'),(5,'paystation');
/*!40000 ALTER TABLE `tblpaymentgatewaynames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpaymentgateways`
--

DROP TABLE IF EXISTS `tblpaymentgateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpaymentgateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway` varchar(64) DEFAULT NULL,
  `setting` text NOT NULL,
  `value` text NOT NULL,
  `order` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gateway_setting` (`gateway`(32),`setting`(32)),
  KEY `setting_value` (`setting`(32),`value`(32)),
  KEY `gateway` (`gateway`),
  CONSTRAINT `tblpaymentgateways_ibfk_1` FOREIGN KEY (`gateway`) REFERENCES `tblpaymentgatewaynames` (`gateway`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpaymentgateways`
--

LOCK TABLES `tblpaymentgateways` WRITE;
/*!40000 ALTER TABLE `tblpaymentgateways` DISABLE KEYS */;
INSERT INTO `tblpaymentgateways` VALUES (20,'banktransfer','name','Bank Transfer',1),(21,'banktransfer','type','Invoices',0),(22,'banktransfer','visible','on',0),(32,'mailin','name','Mail In Payment',2),(33,'mailin','type','Invoices',0),(34,'mailin','visible','on',0),(56,'paystation','name','Credit Card (Visa / Master Card)',3),(57,'paystation','type','Invoices',0),(58,'paystation','visible','on',0),(59,'paystation','paystationid','',0),(60,'paystation','gatewayid','',0),(61,'paystation','hashkey','',0),(62,'paystation','url','',0),(63,'paystation','testmode','',0),(64,'paystation','convertto','',0),(65,'mailin','instructions','Bank Name: BNZ\r\nPayee Name: UNLIMITED INTERNET\r\nAccount Number:',0),(66,'mailin','convertto','',0);
/*!40000 ALTER TABLE `tblpaymentgateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpricing`
--

DROP TABLE IF EXISTS `tblpricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpricing` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('product','addon','configoptions','domainregister','domaintransfer','domainrenew','domainaddons') NOT NULL,
  `currency` int(10) NOT NULL,
  `relid` int(10) NOT NULL,
  `msetupfee` decimal(10,2) NOT NULL,
  `qsetupfee` decimal(10,2) NOT NULL,
  `ssetupfee` decimal(10,2) NOT NULL,
  `asetupfee` decimal(10,2) NOT NULL,
  `bsetupfee` decimal(10,2) NOT NULL,
  `tsetupfee` decimal(10,2) NOT NULL,
  `monthly` decimal(10,2) NOT NULL,
  `quarterly` decimal(10,2) NOT NULL,
  `semiannually` decimal(10,2) NOT NULL,
  `annually` decimal(10,2) NOT NULL,
  `biennially` decimal(10,2) NOT NULL,
  `triennially` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpricing`
--

LOCK TABLES `tblpricing` WRITE;
/*!40000 ALTER TABLE `tblpricing` DISABLE KEYS */;
INSERT INTO `tblpricing` VALUES (1,'domainaddons',1,0,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(2,'product',1,1,10.00,-1.00,-1.00,-1.00,-1.00,-1.00,75.00,-1.00,-1.00,-1.00,-1.00,-1.00),(3,'product',1,2,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(4,'product',1,3,49.00,0.00,0.00,0.00,0.00,0.00,69.00,0.00,0.00,0.00,0.00,0.00),(5,'product',1,4,70.00,0.00,0.00,0.00,0.00,0.00,49.00,0.00,0.00,0.00,0.00,0.00),(6,'product',1,5,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(7,'configoptions',1,1,45.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00,-1.00),(8,'addon',1,1,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(9,'addon',1,2,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(10,'product',1,6,49.00,0.00,0.00,0.00,0.00,0.00,75.00,0.00,0.00,0.00,0.00,0.00),(11,'product',1,7,0.00,0.00,0.00,0.00,0.00,0.00,45.00,0.00,0.00,0.00,0.00,0.00),(12,'product',1,8,0.00,0.00,0.00,0.00,0.00,0.00,189.00,0.00,0.00,0.00,0.00,0.00),(13,'product',1,0,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(14,'product',1,9,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(15,'product',1,10,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(16,'product',1,11,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(17,'product',1,12,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(18,'product',1,38,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(19,'product',1,14,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(20,'product',1,25,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(21,'product',1,46,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(22,'product',1,34,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(23,'product',1,36,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(24,'product',1,37,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(25,'product',1,47,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(26,'product',1,48,49.00,0.00,0.00,0.00,0.00,0.00,69.00,0.00,0.00,0.00,0.00,0.00),(27,'product',1,49,0.00,0.00,0.00,0.00,0.00,0.00,159.00,0.00,0.00,0.00,0.00,0.00),(28,'product',1,50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),(29,'product',1,51,49.00,0.00,0.00,0.00,0.00,0.00,65.00,0.00,0.00,0.00,0.00,0.00),(30,'product',1,52,49.00,0.00,0.00,0.00,0.00,0.00,65.00,0.00,0.00,0.00,0.00,0.00),(31,'product',1,53,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `tblpricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpromotions`
--

DROP TABLE IF EXISTS `tblpromotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpromotions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `type` text NOT NULL,
  `recurring` int(1) DEFAULT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cycles` text NOT NULL,
  `appliesto` text NOT NULL,
  `requires` text NOT NULL,
  `requiresexisting` int(1) NOT NULL,
  `startdate` date NOT NULL,
  `expirationdate` date DEFAULT NULL,
  `maxuses` int(10) NOT NULL DEFAULT 0,
  `uses` int(10) NOT NULL DEFAULT 0,
  `lifetimepromo` int(1) NOT NULL,
  `applyonce` int(1) NOT NULL,
  `newsignups` int(1) NOT NULL,
  `existingclient` int(11) NOT NULL,
  `onceperclient` int(11) NOT NULL,
  `recurfor` int(3) NOT NULL,
  `upgrades` int(1) NOT NULL,
  `upgradeconfig` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`(32))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpromotions`
--

LOCK TABLES `tblpromotions` WRITE;
/*!40000 ALTER TABLE `tblpromotions` DISABLE KEYS */;
INSERT INTO `tblpromotions` VALUES (1,'test','Percentage',0,10.00,'','','',0,'0000-00-00','0000-00-00',1,2,0,0,0,0,0,0,0,'','Order Process One Off Custom Promo');
/*!40000 ALTER TABLE `tblpromotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservergroups`
--

DROP TABLE IF EXISTS `tblservergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservergroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `filltype` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservergroups`
--

LOCK TABLES `tblservergroups` WRITE;
/*!40000 ALTER TABLE `tblservergroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservergroupsrel`
--

DROP TABLE IF EXISTS `tblservergroupsrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservergroupsrel` (
  `groupid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  KEY `serverid` (`serverid`),
  KEY `groupid` (`groupid`),
  CONSTRAINT `tblservergroupsrel_ibfk_1` FOREIGN KEY (`serverid`) REFERENCES `tblservers` (`id`),
  CONSTRAINT `tblservergroupsrel_ibfk_2` FOREIGN KEY (`groupid`) REFERENCES `tblservergroups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservergroupsrel`
--

LOCK TABLES `tblservergroupsrel` WRITE;
/*!40000 ALTER TABLE `tblservergroupsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservergroupsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservers`
--

DROP TABLE IF EXISTS `tblservers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `ipaddress` text NOT NULL,
  `assignedips` text NOT NULL,
  `hostname` text NOT NULL,
  `monthlycost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `noc` text NOT NULL,
  `statusaddress` text NOT NULL,
  `nameserver1` text NOT NULL,
  `nameserver1ip` text NOT NULL,
  `nameserver2` text NOT NULL,
  `nameserver2ip` text NOT NULL,
  `maxaccounts` int(10) NOT NULL DEFAULT 0,
  `type` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `accesshash` text NOT NULL,
  `secure` text NOT NULL,
  `active` int(1) NOT NULL,
  `disabled` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservers`
--

LOCK TABLES `tblservers` WRITE;
/*!40000 ALTER TABLE `tblservers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblservers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblserviceaddons`
--

DROP TABLE IF EXISTS `tblserviceaddons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceaddons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `orderid` int(10) NOT NULL,
  `serviceid` int(10) NOT NULL,
  `addonid` int(10) NOT NULL,
  `name` text NOT NULL,
  `setupfee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `recurring` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billingcycle` text NOT NULL,
  `tax` text NOT NULL,
  `status` enum('Pending','Active','Suspended','Terminated','Cancelled','Fraud','Draft') NOT NULL DEFAULT 'Pending',
  `regdate` date NOT NULL DEFAULT '0000-00-00',
  `nextduedate` date DEFAULT NULL,
  `nextinvoicedate` date NOT NULL,
  `paymentmethod` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `serviceid` (`serviceid`),
  KEY `name` (`name`(32)),
  KEY `status` (`status`),
  KEY `addonid` (`addonid`),
  CONSTRAINT `tblserviceaddons_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `tblorders` (`id`),
  CONSTRAINT `tblserviceaddons_ibfk_2` FOREIGN KEY (`serviceid`) REFERENCES `tblcustomerservices` (`id`),
  CONSTRAINT `tblserviceaddons_ibfk_3` FOREIGN KEY (`addonid`) REFERENCES `tbladdons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblserviceaddons`
--

LOCK TABLES `tblserviceaddons` WRITE;
/*!40000 ALTER TABLE `tblserviceaddons` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceaddons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblserviceconfiggroups`
--

DROP TABLE IF EXISTS `tblserviceconfiggroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceconfiggroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tblserviceconfiggroups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblserviceconfiggroups`
--

LOCK TABLES `tblserviceconfiggroups` WRITE;
/*!40000 ALTER TABLE `tblserviceconfiggroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfiggroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblserviceconfiglinks`
--

DROP TABLE IF EXISTS `tblserviceconfiglinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceconfiglinks` (
  `gid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  KEY `gid` (`gid`),
  KEY `pid` (`pid`),
  CONSTRAINT `tblserviceconfiglinks_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `tblserviceconfiggroups` (`id`),
  CONSTRAINT `tblserviceconfiglinks_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `tblservices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblserviceconfiglinks`
--

LOCK TABLES `tblserviceconfiglinks` WRITE;
/*!40000 ALTER TABLE `tblserviceconfiglinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfiglinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblserviceconfigoptions`
--

DROP TABLE IF EXISTS `tblserviceconfigoptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceconfigoptions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL DEFAULT 0,
  `optionname` text NOT NULL,
  `optiontype` text NOT NULL,
  `qtyminimum` int(10) NOT NULL,
  `qtymaximum` int(10) NOT NULL,
  `order` int(1) NOT NULL DEFAULT 0,
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `productid` (`gid`),
  CONSTRAINT `tblserviceconfigoptions_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `tblserviceconfiggroups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblserviceconfigoptions`
--

LOCK TABLES `tblserviceconfigoptions` WRITE;
/*!40000 ALTER TABLE `tblserviceconfigoptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfigoptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblserviceconfigoptionssub`
--

DROP TABLE IF EXISTS `tblserviceconfigoptionssub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceconfigoptionssub` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `configid` int(10) NOT NULL,
  `optionname` text NOT NULL,
  `sortorder` int(10) NOT NULL DEFAULT 0,
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `configid` (`configid`),
  CONSTRAINT `tblserviceconfigoptionssub_ibfk_1` FOREIGN KEY (`configid`) REFERENCES `tblserviceconfigoptions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblserviceconfigoptionssub`
--

LOCK TABLES `tblserviceconfigoptionssub` WRITE;
/*!40000 ALTER TABLE `tblserviceconfigoptionssub` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblserviceconfigoptionssub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservicegroups`
--

DROP TABLE IF EXISTS `tblservicegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservicegroups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` enum('service','product') DEFAULT 'service',
  `orderfrmtpl` text NOT NULL,
  `disabledgateways` text NOT NULL,
  `hidden` text NOT NULL,
  `order` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservicegroups`
--

LOCK TABLES `tblservicegroups` WRITE;
/*!40000 ALTER TABLE `tblservicegroups` DISABLE KEYS */;
INSERT INTO `tblservicegroups` VALUES (9,'Service','service','service','','',1),(10,'Product','product','product','','',2);
/*!40000 ALTER TABLE `tblservicegroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservices`
--

DROP TABLE IF EXISTS `tblservices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `gid` int(10) NOT NULL,
  `individual` int(11) NOT NULL,
  `contract` int(11) NOT NULL,
  `etf` double NOT NULL,
  `term` int(11) NOT NULL,
  `name` text NOT NULL,
  `stock` int(11) NOT NULL,
  `stockalert` int(11) NOT NULL,
  `cost` float NOT NULL,
  `description` text NOT NULL,
  `revenuecode` varchar(10) DEFAULT NULL,
  `hidden` text NOT NULL,
  `welcomeemail` int(10) DEFAULT NULL,
  `proratabilling` text NOT NULL,
  `proratadate` int(2) NOT NULL,
  `proratachargenextmonth` int(2) NOT NULL,
  `paytype` text NOT NULL,
  `autosetup` text NOT NULL,
  `servertype` text NOT NULL,
  `servergroup` int(10) NOT NULL,
  `recurringcycles` int(2) NOT NULL,
  `autoterminatedays` int(4) NOT NULL,
  `autoterminateemail` text NOT NULL,
  `configoptionsupgrade` text NOT NULL,
  `upgradechargefullcycle` int(1) NOT NULL,
  `tax` int(1) NOT NULL,
  `affiliateonetime` varchar(20) NOT NULL,
  `affiliatepaytype` text NOT NULL,
  `affiliatepayamount` decimal(10,2) NOT NULL,
  `order` int(1) NOT NULL,
  `retired` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `name` (`name`(64)),
  KEY `welcomeemail` (`welcomeemail`),
  CONSTRAINT `tblservices_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `tblservicegroups` (`id`),
  CONSTRAINT `tblservices_ibfk_2` FOREIGN KEY (`welcomeemail`) REFERENCES `tblemailtemplates` (`id`),
  CONSTRAINT `tblservices_ibfk_3` FOREIGN KEY (`gid`) REFERENCES `tblservicegroups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservices`
--

LOCK TABLES `tblservices` WRITE;
/*!40000 ALTER TABLE `tblservices` DISABLE KEYS */;
INSERT INTO `tblservices` VALUES (48,'residential',9,0,0,0,0,'ADSL ',0,0,0,'drttre','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,1,'','',0.00,0,0),(49,'other',10,1,0,0,0,'ADSL / VDSL / UFB / VOIP / Gigabit Router',100,10,100,'','','',NULL,'',0,0,'onetime','','',0,0,0,'0','',0,1,'','',0.00,0,0),(50,'other',10,0,0,0,0,'Router 1',0,0,0,'',NULL,'',NULL,'',0,0,'free','','',0,0,0,'','',0,0,'','',0.00,0,0),(51,'residential',9,0,0,0,0,'VDSL',0,0,0,'','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,0,'','',0.00,0,0),(52,'residential',9,0,0,0,0,'UFB 30/10',0,0,0,'','','',10,'',0,0,'recurring','','',0,0,0,'0','',0,0,'','',0.00,0,0);
/*!40000 ALTER TABLE `tblservices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblservicetoservice`
--

DROP TABLE IF EXISTS `tblservicetoservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblservicetoservice` (
  `parent_id` int(11) DEFAULT NULL,
  `children_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblservicetoservice`
--

LOCK TABLES `tblservicetoservice` WRITE;
/*!40000 ALTER TABLE `tblservicetoservice` DISABLE KEYS */;
INSERT INTO `tblservicetoservice` VALUES (48,49),(48,50);
/*!40000 ALTER TABLE `tblservicetoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsmstemplate`
--

DROP TABLE IF EXISTS `tblsmstemplate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsmstemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `smsgrp` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsmstemplate`
--

LOCK TABLES `tblsmstemplate` WRITE;
/*!40000 ALTER TABLE `tblsmstemplate` DISABLE KEYS */;
INSERT INTO `tblsmstemplate` VALUES (4,'test','account','test 3 '),(5,'invoice txt','invoice','xdsadas  dsad adsa dasd asdas dsd ');
/*!40000 ALTER TABLE `tblsmstemplate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltax`
--

DROP TABLE IF EXISTS `tbltax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltax` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` int(1) NOT NULL,
  `name` text NOT NULL,
  `state` text NOT NULL,
  `country` text NOT NULL,
  `taxrate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_country` (`state`(32),`country`(2))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltax`
--

LOCK TABLES `tbltax` WRITE;
/*!40000 ALTER TABLE `tbltax` DISABLE KEYS */;
INSERT INTO `tbltax` VALUES (1,1,'GST','','New Zealand',15.00),(2,1,'World','','',0.00);
/*!40000 ALTER TABLE `tbltax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketbreaklines`
--

DROP TABLE IF EXISTS `tblticketbreaklines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketbreaklines` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `breakline` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketbreaklines`
--

LOCK TABLES `tblticketbreaklines` WRITE;
/*!40000 ALTER TABLE `tblticketbreaklines` DISABLE KEYS */;
INSERT INTO `tblticketbreaklines` VALUES (1,'> -----Original Message-----'),(2,'----- Original Message -----'),(3,'-----Original Message-----'),(4,'<!-- Break Line -->'),(5,'====== Please reply above this line ======'),(6,'_____');
/*!40000 ALTER TABLE `tblticketbreaklines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketdepartments`
--

DROP TABLE IF EXISTS `tblticketdepartments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketdepartments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `email` text NOT NULL,
  `clientsonly` text NOT NULL,
  `piperepliesonly` text NOT NULL,
  `noautoresponder` text NOT NULL,
  `hidden` text NOT NULL,
  `order` int(1) NOT NULL,
  `host` text NOT NULL,
  `port` text NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(64))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketdepartments`
--

LOCK TABLES `tblticketdepartments` WRITE;
/*!40000 ALTER TABLE `tblticketdepartments` DISABLE KEYS */;
INSERT INTO `tblticketdepartments` VALUES (1,'Provisioning','','provisioningtest','','','','',1,'','110','','fZjPBXW3a+6AgoUdYX0mFLhL8/g=');
/*!40000 ALTER TABLE `tblticketdepartments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketfeedback`
--

DROP TABLE IF EXISTS `tblticketfeedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketfeedback` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) NOT NULL,
  `adminid` int(10) NOT NULL,
  `rating` int(2) NOT NULL,
  `comments` text NOT NULL,
  `datetime` datetime NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticketid` (`ticketid`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tblticketfeedback_ibfk_1` FOREIGN KEY (`ticketid`) REFERENCES `tbltickets` (`id`),
  CONSTRAINT `tblticketfeedback_ibfk_2` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketfeedback`
--

LOCK TABLES `tblticketfeedback` WRITE;
/*!40000 ALTER TABLE `tblticketfeedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketfeedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketlog`
--

DROP TABLE IF EXISTS `tblticketlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketlog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `tid` int(10) NOT NULL,
  `action` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketlog`
--

LOCK TABLES `tblticketlog` WRITE;
/*!40000 ALTER TABLE `tblticketlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketmaillog`
--

DROP TABLE IF EXISTS `tblticketmaillog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketmaillog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `to` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `status` text NOT NULL,
  `ticketid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticketid` (`ticketid`),
  CONSTRAINT `tblticketmaillog_ibfk_1` FOREIGN KEY (`ticketid`) REFERENCES `tbltickets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketmaillog`
--

LOCK TABLES `tblticketmaillog` WRITE;
/*!40000 ALTER TABLE `tblticketmaillog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketmaillog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketnotes`
--

DROP TABLE IF EXISTS `tblticketnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketnotes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) NOT NULL,
  `adminid` int(10) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticketid_date` (`ticketid`,`date`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tblticketnotes_ibfk_1` FOREIGN KEY (`ticketid`) REFERENCES `tbltickets` (`id`),
  CONSTRAINT `tblticketnotes_ibfk_2` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketnotes`
--

LOCK TABLES `tblticketnotes` WRITE;
/*!40000 ALTER TABLE `tblticketnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketpredefinedcats`
--

DROP TABLE IF EXISTS `tblticketpredefinedcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketpredefinedcats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parentid` int(10) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid_name` (`parentid`,`name`(64))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketpredefinedcats`
--

LOCK TABLES `tblticketpredefinedcats` WRITE;
/*!40000 ALTER TABLE `tblticketpredefinedcats` DISABLE KEYS */;
INSERT INTO `tblticketpredefinedcats` VALUES (6,0,'test');
/*!40000 ALTER TABLE `tblticketpredefinedcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketpredefinedreplies`
--

DROP TABLE IF EXISTS `tblticketpredefinedreplies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketpredefinedreplies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL,
  `name` text NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  CONSTRAINT `tblticketpredefinedreplies_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `tblticketpredefinedcats` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketpredefinedreplies`
--

LOCK TABLES `tblticketpredefinedreplies` WRITE;
/*!40000 ALTER TABLE `tblticketpredefinedreplies` DISABLE KEYS */;
INSERT INTO `tblticketpredefinedreplies` VALUES (1,6,'reply one ','dsadsadasdas');
/*!40000 ALTER TABLE `tblticketpredefinedreplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketreplies`
--

DROP TABLE IF EXISTS `tblticketreplies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketreplies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` int(10) NOT NULL,
  `draft` tinyint(4) NOT NULL,
  `userid` int(10) DEFAULT NULL,
  `contactid` int(10) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `message` text NOT NULL,
  `adminname` varchar(32) DEFAULT NULL,
  `attachment` text NOT NULL,
  `rating` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid_date` (`tid`,`date`),
  KEY `userid` (`userid`),
  KEY `adminid` (`adminname`),
  KEY `contactid` (`contactid`),
  CONSTRAINT `tblticketreplies_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `tbltickets` (`id`),
  CONSTRAINT `tblticketreplies_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `tbltickets` (`id`),
  CONSTRAINT `tblticketreplies_ibfk_3` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`),
  CONSTRAINT `tblticketreplies_ibfk_5` FOREIGN KEY (`contactid`) REFERENCES `tblcontacts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketreplies`
--

LOCK TABLES `tblticketreplies` WRITE;
/*!40000 ALTER TABLE `tblticketreplies` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketreplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickets`
--

DROP TABLE IF EXISTS `tbltickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` varchar(15) NOT NULL,
  `did` int(10) DEFAULT NULL,
  `userid` int(10) DEFAULT NULL,
  `contactid` int(10) DEFAULT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `cc` text DEFAULT NULL,
  `c` text DEFAULT NULL COMMENT 'Security Code',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` text NOT NULL,
  `message` text NOT NULL,
  `status` varchar(64) DEFAULT NULL,
  `urgency` text NOT NULL,
  `adminname` varchar(32) DEFAULT NULL,
  `attachment` text NOT NULL,
  `lastreply` datetime NOT NULL,
  `flag` int(1) NOT NULL,
  `clientunread` int(1) NOT NULL,
  `adminunread` text NOT NULL,
  `replyingadmin` int(1) NOT NULL,
  `replyingtime` datetime NOT NULL,
  `service` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid_c` (`tid`,`c`(64)),
  KEY `userid` (`userid`),
  KEY `status` (`status`(10)),
  KEY `date` (`date`),
  KEY `contactid` (`contactid`),
  KEY `assignedadminid` (`adminname`),
  KEY `status_2` (`status`),
  KEY `did` (`did`),
  CONSTRAINT `tbltickets_ibfk_2` FOREIGN KEY (`contactid`) REFERENCES `tblcontacts` (`id`),
  CONSTRAINT `tbltickets_ibfk_4` FOREIGN KEY (`status`) REFERENCES `tblticketstatuses` (`title`),
  CONSTRAINT `tbltickets_ibfk_5` FOREIGN KEY (`did`) REFERENCES `tblticketdepartments` (`id`),
  CONSTRAINT `tbltickets_ibfk_6` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickets`
--

LOCK TABLES `tbltickets` WRITE;
/*!40000 ALTER TABLE `tbltickets` DISABLE KEYS */;
INSERT INTO `tbltickets` VALUES (30,'745673',1,NULL,NULL,'guy@hd.net.nz','guy@hd.net.nz','guy@hd.net.nz','WjEs0s0a','2016-06-17 15:34:47','guy@hd.net.nz','\r\n\r\nguy@hd.net.nz','Answered','Medium','Guy Lowe','','2017-02-21 16:34:56',2,1,',1',0,'2017-04-19 12:41:51',''),(31,'614919',1,NULL,NULL,'guy@hd.net.nz','guy@hd.net.nz','','vssbJ3yg','2016-06-17 15:36:45','guy@hd.net.nz','\r\nguy@hd.net.nz','Answered','Medium','Guy Lowe','','2017-01-16 13:37:10',1,1,',1',0,'2017-02-22 12:40:40','');
/*!40000 ALTER TABLE `tbltickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketspamfilters`
--

DROP TABLE IF EXISTS `tblticketspamfilters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketspamfilters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('sender','subject','phrase') NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketspamfilters`
--

LOCK TABLES `tblticketspamfilters` WRITE;
/*!40000 ALTER TABLE `tblticketspamfilters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblticketspamfilters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblticketstatuses`
--

DROP TABLE IF EXISTS `tblticketstatuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketstatuses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `color` text NOT NULL,
  `sortorder` int(2) NOT NULL,
  `showactive` int(1) NOT NULL,
  `showawaiting` int(1) NOT NULL,
  `autoclose` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblticketstatuses`
--

LOCK TABLES `tblticketstatuses` WRITE;
/*!40000 ALTER TABLE `tblticketstatuses` DISABLE KEYS */;
INSERT INTO `tblticketstatuses` VALUES (1,'Open','#1ba1ff',1,1,1,0),(2,'Answered','#0C0',2,1,1,1),(3,'Customer-Reply','#ff6600',3,1,1,1),(4,'Closed','#888888',10,1,1,0),(5,'On Hold','#224488',5,1,1,0),(6,'In Progress','#cc0000',6,1,1,0),(7,'TEST STATUS','',0,0,0,0);
/*!40000 ALTER TABLE `tblticketstatuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltickettags`
--

DROP TABLE IF EXISTS `tbltickettags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltickettags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) NOT NULL,
  `tag` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticketid` (`ticketid`),
  CONSTRAINT `tbltickettags_ibfk_1` FOREIGN KEY (`ticketid`) REFERENCES `tbltickets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbltickettags`
--

LOCK TABLES `tbltickettags` WRITE;
/*!40000 ALTER TABLE `tbltickettags` DISABLE KEYS */;
INSERT INTO `tbltickettags` VALUES (6,30,'test');
/*!40000 ALTER TABLE `tbltickettags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbltodolist`
--

DROP TABLE IF EXISTS `tbltodolist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbltodolist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `adminid` int(10) DEFAULT NULL COMMENT 'null is unassigned to clients',
  `status` text NOT NULL,
  `duedate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `duedate` (`duedate`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tbltodolist_ibfk_1` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-08  0:10:46
