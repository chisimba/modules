/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    jQuery('a').unbind('click');
    //hide the side topics
    jQuery('ul.indicator').hide();
    jQuery('.replyForumUserPicture').hide();
    jQuery('.deleteconfirm').hide();
    jQuery('div.hiddenOptions, div.attachmentwrapper, div.file-preview').hide();
    jQuery('div.filePreview').hide();
    jQuery('a.ratings').click(function(e) {
        e.preventDefault()
    });
    /**
     * ===Confirmation message===
     * 
     * @returns {undefined}
     */
    jQuery.fn.displayConfirmationMessage = function(message) {
        if (message != '') {
            jQuery('div#Canvas_Content_Body_Region2').append('<span class=\'jqGenerated\'  id=\'confirm\' >' + message + '</span>');
        } else {
            message = "Success";
        }
        setTimeout(function() {
            jQuery('.jqGenerated').remove();
        }, 5000);
    }
    /**
     * ===Increase the number of rows when the textare is clicked===
     */
    jQuery('.miniReply').focus(function() {
        jQuery(this).attr('rows', 6);
        jQuery('.replyForumUserPicture').show('slow');
    });
    /**
     * ===Send the message===
     */
    jQuery('.postReplyLink').click(function(e) {
        e.preventDefault();
        //get aattributes
        var element_Id = jQuery(this).attr('id');
        //html elements
        var message_text = jQuery('iframe').contents().find("body.cke_show_borders").html();
        var text_area = jQuery('textarea.miniReply');
//        if (jQuery(message_text).val() != '') {
        var image = jQuery('<img>', {
            src: 'skins/_common/icons/301.gif',
            align: 'center'
        });
        jQuery(this).fadeOut('slow');
        jQuery('div.content').hide();
        jQuery('.newForumContainer.reply').append(image);
        jQuery('.newForumContainer.reply').append('<br/><h4>Please wait..</h1>')
        //get attachment value
        var attachment_id = jQuery('#hidden_fileselect').val();
        //get post text
        var message = jQuery(text_area).val();
        var parent_id = element_Id;
        var topic_id = jQuery('.topicid').attr('id');
        var lang = jQuery('.lang').attr('id');
        var forum_id = jQuery('.forumid').attr('id');
        var post_title = jQuery('.posttitle').attr('id');
        //Send the data
        jQuery.ajax({
            url: 'index.php?module=forum&action=savepostreply',
            type: 'post',
            data: {
                forumid: forum_id,
                topicid: topic_id,
                parent: parent_id,
                message: message,
                posttitle: post_title,
                lang: lang,
                attachment: attachment_id
            },
//                data: ' forumid=' + forum_id + '&topicid=' + topic_id + '&parent=' + parent_id + '&message=' + message_text + '&posttitle=' + post_title + '&lang=' + lang + '&attachment=' + attachment_id,
            success: function() {
                jQuery('.newForumContainer.reply').empty();
                alert("Success");
                //add element to another class
                window.location.reload();
//                    jQuery('.content').html('<br/>' + message);
            }
        });
//        } else {
////            jQuery(text_area).val('why you no type here?');
//        }
    });
    /**
     * ===Deleting the post===
     */
    jQuery('a.postDeleteLink').click(function(event) {
        event.preventDefault();
        var link_id = jQuery(this).attr('id');
        jQuery('div.deleteconfirm#' + link_id).toggle('fade');
    });
    //on focus out
    jQuery('.miniReply').focusout(function() {
        if (jQuery('.miniReply').val() == 0) {
            jQuery(this).attr('rows', 2);
        }
    });

    /**
     * ===Display moderation options===
     */
    jQuery('a.moderatetopic').click(function(e) {
        e.preventDefault();
        jQuery('div.hiddenOptions').toggle('fade');
    });

    /**
     * ==Confirming post delete===
     */
    jQuery('a.postDeleteConfirm').click(function(e) {
        e.preventDefault();
        var link_id = jQuery(this).attr('id');
        jQuery('div.deleteconfirm#' + link_id).toggle('fade');
        var post_id = jQuery(this).attr('id');
        var topic_id = jQuery('span.topicid').attr('id');
        jQuery.ajax({
            url: 'index.php?module=forum&action=removepost',
            type: 'post',
            data: {
                postid: link_id,
                topic_id: topic_id
            },
            success: function(data) {
                jQuery('body').append('<div class="blurPopUp"><span id="confirm">Post deleted successfuly<br/><br/><a href="#" class="ok" >OK</a></span></div>');
            }
        });
    });
    //when clicking OK on the confirmation message
    jQuery('.ok').live('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });

    /**
     * ===Showing the attachment popUp===
     */
    jQuery('a.attachmentLink').click(function(e) {
        e.preventDefault();
        var attachCancelButton = jQuery('<button>', {
            text: 'Cancel',
            class: 'sexybutton',
            click: function(e) {
                e.preventDefault();
                jQuery('div.attachmentwrapper').val('');
                jQuery('div.attachmentwrapper').toggle('fade');
                jQuery(this).remove();
            }
        });
        jQuery('div.attachmentwrapper').toggle('fade');
        jQuery('div.attachmentwrapper').append(attachCancelButton);
    });

    /**
     * ===Moderating a post===
     */
    jQuery('#moderationSave').click(function() {
        var data_string = jQuery('#form_topicModeration').serialize();
        var forum_id = "forum=" + jQuery('.forumid').attr('id');
        jQuery(data_string).append(forum_id);
        jQuery.ajax({
            url: 'index.php?module=forum&action=usersubscription',
            type: 'post',
            data: data_string,
            success: function() {
                jQuery('div.hiddenOptions').hide();
            }
        });
    });

    /**
     * ===Canceling post delete===
     */
    jQuery('a.postDeleteCancel').click(function(e) {
        e.preventDefault();
        var link_id = jQuery(this).attr('id');
        jQuery('div.deleteconfirm#' + link_id).toggle('fade');
    });

    /**
     * @RATINGS
     */
    jQuery('a.ratings.up').click(function() {
        var post_id = jQuery(this).attr('id');
        jQuery.ajax({
            url: 'index.php?module=forum&action=savepostratingup',
            type: 'post',
            data: 'post_id=' + post_id,
            success: function() {
                window.location.reload()
            }
        })
    });
    /**
     * ===Lowering ratings===
     */
    jQuery('a.ratings.down ').click(function() {
        var post_id = jQuery(this).attr('id');
        jQuery.ajax({
            url: 'index.php?module=forum&action=savepostratingdown',
            type: 'post',
            data: 'post_id=' + post_id,
            success: function() {
                window.location.reload()
            }
        })
    });

    /**
     * Side block
     */
    jQuery('a.indicatorparent').click(function(event) {
        event.preventDefault();
        var element_ID = jQuery(this).attr('id');
        jQuery('ul#' + element_ID).slideToggle();
    });
    /**
     * ==View the post attachment===
     */
    jQuery('.forumViewAttachment').live('click', function(e) {
        var current_text = jQuery(this).html();
        if (current_text == 'View') {
            current_text = 'Hide'
        } else {
            current_text = 'View';
        }
        e.preventDefault();
        var parentElement_id = jQuery(this).attr('id');
        jQuery('div.file-preview#' + parentElement_id).toggle('blind');
        jQuery(this).html(current_text);
    });

    /**
     * =======Editing post==========
     */
    jQuery('.postEditClass').live('click', function() {
        var post_id = jQuery(this).attr('id');
        var _id = jQuery(this).attr('class');
        var post_text = jQuery('div.postText#' + post_id).html();
        //wrapper div
        var popUpWrapper = jQuery('<div>', {
            class: 'popUpWrapper'
        });
        //textarea
        var edit_post_area = jQuery('<span>', {
            val: post_text
        });
        var blocktext=jQuery('textarea[blocktext]');
        //save button
        var save_button = jQuery('<button>', {
            text: 'Save',
            class: 'sexybutton',
            id: post_id,
            click: function() {
                _id = _id.replace('postEditClass ', '');
                var new_value = jQuery('iframe').contents().find("body.cke_show_borders").html();
//                                var new_value = jQuery('td.cke_contents iframe').conents().find('html').html();
//                                var new_text = jQuery('[name="blocktext"]').val();
                jQuery.ajax({
                    url: 'index.php?module=forum&action=savepostedit',
                    type: 'post',
                    data: {
                        _id: _id,
                        post_id: post_id,
                        new_text: new_value
                    },
                    success: function() {
                        jQuery('div.postText#' + post_id).html(new_value);
                        //jQuery(popUpWrapper).empty();
                        //jQuery('.popUpWrapper').remove();
                        //jQuery('body').remove(popUpWrapper);
                        CKEDITOR.instances.blocktext.destroy();
                        jQuery.fn.displayConfirmationMessage();
                    }
                });
            }
        });
        //cancel button
        var cancel_button = jQuery('<button>', {
            text: 'Canel',
            class: 'sexybutton',
            click: function(e) {
                e.preventDefault();
//                jQuery(edit_post_area).val('');
//                jQuery(popUpWrapper).empty();
                jQuery('textarea#' + _id).remove();
                jQuery('body').remove('textarea#' + _id);
                jQuery(document).remove('.popUpWrapper');
                jQuery('.popUpWrapper').remove();
            }
        });
//            add all elements to wrapper div
//                jQuery(popUpWrapper).append(edit_post_area);
        jQuery('body').append(popUpWrapper);
        new_text = jQuery(edit_post_area).val();
        //Get and return the editor
        jQuery.ajax({
            url: 'index.php?module=forum&action=showeditpostpopup'/*&_id=' + _id + '&post_id=' + post_id + '&new_text=' + jQuery(edit_post_area).val()*/,
            type: 'post',
            data: {
                _id: _id,
                post_id: post_id,
                new_text: new_text
            },
            success: function(data) {
//                                jQuery('.postText#' + post_id).html(jQuery(edit_post_area).val());
//                                jQuery('.popUpWrapper').remove();
//                                                jQuery.fn.displayConfirmationMessage();
                var editor = data;
                jQuery(popUpWrapper).append(editor);
                jQuery(popUpWrapper).append(save_button);
                jQuery(popUpWrapper).append(cancel_button);
            }
        });
    });
    /**
     * ===Trying to inform the user as to which file is selecteded===
     */
    jQuery('#input_selectfile_fileselect').live('change', function() {
        alert(this).val();
    });
});
