<?php
/**
 * Class for storing and presenting the tags relating to slide show presentations
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
 * @package   webpresent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */


// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
 * Class for building the catalogue navigation for module catalogue.
 * The class makes use of nodes in an array to keep track of the different categories
 * and the shoe() function renders the array as a navigation menu.
 *
 * The class builds a css style navigation menu
 *
 * @category  Chisimba
 * @package   webpresent
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class dbwebpresenttags extends dbtable
{

    /**
    * Method to construct the class.
    */
    public function init()
    {
        parent::init('tbl_webpresent_tags');
    }

    /**
     * Method to get all the tags of a particular file
     * @param string $fileId Record Id of the File
     * @return array List of Tags associated with a file
     */
    public function getTags($fileId)
    {
        return $this->getAll(' WHERE fileid=\''.$fileId.'\'');
    }

    public function getTagsAsArray($fileId)
    {
        $tags = $this->getTags($fileId);

        if (count($tags) == 0)
        {
            return array();
        } else {
            $tagsArray = array();

            foreach ($tags as $tag)
            {
                $tagsArray[] = $tag['tag'];
            }

            return $tagsArray;
        }

    }

    /**
     * Method to get the list of all the tags, and the weight of each tag
     * @return array List of Tags and Corresponding Weight in alphabetical order
     */
    public function getAllTags()
    {
        $sql = 'SELECT tag, count( tag ) AS tagcount FROM tbl_webpresent_tags GROUP BY tag ORDER BY tag';
        return $this->getArray($sql);
    }

    public function getLastLimitTags($limit=50)
    {
        $sql = 'SELECT tbl_webpresent_tags.* , (SELECT count(tag) FROM tbl_webpresent_tags AS tags2 WHERE tbl_webpresent_tags.tag = tags2.tag) AS tagcount
FROM tbl_webpresent_tags GROUP BY tbl_webpresent_tags.tag ORDER BY tbl_webpresent_tags.puid DESC ';

        if (isset($limit) && $limit > 0) {
            $sql .= ' LIMIT '.$limit;
        }

        return $this->prepArrayForTagCloud($this->getArray($sql));
    }

    public function prepArrayForTagCloud($array)
    {
        $finalArray = array();

        if (count($array) > 0) {
            foreach ($array as $item)
            {
                $finalArray[$item['tag']] = $item;
            }
        }

        return $finalArray;
    }

    /**
     * Method to take all the existing tags, and build them into a Tag Cloud
     * @return string Tag Cloud
     */
    public function getTagCloud()
    {
        // Get All Tags
        $tags = $this->getLastLimitTags();

        // Check that there are tags
        if (count($tags) == 0) {
            return '<div class="noRecordsMessage">Tag Cloud Goes Here</div>';
        } else {
            // Load Object
            $objTagCloud = $this->newObject('tagcloud', 'utilities');

            // Loop through tags
            foreach ($tags as $tag)
            {
                // Link to File
                $uri = $this->uri(array('action'=>'tag', 'tag'=>$tag['tag']));

                // Add Tag
                $objTagCloud->addElement($tag['tag'], $uri, $tag['tagcount']*6, strtotime('-1 day'));
            }

            // Return Tag Cloud
            return $objTagCloud->biuldAll();
        }
    }

    /**
     * Method to take all the existing tags, and build them into a Tag Cloud
     * @return string Tag Cloud
     */
    public function getCompleteTagCloud()
    {
        // Get All Tags
        $tags = $this->getAllTags();

        // Check that there are tags
        if (count($tags) == 0) {
            return '<div class="noRecordsMessage">Tag Cloud Goes Here</div>';
        } else {
            // Load Object
            $objTagCloud = $this->newObject('tagcloud', 'utilities');

            // Loop through tags
            foreach ($tags as $tag)
            {
                // Link to File
                $uri = $this->uri(array('action'=>'tag', 'tag'=>$tag['tag']));

                // Add Tag
                $objTagCloud->addElement($tag['tag'], $uri, $tag['tagcount']*6, strtotime('-1 day'));
            }

            // Return Tag Cloud
            return $objTagCloud->biuldAll();
        }
    }

    /**
     * Method to add tags to a file
     * @param string $fileId Record Id of the File
     * @param array $tags List of Tags to be added
     *
     */
    public function addTags($fileId, $tags)
    {
        // Check that there are tags
        if (is_array($tags) && count($tags) > 0) {

            // Delete Existing Tags
            $this->deleteTags($fileId);

            // Loop through tags
            foreach ($tags as $tag)
            {
                // Check that tag has a value
                if (trim($tag != '')) {
                    $this->addTag($fileId, trim($tag));
                }
            }

        }
    }

    /**
     * Method to add a tag
     * @param string $fileid Record Id of the File
     * @param string $tag Tag
     * @return string Record Id of tag insert
     */
    private function addTag($fileid, $tag)
    {
        return $this->insert(array(
                'fileid'=> $fileid,
                'tag'=> stripslashes($tag),
            ));
    }

    /**
     * Method to get all files with a particular tag
     * @param string $tag Tag
     * @return array List of Files with tag associated
     */
    public function getFilesWithTag($tag, $order='dateuploaded DESC')
    {
        if ($order == 'creatorname asc')
        {
            $order = 'firstname, surname, creatorid';
        } else if ($order == 'creatorname desc')
        {
            $order = 'firstname DESC, surname DESC , creatorid DESC';
        }


        $sql = 'SELECT DISTINCT tbl_webpresent_files.id, tbl_webpresent_files.*, tbl_users.firstName as firstname, tbl_users.surname FROM tbl_webpresent_files, tbl_webpresent_tags, tbl_users
        WHERE (tbl_webpresent_tags.fileid = tbl_webpresent_files.id AND tbl_webpresent_files.creatorid = tbl_users.userid) AND tbl_webpresent_tags.tag LIKE \''.$tag.'\' ORDER BY '.$order;

        //echo $sql;

        return $this->getArray($sql);
    }

    /**
     * Method to delete all tags of a file
     * @param string $id Record Id of the file
     * @return boolean Result of Deletes
     */
    public function deleteTags($id)
    {
        return $this->delete('fileid', $id);
    }


}
?>