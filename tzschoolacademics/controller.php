<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Controller class for academic module
 *
 * @category  Chisimba
 * @package   SMIS Academics
 * @author    Boniface Chacha <bonifacechacha@gmail.com>
 */
class tzschoolacademics extends controller {

  public $lang;

  private $user;

  public function  init() {

        $this->loadClass('user','security');
        $this->lang=$this->getObject('language','language');
        $this->user=$this->getObject('user','security');
    }

    public function dispatch(){
        //return 'AddEdit_tpl.php';
        $action=$this->getParam('action', 'main');
        $method=$this->__getMethod($action);
        return $this->$method();

    }

    function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

    function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function __actionError()
    {
       /* echo 'error';
        $this->setVar('str', "<h3>"
          . $this->objLanguage->languageText("phrase_unrecognizedaction")
          .": " . $action . "</h3>");
        return 'dump_tpl.php';*/
        echo 'error';
    }

    private function __main(){
        return 'mainacademics_tpl.php';
    }

    public function  requiresLogin($action) {
        return false;
    }

}
?>
