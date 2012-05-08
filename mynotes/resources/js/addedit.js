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
    
    var myUrl = jQuery("#form_mynotes").attr("action");alert(myUrl);
    
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
                var mode = jQuery.getQueryString('mode');

                if(mode == "add") {
                    // change mode so that we now editing data, not adding new 
                    // data
                    tmpUrl = jQuery("#form_mynotes").attr("action");
                    tmpUrl = tmpUrl.replace("add", "edit");
                    tmpUrl += "&id=" + data;
                    jQuery("#form_mynotes").attr("action",tmpUrl);
                }
            }
        }
    });
}

/* create jQuery plugin to retrieve the param values from query string */
;(function ($) {
    $.extend({      
        getQueryString: function (name) {           
            function parseParams() {
                var params = {},
                    e,
                    a = /\+/g,  // Regex for replacing addition symbol with a space
                    r = /([^&=]+)=?([^&]*)/g,
                    d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
                    q = window.location.search.substring(1);

                while (e = r.exec(q))
                    params[d(e[1])] = d(e[2]);

                return params;
            }

            if (!this.queryStringParams)
                this.queryStringParams = parseParams(); 

            return this.queryStringParams[name];
        }
    });
})(jQuery);