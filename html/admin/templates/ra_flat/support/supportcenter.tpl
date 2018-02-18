
<section class="content">
    <div class="box">
        <div class="box-header">
            <form method="post" action="supportcenter.php">
                <div style="padding:5px 15px;">Displaying Overview For:
                    <select name="period" onchange="submit()">
                        <option value="Today" {if $period eq "Today"}selected{/if}>Today</option>
                        <option value="Yesterday" {if $period eq "Yesterday"}selected{/if}>Yesterday</option>
                        <option value="This Week" {if $period eq "This Week"}selected{/if}>This Week</option>
                        <option value="This Month" {if $period eq "This Month"}selected{/if}>This Month</option>
                        <option value="Last Month" {if $period eq "Last Month"}selected{/if}>Last Month</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2 col-md-offset-1">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{$stats.newtickets}</h3>
                            <p>New Tickets</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{$stats.clientreplies}</h3>
                            <p>Client Replies</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{$stats.staffreplies}</h3>
                            <p>Staff Replies</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{$stats.opennoreply}</h3>
                            <p>Tickets Without Reply</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{$stats.avefirstresponse}</h3>
                            <p>Average First Response</p>
                        </div>
                    </div>
                </div>
            </div>
            <canvas id="pieChart" height="100"></canvas>
        </div>
    </div>     
</section>
<script src="templates/{$template}/plugins/chartjs/Chart.min.js"></script>
{literal}
    <script>
                        $(document).ready(function () {

                         

                            var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
                            var data = {
                                labels: [
                                    "Red",
                                    "Blue",
                                    "Yellow"
                                ],
                                datasets: [
                                    {
                                        data: [300, 50, 100],
                                        backgroundColor: [
                                            "#FF6384",
                                            "#36A2EB",
                                            "#FFCE56"
                                        ],
                                        hoverBackgroundColor: [
                                            "#FF6384",
                                            "#36A2EB",
                                            "#FFCE56"
                                        ]
                                    }]
                            };

                            var myPieChart = new Chart(pieChartCanvas, {
                                type: 'pie',
                                data: data,
                                options: {
                                    legend: {
                                        position: 'right',
                                    },
                                    animation: {
                                        animateScale: true,
                                        animateRotate: true
                                    },
                                    tooltips: {
                                        callbacks: {
                                            label: function (tooltipItem, data) {
                                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                                var total = dataset.data.reduce(function (previousValue, currentValue, currentIndex, array) {
                                                    return previousValue + currentValue;
                                                });
                                                var currentValue = dataset.data[tooltipItem.index];
                                                var precentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                                return precentage + "%";
                                            }
                                        }
                                    }
                                }
                            });
                        });
    </script>

{/literal}