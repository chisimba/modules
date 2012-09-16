<?php
/**
 *
 * Database access for Simple blog
 *
 * Database access for Simple blog. This is a sample database model class
 * that you will need to edit in order for it to work.
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
 * @author    Derek Keats <derek.keats@wits.ac.za>
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
* Database access for Simple blog
*
* Database access for Simple blog. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   simpleblog
* @author     Derek Keats <derek.keats@wits.ac.za>
*
*/
class dbsimpleblog extends dbtable
{

    /**
    *
    * Intialiser for the simpleblog database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Instantiate the user object
        $this->objUser = $this->getObject('user', 'security');
        //Set the parent table to our demo table
        parent::init('tbl_simpleblog_posts');
    }

    /**
     *
     * Get more posts according to page and page size
     *
     * @param integer $blogId The blog id
     * @param integer $page The starting page
     * @param string $pageSize Number of records per page
     * @return string array An array of posts if any
     * @access public
     *
     */
    public function getPosts($blogId, $page, $pageSize=FALSE)
    {
        if (!$pageSize) {
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $pageSize = $objSysConfig->getValue('simpleblog_numpostdisplay', 'simpleblog');
        }
        // Subtract 1 from page since the first page is 0
        $page=$page-1;
        // The base SQL, uses joins to avoid going back and forth to the db
        $baseSql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE  tbl_simpleblog_posts.userid = tbl_users.userid
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' "
            ORDER BY datecreated DESC ';
        $startPoint = $page * $pageSize;
        $posts = $this->getArrayWithLimit($baseSql, $startPoint, $pageSize);
        return $posts;
    }
    
    /**
     * 
     * Get posts for a particular tag and blogid
     * 
     * @param string $blogId The plog to get the tags for
     * @param string $tag The tag to retrieve posts against
     * @return string array An array of blog posts for the given tag
     * @access public
     * 
     */
    public function getPostsByTag($blogId, $tag) 
    {
        $blogId=trim($blogId);
        $tag = trim($tag);
        $sql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE tbl_simpleblog_posts.userid = tbl_users.userid   
            AND tbl_simpleblog_posts.blogId = \'' . $blogId . '\' 
            AND tbl_simpleblog_posts.post_tags LIKE \'%' . $tag . '%\'  
            ORDER BY datecreated DESC ';
        return $this->getArray($sql);
    }
    
    /**
     * 
     * Get a post by its id
     * 
     * @param string $id The id of the post to retrieve
     * @return string array An array containing the single blog post
     * @access public
     * 
     */
    public function getPostsById($id) 
    {
        $sql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE tbl_simpleblog_posts.userid = tbl_users.userid   
            AND tbl_simpleblog_posts.id = \'' . $id . '\' ';
        return $this->getArray($sql);
    }
    
    /**
     * 
     * Get an associative array of tags and their count
     * 
     * @param string $blogId The blog id to get tag counts for
     * @return string array An associative array of tags and their frequency
     * @access public
     * 
     */
    public function getTagCloudArray($blogId) 
    {
        $sql = 'SELECT post_tags FROM tbl_simpleblog_posts
            WHERE blogId = \'' . $blogId . '\' AND post_tags IS NOT NULL';
        $ar = $this->getArray($sql);
        $res = array();
        foreach ($ar as $postTag) {
            $tagsAr = explode(',', $postTag['post_tags']);
            foreach ($tagsAr as $tag) {
                $res[] = trim($tag);
            }
        }
        //var_dump(array_count_values ($res)); die();
        return array_count_values ($res);
    }
    
    /**
     *
     * Get posts by current year and current month (used for 'archive' records)
     *
     * @param integer $blogId The blog id
     * @return string array An array of posts if any
     * @access public
     *
     */
    public function getCurrentMonth($blogId)
    {
        // The base SQL, uses joins to avoid going back and forth to the db
        $baseSql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE  tbl_simpleblog_posts.userid = tbl_users.userid
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' "
            AND YEAR( tbl_simpleblog_posts.datecreated ) = YEAR( CURRENT_DATE ) 
            AND  MONTH( tbl_simpleblog_posts.datecreated ) = MONTH( CURRENT_DATE )
            ORDER BY datecreated DESC ';
        $posts = $this->getArray($baseSql);
        return $posts;
    }
    
    /**
     *
     * Get posts by current year and current month (used for 'archive' records)
     *
     * @param integer $blogId The blog id
     * @param integer $page The starting page
     * @param string $year The year to return
     * @param string $keyValue The value of the key (usually contextcode)
     * @param integer $num The number of records to return (pagesize)
     * @return string array An array of posts if any
     * @access public
     *
     */
    public function getLastMonth($blogId)
    {
        // The base SQL, uses joins to avoid going back and forth to the db
        $baseSql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE  tbl_simpleblog_posts.userid = tbl_users.userid
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' "
            AND YEAR( tbl_simpleblog_posts.datecreated ) = YEAR( CURRENT_DATE  - INTERVAL 1 MONTH) 
            AND  MONTH( tbl_simpleblog_posts.datecreated ) = MONTH( CURRENT_DATE  - INTERVAL 1 MONTH)
            ORDER BY datecreated DESC ';
        $posts = $this->getArray($baseSql);
        return $posts;
    }
    
