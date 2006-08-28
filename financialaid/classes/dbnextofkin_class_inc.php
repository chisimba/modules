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
class dbnextofkin extends dbTable
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
        parent::init('tbl_financialaid_nextofkin');
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
        $surname = $this->getParam('surname', NULL);
        $firstNames = $this->getParam('firstname', NULL);
        $strAddress = $this->getParam('straddress', NULL);
        $suburb = $this->getParam('suburb', NULL);
        $city = $this->getParam('city', NULL);
        $postcode = $this->getParam('postcode', NULL);
        $idNumber = $this->getParam('idnum', NULL);
        $spouse = $this->getParam('spouse', NULL);
        $occupation = $this->getParam('occupation', NULL);
        $employersDetails = $this->getParam('employerdetails', NULL);
        $employersTelNo = $this->getParam('employertelno', NULL);
        $relationship = $this->getParam('relationship',NULL);
        $maritalSts = $this->getParam('maritalstatus', NULL);
        
        $id = $this->getParam('id', NULL);

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, array(
            'appNumber' => $appnum,
            'idNumber' => $idNumber,
            'surname' => $surname,
            'firstNames' => $firstNames,
            'relationship' => $relationship,
            'strAddress' => $strAddress,
            'suburb' => $suburb,
            'city' => $city,
            'postcode' => $postcode,
            'maritalStatus' => $maritalSts,
            'spouse' => $spouse,
            'occupation' => $occupation,
            'employersDetails' => $employersDetails,
            'employersTelNo' => $employersTelNo,
            'dateModified' => date("Y-m-d H:i:s"),
            'modifierId' => $userId));

        }#if

        // if add use insert
        if ($mode=="add" && $appnum!=NULL) {
            $this->insert(array(
            'appNumber' => $appnum,
            'idNumber' => $idNumber,
            'surname' => $surname,
            'firstNames' => $firstNames,
            'relationship' => $relationship,
            'strAddress' => $strAddress,
            'suburb' => $suburb,
            'city' => $city,
            'postcode' => $postcode,
            'maritalStatus' => $maritalSts,
            'spouse' => $spouse,
            'occupation' => $occupation,
            'employersDetails' => $employersDetails,
            'employersTelNo' => $employersTelNo,
            'dateCreated' => date("Y-m-d H:i:s"),
            'creatorId' => $userId));
        }
    }


    function getNextofkin($appnum)
    {
        $where = " WHERE appNumber ='" . $appNum ."'";
        return $this->getAll($where);
    }
} #end of class
?>
