/* 
 * Javascript to support registerinterest
 *
 * Written by Derek Keats derek@dkeats.com
 * STarted on: November 28, 2012, 8:24 am
 *
 * The following parameters need to be set in the
 * PHP code for this to work:
 *
 * @todo
 *   List your parameters here so you won't forget to add them
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
    });
    
    // Toggle the wall
    jQuery("#ri_save_button").live("click", function(e){
        e.preventDefault();
        data_string = jQuery("#form_edituser").serialize();
        jQuery.ajax({
            url: 'index.php?module=registerinterest&action=save',
            type: "POST",
            data: data_string,
            success: function(msg) {
                jQuery("#ri_save_button").attr("disabled", "");
                jQuery("#before_riform").html('<span class="success">Thank you for registering your interest.</span>');
                jQuery("#input_fullname").val('');
                jQuery("#input_email").val('');
            }
        });
    });

});