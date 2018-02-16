




</div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <p class="copyright pull-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>
            <a href="#">Ra Admin</a>
        </p>
    </div>
</footer>
</div>

</div>



<script src="templates/{$template}/assets/vendors/jquery-ui.min.js" type="text/javascript"></script>
<script src="templates/{$template}/assets/vendors/bootstrap.min.js" type="text/javascript"></script>
<script src="templates/{$template}/assets/vendors/material.min.js" type="text/javascript"></script>
<script src="templates/{$template}/assets/vendors/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="templates/{$template}/assets/vendors/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="templates/{$template}/assets/vendors/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="templates/{$template}/assets/vendors/charts/flot/jquery.flot.js"></script>
<script src="templates/{$template}/assets/vendors/charts/flot/jquery.flot.resize.js"></script>
<script src="templates/{$template}/assets/vendors/charts/flot/jquery.flot.pie.js"></script>
<script src="templates/{$template}/assets/vendors/charts/flot/jquery.flot.stack.js"></script>
<script src="templates/{$template}/assets/vendors/charts/flot/jquery.flot.categories.js"></script>
<script src="templates/{$template}/assets/vendors/charts/chartjs/Chart.min.js" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="templates/{$template}/assets/vendors/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="templates/{$template}/assets/vendors/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin -->
<script src="templates/{$template}/assets/vendors/bootstrap-datetimepicker.js"></script>

<!-- Sliders Plugin -->
<script src="templates/{$template}/assets/vendors/nouislider.min.js"></script>

<!-- Select Plugin -->
<script src="templates/{$template}/assets/vendors/jquery.select-bootstrap.js"></script>

<!--  Checkbox, Radio, Switch and Tags Input Plugins -->
<script src="templates/{$template}/assets/js/bootstrap-checkbox-radio-switch-tags.js"></script>

<!-- Circle Percentage-chart -->
<script src="templates/{$template}/assets/js/jquery.easypiechart.min.js"></script>

<!--  DataTables.net Plugin    -->
<script src="templates/{$template}/assets/vendors/jquery.datatables.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="templates/{$template}/assets/vendors/sweetalert/js/sweetalert2.min.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="templates/{$template}/assets/vendors/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="templates/{$template}/assets/vendors/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="templates/{$template}/assets/vendors/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="templates/{$template}/assets/js/amaze.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="templates/{$template}/assets/js/demo.js"></script>

<script src="templates/{$template}/assets/js/charts/flot-charts.js"></script>
<script src="templates/{$template}/assets/js/charts/chartjs-charts.js"></script>

{literal}
    <script type="text/javascript">
                function goBack() {
                    window.history.back();
                }
    {/literal} {$jscode}{$jquerycode}{literal}

                $(document).ready(function () {
                    //demo.initStatsDashboard();
                    demo.initVectorMap();
                    demo.initCirclePercentage();

                    $("#intellisearchval").keyup(function () {
                        $(".resultbox").hide();
                        var value = $(this).val();
                        if (value.length > 2)
                        {
                            $.ajax({
                                url: "search.php",
                                method: "POST",
                                data: {"value": value, "intellisearch": 1, "token": "{/literal}{$csrfToken}{literal}"},
                                success: function (data)
                                {
                                    $(".resultbox").show();
                                    $("#searchresultsscroller").html(data);
                                    $("#searchresults").slideDown("slow", function () {

                                    });
                                }
                            });
                        }
                    });


                });
    </script>
{/literal}
</body>
</html>