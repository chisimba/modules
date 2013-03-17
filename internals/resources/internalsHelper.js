/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    jQuery("#start_date_error, #end_date_error").hide();
    jQuery("#end_date_error, #start_date_error").css({
        'position': 'absolute',
        'font-weight': 'bold'
    });
    //date variable to be used for validation
    jQuery('#leavetypes').live('change', function() {
//                alert(jQuery('#leavetype').val());
    });
    jQuery('.requestComments, .sendLink').hide();
});

jQuery('#btnSave').die('click').live('click', function() {
    var startDate = jQuery("[name='startdate']").val();
    var endDate = jQuery("[name='enddate']").val();
    data_string = jQuery('#form_frmLeave').serialize();
    if (startDate.length > 0) {
        if (endDate.length > 0) {
            jQuery.ajax({
                url: 'index.php?module=internals&action=pushRequest',
                type: 'post',
                data: data_string,
                success: function() {
                    //message to indicate that the request went through
                    alert("Done!");
                }
            })
        } else {
            jQuery("#end_date_error").show('slow');
        }
    } else {
        jQuery("#start_date_error").show('slow');
    }
});

//add leave
jQuery('#btnSaveLeave').die('click').live('click', function() {
    var leavename = jQuery('#txtSaveLeave').val();
    var numberofdays = jQuery('#txtNumDays').val();
    if (leavename.length >= 1) {
        if (numberofdays.length >= 1) {
            var leave_info = "leavename="+leavename+"&numberofdays="+numberofdays;
            jQuery.ajax({
                url: 'index.php?module=internals&action=addLeavetype',
                type: 'post',
                data: leave_info,
                success: function() {
                    alert(leave_info)
                }
            });
        }else{
            alert("Enter leave name");
        }
    }else{
        alert("Enter number of days")
    }
});
jQuery(function() {
//Handling request reject
    jQuery('.rejectLink').live('click', function(e) {
        e.preventDefault();
        jQuery('.requestComments#' + jQuery(this).attr('id')).show('slow');
        jQuery('.sendLink#' + jQuery(this).attr('id')).show('slow');
//        alert(comments);
//        var data_string = jQuery("#form_").serialize();
//        data_string['id'] = req_Id;
    });
    jQuery('.sendLink').die('click').live('click', function(e) {
        e.preventDefault();
        var leave_Id = jQuery("input:radio:checked").attr('id');
        var req_Id = jQuery(this).attr('id');
        var x_data = jQuery(this).attr('x-data');
        var comments = jQuery('textarea#' + req_Id).val();
        jQuery('textarea#' + req_Id).hide('slow');
        jQuery(this).hide('slow');
//                alert(comments);
        var data_string = 'id=' + req_Id + '&x_data=' + x_data + '&status=rejected&comments=' + comments + 'leaveid=' + leave_Id;
        jQuery.ajax({
            url: 'index.php?module=internals&action=updateRequest',
            type: 'post',
            data: data_string,
            success: function() {
                alert('request processed!')
            }
        });
    });
});
jQuery('input.transparentbgnb').die('click').live('click', function() {
    jQuery('input.transparentbgnb').attr('checked', false);
    jQuery(this).attr('checked', true);
});
//Handling request accept
jQuery('.acceptLink').die('click').live('click', function() {
    var leave_Id = jQuery("input:radio:checked").attr('id');
    var req_Id = jQuery(this).attr('id');
    var x_data = jQuery(this).attr('x-data');
    var data_string = 'id=' + req_Id + '&x_data=' + x_data + '&status=approved&leaveid=' + leave_Id;
    data_string['id'] = req_Id;
    jQuery.ajax({
        url: 'index.php?module=internals&action=updateRequest',
        type: 'post',
        data: data_string,
        success: function() {
            alert('request processed!')
        }
    });
});