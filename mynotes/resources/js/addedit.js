/* 
 * Javascript to support add edit note
 *
 * Written by Nguni Phakela nguni52@gmail.com
 * Started on: April 30, 2012, 5:51 pm
 *
 */

jQuery(function() {
    // Things to do on loading the page.
    jQuery(document).ready(function() {
        jQuery("form").submit(function() {
            if(validateForm()) {
                return true;
            } else {
                return false;
            }
        })        
    });
});

function validateForm() {
    var message = "Are you sure you want to save this note without any tags?";
    var title = jQuery("#input_title").val().trim();
    
    if(title.length == 0) {
        alert("Please enter the title");
        jQuery("#input_title").focus();
        return false;
    }
    
    var tags = jQuery("#input_tags").val().trim();
    if(tags.length == 0) {
        var answer = confirm(message);
        if (!answer){
            jQuery("#input_tags").focus();
            return false;
        }
    }
    
    // validate content from CKEditor
    /*var noteContent = jQuery(".cke_show_borders").text().trim();alert(noteContent);
    if(noteContent.length == 0) {
        alert("Please enter content for you note");
        return false;
    }*/
    
    return true;
}

