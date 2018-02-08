/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {

    state = $("input[name='state']").val();
    if (typeof (state) != 'undefined')
    {
        $.ajax({
            url: "region.php",
            method: "POST",
            data: {"region": $(".country").val(), 'state': state}
        }).done(function (data) {
            if (data)
            {
                $(".region").html(data);
            }
        });
    }
    $(".country").change(function () {
        $.ajax({
            url: "region.php",
            method: "POST",
            data: {"region": $(this).val()}
        }).done(function (data) {
            if (data)
            {
                $(".region").html(data);

            }
        });
    });

    $(".notesdone").click(function () {
        var notes = $("textarea[name='notesdata']").val();
        var assign = $("input[name='assign']").val();
        var duedate = $("input[name='updatetime']").val();
        if ($("input[name='finishtask']").is(":checked"))
        {
            var done = "1";
        }
        else {
            done = "0";
        }
        var id = $("input[name='noteid']").val();
        $.ajax({
            url: "/admin/clientsnotes.php",
            method: "post",
            data: {
                "id": id,
                "update": 1,
                "notes": notes,
                "assign": assign,
                "duedate": duedate,
                "done": done
            }}).done(function () {
            location.reload();
        });
    });
});