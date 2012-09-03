<?php
/**
 *
 * A simple blog editor for Simple Blog
 *
 * A simple blog editor for Simple Blog which provides the user interface for
 * adding and editing blog posts
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
 * @package   simpleblog
 * @author    Administrative User admin@localhost.local
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
 * A simple blog editor for Simple Blog
 *
 * A simple blog editor for Simple Blog which provides the user interface for
 * adding and editing blog posts
*
* @package   simpleblog
* @author    Administrative User admin@localhost.local
*
*/
class simpleblogops extends object
{

    /**
     *
     * @var string Object $objDbPosts String for the model object
     * @access public
     *
     */
    public $objDbPosts;
    /**
     *
     * @var string Object $objUser String for the user object
     * @access public
     *
     */
    public $objUser;
    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;
    /**
     *
     * @var string Object $objDd String for the date-difference object (for human dates)
     * @access public
     *
     */
    public $objDd;
    /**
     *
     * @var string $hasEditAddDelRights Does the user have full access rights
     * @access private
     *
     */
    private $hasEditAddDelRights=FALSE;

    /**
    *
    * Intialiser for the simpleblog ops class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the language object
        $this->objLanguage = $this->getObject('language', 'language');
        // Get an instance of the user object
        $this->objUser = $this->getObject('user', 'security');
        // Get the blog posts db.
        $this->objDbPosts = $this->getObject('dbsimpleblog', 'simpleblog');
        // Set the jQuery version to the latest functional.
        //$this->setVar('JQUERY_VERSION', '1.4.2');
        // Load jQuery Oembed.
        $oEmb = $this->getObject('jqoembed', 'oembed');
        $oEmb->loadOembedPlugin();
        
        $me = $this->objUser->fullName();

        //$me =  "\n\n<span class='wallposter'>" . $me . "</span>";
        $youSaid = $this->objLanguage->languageText("mod_wall_yousaid", "wall", "You said");
        $secsAgo = $this->objLanguage->languageText("mod_wall_secsago", "wall", "a few seconds ago");
        $nothingApppendTo = $this->objLanguage->languageText("mod_wall_nothingappendto", "wall",
          "There is nothing to append to. Reload the page and try again.");

        // Get the page number
        $page = $this->getParam('page', 0);

        // Set some parameters required by the wall
        $ar = array(
            'wallType' => '4',
            'me' => $me,
            'page' => $page,
            'youSaid' => $youSaid,
            'secsAgo' => $secsAgo,
            'nothingApppendTo' => $nothingApppendTo
            );
        $this->phpToJs($ar);
        // Load the functions specific to this module.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('simpleblog.js', 'simpleblog'));
        // Load the OEMBED parser
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('oembed.js', 'simpleblog'));
        // Load the WALL Javascript which we need
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('wall.js', 'wall'));
    }


    /**
     *
     * Create the edit link
     *
     * @param string $id The id (primary key) of the post to edit
     * @return string The rendered edit link with icon
     * @access private
     *
     */
    private function builtEditLink($id)
    {
        $editUri = $this->uri(array(
          'action' =>'edit',
          'mode' => 'edit',
          'postid' => $id), 'simpleblog');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        $edIcon = $this->objIcon->getEditIcon($editUri);
        return '<span id="' . $id . '" class="simpleblog_editicon">'
          . $edIcon . '</span>';
    }

    /**
     *
     * Create the delete link
     *
     * @param string $id The id (primary key) of the post to delete
     * @return string The rendered delete link with icon
     *
     */
    public function buildDeleteLink($id)
    {
        
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        $delUri = 'javascript:void(0);';
        $delIcon = $this->objIcon->getDeleteIcon($delUri);
        return '<span id="' . $id . '" class="simpleblog_delicon">'
          . $delIcon . '</span>';
    }

    /**
     *
     * Create a header script with Javascript values corresponding to the
     * PHP variables passed in the array.
     *
     * @param mixed Array $params An array of parameters and values
     * @return VOID
     *
     */
    public function phpToJs($params = array())
    {
        if (!empty ($params)) {
            $script = '<script type="text/javascript">
            // <![CDATA[
            ';
            foreach ($params as $key=>$value) {
                $script .= "{$key} = '{$value}';";
            }
            $script = $script . "\n// ]]>\n</script>\n\n";
        }
        $this->appendArrayVar('headerParams', $script);
    }

