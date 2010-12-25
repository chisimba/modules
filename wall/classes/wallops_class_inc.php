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


    /**
    *
    * Intialiser for the wall database connector
    * @access public
    *
    */
    public function init()
    {
        // Create an instance of the database class
        $this->objDbwall = & $this->getObject('dbwall', 'wall');
        // Load jQuery Oembed
        $oEmb = $this->getObject('jqoembed', 'oembed');
        $oEmb->loadOembedPlugin();
        // Load the functions specific to this module
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('functions.js'));
        $this->loadScript();
        // Instantiate the user object
        $this->objUser = $this->getObject('user', 'security');
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
        $ret ="\n\n<div id='wrapper'>\n" . $this->showPostBox() . "<div id='wall'>\n";
        $posts = $this->objDbwall->getWall($wallType, $num);
        foreach ($posts as $post) {
            $img = $this->objUser->getSmallUserImage($post['posterid'], FALSE);
            $ret .= "<div class='msg'>\n" . $img . $post['wallpost'] . "</div>\n";
        }
        $ret .="</div>\n</div>\n\n";
        return $ret;
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
            $ret = '<div id=\'updateBox\'>
            Enter Your Message here...
            <textarea id=\'wallpost\'></textarea>
            <button id=\'shareBtn\'>Share</button>
            (No HTML, only links and text)
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
        
        $target = $this->uri(array(
            'action' => 'save',
            'walltype' => $wallType
        ), 'wall');
        $target = str_replace('&amp;', "&", $target);
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
						jQuery("#wall").prepend("<div class=\'msg\'>"+status_text+"</div>");
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
}
?>