<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller class for FAQ Admin module
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
* $Id$
*/
class faqadmin extends controller
{
    public $objUser;
    //var $objHelp;
    public $objLanguage;
    public $objDbFaqCategories;
    public $contextId;
    public $contextTitle;
    public $categoryId;

    /**
    * The Init function
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        //$this->objHelp =& $this->getObject('helplink','help');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objDbFaqCategories =& $this->getObject('dbfaqcategories'); 
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        //$this->objLog->logOncePerSession = TRUE;
        //Log this module call
        $this->objLog->log();
    }
    
    /**
    * The dispatch funtion
    * @param string $action The action
    */
    public function dispatch($action=NULL)
    {
        // Set the layout template for faq - includes the context menu
        $this->setLayoutTemplate("context_layout_tpl.php");
        $this->setVarByRef('objUser', $this->objUser);
        //$this->setVarByRef('objHelp', $this->objHelp);
        $this->setVarByRef('objLanguage', $this->objLanguage);
        // Get the context
        $this->objDbContext = &$this->getObject('dbcontext','context');
        $this->contextCode = $this->objDbContext->getContextCode();
        // If we are not in a context...
        if ($this->contextCode == null) {
            $this->contextId = "root";
            $this->setVarByRef('contextid', $this->contextId);
            $this->contextTitle = "Lobby";
            $this->setVarByRef('contexttitle', $this->contextTitle);
        }
        // ... we are in a context
        else {
            $this->contextId = $this->contextCode;
            $this->setVarByRef('contextid', $this->contextId);
            $contextRecord = $this->objDbContext->getContextDetails($this->contextCode);
            $this->contextTitle = $contextRecord['title'];
            $this->setVarByRef('contexttitle', $this->contextTitle);
        }
        //
        //Create root category if neccessary.
        $list = $this->objDbFaqCategories->listSingle($this->contextId, 'Not Categorised');
        if (empty($list)) {
            $this->objDbFaqCategories->insertSingle(
                $this->contextId, 
                'Not Categorised', 
                'admin', //$this->objUser->userName(),
                mktime()
            );
        }
       //check for action
       switch($action){
            // Add an entry
            case "add": 
                $this->add();
                return "add_tpl.php";
            // Add Confirm
            case "addconfirm":
                $this->addConfirm();
                break;
            // Edit an entry
            case "edit":
                $this->edit();
                return "edit_tpl.php";
            // Edit Confirm
            case "editconfirm":
                $this->editConfirm();
                break;
            // Delete an entry
            case "deleteconfirm":
                $this->deleteConfirm();
                break;
            // Default : view entries
            default:
        } // switch
        // Get all the categories
        $categories =  $this->objDbFaqCategories->listAll($this->contextId);
        $this->setVarByRef('categories', $categories);
        return "main_tpl.php";
    }
    
    /**
    * Add a FAQ entry.
    */
    public function add()
    {
    }

    /**
    * Confirm add.
    */
    public function addConfirm()
    {
        $this->categoryId = $_POST["category"];
        // Insert the category into the database
        $this->objDbFaqCategories->insertSingle(
            $this->contextId, 
            $this->categoryId, 
            $this->objUser->userName(),
            mktime()
        );
        return $this->nextAction(NULL);
    }
    
    /**
    * Edit a FAQ entry.
    */
    public function edit()
    {
        $id = $this->getParam('id', null);
        $list = $this->objDbFaqCategories->listSingleId($id);
        $this->setVarByRef('list', $list);
    }

    /**
    * Confirm edit.
    */
    public function editConfirm()
    {
        $id = $this->getParam('id', null);
        $categoryId = $_POST["category"];
        // Update the record in the database
        $this->objDbFaqCategories->updateSingle(
            $id, 
            $categoryId, 
            $this->objUser->userId(),
            mktime()
        );
        return $this->nextAction(NULL);
    }

    /**
    * Confirm delete.
    */
    public function deleteConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objDbFaqCategories->deleteSingle($id); 
        return $this->nextAction(NULL);
    }
}    
?>
