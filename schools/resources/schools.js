/* 
 * Javascript to support schools
 *
 * Written by Kevin Cyster kcyster@gmail.com
 * STarted on: February 29, 2012, 8:26 pm
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
    
    // jQuery ajax function to return the details dropdown.
    jQuery('#input_province_id').change(function() {
        var id = jQuery("#input_province_id").val();
        if (id >= 1)
        {
            var mydata = "id=" + id;
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=schools&action=ajaxDistricts",
                data: mydata,
                success: function(ret) {
                    jQuery("#districts").html(ret); 
                }
            });
        }
        else
        {
            jQuery("#districts").html(''); 
        }
    });
    
    // jQuery ajax function to check the username.
    jQuery('#input_username').blur(function() {
        var username = jQuery('#input_username').val();
        var mydata = "username=" + username;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxUsername",
            data: mydata,
            success: function(ret) {
                jQuery("#username").html(ret);
                if (jQuery("#username > span").hasClass("error"))
                {
                    jQuery('#input_username').select();
                }
            }
        });
    });

    // jQuery function to check if the passwords match.
    jQuery('#input_confirm_password').blur(function() {
        var password = jQuery('#input_password').val();
        var confirm = jQuery('#input_confirm_password').val();
        if (password != confirm)
        {
            alert(password_not_alike);
            jQuery('#input_confirm_password').val('');
            jQuery('#input_password').select();
        }
    });

});