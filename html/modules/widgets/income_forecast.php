<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function widget_income_forecast($vars) {
    global $ra, $_ADMINLANG, $currency, $currencytotal, $data;
    $title = $_ADMINLANG['home']['incomeforecast'];

    function ah_formatstat($billingcycle, $stat) {
        global $data, $currency, $currencytotal;
        $value = array_key_exists($billingcycle, $data) ? $data[$billingcycle][$stat] : '';
        if (!$value)
            $value = 0;
        if ($stat == "sum") {
            if ($billingcycle == "Monthly") {
                $currencytotal += $value * 12;
            } elseif ($billingcycle == "Quarterly") {
                $currencytotal += $value * 4;
            } elseif ($billingcycle == "Semi-Annually") {
                $currencytotal += $value * 2;
            } elseif ($billingcycle == "Annually") {
                $currencytotal += $value;
            } elseif ($billingcycle == "Biennially") {
                $currencytotal += $value / 2;
            } elseif ($billingcycle == "Triennially") {
                $currencytotal += $value / 3;
            }
            $value = formatCurrency($value);
        }
        return $value;
    }

    $incomestats = array();
    $result = select_query_i("tblcustomerservices,tblclients", "currency,billingcycle,COUNT(*),SUM(amount)", "tblclients.id = tblcustomerservices.userid AND (servicestatus = 'Active' OR servicestatus = 'Suspended') GROUP BY currency, billingcycle");
    while ($data = mysqli_fetch_array($result)) {
        $incomestats[$data['currency']][$data['billingcycle']]["count"] = $data[2];
        $incomestats[$data['currency']][$data['billingcycle']]["sum"] = $data[3];
    }
    $content = '';
    if (count($incomestats)) {
        foreach ($incomestats AS $currency => $data) {
            $currency = getCurrency("", $currency);
            $currencytotal = 0;
            $content .= "<div style=\"text-align:center;\"><span class=\"textred\"><b>{$currency['code']} " . $_ADMINLANG['currencies']['currency'] . "</b></span><br />
    " . $_ADMINLANG['billingcycles']['monthly'] . ": " . ah_formatstat('Monthly', 'sum') . " (" . ah_formatstat('Monthly', 'count') . ")<br />
    " . $_ADMINLANG['billingcycles']['quarterly'] . ": " . ah_formatstat('Quarterly', 'sum') . " (" . ah_formatstat('Quarterly', 'count') . ")<br />
    " . $_ADMINLANG['billingcycles']['semiannually'] . ": " . ah_formatstat('Semi-Annually', 'sum') . " (" . ah_formatstat('Semi-Annually', 'count') . ")<br />
    " . $_ADMINLANG['billingcycles']['annually'] . ": " . ah_formatstat('Annually', 'sum') . " (" . ah_formatstat('Annually', 'count') . ")<br />
    " . $_ADMINLANG['billingcycles']['biennially'] . ": " . ah_formatstat('Biennially', 'sum') . " (" . ah_formatstat('Biennially', 'count') . ")<br />
    " . $_ADMINLANG['billingcycles']['triennially'] . ": " . ah_formatstat('Triennially', 'sum') . " (" . ah_formatstat('Triennially', 'count') . ")<br />
    <span class=\"textgreen\"><b>" . $_ADMINLANG['billing']['annualestimate'] . ": " . formatCurrency($currencytotal) . "</b></span></div>";
        }
    } else {
        $content = '<div align="center">No Active or Suspended Products/Services Found to build Forecast</div>';
    }
    $content = '<div id="incomeforecast">' . $content . '</div>';
    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_income_forecast");
?>

