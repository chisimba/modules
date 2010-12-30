<?php
/**
 *
 * A simple wall module operations object
 *
 * A simple wall module that makes use of OEMBED and that tries to look a bit
 * like Facebook's wall. This is the operations class. The module creates wall
 * posts (status messages) and comments (or replies) linked to each post or
 * status message
 *   WALL POST MESSAGE
 *       Reply to it
 *       Reply to it
 *   ANOTHER WALL POST MESSAGE
 *       Reply to it
 *
 *   ...etc...
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   wall
 * @author    Derek Keats derek@dkeats.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: dbwall.php,v 1.1 2007-11-25 09:13:27 dkeats Exp $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
 * A simple wall module operations object
 *
 * A simple wall module that makes use of OEMBED and that tries to look a bit
 * like Facebook's wall. This is the operations class.
*
* @author Derek Keats
* @package wall
*
*/
class wallops extends object
{

    public $objDbwall;
    public $objUser;
    public $objLanguage;
    public $objDd;


    /**
    *
    * Intialiser for the wall database connector
    * @access public
    *
    */
    public function init()
    {
        // Set the jQuery version to the latest functional
        $this->setVar('JQUERY_VERSION', '1.4.2');
        // Create an instance of the database class.
        $this->objDbwall = & $this->getObject('dbwall', 'wall');
        // Load jQuery Oembed.
        $oEmb = $this->getObject('jqoembed', 'oembed');
        $oEmb->loadOembedPlugin();
        // Load the functions specific to this module.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('functions.js'));
        // Load the jQuery pluging showloading
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/showloading/js/jquery.showLoading.min.js', 'jquery'));
        // Instantiate the user object.
        $this->objUser = $this->getObject('user', 'security');
        // Instantiate the language object.
        $this->objLanguage = & $this->getObject('language', 'language');
        // Get an instance of the humanize date class.
        $this->objDd = $this->getObject('translatedatedifference', 'utilities');
        // Load all javascripts
        $this->loadScript();
        $this->loadDeleteScript();
        $this->loadCommentScript();
    }

    /**
     *
     * Render the wall posts for display
     *
     * @param string $wallType The wall type (0=site wall, 1=personal or user wall, 2=context wall)
     * @param integer $num The number of results to return, defaulting to 10
     * @return string The formatted wall posts
     * 
     */
    public function showWall($wallType, $num=10)
    {
        $comments = NULL;
        $ret ="\n\n<div id='wrapper'>\n" . $this->showPostBox() . "<div id='wall'>\n";
        $posts = $this->objDbwall->getWall($wallType, $num);
        $objCommentDb = $this->getObject('dbcomment', 'wall');
        // See if they are in myprofile else default link to wall
        $currentModule = $this->getParam('module', 'wall');
        // Get the current user Id
        $myUserId = $this->objUser->userId();
        foreach ($posts as $post) {
            // Set variables to NULL because they are used with .= later.
            $comments = NULL;
            $showMoreReplies = NULL;
            // Retrieve a small version of the user's avatar image.
            $img = $this->objUser->getSmallUserImage($post['posterid'], FALSE);
            // Convert the post date into human time.
            $when = $this->objDd->getDifference($post['datecreated']);
            $fullName = $post['firstname'] . " " . $post['surname'];
            $id = $post['id'];
            $replies = $post['replies'];
            // If there are fewer than 3 replies, show them all, otherwise get last 3
            if ($replies > 0) {
                // Get the last three replies.
                $commentAr = $objCommentDb->getComments($id, 3);
                if ($replies > 3) {
                    // Let us know that there are more and build a link to get them via ajax
                    $moreReplies = $replies - 3;
                    $reps = $this->objLanguage->languageText("mod_wall_replies", "wall", "more replies");
                    $showMoreReplies = $moreReplies ." " . $reps . ".";
                    $showMoreReplies = "<a class='wall_comments_more' id='mrep__$id' href='javascript:void(0);'>" . $showMoreReplies . "</a>";
                }
                // Render the comments into LI tags.
                $comments = $this->loadComments($commentAr, $currentModule);
                $comments = $this->createCommentBlock($comments, $id);
                // The replies notice which goes at the bottom of the wall post
                $repliesNotice = $replies . ' '
                  . $this->objLanguage->languageText(
                     'mod_wall_replies', 'wall', 'replies');
            } else {
                $repliesNotice = $this->objLanguage->languageText(
                        'mod_wall_noreplies', 'wall', 'No replies');
            }

            if ($currentModule == 'myprofile') {
                $fnLink = $this->uri(array(
                    'username' => $post['username']
                ), 'myprofile');
            } else {
                $fnLink = $this->uri(array(
                    'walltype' => 'personal',
                    'username' => $post['username']
                ), 'wall');
            }

            $fullName = '<a href="' . $fnLink . '">' . $fullName . '</a>';
            if ($myUserId == $post['posterid'] || $myUserId == $post['ownerid'] ) {
                $delLink = $this->uri(array(
                  'action' => 'delete',
                  'id' => $id
                ), 'wall');
                $delLink="#";
                $delLink = str_replace('&amp;', '&', $delLink);
                $del = '<a class="delpost" id="'
                  . $id . '" href="' . $delLink . '">X</a>';
            } else {
                $del = NULL;
            }

            // Render the content for display.
            $ret .= "<div class='wallpostrow' id='wpr__" . $id . "'>$del<div class='msg'>\n" . $img
              . "<span class='wallposter'>" . $fullName 
              . "</span><br />"
              . $post['wallpost'] . "</div><div class='wall_post_info'>" . $when
              . "&nbsp;&nbsp;&nbsp;&nbsp;" . $repliesNotice . "&nbsp;&nbsp;&nbsp;&nbsp;"
              . $this->getCommentDisplayButton($id) . "</div>\n"
              . $this->getReplyLink($id) . $comments . " "
              . $showMoreReplies . "</div>\n"; //"  "
        }
        $ret .="</div>\n</div>\n\n";
        return $ret;
    }

