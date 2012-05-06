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
        config.removePlugins = 'save';
        jQuery('textarea.editor').val( 'my new content' );
        CKEDITOR.instances.NoteContent.setData("<p>Hello World</p>");
        editor_data = CKEDITOR.instances.NoteContent.getData();
        alert(editor_data);
        
        var options = {
            target: "#responsearea",
            beforeSubmit: showRequest,
            success: showResponse
        }
        
        // bind form using 'ajaxForm' 
        jQuery('#form_mynotes').ajaxForm(options); 
        
    /*jQuery("form").submit(function() {
            validateForm();
            ajaxSubmitForm();
            
            return false;
            
            /*if(validateForm()) {
                return true;
            } else {
                return false;
            }
        })*/     
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
    
//return true;
}

function ajaxSubmitForm() {
    var action = jQuery( "#form_mynotes").attr("action");
    alert("The Form href attribute" + action);
    
    var data = jQuery('textarea.editor').val();
    alert("CK editor data: " + data);
//jQuery.post(action, jQuery( "#form_mynotes").serialize());
}

// pre-submit callback 
function showRequest(formData, jqForm, options) {
    var editor_data = CKEDITOR.instances.NoteContent.getData();
    alert(editor_data);
    
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    if ( formData['NoteContent'] == '' ) {
        // formData is an array; here we use $.param to convert it to a string to display it 
        // but the form plugin does this for you automatically when it submits the data 
        //var queryString = jQuery.param(formData); 
 
        alert( 'There is no data available' );
        return false;
    } else {
        //alert('About to submit: \n\n' + queryString); 
        return true; 
    }
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
        '\n\nThe output div should have already been updated with the responseText.'); 
} 