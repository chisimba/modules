<?php
/**
 *
 * A simple wall module
 *
 * A simple wall module that makes use of OEMBED and that tries to look a bit like Facebook's wall
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
* Database accesss class for Chisimba for the module wall
*
* @author Derek Keats
* @package wall
*
*/
class dbcomment extends dbtable
{
    /**
    *
    * @var string object The user object
    * @access public
    * 
    */
    public $objUser;

    /**
    *
    * Intialiser for the wall database connector
    * @access public
    *
    */
    public function init()
    {
        //Set the parent table here
        parent::init('tbl_wall_comments');
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     *
     * Default method to get the comment data as an array
     *
     * @param string $wallType The wall type (0=site wall, 1=personal or user wall, 2=context wall)
     * @param integer $num The number of results to return, defaulting to 10
     * @return string array An array of posts
     *
     */
    public function getComments($id, $num=10)
    {
        $sql = 'SELECT tbl_wall_comments.*,
          tbl_users.userid,
          tbl_users.firstname,
          tbl_users.surname,
          tbl_users.username
        FROM tbl_wall_comments, tbl_users
        WHERE tbl_wall_comments.posterId = tbl_users.userid
        AND tbl_wall_comments.parentid = \'' .$id . '\'
        ORDER BY datecreated DESC
        LIMIT ' . $num;
        return $this->getArray($sql);

    }

















    

    /**
    *
    * Save a post and return something to send back to the ajax request.
    *
    * Note that walltypes can be 0 for site wall, 1 for personal wall, and
    * 2 for context wall.
    *
    * @return string The results of the save (true, empty, false)
    *
    */
    public function savePost()
    {
        if ($this->objUser->isLoggedIn()) {
            $wallPost = $this->getParam('wallpost', 'empty');
            $posterId = $this->objUser->userId();
            $ownerId = $this->getParam('ownerid', NULL);
            $objGuessWall = $this->getObject('wallguesser','wall');
            $wallType = $objGuessWall->guessWall();
            if ($wallType == '2') {
                $objContext = $this->getObject('dbcontext', 'context');
                if($objContext->isInContext()){
                    $identifier = $objContext->getcontextcode();
                } else {
                    $identifier = NULL;
                }
            } elseif ($wallType == '0') {
                $identifier="sitewall";
            } else {
                $identifier=NULL;
            }
            if ($wallPost !=='empty') {
                try
                {
                    $this->insert(array(
                        'wallpost' => $wallPost,
                        'posterid' => $posterId,
                        'ownerid' => $ownerId,
                        'identifier' => $identifier,
                        'walltype' => $wallType,
                        'datecreated' => $this->now()));
                    return 'true';
                } catch (customException $e)
                {
                    echo customException::cleanUp($e);
                    die();
                }
            } else {
                return 'empty';
            }
        } else {
            return 'spoofattemptfailure';
        }
    }

    /**
     *
     * Delete a wall post
     *
     * @param string $id The id key of the record to delete
     * @return string An indication of the reuslts ('true' or 'norights')
     *
     */
    public function deletePost($id)
    {
        $chSql = "SELECT id, posterid, ownerid FROM tbl_wall_posts WHERE id='$id'";
        $ar = $this->getArray($chSql);
        $me = $this->objUser->userId();
        $posterid = $ar[0]['posterid'];
        $ownerid =  $ar[0]['ownerid'];
        if ($me == $posterid || $me = $ownerid) {
            // I can delete
            $this->delete('id', $id);
            return "true";
        } else {
            return 'norights';
        }

    }
}
?>