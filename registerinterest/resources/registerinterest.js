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
        //hide the textboxes
        });
    
    // Save the intrest
    jQuery("#ri_save_button").live("click", function(e){
        e.preventDefault();
        data_string = jQuery("#form_edituser").serialize();
        jQuery.ajax({
            url: 'index.php?module=registerinterest&action=save',
            type: "POST",
            data: data_string,
            success: function(msg) {
                var msg;
                var fullName =  jQuery("#input_fullname").val();
                var email =  jQuery("#input_email").val();
                if(jQuery.trim(fullName).length != 0){
                    if(fullName.length > 1){
                        if(jQuery.trim(email).length != 0){
                            if(email.indexOf('@') != -1 && email.length > 2){
                                msg = "<span class='success' >Thank you for registering your interest.</span>";
                                jQuery("#input_email").val('');
                                jQuery("#input_fullname").val('');
                            }else{
                                msg = "<span class='error' >Enter valid email address</span>";
                            }
                        }else{
                            msg = "<span class='error' >Enter valid email address</span>";
                            jQuery("#input_fullname").html(fullName);
                        }
                    }else{
                        msg = "<span class='error' >Enter valid full names</span>";
                    }
                }else{
                    msg = "<span class='error' >Enter valid full names</span>";
                    jQuery("#input_email").html(email);
                }
                jQuery("#ri_save_button").attr("disabled", "");
                jQuery("#before_riform").html(msg);
            }
        });
    });
    
    //edit values on the list
    jQuery("label[for='interestEmail']").click(function(){
        var value = jQuery(this).attr('value');
        var textBox = jQuery("<input type='text' class='newvalue' />");
        jQuery(textBox).val(value);
        jQuery(this).html(textBox);
        jQuery(textBox).focus();
    });
    
    //send update request when the textbox looses focus
    jQuery("label[for='interestEmail']").focusout(function(){
        var id = jQuery(this).attr('id');
        var newValue = jQuery("input[class='newvalue'] ").val();
        //send the value
        jQuery.ajax({
            url: 'index.php?module=registerinterest&action=update',
            type: 'POST',
            data: {
                'newValue': newValue,
                'id':id
            },
            success: function(){
                jQuery("#before_riform").html("<span class='success' >Update request sent successfully</span>");
                setTimeout(function(){
                    jQuery("#before_riform").text('')
                },5000)
            }
        })
        jQuery(this).html(newValue);
        jQuery(this).attr('value',newValue);
    });
    
    // Send the message
    jQuery("#ri_savemsg_button").live("click", function(e){
        var noSubject = false;
        //alert("Not ready yet");
        if(jQuery("#input_subject").val().length == 0){
            if(confirm("Are you sure you want to send the email without a subject?")){
                noSubject = true;
            }else{
                //e.preventDefault();
                noSubject = false;
                jQuery("#input_subject").focus();
            }
        }else{
            noSubject = true;
        }
        data_string = jQuery("#form_editmsg").serialize();
        if(noSubject){
            jQuery(this).attr('disabled',true);
            jQuery("#form_editmsg").hide('fast');
            var span = document.createElement('span');
            jQuery(span).attr('id','ri_paragraph');
            jQuery(span).text('Please wait....');
            jQuery("#riwritemsg").append(span);

            jQuery.ajax({
                url: 'index.php?module=registerinterest&action=sendmessage',
                type: "POST",
                data: data_string,
                success: function() {
                    jQuery("#ri_savemsg_button").attr("disabled", "");
                    jQuery("#before_riform").html('<span class="success">Message send.</span>');
    
                }
            });
        };
    });
    
});