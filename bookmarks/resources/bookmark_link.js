/* 
 * Javascript to support bookmarks
 *
 * Written by Kevin Cyster kcyster@gmail.com
 * STarted on: March 28, 2012, 12:34 pm
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
    var fullUrl = jQuery(location).attr('href');
    var domain = jQuery(location).attr('hostname');
    var path = jQuery(location).attr('pathname');
    
    var temp = fullUrl.replace('http://' + domain, '');
    var bookmark = temp.replace(path + '?', '');
    
    // Things to do on loading the page.
    jQuery(document).ready(function() {

    });
    
    jQuery('#add_bookmark').click(function() {
        jQuery('#dialog_add_bookmark').dialog('open');
    });
    
    jQuery('#dialog_add_bookmark').live('dialogopen', function(event, ui) {
        jQuery('.ui-dialog-titlebar-close').hide();
        jQuery('#input_bookmark_name').val('');
        jQuery('#input_location').val(bookmark);
        jQuery('#input_visible_location').val(bookmark);
    });
    
    jQuery('#modal_cancel').live('click', function() {
        jQuery('#dialog_add_bookmark').dialog('close');
        return false;
    });
    
    jQuery('#modal_save').live('click', function() {
        var nameValue = jQuery('#input_bookmark_name').val();
        if (nameValue == '')
        {
            alert(no_name);
            jQuery('#input_bookmark_name').val('');
            jQuery('#input_bookmark_name').focus();
            return false;
        }
        else
        {
            jQuery('#dialog_add_bookmark').dialog('close');
            var folder = jQuery('#input_folder_id').val();
            var name = jQuery('#input_bookmark_name').val();
            var location = jQuery('#input_location').val();
            location = location.replace(/=/gi, "/");
            location = location.replace(/&/gi, "|");
            
            var params = 'folder_id=' + folder + '&bookmark_name=' + name + '&location=' + location;
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=bookmarks&action=ajaxSaveBookmark",
                data: params,
                success: function(ret) {
                    jQuery("#dialog_bookmark_success").dialog('open');
                    return false;
                }
            });
        }
    });
});
