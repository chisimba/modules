/* 
 * Javascript to support add/edit note
 *
 * Written by Nguni Phakela nguni52@gmail.com
 * Started on: April 30, 2012, 5:51 pm
 *
 */

jQuery(function() {
    // Things to do on loading the page.
    jQuery(document).ready(function() {
        jQuery("#responsearea").hide();
    });
});


function checkFormSubmit() {
    // update ckeditor elements
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();

    validateForm();
}

function validateForm() {
    var message = "Are you sure you want to save this note without any tags?";
    var myTitle = jQuery("#input_title").val().trim();
    
    if(myTitle.length == 0) {
        alert("Please enter the title");
        jQuery("#input_title").focus();
        return false;
    }
    
    var myTags = jQuery("#input_tags").val().trim();
    if(myTags.length == 0) {
        var answer = confirm(message);
        if (!answer){
            jQuery("#input_tags").focus();
            return false;
        }
    }
    
    var myContent = CKEDITOR.instances.NoteContent.getData().trim();
    alert(myContent);
    if(myContent.length == 0) {
        alert("Please enter content");
        return false;
    }
    
    var myUrl = jQuery("#form_mynotes").attr("action");
    
    var myId = jQuery("#input_id").val();
    
    // ajax submit form
    jQuery.ajax({
        type: "POST",
        url: myUrl,
        data: {
            title: myTitle, 
            tags: myTags, 
            content: myContent, 
            id: myId
        },
        success: function(data) {
            if(data == "FALSE") {
                jQuery("#responsearea").fadeIn("Error submitting data");
                alert("Error submitting data");
            } else {
                jQuery("#responsearea").text("Data saved Successfully");
                jQuery("#responsearea").show();
                jQuery('#responsearea').fadeOut('slow');
                
                jQuery("#input_id").val(data);
                if(myUrl.has("add")) {
                    // change mode so that we now editing data, not adding new 
                    // data
                    jQuery("#form_mynotes").attr("action").replace("add", "edit");
                }
            }
        }
    });
}