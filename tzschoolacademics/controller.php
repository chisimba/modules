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

    public function init() {

        $this->loadClass('user', 'security');
        $this->lang = $this->getObject('language', 'language');
        $this->user = $this->getObject('user', 'security');
    }

    public function dispatch($action) {

        $action = $this->getParam('action', 'main');
        $this->setLayoutTemplate('main_layout_tpl.php');
        switch ($action) {
            case 'profile':
                return 'demo_profile_tpl.php';
                break;
            case 'setup':
                return 'demo_setup_tpl.php';
                break;

            default:
                return 'demo_home_tpl.php';
                break;
        }

        ////start page redirection
        /**
          switch ($action) {
          case 'Admission':
          echo $action;


          break;

          case 'Results':
          echo $action;
          break;

          case 'Report':
          echo $action;
          break;



          default:
          //return 'academic_report_home_tpl.php';
          return 'academicsmain_tpl.php';
          break;


          } */
        ///end of dispatch
    }

    function __getMethod(& $action) {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

    function __validAction(& $action) {
        if (method_exists($this, "__" . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function __actionError() {
        /* echo 'error';
          $this->setVar('str', "<h3>"
          . $this->objLanguage->languageText("phrase_unrecognizedaction")
          .": " . $action . "</h3>");
          return 'dump_tpl.php'; */
        echo 'error';
    }

    private function __main() {
        return 'academicsmain_tpl.php';
    }

    public function requiresLogin($action) {
        return false;
    }

    /*
     * method to redirect actions when report menus are accessed
     * @access public
     * @param string action   :An action received by displatch method of the controller
     *
     * @author: mhoja charles
     * @email:  charlesmdack@gmail.com
     */

    function report_actions($action) {
        echo $action;
        exit;
        if (strcmp($action, 'Report')) {
            ////start page redirection
            $action2 = $this->getParam('action2', 'main'); ///getting action2
            echo $action2;
            exit;
            switch ($action2) {

                case 'StudentResults':

                    $middle_content = 'StudentResults';
                    $this->setVar('content', $middle_content);
                    return 'academic_report_home_tpl.php';
                    break;

                case 'ClassResults':

                    $middle_content = 'ClassResults';
                    $this->setVar('content', $middle_content);
                    return 'academic_report_home_tpl.php';
                    break;

                case 'SubjectResults':

                    $middle_content = 'ClassResults';
                    $this->setVar('content', $middle_content);
                    break;

                case 'FailuredStudents':

                    $middle_content = 'ClassResults';
                    $this->setVar('content', $middle_content);
                    break;

                case 'BestStudents':

                    $middle_content = 'ClassResults';
                    $this->setVar('content', $middle_content);
                    break;

                case 'StudentReport':

                    $middle_content = 'ClassResults';
                    $this->setVar('content', $middle_content);
                    break;

                case 'StudentList':
                    break;


                default:
                    //  echo 'bdbdbd';
                    $middle_content = 'DATA HERE';
                    $this->setVar('content', $middle_content);
                    return 'academic_report_home_tpl.php';
                    break;
            }
        }
        //loading academic home page by default
        else {
            return 'academicsmain_tpl.php';
        }
    }

}

?>
