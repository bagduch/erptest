
<script type="text/javascript" src="templates/ra_flat/treegrid/js/jquery.treegrid.js"></script>
<link href="templates/ra_flat/treegrid/css/jquery.treegrid.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <div class="card" style="z-index: 2">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Period</p>
                        <div class="form-group">
                            <input type="text" class="form-control datepicker" value="{$date}">
                        </div>
                        <div class="form-group">
                            <button id="search" class="btn btn-default">Search</button>
                        </div>
                    </div> 
                    <div class="col-sm-4">
                        <p>Search (Phone, Name)</p>
                        <div class="form-group">
                            <input type="text" class="form-control search" value="{$field}">
                            <input id="searchinput" type="hidden" value="{$accountcode}" name="search">
                            <div class="searchresut"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="content">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                            <li class="active">
                                <a href="#pill1" data-toggle="tab">Sum</a>
                            </li>
                            <li>
                                <a id="outbound" href="#pill2" data-toggle="tab">Out-Bound Calls</a>
                            </li>
                            <li>
                                <a href="#pill3" data-toggle="tab">In-Bound Calls</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="pill1">
                        <div class="col-md-8">
                            <div class="">
                                <canvas id="sumchart"></canvas>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <td>Category</td>
                                        <td>Billing Amount</td>
                                        <td>Total Number</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$categoriessum item=data}
                                        <tr>
                                            <td>{$data.category}</td>
                                            <td>$ {$data.bill_sum}</td>
                                            <td>{$data.count}</td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="td-total">Total Calls</td>
                                        <td class="td-price">{$sumbill.total}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="td-total">Total</td>
                                        <td class="td-price">$ {$sumbill.bill}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                    <div class="tab-pane" id="pill2">


                    </div>
                    <div class="tab-pane" id="pill3">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{literal}

    <script type="text/javascript">
        $("document").ready(function () {
            $(".search").on('input', function () {

                var $this = $(this);
                var $searchinput = $("#searchinput");
                $searchinput.val("");
                var value = $(this).val();
                if (value.length > 2)
                {
                    $.ajax({
                        url: "",
                        method: "POST",
                        data: {"value": value, "search": 1, "token": "{/literal}{$csrfToken}{literal}"},
                        success: function (data)
                        {
                            $(".searchresut").html(data);
                            $(".searchresut").find("button").click(function () {
                                $searchinput.val($(this).attr('data-value'));
                                $this.val($(this).html());
                                $(".searchresut").find("div").remove();
                            });
                        }
                    });
                }
            });


            function ajaxGetDetail(id, period, cate, cateid)
            {
                $.ajax({
                    url: "addonmodules.php?module=tolls&v=api",
                    method: "POST",
                    data: {"method": 'getDetail', 'accountcode': id, 'cate': cate, 'date': period}
                    ,
                    success: function (data)
                    {
                        $(".treegrid-00" + cateid + id + "").after(data);
                        $(".tree").treegrid();
                        $(".treegrid-00" + cateid + id + "").treegrid('expand');
                    }
                });

            }

            function ajaxGetCate(id, period)
            {
                $.ajax({
                    url: "addonmodules.php?module=tolls&v=api",
                    method: "POST",
                    data: {"method": 'getCategroy', 'date': period, 'accountcode': id}
                    ,
                    success: function (data)
                    {
                        $(".treegrid-" + id + "").after(data);
                        $(".tree").treegrid();
                        $(".clientcate").click(function (e) {
                            e.preventDefault();
                            cate = $(this).attr('data-cate');
                            cateid = $(this).attr('date-cateid');
                            $(".treegrid-parent-00" + cateid + id + "").remove();
                            ajaxGetDetail(id, period, cate, cateid);
                        });
                    }
                });
            }
            $("#tabs a").on("shown.bs.tab", function (e)
            {
                if (e.target.id == "outbound" && !$(".tree")[0])
                {
                    period = $('.datepicker').val();
                    accountcode = $("#searchinput").val();
                    $.ajax({
                        url: "addonmodules.php?module=tolls&v=api",
                        method: "POST",
                        data: {"method": 'getOutBoundcalls', "date": period, "accountcode": accountcode, "token": "{/literal}{$csrfToken}{literal}"},
                        success: function (data)
                        {
                            $("#pill2").append(data);
                            $(".clientid").click(function (e) {
                                e.preventDefault();
                                id = $(this).attr('data-id');
                                $(".treegrid-parent-" + id + "").remove();
                                ajaxGetCate(id, period);
                            });
                        }
                    });
                }

            });



            $("#simple-accordion-alt").accordion({
                collapsible: true,
                active: false,
                animate: 200
            });
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM',
            });
            $("#search").click(function () {
                value = $('.datepicker').val();
                search = $("#searchinput").val();
                field = $(".search").val();
                url = "addonmodules.php?module=tolls&date=" + value + "&search=" + search + "&field=" + field;
                window.location = url;
            });
            var barChartData = {
                labels: {/literal}{$category}{literal},
                datasets: {/literal}{$categraphic}{literal}

            };
            var config = {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    scales: {
                        xAxes: [{
                                stacked: true,
                            }],
                        yAxes: [{
                                stacked: true
                            }]
                    }

                }
            }


            if ($('#sumchart')[0]) {
                var barChartCanvas = $("#sumchart").get(0).getContext("2d");
                var barChart = new Chart(barChartCanvas, config);
            }
        });
        /*---------------------
         ----- BAR CHART -----
         ---------------------*/


    </script>
{/literal}
