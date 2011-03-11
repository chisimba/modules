<?php

$id = "";
/**
 *
 *  PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   apo (Academic Planning Office)

 *
  =
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end security check

class apo extends controller {

    function init() {
        $this->loadclass('link', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLog->log();
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objUser = $this->getObject('user', 'security');
        $this->objUtils = $this->getObject('userutils');
        $this->documents = $this->getObject('dbdocuments');
        $this->objformdata = $this->getObject('dbformdata');
        $this->mode = $this->objSysConfig->getValue('MODE', 'apo');
        $this->faculties = $this->getObject('dbfaculties');
        $this->objFormatting = $this->getObject('formatting');
    }

    /**
     *
     * The standard dispatch method for the apo module.
     * The dispatch method uses methods determined from the action
     * parameter of the querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     * @access public
     * @param $action
     * @return A call to the appropriate method
     *
     */
    public function dispatch($action) {
        /*
         * Convert the action into a method (alternative to
         * using case selections)
         */
        $this->setLayoutTemplate("apo_layout_tpl.php");
        $method = $this->getMethod($action);
        /*
         * Return the template determined by the method resulting
         * from action
         */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__' . $action;
        } else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__' . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home() {
        $selected = "unapproved";
        $documents = $this->documents->getdocuments(0, 10, $this->mode);

        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        return "home_tpl.php";
    }

    public function __newdocument() {
        $selected = $this->getParam('selected');
        $id = $this->getParam("docid");
        $faculties = $this->faculties->getFaculties();

        $mode = "new";

        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        $this->setVarByRef("departments", $faculties);

        return "addeditdocument_tpl.php";
    }

    function __registerdocument() {

        $errormessages = $this->getParam('errormessages');
        $date = $this->getParam('date_created');
        $number = $this->getParam('number');
        $dept = $this->getParam('department');

        if ($dept == '') {
            $errormessages[] = "Fill in department";
        }
        $title = $this->getParam('title');

        if ($title == 'title') {
            $errormessages[] = "Fill in course title";
        }
        $selectedfolder = $this->getParam('parentfolder');

        $refno = $number . date("Y");
        $contact = $this->getParam('contact', '');
        if ($contact == null || $contact == '') {
            $contact = $this->objUser->fullname();
        }
        $telephone = $this->getParam('telephone');


        if ($telephone == '') {
            $errormessages[] = "Fill in telephone";
        }

        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("department", $dept);
            $this->setVarByRef("contact", $contact);
            $this->setVarByRef("telephone", $telephone);
            $this->setVarByRef("title", $title);
            $this->setVarByRef("number", $number);

            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("action", $action);

            return "addeditdocument_tpl.php";
        }
        $status = $this->getParam('status');
        if ($status == '' || $status == NULL) {
            $status = "0";
        }
        $currentuserid = $this->objUser->userid();
        $groupid = "0";
        $selectedfolder = "/";
        $version = $this->getParam('version', "1");

        $refNo = $this->documents->addDocument(
                        $date,
                        $refno,
                        $dept,
                        $contact,
                        $telephone,
                        $title,
                        $groupid,
                        $selectedfolder,
                        $currentuserid,
                        $mode = "apo",
                        $approved = "N",
                        $status = "0",
                        $currentuserid,
                        $version,
                        $ref_version
        );


        $documents = $this->documents->getdocuments(0, 10, $this->mode);
        $this->setVarByRef("documents", $documents);
        $selected = "unapproved";
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        $this->setVarByRef("refno", $refNo);
    
        return "home_tpl.php";
    }

    /**
     *
     * The standard dispatch method for the apo module.
     * The dispatch method uses methods determined from the action
     * parameter of the querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     * @access public
     * @param $action
     * @return A call to the appropriate method
     *
     */
    public function __facultymanagement() {
        $selected = "facultymanagement";
        $faculties = $this->faculties->getFaculties(0, 10, $this->mode);
        $this->setVarByRef("faculties", $faculties);
        $this->setVarByRef("selected", $selected);

        return "facultymanagement_tpl.php";
    }

    /*
     * This method is used to add a new faculty
     * @param none
     * @access public
     * @return the form that will be used to capture the information for the new
     * faculty
     */
    public function __newfaculty() {
        $selected = $this->getParam('selected');
        $mode = "new";
        $action = "registerfaculty";
        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);

        return "addeditfaculty_tpl.php";
    }

    /*
     * This method is used to add a new faculty
     * @param none
     * @access public
     * @return the form that will be used to edit the information for the faculty
     */
    public function __editfaculty() {
        $selected = $this->getParam('selected');
        $mode = "edit";
        $action = "editfaculty";
        $id = $this->getParam('id');
        $data = $this->faculties->getFaculty($id);

        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("faculties", $data);
        $this->setVarByRef("id", $id);

        return "addeditfaculty_tpl.php";
    }

    public function __registerfaculty() {
        $name = $this->getParam('faculty');
        $contact = $this->getParam('contact');
        $telephone = $this->getParam('telephone');

        if ($this->faculties->exists($name)) {
            $errormessage = "Faculty Name already exists";
            die();
        } else {
            $parentid = $this->getParam('parentfolder');
            $fac = $this->faculties->getFaculty($parentid);
            $parent = $fac['path'];
            $path = "";
            if ($parent) {
                $path .= $parent . '/' . $name;
            } else {
                $path .= $name;
            }
            $data = array("name" => $name, "contact" => $contact, "telephone" => $telephone);

            $this->faculties->addFaculty($data, $path);

            return $this->nextAction('facultymanagement');
        }
    }

    public function __updatefaculty() {
        $faculty = $this->getParam('faculty');
        $contact = $this->getParam('contact');
        $telephone = $this->getParam('telephone');

        if (empty($contact)) {
            // using this user id, get the full name and compare it with contact person!
            $contact = $this->objUser->fullname($userid);
        }

        $data = array("name" => $faculty, "contact_person" => $contact, "telephone" => $telephone, "userid" => $this->objUser->userId());
        $this->faculties->editFaculty($this->getParam('id'), $data);

        return $this->nextAction('facultymanagement', array('folder' => '0'));
    }

    public function __deletefaculty() {
        $id = $this->getParam('id');
        $this->faculties->deleteFaculty($id);

        return $this->nextAction('facultymanagement', array('folder' => '0'));
    }

    /*
     * This method is used to display the information for a document on a pdf.
     * @param none
     * @access public
     * @return the data exported to a pdf
     */

    public function __makepdf() {
        $id = $this->getParam('id');
        $all = $this->getParam('all');
        $overview = $this->getParam('overview');
        $rulesandsyllabusone = $this->getParam('rulesandsyllabusone');
        $rulesandsyllabustwo = $this->getParam('rulesandsyllabustwo');
        $subsidy = $this->getParam('subsidy');
        $outcomesandassessmentone = $this->getParam('outcomesandassessmentone');
        $outcomesandassessmenttwo = $this->getParam('outcomesandassessmenttwo');
        $outcomesandassessmentthree = $this->getParam('outcomesandassessmentthree');
        $resources = $this->getParam('resources');
        $collaborations = $this->getParam('collaborations');
        $review = $this->getParam('review');
        $comments = $this->getParam('comments');
        $feedback= $this->getParam('feedback');

        $documents = $this->documents->getDocument($id);

        $myid = $this->objUser->userId();
        //$documents = $this->documents->getdocuments(0, 20, $this->mode, "N", $myid);
        $createPdf = False;
        $fullnames = $this->objUser->fullName(). "'s Document";
        if(count($documents) > 1) {
            $fullnames .= "s";
        }

        if (!empty($documents)) {
            $createPdf = True;
            // get all the data for these documents
            $text1 = "";

            //foreach ($documents as $row) {
            $row = $documents; // in case i need to modify my code late to use foreach.
                if($overview == 'on' || $all == 'on') {
                    $overview = $this->objformdata->getFormData("overview", $row['id']);
                    $overviewTable = $this->objFormatting->getOviewviewTable($overview);
                }
                if($rulesandsyllabusone== 'on' || $all == 'on') {
                    $rulesandsyllabusone = $this->objformdata->getFormData("rulesandsyllabusone", $row['id']);
                    $rulesAndSyllabusoneTable = $this->objFormatting->getRulesAndSyllabusOne($rulesandsyllabusone);
                }
                if($rulesandsyllabustwo== 'on' || $all == 'on') {
                    $rulesandsyllabustwo = $this->objformdata->getFormData("rulesandsyllabustwo", $row['id']);
                    $rulesAndSyllabustwoTable = $this->objFormatting->getRulesAndSyllabusTwo($rulesandsyllabustwo);
                }
                if($subsidy== 'on' || $all == 'on') {
                    $subsidyRequirements = $this->objformdata->getFormData("subsidyrequirements", $row['id']);
                    $subsidyRequirementsTable = $this->objFormatting->getSubsidyRequirements($subsidyRequirements);
                }
                if($outcomesandassessmentone == 'on' || $all == 'on') {
                    $outcomesandassessmentone = $this->objformdata->getFormData("outcomesandassessmentone", $row['id']);
                    $outcomesandassessmentoneTable = $this->objFormatting->getOutcomesAndAssessmentsOne($outcomesandassessmentone);
                }
                if($outcomesandassessmenttwo == 'on' || $all == 'on') {
                    $outcomesandassessmenttwo = $this->objformdata->getFormData("outcomesandassessmenttwo", $row['id']);
                    $outcomesandassessmenttwoTable = $this->objFormatting->getOutcomesAndAssessmentsTwo($outcomesandassessmenttwo);
                }
                if($outcomesandassessmentthree == 'on' || $all == 'on') {
                    $outcomesandassessmentthree = $this->objformdata->getFormData("outcomesandassessmentthree", $row['id']);
                    $outcomesandassessmentthreeTable = $this->objFormatting->getOutcomesAndAssessmentsThree($outcomesandassessmentthree);
                }
                if($resources == 'on' || $all == 'on') {
                    $resources = $this->objformdata->getFormData("resources", $row['id']);
                    $resourcesTable = $this->objFormatting->getResources($resources);
                }
                if($collaborations == 'on' || $all == 'on') {
                    $collaborations = $this->objformdata->getFormData("collaborationandcontracts", $row['id']);
                    $colloborationsTable = $this->objFormatting->getCollaborationAndContracts($collaborations);
                }
                if($review == 'on') {
                    $review = $this->objformdata->getFormData("review", $row['id']);
                    $reviewTable = $this->objFormatting->getReview($review);
                }
                if($comments == 'on' || $all == 'on') {
                    $comments = $this->objformdata->getFormData("comments", $row['id']);
                    $commentsTable = $this->objFormatting->getComments($comments);
                }
                if($feedback == 'on' || $all == 'on') {
                    $feedback = $this->objformdata->getFormData("feedback", $row['id']);
                    $feedbackTable = $this->objFormatting->getFeedback($feedback);
                }

                //get the pdfmaker classes
                $text1 .= '<h1>' . $fullnames . "</h1><br><br>\r\n"
                      . $overviewTable
                      . $rulesAndSyllabusoneTable
                      . $rulesAndSyllabustwoTable
                      . $subsidyRequirementsTable
                      . $outcomesandassessmentoneTable
                      . $outcomesandassessmenttwoTable
                      . $outcomesandassessmentthreeTable
                      . $resourcesTable
                      . $colloborationsTable
                      . $reviewTable
                      . $contactTable
                      . $commentsTable
                      . $feedbackTable;
            }
        //}
        $objPdf = $this->getObject('tcpdfwrapper', 'pdfmaker');
        //Write pdf
        $objPdf->initWrite();
        if ($createPdf == True) {
            $objPdf->partWrite($text1);
        }
        if ($createPdf == True) {
            return $objPdf->show();
        } else {
            echo 'Nothing to display in pdf.';
        }
    }

    /*
     * This method is used to determine the content that the user wants to display
     * in a pdf that they will want to download or print.
     * @access public
     * @param $id The id of the document that the user would like to print
     * @return template page where the user can customize the data
     */
    public function __selectpdf() {
        $id=$this->getParam('id');
        $document = $this->documents->getDocument($id);

        $this->setVarByRef("id", $id);
        $this->setVarByRef("document", $document);

        return "selectpdf_tpl.php";
    }
}
?>