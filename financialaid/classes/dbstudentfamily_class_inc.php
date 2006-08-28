<?php
/* ----------- data class extends dbTable for tbl_comment------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
*
* Model class for the table tbl_studentfamily as used
* in the financial aid module
*
* @author Serge Meunier
* @package financialaid
*
*/
class dbstudentfamily extends dbTable
{
    /**
    *
    * @var object $objUser String to hold instance of the user object
    *
    */
    var $objUser;

    /**
    * Constructor method to define the table
    */
    function init() {
        parent::init('tbl_financialaid_studentfamily');
        $this->objUser = & $this->getObject("user", "security");
    }

    /**
    *
    * Save method for editing a record in this table
    *
    * @param string $mode: edit if coming from edit, add if coming from add
    *
    */
    function saveRecord($mode, $userId)
    {
        $appnum = $this->getParam('appnum', NULL);
        $stdnum = $this->getParam('stdnum', NULL);
        $institution = $this->getParam('institution', NULL);
        $course = $this->getParam('course', NULL);
        $firstname = $this->getParam('firstname', NULL);
        $year = $this->getParam('year', NULL);

        $id = $this->getParam('id', NULL);

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, array(
            'appNumber' => $appnum,
            'firstname' => $firstname,
            'institution' => $institution,
            'course' => $course,
            'yearOfStudy' => $year,
            'studentNumber' => $stdnum,
            'dateModified' => date("Y-m-d H:i:s"),
            'modifierId' => $userId));

        }#if

        // if add use insert
        if ($mode=="add" && $appnum!=NULL) {
            $this->insert(array(
            'appNumber' => $appnum,
            'firstname' => $firstname,
            'institution' => $institution,
            'course' => $course,
            'yearOfStudy' => $year,
            'studentNumber' => $stdnum,
            'dateCreated' => date("Y-m-d H:i:s"),
            'creatorId' => $userId));
        }
    }


    function getStudentFamily($appnum)
    {
        $where = " WHERE appNumber ='" . $appnum ."'";
        return $this->getAll($where);
    }
} #end of class
?>
