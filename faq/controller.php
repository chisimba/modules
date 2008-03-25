<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller class for FAQ module
* @author Jeremy O'Connor , remade by Stelio Macumbe
* @copyright 2004 University of the Western Cape
* $Id$
*/
class faq extends controller
{
    public $objUser;
    
    public $objLanguage;
    
    public $objFaqCategories;
    
    public $objFaqEntries;
    
    public $contextId;
    
    public $contextTitle;
    
    public $categoryId;

    /**
    * The Init function
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objFaqCategories =& $this->getObject('dbfaqcategories');
        $this->objFaqEntries =& $this->getObject('dbfaqentries');
        
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
        
        // Set the error string
        $error = "";
        $this->setVarByRef("error", $error);
        
        // Get the context
        $this->objDbContext = &$this->getObject('dbcontext','context');
        $this->contextCode = $this->objDbContext->getContextCode();
        
        // If we are not in a context...
        if ($this->contextCode == null) {
            $this->contextId = "root";
            $this->setVarByRef('contextId', $this->contextId);
            $this->contextTitle = "Lobby";
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
        // ... we are in a context
        else {
            $this->contextId = $this->contextCode;
            $this->setVarByRef('contextId', $this->contextId);
            $contextRecord = $this->objDbContext->getContextDetails($this->contextCode);
            $this->contextTitle = $contextRecord['title'];
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
        
        $numCategories = $this->objFaqCategories->getNumContextCategories($this->contextId);
        
        // Get category from URL
        $this->categoryId = $this->getParam('category');
        $this->setVarByRef('categoryId', $this->categoryId);
        
        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
        switch($action){
            // Change the category
            case 'changecategory':
                return $this->view();
           // Add an entry
            case "add":
                return $this->add();
            //Add confirm
            case "addconfirm":
                return $this->addConfirm();
            // Edit an entry
            case "edit":
                return $this->edit();
            //Edit confirm
            case "editconfirm":
                return $this->editConfirm();
            // Delete an entry
            case "deleteconfirm":
                return $this->deleteConfirm();
            // Default : view entries
            

            // Add an entry
            case "addcategory": 
                return "add_category_tpl.php";
            // Add Confirm
            case "addcategoryconfirm":
                $this->addCategoryConfirm();
                break;
            // Edit an entry
            case "editcategory":
                $this->editCategory();
                return "edit_category_tpl.php";
            // Edit Confirm
            case "editcategoryconfirm":
                $this->editCategoryConfirm();
                break;
            // Delete an entry
            case "deletecategoryconfirm":
                $this->deleteCategoryConfirm();
                break;
            case "managecategories":
                    $categories =  $this->objFaqCategories->getContextCategories($this->contextId);
                                $this->setVarByRef('categories', $categories);
                                return "main_tpl.php";
                                break;
 //************************************************************************************************/
            case "view":
                return $this->view();
            default:
                return $this->listCategories();
        } // switch

 
    }
     /**
    * 
    * This is a method that overrides the parent class to stipulate whether
    * the current module requires login. Having it set to false gives public
    * access to this module including all its actions.
    *
    * @access public
    * @return bool FALSE
    */
    public function requiresLogin($action) 
    {
        $requiresLogin = array ('add', 'addcategory');
        
        if (in_array($action, $requiresLogin)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function listCategories()
    {
        $categories =  $this->objFaqCategories->getContextCategories($this->contextId);
        $this->setVarByRef('categories', $categories);
        return "main_tpl.php";
    }

    /**
    * View all FAQ entries.
    */
    public function view()
    {
        
        
        // Get all FAQ entries
        $list = $this->objFaqEntries->listAll($this->contextId, $this->categoryId);
        $this->setVarByRef('list', $list);
        
        // Get all the categories
        $categories =  $this->objFaqCategories->getContextCategories($this->contextId);
        $this->setVarByRef('categories', $categories);
        return "view_tpl.php";
    }

    /**
    * Add a FAQ entry.
    */
    public function add()
    {
        // Get all the categories
        $categories =  $this->objFaqCategories->getContextCategories($this->contextId);
        $this->setVarByRef('categories', $categories);
        
        return "add_tpl.php";
    }

    /**
    * Confirm add.
    */
    public function addConfirm()
    {
        $question = $this->getParam("question");
        $answer = $this->getParam("answer");
        $category = $this->getParam("category");
        
        // Insert a record into the database
        $this->objFaqEntries->insertSingle(
            $this->contextId,
            $category,
            $question,
            $answer,
            $this->objUser->userId(),
            mktime()
        );

        return $this->nextAction('view', array('category'=>$category));
    }

    /**
    * Edit a FAQ entry.
    */
    public function edit()
    {
        $id = $this->getParam('id', null);
        $item = $this->objFaqEntries->listSingle($id);
        $this->setVarByRef('item', $item);

        // Get all the categories
        $categories =  $this->objFaqCategories->getContextCategories($this->contextId);
        $this->setVarByRef('categories', $categories);

        return "edit_tpl.php";
    }

    /**
    * Confirm edit.
    */
    public function editConfirm()
    {
        $id = $this->getParam('id');
        $question = $this->getParam('question');
        $answer = $this->getParam('answer');
        $category = $this->getParam('category');
        
        // Update the record in the database
        $this->objFaqEntries->updateSingle(
            $id,
            $question,
            $answer,
            $category,
            $this->objUser->userId(),
            mktime()
        );
        
        return $this->nextAction('view', array('category'=>$category));
    }

    /**
    * Confirm delete.
    */
    public function deleteConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objFaqEntries->deleteSingle($id);
        return $this->nextAction('view', array('category'=>$this->categoryId));
    }

     /**
     * Method to load an HTML element's class.
     * @param string $name The name of the element
     * @return The element object
     */
     public function loadHTMLElement($name)
     {
         return $this->loadClass($name, 'htmlelements');
     }

     /**
     * Method to get a new HTML element.
     * @param string $name The name of the element
     * @return The element object
     */
     public function &newHTMLElement($name)
     {
         return $this->newObject($name, 'htmlelements');
     }

     /**
     * Method to get an HTML element.
     * @param string $name The name of the element
     * @return The element object
     */
     public function getHTMLElement($name)
     {
         return $this->getObject($name, 'htmlelements');
     }
     
    

    /**
    * Confirm add.
    */
    public function addCategoryConfirm()
    {
        $categoryName = $this->getParam("category");
        
        if (trim($categoryName) == '') {
            return $this->nextAction('addcategory', array('error'=>'nothingentered'));
        } else {
            // Insert the category into the database
            $result = $this->objFaqCategories->insertSingle(
                $this->contextId, 
                $categoryName, 
                $this->objUser->userId()
            );
            return $this->nextAction(NULL, array('message'=>'categoryadded', 'result'=>$result));
        }
    }
    
    /**
    * Edit a FAQ entry.
    */
    public function editCategory()
    {
        $id = $this->getParam('id', null);
        $list = $this->objFaqCategories->listSingleId($id);
        $this->setVarByRef('list', $list);
    }

    /**
    * Confirm edit.
    */
    public function editCategoryConfirm()
    {
        $id = $this->getParam('id', null);
        $categoryId = $_POST["category"];
        // Update the record in the database
        $this->objFaqCategories->updateSingle(
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
    public function deleteCategoryConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objFaqCategories->deleteSingle($id); 
        $this->objFaqEntries->delete("categoryid",$id);
        return $this->nextAction(NULL);
    }












}
?>