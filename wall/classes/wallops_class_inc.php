<?php
/**
 *
 * A simple wall module operations object
 *
 * A simple wall module that makes use of OEMBED and that tries to look a bit
 * like Facebook's wall. This is the operations class.
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


    /**
    *
    * Intialiser for the wall database connector
    * @access public
    *
    */
    public function init()
    {
        // Create an instance of the database class.
        $this->objDbwall = & $this->getObject('dbwall', 'wall');
        // Load jQuery Oembed.
        $oEmb = $this->getObject('jqoembed', 'oembed');
        $oEmb->loadOembedPlugin();
        // Load the functions specific to this module.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('functions.js'));
        // Instantiate the user object.
        $this->objUser = $this->getObject('user', 'security');
        // Instantiate the language object.
        $this->objLanguage = & $this->getObject('language', 'language');
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
        $objDd = $this->getObject('translatedatedifference', 'utilities');
        $objCommentDb = $this->getObject('dbcomment', 'wall');
        // See if they are in myprofile else default link to wall
        $currentModule = $this->getParam('module', 'wall');
        // Get the current user Id
        $myUserId = $this->objUser->userId();
        foreach ($posts as $post) {
            $comments = NULL;
            $showMoreReplies = NULL;
            $img = $this->objUser->getSmallUserImage($post['posterid'], FALSE);
            $when = $objDd->getDifference($post['datecreated']);
            $fullName = $post['firstname'] . " " . $post['surname'];
            $id = $post['id'];
            $replies = $post['replies'];
            // If there are fewer than 3 replies, show them all, otherwise get last 3
            if ($replies > 0) {
                // Get the last three replies.
                $commentAr = $objCommentDb->getComments($id, 3);
                if ($replies > 3) {
                    // Let us know that there are more
                    $moreReplies = $replies - 3;
                    $showMoreReplies = "View all $moreReplies replies."; // MULTILINGUALISE-------------------------------------------------------
                }
                foreach ($commentAr as $comment) {
                    $commentWhen = $objDd->getDifference($post['datecreated']);
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
                    $commentFn = '<a href="' . $cfnLink . '">' . $commentFn . '</a>';
                    $comments .= "<li><span class='wall_comment_author'>" 
                    . $commentFn . "</span>&nbsp;&nbsp;" . $comment['wallcomment']
                    . "<br /><div class='wall_comment_when'>"
                    . $commentWhen . "</div></li>";
                }
                $comments = $this->createCommentBlock($comments);
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
            if ($myUserId == $post['posterid']) {
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
            $ret .= "<div class='wallpostrow'>$del<div class='msg'>\n" . $img
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


    public function createCommentBlock($comments)
    {
        $blockTop = "\n\n<div class='wall_comments_top'></div><ol class='wall_replies'>";
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
                    var string = \'id=\'+ id ;
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
        $script = '<script type="text/javascript" >
            jQuery(function() {
                jQuery(".wall_comment_button").click(function(){
                    var element = jQuery(this);
                    var id = element.attr("id");
                    jQuery("#c__"+id).slideToggle(300);
                    jQuery(this).toggleClass("active");
                    return false;
                });
                jQuery(".comment_submit").click(function(){
                    var id = jQuery(this).attr("id");
                    var comment_text = jQuery("#ct_"+id).val();
                    if(comment_text.length == 0) {
                        return;
                    } else {
                        jQuery.ajax({
                            type: "POST",
                            url: "index.php?module=wall&action=addcomment&id=" + id,
                            data: "comment_text="+comment_text,
                            success: function(ret) {
                                if(ret == "true") {
                                   alert("comment posted");
                                } else {
                                   alert(ret);
                                }
                                alert(msg);
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
        $button = '<a href="#" class="wall_comment_button" id="' . $id . '">Comment</a>';
        return $button;
    }
}
?>