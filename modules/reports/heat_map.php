<?php
 ini_set('memory_limit', '1024M'); // or you could use 1G
if (!defined("RA"))
    die("This file cannot be accessed directly");
try {
    $dbh = new PDO('mysql:host=hc2.hd.net.nz;port=3306;dbname=unlimite_addchecker', 'showlist', 'CryRauTwiockepiccihu');
    $currentmonty = date("Y-m");
    $stmt = $dbh->prepare("select timestamp,address_request,remote_address,user_agent from checks order by timestamp desc");

    $stmt->execute();
} catch (Exception $e) {
    var_dump($e);
}

$stmt2 = $dbh->prepare("select count(distinct remote_address) as numip from checks");
$stmt2->execute();

$rows = $stmt->fetchAll();

$stmt3 = $dbh->prepare("select count(*) as totalamount from checks");
$stmt3->execute();
$totalnumbers = $stmt3->fetchAll();
$num = $stmt2->fetchAll();
$groupby = array();
$browser = array();
foreach ($rows as $row) {
    $groupby[date("Y-m-d", strtotime($row['timestamp']))] ++;
    $browser[$row['user_agent']] ++;
}


$days = array();

try {

    $stmt5 = $dbh->prepare("select id,timestamp,address_request,remote_address,user_agent,lat,lng from checks where lat is not null order by timestamp desc");
    $stmt5->execute();
} catch (Exception $e) {
    var_dump($e);
}

$mapdata = $stmt5->fetchAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                height: 60%;
                width: 60%;
                margin:auto;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #floating-panel {
                position: absolute;
                top: 10px;
                left: 25%;
                z-index: 5;
                background-color: #fff;
                padding: 5px;
                border: 1px solid #999;
                text-align: center;
                font-family: 'Roboto','sans-serif';
                line-height: 30px;
                padding-left: 10px;
            }
            #floating-panel {
                background-color: #fff;
                border: 1px solid #999;
                left: 25%;
                padding: 5px;
                position: absolute;
                top: 10px;
                z-index: 5;
            }
        </style>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <title>Unlimited Internet Address Checked Log</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Year', 'Checks'],
<?php foreach ($groupby as $date => $row) {
    ?>
                        [new Date('<?php echo $date ?>'),<?php echo $row; ?>],
<?php }
?>

                ]);

                var options = {
                    title: 'Address Checks',
                    hAxis: {
                        title: 'Year',
                        titleTextStyle: {
                            color: '#333'
                        },
                    },
                    vAxis: {
                        minValue: 0
                    },
                    explorer: {
                        axis: 'horizontal',
                        maxZoomIn: 4.0,
                        keepInBounds: true
                    },
                    colors: ['#D44E41'],
                }
                ;

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                chart.draw(data, options);


            }
        </script>

    </head>

    <body>
        <h1>Unlimited Internet Address Checked Log (<?php echo $totalnumbers[0]['totalamount']; ?> checks, <?php echo $num[0]['numip']; ?> unique)</h1>
        <div id="curve_chart"></div>
        <div id="map"></div>
        <br/>


        <pre style="font-size: 22px;">
<?php
$days = array_reverse($days);

$keys = array_keys($days);

for ($i = 0; $i < count($days); $i++) {
    $cur = $days[$keys[$i - 1]];
    $next = $days[$keys[$i]];
    echo $keys[$i] . ": " . str_pad($cur ? : "?", 4) . " ->   " . str_pad($next, 4) . " (" . ($cur ? round(($next - $cur) / $cur * 100, 0) : "?") . "%) <br />";
}
?>
        </pre>
        <script>

            // This example requires the Visualization library. Include the libraries=visualization
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=visualization">

            var map, heatmap;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: {lat: -36.848461, lng: 174.763336},
                });

                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: getPoints(),
                    map: map
                });

                heatmap.setOptions({
                    dissipating: true,
                    maxIntensity: 10,
                    radius: 5,
                    opacity: 0.9,
                    //dissipating: false
                });
                heatmap.setMap(map);
            }

            function toggleHeatmap() {
                heatmap.setMap(heatmap.getMap() ? null : map);
            }

            function changeGradient() {
                var gradient = [
                    'rgba(0, 255, 255, 0)',
                    'rgba(0, 255, 255, 1)',
                    'rgba(0, 191, 255, 1)',
                    'rgba(0, 127, 255, 1)',
                    'rgba(0, 63, 255, 1)',
                    'rgba(0, 0, 255, 1)',
                    'rgba(0, 0, 223, 1)',
                    'rgba(0, 0, 191, 1)',
                    'rgba(0, 0, 159, 1)',
                    'rgba(0, 0, 127, 1)',
                    'rgba(63, 0, 91, 1)',
                    'rgba(127, 0, 63, 1)',
                    'rgba(191, 0, 31, 1)',
                    'rgba(255, 0, 0, 1)'
                ]
                heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
            }

            function changeRadius() {
                heatmap.set('radius', heatmap.get('radius') ? null : 20);
            }

            function changeOpacity() {
                heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
            }
            // Heatmap data: 500 Points
            function getPoints() {
                return [
<?php
foreach ($mapdata as $row) {

    $line.="{location: new google.maps.LatLng(" . $row['lat'] . "," . $row['lng'] . "),weight: 10},";
}
$line = rtrim($line, ',');
echo $line;
?>

                ];
            }
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1-ZaqRz5KwUT62JHMLcw_8_FSC7ZqHbo&libraries=visualization&callback=initMap">
        </script>

    </body>
</html>
