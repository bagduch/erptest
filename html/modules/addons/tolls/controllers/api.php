<?php

if (!defined("RA")) {
    die("This file cannot be accessed directly");
}
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require(dirname(dirname(__FILE__)) . '/models/tolls.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$catearray = array(
    "local" => 1,
    "international" => 2,
    "local" => 3,
    "miscellaneous" => 4,
    "mobile" => 5,
    "national" => 6,
    "toll-free" => 7,
    "" => 8,
    "unknown" => 9
);

if (isset($_POST['method'])) {
    $accountcode = $_POST['accountcode'];
    $date = $_POST['date'];
    $tolls = new tolls($accountcode, $date);
    $outboundids = $tolls->getOutBoundcallsIds($accountcode, $date);
    if ($_POST['method'] == "getOutBoundcalls") {
        $content = "<table class='table table-striped tree'>";
        foreach ($outboundids as $row) {
            $content .= "
                    <tr class='treegrid-" . $row . "'>
                        <td><a data-id='" . $row . "' class='clientid'>Client ID " . $row . "</a></td>
                    </tr>
";
        }
        $content .= " </table>";
        echo $content;
    } elseif ($_POST['method'] == "getCategroy") {
        $categories = $tolls->getCategroies($accountcode, $date);
        $content = "";
        foreach ($categories as $row) {
            $content .= "
                    <tr class='treegrid-00" . $catearray[$row] . $accountcode . " treegrid-parent-" . $accountcode . "'>
                        <td><a date-cateid='".$catearray[$row]."' data-cate='" . $row . "' class='clientcate'>" . $row . "</a></td>
                    </tr>";
        }
        echo $content;
    } elseif ($_POST['method'] == "getDetail") {
        $details = $tolls->getDetails($accountcode, $date, $_POST['cate']);
        $content = "";
        foreach ($details as $row) {
            $content .= "
                    <tr class='treegrid-" . $row['id'] . " treegrid-parent-00" . $catearray[$_POST['cate']] . $accountcode . "'>
                        <td>" . $row['id'] . "</td>
                    </tr>";
        }

        echo $content;
    }
} else {
    echo "No Method Found";
}

