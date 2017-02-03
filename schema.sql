-- MySQL dump 10.13  Distrib 5.7.15, for Linux (x86_64)
--
-- Host: mysql1.hd.net.nz    Database: robotacct
-- ------------------------------------------------------
-- Server version	5.5.52-0+deb8u1-log

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
-- Table structure for table `mod_staffboard`
--

DROP TABLE IF EXISTS `mod_staffboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_staffboard` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `note` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `amountin` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fees` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amountout` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rate` decimal(10,5) NOT NULL DEFAULT '1.00000',
  `transid` varchar(64) NOT NULL,
  `invoiceid` int(10) DEFAULT NULL,
  `refundid` int(10) NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=7910 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `weight` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`(32)),
  KEY `welcomeemail` (`welcomeemail`),
  CONSTRAINT `tbladdons_ibfk_1` FOREIGN KEY (`welcomeemail`) REFERENCES `tblemailtemplates` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=1362 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tbladminroles`
--

DROP TABLE IF EXISTS `tbladminroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladminroles` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `widgets` varchar(64) NOT NULL,
  `systememails` int(1) NOT NULL,
  `accountemails` int(1) NOT NULL,
  `supportemails` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  KEY `affiliateid` (`id`),
  KEY `clientid` (`clientid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tblaffiliatespending`
--

DROP TABLE IF EXISTS `tblaffiliatespending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblaffiliatespending` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `affaccid` int(10) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL,
  `clearingdate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clearingdate` (`clearingdate`),
  KEY `affaccid` (`affaccid`),
  CONSTRAINT `tblaffiliatespending_ibfk_1` FOREIGN KEY (`affaccid`) REFERENCES `tblaffiliatesaccounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tblbannedemails`
--

DROP TABLE IF EXISTS `tblbannedemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbannedemails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain` varchar(64) NOT NULL,
  `count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tblclientgroups`
--

DROP TABLE IF EXISTS `tblclientgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblclientgroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(45) NOT NULL,
  `groupcolour` varchar(45) DEFAULT NULL,
  `discountpercent` decimal(10,2) unsigned DEFAULT '0.00',
  `susptermexempt` text,
  `separateinvoices` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `phonenumber` text,
  `mobilenumber` varchar(16) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `authmodule` text NOT NULL,
  `authdata` text NOT NULL,
  `currency` int(10) NOT NULL,
  `defaultgateway` varchar(64) DEFAULT NULL,
  `credit` decimal(10,2) NOT NULL,
  `taxexempt` text NOT NULL,
  `latefeeoveride` text NOT NULL,
  `overideduenotices` tinyint(1) DEFAULT NULL,
  `separateinvoices` text NOT NULL,
  `disableautocc` text NOT NULL,
  `datecreated` date NOT NULL,
  `notes` text NOT NULL,
  `billingcid` int(10) NOT NULL,
  `securityqid` int(10) DEFAULT NULL,
  `securityqans` text NOT NULL,
  `groupid` int(10) unsigned DEFAULT '0',
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
  PRIMARY KEY (`id`),
  KEY `firstname_lastname` (`firstname`(32),`lastname`(32)),
  KEY `email` (`email`(64)),
  KEY `groupid` (`groupid`),
  KEY `currency` (`currency`),
  KEY `defaultgateway` (`defaultgateway`),
  CONSTRAINT `tblclients_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `tblclientgroups` (`id`),
  CONSTRAINT `tblclients_ibfk_2` FOREIGN KEY (`currency`) REFERENCES `tblcurrencies` (`id`),
  CONSTRAINT `tblclients_ibfk_3` FOREIGN KEY (`defaultgateway`) REFERENCES `tblpaymentgatewaynames` (`gateway`)
) ENGINE=InnoDB AUTO_INCREMENT=12768 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `mobilenumber` text,
  `subaccount` int(1) NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `rate` decimal(10,5) NOT NULL DEFAULT '1.00000',
  `default` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `firstpaymentamount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `billingcycle` text NOT NULL,
  `nextduedate` date DEFAULT NULL,
  `nextinvoicedate` date NOT NULL,
  `servicestatus` enum('Pending','Active','Suspended','Terminated','Cancelled','Fraud','Draft') NOT NULL,
  `billstatus` enum('Active','Pending','Suspend','Terminate','Cancel') DEFAULT NULL,
  `suspendreason` text,
  `overideautosuspend` text,
  `overidesuspenduntil` date DEFAULT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text,
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
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `description` text,
  `fieldoptions` text,
  `regexpr` text COMMENT 'Regex which must be matched for value to be valid',
  `adminonly` tinyint(1) DEFAULT '0' COMMENT 'Whether only visible to admin / system',
  `required` tinyint(1) DEFAULT '0' COMMENT 'Whether customfield is required',
  `showinvoice` tinyint(1) DEFAULT '0' COMMENT 'Whether to show on client-facing invoices',
  `sortorder` int(10) NOT NULL DEFAULT '0',
  `showorder` int(10) DEFAULT NULL COMMENT 'Order to display in on order form, client area and admin area',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cfid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='Custom fields applied to products and services';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  KEY `serviceid` (`serviceid`),
  KEY `servicegid` (`servicegid`),
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_1` FOREIGN KEY (`cfgid`) REFERENCES `tblcustomfieldsgroupnames` (`cfgid`),
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_2` FOREIGN KEY (`serviceid`) REFERENCES `tblservices` (`id`),
  CONSTRAINT `tblcustomfieldsgrouplinks_ibfk_3` FOREIGN KEY (`servicegid`) REFERENCES `tblservicegroups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tblcustomfieldsgroupnames`
