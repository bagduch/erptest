
<div class="card">
    <div class="content">
        <div clas="row">
            <form action="supporttickets.php" method="post">

                <table class="table" width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td width="15%" class="fieldlabel">Status</td>
                            <td class="fieldarea">
                                <select class="form-control" name="view">
                                    <option value="any">
                                        - Any -
                                    </option>
                                    {$statuseshtml}
                                </select>
                            </td>
                            <td width="15%" class="fieldlabel">
                                Client
                            </td>
                            <td class="fieldarea">
                                <input class="form-control" type="text" name="client" value="{$ticketfilterdata.client}" size="10">
                            </td>
                        </tr>

                        <tr>
                            <td class="fieldlabel">
                                Department
                            </td>
                            <td class="fieldarea">
                                <select class="form-control" name="deptid">
                                    <option value="">
                                        - Any -
                                    </option> 
                                    {$departmentshtml}
                                </select>
                            </td>

                            <td class="fieldlabel">
                                Ticket ID
                            </td>
                            <td class="fieldarea">
                                <input class="form-control" type="text" name="ticketid" value="{$ticketfilterdata.ticketid}" size="15">
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel">
                                Subject/Message
                            </td>
                            <td class="fieldarea">
                                <input class="form-control" type="text" name="subject" size="40" value="{$ticketfilterdata.subject}">
                            </td>
                            <td class="fieldlabel">
                                Email Address
                            </td>
                            <td class="fieldarea">
                                <input class="form-control" type="text" name="email" size="40" value="{$ticketfilterdata.email}">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <img src="images/spacer.gif" height="10" width="1"><br>
                <div align="center">
                    <input type="submit" value="Search/Filter" class="btn btn-default">
                </div>

            </form>
        </div>
    </div>
</div>

<div class="card">

    <div class="content">
        <div class="nav-tabs-navigation">
            <div class="nav-tabs-wrapper">
                <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                    <li class="active">
                        <a href="#pill1" data-toggle="tab">Your Tickets</a>
                    </li>
                    <li>
                        <a href="#pill2" data-toggle="tab">Assigned Tickets</a>
                    </li>
                    <li>
                        <a href="#pill3" data-toggle="tab">Unassigned Tickets</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="pill1">
               
                    {if $smartyvalues.yourticket}
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Your Assigned Tickets</h3>
                            </div>
                            <div class="box-body">
                                {$smartyvalues.yourticket}
                            </div>
                        </div>
                    {/if}
            </div>
            <div class="tab-pane" id="pill2">
               {if $table}
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Assigned Tickets</h3>
                            </div>
                            <div class="box-body">{$table}</div>
                        </div>
                    {/if}
            </div>
            <div class="tab-pane" id="pill3">
              {if $smartyvalues.unsignedtable}
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Unassigned Tickets</h3>
                            </div>
                            <div class="box-body">

                                {$smartyvalues.unsignedtable}

                            </div>
                        </div>
                    {/if}
            </div>
        </div>
    </div>
</div>
