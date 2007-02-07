<?php
/* -------------------- helloworld class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
*
* Controller for consol_coursemanager
*
* @author Dean Van Niekerk
* $controller.php,v 1.0
*
*/
class consol_coursemanager extends controller
{

    /**
    *
    * @var object $objLanguage String to hold the language object
    *
    */
    public $objLanguage;

    /**
    *
    * @var object $objBab String to hold the dictionary lookup object
    *
    */
    public $objDict;


    /**
    *
    * @var string $action The action parameter from the querystring
    *
    */
  //  pubic $action;

    /**
    *
    * Standard init class to instantiate the core objects and grab
    * the action parameter.
    *
    */
    public function init()
    {
        //Retrieve the action parameter from the querystring
        $this->action = $this->getParam('action', Null);
        //Create an instance of the language object
        $this->objLanguage = &$this->getObject("language", "language");
        //Create an instance of the bablefish object
        //$this->objDict = & $this->getObject('diclookup');
    }

    /**
    *
    * Standard dispatch method of controller
    *
    */
    public function dispatch($action)
    {
    		//$this->action = 'addcourse';
        //$this->setLayoutTemplate('layout_tpl.php');
        switch ($this->action) {
            //Default to view and display view template
            case null:
               //getting the value to be used from insertert of category
               $name = $this->getParam('catname', NULL);
               $image = $this->getParam('image', NULL);
               
               $objDbCategories	=& $this->getObject('dbconsol_categories');
               $categories = $objDbCategories->listAll();
        			$this->setVarByRef('categories',  $categories);
        			$num = count($categories);
        			die("the number is ".$num);
    				if(empty($categories)){
			    	$order = 1;
			    	}

			    	
               
               
               
             	die("name = ".$name." image = ".$image."  this functionality will come soon");
            break;
            case 'add':
            	die("this functionality will come soon");
            break;
            case 'addcatgery':
              return 'addCourseCategory_tpl.php';
            break;
            case 'addcourse':
              return 'addCategory_tpl.php';
            break;
            default:
            break;
        }
    }

}
?>
