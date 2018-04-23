<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$region = array(
    "New Zealand" => array(
        "Northland",
        "Auckland",
        "Coromandel",
        "Bay of Plenty",
        "Waikato",
        "Rotorua",
        "Eastland",
        "Taupo",
        "Ruapehu",
        "Taranaki",
        "Hawke's Bay",
        "Wanganui",
        "Manawatu",
        "Wairarapa",
        "Wellington",
        "Nelson",
        "Marlborough",
        "West Coast",
        "Christchurch",
        "Canterbury",
        "Mt Cook",
        "Wanaka",
        "Queenstown",
        "Otago",
        "Dunedin",
        "Fiordland",
        "Southland"
    )
);



if (isset($_POST['region'])) {
    if (isset($region[$_POST['region']])) {
        $html = "<select name='state' class='form-control state'>";
        foreach ($region[$_POST['region']] as $row) {
            if (isset($_POST['state']) && $_POST['state'] == $row) {
                $select = "Selected";
            } else {
                $select = "";
            }
            $html .= "<option " . $select . " value='" . $row . "'>" . $row . "</option>";
        }
        $html .="</select>";
        echo $html;
    } else {
       echo "<input class='form-control state' type='text'>";
    }
}