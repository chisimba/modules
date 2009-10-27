<?php
/* ----------- data class extends dbTable for tbl_email------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_liftclub_details
 * @author Paul Mungai
 * @copyright 2009 University of the Western Cape
 */
class dbLiftclub_details extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_liftclub_details');
        $this->objUser = &$this->getObject('user', 'security');
    }
    /**
     * Return all records
     * @param string $id The Origin ID
     * @return array The entries
     */
    function listAll($id) 
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    /**
     * Return a single record
     * @param string $userid user ID
     * @return array The values
     */
    function userDetails($userId) 
    {
        return $this->getAll("WHERE userid='" . $userId . "'");
    }
    function getByItem($Id) 
    {
        $sql = "SELECT * FROM tbl_liftclub_details WHERE id = '" . $Id . "'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    /**
     * Insert a record
     * @param string $userid The user Id
     * @param string $times travel times
     * @param string $additionalinfo additional information
     * @param string $specialoffer special offer
     * @param string $emailnotifications email notifications
     * @param string $daysvary days vary
     * @param string $smoke smoke
     * @param string $monday monday
     * @param string $tuesday tuesday
     * @param string $wednesday wednesday
     * @param string $thursday thursday
     * @param string $friday friday
     * @param string $saturday saturday
     * @param string $sunday sunday
     */
    function insertSingle($userid, $times, $additionalinfo, $specialoffer, $emailnotifications, $daysvary, $smoke, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday)
    {
        $id = $this->insert(array(
            'times' => $times,
            'userid' => $userid,
            'additionalinfo' => $additionalinfo,
            'specialoffer' => $specialoffer,
            'emailnotifications' => $emailnotifications,
            'daysvary' => $daysvary,
            'smoke' => $smoke,
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday
         ));
        return $id;
    }
    /**
     * Update a record
     * @param string $times travel times
     * @param string $additionalinfo additional information
     * @param string $specialoffer special offer
     * @param string $emailnotifications email notifications
     * @param string $daysvary days vary
     * @param string $smoke smoke
     * @param string $monday monday
     * @param string $tuesday tuesday
     * @param string $wednesday wednesday
     * @param string $thursday thursday
     * @param string $friday friday
     * @param string $saturday saturday
     * @param string $sunday sunday
     */
    function updateSingle($id, $times, $additionalinfo, $specialoffer, $emailnotifications, $daysvary, $smoke, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday)
    {
        $this->update("id", $id, array(
            'times' => $times,
            'additionalinfo' => $additionalinfo,
            'specialoffer' => $specialoffer,
            'emailnotifications' => $emailnotifications,
            'daysvary' => $daysvary,
            'smoke' => $smoke,
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday
        ));
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) 
    {
        $this->delete("id", $id);
    }
}
?>
