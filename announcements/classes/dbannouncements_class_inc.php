<?php


/**
 * Short description for file
 * 
 * Long description (if any) ...
 * 
 * PHP version unknow
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
 

/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts

/**
 * Description for $GLOBALS
 * @global integer $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}



class dbAnnouncements extends dbTable
{
    /**
     * Constructor method to define the table
     */
    public function init() 
    {
        parent::init('tbl_announcements');
    }
    /**
     * Return all records in the tbl_announcements.
     * 
     * @param $userId is the id taken from the tbl_user
     */
    public function listAll($contextPuid,$start) 
    {       
        
        $userrec = $this->getAll("WHERE courseid = '$contextPuid' ORDER BY createdon DESC LIMIT $start,5 ");
        return $userrec;
    }
    /**
     * Return a single record in the tbl_announcements.
     *
     * @param $id is the id taken from the tbl_announcements
     */
    public function listSingle($id) 
    {
        $onerec = $this->getRow('id', $id);
        return $onerec;
    }
    /**
     * Insert a record in the tbl_announcements.
     *
     * @param $userId         is the id taken from the tbl_user
     * @param $title      is the name taken from the form
     * @param $message       is the surname taken from the form
     * @param $createdBy   is the id taken from the tbl_user
     * @param $createdOn     take from date function now
     * @param $courseId		 is the id taken from the form from tbl_courses
     
     *                           
     *                           Also checks if text inputs are empty and returns the add a record template
     */
    public function insertRecord($title, $message, $createdon, $createdby,$courseid) 
    {
        $this->objUser = $this->getObject('user', 'security');
        $arrayOfRecords = array(
            'createdBy' => $this->objUser->userId() ,
            'title' => $title,
            'message' => $message,
            'createdOn' => $this->now(),
		'courseid' => $courseid,
            
        );
        if (empty($title) && empty($message)) {
            return "add_tpl.php";
        } else {
            return $this->insert($arrayOfRecords, 'tbl_announcements');
        }
    }
    /**
     * Deletes a record from the tbl_announcements
     *
     * @param $id is the generated id for a single record
     */
    public function deleteRec($id) 
    {
        return $this->delete('id', $id, 'tbl_announcements');
    }
    /**
     * Updates a record to the tbl_announcements
     *
     * @param $id             is the generated id for a single record
     * @param $arrayOfRecords is an array of all the information added in the form
     *                           
     */
    public function updateRec($id, $arrayOfRecords) 
    {
        return $this->update('id', $id, $arrayOfRecords, 'tbl_announcements');
    }
}
?>
