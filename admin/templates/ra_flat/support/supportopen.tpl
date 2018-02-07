<div class="box">
    <div class="box-body">
        <form method="post" action="/admin/supporttickets.php?action=openticket" enctype="multipart/form-data">

            <div class="col-lg-6">
                <table class="form" width="100%"  border="0" cellspacing="2" cellpadding="3">
                    <tr>
                        <td width="200" class="fieldlabel">To:</td>
                        <td class="fieldarea">   <input type="hidden" name="client" id="clientinput" value=""> <input class="form-control" type="text" name="name" id="name" size="40" value=""></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Email Address</td>
                        <td class="fieldarea">
                            <div style="padding-left: 0px" class="col-xs-12 col-md-8">
                                <input class="form-control" type="text" name="email" id="email" size="50" value=""> 
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <label style="margin-top: 5px;"><input class="flat-red" type="checkbox" name="sendemail" checked=""> Send Email</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Department</td>
                        <td class="fieldarea">
                            <select class="form-control" name="deptid">
                                <option value="">Any</option>
                                {$depidoption}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Priority</td>
                        <td class="fieldarea"><select class="form-control" name="priority"><option>High</option><option selected="">Medium</option><option>Low</option></select></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="form" width="100%"  border="0" cellspacing="2" cellpadding="3">
                    <tr>
                        <td width="200" class="fieldlabel">Client Search:</td>
                        <td class="fieldarea"><input class="form-control" type="text" id="clientsearchval" size="15"> <img src="images/icons/delete.png" alt="Cancel" class="absmiddle" id="clientsearchcancel" height="16" width="16"></td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">CC Recipients</td>
                        <td class="fieldarea">
                            <div style="padding-left: 0px" class="col-xs-12 col-md-8">
                                <input class="form-control" type="text" name="ccemail" value="" size="50">
                            </div>
                            <div style="margin-top: 5px;" class="col-xs-6 col-md-4">(Comma Separated)
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldlabel">Subject</td>
                        <td class="fieldarea"><input class="form-control" type="text" name="subject" size="75" value=""></td>
                    </tr>
                </table>

            </div>
            <div class="clearfix"></div>
            <br>
            <textarea name="message" id="replymessage" class="textarea" placeholder="Reply here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
            <br>
            <div id="insertlinks">
                <div align="center">
                    <a class="btn btn-default" href="#" onclick="window.open('supportticketskbarticle.php', 'kbartwnd', 'width=500,height=400,scrollbars=yes');
                            return false">Insert Knowledgebase Link</a>
                    <a class="btn btn-default" href="#" onclick="loadpredef('0');
                            return false">Insert Predefined Reply</a>
                </div>
            </div>
            <br>
            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr>
                        <td width="15%" class="fieldlabel">Attachments</td>
                        <td class="fieldarea"><input type="file" name="attachments[]" size="85"> 
                            <br>
                            <a class="btn btn-default" href="#" id="addfileupload"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add More</a>
                            <br>
                            <div id="fileuploads"></div>
                        </td>
                    </tr>
                </tbody></table>
            <div id="prerepliescontainer" style="display:none;">
                <img src="images/spacer.gif" height="8" width="1">
                <br>
                <div style="border:1px solid #DFDCCE;background-color:#F7F7F2;padding:5px;text-align:left;">
                    <div style="float:right;">Search: <input type="text" id="predefq" size="25"></div>
                    <div id="prerepliescontent"></div>
                </div>
            </div>
            <img src="images/spacer.gif" height="8" width="1"><br>
            <div align="center"><input type="submit" value="Open Ticket" class="button"></div>
        </form>
    </div>
