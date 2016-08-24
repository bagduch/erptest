
<div class="intellisearch">
    IntelliSearch WHMCS<form id="frmintellisearch">
        <input type="hidden" name="intellisearch" value="1" />
        <input type="hidden" name="token" value="{$csrfToken}" />
        <input type="text" name="value" id="intellisearchval" autocomplete="off"/>
        <input type="submit" style="display:none;">
    </form>
</div>
<div id="searchresults">
    <div id="searchresultsscroller"></div>
    <div class="close"><a href="#" onclick="searchclose();
          return false">{$_ADMINLANG.clientsummary.close} <img src="images/delete.gif" width="16" height="16" border="0" align="top" /></a></div>
</div>
<div id="greyout"></div>


{$footeroutput}
{literal} 
    <script type="text/javascript">
        jQuery(function ($) {
            var performance = [12, 43, 34, 22, 12, 33, 4, 17, 22, 34, 54, 67],
                    visits = [123, 323, 443, 32],
                    budget = [23, 19, 11, 134, 242, 352, 435, 22, 637, 445, 555, 57],
                    sales = [11, 9, 31, 34, 42, 52, 35, 22, 37, 45, 55, 57];

            $("#shieldui-chart1").shieldChart({
                primaryHeader: {
                    text: "Visitors"
                },
                exportOptions: {
                    image: false,
                    print: false
                },
                dataSeries: [{
                        seriesType: "area",
                        collectionAlias: "Q Data",
                        data: performance
                    }]
            });

            $("#shieldui-chart2").shieldChart({
                primaryHeader: {
                    text: "Logins Per week"
                },
                exportOptions: {
                    image: false,
                    print: false
                },
                seriesSettings: {
                    donut: {
                        enablePointSelection: true
                    }
                },
                dataSeries: [{
                        seriesType: "donut",
                        collectionAlias: "logins",
                        data: visits
                    }]
            });

            $("#shieldui-chart3").shieldChart({
                primaryHeader: {
                    text: "Budget"
                },
                dataSeries: [{
                        seriesType: "line",
                        collectionAlias: "Budget",
                        data: budget
                    }]
            });

            $("#shieldui-chart4").shieldChart({
                primaryHeader: {
                    text: "Sales"
                },
                dataSeries: [{
                        seriesType: "bar",
                        collectionAlias: "sales",
                        data: sales
                    }]
            });

            $("#shieldui-grid1").shieldGrid({
                dataSource: {
                    data: gridData
                },
                sorting: {
                    multiple: true
                },
                paging: {
                    pageSize: 12,
                    pageLinksCount: 4
                },
                selection: {
                    type: "row",
                    multiple: true,
                    toggle: false
                },
                columns: [
                    {field: "id", width: "70px", title: "ID"},
                    {field: "name", title: "Person Name"},
                    {field: "company", title: "Company Name"},
                    {field: "email", title: "Email Address", width: "270px"}
                ]
            });
        });
    </script> 
{/literal}
        </div>
<div class="lastfooter">
    <div class="right"><a href="#">Top</a></div>
    <div class="left">Copyright &copy; <a href="#" target="_blank">Robotic Accounting</a>.  All Rights Reserved.</div>
</div>
</body>
</html>
