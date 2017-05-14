<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("CLIENTAREA", true);
require "init.php";
initialiseClientArea($_LANG['carttitle'], "", "<a href=\"cart.php\">" . $_LANG['carttitle'] . "</a>");

$templatefile = "serviceorder";
outputClientArea($templatefile, true);
?>

