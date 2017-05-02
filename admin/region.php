<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$region = array(
    "New Zealand" => array(
        "Northland", "Auckland", "Waikato", "Bay of Plenty", "Gisborne", "Hawke's Bay", "Taranaki", "Manawatu-Wanganui", "Wellington", "Tasman", "Nelson",
        "Marlborough", "West Coast", "Canterbury", "Otago", "Southland"
    )
);



if (isset($_POST['region'])) {
    if (isset($region[$_POST['region']])) {
        $html = "<select name='state' class='form-control'>";
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
        echo FALSE;
    }
}