    /**
     *
     * Method to load the comments into LI tags and add additional
     * data for rendering to display.
     *
     * @param string Array $commentAr An array of comments retrieved from the database
     * @param string $currentModule The current module we are in
     * @return string The rendered comments
     *
     */
    public function loadComments($commentAr, $currentModule='wall')
    {
        $comments = NULL;
        foreach ($commentAr as $comment) {
            $commentWhen = $this->objDd->getDifference($comment['datecreated']);
            $commentFn = $comment['firstname']. " " . $comment['surname'];
            if ($currentModule == 'myprofile') {
                $cfnLink = $this->uri(array(
                    'username' => $comment['username']
                ), 'myprofile');
            } else {
                $cfnLink = $this->uri(array(
                    'walltype' => 'personal',
                    'username' => $comment['username']
                ), 'wall');
            }
            $targetId = $comment['id'];
            $commentor = $comment['posterid'];
            $wallOwner = $comment['wallowner'];
            $wallType = $comment['walltype'];
            $del = $this->getDelCommentLink($targetId, $commentor,
              $wallOwner, $wallType);
            $commentFn = '<a href="' . $cfnLink . '">' . $commentFn . '</a>';
            $comments .= "<li id='cmt__" . $targetId . "'>" . $del . "<span class='wall_comment_author'>"
            . $commentFn . "</span>&nbsp;&nbsp;" . $comment['wallcomment']
            . "<br /><div class='wall_comment_when'>"
            . $commentWhen . "</div></li>";
        }
        return $comments;
    }

    /**
     *
     * Build the coment block
     *
     * @param string $comments The contents of the comment block
     * @return string The rendered block
     *
     */
    public function createCommentBlock($comments, $id)
    {
        // Tag it with the ID so we can write back to it from Javascript
        $blockTop = "\n\n<div class='wall_comments_top'></div>" 
          . "<ol class='wall_replies' id='wct_" . $id . "'>";
        $blockBottom = "</ol><div class='wall_comments_bottom'></div>\n\n";
        return $blockTop . "\n" . $comments . "\n" . $blockBottom;
    }

    /**
    *
    * Render the input box for posting a wall post.
    *
    * @return string The input box and button
    *
    */
    private function showPostBox()
    {
        if ( $this->objUser->isLoggedIn() ) {
            $onlyText = $this->objLanguage->languageText("mod_wall_onlytext",
              "wall", "No HTML, only links and text");
            $enterText =  $this->objLanguage->languageText("mod_wall_entertext",
              "wall", "Enter your message here...");
            $shareText =  $this->objLanguage->languageText("mod_wall_share",
              "wall", "Share");
            $ret = '<div id=\'updateBox\'>
            ' . $enterText . '
            <textarea id=\'wallpost\'></textarea>
            <button id=\'shareBtn\'>' . $shareText . '</button>
            (' . $onlyText . ')
            <div class=\'clear\'></div>
            </div>';
        } else {
            $ret=NULL;
        }
        return $ret;
    }

