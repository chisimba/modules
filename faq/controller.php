<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller class for FAQ module
* @author Jeremy O'Connor
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
    * @return string The content template file
    */
    public function dispatch($action=NULL)
    {
        // Set the layout template for faq - includes the context menu
        $this->setLayoutTemplate("context_layout_tpl.php");
        // 1. ignore action at moment as we only do one thing - say hello
        // 2. load the data object (calls the magical getObject which finds the
        //    appropriate file, includes it, and either instantiates the object,
        //    or returns the existing instance if there is one. In this case we
        //    are not actually getting a data object, just a helper to the
        //    controller.
        // 3. Pass variables to the template
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
            $this->setVarByRef('contextid', $this->contextId);
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
        $this->setVarByRef('categoryid', $this->categoryId);

        // CategoryDetails
        $categoryDetails = $this->objDbFaqCategories->listSingle($this->contextId, $this->categoryId);
        $this->setVarByRef('categoryDetails', $categoryDetails);


        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
        switch($action){
            // Change the category
            case 'changecategory':
                //$this->categoryId = $this->getParam('newcategory', 'Not Categorised');
                return $this->view();
            // Create a new category
            /*
            case 'createcategory':
                $_categoryId = $_POST["newcategory"];
                $list = $this->objDbFaqCategories->listSingle($this->contextId, $_categoryId);
                // Check if category already exists
                if (!empty($list)) {
                    $error = "Category '" . $_categoryId . "' already exists. Type another category.";
                }
                else {
                    // Insert the category into the database
                    $this->categoryId = $_categoryId;
                    $this->objDbFaqCategories->insertSingle(
                        $this->contextId,
                        $this->categoryId,
                        $this->objUser->userName(),
                        mktime()
                    );
                }
                return $this->view();
            */
            // Add an entry
            case "add":
                return $this->add();
            case "addconfirm":
                return $this->addConfirm();
            // Edit an entry
            case "edit":
                return $this->edit();
            case "editconfirm":
                return $this->editConfirm();
            // Delete an entry
            case "deleteconfirm":
                return $this->deleteConfirm();
            // Default : view entries
            case "view":
            default:
                return $this->view();
        } // switch

        //return "faq_tpl.php";
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
     public function &getHTMLElement($name)
     {
         return $this->getObject($name, 'htmlelements');
     }

}
?>
