/* 
 * Javascript to support statusbar
 *
 * Written by Kevin Cyster kcyster@gmail.com
 * STarted on: May 17, 2012, 10:54 am
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

    jQuery(document).ready(function() {

    });
    
    jQuery('#statusbar_settings').live('click', function() {
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxShowOrientation",
            success: function(ret) {
                jQuery("#statusbar_orientation_div").html(ret);
                var orientation = jQuery('#input_orientation').val();
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=statusbar&action=ajaxShowPosition",
                    data: 'orientation=' + orientation,
                    success: function(ret) {
                        jQuery("#statusbar_position_div").html(ret);
                        jQuery.ajax({
                            type: "POST",
                            url: "index.php?module=statusbar&action=ajaxShowPosition",
                            data: 'orientation=' + orientation,
                            success: function(ret) {
                                jQuery("#statusbar_display_div").html(ret);
                                jQuery('#dialog_statusbar_settings').dialog('open');
                            }
                        });
                    }
                });
            }
        });
    });

    jQuery('#statusbar_settings_cancel').live('click', function() {
        jQuery('#dialog_statusbar_settings').dialog('close');
        return false;
    });
    
    jQuery('#input_orientation').live('change', function() {
        var orientation = jQuery('#input_orientation').val();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxShowPosition",
            data: 'orientation=' + orientation,
            success: function(ret) {
                jQuery("#statusbar_position_div").html(ret);
            }
        });
    });
    
    jQuery('#statusbar_settings_save').live('click', function() {
        var orientation = jQuery("#input_orientation").val();
        var position = jQuery("#input_position").val();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxSaveSettings",
            data: "orientation=" + orientation + "&position=" + position,
            success: function(ret) {
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=statusbar&action=ajaxShowMain",
                    success: function(ret) {
                        jQuery("#statusbar").html(ret);
                        if (orientation == 'horizontal')
                        {
                            jQuery("#statusbar").dialog("option", "minHeight", 35);
                        }
                        else
                        {
                            jQuery("#statusbar").dialog("option", "minHeight", "auto");
                        }
                        jQuery("#statusbar").dialog("option", "position", position);
                        jQuery("#dialog_statusbar_settings").dialog("close");
                    }
                });
            }
        });
    });
    
    jQuery("#statusbar_display_hide").live('click', function() {
        var position = jQuery("#input_position").val();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxSaveSettings",
            data: "display=no",
            success: function(ret) {
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=statusbar&action=ajaxShowMain",
                    success: function(ret) {
                        jQuery("#statusbar").html(ret);
                        jQuery("#statusbar").dialog("close");
                        jQuery("#statusbar").dialog("option", "minHeight", 35);
                        jQuery("#statusbar").dialog("option", "position", position);
                        jQuery("#statusbar").dialog("open");
                    }
                });
            }
        });
    });

    jQuery("#statusbar_display_show").live('click', function() {
        var orientation = jQuery("#input_orientation").val();
        var position = jQuery("#input_position").val();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxSaveSettings",
            data: "display=yes",
            success: function(ret) {
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=statusbar&action=ajaxShowMain",
                    success: function(ret) {
                        jQuery("#statusbar").html(ret);
                        jQuery("#statusbar").dialog("close");
                        if (orientation == 'horizontal')
                        {
                            jQuery("#statusbar").dialog("option", "minHeight", 35);
                        }
                        else
                        {
                            jQuery("#statusbar").dialog("option", "minHeight", "auto");
                        }
                        jQuery("#statusbar").dialog("option", "position", position);
                        jQuery("#statusbar").dialog("open");
                    }
                });
            }
        });
    });
    
    jQuery("#statusbar_buddies").live("click", function() {
        jQuery("#dialog_statusbar_message").dialog("open");
    });

    jQuery("#statusbar_message_cancel").live("click", function() {
        jQuery("#dialog_statusbar_message").dialog("close");
    });
    
    jQuery("#statusbar_message_send").live("click", function() {
        var recipient = jQuery("#input_to").val();
        var message = jQuery("#input_message").val();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxSaveMessage",
            data: "recipient=" + recipient + "&message=" + message,
            success: function(ret) {
                jQuery("#dialog_statusbar_message").dialog("close");
                jQuery("#dialog_statusbar_message_confirm").dialog("open");
            }
        });
    });
    
    jQuery("#statusbar_messaging").live("click", function() {
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=statusbar&action=ajaxShowMessage",
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery('#statusbar_message_from').html(obj.from);
                jQuery('#statusbar_message_message').html(obj.message);
                jQuery("#dialog_statusbar_message_show").dialog("open");
            }
        });
    });
});

var updateInstantMessages = function(myDialog)
{
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=statusbar&action=ajaxUpdateMessages",
        success: function (data) {
            return false;
        }
    });    
}
