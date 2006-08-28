<?php
/* ----------- data class extends dbTable for tbl_comment------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
*
* Model class for the table tbl_applications as used
* in the financial aid module
*
* @author Serge Meunier
* @package financialaid
*
*/
class dbapplication extends dbTable
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
        parent::init('tbl_financialaid_applications');
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
        $stdnum = $this->getParam('stdnum', NULL);
        $appnum = $this->getParam('appnum', NULL);
        $surname = $this->getParam('surname', NULL);
        $firstNames = $this->getParam('firstname', NULL);
        $saCitizen = $this->getParam('saCitizen', NULL);
        $gender = $this->getParam('gender', NULL);
        $idNumber = $this->getParam('idNum', NULL);
        $supportingSelf = $this->getParam('supportingself', NULL);

        $id = $this->getParam('id', NULL);

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, array(
            'appNumber' => $appnum,
            'studentNumber' => $stdnum,
            'idNumber' => $idNumber,
            'surname' => $surname,
            'firstNames' => $firstNames,
            'surname' => $surname,
            'gender' => $gender,
            'saCitizen' => $saCitizen,
            'supportingSelf' => $supportingSelf,
            'dateModified' => date("Y-m-d H:i:s"),
            'modifierId' => $userId));

        }#if

        // if add use insert
        if ($mode=="add" && $appnum!=NULL) {
            $this->insert(array(
            'appNumber' => $appnum,
            'studentNumber' => $stdnum,
            'idNumber' => $idNumber,
            'surname' => $surname,
            'firstNames' => $firstNames,
            'surname' => $surname,
            'gender' => $gender,
            'saCitizen' => $saCitizen,
            'supportingSelf' => $supportingSelf,
            'dateCreated' => date("Y-m-d H:i:s"),
            'creatorId' => $userId));
        }
    }


    function getApplication($appnum)
    {
        $where = " WHERE appNumber ='" . $appnum ."'";
        return $this->getAll($where);
    }
} #end of class
?>
