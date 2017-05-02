/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    state = jQuery("input[name='state']").val();
    jQuery.ajax({
        url: "region.php",
        method: "POST",
        data: {"region": jQuery(".country").val(), 'state': state}
    }).done(function (data) {
        if (data)
        {
            jQuery(".state").html(data);
        }
    });
    jQuery(".country").change(function () {
        jQuery.ajax({
            url: "region.php",
            method: "POST",
            data: {"region": jQuery(this).val()}
        }).done(function (data) {
            if (data)
            {
                jQuery(".state").html(data);

            }
        });
    });
});
