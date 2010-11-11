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
class dbformbuilder_submit_results extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_formbuilder_submit_results');
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

    function getNumberofSubmissionElements($formNumber,$submitNumber)
    {
      $sql = "where formnumber like '".$formNumber."' and submitnumber like '".$submitNumber."'";
     return $numberofFormSubmissionElements = $this->getRecordCount($sql);

    }
function getAllFormResults($formNumber)
{
        return $this->getAll("WHERE formnumber='" . $formNumber . "'");
}


function getParticularSubmitResults($submitNumber)
{
        return $this->getAll("WHERE submitnumber='" . $submitNumber . "'");
}
function getOnlyDistinctFormResults($formNumber,$number_of_entries_per_pagination_request,$starting_element)
{
    $sql ="select submitnumber,useridofformsubmitter,max(timeofsubmission) as timeofsubmission
from tbl_formbuilder_submit_results where formnumber like '".$formNumber."'
group by submitnumber,useridofformsubmitter
order by submitnumber asc LIMIT $number_of_entries_per_pagination_request OFFSET $starting_element";
       return $this->getArray($sql);

}
    function searchOriginalMessages($searchValue,$number_of_entries_per_page = 5,$starting_element = 0) {
///Select all for the original messages table in which the search parameter is like the comments or subject matter or
///or author and order them by latest modified and paginate them and store the result in a temporary variable sql.
        $sql=   "select * from tbl_hosportal_original_messages where commenttxt like '%".$searchValue."%' || title like '%$searchValue%' || userid like '%$searchValue%' order by modified desc LIMIT $number_of_entries_per_page OFFSET $starting_element";
///Return the array of entries. Note that is function in part of the parent class dbTable.
        return $this->getArray($sql);

    }
function getLatestSubmitResult($formNumber,$number_of_entries_per_pagination_request,$starting_element)
{

        $sql ="select max(submitnumber) as submitnumber,useridofformsubmitter,max(timeofsubmission) as timeofsubmission
from tbl_formbuilder_submit_results where formnumber like '".$formNumber."'
group by useridofformsubmitter
order by submitnumber asc LIMIT $number_of_entries_per_pagination_request OFFSET $starting_element";

       return $this->getArray($sql);
}
    function getCurrentSubmitNumberofSubmitter($formNumber,$submitNumber)
    {
      $sql = "where formnumber like '".$formNumber."' and submitnumber like '".$submitNumber."'";
      $numberofFormSubmissionElements = $this->getRecordCount($sql);
if ($numberofFormSubmissionElements == NULL)
{
 return "1<sup>st</sup>";
}

       $sqlStatement = "select * from tbl_formbuilder_submit_results where formnumber like '".$formNumber."'";
        $submitNumbersArray = $this->getArray($sqlStatement);
$submitNumberofSubmitter =0;
        foreach($submitNumbersArray as $thisSubmitNumber){
   //Store the values of the array in variables

   $databaseSubmitNumber = $thisSubmitNumber["submitnumber"];
     $submitNumberofSubmitter++;
   if ($submitNumber == $databaseSubmitNumber)
   {
    //  return $submitNumberofSubmitter."<sup>th</sup>";
       $realSubmitNumber = ceil($submitNumberofSubmitter/$numberofFormSubmissionElements);
       switch ($realSubmitNumber)
        {
         case '1':
             return "1<sup>st</sup>";
             break;
                  case '2':
             return "2<sup>nd</sup>";
             break;
                  case '3':
             return "3<sup>rd</sup>";
             break;
         default:
             return $realSubmitNumber."<sup>th</sup>";
       }
 
        }
    
    }
   return   "[Error. No Submit Number Match Found.]";
    }
    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */

function getNextSubmitNumber($formNumber)
{
  //  $sqlStatement =  "select * from tbl_formbuilder_submit_results where formnumber like '".$formNumber."' and MAX(submitnumber) AS submitnumber";
  //  $sqlStatement = "SELECT * from tbl_formbuilder_submit_results where formnumber like '".$formNumber."' where submitnumber=(SELECT MAX(submitnumber) FROM tbl_formbuilder_submit_results";
    $sqlStatement = "SELECT MAX(submitnumber) AS submitnumber FROM tbl_formbuilder_submit_results";
 $submitNumberOrder=$this->getArray($sqlStatement);
  $maxSubmitNumber=$submitNumberOrder[0]["submitnumber"];
  if ($maxSubmitNumber==NULL)
  {
  return $maxSubmitNumber = 1;
  }
  else
  {
   $maxSubmitNumber++;
  }
    return $maxSubmitNumber;
}

function getSubmitUsersFullName($userID)
{
    return $this->objUser->fullname($userID);
}

function getSubmitUsersEmail($userID)
{
    return $this->objUser->email($userID);
}

       function insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue)
               {
//$sqlStatement = "SELECT MAX(submitnumber) AS submitnumber FROM tbl_formbuilder_submit_results";
// $submitNumberOrder=$this->getArray($sqlStatement);
//  $maxSubmitNumber=$submitNumberOrder[0]["submitnumber"];
//  if ($maxSubmitNumber==NULL)
//  {
//      $maxSubmitNumber = 1;
//  }
//  else
//  {
//   $maxSubmitNumber++;
//  }


        $userid = $this->objUser->userId();
        $id = $this->insert(array(
            'formnumber'=>$formNumber,
            'submitnumber' => $submitNumber,
            'formelementtype' => $formElementType,
            'formelementname' => $formElementName,
              'formelementvalue' => $formElementValue,
            'useridofformsubmitter' => $userid,
            'timeofsubmission'=> $this->now()
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

function deleteAllSubmissions($formNumber)
{
   $sql="where formnumber like '".$formNumber."'";
    $numberOfSubmissions = $this->getRecordCount($sql);
if ($numberOfSubmissions > 0)
{
//$formElementDBEntry = $this->getAll("where formnumber like '".$formNumber."' and formelementname like '".$formElementName."'");
////$formElementDBEntry = $this->getArray($sql);
//   $formElementDBEntryType = $formElementDBEntry[0]["formelementtpye"];
  $this->delete("formnumber", $formNumber);
    return true;
      

}
else
{
 return false;
}
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
