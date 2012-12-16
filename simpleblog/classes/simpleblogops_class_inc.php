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
        
        // Load the link class used by several methods
        $this->loadClass('link', 'htmlelements');
        
        // Get the jQuery Twitter button object
        $this->objTweetButton = $this->getObject('tweetbttn', 'socialweb');
        // Get the google plus button
        $this->objPlusButton = $this->getObject('gplusbttn', 'socialweb');

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
        //$this->appendArrayVar('headerParams', $this->getJavaScriptFile('oembed.js', 'simpleblog'));
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
     * Show the last N posts, specified by the number stored in sysconfig for
     * a particular blog given user identified by userid in the querystring.
     *
     * @param string $blogId The blog to show from
     * @param string $userId The userid of the user to show from
     * @return string The rendered text of the last N posts
     * @access public
     *
     */
    public function getPostsByUser($blogId, $userId) 
    {
        $page = $this->getParam('page', 1);
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $pageSize = $objSysConfig->getValue('simpleblog_numpostdisplay', 'simpleblog');
        $posts = $this->objDbPosts->getPostsByUser($blogId, $userId, $page, $pageSize);
        $recs = $this->objDbPosts->getRecordCount(" WHERE blogid='$blogId' AND userid='$userId'");
        $totalPages = ceil($recs/$pageSize);
        $nextLink = NULL;
        $prevLink = NULL;
        $counter = 0;
        $pageList = NULL;
        $wordPage = $this->objLanguage->languageText("word_page", "system", "Page");
        $wordStart = $this->objLanguage->languageText("word_start", "system", "Start");
        $wordEnd = $this->objLanguage->languageText("word_end", "system", "End");
        while ($counter < $totalPages) {
            $counter++;
            $pgUri = $this->uri(array(
                'page' => $counter,
                'by' => 'user',
                'userid' => $userId
              ), 'simpleblog'
            );
            if ($counter == $page) {
                $pageList .= " [$counter] ";
            } else {
                $pageList .= " <a href='$pgUri'>$counter</a> ";
            }
            
        }
        $pageList = "<div class='simpleblog_allpages'>$pageList</div>";
        if ($page < $totalPages) {
            // There is a next page
            $nextPage = $page + 1;
            $nUri = $this->uri(array(
                'page' => $nextPage,
                'by' => 'user',
                'userid' => $userId
              ), 'simpleblog');
            $nextLink = "<a href='$nUri'>$wordPage $nextPage</a>";
        } else {
            $nextLink = $wordEnd;
        }
        if ($page > 1) {
            // There is a previous page
            $prevPage = $page - 1;
            $nUri = $this->uri(array('page' => $prevPage), 'simpleblog');
            $prevLink = "<a href='$nUri'>$wordPage $prevPage</a>";
        } else {
            $prevLink = $wordStart;
        }
        $ret ="";
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $ret .= "<table class='simpleblog_pagenav'><tr>"
              . "<td class='simpleblog_prev'>"
              . "$prevLink</td><td class='simpleblog_allpages'>"
              . "$pageList</td><td class='simpleblog_next'>"
              . "$nextLink</td></tr></table>";
        } else {
            $ret = NULL;
        }
        return $ret;
    }

    /**
     *
     * Show the last N posts, specified by the number stored in sysconfig.
     *
     * @param string $blogId The blog to show from
     * @return string The rendered text of the last N posts
     * @access public
     *
     */
    public function showCurrentPosts($blogId, $userId=FALSE)
    {
        $page = $this->getParam('page', 1);
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $pageSize = $objSysConfig->getValue('simpleblog_numpostdisplay', 'simpleblog');
        $posts = $this->objDbPosts->getPosts($blogId, $page, FALSE);
        $recs = $this->objDbPosts->getRecordCount(" WHERE blogid='$blogId' ");
        $totalPages = ceil($recs/$pageSize);
        $nextLink = NULL;
        $prevLink = NULL;
        $counter = 0;
        $pageList = NULL;
        $wordPage = $this->objLanguage->languageText("word_page", "system", "Page");
        $wordStart = $this->objLanguage->languageText("word_start", "system", "Start");
        $wordEnd = $this->objLanguage->languageText("word_end", "system", "End");
        while ($counter < $totalPages) {
            $counter++;
            $pgUri = $this->uri(array('page' => $counter), 'simpleblog');
            if ($counter == $page) {
                $pageList .= " [$counter] ";
            } else {
                $pageList .= " <a href='$pgUri'>$counter</a> ";
            }
            
        }
        $pageList = "<div class='simpleblog_allpages'>$pageList</div>";
        if ($page < $totalPages) {
            // There is a next page
            $nextPage = $page + 1;
            $nUri = $this->uri(array('page' => $nextPage), 'simpleblog');
            $nextLink = "<a href='$nUri'>$wordPage $nextPage</a>";
        } else {
            $nextLink = $wordEnd;
        }
        if ($page > 1) {
            // There is a previous page
            $prevPage = $page - 1;
            $nUri = $this->uri(array('page' => $prevPage), 'simpleblog');
            $prevLink = "<a href='$nUri'>$wordPage $prevPage</a>";
        } else {
            $prevLink = $wordStart;
        }
        $ret ="";
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
            $ret .= "<table class='simpleblog_pagenav'><tr>"
              . "<td class='simpleblog_prev'>"
              . "$prevLink</td><td class='simpleblog_allpages'>"
              . "$pageList</td><td class='simpleblog_next'>"
              . "$nextLink</td></tr></table>";
        } else {
            $ret = "nodata";
        }
        return $ret;
    }
    
    /**
     *
     * Show all the posts for the current month
     * 
     * @param string $blogId The blog to show from
     * @return string The formatted posts for viewing
     * @access public
     * 
     */
    public function showThisMonth($blogId) 
    {
        $posts = $this->objDbPosts->getCurrentMonth($blogId);
        if (count($posts) > 0) {
            $ret ="";
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
        } else {
            $ret = NULL;
        }
        return $ret;
    }
    
    /**
     *
     * Show all the posts for the previous month
     * 
     * @param string $blogId The blog to show from
     * @return string The formatted posts for viewing
     * @access public
     * 
     */
    public function showLastMonth($blogId) 
    {
        $posts = $this->objDbPosts->getLastMonth($blogId);
        if (count($posts) > 0) {
            $ret ="";
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
        } else {
            $ret = NULL;
        }
        return $ret;
    }
    
    /**
     *
     * Show archive list of posts
     * 
     * @param string $blogId The blog to show from
     * @return string The formatted posts for viewing
     * @access public
     * 
     */
    public function showArchiveList($blogId) 
    {
        $ar = $this->objDbPosts->getArchivePosts($blogId);
        $lastMonth = $this->objLanguage->languageText("mod_simpleblog_lastmo", "simpleblog", "Last month");
        $thisMonth = $this->objLanguage->languageText("mod_simpleblog_thismo", "simpleblog", "This month");
        $blogHome = $this->objLanguage->languageText("mod_simpleblog_home", "simpleblog", "Blog home");
        $uri = $this->uri(array(
          'by' => 'thismonth'
        ), 'simpleblog');
        $ret = "<a href='$uri' alt='$thisMonth'>$thisMonth</a><br />";
        $uri = $this->uri(array(
          'by' => 'lastmonth'
        ), 'simpleblog');
        $ret .= "<a href='$uri' alt='$lastMonth'>$lastMonth</a><br />"; 
        $uri = $this->uri(array(), 'simpleblog');
        $ret .= "<a href='$uri' alt='$blogHome'>$blogHome</a><br /><hr />"; 
        if (count($ar) > 0) {
            $ret = $ret . "\n\n<ul>";
            foreach ($ar as $item) {
                $uri = $this->uri(array(
                  'by' => 'archive',
                  'year' => $item['year'],
                  'month' => $item['month']
                ), 'simpleblog');
                $linkTxt = $item['month'] . ' ' . $item['year'];
                $ret .= "<li><a href='$uri' alt='$linkTxt'>$linkTxt</a></li>";
            }
            $ret = $ret . "</ul>";
        } else {
            $ret = NULL;
        }
        return "<div class='simpleblog_archive'>" . $ret . "</div>";
    }
    
    /** 
     * 
     * Show all posts for a given month and year for a given blogid.
     * 
     * @param string $blogId  The blog to show from
     * @param type $year The year to show
     * @param type $month The month in that year to show
     * @return string The formatted posts for viewing
     * @accesss public
     * 
     */
    public function showArchive($blogId, $year, $month) 
    {
        $posts = $this->objDbPosts->getPostsByYearMonth($blogId, $year, $month);
        if (count($posts) > 0) {
            $ret ="";
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
        } else {
            $ret = NULL;
        }
        return $ret;
    }
    
    /**
     * 
     * Get posts for a given tag.
     * 
     * @param string $blogId The blog to show from
     * @param type $tag The tag to lookup
     * @return string The formatted posts
     * 
     */
    public function showTag($blogId, $tag) 
    {
        $posts = $this->objDbPosts->getPostsByTag($blogId, $tag);
        if (count($posts) > 0) {
            $ret ="";
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
        } else {
            $ret = NULL;
        }
        return $ret;
    }
    
    /**
     * 
     * Get a post for a given id record.
     * 
     * @param string $id The record id to show 
     * @return string The formatted posts
     * @access public
     * 
     */
    public function showById($id) 
    {
        $posts = $this->objDbPosts->getPostsById($id);
        if (count($posts) > 0) {
            $ret ="";
            foreach ($posts as $post) {
                $ret .= $this->formatItem($post);
            }
            $fbLike = $this->getObject('fblikebttn', 'socialweb');
            $ret .= $fbLike->activateButtons();
        } else {
            $ret = NULL;
        }
        
        return $ret;
    }
    
    /**
     * 
     * Retrieve the posts from a search result and format
     * them for display
     * 
     * @param string $blogId The blog we are searching
     * @return string The formatted search results (or NULL if none)
     * @access public
     * 
     */
    public function getPostsFromSearch($blogId)
    {
        $searchTerm = $this->getParam('searchphrase', FALSE);
        if ($searchTerm) {
            $searchTerm = trim($searchTerm);
            $posts = $this->objDbPosts->getPostsFromSearch($blogId, $searchTerm);
            return $this->formatSearchResults($blogId, $searchTerm, $posts);
        } else {
            return NULL;
        }
    }
    
    /**
     * 
     * Fomat search results
     * 
     * @param string $blogId The blog we searched
     * @param string $searchTerm The terms we searched for
     * @param string Array $posts An array of results
     * @return string The formatted results for display
     * @access public
     * 
     */
    public function formatSearchResults($blogId, $searchTerm, $posts)
    {
        $ret = "<h3>Searching for <em>$searchTerm</em> in blog <em>$blogId</em></h3>";
        $searchTerm = preg_replace("/[^A-Za-z0-9\s\s+]/","",$searchTerm);
        $searchTerm = urlencode($searchTerm);
        if(count($posts) > 0) {
            $ret .= "<div class='sb_searchresults'>";
            // Get an instance of the humanize date class.
            $objDd = $this->getObject('translatedatedifference', 'utilities');
            foreach ($posts as $post) {
                $id = $post['id'];
                $titleUri = $this->uri(array(
                    'by' => 'id',
                    'id' => $id,
                    'highlight' =>  $searchTerm
                ), 'simpleblog');
                $postTitle = $post['post_title'];
                $postTitle = '<a href="' . $titleUri . '" alt="' . $postTitle 
                  . '">' . $postTitle . '</a>';
                // The author
                $poster = $post['firstname'] . " " . $poster = $post['surname'];
                $poster = $this->objLanguage->languageText("mod_simpleblog_postedby",
                  "simpleblog", "Posted by")
                . " " . $poster;
                // Convert the post date into human time.
                $postDate = $objDd->getDifference($post['datecreated']);
                $editDate = $post['datemodified'];
                if ($editDate !==NULL) {
                    $editDate = $objDd->getDifference($editDate);
                }
                $ret .= "<div class='sb_searchresult'>" . $postTitle 
                  . "<br />&nbsp;&nbsp;&nbsp;" . $poster. " " . $editDate . "</div>";

        
            }
            $ret = $ret . "</div>";
        } else {
           $ret .= "<br /><span class='warning'>No results</span>";
        }
        return $ret;
    }
    
    /**
     * 
     * Get Tag cloud for a blog identified by blogid
     * 
     * @param string $blogId The blog to get tag cloud for
     * @return string Formatted tag cloud
     * @access public
     * 
     */
    public function getTagCloud($blogId) {
        $cloudAr = $this->objDbPosts->getTagCloudArray($blogId);
        $ret = '';
        $maxFreq = $this->getMax($cloudAr);
        foreach ($cloudAr as $tag=>$freq) {
            $cssClass = 'tag';
            $pct = floor(($freq/$maxFreq) * 100);
             //round the number to the nearest 10  
            $pct =  round($pct,-1);  
            $uri = $this->uri(array(
                'blogid' => $blogId,
                'by' => 'tag',
                'tag' => $tag
            ), 'simpleblog');
            $ln = "<a href='$uri' class='$cssClass' id='$tag' alt='$tag'>$tag</a> ";
            $ret .= ' <span class="tag_' . $pct . '">' . $ln . '</span> ';
        }
        return $ret;
    }
    
    /**
     * 
     * Get the maximum frequency of occurrence
     * 
     * @param string Array $cloudAr A 2-d array of tags and frequency
     * @return integer Maximum frequency
     * @access private
     */
    private function getMax($cloudAr) 
    {
        $chAr = array();
        if (!empty($cloudAr)) {
            foreach ($cloudAr as $tag=>$freq) { 
                $chAr[] = $freq;
            }
            return max($chAr);
        } else {
            return NULL;
        }
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
        // Get an instance of the humanize date class.
        $objDd = $this->getObject('translatedatedifference', 'utilities');
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
        $postTitle = $post['post_title'];
        // The URI for this post
        $titleUri = $this->uri(array(
            'by' => 'id',
            'id' => $id
        ), 'simpleblog');
        // The retweet button style
        $style = 'vertical';
        $via = NULL;
        $related = NULL;
        // The retweet button
        $rt = $this->objTweetButton->getButton($postTitle, $style, $via, $related, htmlspecialchars_decode($titleUri));
        // Google plus button
        $plsBtn = $this->objPlusButton->getButton('tall', htmlspecialchars_decode($titleUri));
        
        $by = $this->getParam('by', NULL);
        if ($by !== 'id') {
            $postTitle = '<a href="' . $titleUri . '" alt="' . $postTitle 
              . '">' . $postTitle . '</a>';
        } else {
            $this->setVar('og_title', $postTitle);
            $this->setVar('og_content', $post['post_content']);
        }
        $clear = ' <br style="clear:both;" />';
        $title = "<div class='simpleblog_post_title'><div class='titletxt'>"
          . $postTitle . "</div><div class='social_buttons'>" . $edel 
          . $plsBtn . '</div>' . $rt . "</div>\n";
        
        $objWashout = $this->getObject('washout', 'utilities');
        $content = $objWashout->parseText($post['post_content']);
        // If we are coming from a search
        $hilite = urldecode($this->getParam('highlight', FALSE));
        if ($hilite) {
            $content = str_replace($hilite, '<span class="hilite">' . $hilite . '</span>', $content);
        }
        
        $content = "<div class='simpleblog_post_content'>"
          . $content . "</div>\n";
        $poster = $post['firstname'] . " " . $poster = $post['surname'];
        $link = new link ($this->uri(array(
            'by'=>'user', 
            'userid'=>$bloggerId), 
          'simpleblog')
        );
        $link->link = $poster;
        $poster = $link->show();
        $poster = $this->objLanguage->languageText("mod_simpleblog_postedby",
                "simpleblog", "Posted by")
                . " " . $poster;
        // Convert the post date into human time.
        $postDate = $objDd->getDifference($post['datecreated']);
        $editDate = $post['datemodified'];
        if ($editDate !==NULL) {
            $editDate = $objDd->getDifference($editDate);
        }
        
        $postTags = $post['post_tags'];
        if (!$postTags == "") {
            $postTags = $this->formatTags($postTags, $blogId);
        }
        $tags = "\n<div class='simpleblog_post_tags'>" . $postTags . "</div>\n";
        $foot = "\n<div class='simpleblog_post_footer'>{$poster} {$postDate}</div>\n";
        
        $viewWall = $this->objLanguage->languageText("mod_simpleblog_viewwall",
                "simpleblog", "Blog wall");
        $wallText = "<span class='simpleblog_view_wall'>$viewWall</span>";

        //$numPosts = $this->objDbwall->countPosts(4, FALSE, 'identifier', $id);
        $numPosts = $post['comments'];

        if ($numPosts == 0) {
            $numPosts = "<span class='simpleblog_wall_comments'>No comments yet<span>";
        } else {
            $numPosts = "<span class='simpleblog_wall_comments'>This post has {$numPosts} comments.<span>";
        }

        $wall = "\n<div class='simpleblog_wall_nav' id='simpleblog_wall_nav_{$id }'>"
        . "<a class='wall_link' id='wall_link_{$id }' href='javascript:void(0);'>"
        . $wallText . $numPosts . "</a></div><div class='simpleblog_wall' id='simpleblog_wall_{$id }'></div>\n";
        $fbL = $this->getObject('fblikebttn', 'socialweb');
        // Add the Facebook like button.
        $fbLikeButton = $fbL->getButton($titleUri);
        return "<div class='simpleblog_post_wrapper' id='wrapper_{$id}'>\n"
          . $before . $title . $fbLikeButton . $content . $tags . $foot . $wall
          . "</div>\n\n";
    }
    
    /**
     * 
     * Format the tags for display under the post
     * 
     * @param string array $postTags Array of tages and frequencies
     * @param string $blogId The blog to get tags from
     * @return string Formatted tag group for post
     * @access private
     * 
     */
    private function formatTags($postTags, $blogId) 
    {
        $tagsAr = explode(',', $postTags);
        $ret ="";
        $viewingTag = $this->getParam('tag', FALSE);
        foreach ($tagsAr as $tag) {
            $tag = trim($tag);
            $cssClass = 'tag';
            if ($viewingTag) {
                if ($viewingTag == $tag) {
                    $cssClass = 'tag_active';
                }
            }
            $uri = $this->uri(array(
                'blogid' => $blogId,
                'by' => 'tag',
                'tag' => $tag
            ), 'simpleblog');
            $ret .= "<a href='$uri' class='$cssClass' id='$tag' alt='$tag'>$tag</a> ";
        }
        return $ret;
    }

    /**
     *
     * Check the rights of the user
     * 
     * @param string $bloggerId The userId of the blogger
     * @param string $blogType The type of blog (personal, site, context)
     * @return boolean TRUE|FALSE
     * @access private
     * 
     */
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
    
    /**
     * 
     * Render a search box for use in a search block
     * 
     * @return string The rendered search box
     * @access public
     * 
     */
    public function renderSearchBox()
    {
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        // The URI for the search
        $formAction = $this->uri(array(
          'by' => 'search'
        ), 'simpleblog');
        
        // The word search
        $wordSearch = $this->objLanguage->languageText('word_search');
        
        //Create the form element.
        $objForm = new form('sb_search');
        $objForm->extra = ' class="sb_searchform" ';
        $objForm->setAction($formAction);
        $objForm->displayType=3;
        
        // Create the search input.
        $objSearchInp = new textinput('searchphrase', $wordSearch . "...");
        $objSearchInp->setCss('sb_searchfield');
        $extra = 'onfocus="if (this.value == \'' . $wordSearch . '...\') {this.value = \'\';}"'
         . ' onblur="if (this.value == \'\') {this.value = \'' . $wordSearch . '...\';}"';
        $objSearchInp->extra = $extra;
        $objForm->addToForm($objSearchInp->show());
       
        //Add a save button
        $objButton = $this->newObject('button', 'htmlelements');
        $objButton->button('go',$this->objLanguage->languageText('word_go'));
        $objButton->setCSS('sb_searchbutton');
        $objButton->sexyButtons=FALSE;
        $objButton->setToSubmit();
        $objForm->addToForm($objButton->show());
        return $objForm->show();
    }
}
?>