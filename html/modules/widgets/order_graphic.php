<?php
if (!defined("RA"))
    die("This file cannot be accessed directly");

function createDateRangeArraysecond($strDateFrom, $strDateTo) {
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
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function widget_order_left_overview($vars) {
    global $_ADMINLANG;
    $title = "Order Overview";

    $end_date = date("Y-m-d");
    $start_date = date("Y-m-d", strtotime("-15 days", strtotime("now")));
    $query = "select * from tblorders where date between '" . $start_date . "' AND '" . $end_date . "' order by date desc";
    $result = full_query_i($query);
    $orders = array();
    $starttime = date("t", strtotime(date("Y-m-01")));
    if (function_exists($query)) {
        
    }
    $datePeriod = createDateRangeArraysecond($start_date, $end_date);

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
    foreach ($orders as $date => $row) {
        $active[] = $row['active'];
        $pending[] = $row['pending'];
        $cancel[] = $row['cancel'];
        $draft[] = $row['draft'];
    }
    $datePeriod = json_encode($datePeriod);

    $active = json_encode($active);
    $pending = json_encode($pending);
    $cancel = json_encode($cancel);
    $draft = json_encode($draft);

    ob_start();
    ?>

    <canvas id="orderChart"></canvas>
    <script type="text/javascript">
        function orderjqueryLoaded() {
            //do stuff

            $(document).ready(function () {
                var orderChartData = {
                    labels: <?= $datePeriod ?>,

                    datasets: [{
                            type: 'bar',
                            label: 'Active',
                            borderColor: "#7AC29A",
                            backgroundColor: "#7AC29A",
                            borderWidth: 2,
                            fill: false,
                            data: <?= $active ?>
                        }, {
                            type: 'bar',
                            label: 'Pending',
                            borderColor: "#68B3C8",
                            backgroundColor: "#68B3C8",
                            data: <?= $pending ?>,
                            borderColor: 'white',
                            borderWidth: 2
                        }, {
                            type: 'bar',
                            label: 'Cancel',
                            borderColor: "#FF6384",
                            backgroundColor: "#FF6384",
                            data: <?= $cancel ?>
                        }, {
                            type: 'bar',
                            label: 'Draft',
                            borderColor: "#F3BB45",
                            backgroundColor: "#F3BB45",
                            data: <?= $draft ?>
                        }]

                };


                var orderconfig = {
                    type: 'bar',
                    data: orderChartData,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Orders'
                        },
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                        }
                    }
                }


                if ($('#orderChart')[0]) {
                    var orderChartCanvas = $("#orderChart").get(0).getContext("2d");
                    var orderChart = new Chart(orderChartCanvas, orderconfig);

                }
            });
        }

        function ordercheckJquery() {
            if (window.jQuery && jQuery.ui) {
                orderjqueryLoaded();
            } else {
                window.setTimeout(ordercheckJquery, 100);
            }
        }

        ordercheckJquery();

    </script>


    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return array('title' => $title, 'content' => $content);
}

add_hook("AdminHomeWidgets", 2, "widget_order_left_overview");
?>