    /**
     *
     * Load the Ajax and other jQuery Javascript, including the OEMBED
     * translation. The script is rendered in the page header.
     *
     * @return VOID
     */
    private function loadScript()
    {
        $objGuessWall = $this->getObject('wallguesser','wall');
        $wallType = $objGuessWall->guessWall();
        $objGuessUser = $this->getObject('bestguess', 'utilities');
        $ownerId = $objGuessUser->guessUserId();
        $target = $this->uri(array(
            'action' => 'save',
            'ownerid' => $ownerId,
            'walltype' => $wallType
        ), 'wall');
        $target = str_replace('&amp;', "&", $target);
        $myUserId = $this->objUser->userId();
        $me = $this->objUser->fullName();
        //$un = $this->objUser->userName();
        //$currentModule = $this->getParam('module', 'wall');
        //if ($currentModule == 'myprofile') {
        //    $fnLink = $this->uri(array(
        //        'username' => $un
        //    ), 'myprofile');
        //} else {
        //    $fnLink = $this->uri(array(
        //        'walltype' => 'personal',
        //        'username' => $un
        //    ), 'wall');
        //}

        //$me = '<a href="' . $fnLink . '">' . $fn . '</a>';

        $img = $this->objUser->getSmallUserImage();
        $me =  "<span class='wallposter'>" . $me . "</span>";
        $script = '<script type=\'text/javascript\'>
        jQuery(function(){
	jQuery(".msg a").oembed(null, {
	embedMethod: "append",
	maxWidth: 480
	});
	jQuery("#shareBtn").click(function(){
            status_text = jQuery("#wallpost").val();
            if(status_text.length == 0) {
                    return;
            } else {
                jQuery("#wallpost").attr("disabled", "disabled");
                status_text = stripHTML(status_text); // clean all html tags
                status_text = replaceURLWithHTMLLinks(status_text); // replace links with HTML anchor tags.
                jQuery.ajax({
                        url: "' . $target . '",
                        type: "POST",
                        data: "wallpost="+status_text,
                        success: function(msg) {
                            jQuery("#wallpost").val("");
                            jQuery("#wallpost").attr("disabled", "");
                            if(msg == "true") {
                                jQuery("#wall").prepend("<div class=\'wallpostrow\'>' . $me . '<div class=\'msg\'>"+status_text+"</div></div>");
                                jQuery(".msg:first a").oembed(null, {maxWidth: 480, embedMethod: "append"});
                            } else {
                                alert(msg);
                                //alert("Cannot be posted at the moment! Please try again later.");
                            }
                        }
                });
                return false;
		}
	});
        });
        </script>';
        $this->appendArrayVar('headerParams', $script);
    }


