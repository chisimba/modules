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
class dbformbuilder_multiselect_dropdown_entity extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_formbuilder_multiselect_dropdown_entity');
       // $this->objUser = &$this->getObject('user', 'security');
        
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

     function listMultiSelectDropdownParameters($msddName) {
///Select all for the replies table with one subject matter according to the subject matter parameter AND the search parameter
//is like the comments or subject matter or
///or author and order them by latest modified and paginate them and store the result in a temporary variable sql.
        $sql=   "select * from tbl_formbuilder_multiselect_dropdown_entity where multiselectdropdownname like '".$msddName."'";

///Return the array of entries. Note that is function in part of the parent class dbTable.
        return $this->getArray($sql);

    }


            function checkDuplicateMultiSelectDropdownEntry($msddName,$msddValue) {

///Get entries where the comments or the subject matter or the author is like the search parameter and store
///these entries in a temporary variable.
         $sql=   "where multiselectdropdownname like '".$msddName."' and msddoptionvalue like '".$msddValue."'";

///Return the number of entries. Note that is function in part of the parent class dbTable.
       $numberofDuplicates = $this->getRecordCount($sql);
if ($numberofDuplicates < 1)
{
    return true;
}
 else {
    return FALSE;
}
    }

    /**
     * Insert a record
     * @param string $title title
     * @param string $comments comments
     * -- @param string $userId The user ID
     */



    function insertSingle($msddName,$msddLabel,$msddValue,$defaultValue,$msddsize,$formElementLabel,$labelLayout)
    {
       // $userid = $this->objUser->userId();
        $id = $this->insert(array(
            'multiselectdropdownname' => $msddName,
            'msddoptionlabel' => $msddLabel,
            'msddoptionvalue' => $msddValue,
            'defaultvalue' => $defaultValue,
               'msddsize' => $msddsize,
             'label' => $formElementLabel,
 'labelorientation'=>$labelLayout
        ));
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $category Category
     * -- @param string $userId The user ID
     */
    function updateMenuSize( $msddName, $msddsize)
    {
    //    $userid = $this->objUser->userId();
        $this->update('multiselectdropdownname', $msddName, array(
            'msddsize' => $msddsize
        ));
    }

                     function deleteFormElement($formElementName)
{
   $sql="where multiselectdropdownname like '".$formElementName."'";
    $valueExists = $this->getRecordCount($sql);
if ($valueExists >= 1)
{
        $this->delete("multiselectdropdownname", $formElementName);
    return   true;
}
else
{
 return  false;
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