    /**
     * 
     * Get a list of years and months for archive posts
     * 
     * @param string $blogId The id of the blog to render
     * @return string array An array of years and months
     * @access public 
     *
     */
    public function getArchivePosts($blogId)
    {
        $sql = 'SELECT 
          DISTINCT CONCAT( YEAR( datecreated ) , \' \', MONTHNAME( datecreated ) ) 
          AS \'YearMonth\', YEAR( datecreated ) as \'year\', 
          MONTHNAME( datecreated ) as \'month\', blogid 
          FROM `tbl_simpleblog_posts`
          WHERE blogId = "' . $blogId . ' "
          ORDER BY datecreated DESC';
        return $this->getArray($sql);
    }
    
    /**
     * 
     * Get archive posts by blogid, year and month
     * 
     * @param string $blogId The id of the blog to render
     * @param string $year The years of the posts to render
     * @param string $month The month within the given year
     * @return string array An array of the posts for the given blog, year and month
     */
    public function getPostsByYearMonth($blogId, $year, $month) 
    {
        $sql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE  tbl_simpleblog_posts.userid = tbl_users.userid
            AND YEAR( tbl_simpleblog_posts.datecreated )  = \'' . $year . '\' 
            AND MONTHNAME( tbl_simpleblog_posts.datecreated ) = \'' . $month . '\' 
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' "
            ORDER BY datecreated DESC';
        return $this->getArray($sql);
    }
    
    /**
     * 
     * Get posts from a search. Note that the table must be MYISAM.
     * 
     * @param string $blogId The blog to look in
     * @param string $searchTerm The phrase to search for
     * @return string Array Array of results
     * @access public
     * 
     */
    public function getPostsFromSearch($blogId, $searchTerm)
    {
        $sql = 'SELECT tbl_simpleblog_posts.*,
            tbl_users.userid,
            tbl_users.firstname,
            tbl_users.surname
            FROM tbl_simpleblog_posts, tbl_users 
            WHERE MATCH(post_title, post_content) AGAINST (\'' . $searchTerm . '\' IN BOOLEAN MODE)
            AND tbl_simpleblog_posts.userid = tbl_users.userid
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' " 
            ORDER BY datecreated DESC';
        return $this->getArray($sql);
    }
    
    /**
     *
     * Get posts by a particular user and blog, according to 
     * page and page size
     *
     * @param integer $blogId The blog id
     * @param integer $userId The blog id
     * @param integer $page The starting page
     * @param string $pageSize Number of records per page
     * @return string array An array of posts if any
     * @access public
     *
     */
    public function getPostsByUser($blogId, $userId, $page=1, $pageSize=FALSE)
    {
        if (!$pageSize) {
            $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $pageSize = $objSysConfig->getValue('simpleblog_numpostdisplay', 'simpleblog');
        }
        // Subtract 1 from page since the first page is 0
        $page=$page-1;
        // The base SQL, uses joins to avoid going back and forth to the db
        $baseSql = 'SELECT tbl_simpleblog_posts.*,
              tbl_users.userid,
              tbl_users.firstname,
              tbl_users.surname,
              tbl_users.username,
              (SELECT COUNT(tbl_wall_posts.id)
                   FROM tbl_wall_posts
                   WHERE tbl_wall_posts.identifier = tbl_simpleblog_posts.id
              ) AS comments
            FROM tbl_simpleblog_posts, tbl_users
            WHERE  tbl_simpleblog_posts.userid = tbl_users.userid
            AND tbl_simpleblog_posts.blogId = "' . $blogId . ' "
            AND tbl_simpleblog_posts.userid = "' . $userId . ' "
            ORDER BY datecreated DESC ';
        $startPoint = $page * $pageSize;
        $posts = $this->getArrayWithLimit($baseSql, $startPoint, $pageSize);
        return $posts;
    }

    /**
     *
     * Delete a blog post
     *
     * @param string $id The id key of the record to delete
     * @return string An indication of the reuslts ('true' or 'norights')
     *
     */
    public function deletePost($id)
    {
        if ($this->delete('id', $id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Method to retrieve the data for edit and prepare the vars for
    * the edit template.
    *
    * @param string $mode The mode should be edit or add
    */
    public function getForEdit($id)
    {
        // Get the data for edit
        $key="id";
        return $this->getRow('id', $id);
    }


    /**
     * 
     * Save the submitted post
     * 
     * @return string $id The primary key of the inserted, or updated record.
     * @access public
     * 
     */
    public function savePost()
    {
        $mode = $this->getParam('mode', 'add');
        //The current user
        $userId = $this->objUser->userId();
        $title = trim($this->getParam('post_title', NULL));
        $content = trim($this->getParam('post_content', NULL));
        $status = $this->getParam('post_status', NULL);
        $blogId = $this->getParam('blogid', NULL);
        $blogType = $this->getParam('post_type', NULL);
        $postTags = $this->getParam('post_tags', NULL);
        if ($blogType == 'site') {
            $blogId = 'site';
        }
        if ($blogType == 'context') {
            $objContext = $this->getObject('dbcontext', 'context');
            if($objContext->isInContext()){
                $blogId = $objContext->getcontextcode();
            }
        }
        $id = TRIM($this->getParam('id', NULL));
        // if edit use update
        if ($mode=="edit") {
            $rsArray=array(
                'post_title'=>$title,
                'post_content'=>$content,
                'post_status'=>$status,
                'post_type' => $blogType,
                'modifierid'=>$userId,
                'blogid'=>$blogId,
                'datemodified'=>$this->now(),
                'post_tags' => $postTags
            );
            $this->update("id", $id, $rsArray);
            return $id;
        } elseif ($mode=="add" || $mode="translate") {
            return $this->insert(array(
                'post_title'=>$title,
                'post_content'=>$content,
                'post_status'=>$status,
                'post_type' => $blogType,
                'userid'=>$userId,
                'blogid'=>$blogId,
                'datecreated'=>$this->now(),
                'post_tags' => $postTags
            ));
        }
        // There must have been an error, neither edit nor add nor translate.
        return FALSE;
    }
    
}
?>