--

DROP TABLE IF EXISTS `tblcustomfieldsgroupnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcustomfieldsgroupnames` (
  `cfgid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`cfgid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tbldownloadcats`
--

DROP TABLE IF EXISTS `tbldownloadcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldownloadcats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parentid` int(10) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `hidden` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  CONSTRAINT `tbldownloadcats_ibfk_1` FOREIGN KEY (`parentid`) REFERENCES `tbldownloadcats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `downloads` int(10) NOT NULL DEFAULT '0',
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
  `to` text,
  `cc` text,
  `bcc` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `tblemails_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `taxed` int(1) NOT NULL,
  `duedate` date DEFAULT NULL,
  `paymentmethod` text NOT NULL,
  `notes` text NOT NULL,
  `type` enum('AddFunds','Hosting','Invoice','Item','LateFee','Project','Upgrade','Addon') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoiceid` (`invoiceid`),
  KEY `userid` (`userid`),
  KEY `relid` (`relid`),
  CONSTRAINT `tblinvoiceitems_ibfk_8` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tblinvoiceitems_ibfk_4` FOREIGN KEY (`invoiceid`) REFERENCES `tblinvoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblinvoiceitems_ibfk_5` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblinvoiceitems_ibfk_6` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`) ON UPDATE SET NULL,
  CONSTRAINT `tblinvoiceitems_ibfk_7` FOREIGN KEY (`relid`) REFERENCES `tblcustomerservices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `taxrate` decimal(10,2) NOT NULL,
  `taxrate2` decimal(10,2) NOT NULL,
  `status` enum('Draft','Unpaid','Overdue','Paid','Cancelled','Refunded','Collections') DEFAULT NULL,
  `paymentmethod` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`),
  CONSTRAINT `tblinvoices_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152039 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `views` int(10) NOT NULL DEFAULT '0',
  `useful` int(10) NOT NULL DEFAULT '0',
  `votes` int(10) NOT NULL DEFAULT '0',
  `private` text NOT NULL,
  `order` int(3) NOT NULL,
  `parentid` int(10) NOT NULL,
  `language` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `tblnotes`
--

DROP TABLE IF EXISTS `tblnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `adminid` int(10) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `note` text NOT NULL,
  `sticky` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `adminid` (`adminid`),
  CONSTRAINT `tblnotes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tblclients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblnotes_ibfk_2` FOREIGN KEY (`adminid`) REFERENCES `tbladmins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `nameservers` text,
  `promocode` text,
  `promotype` text,
  `promovalue` text,
  `orderdata` text,
  `amount` decimal(10,2) DEFAULT '0.00',
  `paymentmethod` text NOT NULL,
  `invoiceid` int(10) DEFAULT NULL COMMENT 'First invoice only',
  `status` varchar(64) NOT NULL,
  `ipaddress` text NOT NULL,
  `fraudmodule` text,
  `fraudoutput` text,
  `notes` text,
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
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cycles` text NOT NULL,
  `appliesto` text NOT NULL,
  `requires` text NOT NULL,
  `requiresexisting` int(1) NOT NULL,
  `startdate` date NOT NULL,
  `expirationdate` date DEFAULT NULL,
  `maxuses` int(10) NOT NULL DEFAULT '0',
  `uses` int(10) NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `monthlycost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `noc` text NOT NULL,
  `statusaddress` text NOT NULL,
  `nameserver1` text NOT NULL,
  `nameserver1ip` text NOT NULL,
  `nameserver2` text NOT NULL,
  `nameserver2ip` text NOT NULL,
  `maxaccounts` int(10) NOT NULL DEFAULT '0',
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
  `setupfee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `recurring` decimal(10,2) NOT NULL DEFAULT '0.00',
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
-- Table structure for table `tblserviceconfigoptions`
--

DROP TABLE IF EXISTS `tblserviceconfigoptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblserviceconfigoptions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` int(10) NOT NULL DEFAULT '0',
  `optionname` text NOT NULL,
  `optiontype` text NOT NULL,
  `qtyminimum` int(10) NOT NULL,
  `qtymaximum` int(10) NOT NULL,
  `order` int(1) NOT NULL DEFAULT '0',
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `productid` (`gid`),
  CONSTRAINT `tblserviceconfigoptions_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `tblserviceconfiggroups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `sortorder` int(10) NOT NULL DEFAULT '0',
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `configid` (`configid`),
  CONSTRAINT `tblserviceconfigoptionssub_ibfk_1` FOREIGN KEY (`configid`) REFERENCES `tblserviceconfigoptions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `order` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `tblservices_ibfk_2` FOREIGN KEY (`welcomeemail`) REFERENCES `tblemailtemplates` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=297 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tblticketpredefinedcats`
--

DROP TABLE IF EXISTS `tblticketpredefinedcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblticketpredefinedcats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parentid` int(10) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid_name` (`parentid`,`name`(64))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `name` text,
  `email` text,
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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `cc` text,
  `c` text COMMENT 'Security Code',
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
