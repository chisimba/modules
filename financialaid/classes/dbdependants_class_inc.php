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
class dbdependants extends dbTable
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
        parent::init('tbl_financialaid_dependants');
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
        $firstname = $this->getParam('firstname', NULL);
        $relationship = $this->getParam('relationship', NULL);
        $dependantReason = $this->getParam('dependantreason', NULL);
        $hasIncome = $this->getParam('hasIncome', NULL);
        $incomeType = $this->getParam('incomeType', NULL);
        $incomeAmount = $this->getParam('incomeAmount', NULL);
        $category = $this->getParam('category', NULL);

        $id = $this->getParam('id', NULL);

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, array(
            'appNumber' => $appnum,
            'firstName' => $firstname,
            'relationship' => $relationship,
            'dependantReason' => $dependantReason,
            'category' => $category,
            'hasIncome' => $hasIncome,
            'incomeType' => $incomeType,
            'incomeAmount' => $incomeAmount,
            'dateModified' => date("Y-m-d H:i:s"),
            'modifierId' => $userId));

        }#if

        // if add use insert
        if ($mode=="add" && $appnum!=NULL) {
            $this->insert(array(
            'appNumber' => $appnum,
            'firstName' => $firstname,
            'relationship' => $relationship,
            'dependantReason' => $dependantReason,
            'category' => $category,
            'hasIncome' => $hasIncome,
            'incomeType' => $incomeType,
            'incomeAmount' => $incomeAmount,
            'dateCreated' => date("Y-m-d H:i:s"),
            'creatorId' => $userId));
        }
    }


    function getDependants($appnum)
    {
        $where = " WHERE appNumber ='" . $appnum ."'";
        return $this->getAll($where);
    }
} #end of class
?>
