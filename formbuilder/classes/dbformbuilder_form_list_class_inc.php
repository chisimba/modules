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
    function listSingle($formNumber)
    {
        return $this->getAll("WHERE formnumber='" . $formNumber . "'");
    }
    function getNumberOfForms() {


        return $this->getRecordCount();
    }

    function getFormAuthorsFullName($userID)
{
    return $this->objUser->fullname($userID);
}

    function getFormName($formNumber)
    {
    $sql=   "select * from tbl_formbuilder_form_list where formnumber='" . $formNumber . "'";
    $formParameters =$this->getArray($sql);
    return $formParameters['0']['name'];
    }
function getNumberofSearchedEntries($searchValue)
{
         $sql=   "where name like '%".$searchValue."%' || label like '%$searchValue%' || details like '%$searchValue%' || author like '%$searchValue%'
                    || submissionemailaddress like '%$searchValue%' || created like '%$searchValue%'";
        return $this->getRecordCount($sql);
}

        function searchFormList($searchValue,$number_of_entries_per_page = 5,$starting_element = 0) {

        $sql=   "select * from tbl_formbuilder_form_list where name like '%".$searchValue."%' || label like '%$searchValue%' || details like '%$searchValue%' || author like '%$searchValue%'
                    || submissionemailaddress like '%$searchValue%' || created like '%$searchValue%' LIMIT $number_of_entries_per_page OFFSET $starting_element
";
      //  $sql = "select tbl_formbuilder_form_list.formnumber from tbl_formbuilder_form_list, tbl_formbuilder_form_elements where tbl_formbuilder_form_elements.formelementname like '%$searchValue%'";

        return $this->getArray($sql);

    }

    function getPaginatedEntries($number_of_entries_per_page = 5,$starting_element = 0) {
///Select all or * entries from the table hosportal original messages and order them by the field modified
///and store the result in a temporary variable. The language MYSQL is used to do this. It is important to
///note that no changes are being made in the database.
        $sql = "select*from tbl_formbuilder_form_list LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Convert the temporary variable into an array and return it.
        return $this->getArray($sql);

    }
    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */
     function getFormMetaData($formNumber) {
///Select all for the replies table with one subject matter according to the subject matter parameter AND the search parameter
//is like the comments or subject matter or
///or author and order them by latest modified and paginate them and store the result in a temporary variable sql.
        $sql=   "select * from tbl_formbuilder_form_list where formnumber like '".$formNumber."'";

///Return the array of entries. Note that is function in part of the parent class dbTable.
        return $this->getArray($sql);

    }
    function getSubmitTime()
    {
return $this->now();
    }
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
    
    function insertSingle($formName,$formLabel,$formDetails, $submissionEmailAddress,$submissionOption)
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


                $userid = $this->objUser->userId();
        $id = $this->insert(array(
            'formnumber'=>$maxFormNumber,
            'name' => $formName,
            'label' => $formLabel,
            'details' => $formDetails,
              'author' => $userid,
                  'submissionemailaddress'=> $submissionEmailAddress,
                   'submissionoption' => $submissionOption,
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
    function updateSingle($formNumber, $formLabel,$formDetails, $submissionEmailAddress,$submissionOption)
    {
        $userid = $this->objUser->userId();
        $this->update("formnumber", $formNumber, array(
            'label' => $formLabel,
            'details' => $formDetails,
              'author' => $userid,
                  'submissionemailaddress'=> $submissionEmailAddress,
                   'submissionoption' => $submissionOption
        ));
        return $formNumber;
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($formNumber)
    {
        $this->delete("formnumber", $formNumber);
    }
}
?>
