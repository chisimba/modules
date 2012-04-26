/* 
 * Javascript to support mynotes
 *
 * Written by Nguni Phakela nguni52@gmail.com
 * STarted on: March 16, 2012, 7:33 am
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
        var viewAll = jQuery("#viewall").val();
        
        if(viewAll === undefined) {
            // Load notes into the dynamic area. These are the latest two notes
            // with the first 200 words showing
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=mynotes&action=ajaxGetNotes",
                success: function(ret) {
                    jQuery("#middledynamic_area").html(ret); 
                }
            });
        } else {
           // this is a list of all the notes that I have.
           jQuery.ajax({
                type: "POST",
                url: "index.php?module=mynotes&action=ajaxGetNotes&viewall=true",
                success: function(ret) {
                    jQuery("#middledynamic_area").html(ret); 
                }
            });
        }
    });
});

function confirmDelete() {
    message = "Are you sure you want to delete this note?";
    var answer = confirm(message);
    if (answer){
        window.location.href = jQuery("#delete").attr("href") + "&confirm=yes";
        return false; // This line added
    }
    
    return false;  
}  