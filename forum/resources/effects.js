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

//Saved changes message alert
        jQuery.fn.displayConfirmationMessage = function() {
                jQuery('div#Canvas_Content_Body_Region2').append('<span class=\'jqGenerated\'  id=\'confirm\' >it works</span>');
                setTimeout(function() {
                        jQuery('.jqGenerated').remove();
                }, 5000);
        }
        //increase the number of rows when the textare is clicked
        jQuery('.miniReply').click(function() {
                jQuery(this).attr('rows', 6);
                jQuery('.replyForumUserPicture').show('slow');
        });
        //make the post
        //prevent the event from firing twice
//        jQuery('.postReplyLink').unbind('click');
        jQuery('.postReplyLink').click(function(e) {
                e.preventDefault();
                //get aattributes
//                var href = jQuery(this).attr('href');
                var element_Id = jQuery(this).attr('id');
                //html elements
                var text_area = jQuery('textarea#' + element_Id);
                if (jQuery(text_area).val() != '' && jQuery(text_area).val() != 'why u no type here?') {
//                        var replyDiv = jQuery('.clone').clone();
//                        jQuery('textarea#' + element_Id).fadeOut('slow');
                        jQuery(this).fadeOut('slow');
                        var attachment_id = jQuery('#hidden_fileselect').val();
                        var message = jQuery(text_area).val();
                        var parent_id = jQuery(text_area).attr('id');
                        var topic_id = jQuery('.topicid').attr('id');
                        var lang = jQuery('.lang').attr('id');
                        var forum_id = jQuery('.forumid').attr('id');
                        var post_title = jQuery('.posttitle').attr('id');
//              var data_string = '{"data_string" : [{"forumid" : "'+forum_id+' ", "topicid" : "'+topic_id+'", "parent" : "'+parent_id+'", "message" : "'+message+'"}]}';
//              var data_bank = jQuery.parseJSON(data_string);
//              alert(data_bank);
                        jQuery.ajax({
                                url: 'index.php?module=forum&action=savepostreply',
                                type: 'post',
                                data: ' forumid=' + forum_id + '&topicid=' + topic_id + '&parent=' + parent_id + '&message=' + message + '&posttitle=' + post_title + '&lang=' + lang + '&attachment=' + attachment_id,
                                success: function() {
                                        //add element to another class
                                        window.location.reload();
                                        jQuery('.content').html('<br/>' + message);
//                                        jQuery('.newForumContent').append(replyDiv);
                                }
                        });
                } else {
                        jQuery(text_area).val('why you no type here?');
                }
        });
        //DELETE POST LINK
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

        //Display moderation options
        jQuery('a.moderatetopic').click(function(e) {
                e.preventDefault();
                jQuery('div.hiddenOptions').toggle('fade');
        });

        jQuery('a.postDeleteConfirm').click(function(e) {
                e.preventDefault();
                var link_id = jQuery(this).attr('id');
                jQuery('div.deleteconfirm#' + link_id).toggle('fade');
                var post_id = jQuery(this).attr('id');
                jQuery.ajax({
                        url: 'index.php?module=forum&action=removepost',
                        type: 'post',
                        data: 'postid=' + post_id,
                        success: function() {
//                                alert(post_id);
                                var current_location = window.location;
                                var new_location = current_location + '&message=deletesuccess';
                                jQuery('body').append('<span id="confirm">Post deleted successfuly<br/><a href="#" class="ok" >OK</a></span> ');
//                                window.location.replace(new_location);
//                                alert(window.location);
                        }
                });
        });
        //when clicking OK on the confirmation message
        jQuery('.ok').live('click', function(e) {
                e.preventDefault();
                window.location.reload();
        });

//
        jQuery('a.attachmentLink').click(function(e) {
                e.preventDefault();
                var attachCancelButton = jQuery('<button>', {
                        text: 'Cancel',
                        class: 'sexybutton',
                        click: function() {
                                jQuery('div.attachmentwrapper').val('');
                                jQuery('div.attachmentwrapper').toggle('fade');
                        }
                });
                jQuery('div.attachmentwrapper').toggle('fade');
                jQuery('div.attachmentwrapper').append(attachCancelButton);
        });

        //update topic status
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
//                                alert(this.data)
                        }
                });
        });
        //moderation cancel button
        jQuery('.postDeleteCancel').click(function(e) {
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
        jQuery('a.indicatorparent').click(function(event) {
                event.preventDefault();
                var element_ID = jQuery(this).attr('id');
//                alert(element_ID);
                jQuery('ul#' + element_ID).slideToggle();
        });
        jQuery('.forumViewAttachment').live('click', function(e) {
                e.preventDefault();
                var parentElement_id = jQuery(this).attr('id');
                jQuery('div.file-preview#' + parentElement_id).toggle('fade');
        });

        jQuery('.postEditClass').live('click', function() {
                var post_id = jQuery(this).attr('id');
                var post_text = jQuery('.postText#' + post_id).html();
                //wrapper div
                var popUpWrapper = jQuery('<div>', {
                        class: 'popUpWrapper'
                });
                //textarea
                var edit_post_area = jQuery('<textarea>', {
                        val: post_text
                });
                //save button
                var save_button = jQuery('<button>', {
                        text: 'Save',
                        class: 'sexybutton',
                        click: function() {
                                jQuery.ajax({
                                        url: 'index.php?module=forum&action=savepostedit',
                                        type: 'post',
                                        data: 'id=' + post_id + 'new_text=' + jQuery(edit_post_area).val(),
                                        success: function() {
                                                jQuery('.postText#' + post_id).html(jQuery(edit_post_area).val());
                                                jQuery('.popUpWrapper').remove();
                                                jQuery.fn.displayConfirmationMessage();
                                        }
                                });
                        }
                });
                //cancel button
                var cancel_button = jQuery('<button>', {
                        text: 'Canel',
                        class: 'sexybutton',
                        click: function() {
                                jQuery(edit_post_area).val('');
                                jQuery('.popUpWrapper').remove();
                        }
                });
//                jQuery(new_div).css('background', 'red');
//            add all elements to wrapper div
                jQuery(popUpWrapper).append(save_button);
                jQuery(popUpWrapper).append(cancel_button);
                jQuery(popUpWrapper).append(edit_post_area);
                jQuery('body').append(popUpWrapper);
        });
        jQuery('#input_selectfile_fileselect').live('change', function() {
                alert(this).val();
        });
});