/* 
 * Javascript to support mynotes
 *
 * Written by Nguni Phakela nguni52@gmail.com
 * STarted on: March 16, 2012, 7:33 am
 *
 */

jQuery(function() {
     
    // Things to do on loading the page.
    jQuery(document).ready(function() {
        var viewAll = jQuery("#viewall").val();
        var nextPage = jQuery("#nextpage").val();
        var prevPage = jQuery("#prevpage").val();
        
        if(viewAll === undefined) {
            // Load notes into the dynamic area. These are the latest two notes
            // with the first 200 words showing
            if(isNaN(nextPage)) {
                nextPage="7";
            } else {
                nextPage =parseInt(nextPage) + 6;
            }
            if(isNaN(prevPage)) {
                if(!isNaN(nextPage)) {
                    prevPage = parseInt(nextPage)-5;
                } else {
                    prevPage="2";
                }
            } else {
                nextPage = prevPage-1;
                prevPage = parseInt(prevPage) - 6;
            }
            
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=mynotes&action=ajaxGetNotes",
                data: "nextnotepage="+nextPage+"&prevnotepage="+ prevPage,
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