    /**
     *
     * Load the script for deleting a post into the page header.
     *
     * @TODO change it to work with comments too
     *
     * @return VOID
     * @access public
     *
     */
    public function loadDeleteScript()
    {
        $script = '<script type="text/javascript">
            jQuery(function() {
                jQuery(".delpost").click(function() {
                    var commentContainer = jQuery(this).parent();
                    var id = jQuery(this).attr("id");
                    var string = \'id=\'+ id;
                    jQuery.ajax({
                       type: "POST",
                       url: "index.php?module=wall&action=delete&id=" + id,
                       data: string,
                       cache: false,
                       success: function(ret){
                           if(ret == "true") {
                               commentContainer.slideUp(\'slow\', function() {jQuery(this).remove();});
                           } else {
                               alert(ret);
                           }
                      }
                    });
                    return false;
                });
            });
        </script>';
        $this->appendArrayVar('headerParams', $script);
    }

    /**
     *
     * Load the javascript for running the comments. It appends the script to the
     * page header
     *
     * @access public
     * @return VOID
     *
     */
    public function loadCommentScript()
    {
        $youSaid = $this->objLanguage->languageText("mod_wall_yousaid", "wall", "You said");
        $secsAgo = $this->objLanguage->languageText("mod_wall_secsago", "wall", "a few seconds ago");
        // Get an ajax Loading icon.
        $icon = $this->newObject('getIcon','htmlelements');
        //$icon->setIcon("loading_circles_big");
        $icon->setIcon("loading_bar");
        $loadingImage = $icon->show();
        $loadingImage = str_replace('"', '\'', $loadingImage);
        $script = '<script type="text/javascript" >
            jQuery(function() {
                // Show the post box and submit button
                jQuery(".wall_comment_button").live("click", function(){
                    var element = jQuery(this);
                    var id = element.attr("id");
                    jQuery("#c__"+id).slideToggle(300);
                    jQuery(this).toggleClass("active");
                    return false;
                });
                
                // Get additional comments via ajax
                jQuery(".wall_comments_more").live("click", function(){
                    var id = jQuery(this).attr("id");
                    var fixedid = id.replace("mrep__", "");
                    jQuery.ajax({
                        type: "POST",
                        url: "index.php?module=wall&action=morecomments&id=" + fixedid,
                        success: function(ret) {
                            jQuery("#wct_"+fixedid).append(ret);
                            jQuery("#"+id).slideToggle(300);
                        }
                    });

                });

                // Delete a comment
                jQuery(".wall_delcomment").live("click", function(){
                    var id = jQuery(this).attr("id");
                    jQuery.ajax({
                        type: "POST",
                        url: "index.php?module=wall&action=deletecomment&id="+id,
                        success: function(ret) {
                            if (ret==\'true\') {
                                //alert("#wct__"+id);
                                jQuery("#cmt__"+id).slideToggle(300);
                            } else {
                                alert(ret);
                            }
                        }
                    });
                });

                // Post the comment
                jQuery(".comment_submit").live("click", function(){
                    var id = jQuery(this).attr("id");
                    var fixedid = id.replace("cb_", "");
                    var comment_text = jQuery("#ct_"+id).val();
                    if(comment_text.length == 0) {
                        return;
                    } else {

                        jQuery("#ct_"+id).attr("disabled", "disabled");
                        var tmpHolder = jQuery("#c__"+fixedid).html();
                        jQuery("#c__"+fixedid).html("' . $loadingImage . '");


                        jQuery.ajax({
                            type: "POST",
                            url: "index.php?module=wall&action=addcomment&id=" + id,
                            data: "comment_text="+comment_text,
                            success: function(ret) {
                                if(ret == "true") {
                                    // The comment blocks have ids starting with wct_
                                    if ( jQuery("#wct_"+fixedid).length > 0 ) {
                                        jQuery("#wct_"+fixedid).prepend(\'<li><b><span class="wall_comment_author">' 
                                          . $youSaid . '</span></b>&nbsp; \'+comment_text+\'&nbsp;<div class="wall_comment_when"><strong>'
                                          . $secsAgo . '</strong></div></li>\');
                                        jQuery("#c__"+fixedid).slideToggle(300);
                                    } else {
                                        if ( jQuery("#wpr__"+fixedid).length > 0 ) {
                                            jQuery("#wpr__"+fixedid).append(\'<br /><br />'
                                              . '<div class="wall_comments_top"></div><ol class="wall_replies" id="wct_\'+fixedid+\'">'
                                              . '<li><b><span class="wall_comment_author">' . $youSaid
                                              . '</span></b>&nbsp;\'+comment_text+\'<div class="wall_comment_when"><strong>'
                                              . $secsAgo . '</strong></div></li></ol>\');
                                            jQuery("#c__"+fixedid).slideToggle(300);
                                        } else {
                                            // We should never be able to get here
                                            alert(\'There is nothing to append to. Reload the page and try again.\');
                                        }
                                    }

                                } else {
                                    alert(ret);
                                }
                                jQuery("#c__"+fixedid).html(tmpHolder);
                                jQuery("#ct_"+id).val("");
                                jQuery("#ct_"+id).attr("disabled", "");
                            }
                        });
                        return false;
                    }
                });
            });
            </script>';
         $this->appendArrayVar('headerParams', $script);
    }

    /**
     *
     * Return the comment box area or panel. There is a bit of trickery here with
     * the use of $id to generate the code to pass to the Ajax.
     *
     * @param string $id The parent id of the wall post to which the comment applies
     * @return string The formatted panel.
     *
     */
    public function getReplyLink($id)
    {
        $panel = '<div class=\'wall_panel\' id="c__' . $id . '">'
          . '<textarea id=\'ct_cb_' . $id . '\' style="width:390px;height:23px"></textarea><br />
            <input type="submit" value=" Comment " class="comment_submit" id="cb_' . $id . '"/>
            </div>';
        return $panel;
    }

    /**
     *
     * Render the comment link
     *
     * @param string $id The parent id of the wall post to which the comment applies
     * @return string  The formatted button
     *
     */
    public function getCommentDisplayButton($id)
    {
        $button = '<a href="#" class="wall_comment_button" id="'
          . $id . '">Comment</a>';
        return $button;
    }

    public function getDelCommentLink($id, $commentor, $wallOwner, $wallType)
    {
        $userId = $this->objUser->userId();
        $delLink = $this->uri(array(
          'action' => 'deletecomment',
          'id' => $id
        ), 'wall');
        $delLink="javascript:void(0);";
        $delLink = str_replace('&amp;', '&', $delLink);
        $delLink = '<a class="wall_delcomment" id="'
          . $id . '" href="'
          . $delLink . '">x</a>';

        switch ($wallType) {
            case '0':
                if ($userId == $commentor || $this->objUser->isAdmin()) {
                    return $delLink;
                } else {
                    return NULL;
                }
            case '1':
                if ($userId == $commentor || $userId == $wallOwner) {
                    return $delLink;
                } else {
                    return NULL;
                }
                break;
            default:
                return NULL;
                break;
        }
    }
}
?>