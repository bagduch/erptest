<?php
/**
 * Affiliate Cookie Tracking + Redirection Handler
 *
 * @package    ra
 * @author     ra Limited <development@ra.com>
 * @copyright  Copyright (c) ra Limited 2005-2013
 * @license    http://www.ra.com/license/ ra Eula
 * @version    $Id$
 * @link       http://www.ra.com/
 */

define("CLIENTAREA",true);

require("init.php");

// if affiliate id is present, update visitor count & set cookie
if ($aff = $ra->get_req_var('aff')) {
	update_query("tblaffiliates",array("visitors"=>"+1"),array("id"=>$aff));
    RA_Cookie::set('AffiliateID',$aff,'3m');
}

// if product id passed in, redirect to order form
if ($pid = $ra->get_req_var('pid')) redir("a=add&pid=".(int)$pid,"cart.php");

// if product group id passed in, redirect to product group
if ($gid = $ra->get_req_var('gid')) redir("gid=".(int)$gid,"cart.php");

// if register = true, redirect to registration form
if ($ra->get_req_var('register')) redir("","register.php");

// if gocart = true, redirect to cart with request params
if ($ra->get_req_var('gocart')) {
    $reqvars = '';
    foreach ($_GET AS $k=>$v) $reqvars .= $k.'='.urlencode($v).'&';
    redir($reqvars,"cart.php");
}

// perform redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$ra->get_config('Domain'),true,301);