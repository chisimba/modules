<?php
/* ----------- data class extends dbTable for tbl_helloforms_comments------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_helloforms_comments
 * @author Paul Mungai, Zwelithini, Philani, Thenjiwe
 * @copyright 2010 University of the Western Cape
 */
class dbformbuilder_form_list extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_formbuilder_form_list');
        $this->objUser = &$this->getObject('user', 'security');
        
    }
    /**
     * Return all records
     * @param string $userid The User ID
     * @return array The entries
     */
    function listAll() 
    {
        return $this->getAll();
    }
    /**
     * Return all records
     * @param string $comments The comments
     * @return array The entries
     */
    function listComment($comments) 
    {
        return $this->getAll("WHERE commenttxt LIKE '%" . $comments . "%'");
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
    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */

                function checkDuplicateFormEntry($formNumber,$formName) {

  // $sqlStatementForFormNumber=   "where formnumber like '".$formNumber."'";
 $sqlStatementForFormName=   "where name like '".$formName."'";
///Return the number of entries. Note that is function in part of the parent class dbTable.
      // $numberofDuplicatesFormNumber = $this->getRecordCount($sqlStatementForFormNumber);
       $numberofDuplicatesFormName = $this->getRecordCount($sqlStatementForFormName);
if ($numberofDuplicatesFormName < 1)
{
    return true;
}
 else
     {
    return FALSE;
}
    }

    function getCurrentFormNumber()
    {
    $sqlStatement = "SELECT MAX(formnumber) AS formnumber FROM tbl_formbuilder_form_list";
 $formNumberOrder = $this->getArray($sqlStatement);
  $maxFormNumber=$formNumberOrder[0]["formnumber"];
  if ($maxFormNumber==NULL)
  {
      return $maxFormNumber = 1;
  }
  else
  {
   return $maxFormNumber+=1;
  }
    }
    
    function insertSingle($formName,$formLabel,$formDetails)
    {
$sqlStatement = "SELECT MAX(formnumber) AS formnumber FROM tbl_formbuilder_form_list";
 $formNumberOrder=$this->getArray($sqlStatement);
  $maxFormNumber=$formNumberOrder[0]["formnumber"];
  if ($maxFormNumber==NULL)
  {
      $maxFormNumber = 1;
  }
  else
  {
   $maxFormNumber++;
  }


        $userid = $this->objUser->fullname();
        $id = $this->insert(array(
            'formnumber'=>$maxFormNumber,
            'name' => $formName,
            'label' => $formLabel,
            'details' => $formDetails,
              'author' => $userid,
            'created' => $this->now(),
        ));
        return $maxFormNumber;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $category Category
     * -- @param string $userId The user ID
     */
    function updateSingle($id, $title, $comments) 
    {
        $userid = $this->objUser->userId();
        $this->update("id", $id, array(
            'userid' => $userid,
            'title' => $title,
            'commenttxt' => $comments,
            'modified' => TRUE
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
