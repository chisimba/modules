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
     * Get more older posts for appending to the bottom of existing posts
     * by Ajax
     *
     * @param integer $wallType The wall type (1,2,3)
     * @param integer $page The starting page
     * @param string $keyName The name of the key (contextcode usually)
     * @param string $keyValue The value of the key (usually contextcode)
     * @param integer $num The number of records to return (pagesize)
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
                'datemodified'=>$this->now()
            );
            $this->update("id", $id, $rsArray);
        } elseif ($mode=="add" || $mode="translate") {
            $this->insert(array(
                'post_title'=>$title,
                'post_content'=>$content,
                'post_status'=>$status,
                'post_type' => $blogType,
                'userid'=>$userId,
                'blogid'=>$blogId,
                'datecreated'=>$this->now()
            ));
        }

    }
    
}
?>