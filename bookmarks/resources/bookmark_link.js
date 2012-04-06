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
    
    jQuery('#link_add_bookmark').click(function() {
        jQuery('#jqdialogue_add_bookmark').dialog('open');
    });
    
    jQuery('#jqdialogue_add_bookmark').live('dialogopen', function(event, ui) {
        jQuery('.ui-dialog-titlebar-close').hide();
        jQuery('#input_location').val(bookmark);
        jQuery('#input_visible_location').val(bookmark);
    });
    
    jQuery('#modal_cancel').live('click', function() {
        jQuery('#jqdialogue_add_bookmark').dialog('close');
        return false;
    });
    
    jQuery('#modal_save').live('click', function() {
        var nameValue = jQuery('#input_bookmark_name').val().trim();
        if (nameValue == '')
        {
            alert(no_name);
            jQuery('#input_bookmark_name').val('');
            jQuery('#input_bookmark_name').focus();
            return false;
        }
        else
        {
            jQuery('#form_modal_bookmarks').submit();
            jQuery('#jqdialogue_add_bookmark').dialog('close');
        }
    });
});