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
    
    // jQuery ajax function to return the districts dropdown.
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

    // jQuery ajax function to return the district dropdown.
    jQuery('#input_province').change(function() {
        var pid = jQuery("#input_province").val();
        if (pid >= 1)
        {
            var mydata = "pid=" + pid;
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=schools&action=ajaxManageDistricts",
                data: mydata,
                success: function(ret) {
                    jQuery("#district").html(ret); 
                }
            });
        }
        else
        {
            jQuery("#district").html(''); 
        }
    });

    // jQuery ajax function to return the add district form.
    jQuery('#adddistrict').live("click", function() {
        var pid = jQuery("#input_province").val();
        var mydata = "pid=" + pid;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxAddEditDistrict",
            data: mydata,
            success: function(ret) {
                jQuery("#adddistrictdiv").html(ret); 
                jQuery('#adddistrict').toggle();
            }
        });
    });

    // jQuery function to cancel the district form.
    jQuery('#cancel_district').live("click", function() {
        jQuery("#adddistrictdiv").html(''); 
        jQuery('#adddistrict').toggle();
        return false;
    });

    // jQuery function to submit the district form.
    jQuery('#form_district').live("submit", function() {
        var district = jQuery("#input_district_name").val();
        if (district == "")
        {
            alert(no_district);
            jQuery('#input_district_name').focus();
            return false;
        }
        else
        {
            return true;
        }
    });

    // jQuery ajax function to return the edit district form.
    jQuery('#editdistrict').live("click", function() {
        var id = jQuery(this).attr('class');
        var mydata = '&id=' + id;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxAddEditDistrict",
            data: mydata,
            success: function(ret) {
                jQuery("#adddistrictdiv").html(ret); 
                jQuery("#adddistrict").toggle(); 
            }
        });
    });

    // jQuery autocomplet function to return schools.
    jQuery( "#input_schools" ).autocomplete({
        source: schools,
        select: function(event, ui) {
            jQuery('#input_schools').val(ui.item.label);
            jQuery('#input_id').val(ui.item.value);
            return false;
        },
        close: function(event, ui) {
            var sid = jQuery("#input_id").val();
            var mydata = "sid=" + sid;
             jQuery.ajax({
                type: "POST",
                url: "index.php?module=schools&action=ajaxManageContacts",
                data: mydata,
                success: function(ret) {
                    jQuery("#contacts").html(ret); 
                }
            });
        }
    });    

    // jQuery ajax function to return the add contact form
    jQuery('#addcontact').live("click", function() {
        var sid = jQuery("#input_id").val();
        var mydata = "sid=" + sid + "&mode=add";
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxAddEditContact",
            data: mydata,
            success: function(ret) {
                jQuery("#addcontactdiv").html(ret); 
                jQuery('#input_contact_position').focus();
                jQuery('#addcontact').toggle();
            }
        });
    });

    // jQuery function to cancel the contact form.
    jQuery('#cancel_contact').live("click", function() {
        jQuery("#addcontactdiv").html(''); 
        jQuery('#addcontact').toggle();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxResetSession"
        });
        return false;
    });

    // jQuery ajax function to return the edit contact form.
    jQuery('#editcontact').live("click", function() {
        var id = jQuery(this).attr('class');
        var mydata = '&id=' + id + '&mode=edit';
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=schools&action=ajaxAddEditContact",
            data: mydata,
            success: function(ret) {
                jQuery("#addcontactdiv").html(ret); 
                jQuery('#input_contact_position').select();
                jQuery("#addcontact").toggle(); 
            }
        });
    });

});