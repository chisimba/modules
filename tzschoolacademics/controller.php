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
 * @author    Academic module team
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
        switch ($action){
            //case your_action:do something
            //                  break;
            case  'register_student':
               return  'student_registration_tpl.php';
                 break;
            case 'upload_result':
               return 'load_upload_form_tpl.php';

            
            default:return 'academics_home_tpl.php';
                break;
            
        }
    }

    public function  requiresLogin($action) {
        return false;
    }
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
