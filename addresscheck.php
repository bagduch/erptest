<?php

// Unlimited Internet Home Page Address Checker, Written by Milos 09/09/2014 - 5PM, version 1.0
// For Fastcom API address checker.

header('access-control-allow-origin: null');

function checkregion($regioninput) {

    $region = array(
        "Ashburton",
        "Blenheim",
        "Gisborne",
        "Greymouth",
        "Invercargill",
        "Kapiti",
        "Paraparaumu",
        "Levin",
        "Masterton",
        "Napier",
        "Hastings",
        "Nelson",
        "Oamaru",
        "Queenstown",
        "Wanaka",
        "Taupo",
        "Tauranga",
        "Timaru",
        "Whakatane",
        "Whanganui"
    );

    $flag = false;
    for ($i = 0; $i < sizeof($region); $i++) {
        if ($region[$i] == $regioninput) {
            $flag = true;
        }
    }
    return $flag;
}

if ($_POST['lat']) {

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Max-Age: 1000');
    $url = 'https://broadbandmap.nz/api/1.0/networks?x=' . $_POST['lng'] . '&y=' . $_POST['lat'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $_SESSION['useraddress'] = $_POST['address'];
    $data = json_decode($response);



    if ($data->results[4]->availability == "Available") {
        $_SESSION['plan']['adsl'] = 1;
    } else {
        $_SESSION['plan']['adsl'] = 0;
    }

    if ($data->results[2]->availability == "Available") {
        $_SESSION['plan']['vdsl'] = 1;
    } else {
        $_SESSION['plan']['vdsl'] = 0;
    }

    if ($data->results[0]->availability == "Available" && $data->results[0]->technology == "Fibre" && !checkregion($_POST['region'])) {
        $_SESSION['plan']['ufb'] = 1;
    } else {
        $_SESSION['plan']['ufb'] = 0;
    }


    echo json_encode($response);
}
?>
