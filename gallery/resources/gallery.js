/* 
 * Javascript to support gallery
 *
 * Written by Kevin Cyster kcyster@gmail.com
 * STarted on: June 14, 2012, 11:07 am
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

    jQuery("#add_gallery").live("click", function(){
        jQuery("#input_gallery_name").val('');
        jQuery("#input_gallery_description").val('');
        jQuery("#dialog_gallery_add").dialog("open");
    });
    
    jQuery("#gallery_add_cancel").live("click", function(){
        jQuery("#dialog_gallery_add").dialog("close");
    });
    
    jQuery("#form_gallery_add").submit(function(){
        if (jQuery("#input_gallery_name").val() == '')
        {
            alert(no_gallery_name);
            jQuery("#input_gallery_name").focus();
            return false;
        }
        if (jQuery("#input_gallery_description").val() == '')
        {
            alert(no_gallery_desc);
            jQuery("#input_gallery_description").focus();
            return false;
        }
        var myData = jQuery("#form_gallery_add").serialize();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxSaveAddGallery",
            data: myData,
            success: function(ret) {
                jQuery("#dialog_gallery_add").dialog("close");
                jQuery("#image_gallery").html(ret);                
            }
        });
        return false;
    });
    
    jQuery(".gallery").live("click", function() {
        var id = this.id.replace("gallery_", "");
        jQuery(".gallery_options").hide();
        jQuery("#gallery_options_" + id).show();
    });
    
    jQuery(".gallery").live("dblclick", function() {
        var id = this.id.replace("gallery_", "");
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowAlbums",
            data: "gallery_id=" + id,
            success: function(ret) {
                jQuery("#image_gallery").html(ret);                
            }
        });
    });

    jQuery(".gallery_edit").live("click", function() {
        var id = this.id.replace("gallery_edit_", "");
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowEditGallery",
            data: "gallery_id=" + id,
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery("#input_edit_gallery_id").val(obj.gallery_id);                
                jQuery("#input_edit_gallery_name").val(obj.name);                
                jQuery("#input_edit_gallery_description").val(obj.desc);                
                jQuery("#input_edit_gallery_shared").val([obj.shared]);
                jQuery("#dialog_gallery_edit").dialog("open");
            }
        });
    });
    
    jQuery("#gallery_edit_cancel").live("click", function(){
        jQuery("#dialog_gallery_edit").dialog("close");
    });
    
    jQuery("#form_gallery_edit").submit(function(){
        if (jQuery("#input_edit_gallery_name").val() == '')
        {
            alert(no_gallery_name);
            jQuery("#input_edit_gallery_name").focus();
            return false;
        }
        if (jQuery("#input_edit_gallery_description").val() == '')
        {
            alert(no_gallery_desc);
            jQuery("#input_edit_gallery_description").focus();
            return false;
        }
        var myData = jQuery("#form_gallery_edit").serialize();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxSaveEditGallery",
            data: myData,
            success: function(ret) {
                jQuery(".gallery_options").hide();
                jQuery("#dialog_gallery_edit").dialog("close");
                jQuery("#image_gallery").html(ret);                
            }
        });
        return false;
    });
    
    jQuery(".album_add").live("click", function() {
        var id = this.id;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowAddAlbum",
            data: "gallery_id=" + id,
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery("#input_album_gallery_id").val(obj.gallery_id);                
                jQuery("#input_album_name").val('');
                jQuery("#input_album_description").val('');
                jQuery("#dialog_album_add").dialog("open");
            }
        });
    });

    jQuery("#album_add_cancel").live("click", function(){
        jQuery("#dialog_album_add").dialog("close");
    });
    
    jQuery("#form_album_add").submit(function(){
        var gallery = jQuery("#input_gallery");        
        if (gallery.length > 0)
        {
            myData = myData + '&from=album';
        }
        if (jQuery("#input_album_name").val() == '')
        {
            alert(no_album_name);
            jQuery("#input_album_name").focus();
            return false;
        }
        if (jQuery("#input_album_description").val() == '')
        {
            alert(no_album_desc);
            jQuery("#input_album_description").focus();
            return false;
        }
        var myData = jQuery("#form_album_add").serialize();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxSaveAddAlbum",
            data: myData,
            success: function(ret) {
                jQuery("#dialog_album_add").dialog("close");
                jQuery("#image_gallery").html(ret);                
            }
        });
        return false;
    });
    
    jQuery(".album").live("click", function() {
        var id = this.id.replace("album_", "");
        jQuery(".album_options").hide();
        jQuery("#album_options_" + id).show();
    });
    
    jQuery(".album_edit").live("click", function() {
        var id = this.id.replace("album_edit_", "");
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowEditAlbum",
            data: "album_id=" + id,
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery("#input_edit_album_gallery_id").val(obj.gallery_id);                
                jQuery("#input_edit_album_id").val(obj.album_id);                
                jQuery("#input_edit_album_name").val(obj.name);                
                jQuery("#input_edit_album_description").val(obj.desc);                
                jQuery("#dialog_album_edit").dialog("open");
            }
        });
    });
    
    jQuery("#album_edit_cancel").live("click", function(){
        jQuery("#dialog_album_edit").dialog("close");
    });
    
    jQuery("#form_album_edit").submit(function(){
        if (jQuery("#input_edit_album_name").val() == '')
        {
            alert(no_album_name);
            jQuery("#input_edit_album_name").focus();
            return false;
        }
        if (jQuery("#input_edit_album_description").val() == '')
        {
            alert(no_album_desc);
            jQuery("#input_edit_album_description").focus();
            return false;
        }
        var myData = jQuery("#form_album_edit").serialize();
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxSaveEditAlbum",
            data: myData,
            success: function(ret) {
                jQuery(".album_options").hide();
                jQuery("#dialog_album_edit").dialog("close");
                jQuery("#image_gallery").html(ret);                
            }
        });
        return false;
    });
    
    jQuery(".gallery_upload").live("click", function() {
        var id = this.id;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowUploadPhoto",
            data: "gallery_id=" + id,
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery("#input_photo_gallery_id").val(obj.gallery_id);                
                jQuery(".more_boxes").hide();
                jQuery("input[id^=input_file]").each(function(){
                    jQuery(this).val('');
                });
                jQuery("#more_photos em").html(more_inputs)
                jQuery("#upload_to").html(obj.album_string);
                jQuery("#dialog_photo_add").dialog("open");
            }
        });
    });
    
    jQuery("#input_photo_album_id").live("change", function() {
        var id = jQuery("#input_album_id").val();
        if (id == '')
        {
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=gallery&action=ajaxShowNewAlbumInputs",
                success: function(ret) {
                    jQuery("#upload_new").html(ret)
                }
            });
        }
        else
        {
            jQuery("#upload_new").html('');
        }
    });
    
    jQuery("#more_photos").live("click", function() {
        jQuery(".more_boxes").toggle();
        if (jQuery(".more_boxes").is(":visible"))
        {
            jQuery("#more_photos em").html(less_inputs)
        }
        else
        {
            jQuery("#more_photos em").html(more_inputs)
        }
        jQuery("#dialog_photo_add").dialog("option", "height", "auto");
        jQuery("#dialog_photo_add").dialog("option", "position", "auto");
    });
    
    jQuery("#photo_add_cancel").live("click", function() {
        jQuery("#dialog_photo_add").dialog("close");
    });
    
    jQuery("#form_photo_add").submit(function() {
        if (jQuery("#input_photo_album_id").val() == '')
        {
            if (jQuery("#input_photo_album_name").val() == '')
            {
                alert(no_album_name);
                return false;
            }
            if (jQuery("#input_photo_album_description").val() == '')
            {
                alert(no_album_desc);
                return false;
            }
        }
        var files = false;
        jQuery("input[id^=input_file]").each(function() {
            if (jQuery(this).val() != '')
            {
                files = true;
            }
        });
        if (!files)
        {
            alert(no_photo);
            return false;
        }
        jQuery("input[id^=input_file]").each(function() {
            if (jQuery(this).val() == '')
            {
                jQuery(this).detach();
            }
        });
    });
    
    jQuery(".album_upload").live("click", function() {
        var id = this.id;
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=gallery&action=ajaxShowUploadPhoto",
            data: "album_id=" + id + "&gallery_id=" + jQuery("#input_gallery").val(),
            success: function(ret) {
                var obj = jQuery.parseJSON(ret)
                jQuery("#input_photo_gallery_id").val(obj.gallery_id);                
                jQuery(".more_boxes").hide();
                jQuery("input[id^=input_file]").each(function(){
                    jQuery(this).val('');
                });
                jQuery("#more_photos em").html(more_inputs)
                jQuery("#upload_to").html(obj.album_string);
                jQuery("#upload_new").html('');
                jQuery("#dialog_photo_add").dialog("open");
            }
        });
    });
    
});