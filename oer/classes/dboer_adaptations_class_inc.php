<?php
/* ----------- data class extends dbTable for tbl_oer_adaptations------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_oer_adaptations
 * @author Paul Mungai
 * @copyright 2012 Kengasolutions
 */
class dboer_adaptations extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init()
    {
        parent::init('tbl_oer_adaptations');
        $this->objUser = &$this->getObject('user', 'security');
    }
    /**
     * Return all records
     * @param string $userid The User ID
     * @return array The entries
     */
    function listAll($userid)
    {
        return $this->getAll("WHERE userid='" . $userid . "'");
    }
    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listSingle($id)
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }

    function getByItem($userId)
    {
        $sql = "SELECT * FROM tbl_oer_adaptations WHERE userid = '" . $userId . "'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    /**
     * Insert a record
     * @param string $parentproduct_id The Root product ID
     * @param string $userid The userId of the current user
     * @param string $section_title The section title
     * @param string $current_path The current path
     * @param string $section_content The section content
     * @param string $status The status of publication
     * @param string $attachment The attachment path
     * @param string $longdescription The long description
     * @return string $id Id of new record
     */
    function insertSingle($parentproduct_id, $userid, $section_title, $current_path, $section_content, $status, $attachment, $longdescription)
    {
        $userid = $this->objUser->userId();
        $id = $this->insert(array(
            'parent_productid' => $parentproduct_id,
            'userid' => $userid,
            'section_title' => $section_title,
            'current_path' => $current_path,
            'section_content' => $section_content,
            'status' => $status,
            'attachment' => $attachment,
            'keywords' => $keywords,
            'contributed_by' => $contributed_by,
            'adaptation_notes' => $adaptation_notes
        ));
        return $id;
    }
    /**
     * save adaptation into db
     */
    function saveAdaptation($data) {
        return $this->insert($data);
    }
    /**
     * Updates Adaptation
     * @param  $data fields containing updated data
     * @param  $id ID of adaptation to be updated
     * @return type
     */
    function updateAdaptation($data, $id) {
        return $this->update("id", $id, $data);
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $parentproduct_id The Root product ID
     * @param string $userid The userId of the current user
     * @param string $section_title The section title
     * @param string $current_path The current path
     * @param string $section_content The section content
     * @param string $status The status of publication
     * @param string $attachment The attachment path
     * @param string $longdescription The long description
     * @return string $id Id of updated record
     */
    function updateSingle($id, $parentproduct_id, $userid, $section_title, $current_path, $section_content, $status, $attachment, $longdescription)
    {
        $userid = $this->objUser->userId();
        $this->update("id", $id, array(
            'parent_productid' => $parentproduct_id,
            'userid' => $userid,
            'section_title' => $section_title,
            'current_path' => $current_path,
            'section_content' => $section_content,
            'status' => $status,
            'attachment' => $attachment,
            'keywords' => $keywords,
            'contributed_by' => $contributed_by,
            'adaptation_notes' => $adaptation_notes
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