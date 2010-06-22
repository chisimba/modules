<?
/* -------------------- gradebook2 class extends controller ---------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * controller class for the gradebook2 module
 * authors: Paul Mungai
 * date: 2010 06 22
 */

class gradebook2 extends controller {
    //user object - security and access rights validation
    public $objUser;

    //language object - multilingularity
    public $objLanguage;

    //form object
    public $objForm;

    //heading object
    public $objHeading;

    /**
     * initilization function - declaration of required objects
     */
    public function init() {
        $this->objUser=& $this->getObject('user','security');
        $this->objLanguage = & $this->getObject('language', 'language');
        $this->objForm = & $this->getObject('form','htmlelements');
        $this->objHeading =& $this->getObject('htmlheading','htmlelements');
    }

    /**
     * dispatch() function for the gradebook module:
     * providing standard controlls for the module's logic and execution
     */
    public function dispatch($action) {
        //get the parameter from the querystring
        $action = $this->getParam("action", NULL);
    }
}
?>
