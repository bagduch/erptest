<?php

if (!defined("RA"))
    die("This file cannot be accessed directly");

function createDateRangeArray($strDateFrom, $strDateTo) {
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.
    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function widget_income_left_overview($vars) {
    global $_ADMINLANG;
    $title = "Income Overview";

    $end_date = date("Y-m-d");
    $start_date = date("Y-m-d", strtotime("-15 days", strtotime("now")));
    $query = "select * from tblorders where date between '" . $start_date . "' AND '" . $end_date . "' order by date desc";
    $result = full_query_i($query);
    $orders = array();
    $starttime = date("t", strtotime(date("Y-m-01")));
    $datePeriod = createDateRangeArray($start_date, $end_date);
    $income = array();
    foreach ($datePeriod as $date) {
        $orders[$date]['active'] = 0;
        $orders[$date]['pending'] = 0;
        $orders[$date]['draft'] = 0;
        $orders[$date]['cancel'] = 0;
        $orders[$date]['total'] = 0;
        $income[$date]['actual'] = 0;
        $income[$date]['pending'] = 0;
    }

    while ($data = mysqli_fetch_array($result)) {
        if ($data['status'] == "Active") {
            $orders[date("Y-m-d", strtotime($data['date']))]['active'] ++;
            $income[date("Y-m-d", strtotime($data['date']))]['actual'] += $data['amount'];
        }
        if ($data['status'] == "Pending") {
            $orders[date("Y-m-d", strtotime($data['date']))]['pending'] ++;
            $income[date("Y-m-d", strtotime($data['date']))]['pending'] += $data['amount'];
        }
        if ($data['status'] == "Draft") {
            $orders[date("Y-m-d", strtotime($data['date']))]['draft'] ++;
            $income[date("Y-m-d", strtotime($data['date']))]['pending'] += $data['amount'];
        }
        if ($data['status'] == "Fraud") {
            $orders[date("Y-m-d", strtotime($data['date']))]['cancel'] ++;
        }
        $orders[date("Y-m-d", strtotime($data['date']))]['total'] ++;
    }

    $dataarray = array();
    foreach ($income as $date => $row) {
        $dataarray[] = array(
            'y' => $date,
            'item1' => $row['pending'],
            'item2' => $row['actual']
        );
    }
    $dataarray = json_encode($dataarray);
    $content = <<<EOF
                <div class="chart tab-pane active" id="sales-chart" style="position: relative; height: 300px;"></div>
                <script type="text/javascript">
               var sarea = new Morris.Area({
                    element: 'sales-chart',
                    resize: true,
                    behaveLikeLine: true,
                    data: 
           
$dataarray
                        ,
                        xkey: 'y',
                                ykeys: ['item1', 'item2'],
                        labels: ['Potential Income', 'Actual income'],
                                lineColors: ['#f0f0f0', '#aded7a'],
                        preUnits: "$",
                                hideHover: 'auto'
                    });

                    $('.box ul.nav a').on('shown.bs.tab', function () {
                        sarea.redraw();
                    });
             </script>
EOF;



    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 1, "widget_income_left_overview");
?>