</div>
<script src="templates/{$template}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
{literal}
    <script type="text/javascript">
                        $(".textarea").wysihtml5();
                        function insertKBLink(url) {
                            $("#replymessage").addToReply(url);
                        }
                        function selectpredefcat(catid) {
                            $.post("supporttickets.php", {action: "loadpredefinedreplies", cat: catid, token: "{/literal}{$csrfToken}{literal}"},
                            function (data) {
                                $("#prerepliescontent").html(data);
                            });
                        }
                        function loadpredef(catid) {
                            $("#prerepliescontainer").slideToggle();
                            $("#prerepliescontent").html('<img src="images/loading.gif" align="top" /> Loading...');
                            $.post("supporttickets.php", {action: "loadpredefinedreplies", cat: catid, token: "{/literal}{$csrfToken}{literal}"},
                            function (data) {
                                $("#prerepliescontent").html(data);
                            });
                        }
                        function selectpredefreply(artid) {
                            $.post("supporttickets.php", {action: "getpredefinedreply", id: artid, token: "{/literal}{$csrfToken}{literal}"},
                            function (data) {
                                $("#replymessage").addToReply(data);
                            });
                            $("#prerepliescontainer").slideToggle();
                        }
                        function searchselectclient(userid, name, email) {
                            $("#clientsearchval").val("");
                            $("#clientinput").val(userid);
                            $("#name").val(name);
                            $("#email").val(email);
                            $("#ticketclientsearchresults").slideUp("slow");
                            $("#clientsearchcancel").fadeOut();
                            $.post("supporttickets.php", {action: "getcontacts", userid: userid, token: "{/literal}{$csrfToken}{literal}"},
                            function (data) {
                                if (data) {
                                    $("#contacthtml").html(data);
                                    $("#contactrow").show();
                                } else {
                                    $("#contactrow").hide();
                                }
                            });
                        }
                        (function () {
                            var fieldSelection = {
                                addToReply: function () {
                                    var e = this.jquery ? this[0] : this;
                                    var text = arguments[0] || '';
                                    return (
                                            ('selectionStart' in e && function () {
                                                if (e.value == "\n\n") {
                                                    e.selectionStart = 0;
                                                    e.selectionEnd = 0;
                                                }
                                                e.value = e.value.substr(0, e.selectionStart) + text + e.value.substr(e.selectionEnd, e.value.length);
                                                e.focus();
                                                return this;
                                            }) ||
                                            (document.selection && function () {
                                                e.focus();
                                                document.selection.createRange().text = text;
                                                return this;
                                            }) ||
                                            function () {
                                                e.value += text;
                                                return this;
                                            }
                                    )();
                                }
                            };
                            jQuery.each(fieldSelection, function (i) {
                                jQuery.fn[i] = this;
                            });
                        })();
                        $("#addfileupload").click(function () {
                            $("#fileuploads").append("<input type=\"file\" name=\"attachments[]\" size=\"85\"><br />");
                            return false;
                        });
                        $("#clientsearchval").keyup(function () {
                            var ticketuseridsearchlength = $("#clientsearchval").val().length;
                            if (ticketuseridsearchlength > 2) {
                                $.post("search.php", {ticketclientsearch: 1, value: $("#clientsearchval").val()},
                                function (data) {
                                    if (data) {
                                        $("#ticketclientsearchresults").html(data);
                                        $("#ticketclientsearchresults").slideDown("slow");
                                        $("#clientsearchcancel").fadeIn();
                                    }
                                });
                            }
                        });
                        $("#clientsearchcancel").click(function () {
                            $("#ticketclientsearchresults").slideUp("slow");
                            $("#clientsearchcancel").fadeOut();
                        });
                        $("#predefq").keyup(function () {
                            var intellisearchlength = $("#predefq").val().length;
                            if (intellisearchlength > 2) {
                                $.post("supporttickets.php", {action: "loadpredefinedreplies", predefq: $("#predefq").val(), token: "{/literal}{$csrfToken}{literal}"},
                                function (data) {
                                    $("#prerepliescontent").html(data);
                                });
                            }
                        });


                        function searchclose() {
                            $("#searchresults").slideUp();
                        }
                        $(document).ready(function () {


                            $("#intellisearchval").keyup(function () {
                                var value = $(this).val();
                                if (value.length > 2)
                                {
                                    $.ajax({
                                        url: "search.php",
                                        method: "POST",
                                        data: {"value": value, "intellisearch": 1, "token": "{/literal}{$csrfToken}{literal}"},
                                        success: function (data)
                                        {
                                            $("#searchresultsscroller").html(data);
                                            $("#searchresults").slideDown("slow", function () {

                                            });
                                        }
                                    });
                                }
                            });

                            //iCheck for checkbox and radio inputs
                            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                                checkboxClass: 'icheckbox_minimal-blue',
                                radioClass: 'iradio_minimal-blue'
                            });
                            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                                checkboxClass: 'icheckbox_flat-green',
                                radioClass: 'iradio_flat-green'
                            });

                            $('.datepick').datepicker({
                                autoclose: true,
                                format: 'dd/mm/yyyy',
                            });
                            $("[data-mask]").inputmask();

                        });
    </script>
{/literal}