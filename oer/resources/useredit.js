/* 
 * Javascript to support user edit
 *
 * Written by Derek Keats derek@dkeats.com
 * Started on: December 18, 2011, 8:48 am
 *
 *
 */


var data_string;


/**
 *
 * jQuery code belongs inside this function.
 *
 */
jQuery(function() {

    // Things to do on loading the page.
    jQuery(document).ready(function() {
        // Add jQuery Validation to form
        jQuery("#form_edituser").validate();
    });

    // Function for saving the institutional data
    jQuery("#form_edituser").submit(function(e) {
        if(jQuery("#form_edituser").valid()){ 
            e.preventDefault();
            jQuery("#submitUser").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_edituser").serialize();
            jQuery.ajax({
                url: 'index.php?module=oer&action=userdetailssave',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    jQuery("#submitUser").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
                        jQuery("#id").val(msg);
                        jQuery("#mode").val('edit');
                    } else {
                        //alert(msg);
                        jQuery("#save_results").html('<span class="error">' + status_fail + ": " + msg + '</span>');
                    }
                }
            });
        }
    });
});