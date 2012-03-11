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
    
    // jQuery autocomplet function to return schools.
    if (typeof schools != 'undefined')
    {
        jQuery( "#input_schools" ).autocomplete({
            source: schools,
            select: function(event, ui) {
                jQuery('#input_schools').val(ui.item.label);
                jQuery('#input_sid').val(ui.item.value);
                return false;
            },
            close: function(event, ui) {
                var sid = jQuery("#input_sid").val();
                var mydata = "sid=" + sid;
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=schools&action=ajaxShowSchool",
                    data: mydata,
                    success: function(ret) {
                        jQuery("#contacts").html(ret); 
                    }
                });
            }
        });    
    }

    // jQuery ajax function to return the districts dropdown.
    jQuery('#input_province_id').change(function() {
        var pid = jQuery("#input_province_id").val();
        if (pid >= 1)
        {
            var mydata = "pid=" + pid;
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=schools&action=ajaxGetDistricts",
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

});