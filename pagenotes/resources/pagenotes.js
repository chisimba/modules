/* 
 * Javascript to support pagenotes
 *
 * Written by Derek Keats <derek@dkeats.com>
 * STarted on: February 23, 2012, 12:06 pm
 *
 */

/**
 *
 * Put your jQuery code inside this function.
 *
 */
jQuery(function() {
    
    // Things to do on loading the page.
    jQuery(document).ready(function() {
        // Add jQuery Validation to form
        jQuery("#form_noteEditor").validate();
    });
    
    
    // Function for saving the link data
    jQuery("#form_noteEditor").submit(function(e) {
        if(jQuery("#form_noteEditor").valid()){ 
            e.preventDefault();
            jQuery("#submitNote").attr("disabled", "disabled");
            data_string = jQuery("#form_noteEditor").serialize();
            jQuery.ajax({
                url: 'index.php?module=pagenotes&action=save',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    jQuery("#submitNote").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
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