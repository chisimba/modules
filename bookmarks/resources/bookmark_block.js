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
    // Things to do on loading the page.
    jQuery(document).ready(function() {

    });
    
    jQuery('#input_block_folder_id').change(function () {
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=bookmarks&action=ajaxGetBlockBookmarks",
            data: 'id=' + jQuery('#input_block_folder_id').val(),
            success: function(ret) {
                jQuery("#bookmarks_block_layer").html(ret); 
            }
        });
    });
    
    jQuery('[id^="block_"]').live('click', function() {
        var id = this.id.replace("block_", "");
        var bookmark = jQuery("#block_" + id);
    });

});