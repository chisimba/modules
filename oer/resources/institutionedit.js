/* 
 * Javascript to support institution edit
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
        jQuery("#form_institutionEditor").validate();
    });

    // Function for saving the institutional data
    jQuery("#form_institutionEditor").submit(function(e) {
        if(jQuery("#form_institutionEditor").valid()){ 
            e.preventDefault();
            jQuery("#submitInstitution").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_institutionEditor").serialize();
            jQuery.ajax({
                url: 'index.php?module=oer&action=institutionsave',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    jQuery("#submitInstitution").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        jQuery("#save_results").html('<span class="success">' + status_success + msg + '</span>');
                    } else {
                        //alert(msg);
                        alert(status_fail);
                    }
                }
            });
        }
    });
});