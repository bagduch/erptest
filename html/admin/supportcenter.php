<?php

/**
 *
 * @ RA
 *
 *
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("Support Center Overview");
$aInt->title = $aInt->lang("support", "supportoverview");
$aInt->sidebar = "support";
$aInt->icon = "support";
$aInt->helplink = "Support Center";
$aInt->requiredFiles(array("ticketfunctions", "reportfunctions"));

$chart = new RAChart();

if ($period == "Yesterday") {
    $date = "date LIKE '" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))) . "%'";
} else {
    if ($period == "This Week") {
        $last_monday = strtotime("last monday");
        $next_sunday = strtotime("next sunday");
        $date = "date>='" . date("Y-m-d", $last_monday) . "' AND date<='" . date("Y-m-d", $next_sunday) . " 23:59:59'";
    } else {
        if ($period == "This Month") {
            $date = "date LIKE '" . date("Y-m-") . "%'";
        } else {
            if ($period == "Last Month") {
                $date = "date LIKE '" . date("Y-m-", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"))) . "%'";
            } else {
                $date = "date LIKE '" . date("Y-m-d") . "%'";
            }
        }
    }
}

$newtickets = get_query_val("tbltickets", "COUNT(id)", "" . $date);
$clientreplies = get_query_val("tblticketreplies", "COUNT(id)", "" . $date . " AND admin=''");
$staffreplies = get_query_val("tblticketreplies", "COUNT(id)", "" . $date . " AND admin!=''");
$hours = array();
$replytimes = array("1" => "0", "2" => 0, "4" => "0", "8" => "0", "16" => "0", "24" => "0");
$avefirstresponse = "0";
$avefirstresponsecount = "0";
$opennoreply = "0";
$result = full_query_i("SELECT id,date,(SELECT date FROM tblticketreplies WHERE tblticketreplies.tid=tbltickets.id AND admin!='' LIMIT 1) FROM tbltickets WHERE " . $date . " ORDER BY id ASC");

while ($data = mysqli_fetch_array($result)) {
    $ticketid = $data[0];
    $dateopened = $data[1];
    $datefirstreply = $data[2];
    $datehour = substr($dateopened, 11, 2);
    ++$hours[$datehour];

    if (!$datefirstreply) {
        ++$opennoreply;
    }

    $timetofirstreply = strtotime($datefirstreply) - strtotime($dateopened);
    $timetofirstreply = round($timetofirstreply / (60 * 60), 2);
    $avefirstresponse += $timetofirstreply;
    ++$avefirstresponsecount;

    if ($timetofirstreply <= 1) {
        ++$replytimes[1];
    }


    if (1 < $timetofirstreply && $timetofirstreply <= 4) {
        ++$replytimes[2];
    }


    if (4 < $timetofirstreply && $timetofirstreply <= 8) {
        ++$replytimes[4];
    }


    if (8 < $timetofirstreply && $timetofirstreply <= 16) {
        ++$replytimes[8];
    }


    if (16 < $timetofirstreply && $timetofirstreply <= 24) {
        ++$replytimes[16];
    }

    ++$replytimes[24];
}

$avefirstresponse = round($avefirstresponse / $avefirstresponsecount, 2);
$avereplieschartdata = array();
$avereplieschartdata['cols'][] = array("label" => "Timeframe", "type" => "string");
$avereplieschartdata['cols'][] = array("label" => "Number of Tickets", "type" => "number");

if (0 < $replytimes[1]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "0-1 Hours"), array("v" => $replytimes[1], "f" => $replytimes[1])));
}


if (0 < $replytimes[2]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "1-4 Hours"), array("v" => $replytimes[2], "f" => $replytimes[2])));
}


if (0 < $replytimes[4]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "4-8 Hours"), array("v" => $replytimes[4], "f" => $replytimes[2])));
}


if (0 < $replytimes[8]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "8-16 Hours"), array("v" => $replytimes[8], "f" => $replytimes[8])));
}


if (0 < $replytimes[16]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "16-24 Hours"), array("v" => $replytimes[16], "f" => $replytimes[16])));
}


if (0 < $replytimes[24]) {
    $avereplieschartdata['rows'][] = array("c" => array(array("v" => "24+ Hours"), array("v" => $replytimes[24], "f" => $replytimes[24])));
}

$averepliesargs = array();
$averepliesargs['title'] = "Average First Reply Time";
$averepliesargs['legendpos'] = "right";
$hourschartdata = array();
$hourschartdata['cols'][] = array("label" => "Timeframe", "type" => "string");
$hourschartdata['cols'][] = array("label" => "Number of Tickets", "type" => "number");
foreach ($hours as $hour => $count) {
    $hourschartdata['rows'][] = array("c" => array(array("v" => $hour), array("v" => $count, "f" => $count)));
}

$hoursargs = array();
$hoursargs['title'] = "Tickets Submitted by Hour";
$hoursargs['xlabel'] = "Number of Tickets Submitted";
$hoursargs['ylabel'] = "Hour";
$hoursargs['legendpos'] = "none";


//echo "<pre>", print_r($avereplieschartdata, 1), "</pre>";
//echo "<pre>", print_r($averepliesargs, 1), "</pre>";
//echo "<pre>", print_r($hourschartdata, 1), "</pre>";


$stats = array(
    'newtickets' => $newtickets,
    'clientreplies' => $clientreplies == "" ? 0 : $clientreplies,
    'staffreplies' => $staffreplies == "" ? 0 : $staffreplies,
    'opennoreply' => $opennoreply,
    'avefirstresponse' => $avefirstresponse,
);
$aInt->jquerycode = "$('#menu').multilevelpushmenu('expand','Support');";
$aInt->assign("period", $period);
$aInt->assign("stats", $stats);
$aInt->template = "support/supportcenter";
$aInt->display();
?>