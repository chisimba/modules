<?php
/* ----------- data class extends dbTable for tbl_formcatcher------------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_formcatcher
*/
class dbformcatcher extends dbTable
{
    //Context code property
    public $contextCode;
    
    /**
    * Constructor method to define the table
    */
    public function init() {
        parent::init('tbl_formcatcher');
        // Context Code
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
    }
    
    public function getCurrentContext()
    {
        return $this->getAll("WHERE context='" 
          . $this->contextCode . "'");
    }
    
    /**
    * Save method for editing a record in this table
    *@param string $mode: edit if coming from edit, add if coming from add
    */
    public function saveRecord($filename)
    {
        $mode = $this->getParam('mode', 'create');
        //Get the fields that must be in both add and edit
        $title = $this->getParam('title', NULL);
        $email = $this->getParam('email', NULL);
        $description = $this->getParam('description', NULL);
        $usefullpage = $this->getParam('usefullpage', NULL);
        $emailtowhere = $this->getParam('emailtowhere', NULL);
        $id = $this->getParam('id', NULL);
        // if edit use update
        if ($mode=="edit") {
            //Id is only there on edit
            $id=$this->getParam('id', NULL);
            if ( $this->update("id", $id, array(
                   'title' => $title,
                   'email' => $email,
                   'description' => $description,
                   'usefullpage' => $usefullpage,
                   'emailtowhere' => $emailtowhere))) {
                return TRUE;
            } else {
                return FALSE;
            }
        }#if
        // if add use insert
        if ($mode=="create") {
            ///Get the fields that are only there on add
            $context = $this->getParam('context', NULL);
            $link = $this->getParam('link', NULL);
            if ($this->insert(array(
                  'creatorId' => $this->objUser->userId(),
                  'dateCreated' => date("Y/m/d H:i:s"),
                  'modifierId' => $this->objUser->userId(),
                  'dateModified' => date("Y/m/d H:i:s"),
                  'email' => $email,
                  'title' => $title,
                  'link' => $link,
                  'filename' => $filename,
                  'description' => $description,
                  'usefullpage' => $usefullpage,
                  'emailtowhere' => $emailtowhere,
                  'context' => $this->contextCode))) {
                return TRUE;
            } else {
                return FALSE;
            } #if
        }#if
    }#function
} #end of class
?>