$(document).ready(function () {

    $("#replymessage").focus(function () {
        $.post("supporttickets.php", {action: "makingreply", id: ticketid, token: csrfToken},
        function (data) {
            $("#replyingadmin").html(data);
        });
        return false;
    });

    $("#replyfrm").submit(function () {
        var status = $("#ticketstatus").val();
        var response = $.ajax({
            type: "POST",
            url: "supporttickets.php?action=checkstatus",
            data: "id=" + ticketid + "&ticketstatus=" + status + "&token=" + csrfToken,
            async: false
        }).responseText;
        if (response == "true") {
            return true;
        } else {
            if (confirm(langstatuschanged + "\n\n" + langstillsubmit)) {
                return true;
            }
            return false;
        }
    });

    var currentTags = '';
    if ($('#ticketTags').length) {
        $('#ticketTags').textext({
            plugins: 'tags prompt focus autocomplete ajax',
            prompt: 'Add one...',
            tagsItems: ticketTags,
            ajax: {
                url: 'supporttickets.php?action=gettags',
                data: 'token=' + csrfToken,
                dataType: 'json',
                cacheResults: true
            }
        }).bind('setFormData', function (e, data, isEmpty) {
            var newTags = $(e.target).textext()[0].hiddenInput().val();
            if (newTags != currentTags) {
                $.post("supporttickets.php", {action: "savetags", id: ticketid, tags: newTags, token: csrfToken});
                currentTags = newTags;
            }
        });
    }

//    $(window).unload(function () {
//        $.post("supporttickets.php", {action: "endreply", id: ticketid, token: csrfToken});
//    });
    $("#insertpredef").click(function () {
        $("#prerepliescontainer").fadeToggle();
        return false;
    });
    $("#addfileupload").click(function () {
        $("#fileuploads").append("<input type=\"file\" name=\"attachments[]\" size=\"85\"><br />");
        return false;
    });
    $("#ticketstatus").change(function () {
        $.post("supporttickets.php", {action: "changestatus", id: ticketid, status: this.options[this.selectedIndex].text, token: csrfToken});
    });

    $("#flagto").change(function () {
        $.post("supporttickets.php", {action: "changeflag", id: ticketid, flag: $("#flagto").val(), token: csrfToken});
    });
    $("#predefq").keyup(function () {
        var intellisearchlength = $("#predefq").val().length;
        if (intellisearchlength > 2) {
            $.post("supporttickets.php", {action: "loadpredefinedreplies", predefq: $("#predefq").val(), token: csrfToken},
            function (data) {
                $("#prerepliescontent").html(data);
            });
        }
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

});

function doDeleteReply(id) {
    if (confirm(langdelreplysure)) {
        window.location = 'supporttickets.php?action=viewticket&id=' + ticketid + '&sub=del&idsd=' + id + '&token=' + csrfToken;
    }
}
function doDeleteTicket() {
    if (confirm(langdelticketsure)) {
        window.location = 'supporttickets.php?sub=deleteticket&id=' + ticketid + '&token=' + csrfToken;
    }
}
function doDeleteNote(id) {
    if (confirm(langdelnotesure)) {
        window.location = 'supporttickets.php?action=viewticket&id=' + ticketid + '&sub=delnote&idsd=' + id + '&token=' + csrfToken;
    }
}
function loadTab(target, type, offset) {
    $.post("supporttickets.php", {action: "get" + type, id: ticketid, userid: userid, target: target, offset: offset, token: csrfToken},
    function (data) {
        $("#tab" + target + "box #tab_content").html(data);
    });
}
function expandRelServices() {
    $("#relatedservicesexpand").html('<img src="images/loading.gif" align="top" /> ' + langloading);
    $.post("supporttickets.php", {action: "getallservices", id: ticketid, userid: userid, token: csrfToken},
    function (data) {
        $("#relatedservicestbl").append(data);
        $("#relatedservicesexpand").fadeOut();
    });
}
function updateTicket(val) {
    $.post("supporttickets.php", {action: "viewticket", id: ticketid, updateticket: val, value: $("#" + val).val(), token: csrfToken});
}
function editTicket(id) {
    $(".editbtns" + id).toggle();
    $("#content" + id + " div.message").hide();
    $("#content" + id + " div.message").after('<textarea rows="15" style="width:99%" id="ticketedit' + id + '">' + langloading + '</textarea>');
    $.post("supporttickets.php", {action: "getmsg", ref: id, token: csrfToken},
    function (data) {
        $("#ticketedit" + id).val(data);
    });
}
function editTicketCancel(id) {
    $("#ticketedit" + id).hide();
    $("#content" + id + " div.message").show();
    $(".editbtns" + id).toggle();
}
function editTicketSave(id) {
    $("#ticketedit" + id).hide();
    $("#content" + id + " div.message").show();
    $(".editbtns" + id).toggle();
    $.post("supporttickets.php", {action: "updatereply", ref: id, text: $("#ticketedit" + id).val(), token: csrfToken},
    function (data) {
        $("#content" + id + " div.message").html(data);
    });
}

function editNotes(id)
{
    if ($("#notes" + id + " div.message").is(":visible"))
    {
        $(".editbtnotes" + id).hide();
        $(".editbtnotess" + id).show();
        text = $("#notes" + id + " div.message").html();
        $("#notes" + id + " div.message").hide();
        $("#notes" + id + " div.message").after('<textarea rows="3" style="width:99%" id="noteedit' + id + '">' + text + '</textarea>');
    }
}
function saveNotes(id)
{
    $.post("supporttickets.php", {action: "updnote", noteid: id, msg: $("#noteedit" + id).val(), token: csrfToken},
    function (data) {
        $("#notes" + id + " div.message").html(data);
        cancelNotes(id);
    });


}
function deleteNotes(id)
{
    $.post("supporttickets.php", {action: "delnote", noteid: id, token: csrfToken},
    function () {
        $("#not" + id).remove();
    });

}
function cancelNotes(id)
{
    $(".editbtnotes" + id).show();
    $(".editbtnotess" + id).hide();
    $("#notes" + id + " div.message").show();
    $("#noteedit" + id).hide();
}
function quoteTicket(id, ids) {
    $(".tab").removeClass("tabselected");
    $("#tab0").addClass("tabselected");
    $(".tabbox").hide();
    $("#tab0box").show();
    $.post("supporttickets.php", {action: "getquotedtext", id: id, ids: ids, token: csrfToken},
    function (data) {
        $("#replymessage").val(data + "\n\n" + $("#replymessage").val());
    });
    return false;
}
function selectpredefcat(catid) {
    $.post("supporttickets.php", {action: "loadpredefinedreplies", cat: catid, token: csrfToken},
    function (data) {
        $("#prerepliescontent").html(data);
    });
}
function selectpredefreply(artid) {
    $.post("supporttickets.php", {action: "getpredefinedreply", id: artid, token: csrfToken},
    function (data) {
        $("#replymessage").addToReply(data);
    });
    $("#prerepliescontainer").fadeOut();
}
function searchselectclient(userid) {
    $("#clientsearchval").val(userid);
    $("#ticketclientsearchresults").slideUp("slow");
    $("#clientsearchcancel").fadeOut();
}