    /**
     *
     * Show the last N posts, specified by the number stored in sysconfig.
     *
     * @return string The rendered text of the last N posts
     * @access public
     *
     */
    public function showCurrentPosts($blogId)
    {
        // Get an instance of the humanize date class.
        $this->objDd = $this->getObject('translatedatedifference', 'utilities');
        $posts = $this->objDbPosts->getPosts($blogId, 1, 10);
        $ret ="";
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
        } else {
            $ret = "nodata";
        }
        return $ret;
    }

    /**
     *
     * Format a post item
     *
     * @param string $post The post entry to format
     * @return string The formatted post
     * @access public
     * 
     */
    public function formatItem($post)
    {
        $before = "<div class='simpleblog_post_before'></div>\n";
        $id = $post['id'];
        $blogId = $post['blogid'];
        $bloggerId = $post['userid'];
        $blogType = $post['post_type'];
        if ($this->checkEditAddDelRights($bloggerId, $blogType)) {
            $edit = $this->builtEditLink($id);
            $del = $this->buildDeleteLink($id);
            $edel = "<div class='simpleblog_edel'>" . $edit . $del . "</div>";
        } else {
            $edel=NULL;
        }
        $title = "<div class='simpleblog_post_title'>"
          . $post['post_title'] . $edel . "</div>\n";
        $content = "<div class='simpleblog_post_content'>"
          . $post['post_content'] . "</div>\n";
        $poster = $post['firstname'] . " " . $poster = $post['surname'];
        $poster = $this->objLanguage->languageText("mod_simpleblog_postedby",
                "simpleblog", "Posted by")
                . " " . $poster;
        // Convert the post date into human time.
        $postDate = $this->objDd->getDifference($post['datecreated']);
        $editDate = $post['datemodified'];
        if ($editDate !==NULL) {
            $editDate = $this->objDd->getDifference($editDate);
        }
        $foot = "\n<div class='simpleblog_post_footer'>{$poster} {$postDate}</div>\n";

        $wallText = "<span class='simpleblog_view_wall'>View wall</span>";


        //$numPosts = $this->objDbwall->countPosts(4, FALSE, 'identifier', $id);
        $numPosts = $post['comments'];

        if ($numPosts == 0) {
            $numPosts = "<span class='simpleblog_wall_comments'>No comments yet<span>";
        } else {
            $numPosts = "<span class='simpleblog_wall_comments'>This post has {$numPosts} comments.<span>";
        }

        $wall = "\n<div class='simpleblog_wall_nav' id='simpleblog_wall_nav_{$id }'>"
        . "<a class='wall_link' id='wall_link_{$id }' href='javascript:void(0);'>"
        . $wallText . $numPosts . "</div><div class='simpleblog_wall' id='simpleblog_wall_{$id }'></div>\n";

        return "<div class='simpleblog_post_wrapper' id='wrapper_{$id}'>\n"
          . $before . $title . $content . $foot . $wall
          . "</div>\n\n";
    }

    private function checkEditAddDelRights($bloggerId, $blogType)
    {
        // If they are not logged in they cannot have rights.
        if ($this->objUser->isLoggedIn()) {
            $this->objSec = $this->getObject('simpleblogsecurity', 'simpleblog');
            if (!$this->objSec->checkBloggingRights()) {
                // If they don't have any blogging rights, then no edit/del.
                return FALSE;
            } else {
                // Check if they have rights to THIS post.
                $userId = $this->objUser->userId();
                $res = $this->objSec->checkRights($bloggerId, $userId, $blogType);
                return $res;
            }
        } else {
            return FALSE;
        }
    }


    /**
     *
     * Return a response when the user of the requested blog has not yet
     * created a blog post.
     *
     * @return string The response text
     * @access public
     * 
     */
    public function noBlogYet($isCurrentBlogger=FALSE)
    {
        if ($isCurrentBlogger) {
            $ret = '<div class="warning">'
              . $this->objLanguage->languageText(
                "mod_simplelblog_yourfirstblog",
                "simpleblog",
                "You have no blog posts. You can create one now. Please take the time to enter a discription of your blog before you make your first post."
             ) . '</div>';
            // The edit form.
            $objDesc = $this->getObject('editdescription', 'simpleblog');
            $ret .= $objDesc->getForm(TRUE);
            return $ret;
        } else {
            return $this->objLanguage->languageText(
                "mod_simplelblog_noblogyet",
                "simpleblog",
                "The user has not yet created a blog");
        }

    }
}
?>