/* 
 * Javascript to support original product creation
 *
 * Written by David Wafula
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
        jQuery("#form_originalProductForm1").validate();
        jQuery("#form_originalProductForm2").validate();
        jQuery("#form_originalProductForm3").validate();
        jQuery("#form_originalProductForm4").validate();
        
        
        jQuery('ul#nav-secondary li').click(function() {
            jQuery(this).html ('<img src="skins/_common/icons/loading_bar.gif" alt=""Loading..." />');
          
        });
        
        
    });
    
    
    
    jQuery("a[class=deleteoriginalproduct]").click(function(){

        var r=confirm(confirm_delete_original_product);
        if (r==true){
            var link = this.href;

            window.location=link;
        }


        return false;
    });
    
    

    
    
    // Function for saving the institutional data
    jQuery("#form_originalProductForm").submit(function(e) {
        if(jQuery("#form_originalProductForm").valid()){ 
           
            e.preventDefault();
            jQuery("#submitInstitution").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_originalProductForm").serialize();
            jQuery.ajax({
                url: 'index.php?module=oer&action=saveoriginalproduct',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    jQuery("#submitInstitution").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
                        jQuery("#id").val(msg);
                        jQuery("#mode").val('edit');
                        alert("relocating....");
                        window.location="?module=oer&action=editoriginalproductstep5";
                    } else {
                        //alert(msg);
                        alert(status_fail);
                    }
                }
            });
        }
    });

});

/**
 * Submits step 1 details
 */
function saveStep1(){
    if(jQuery("#form_originalProductForm1").valid()){ 
        jQuery("#saveStep1Button").attr("disabled", "disabled");
        jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt="'+loading+'" />');
        jQuery("#form_originalProductForm1").submit();
    }
}
    

/**
 * Submits step 2 details
 */
function saveStep2(){
    if(jQuery("#form_originalProductForm2").valid()){ 
        jQuery("#saveStep2Button").attr("disabled", "disabled");
        jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt="'+loading+'" />');
        jQuery("#form_originalProductForm2").submit();
    }
}
    


/**
 * Submits step 3 details
 */
function saveStep3(){
    if(jQuery("#form_originalProductForm3").valid()){ 
        jQuery("#saveStep3Button").attr("disabled", "disabled");
        jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt="'+loading+'" />');
        jQuery("#form_originalProductForm3").submit();
    }
}
    

/**
 * Submits step 4 details
 */
function saveStep4(){
    if(jQuery("#form_originalProductForm4").valid()){ 
        jQuery("#saveStep1Button").attr("disabled", "disabled");
        jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt="'+loading+'" />');
        jQuery("#form_originalProductForm4").submit();
    }
}
        
    