/* 
 * Javascript to support user edit
 *
 * Written by Derek Keats derek@dkeats.com
 * Started on: December 18, 2011, 8:48 am
 *
 *
 */


var data_string;


/**
 *
 * jQuery code belongs inside this function.
 *
 */
jQuery(function() {
    
    jQuery.validator.addMethod(
        "unescoDate",
            function(value, element) {
                // put your own logic here, this is just a (crappy) example
                return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
            },
        "Please enter a date in the format dd/mm/yyyy"
    );


    // Things to do on loading the page.
    jQuery(document).ready(function() {
        // Add jQuery Validation to form
        jQuery("#form_edituser").validate({
            errorLabelContainer: jQuery("#RegisterErrors"),
            // JUST MAKE ALL THE MESSAGES SIMPLE, SHORT - required
            rules: {
                title: {
                }, 
                firstname: {
                    required: true,
                    minlength: 2
                }, 
                surname: {
                    required: true,
                    minlength: 2
                },
                username: {
                    required: true,
                    minlength: 6
                },
                password: {
                    required: true,
                    minlength: 8
                },
                confirmpassword: {
                    required: true,
                    minlength: 8,
                    equalTo: "#input_password"
                },
                email: {
                    required: true,
                    email: true
                },
                birthdate: {
                    required: true,
                    minlength: 2
                },
                address: {
                    required: true,
                    minlength: 8
                },
                city: {
                    required: true,
                    minlength: 2
                },
                state: {
                    required: true,
                    minlength: 2
                },
                postalcode: {
                    required: true,
                    minlength: 2
                },
                country: {

                },
                orgcomp: {
                    required: true,
                    minlength: 2
                },
                jobtitle: {
                    required: true,
                    minlength: 2
                },
                occupationtype: {
                    required: true,
                    minlength: 2
                },
                workphone: {
                    required: true,
                    minlength: 2
                },
                mobilephone: {
                    required: true,
                    minlength: 2
                },
                description: {
                    required: true
                }
            },
            
            messages: {
                firstname: {
                    required: required_field, 
                    minlength: min2
                },
                surname: {
                    required: required_field,
                    minlength: min2
                },
                username: {
                    required: required_field,
                    minlength: min6
                },
                password: {
                    required: required_field,
                    minlength: min2
                },
                confirmpassword: {
                    required: required_field,
                    minlength: min8,
                    equalTo: passnomatch
                },
                email: {
                    required: required_field,
                    email: validemail
                },
                birthdate: {
                    required: required_field
                },
                address: {
                    required: required_field,
                    minlength: min8
                },
                city: {
                    required: required_field
                },
                state: {
                    required: required_field
                },
                postalcode: {
                    required: required_field
                },
                orgcomp: {
                    required: required_field
                },
                jobtitle: {
                    required: required_field
                },
                occupationtype: {
                    required: required_field
                },
                workphone: {
                    required: required_field
                },
                mobilephone: {
                    required: required_field
                },
                description: {
                    required: min100
                }
            }
        });
    });

    // Function for saving the institutional data
    jQuery("#form_edituser").submit(function(e) {
        if(jQuery("#form_edituser").valid()){ 
            e.preventDefault();
            jQuery("#submitUser").attr("disabled", "disabled");
            jQuery("#save_results").html('<img src="skins/_common/icons/loading_bar.gif" alt=""Saving..." />');
            data_string = jQuery("#form_edituser").serialize();
            jQuery.ajax({
                url: 'index.php?module=oer&action=userdetailssave',
                type: "POST",
                data: data_string,
                success: function(msg) {
                    alert(msg);
                    jQuery("#submitUser").attr("disabled", "");
                    if(msg !== "ERROR_DATA_INSERT_FAIL") {
                        // Update the information area 
                        // (msg is the id of the record on success)
                        jQuery("#save_results").html('<span class="success">' + status_success + ": " + msg + '</span>');
                        // Change the id field to be the id that is returned as msg & mode to edit
                        jQuery("#id").val(msg);
                        jQuery("#mode").val('edit');
                    } else {
                        //alert(msg);
                        jQuery("#save_results").html('<span class="error">' + status_fail + ": " + msg + '</span>');
                    }
                }
            });
        }
    });
});