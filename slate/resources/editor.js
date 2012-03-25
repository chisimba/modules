/* 
 * Javascript to support slate page editor
 *
 * Written by Derek Keats derek@dkeats.com
 * STarted on: January 4, 2012, 3:53 pm
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
        jQuery("#form_slatepageEditor").validate();
    });
    
    // Function for saving the page data
    jQuery("#form_slatepageEditor").submit(function(e) {
        if(jQuery("#form_slatepageEditor").valid()){ 
            e.preventDefault();
            jQuery("#savePage").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_slatepageEditor").serialize();
            jQuery.ajax({
                url: 'index.php?module=slate&action=pagesave',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    jQuery("#savePage").attr("disabled", "");
                    alert(msg);
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
                        jQuery("#id").val(msg);
                        jQuery("#mode").val('edit');
                        jQuery(".conditional_add").css('visibility', 'visible');
                    } else {
                        //alert(msg);
                        jQuery("#save_results").html('<span class="error">' + status_fail + ": " + msg + '</span>');
                    }
                }
            });
        }
    });

});