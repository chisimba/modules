/* 
 * Javascript to support group edit
 *
 * @author Derek Keats derek@dkeats.com
 * @author Paul Mungai paulwando@gmail.com
 * Started on: December 18, 2011, 8:48 am
 *
 *
 */

/**
 *
 * jQuery code belongs inside this function.
 *
 */
jQuery(function() {

    // Things to do on loading the page.
    jQuery(document).ready(function() {
        // Add jQuery Validation to form
        jQuery("#form_downloadereditor").validate();
    });

    // Function for saving the downloader data
    jQuery("#form_downloadereditor").submit(function(e) {
        if(jQuery("#form_downloadereditor").valid()){
            e.preventDefault();
            jQuery("#submit").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_downloadereditor").serialize();
            
            jQuery.ajax({
                url: 'index.php?module=oer&action=downloadersave',
                type: "POST",
                data: data_string,
                success: function(msg) {                    
                    jQuery("#submit").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
                        jQuery("#id").val(msg);
                        jQuery("#mode").val('edit');
                    } else {
                        //alert(msg);
                        alert(status_fail);
                    }
                }
            });
        }
    });
});