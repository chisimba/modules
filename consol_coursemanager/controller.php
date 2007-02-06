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
    		$this->action = 'addcourse';
        //$this->setLayoutTemplate('layout_tpl.php');
        switch ($this->action) {
            //Default to view and display view template
            case null:
            	die("this functionality will come soon");
            break;
            case 'addcourse':
              return 'addCourse_tpl.php';
            break;
            default:
            break;
        }
    }

}
?>
