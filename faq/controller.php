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
	
    public $objHelp;
	
    public $objLanguage;
	
    public $objDbFaqCategories;
	
    public $objDbFaqEntries;
	
    public $contextId;
	
    public $contextTitle;
	
    public $categoryId;

    /**
    * The Init function
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objHelp =& $this->getObject('helplink','help');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objDbFaqCategories =& $this->getObject('dbfaqcategories');
        $this->objDbFaqEntries =& $this->getObject('dbfaqentries');
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
        $this->setVarByRef('objHelp', $this->objHelp);
        $this->setVarByRef('objLanguage', $this->objLanguage);
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


        //
        //Create root category if neccessary.
        $notCategorizeId= $this->objDbFaqCategories->getNotCategorisedId($this->contextId);
        if (empty($notCategorizeId)) {
            $this->objDbFaqCategories->insertSingle(
                $this->contextId,
                'Not Categorised',
                'admin', //$this->objUser->userName(),
                mktime()
            );
        }
        // Get category from URL
        $this->categoryId = $this->getParam('category', $notCategorizeId);
        $this->setVarByRef('categoryId', $this->categoryId);

        // CategoryDetails
        $categoryDetails = $this->objDbFaqCategories->listSingle($this->contextId, $this->categoryId);
        $this->setVarByRef('categoryDetails', $categoryDetails);


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
                $this->addCategory();
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
              		$categories =  $this->objDbFaqCategories->listAll($this->contextId);
   								$this->setVarByRef('categories', $categories);
   								return "main_tpl.php";
   								break;
 //************************************************************************************************/
            case "view":
            default:
                return $this->view();
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
        public function requiresLogin() 
        {
            return FALSE;

        }

    /**
    * View all FAQ entries.
    */
    public function view()
    {
        // Get all FAQ entries
        $list = $this->objDbFaqEntries->listAll($this->contextId, $this->categoryId);
        $this->setVarByRef('list', $list);

        // Get all the categories
        $categories =  $this->objDbFaqCategories->listAll($this->contextId);
        $this->setVarByRef('categories', $categories);
        return "view_tpl.php";
    }

    /**
    * Add a FAQ entry.
    */
    public function add()
    {
        // Get all the categories
        $categories =  $this->objDbFaqCategories->listAll($this->contextId);
        $this->setVarByRef('categories', $categories);

        return "add_tpl.php";
    }

    /**
    * Confirm add.
    */
    public function addConfirm()
    {
	     $index = $_POST['index'];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $category = $_POST["category"];
        // Insert a record into the database
        $this->objDbFaqEntries->insertSingle(
            $this->contextId,
            $category,
			$index,
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
        $list = $this->objDbFaqEntries->listSingle($id);
        $this->setVarByRef('list', $list);

        // Get all the categories
        $categories =  $this->objDbFaqCategories->listAll($this->contextId);
        $this->setVarByRef('categories', $categories);

        return "edit_tpl.php";
    }

    /**
    * Confirm edit.
    */
    public function editConfirm()
    {
        $id = $this->getParam('id', null);
		$index = $_POST['index'];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $category = $_POST["category"];
        // Update the record in the database
        $this->objDbFaqEntries->updateSingle(
            $id,
			   $index,
            $question,
            $answer,
            $category,
            $this->objUser->userId(),
            mktime()
        );//
        return $this->nextAction('view', array('category'=>$category));
    }

    /**
    * Confirm delete.
    */
    public function deleteConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objDbFaqEntries->deleteSingle($id);
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
    * Add a FAQ category.
    */
    public function addCategory()
    {
    }

    /**
    * Confirm add.
    */
    public function addCategoryConfirm()
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
    public function editCategory()
    {
        $id = $this->getParam('id', null);
        $list = $this->objDbFaqCategories->listSingleId($id);
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
    public function deleteCategoryConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objDbFaqCategories->deleteSingle($id); 
	 $this->objDbFaqEntries->delete("categoryid",$id);
        return $this->nextAction(NULL);
    }












}
?>