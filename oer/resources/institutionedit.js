/* 
 * Javascript to support institution edit
 *
 * Written by Derek Keats derek@dkeats.com
 * Started on: December 18, 2011, 8:48 am
 *
 *
 */


var status_success;
var status_fail;
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
        //jQuery("#form_institutionEditor").validate();
    });

    // Function for saving the institutional data
    jQuery("#submitInstitution").live("click", function(){
        alert("CLICKED");
        //return;
        //jQuery("#submitInstitution").attr("disabled", "disabled");
        jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
        status_success = 'Institution data saved.';
        status_fail = 'Institution data was not saved due to an unknown error.';
        data_string = '';
        jQuery.ajax({
            url: 'module=oer&action=institutionsave',
            type: "POST",
            //data: data_string,
            success: function(msg) {
                jQuery("#submitInstitution").attr("disabled", NULL);
                if(msg !== "ERROR_DATA_INSERT_FAIL") {
                    alert('Record saved with id ' + msg);
                    jQuery("#save_results").html('Record saved with id ' + msg);
                } else {
                    alert(msg);
                    //alert("Cannot be posted at the moment! Please try again later.");
                }
            }
        });
    });
});